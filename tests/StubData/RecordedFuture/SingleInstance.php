<?php

namespace Tests\StubData\RecordedFuture;

use App\Entities\VectorEventType;

class SingleInstance
{
    public static function get(array $options = [], bool $jsonEncode = false)
    {
        $faker = \Faker\Factory::create();
        $instanceId = array_key_exists('id', $options) ? $options['id'] : $faker->name;
        $fragment = array_key_exists('fragment', $options) ? $options['fragment'] : $faker->sentence;
        $totalReferences = array_key_exists('total', $options) ? $options['total'] : 5;
        $referencesReturned = array_key_exists('returned', $options) ? $options['returned'] : 1;

        if (array_key_exists('type', $options)) {
            $eventType = $options['type'];
        } else {
            $eventVectors = VectorEventType::keys();
            $randomVector = $eventVectors[array_rand($eventVectors)];

            $vectorEventTypes = VectorEventType::valueByStringKey($randomVector);
            $eventType = $vectorEventTypes[array_rand($vectorEventTypes)];
        }

        $result = [
            'count' => [
                'references' => [
                    'returned' => $referencesReturned,
                    'total' => $totalReferences,
                ],
            ],
            'next_page_start' => '1',
            'status' => 'SUCCESS',
            'instances' => [
                [
                    'id' => $instanceId,
                    'type' => $eventType,
                    'cluster_id' => 'HOT13RwyMNk',
                    'cluster_ids' => [
                        'HOT13RwyMNk'
                    ],
                    'start' => '2016-03-17T00:00:00.000Z',
                    'stop' => '2016-03-17T23:59:59.000Z',
                    'tagged_fragment' => 'Lorem ipsum dolor sit amet, aliquam sit libero fringilla purus, aliqua ligula.',
                    'fragment' => $fragment,
                    'time_fragment_context' => $fragment,
                    'time_fragment' => 'March 17',
                    'item_fragment' => 'Lorem ipsum dolor sit amet, aliquam sit libero fringilla purus, aliqua ligula.',
                    'precision' => 'day',
                    'time_type' => 'in',
                    'document' => [
                        'id' => 'PTfC3u',
                        'title' => 'Lorem ipsum dolor sit amet, lacus interdum sit fusce risus vestibulum.',
                        'language' => 'eng',
                        'published' => '2015-11-16T07:21:25.000Z',
                        'downloaded' => '2015-11-16T09:18:17.938Z',
                        'indexed' => '2015-11-16T07:21:25.000Z',
                        'url' => 'Lorem ipsum dolor sit amet, dolor quis nibh, hendrerit ad.',
                        'sourceId' => [
                            'id' => 'KhLfip',
                            'name' => 'Some News Feed',
                            'description' => 'Some News Feed',
                            'media_type' => 'JxSEs2',
                            'country' => 'United States',
                            'topic' => 'JxSEtT'
                        ]
                    ],
                    'attributes' => [
                        'general_negative' => 0,
                        'function' => 'id',
                        'canonic_id' => 'tw9sOCkgxg',
                        'analyzed' => '2015-12-08T15:42:55.394Z',
                        'positive' => 0,
                        'document_url' => 'Lorem ipsum dolor sit amet, nec scelerisque vel.',
                        'sentiments' => [
                            'general_positive' => 0.04477611940298507,
                            'violence' => 0,
                            'activism' => 0,
                            'general_negative' => 0,
                            'negative' => 0,
                            'positive' => 0,
                            'profanity' => 0
                        ],
                        'violence' => 0,
                        'document_external_id' => '/?p=76610',
                        'binning_id' => 'DWrQEOF5c9l',
                        'inherited_locations' => [
                            'I6JBTy',
                            'I2Endo',
                        ],
                        'entities' => [
                            'I6JBTy',
                            'I2Endo',
                        ],
                        'meta_type' => 'type:Event',
                        'general_positive' => 0.04477611940298507,
                        'topics' => [
                            'KPzY_x',
                            'KPzZAT'
                        ],
                        'negative' => 0,
                        'fragment_count' => 171,
                        'document_offset' => 21
                    ]
                ]
            ],
            'entities' => [
                'I6JBTy' => [
                    'name' => 'Salt Lake City',
                    'hits' => 1812506,
                    'type' => 'City',
                    'containers' => [
                        'B_GRZ',
                        'I2EnZw',
                        'B_FAG',
                        'I2Eo1m'
                    ],
                    'longname' => 'Salt Lake City,Utah,United States',
                    'alias' => [],
                    'external_links' => [
                        'wikipedia' => [
                            'id' => 'Salt_Lake_City'
                        ]
                    ],
                    'features' => [
                        'J3HW2_'
                    ],
                    'curated' => 1,
                    'pos' => [
                        'latitude' => 40.76078,
                        'longitude' => -111.89105
                    ],
                        'meta_type' => 'type:City'
                ],
                'I2Endo' => [
                    'name' => 'Sacramento',
                    'hits' => 2819435,
                    'type' => 'City',
                    'containers' => [
                        'I2En_R',
                        'B_GRZ',
                        'B_FAG',
                        'I2EnW9'
                    ],
                    'longname' => 'Sacramento,California,United States',
                    'alias' => [
                        'Sacramento',
                    ],
                    'external_links' => [
                        'wikipedia' => [
                            'id' => 'Sacramento%2C_California'
                        ]
                    ],
                    'features' => [
                        'J3HW2_'
                    ],
                    'curated' => 1,
                    'pos' => [
                        'latitude' => 38.58157,
                        'longitude' => -121.4944
                    ],
                    'meta_type' => 'type:City'
                ],
                'B_FAG' => [
                    'name' => 'United States',
                    'hits' => 453345898,
                    'type' => 'Country',
                    'containers' => [
                        'B_GRZ'
                    ],
                    'external_id' => '188021635',
                    'longname' => 'United States',
                    'alias' => [
                        'united states of america',
                        'u.s.a.'
                    ],
                    'external_links' => [
                        'wikipedia' => [
                            'id' => 'United_States'
                        ]
                    ],
                    'features' => [
                        'J3HWz_'
                    ],
                    'curated' => 1,
                    'pos' => [
                        'latitude' => 39.76,
                        'longitude' => -98.5
                    ],
                    'meta_type' => 'type:Country'
                ],
                'url:http://www.someurl.com' => [
                    'name' => 'http://www.someurl.com',
                    'type' => 'URL',
                    'created' => '2016-03-21T00:11:26.945Z',
                    'domain' => 'idn:www.someurl.com',
                    'curated' => 0,
                    'meta_type' => 'type:URL'
                ]
            ]
        ];

        if ($jsonEncode) {
            return json_encode($result);
        }

        return $result;
    }
}
