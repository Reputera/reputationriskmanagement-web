<?php

$factory->define(App\Entities\Company::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'stock_symbol' => strtoupper($faker->text(3)),
        'deleted_at' => null,
    ];
});
