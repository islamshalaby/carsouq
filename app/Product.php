<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
//    protected $dates = ['publication_date'];
    protected $appends = array('views');
    protected $fillable = ['title','description', 'price','category_id','sub_category_id','sub_category_two_id','expire_special_date',
        'sub_category_three_id','sub_category_four_id','user_id', 'type','publication_date','re_post_date','is_special',
        'views', 'offer', 'status', 'expiry_date','main_image','expire_pin_date','pin','created_at','plan_id','publish','sub_category_five_id','deleted'];

    public function category() {
        return $this->belongsTo('App\Category', 'category_id');
    }
    public function Sub_category() {
        return $this->belongsTo('App\SubCategory', 'sub_category_id');
    }
    public function Sub_two_category() {
        return $this->belongsTo('App\SubTwoCategory', 'sub_category_two_id');
    }
    public function Sub_three_category() {
        return $this->belongsTo('App\SubThreeCategory', 'sub_category_three_id');
    }
    public function Sub_four_category() {
        return $this->belongsTo('App\SubFourCategory', 'sub_category_four_id');
    }
    public function Sub_five_category() {
        return $this->belongsTo('App\SubFiveCategory', 'sub_category_five_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function Product_user() {
        return $this->belongsTo('App\User', 'user_id')->select('id','name','phone','watsapp');
    }
    public function Plan() {
        return $this->belongsTo('App\Plan', 'plan_id');
    }

    public function images() {
        return $this->hasMany('App\ProductImage', 'product_id');
    }
    public function Features() {
        return $this->hasMany('App\Product_feature', 'product_id');
    }

    public function Views() {
        return $this->hasMany('App\Category_option_value', 'product_id');
    }
	public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('g:i a Y-m-d');
    }

    public function getViewsAttribute()
    {
        $product = Product_view::where('product_id',$this->id)->get()->count();
        return $product;
    }
}
