<?php

namespace Tests\Unit\Companies;

use App\Entities\Company;
use App\Entities\Instance;
use Carbon\Carbon;

class EarliestInstanceDateTest extends \TestCase
{
    public function testCompaniesAreRequired()
    {
        $company = factory(Company::class)->create();
        $instance1 = factory(Instance::class)->create(['start' => Carbon::now()->subDay(10)]);
        $instance2 = factory(Instance::class)->create(['start' => Carbon::now()]);

        $instance1->company()->associate($company)->save();
        $instance2->company()->associate($company)->save();
        $result = $company->earliestInstanceDate();
        $this->assertEquals($result, $instance1->start->toDateString());

    }
}
