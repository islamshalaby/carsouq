<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan_details extends Model
{
    protected $fillable = ['title_ar', 'title_en', 'plan_id','type','expire_days','status'];



}
