<?php

namespace App\Http\Controllers\Admin;

use App\Category_option;
use App\Category_option_value;
use App\Helpers\APIHelpers;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\categories\OptionsValuesController;
use App\Plan;
use App\Plan_details;
use App\Product_feature;
use App\SubCategory;
use App\SubFiveCategory;
use App\SubFourCategory;
use App\SubThreeCategory;
use App\SubTwoCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use App\Product;
use App\ProductImage;
use App\Category;
use App\User;
use App\Setting;


class ProductController extends AdminController
{
    // show
    public function show()
    {
        $data['products'] = Product::where('deleted',0)->orderBy('id', 'desc')->get();
        return view('admin.products.products', ['data' => $data]);
    }

    // add get
    public function addGet()
    {
        $data['categories'] = Category::orderBy('created_at', 'desc')->get();
        $data['users'] = User::orderBy('created_at', 'desc')->get();
        return view('admin.products.product_form', ['data' => $data]);
    }

    // add post
    public function AddPost(Request $request)
    {
        $data = $this->validate(\request(),
            [
                'user_id' => 'required',
                'category_id' => 'required',
                'sub_category_id' => 'required',
                'sub_category_two_id' => '',
                'sub_category_three_id' => '',
                'sub_category_four_id' => '',
                'sub_category_five_id' => '',
                'title' => 'required',
                'price' => 'required',
                'description' => 'required',
                'plan_id' => 'required',
                'main_image' => 'required',
            ]);
        if($request->main_image != null){
            $image_name = $request->file('main_image')->getRealPath();
            Cloudder::upload($image_name, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];
            $image_new_name = $image_id.'.'.$image_format;
            $data['main_image'] = $image_new_name;
        }
        $selected_plan = Plan::where('id',$request->plan_id)->first();
        $plan_detail = Plan_details::where('plan_id',$selected_plan->id)->where('type','expier_num')->first();
        $expire_days = $plan_detail->expire_days;
        //to get the expire_date of ad
        $mytime = Carbon::now();
        $today =  Carbon::parse($mytime->toDateTimeString())->format('Y-m-d H:i');
        $date = null;
        $pin = Plan_details::where('plan_id',$selected_plan->id)->where('type','pin')->first();
        if($pin != null){
            $expire_pin_date = $pin->expire_days;
            $data['pin'] = 1 ;
            //to create expire pin date
            $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
            $final_expire_pin_date = $final_pin_date->addDays($expire_pin_date);
            $data['expire_pin_date'] = $final_expire_pin_date ;
        }
        $re_post = Plan_details::where('plan_id',$selected_plan->id)->where('type','re_post')->first();
        if($re_post != null){
            $expire_re_post_date = $re_post->expire_days;
            $data['re_post'] = '1' ;
            //to create expire pin date
            $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
            $final_expire_re_post_date = $final_pin_date->addDays($expire_re_post_date);
            $data['re_post_date'] = $final_expire_re_post_date ;
        }
        $special = Plan_details::where('plan_id',$selected_plan->id)->where('type','special')->first();
        if($special != null){
            $expire_special_date = $special->expire_days;
            $data['is_special'] = '1' ;
            //to create expire pin date
            $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
            $final_expire_special_date = $final_pin_date->addDays($expire_special_date);
            $data['expire_special_date'] = $final_expire_special_date ;
        }
        $final_today = Carbon::createFromFormat('Y-m-d H:i', $today);
        $expire_date = $final_today->addDays($expire_days);

        $data['publish'] = 'Y';
        $data['publication_date'] = $today;
        $data['expiry_date'] = $expire_date;
        $product = Product::create($data);

        foreach ($request->images as $image){
            $image_name = $image->getRealPath();
            Cloudder::upload($image_name, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];
            $image_new_name = $image_id.'.'.$image_format;

            $data_image['product_id'] = $product->id ;
            $data_image['image'] = $image_new_name ;
            ProductImage::create($data_image);
        }
        foreach ($request->options as $key => $option){
            $type = null;
            if($key==0){
                $type = 'marka';
            }else if($key==1){
                $type = 'marka_type';
            }else if($key==2){
                $type = 'model_year';
            }else if($key==3){
                $type = 'counter';
            }
            $marka_value = Category_option_value::where('id', $option)->first();
            if ($marka_value != null) {
                $feature_data['product_id'] = $product->id;
                $feature_data['target_id'] = $option;
                $feature_data['type'] = 'option';
                $feature_data['option_type'] = $type;
                Product_feature::create($feature_data);
            } else {
                $manual_data['product_id'] = $product->id;
                $manual_data['target_id'] = $option;
                $manual_data['type'] = 'manual';
                $manual_data['option_type'] = $type;
                Product_feature::create($manual_data);
            }
        }
        return redirect()->route('products.index');
    }
    // edit get
    public function edit($id)
    {
        $data = Product::find($id);
        return view("admin.products.product_edit",compact('data'));
    }
    // edit post
    public function EditPost(Request $request, $id)
    {
        $prod = Product::find($id);
        $data = $this->validate(\request(),
            [
                'user_id' => 'required',
                'category_id' => 'required',
                'sub_category_id' => 'required',
                'sub_category_two_id' => '',
                'sub_category_three_id' => '',
                'sub_category_four_id' => '',
                'sub_category_five_id' => '',
                'title' => 'required',
                'price' => 'required',
                'description' => 'required',
                'plan_id' => 'required',
            ]);
        if($request->main_image != null){
            $image = $prod->main_image;
            $publicId = substr($image, 0 ,strrpos($image, "."));
            Cloudder::delete($publicId);

            $image_name = $request->file('main_image')->getRealPath();
            Cloudder::upload($image_name, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];
            $image_new_name = $image_id.'.'.$image_format;
            $data['main_image'] = $image_new_name;
        }
        if($prod->plan_id != $request->plan_id) {
            $selected_plan = Plan::where('id', $request->plan_id)->first();
            $plan_detail = Plan_details::where('plan_id', $selected_plan->id)->where('type', 'expier_num')->first();
            $expire_days = $plan_detail->expire_days;
            //to get the expire_date of ad
            $mytime = Carbon::now();
            $today = Carbon::parse($mytime->toDateTimeString())->format('Y-m-d H:i');
            $date = null;
            $pin = Plan_details::where('plan_id', $selected_plan->id)->where('type', 'pin')->first();
            if ($pin != null) {
                $expire_pin_date = $pin->expire_days;
                $data['pin'] = 1;
                //to create expire pin date
                $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                $final_expire_pin_date = $final_pin_date->addDays($expire_pin_date);
                $data['expire_pin_date'] = $final_expire_pin_date;
            }
            $re_post = Plan_details::where('plan_id', $selected_plan->id)->where('type', 're_post')->first();
            if ($re_post != null) {
                $expire_re_post_date = $re_post->expire_days;
                $data['re_post'] = '1';
                //to create expire pin date
                $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                $final_expire_re_post_date = $final_pin_date->addDays($expire_re_post_date);
                $data['re_post_date'] = $final_expire_re_post_date;
            }
            $special = Plan_details::where('plan_id', $selected_plan->id)->where('type', 'special')->first();
            if ($special != null) {
                $expire_special_date = $special->expire_days;
                $data['is_special'] = '1';
                //to create expire pin date
                $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                $final_expire_special_date = $final_pin_date->addDays($expire_special_date);
                $data['expire_special_date'] = $final_expire_special_date;
            }
            $final_today = Carbon::createFromFormat('Y-m-d H:i', $today);
            $expire_date = $final_today->addDays($expire_days);
            $data['publish'] = 'Y';
            $data['expiry_date'] = $expire_date;
        }
        Product::where('id',$id)->update($data);
        if($request->images != null) {
            foreach ($request->images as $image) {
                $image_name = $image->getRealPath();
                Cloudder::upload($image_name, null);
                $imagereturned = Cloudder::getResult();
                $image_id = $imagereturned['public_id'];
                $image_format = $imagereturned['format'];
                $image_new_name = $image_id . '.' . $image_format;
                $data_image['product_id'] = $id;
                $data_image['image'] = $image_new_name;
                ProductImage::create($data_image);
            }
        }
        $selected_brand = Product_feature::where('product_id',$id)->where('option_type','marka')->first();
        if($request->brand_id != $selected_brand->id){
            $selected_brand->target_id = $request->brand_id;
            $selected_brand->save();
        }
        $selected_brand_type = Product_feature::where('product_id',$id)->where('option_type','marka_type')->first();
        if($request->brand_id != $selected_brand_type->id){
            $selected_brand_type->target_id = $request->brand_type_id;
            $selected_brand_type->save();
        }
        $selected_model_year = Product_feature::where('product_id',$id)->where('option_type','model_year')->first();
        if($request->brand_id != $selected_model_year->id){
            $selected_model_year->target_id = $request->model_year_id;
            $selected_model_year->save();
        }
        $selected_counter = Product_feature::where('product_id',$id)->where('option_type','counter')->first();
        if($request->brand_id != $selected_counter->id){
            $selected_counter->target_id = $request->counter_id;
            $selected_counter->save();
        }
        return redirect()->route('products.index');
    }
    // delete product image
    public function delete_product_image($id)
    {
        $image_data = ProductImage::where('id',$id)->first();
        $image = $image_data->image;
        $publicId = substr($image, 0 ,strrpos($image, "."));
        Cloudder::delete($publicId);
        ProductImage::where('id',$id)->delete();
        return redirect()->back();
    }
    // product details
    public function details($product_id)
    {
        $data = Product::where('id', $product_id)->first();
        return view('admin.products.product_details', compact('data'));
    }
    // delete product
    public function delete(Product $product)
    {
        if (count($product->images) > 0) {
            foreach ($product->images as $image) {
                $publicId = substr($image->image, 0, strrpos($image->image, "."));
                Cloudder::delete($publicId);
                $image->delete();
            }
        }
        $product->deleted = 1;
        $product->save();
        return redirect()->back();
    }

    public function get_sub_cat(Request $request,$id)
    {
        $data = SubCategory::where('category_id',$id)->where('deleted',0)->get();
        return view('admin.products.parts.categories.sub_category',compact('data'));
    }
    public function get_sub_two_cat(Request $request,$id)
    {
        $data = SubTwoCategory::where('sub_category_id',$id)->where('deleted',0)->get();
        return view('admin.products.parts.categories.sub_two_categories',compact('data'));
    }
    public function get_sub_three_cat(Request $request,$id)
    {
        $data = SubThreeCategory::where('sub_category_id',$id)->where('deleted',0)->get();
        return view('admin.products.parts.categories.sub_three_categories',compact('data'));
    }
    public function get_sub_four_cat(Request $request,$id)
    {
        $data = SubFourCategory::where('sub_category_id',$id)->where('deleted',0)->get();
        return view('admin.products.parts.categories.sub_four_categories',compact('data'));
    }
    public function get_sub_five_cat(Request $request,$id)
    {
        $data = SubFiveCategory::where('sub_category_id',$id)->where('deleted','0')->get();
        return view('admin.products.parts.categories.sub_five_categories',compact('data'));
    }
    public function get_brands(Request $request,$id)
    {
        $cat_option = Category_option::where('cat_id',$id)->where('title_en','brand')->first();

        $data = Category_option_value::where('option_id',$cat_option->id)->where('deleted','0')->get();

        return view('admin.products.parts.options.brands',compact('data'));
    }
    public function get_brand_types(Request $request,$id)
    {
        $cat_option = Category_option::where('cat_id',$id)->where('title_en','brand type')->first();
        $data = Category_option_value::where('option_id',$cat_option->id)->where('deleted','0')->get();
        return view('admin.products.parts.options.brand_types',compact('data'));
    }
    public function get_model_year(Request $request,$id)
    {
        $cat_option = Category_option::where('cat_id',$id)->where('title_en','model year')->first();
        $data = Category_option_value::where('option_id',$cat_option->id)->where('deleted','0')->get();
        return view('admin.products.parts.options.model_years',compact('data'));
    }
    public function get_counter(Request $request,$id)
    {
        $cat_option = Category_option::where('cat_id',$id)->where('title_en','speedometer')->first();
        $data = Category_option_value::where('option_id',$cat_option->id)->where('deleted','0')->get();
        return view('admin.products.parts.options.counter',compact('data'));
    }
    public function get_plan(Request $request,$id)
    {
        $data = Plan::where('status' , 'show')
                    ->where('cat_id' , $id)
                    ->orwhere('cat_id' , 'all')
                    ->get();
        return view('admin.products.parts.plans.plans',compact('data'));
    }

}
