<?php

namespace App\Transformers\Instance;


use App\Entities\Instance;
use League\Fractal\TransformerAbstract;

class InstanceTransformer extends TransformerAbstract
{
    public function transform(Instance $instance)
    {
        return [
            'id' => $instance->id,
            'company' => $instance->company->name,
            'vector' => $instance->vector->name ?? null,
            'type' => $instance->type,
            'start' => $instance->start->format('Y-m-d H:i:s'),
            'language' => $instance->language,
            'source' => $instance->source,
            'title' => $instance->title,
            'fragment' => $instance->fragment,
            'link' => $instance->link,
            'positive_sentiment' => $instance->positive_sentiment * 100,
            'negative_sentiment' => $instance->negative_sentiment * -100,
            'risk_score' => ($instance->positive_sentiment - $instance->negative_sentiment) * 100
        ];
    }
}