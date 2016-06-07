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


    /**
     * Associates all unassociated colors with companies.
     */
    public static function syncAllCompanyColors() {
        foreach(Company::all() as $company) {
            CompanyVectorColor::populateCompanyColors($company);
        }
    }

    /**
     * Associate all vector colors to company.
     *
     * @param Company $company
     */
    public static function populateCompanyColors(Company $company)
    {
        foreach(Vector::all() as $vector) {
            CompanyVectorColor::createIfDoesntExist($company, $vector);
        }
    }

    /**
     * Associate a new vector color with all companies.
     *
     * @param Vector $vector
     */
    public static function populateCompaniesWithNewVector(Vector $vector)
    {
        foreach(Company::all() as $company) {
            CompanyVectorColor::createIfDoesntExist($company, $vector);
        }
    }


    /**
     * Create association between company and vector color (deafult color) if one does not already exist.
     *
     * @param Company $company
     * @param Vector $vector
     */
    protected static function createIfDoesntExist(Company $company, Vector $vector)
    {
        if(! CompanyVectorColor::where([
            'company_id' => $company->id,
            'vector_id' => $vector->id
        ])->first()) {
            CompanyVectorColor::create([
                'company_id' => $company->id,
                'vector_id' => $vector->id,
                'color' => $vector->default_color
            ]);
        }
    }

}