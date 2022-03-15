<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubFourCategory extends Model
{
    protected $fillable = ['title_en', 'title_ar', 'image', 'deleted', 'sub_category_id','sort', 'is_show'];


    public function category() {
        return $this->belongsTo('App\SubThreeCategory', 'sub_category_id');
    }

    public function products()
    {
        return $this->hasMany('App\Product', 'sub_category_four_id')->where('status', 1)->where('publish', 'Y')->where('deleted', 0);
    }

    public function SubCategories()
    {
        return $this->hasMany('App\SubFiveCategory', 'sub_category_id')
            ->where('deleted', '0')
            ->where(function ($q) {
                $q->has('Products', '>', 0);
            });
    }

    public function ViewSubCategories() {
        return $this->hasMany('App\SubFiveCategory', 'sub_category_id')->where('deleted', 0);
    }
}
