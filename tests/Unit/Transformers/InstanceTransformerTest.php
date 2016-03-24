<?php

namespace Tests\Unit\Entities;


use App\Entities\Country;
use App\Entities\Instance;
use App\Transformers\Instance\InstanceTransformer;

class InstanceTransformerTest extends \TestCase
{

    public function testPresent()
    {
        $countries = factory(Country::class)->times(2)->create();
        $instance = factory(Instance::class)->create();
        $instance->countries()->attach($countries);

        $expectedResults = [
            'title' => $instance->title,
            'company' => $instance->company->name,
            'vector' => $instance->vector->name,
            'type' => $instance->type,
            'date' => $instance->start->format('Y-m-d H:i:s'),
            'language' => $instance->language,
            'source' => $instance->source,
            'fragment' => $instance->fragment,
            'link' => $instance->link,
            'regions' => $countries[0]->region->name . ', ' . $countries[1]->region->name,
            'positive_sentiment' => (int)($instance->positive_sentiment * 100),
            'negative_sentiment' => (int)($instance->negative_sentiment * -100),
            'sentiment_score' => (int)($instance->sentiment * 100)
        ];

        $transformedData = (new InstanceTransformer())->transform($instance);
        $this->assertEquals($expectedResults, $transformedData);
    }

}