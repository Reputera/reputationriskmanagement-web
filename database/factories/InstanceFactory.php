<?php

$factory->define(App\Entities\Instance::class, function (Faker\Generator $faker) {
    return [
        'company_id' => factory(\App\Entities\Company::class)->create()->id,
        'vector_id' => factory(\App\Entities\Vector::class)->create()->id,
        'entity_id' => $faker->numberBetween(),
        'type' => $faker->word,
        'start' => $faker->date(),
        'language' => $faker->word,
        'source' => $faker->word,
        'title' => $faker->word,
        'fragment' => $faker->sentence(),
        'fragment_hash' => $faker->unique()->word,
        'link' => $faker->url,
        'positive_sentiment' => $faker->numberBetween(0,100) / 100,
        'negative_sentiment' => $faker->numberBetween(0,100) / 100
    ];
});