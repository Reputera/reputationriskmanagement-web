<?php

namespace Tests\Features\Vector\API;


use App\Entities\Vector;

class SaveVectorColorTest extends \TestCase
{

    public function testSaveVectorColorInvalidValueGiven()
    {
        $this->beLoggedInAsUser();
        $this->ajaxCall('POST', 'vectorColor', ['color' => 'NotAHexColor']);
        $this->assertJsonResponseHasStatusCode(422);
    }

    public function testSaveVectorColor()
    {
        $vector = factory(Vector::class)->create();
        $this->beLoggedInAsUser();
        $this->ajaxCall('POST', 'vectorColor', ['vector_id' => $vector->id, 'color' => '#123456']);
        $this->assertResponseOk();
        $this->assertEquals('#123456', $vector->color());
    }

}