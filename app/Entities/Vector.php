<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entities\Vector
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Vector whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Vector whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Vector whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Vector whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Vector extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Instance
     */
    public function instances()
    {
        return $this->hasMany(Instance::class);
    }

    /**
     * @param Company $company
     * @param int $year
     * @param int $month
     * @return int
     */
    public function riskScoreForCompanyByYearAndMonth(Company $company, int $year, int $month): int
    {
        $month = ($month < 9 ) ? '0'.$month : $month;
        $vectorScoreQuery = $test = $this->instances()
            ->where('instances.company_id', $company->id)
            ->where('instances.start', 'like', $year.'-'.$month.'%')
            ->where('instances.vector_id', $this->id)
            ->select(\DB::raw('sum(instances.risk_score) / COUNT(instances.id) AS vector_score'));

        if ($results = $vectorScoreQuery->first()) {
            return (int) round($results->vector_score);
        }
        return 0;
    }
}
