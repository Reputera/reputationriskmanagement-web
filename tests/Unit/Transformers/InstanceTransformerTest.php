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
            'id' => $instance->id,
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
            'positive_risk_score' => $instance->positive_risk_score,
            'negative_risk_score' => $instance->negative_risk_score,
            'risk_score' => $instance->risk_score,
            'deleted_at' => $instance->deleted_at
        ];

        $transformedData = (new InstanceTransformer())->transform($instance);
        $this->assertEquals($expectedResults, $transformedData);
    }

}