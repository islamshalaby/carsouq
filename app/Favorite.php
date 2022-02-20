<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = ['user_id', 'product_id'];

    public function User() {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function Product() {
        return $this->belongsTo('App\Product', 'product_id')->select('id','title','main_image','price','publication_date as created_at');
    }
}
