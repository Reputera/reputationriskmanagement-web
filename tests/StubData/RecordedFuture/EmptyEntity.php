<?php

namespace Tests\StubData\RecordedFuture;

class EmptyEntity
{
    public static function get(bool $jsonEncode = true)
    {
        $result = [
            'count' => [
                'count' => [
                    'entities' => [
                        'returned' => 0,
                        'total' => 0,
                    ],
                ],
                'next_page_start' => '0',
                'status' => 'SUCCESS',
                'entities' => [],
                'entity_details' => [],
            ]
        ];

        if ($jsonEncode) {
            return json_encode($result);
        }

        return $result;
    }
}
