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
                'entities' => [
                    'returned' => 1,
                    'total' => 1,
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
            ]
        ];

        if ($jsonEncode) {
            return json_encode($result);
        }

        return $result;
    }
}
