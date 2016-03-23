<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entities\VectorEventType
 *
 * @property integer $id
 * @property integer $vector_id
 * @property string $event_type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Entities\Vector $vector
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\VectorEventType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\VectorEventType whereVectorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\VectorEventType whereEventType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\VectorEventType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\VectorEventType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class VectorEventType extends Model
{
    public function vector()
    {
        return $this->belongsTo(Vector::class);
    }
}
