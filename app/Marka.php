<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marka extends Model
{
    protected $fillable = ['image', 'title_en', 'title_ar', 'deleted'];
}
