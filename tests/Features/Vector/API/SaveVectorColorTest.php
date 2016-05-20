<?php

namespace Tests\Features\Vector\API;


use App\Entities\Vector;

class SaveVectorColorTest extends \TestCase
{

    public function testGetVectors()
    {
        $vectors = factory(Vector::class)->times(2)->create();
        $this->beLoggedInAsUser();
        $this->ajaxCall('POST', 'vectors');
        dd($this->getResponseData());
        $this->assertCount($vectors->count(), $this->getResponseData());
    }

}