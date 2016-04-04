<?php

$factory->define(App\Entities\Company::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'stock_symbol' => null,
        'entity_id' => str_random(7),
        'deleted_at' => null,
    ];
});
