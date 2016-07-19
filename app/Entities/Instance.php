<?php

namespace App\Entities;

use App\Entities\Traits\Toggleable;
use App\Http\Queries\Instance as InstanceQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * App\Entities\Instance
 *
 * @property integer $id
 * @property integer $company_id
 * @property integer $vector_id
 * @property string $entity_id
 * @property string $type
 * @property \Carbon\Carbon $start
 * @property string $language
 * @property string $source
 * @property string $title
 * @property string $fragment
 * @property string $fragment_hash
 * @property string $link
 * @property string $link_hash
 * @property integer $risk_score
 * @property integer $positive_risk_score
 * @property integer $negative_risk_score
 * @property float $positive_sentiment
 * @property float $negative_sentiment
 * @property boolean $flagged
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \App\Entities\Company $company
 * @property-read \App\Entities\Vector $vector
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Country[] $countries
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereVectorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereEntityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereStart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereLanguage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereSource($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereFragment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereFragmentHash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereLink($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereLinkHash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereRiskScore($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance wherePositiveRiskScore($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereNegativeRiskScore($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance wherePositiveSentiment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereNegativeSentiment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereFlagged($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance companyRiskScore($company)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance competitorRiskScoreForCompany($company)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance filter($filter)
 * @mixin \Eloquent
 */
class Instance extends Model
{
    use SoftDeletes, Toggleable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'start'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vector()
    {
        return $this->belongsTo(Vector::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'instance_country', 'instance_id', 'country_id');
    }

    /**
     * @return array
     */
    public function getRegions(): array
    {
        $regions = [];
        foreach ($this->countries as $country) {
            $regions[] = $country->region->name;
        }
        return array_unique($regions);
    }

    public static function scopeCompanyRiskScore($builder, Company $company)
    {
        return self::addSelectForRiskScoreAverage($builder)
            ->groupBy('instances.company_id');
    }

    public static function scopeCompanyRiskScore1($builder, Company $company)
    {
        return self::addSelectForRiskScoreAverage($builder)
            ->where('instances.company_id', '=', $company->id)
            ->groupBy('instances.company_id');
    }

    public function scopeDailyScaledCompanyRiskScore($builder)
    {
        return $builder->selectRaw('(sum(risk_score) + COUNT(instances.id) * 100) / COUNT(instances.id) as company_risk_scores')
            ->selectRaw('count(instances.id)')
            ->selectRaw('date(start) as start_date')
            ->groupBy('start_date');
    }

    public static function scopeDailyCompanyRiskScore($builder)
    {
        return self::addSelectForRiskScoreAverage($builder)
            ->selectRaw('date(start) as start_date')
            ->groupBy('start_date');
    }

    public static function scopeCompetitorRiskScoreForCompany($builder, Company $company)
    {
        return self::addSelectForRiskScoreAverage($builder)
            ->whereIn('instances.company_id', function ($query) use ($company) {
                $query->select('competitor_company_id')
                    ->from($company->competitors()->getTable())
                    ->where('company_id', $company->id);
            })
            ->groupBy('instances.company_id');
    }

    /**
     * @param $builder
     * @param InstanceQuery $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($builder, InstanceQuery $filter)
    {
        return $filter->apply($builder);
    }

    /**
     * @param $builder
     * @return Builder
     */
    protected static function addSelectForRiskScoreAverage($builder)
    {
        return $builder->select(DB::raw('sum(risk_score) / COUNT(instances.id) as company_risk_scores'));
    }
}
