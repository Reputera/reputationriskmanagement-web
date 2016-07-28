<?php

namespace App\Services\Instance;

use App\Entities\Company;
use App\Entities\Instance;
use App\Entities\Region;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ReputationChange
{
    /**
     * Gets the reputation change for a particular company between two dates.
     *
     * @param Company $company
     * @param Carbon $start
     * @param Carbon $end
     * @return float
     */
    public function forCompanyBetween(Company $company, Carbon $start, Carbon $end)
    {
        $builder = $this->buildInitialQuery($company, $start, $end);

        return $this->calculateChange($builder);
    }

    public function forCompetitorsBetween(Company $company, Carbon $start, Carbon $end)
    {
        $competitorsArray = $company->competitors->pluck('id')->all();
        $competitorsArray[] = $company->id;
        $builder = Instance::whereIn('company_id', $competitorsArray)
            ->dailyScaledCompanyRiskScore($company)
            ->whereRaw("start between '{$start->toDateString()} 00:00:00' and '{$end->toDateString()} 23:59:59'")
            ->orderByRaw('start_date ASC');

        return $this->calculateChange($builder);
    }

    /**
     * Gets the reputation change for a particular company for a region between two dates.
     *
     * @param Company $company
     * @param Region $region
     * @param Carbon $start
     * @param Carbon $end
     * @param $vectorId
     * @return float
     */
    public function forCompanyAndRegionBetween(Company $company, Region $region, Carbon $start, Carbon $end, $vectorId = null)
    {
        $builder = $company->instances()
            ->getRelated()
            ->dailyScaledCompanyRiskScore($company)
            ->whereRaw("start between '{$start->toDateString()} 00:00:00' and '{$end->toDateString()} 23:59:59'")
            ->orderByRaw('start_date ASC');
        $builder->join('instance_country', 'instance_country.instance_id', '=', 'instances.id');
        $builder->join('countries', 'countries.id', '=', 'instance_country.country_id');
        $builder->where('countries.region_id', $region->id);

        if($vectorId) {
            $builder->where('instances.vector_id', '=', $vectorId);
        }

        return $this->calculateChange($builder);
    }

    /**
     * @param Company $company
     * @param Carbon $start
     * @param Carbon $end
     * @return Builder
     */
    protected function buildInitialQuery(Company $company, Carbon $start, Carbon $end): Builder
    {
        // We initially need to make a query to populate the instance scores (reputation score) grouped by the
        // date (YYYY-MM-DD) of the start column. They way the the datetimes (dates withe hours/minutes/seconds)
        // do not try to group.
        return $company->instances()->getRelated()->dailyScaledCompanyRiskScore($company)
            ->whereRaw("start between '{$start->toDateString()} 00:00:00' and '{$end->toDateString()} 23:59:59'")
            ->orderByRaw('start_date ASC');
    }

    /**
     * @link http://www.csgnetwork.com/percentchangecalc.html
     * @link http://math.stackexchange.com/questions/716767/how-to-calculate-the-percentage-of-increase-decrease-with-negative-numbers
     *
     * @param $builder
     * @return float
     */
    protected function calculateChange($builder)
    {
        $results = DB::select("{$builder->toSql()}", $builder->getBindings());
        $finalScores = [];
        foreach ($results as $key => $result) {
            if (!array_key_exists($key + 1, $results)) {
                continue;
            }
            $originalNumber = $result->company_risk_scores;
            $secondNumber = $results[$key + 1]->company_risk_scores;
//            Multiply by (200 / 100 = 50) in order to scale for 0 to 200
            $finalScores[] = (($secondNumber - $originalNumber) / $originalNumber) * 50;
        }

        if (!$finalScores) {
            return 'N/A';
        }

        return (int) (array_sum($finalScores) / count($finalScores));
    }
}
