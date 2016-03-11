<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entities\Country
 *
 * @property integer $id
 * @property string $name
 * @property string $entity_id
 * @property integer $region_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Country whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Country whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Country whereEntityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Country whereRegionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Country whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Country extends Model
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
