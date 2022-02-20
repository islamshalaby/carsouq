<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance_package extends Model
{
    protected $fillable = ['name_ar', 'name_en', 'desc_ar','desc_en','price','amount','status'];
}
