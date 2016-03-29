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
            'title' => $instance->title,
            'company' => $instance->company->name,
            'vector' => $instance->vector->name ?? null,
            'type' => $instance->type,
            'date' => $instance->start->format('Y-m-d H:i:s'),
            'language' => $instance->language,
            'source' => $instance->source,
            'fragment' => $instance->fragment,
            'link' => $instance->link,
            'regions' => implode(', ', $instance->getRegions()),
            'positive_sentiment' => (int)($instance->positive_sentiment * 100),
            'negative_sentiment' => (int)($instance->negative_sentiment * -100),
            'sentiment_score' => (int)($instance->sentiment * 100),
            'flagged' => (bool)$instance->flagged
        ];
    }
}