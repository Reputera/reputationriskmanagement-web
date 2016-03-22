<?php namespace Tests\Features\Instance;


use App\Entities\Instance;

class QueryingTest extends \TestCase
{

    public function testGetByDateRange() {
        $instance = factory(Instance::class)->create();
        $this->beLoggedInAsAdmin();
        $this->apiCall('GET', 'instance', ['sort_by' => 'id']);
        $this->assertJsonResponseOkAndFormattedProperly();
    }

}