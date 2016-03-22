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
            'vector' => $instance->vector_id ? $instance->vector->name : null,
            'type' => $instance->type,
            'start' => $instance->start,
            'language' => $instance->language,
            'source' => $instance->source,
            'title' => $instance->title,
            'fragment' => $instance->fragment,
            'fragment_hash' => $instance->fragment_hash,
            'link' => $instance->link,
            'positive_sentiment' => $instance->positive_sentiment,
            'negative_sentiment' => $instance->negative_sentiment
        ];
    }
}