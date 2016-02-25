<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Entities\Company
 *
 * @property integer $id
 * @property string $name
 * @property string $stock_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read Collection|Region[] $regions
 * @method static \App\Entities\User|null find($id)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereDeletedAt($value)
 */
class Company extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'stock_name'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Collection
     */
    public function regions()
    {
        return $this->hasMany(Region::class);
    }
}
