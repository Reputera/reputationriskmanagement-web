<?php

namespace Tests\Unit\Entities;

use App\Entities\Vector;

class VectorTest extends \TestCase
{
    public function eventTypeDataProvider()
    {
        return [
            ['rfevEPerSONCommunIcation', 'Influencers'],
            ['IPO', 'Operations']
        ];
    }

    /** @dataProvider eventTypeDataProvider */
    public function test_getting_vector_for_event_type($givenString, $expectedVectorName)
    {
        factory(Vector::class)->create(['name' => $expectedVectorName]);
        $result = Vector::fromEventType($givenString);

        $this->assertInstanceOf(Vector::class, $result);
        $this->assertEquals($expectedVectorName, $result->name);
    }
}
