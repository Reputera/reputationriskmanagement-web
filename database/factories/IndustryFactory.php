<?php

$factory->define(App\Entities\Industry::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});
