<?php

namespace Tests\Features\Company;


use App\Entities\Company;

class CompetitorTest extends \TestCase
{

    public function testAddCompetitor()
    {
        $company = factory(Company::class)->create();
        $competitor = factory(Company::class)->create();

        $this->beLoggedInAsAdmin();
        $this->call('POST', 'addCompetitor', [
            'company_id' => $company->id,
            'competitor_id' => $competitor->id
        ]);
        $this->assertJsonResponseOkAndFormattedProperly();
        $this->assertEquals($competitor->id, $company->competitors[0]->id);
    }

    public function testRemoveCompetitor()
    {
        $company = factory(Company::class)->create();
        $competitor = factory(Company::class)->create();
        $company->competitors()->attach($competitor);

        $this->beLoggedInAsAdmin();
        $this->call('POST', 'removeCompetitor', [
            'company_id' => $company->id,
            'competitor_id' => $competitor->id
        ]);
        $this->assertJsonResponseOkAndFormattedProperly();
        $this->assertEmpty($company->competitors);
    }

}