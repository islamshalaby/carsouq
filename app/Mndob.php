<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mndob extends Model
{
    protected $fillable = ['name_ar', 'name_en', 'image','phone','watsapp','status','deleted'];
}
