<?php

$factory->define(App\Entities\StockHistory::class, function (Faker\Generator $faker) {
    return [
        'company_id' => factory(App\Entities\Company::class)->create()->id,
        'date' => date('Y-m-d'),
        'open' => $faker->randomFloat(2, 6000),
        'high' => $faker->randomFloat(2, 6000),
        'low' => $faker->randomFloat(2, 6000),
        'close' => null
    ];
});
