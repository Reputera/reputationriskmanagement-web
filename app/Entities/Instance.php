<?php

namespace App\Entities;


use Illuminate\Database\Eloquent\Model;

class Instance extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vector()
    {
        return $this->belongsTo(Vector::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'instance_country');
    }

}