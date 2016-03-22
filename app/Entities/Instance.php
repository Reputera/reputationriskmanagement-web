<?php

namespace App\Entities;


use Illuminate\Database\Eloquent\Model;

class Instance extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];

}