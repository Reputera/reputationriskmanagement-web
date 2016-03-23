<?php

$factory->define(App\Entities\VectorEventType::class, function (Faker\Generator $faker) {
    return [
        'vector_id' => factory(App\Entities\Vector::class)->create()->id,
        'event_type' => $faker->name,
    ];
});
