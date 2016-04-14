<?php

$factory->define(App\Entities\Instance::class, function (Faker\Generator $faker) {
    $fragment = $faker->sentence();
    $link = $faker->url;

    return [
        'company_id' => function () {
            return factory(\App\Entities\Company::class)->create()->id;
        },
        'vector_id' => function () {
            return factory(\App\Entities\Vector::class)->create()->id;
        },
        'entity_id' => $faker->numberBetween(),
        'type' => $faker->word,
        'start' => $faker->date(),
        'language' => $faker->word,
        'source' => $faker->word,
        'title' => $faker->word,
        'fragment' => $fragment,
        'fragment_hash' => sha1($fragment),
        'link' => $link,
        'link_hash' => sha1($link),
        'risk_score' => $faker->numberBetween(0, 100),
        'positive_risk_score' => $faker->numberBetween(0, 100),
        'negative_risk_score' => $faker->numberBetween(0, 100),
        'positive_sentiment' => ($faker->numberBetween(0, 100) / 100),
        'negative_sentiment' => ($faker->numberBetween(0, 100) / 100),
    ];
});
