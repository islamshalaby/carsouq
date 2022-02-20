<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['title_ar', 'title_en','status','cat_id','day_num', 'price','deleted'];

    public function Details() {
        return $this->hasMany('App\Plan_details', 'plan_id')
            ->select('id' ,'title_ar' , 'title_en' ,'plan_id','status')->where('status','show');
    }
    public function Cat()
    {
        return $this->belongsTo('App\Category', 'cat_id');
    }
}
