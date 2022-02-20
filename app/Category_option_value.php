<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category_option_value extends Model
{
    protected $fillable = ['image','value_ar', 'value_en', 'option_id','deleted'];
}
