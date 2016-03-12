<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entities\Vector
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Vector whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Vector whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Vector whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Vector whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Vector extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Retrieves the Vector from a given event type.
     *
     * @param string $eventType
     * @return Vector|null
     */
    public static function fromEventType(string $eventType)
    {
        $eventType = strtolower($eventType);
        foreach (VectorEventType::all() as $vectorName => $vectorEventTypes) {
            $vectorEventTypes = array_map(function ($value) {
                return  strtolower($value);
            }, $vectorEventTypes);

            if (in_array($eventType, $vectorEventTypes)) {
                return self::whereName(ucfirst(strtolower($vectorName)))->first();
            }
        };

        return null;
    }
}
