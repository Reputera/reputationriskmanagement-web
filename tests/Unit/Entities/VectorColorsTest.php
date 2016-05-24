<?php

namespace Tests\Unit\Vector;

use App\Entities\CompanyVectorColor;
use App\Entities\Instance;
use App\Entities\Vector;

class GettingVectorsPerMonthTest extends \TestCase
{
    public function testGetColorDefault()
    {
        $vector = factory(Vector::class)->create();
        $this->assertEquals($vector->default_color, $vector->color());
    }
}
