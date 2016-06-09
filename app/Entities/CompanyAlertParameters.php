<?php

namespace App\Entities;


use Illuminate\Database\Eloquent\Model;

class CompanyAlertParameters extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
