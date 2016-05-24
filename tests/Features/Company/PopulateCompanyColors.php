<?php

namespace Tests\Features\Company;


use App\Entities\Company;
use App\Entities\Vector;

class PopulateCompanyColors extends \TestCase
{
    public function testPopulateOnCreate() {
        $vector = factory(Vector::class)->create();
        $company = Company::create();
        $this->seeInDatabase('company_vector_colors', [
            'company_id' => $company->id,
            'vector_id' => $vector->id,
            'color' => $vector->default_color
        ]);
    }
}