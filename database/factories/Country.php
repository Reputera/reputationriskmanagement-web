<?php

use App\Entities\Region;

$factory->define(App\Entities\Country::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'entity_id' => 'XyZ_123',
        'region_id' => factory(Region::class)->create()->id
    ];
});
