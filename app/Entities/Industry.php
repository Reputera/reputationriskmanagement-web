<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entities\Industry
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Industry whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Industry whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Industry whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Industry whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Industry extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
