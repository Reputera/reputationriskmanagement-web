<?php

namespace App\Entities;

use App\Http\Queries\Instance as InstanceQuery;
use App\Http\QueryFilter;
use App\Services\Vendors\RecordedFuture\InstanceApiResponseQueue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * App\Entities\Company
 *
 * @property integer $id
 * @property string $name
 * @property string $stock_symbol
 * @property string $entity_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Industry[] $industries
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Company[] $competitors
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Company whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Company whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Company whereStockSymbol($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Company whereEntityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Company whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Company extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'stock_name', 'entity_id'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function industries()
    {
        return $this->belongsToMany(Industry::class);
    }

    public function competitors()
    {
        return $this->belongsToMany(Company::class, 'company_competitor', 'company_id', 'competitor_company_id');
    }

    public static function boot()
    {
        Company::created(function ($company) {
            CompanyVectorColor::populateCompanyColors($company);
        });
        parent::boot();
    }

    /**
     * Queues the responses from the Recorded Future API for a given number of hours.
     *
     * @param int $hours
     */
    public function queueInstancesHourly(int $hours = 1)
    {
        app(InstanceApiResponseQueue::class)->processHourly($this, $hours);
    }

    /**
     * Queues the responses from the Recorded Future API for a given number of days.
     *
     * @param int $days
     */
    public function queueInstancesDaily(int $days = 1)
    {
        app(InstanceApiResponseQueue::class)->processDaily($this, $days);
    }

    /**
     * @param InstanceQuery $filter
     * @return int
     */
    public function averageRiskScore(InstanceQuery $filter): int
    {
        $builder = app(Instance::class)->filter($filter)->companyRiskScore($this);

        if ($averageRiskScores = $builder->pluck('company_risk_scores')->toArray()) {
            return (int) round(array_sum($averageRiskScores) / count($averageRiskScores));
        }

        return 0;
    }

    /**
     * @param InstanceQuery $filter
     * @return int
     */
    public function competitorsAverageRiskScore(InstanceQuery $filter): int
    {
        $builder = app(Instance::class)->filter($filter)->competitorRiskScoreForCompany($this);
        
        if ($averageRiskScores = $builder->pluck('company_risk_scores')->toArray()) {
            return (int) round(array_sum($averageRiskScores) / count($averageRiskScores));
        }

        return 0;
    }

    /**
     * Gets the reputation change for a particular company between two dates. The way this is done is rather complex.
     *
     * @link http://www.csgnetwork.com/percentchangecalc.html
     * @link http://math.stackexchange.com/questions/716767/how-to-calculate-the-percentage-of-increase-decrease-with-negative-numbers
     * @param Carbon $start
     * @param Carbon $end
     * @return float
     */
    public function reputationChangeByDate(Carbon $start, Carbon $end): float
    {
        // We initially need to make a query to populate the instance scores (reputation score) grouped by the
        // date (YYYY-MM-DD) of the start column. They way the the datetimes (dates withe hours/minutes/seconds)
        // do not try to group.
        $builder = (new Instance)->dailyCompanyRiskScore($this)
            ->whereRaw("start between '{$start->toDateString()} 00:00:00' and '{$end->toDateString()} 23:59:59'")
            ->orderByRaw('start_date ASC');

        $results = DB::select("{$builder->toSql()}", $builder->getBindings());

        $finalScores = [];
        foreach ($results as $key => $result) {
            if (!array_key_exists($key + 1, $results)) {
                continue;
            }

            $originalNumber = $result->company_risk_scores;
            $secondNumber = $results[$key + 1]->company_risk_scores;
            if (($originalNumber - $secondNumber) > 0) {
                $value = ($originalNumber - $secondNumber) / $originalNumber;
            } else {
                $value = ($secondNumber - $originalNumber) / $originalNumber;
            }

            if ($originalNumber > $secondNumber && ($originalNumber > 0)) {
                $value = -1 * $value;
            }

            $finalScores[] = $value * 100;
        }

        if (!$finalScores) {
            return 0.0;
        }

        return (float) (array_sum($finalScores) / count($finalScores));
    }
}
