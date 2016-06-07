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
     * @param Carbon $start
     * @param Carbon $end
     * @return float
     */
    public function reputationChangeByDate(Carbon $start, Carbon $end)
    {
        $days = $start->diffInDays($end);

        // We initially need to make a query to populate the instance scores (reputation score) grouped by the
        // date (YYYY-MM-DD) of the start column. They way the the datetimes (dates withe hours/minutes/seconds)
        // do not try to group.
        $builder = (new Instance)->dailyCompanyRiskScore($this)
            ->whereRaw("start between ('{$start->toDateString()}' - interval {$days} day ) and '{$start->toDateString()}'")
            ->orderByRaw('start_date ASC');

        // For speed and because we want to use this same data set over and over (for this "process"), we place the
        // results into a temp table. This table automatically destroys itself after the session ends.
        DB::select(DB::raw("
            CREATE TEMPORARY TABLE IF NOT EXISTS temp_daily_scores_and_start_dates AS
            (
              {$builder->toSql()}
            );
            "), $builder->getBindings());

        // Next we want to create an exact copy of the taem table, again for speed, but also so because we want want to
        // query on this table to get the "next" company risk score.
        DB::select(DB::raw("
            CREATE TEMPORARY TABLE IF NOT EXISTS temp_daily_scores_and_start_dates_copy AS (
              SELECT * FROM temp_daily_scores_and_start_dates
            );
        "));

        // Here is where we are making use of the two temp tables. This allows us to get the current risk score
        // for each day using the "temp_daily_scores_and_start_dates" temp table and using the
        // "temp_daily_scores_and_start_dates_copy" to get the next days risk score.
        // This will allow us to compare the two days and get the percentage of change.
        $results = DB::select(DB::raw("
            SELECT *, (
              SELECT company_risk_scores from temp_daily_scores_and_start_dates_copy where temp_daily_scores_and_start_dates_copy.start_date > temp_daily_scores_and_start_dates.start_date order by temp_daily_scores_and_start_dates_copy.start_date asc limit 1
            ) AS next_company_risk_scores
            FROM temp_daily_scores_and_start_dates;
            "));

        // Since we can't count the next day of the last date, we need to drop that day.
        // The reason we can't count it is
        array_pop($results);
        $finalScores = [];
        foreach ($results as &$result) {
            if ($result->next_company_risk_scores > $result->company_risk_scores) {
                $value = (($result->next_company_risk_scores - $result->company_risk_scores) / $result->company_risk_scores);
            } else {
                $value = -(($result->company_risk_scores - $result->next_company_risk_scores) / $result->company_risk_scores);
            }

            $finalScores[] = $value * 100;
        }

        return array_sum($finalScores) / count($finalScores);
    }
}
