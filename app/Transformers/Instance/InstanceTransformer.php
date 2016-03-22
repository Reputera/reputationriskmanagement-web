<?php

namespace App\Transformers\Instance;


use App\Entities\Instance;
use League\Fractal\TransformerAbstract;

class InstanceTransformer extends TransformerAbstract
{
    public function transform(Instance $instance)
    {
        return (array)$instance;
//        return [
//            'id' => $instance->id,
//        ];
    }
}