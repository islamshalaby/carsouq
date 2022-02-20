<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categories_ad extends Model
{
    protected $fillable = ['image', 'cat_id','type','ad_type' ,'content','deleted'];
}
