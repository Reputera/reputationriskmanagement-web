<?php

namespace Tests\Features\Vector\API;


use App\Entities\Vector;

class GetVectorsTest extends \TestCase
{

    public function testGetVectors()
    {
        $vectors = factory(Vector::class)->times(2)->create();
        $this->beLoggedInAsUser();
        $this->ajaxCall('GET', 'vectors');
        $this->assertCount($vectors->count(), $this->getResponseData());
    }

}