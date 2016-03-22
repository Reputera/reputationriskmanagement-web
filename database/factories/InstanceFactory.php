<?php

$factory->define(App\Entities\Instance::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'company_id' => factory(Region::class)->create()->id,
        'vector_id' => factory(Region::class)->create()->id,
        'entity_id' => $faker->numberBetween(),
        'type' => $faker->word,
        'start' => $faker->date(),
        'language' => $faker->word,
        'source' => $faker->word,
        'title' => $faker->word,
        'fragment' => $faker->words,
        'fragment_hash' => $faker->word,
        'link' => $faker->url,
        'positive_sentiment' => $faker->numberBetween(0,100) / 100,
        'negative_sentiment' => $faker->numberBetween(0,100) / 100
    ];
});