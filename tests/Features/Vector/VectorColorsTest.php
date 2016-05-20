<?php

namespace Tests\Features\Vector;

use App\Entities\CompanyVectorColor;
use App\Entities\Instance;
use App\Entities\Vector;

class GettingVectorsPerMonthTest extends \TestCase
{
    public function testGetColor()
    {
        $vector = factory(Vector::class)->create();
        $user = $this->beLoggedInAsUser();
        $companyVectorColor = CompanyVectorColor::create([
            'company_id' => $user->company_id,
            'vector_id' => $vector->id,
            'color' => str_random(5)
        ]);

        $this->assertEquals($companyVectorColor->color, $vector->color());
    }

    public function testGetColorDefault()
    {
        $vector = factory(Vector::class)->create();
        $this->assertEquals($vector->default_color, $vector->color());
    }
}
