<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubFiveCategory extends Model
{
    protected $fillable = ['title_en', 'title_ar', 'image', 'deleted', 'sub_category_id','sort', 'is_show'];


    public function Category() {
        return $this->belongsTo('App\SubFourCategory', 'sub_category_id');
    }

    public function products() {
        return $this->hasMany('App\Product', 'sub_category_five_id')->where('status', 1)->where('publish', 'Y')->where('deleted', 0);
    }

}
