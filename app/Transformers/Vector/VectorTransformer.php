<?php

namespace App\Transformers\Vector;

use App\Entities\Vector;
use League\Fractal\TransformerAbstract;

class VectorTransformer extends TransformerAbstract
{
    /**
     * @param Vector $vector
     * @return array
     */
    public function transform(Vector $vector)
    {
        return [
            'id' => $vector->id,
            'name' => $vector->name,
            'color' => $vector->color(),
            'default_color' => $vector->default_color
        ];
    }
}
