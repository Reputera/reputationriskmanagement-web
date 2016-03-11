<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Entities\ApiKey
 *
 * @property integer $id
 * @property string $username
 * @property string $key
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \App\Entities\ApiKey|null find($id)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\ApiKey whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\ApiKey whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\ApiKey whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\ApiKey whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\ApiKey whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\ApiKey whereDeletedAt($value)
 * @mixin \Eloquent
 */
class ApiKey extends Model
{
    use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
