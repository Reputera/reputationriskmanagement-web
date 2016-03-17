<?php

$factory->define(App\Entities\Region::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'deleted_at' => null,
    ];
});
