<?php

$factory->define(App\Entities\Region::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'entity_id' => '123_QWER',
        'deleted_at' => null,
    ];
});
