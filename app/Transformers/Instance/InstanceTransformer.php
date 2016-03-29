<?php

namespace App\Transformers\Instance;


use App\Entities\Instance;
use League\Fractal\TransformerAbstract;

class InstanceTransformer extends TransformerAbstract
{
    public function transform(Instance $instance)
    {
        return [
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
            'risk_score' => $instance->risk_score,
        ];
    }
}