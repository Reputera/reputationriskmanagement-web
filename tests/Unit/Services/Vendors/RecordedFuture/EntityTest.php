<?php

namespace Tests\Unit\Services\Vendors\RecordedFuture;

use App\Services\Vendors\RecordedFuture\Entity;

class EntityTest extends \TestCase
{
    /** @var Entity */
    protected $entity;
    
    protected $fields = [
        'B_FAG' => [
            'name' => 'United States',
            'hits' => 451682344,
            'type' => 'Country',
            'external_id' => '188021635',
            'containers' => [
                'B_GRZ'
            ],
            'longname' => 'United States',
            'external_links' => [
                'wikipedia' => [
                    'id' => 'United_States'
                ]
            ],
            'alias' => [
                '',
                'us',
                '188021635',
                'usa',
                'u.s.',
                'united states',
                'u.s.a.',
                'united states of america'
            ],
            'features' => [
                'J3HWz_'
            ],
            'curated' => 1,
            'meta_type' => 'type=>Country',
            'pos' => [
                'latitude' => 39.76,
                'longitude' => -98.5
            ]
        ]
    ];

    public function setUp()
    {
        parent::setUp();
        $this->entity = new Entity(key($this->fields), current($this->fields));
    }

    public function function_name_and_expected_populated_data_provider()
    {
        return [
            ['getKey', 'B_FAG'],
            ['getName', 'United States'],
            ['getHits', 451682344],
            ['getType', 'Country'],
            ['getContainers', ['B_GRZ']]
        ];
    }

    /** @dataProvider function_name_and_expected_populated_data_provider */
    public function test_getting_populated_fields_for_non_return_objects($function, $expectedResult)
    {
        $this->assertEquals($expectedResult, $this->entity->{$function}());
    }

    public function function_name_and_expected_unpopulated_data_provider()
    {
        $populatedData = $this->function_name_and_expected_populated_data_provider();
        $populatedData[0][1] = '';
        $populatedData[1][1] = '';
        $populatedData[2][1] = 0;
        $populatedData[3][1] = '';
        $populatedData[4][1] = [];
        return $populatedData;
    }

    /** @dataProvider function_name_and_expected_unpopulated_data_provider */
    public function test_getting_missing_fields_for_non_return_objects($function, $expectedOutput)
    {
        $entity = new Entity('', []);
        $this->assertEquals($expectedOutput, $entity->{$function}());
    }
}
