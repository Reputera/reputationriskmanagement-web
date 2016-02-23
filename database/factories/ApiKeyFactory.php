<?php

$factory->define(App\Entities\ApiKey::class, function (Faker\Generator $faker) {
    return [
        'username' => 'internal',
        'key' => str_random(10),
        'deleted_at' => null,
    ];
});
