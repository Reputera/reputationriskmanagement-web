<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Entities\Region
 *
 * @property integer $id
 * @property string $name
 * @property string $entity_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read Collection|Company[] $companies
 * @method static \App\Entities\User|null find($id)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Region whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Region whereEntityId($value)
 * @mixin \Eloquent
 */
class Region extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Collection
     */
    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
