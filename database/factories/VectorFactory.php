<?php

$factory->define(App\Entities\Vector::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});
