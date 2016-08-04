<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Entities\Vector
 *
 * @property integer $id
 * @property string $name
 * @property string $default_color
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Instance[] $instances
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\CompanyVectorColor[] $companyVectorColor
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Vector whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Vector whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Vector whereDefaultColor($value)
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

    public function companyVectorColor()
    {
        return $this->hasMany(CompanyVectorColor::class);
    }

    public static function boot()
    {
        Vector::created(function ($vector) {
            CompanyVectorColor::populateCompaniesWithNewVector($vector);
        });
        parent::boot();
    }

    /**
     * @param Company $company
     * @param int $year
     * @param int $month
     * @return int|string
     */
    public function riskScoreForCompanyByYearAndMonth(Company $company, int $year, int $month)
    {
        $month = ($month < 9 ) ? '0'.$month : $month;
        $vectorScoreQuery = $test = $this->instances()
            ->where('instances.company_id', $company->id)
            ->where('instances.start', 'like', $year.'-'.$month.'%')
            ->where('instances.vector_id', $this->id)
            ->where('instances.risk_score', '!=', 0)
            ->selectRaw('sum(instances.risk_score) / COUNT(distinct instances.id) AS vector_score')
            ->selectRaw('COUNT(distinct instances.id) AS count_instances');

        if ($results = $vectorScoreQuery->first()) {
            if($results->count_instances == 0) {
                return 'N/A';
            }
            return (int) round($results->vector_score);
        }
        return 'N/A';
    }

    public function color()
    {
        if ($user = auth()->user()) {
            $colorModel = $this->companyVectorColor()->where('company_id', $user->company_id)->first();
            if ($colorModel) {
                return $colorModel->color;
            }
        }
        return $this->default_color;
    }

    public function colorForCompany($companyId)
    {
        return $this->companyVectorColor()->where('company_id', $companyId)->first();
    }
}
