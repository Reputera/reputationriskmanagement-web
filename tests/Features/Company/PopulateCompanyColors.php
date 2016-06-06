<?php

namespace Tests\Features\Company;


use App\Entities\Company;
use App\Entities\CompanyVectorColor;
use App\Entities\Vector;

class PopulateCompanyColors extends \TestCase
{
    public function testSyncCompanyColors() {
        $vector = factory(Vector::class)->create();
        $company = Company::create();
//        Remove association between company and color to test that association is re-created after sync is run.
        \DB::table('company_vector_colors')->delete();
        CompanyVectorColor::syncAllCompanyColors();
        $this->seeInDatabase('company_vector_colors', [
            'company_id' => $company->id,
            'vector_id' => $vector->id,
            'color' => $vector->default_color
        ]);
    }

    public function testPopulateOnCreationOfCompany() {
        $vector = factory(Vector::class)->create();
        $company = Company::create();
        $this->seeInDatabase('company_vector_colors', [
            'company_id' => $company->id,
            'vector_id' => $vector->id,
            'color' => $vector->default_color
        ]);
    }

    public function testPopulateOnCreationOfVector() {
        $company = Company::create();
        $vector = factory(Vector::class)->create();
        $this->seeInDatabase('company_vector_colors', [
            'company_id' => $company->id,
            'vector_id' => $vector->id,
            'color' => $vector->default_color
        ]);
    }
}