<?php

namespace App\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Entities\CompanyVectorColor
 *
 * @property string $color
 * @property integer $company_id
 * @property integer $vector_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\CompanyVectorColor whereColor($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\CompanyVectorColor whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\CompanyVectorColor whereVectorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\CompanyVectorColor whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\CompanyVectorColor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CompanyVectorColor extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];

}