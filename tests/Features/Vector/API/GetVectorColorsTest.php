<?php

namespace Tests\Features\Vector\API;


use App\Entities\CompanyVectorColor;
use App\Entities\Vector;

class GetVectorColorsTest extends \TestCase
{

    public function testGetVectors()
    {
        $vector = factory(Vector::class)->create();
        $user = $this->beLoggedInAsUser();
        CompanyVectorColor::create(['company_id' => $user->company_id, 'vector_id' => $vector->id, 'color' => 'color']);
        $this->ajaxCall('GET', 'vectorColors', ['company_id' => $user->company_id]);
        $this->assertResponseOk();
        $this->assertCount(1, $this->getResponseData());
    }

}