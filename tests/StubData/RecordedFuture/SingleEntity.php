<?php

namespace Tests\StubData\RecordedFuture;

class SingleEntity
{
    public static function getCompany(array $options = [], bool $jsonEncode = true)
    {
        $faker = \Faker\Factory::create();
        $entityId = array_key_exists('entity_id', $options) ? $options['entity_key'] : $faker->name;
        $entityName = array_key_exists('entity_name', $options) ? $options['entity_name'] : $faker->name;
        $entityIndustries = array_key_exists('industries_array', $options) ?
            $options['industries_array'] : [$faker->name, $faker->name];

        $result = [
            'count' => [
                'entities' => [
                    'returned' => 1,
                    'total' => 1,
                ],
            ],
            'next_page_start' => '1',
            'status' => 'SUCCESS',
            'entities' => [
                $entityId,
            ],
            'entity_details' => [
                $entityId => [
                    'name' => $entityName,
                    'hits' => 559109,
                    'type' => 'Company',
                    'cik' => [
                        '0000046080',
                        '0001176337',
                    ],
                    'external_links' => [
                        'bloomberg' => [
                            'id' => [
                                'EQ0010431400001000',
                            ],
                        ],
                    ],
                    'industries' => $entityIndustries,
                    'curated' => 1,
                    'cusip' => ['418056107'],
                    'sedol' =>['2414580'],
                    'gics' => ['Consumer Discretionary'],
                    'meta_type' => 'type:Company',
                ],
            ],
        ];

        if ($jsonEncode) {
            return json_encode($result);
        }

        return $result;
    }

    public static function getIndustry(array $options = [], bool $jsonEncode = true)
    {
        $faker = \Faker\Factory::create();
        $entityId = array_key_exists('entity_id', $options) ? $options['entity_key'] : $faker->name;
        $entityName = array_key_exists('entity_name', $options) ? $options['entity_name'] : $faker->name;

        $result = [
            'count' => [
                'entities' => [
                    'returned' => 1,
                    'total' => 1,
                ],
            ],
            'next_page_start' => '1',
            'status' => 'SUCCESS',
            'entities' => [
                $entityId,
            ],
            'entity_details' => [
                $entityId => [
                    'name' => $entityName,
                    'hits' => 343401,
                    'type' => 'Industry',
                    'alias' => ['consumer products'],
                    'industries' => ['JxldLp'],
                    'curated' => 1,
                    'meta_type' => 'type:Industry'
                ],
            ],
        ];

        if ($jsonEncode) {
            return json_encode($result);
        }

        return $result;
    }
}
