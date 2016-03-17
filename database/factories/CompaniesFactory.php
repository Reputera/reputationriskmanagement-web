<?php

$factory->define(App\Entities\Company::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'stock_symbol' => null,
        'entity_id' => null,
        'deleted_at' => null,
    ];
});
