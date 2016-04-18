<?php

namespace App\Transformers\Industry;

use App\Entities\Industry;
use League\Fractal\TransformerAbstract;

class IndustryTransformer extends TransformerAbstract
{
    /**
     * @param Industry $industry
     * @return array
     */
    public function transform(Industry $industry)
    {
        return [
            'id' => $industry->id,
            'name' => $industry->name,
        ];
    }
}
