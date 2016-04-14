<?php

$factory->define(App\Entities\VectorEventType::class, function (Faker\Generator $faker) {
    return [
        'vector_id' => function () {
            return factory(App\Entities\Vector::class)->create()->id;
        },
        'event_type' => $faker->name,
    ];
});
