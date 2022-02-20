<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['image', 'title_en', 'title_ar', 'deleted','sort'];

    public function products() {
        return $this->hasMany('App\Product', 'category_id')->where('status', 1)->where('publish', 'Y')->where('deleted', 0);
    }

    public function SubCategories() {
        return $this->hasMany('App\SubCategory', 'category_id')->where('deleted', 0)->where(function ($q) {
            $q->has('SubCategories', '>', 0)->orWhere(function ($qq) {
                $qq->has('Products_custom', '>', 0);
            });
        });
    }

    public function ViewSubCategories() {
        return $this->hasMany('App\SubCategory', 'category_id')->where('deleted', 0);
    }
}
