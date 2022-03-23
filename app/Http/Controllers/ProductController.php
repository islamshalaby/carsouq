<?php

namespace App\Http\Controllers;

use App\Category;
use App\Category_option;
use App\Category_option_value;
use App\Marka;
use App\MarkaType;
use App\Plan;
use App\Plan_details;
use App\Product_feature;
use App\Product_view;
use App\SubCategory;
use App\SubFiveCategory;
use App\SubFourCategory;
use App\SubThreeCategory;
use App\SubTwoCategory;
use App\TypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Helpers\APIHelpers;
use App\User;
use App\Favorite;
use App\Product;
use App\ProductImage;
use App\Setting;
use JD\Cloudder\Facades\Cloudder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['get_special_ads', 'republish_ad', 'third_step_excute_pay', 'save_third_step_with_money', 'update_ad', 'select_ad_data', 'delete_my_ad', 'save_third_step', 'save_second_step', 'save_first_step', 'getdetails', 'getoffers', 'getproducts', 'getsearch', 'getFeatureOffers', 'third_step_excute_pay2', 'execute_response']]);
        //        --------------------------------------------- begin scheduled functions --------------------------------------------------------
        $expired = Product::where('status', 1)->whereDate('expiry_date', '<', Carbon::now())->get();
        foreach ($expired as $row) {
            $product = Product::find($row->id);
            $product->status = 2;
            $product->re_post = '0';
            $product->save();
        }

        $not_special = Product::where('status', 1)->where('is_special', '1')->whereDate('expire_special_date', '<', Carbon::now())->get();
        foreach ($not_special as $row) {
            $product_special = Product::find($row->id);
            $product_special->is_special = '0';
            $product_special->save();
        }
        $mytime = Carbon::now();
        $today = Carbon::parse($mytime->toDateTimeString())->format('Y-m-d H:i');
        $re_post_ad = Product::where('status', 1)->where('re_post', '1')->whereDate('re_post_date', '<', Carbon::now())->get();
        foreach ($re_post_ad as $row) {

            $product_re_post = Product::find($row->id);
            $product_re_post->created_at = Carbon::now();
            // to generate new next repost date ...
            $re_post = Plan_details::where('plan_id', $row->plan_id)->where('type', 're_post')->first();
            $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
            $final_expire_re_post_date = $final_pin_date->addDays($re_post->expire_days);

            $product_re_post->re_post_date = $final_expire_re_post_date;
            $product_re_post->save();
        }

        $pin_ad = Product::where('status', 1)->where('pin', 1)->whereDate('expire_pin_date', '<', Carbon::now())->get();
        foreach ($pin_ad as $row) {
            $product_pined = Product::find($row->id);
            $product_pined->pin = '0';
            $product_pined->save();
        }

        $pin_ad = Setting::where('id', 1)->whereDate('free_loop_date', '<', Carbon::now())->first();
        if ($pin_ad != null) {
            if ($pin_ad->is_loop_free_balance == 'y') {
                $all_users = User::where('active', 1)->get();
                foreach ($all_users as $row) {
                    $user = User::find($row->id);
                    $user->my_wallet = $user->my_wallet + $pin_ad->free_loop_balance;
                    $user->free_balance = $user->free_balance + $pin_ad->free_loop_balance;
                    $user->save();
                }
                $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                $final_free_loop_date = $final_pin_date->addDays($pin_ad->free_loop_period);
                $pin_ad->free_loop_date = $final_free_loop_date;
                $pin_ad->save();
            }
        }
        //        --------------------------------------------- end scheduled functions --------------------------------------------------------


    }

    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->active == 0) {
            $response = APIHelpers::createApiResponse(true, 406, 'تم حظر حسابك', null);
            return response()->json($response, 406);
        }

        if ($user->free_ads_count == 0 && $user->paid_ads_count == 0) {
            $response = APIHelpers::createApiResponse(true, 406, 'ليس لديك رصيد إعلانات لإضافه إعلان جديد يرجي شراء باقه إعلانات', null);
            return response()->json($response, 406);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            "type" => "required",
            "title" => "required",
            "description" => "required",
            "price" => "required",
            "image" => "required"
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true, 406, 'بعض الحقول مفقودة', null);
            return response()->json($response, 406);
        }

        if ($user->free_ads_count > 0) {
            $count = $user->free_ads_count;
            $user->free_ads_count = $count - 1;
        } else {
            $count = $user->paid_ads_count;
            $user->paid_ads_count = $count - 1;
        }

        $user->save();

        $ad_period = Setting::find(1)['ad_period'];

        $product = new Product();
        $product->title = $request->title;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->type = $request->type;
        $product->user_id = $user->id;
        $product->publication_date = date("Y-m-d H:i:s");
        $product->expiry_date = date('Y-m-d H:i:s', strtotime('+' . $ad_period . ' days'));

        $product->save();

        $image = $request->image;
        Cloudder::upload("data:image/jpeg;base64," . $image, null);
        $imagereturned = Cloudder::getResult();
        $image_id = $imagereturned['public_id'];
        $image_format = $imagereturned['format'];
        $image_new_name = $image_id . '.' . $image_format;
        $product_image = new ProductImage();
        $product_image->image = $image_new_name;
        $product_image->product_id = $product->id;
        $product_image->save();

        $product->image = $image_new_name;

        $response = APIHelpers::createApiResponse(false, 200, '', $product);
        return response()->json($response, 200);
    }

    public function uploadimages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true, 406, 'بعض الحقول مفقودة', null);
            return response()->json($response, 406);
        }

        $image = $request->image;
        Cloudder::upload("data:image/jpeg;base64," . $image, null);
        $imagereturned = Cloudder::getResult();
        $image_id = $imagereturned['public_id'];
        $image_format = $imagereturned['format'];
        $image_new_name = $image_id . '.' . $image_format;
        $product_image = new ProductImage();
        $product_image->image = $image_new_name;
        $product_image->product_id = $request->product_id;
        $product_image->save();
        $response = APIHelpers::createApiResponse(false, 200, '', $product_image);
        return response()->json($response, 200);
    }

    public function getdetails(Request $request)
    {
        $user = auth()->user();
        $lang = $request->lang;
        Session::put('lang', $lang);
        $data = Product::with('Product_user')
            ->select('id', 'title', 'main_image', 'description', 'price', 'type', 'publication_date as date', 'user_id', 'category_id')
            ->find($request->id);
        if ($user == null) {
            $data_view['ip'] = 0;
            $data_view['product_id'] = $data->id;
            Product_view::create($data_view);
        } else {
            $data_view['ip'] = $user->id;
            $data_view['product_id'] = $data->id;
            Product_view::create($data_view);
        }
        $features = Product_feature::where('product_id', $request->id)
            ->select('id', 'type', 'product_id', 'target_id', 'option_id')
            ->get();

        $feature_data = null;
        foreach ($features as $key => $feature) {

            $feature_data[$key]['image'] = $feature->Option->image;
            if ($feature->type == 'manual') {
                $feature_data[$key]['title'] = $feature->Option->title . ' : ' . $feature->target_id;
            } else if ($feature->type == 'option') {
                $feature_data[$key]['title'] = $feature->Option->title . ' : ' . $feature->Option_value->value;
            }
        }
        if ($user) {
            $favorite = Favorite::where('user_id', $user->id)->where('product_id', $data->id)->first();
            if ($favorite) {
                $data->favorite = true;
            } else {
                $data->favorite = false;
            }
        } else {
            $data->favorite = false;
        }
        $date = date_create($data->date);
        $data->date = date_format($date, 'd M Y');
        $data->time = date_format($date, 'g:i a');
        $data->likes = Favorite::where('product_id', $data->id)->count();
        $user_product = User::find($data->user_id);
        $images = ProductImage::where('product_id', $data->id)->pluck('image')->toArray();
        if (count($images) > 0) {
			$images[count($images)] = $data->main_image;
		}
        $data->images = $images;
        $related = Product::where('category_id', $data->category_id)
            ->where('id', '!=', $data->id)
            ->where('status', 1)
            ->where('publish', 'Y')
            ->where('deleted', 0)
            ->select('id', 'title', 'price', 'type', 'main_image as image','created_at')
            ->limit(3)
            ->get();
        for ($i = 0; $i < count($related); $i++) {
            if ($user) {
                $favorite = Favorite::where('user_id', $user->id)->where('product_id', $related[$i]['id'])->first();
                if ($favorite) {
                    $related[$i]['favorite'] = true;
                } else {
                    $related[$i]['favorite'] = false;
                }
            } else {
                $related[$i]['favorite'] = false;
            }
        }
        $views = Product_view::where('product_id', $data->id)->count();
        $response = APIHelpers::createApiResponse(false, 200, '', '', array('product' => $data,
            'features' => $feature_data, 'related' => $related, 'views' => $views), $request->lang);
        return response()->json($response, 200);
    }

    public function getoffers(Request $request)
    {
        $products = Product::where('offer', 1)->select('id', 'title', 'price', 'type', 'publication_date as date')->orderBy('publication_date', 'DESC')->where('status', 1)->where('deleted', 0)->where('publish', 'Y')->simplePaginate(12);
        for ($i = 0; $i < count($products); $i++) {
            $date = date_create($products[$i]['date']);
            $products[$i]['date'] = date_format($date, 'd M Y');
            $products[$i]['image'] = ProductImage::where('product_id', $products[$i]['id'])->select('image')->first()['image'];
            $user = auth()->user();
            if ($user) {
                $favorite = Favorite::where('user_id', $user->id)->where('product_id', $products[$i]['id'])->first();
                if ($favorite) {
                    $products[$i]['favorite'] = true;
                } else {
                    $products[$i]['favorite'] = false;
                }
            } else {
                $products[$i]['favorite'] = false;
            }

        }
        $response = APIHelpers::createApiResponse(false, 200, '', '', $products, $request->lang);
        return response()->json($response, 200);
    }

    public function get_special_ads(Request $request)
    {
        $products = Product::where('is_special', '1')->select('id', 'title', 'price', 'type', 'publication_date as date')->orderBy('publication_date', 'DESC')->where('status', 1)->where('deleted', 0)->where('publish', 'Y')->simplePaginate(12);
        for ($i = 0; $i < count($products); $i++) {
            $date = date_create($products[$i]['date']);
            $products[$i]['date'] = date_format($date, 'd M Y');
            $products[$i]['image'] = ProductImage::where('product_id', $products[$i]['id'])->select('image')->first()['image'];
            $user = auth()->user();
            if ($user) {
                $favorite = Favorite::where('user_id', $user->id)->where('product_id', $products[$i]['id'])->first();
                if ($favorite) {
                    $products[$i]['favorite'] = true;
                } else {
                    $products[$i]['favorite'] = false;
                }
            } else {
                $products[$i]['favorite'] = false;
            }

        }
        $response = APIHelpers::createApiResponse(false, 200, '', '', $products, $request->lang);
        return response()->json($response, 200);
    }

    public function getproducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true, 406, 'بعض الحقول مفقودة', 'بعض الحقول مفقودة', null, $request->lang);
            return response()->json($response, 406);
        }

        if ($request->type) {
            $type = [$request->type];
        } else {
            $type = [1, 2];
        }

        $products = Product::where('status', 1)->where('deleted', 0)->where('publish', 'Y')->whereIn('type', $type)->where('category_id', $request->category_id)->select('id', 'title', 'price', 'type', 'publication_date as date')->orderBy('publication_date', 'DESC')->simplePaginate(12);

        for ($i = 0; $i < count($products); $i++) {
            $date = date_create($products[$i]['date']);
            $products[$i]['date'] = date_format($date, 'd M Y');
            $products[$i]['image'] = ProductImage::where('product_id', $products[$i]['id'])->first()['image'];
            $user = auth()->user();
            if ($user) {
                $favorite = Favorite::where('user_id', $user->id)->where('product_id', $products[$i]['id'])->first();
                if ($favorite) {
                    $products[$i]['favorite'] = true;
                } else {
                    $products[$i]['favorite'] = false;
                }
            } else {
                $products[$i]['favorite'] = false;
            }
        }
        $response = APIHelpers::createApiResponse(false, 200, '', '', $products, $request->lang);
        return response()->json($response, 200);

    }

    public function getsearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'required'
        ]);
        $search = $request->search;
        $products = Product::where('publish', 'Y')
            ->where('deleted', 0)
            ->where('status', 1)
            ->where('publish', 'Y')
            ->select('id', 'title', 'price', 'type', 'main_image', 'pin', 'publication_date as created_at')
            ->Where(function ($query) use ($search) {
                $query->Where('title', 'like', '%' . $search . '%');
            })
            ->orderBy('pin', 'desc')
            ->orderBy('created_at', 'desc')
            ->simplePaginate(12);
//        dd($products);
        for ($i = 0; $i < count($products); $i++) {
//            $date = date_create($products[$i]['date']);
//            $products[$i]['date'] = date_format($date, 'd M Y');
            $products[$i]['main_image'] = $products[$i]['main_image'];
            $user = auth()->user();
            if ($user) {
                $favorite = Favorite::where('user_id', $user->id)->where('product_id', $products[$i]['id'])->first();
                if ($favorite) {
                    $products[$i]['favorite'] = true;
                } else {
                    $products[$i]['favorite'] = false;
                }
            } else {
                $products[$i]['favorite'] = false;
            }
        }
        $response = APIHelpers::createApiResponse(false, 200, '', '', $products, $request->lang);
        return response()->json($response, 200);

    }

    public function range_price_search(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'from_price' => 'required',
            'to_price' => 'required'
        ]);
        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true, 406, $validator->messages()->first(), $validator->messages()->first(), null, $request->lang);
            return response()->json($response, 406);
        } else {
            $from = $request->from_price;
            $to = $request->to_price;
            $products = Product::where('publish', 'Y')
                ->where('status', 1)
                ->where('deleted', 0)
                ->whereRaw('price BETWEEN ' . $from . ' AND ' . $to . '')
                ->select('id', 'title', 'main_image', 'price', 'pin', 'publication_date')
                ->orderBy('pin', 'desc')
                ->orderBy('created_at', 'desc')
                ->simplePaginate(12);
            $response = APIHelpers::createApiResponse(false, 200, '', '', $products, $request->lang);
            return response()->json($response, 200);
        }
    }

    public function getFeatureOffers(Request $request)
    {
        $products = Product::where('is_special', '1')
            ->select('id', 'title', 'price', 'main_image','publication_date as created_at')
            ->orderBy('publication_date', 'DESC')
            ->where('status', 1)
            ->where('deleted', 0)
            ->where('publish', 'Y')
            ->simplePaginate(12);
        $response = APIHelpers::createApiResponse(false, 200, '', '', $products, $request->lang);
        return response()->json($response, 200);
    }

    //nasser code

    //to create ad you need 3 steps
    public function save_first_step(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'category_id' => 'required',
            'sub_category_id' => '',
            'sub_category_two_id' => '',
            'sub_category_three_id' => '',
            'sub_category_four_id' => '',
            'sub_category_five_id' => '',
            'title' => 'required',
            'price' => '',
            'options' => '',
            'description' => ''
        ]);
        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true, 406, $validator->messages()->first(), $validator->messages()->first(), null, $request->lang);
            return response()->json($response, 406);
        } else {
            $user = auth()->user();
            if ($user != null) {
                $input['user_id'] = $user->id;
            } else {
                $response = APIHelpers::createApiResponse(true, 406, '', 'يجب تسجيل الدخول اولا', null, $request->lang);
                return response()->json($response, 406);
            }
            if ($request->price == null) {
                $input['price'] = '0';
            }
            if ($request->ad_id && $request->ad_id != 0) {
                $ad_data = Product::where('id', $request->ad_id)->first();
                $ad_data->update($input);
                if($request->options != null) {
                    $proFeatures = Product_feature::where('product_id', $ad_data->id)->get();
                    if (count($proFeatures) > 0) {
                        for ($i = 0; $i < count($proFeatures); $i ++) {
                            $proFeatures[$i]->delete();
                        }
                    }
                    foreach ($request->options as $key => $option) {
                        if($option['option_value'] != null) {
                            if (is_numeric($option['option_value'])) {
                                $option_values = Category_option_value::where('id', $option['option_value'])->first();
                                if ($option_values != null) {
                                    $feature_data['type'] = 'option';
                                } else {
                                    $feature_data['type'] = 'manual';
                                }
                            } else {
                                $feature_data['type'] = 'manual';
                            }
                            $feature_data['product_id'] = $ad_data->id;
                            $feature_data['target_id'] = $option['option_value'];
                            $feature_data['option_id'] = $option['option_id'];
                            Product_feature::create($feature_data);
                        }
                    }
                }
            }else {
                $ad_data = Product::create($input);
                if($request->options != null) {
                    foreach ($request->options as $key => $option) {
                        if($option['option_value'] != null) {
                            if (is_numeric($option['option_value'])) {
                                $option_values = Category_option_value::where('id', $option['option_value'])->first();
                                if ($option_values != null) {
                                    $feature_data['type'] = 'option';
                                } else {
                                    $feature_data['type'] = 'manual';
                                }
                            } else {
                                $feature_data['type'] = 'manual';
                            }
                            $feature_data['product_id'] = $ad_data->id;
                            $feature_data['target_id'] = $option['option_value'];
                            $feature_data['option_id'] = $option['option_id'];
                            Product_feature::create($feature_data);
                        }
                    }
                }
            }

            $response = APIHelpers::createApiResponse(false, 200, '', '', $ad_data, $request->lang);
            return response()->json($response, 200);
        }
    }

    public function save_second_step(Request $request)
    {
        $input = $request->all();
        $messages = [
            "images.reuired" => "Images are required"
        ];
        if ($request->lang == 'ar') {
            $messages = [
                "images.reuired" => "اختر صورة واحدة على الأقل"
            ];
        }
        $validator = Validator::make($input, [
            'ad_id' => 'required|exists:products,id',
            'main_image' => 'required',
            'images' => 'required',
        ], $messages);
        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true, 406, $validator->messages()->first(), $validator->messages()->first(), $validator->messages()->first(), $request->lang);
            return response()->json($response, 406);
        } else {
            if (auth()->user() != null) {
                $product = Product::where('id', $request->ad_id)->first();
                if (count($product->images) > 0) {
                    for ($i = 0; $i < count($product->images); $i ++) {
                        $product->images[$i]->delete();
                    }
                }
                $image = $request->main_image;
                Cloudder::upload("data:image/jpeg;base64," . $image, null);
                $imagereturned = Cloudder::getResult();
                $image_id = $imagereturned['public_id'];
                $image_format = $imagereturned['format'];
                $image_new_name = $image_id . '.' . $image_format;

                $product->main_image = $image_new_name;
                $product->save();

                foreach ($request->images as $image) {
                    Cloudder::upload("data:image/jpeg;base64," . $image, null);
                    $imagereturned = Cloudder::getResult();
                    $image_id = $imagereturned['public_id'];
                    $image_format = $imagereturned['format'];
                    $image_name = $image_id . '.' . $image_format;

                    $data['product_id'] = $request->ad_id;
                    $data['image'] = $image_name;
                    ProductImage::create($data);
                }
                $response = APIHelpers::createApiResponse(false, 200, 'image saved successfully', 'تم حفظ الصور بنجاح', null, $request->lang);
                return response()->json($response, 200);
            } else {
                $response = APIHelpers::createApiResponse(true, 406, '', 'يجب تسجيل الدخول اولا', null, $request->lang);
                return response()->json($response, 406);
            }
        }
    }

    // get ad images
    public function getAdImages(Request $request) {
        $images = ProductImage::where('product_id', $request->ad_id)->select('id', 'image')->get();

        $response = APIHelpers::createApiResponse(false, 200, '', '', $images, $request->lang);
        return response()->json($response, 200);
    }

    public function save_third_step(Request $request, $ad_id, $plan_id)
    {
        //when user have enghe money in wallet
        if (auth()->user() != null) {
            $user = User::where('id', auth()->user()->id)->first();
            $selected_plan = Plan::where('id', $plan_id)->first();
            $plan_ads_number = $selected_plan->ads_count;
            $plan_price = $selected_plan->price;
            if ($plan_price <= $user->my_wallet) {
                $product = Product::where('id',$ad_id)->first();

                //to select expire days of selected plane
                $plan_detail = Plan_details::where('plan_id', $plan_id)->where('type', 'expier_num')->first();
                $expire_days = $plan_detail->expire_days;

                $user->my_wallet = $user->my_wallet - $plan_price;
                if ($user->free_balance >= $plan_price) {
                    $user->free_balance = $user->free_balance - $plan_price;
                } else if ($user->payed_balance >= $plan_price) {
                    $user->payed_balance = $user->payed_balance - $plan_price;
                } else {
                    $free_balance = $user->free_balance;  //70
                    $payed_balance = $user->payed_balance;  //30
                    $price = $plan_price; //100
                    $after_min_free = $price - $free_balance;  // 100 - 70 = 30
                    if ($after_min_free <= $payed_balance && $after_min_free > 0) {
                        // 30 <= 30 && 30 < 0
                        $user->free_balance = 0;
                        $user->payed_balance = $user->payed_balance - $after_min_free;
                    } else if ($after_min_free > $payed_balance && $after_min_free > 0) {
                        $after_min_payed = $price - $payed_balance;
                        $user->free_balance = $user->free_balance - $after_min_payed;
                        $user->payed_balance = 0;
                    }
                }
                $user->save();

                //to get the expire_date of ad
                $mytime = Carbon::now();
                $today = Carbon::parse($mytime->toDateTimeString())->format('Y-m-d H:i');
                $ad_data = null;
                $pin = Plan_details::where('plan_id', $plan_id)->where('type', 'pin')->first();
                if ($pin != null) {
                    $expire_pin_date = $pin->expire_days;
                    $ad_data['pin'] = 1;
                    //to create expire pin date
                    $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                    $final_expire_pin_date = $final_pin_date->addDays($expire_pin_date);
                    $ad_data['expire_pin_date'] = $final_expire_pin_date;
                }
                $re_post = Plan_details::where('plan_id', $plan_id)->where('type', 're_post')->first();
                if ($re_post != null) {
                    $expire_re_post_date = $re_post->expire_days;
                    $ad_data['re_post'] = '1';
                    //to create expire pin date
                    $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                    $final_expire_re_post_date = $final_pin_date->addDays($expire_re_post_date);
                    $ad_data['re_post_date'] = $final_expire_re_post_date;
                }
                $special = Plan_details::where('plan_id', $plan_id)->where('type', 'special')->first();
                if ($special != null) {
                    $expire_special_date = $special->expire_days;
                    $ad_data['is_special'] = '1';
                    //to create expire pin date
                    $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                    $final_expire_special_date = $final_pin_date->addDays($expire_special_date);
                    $ad_data['expire_special_date'] = $final_expire_special_date;
                }

                $final_today = Carbon::createFromFormat('Y-m-d H:i', $today);
                $expire_date = $final_today->addDays($expire_days);

                $ad_data['publish'] = 'Y';
                $ad_data['plan_id'] = $plan_id;
                $ad_data['publication_date'] = $today;
                $ad_data['expiry_date'] = $expire_date;
                if($product->status == 1) {
                    Product::where('id', $ad_id)->update($ad_data);
                    $response = APIHelpers::createApiResponse(false, 200, 'your ad added successfully', 'تم أنشاء الاعلان بنجاح', (object)[], $request->lang);
                    return response()->json($response, 200);
                }else{
                    $ad_data['status'] = 1;
                    $ad_data['publish'] = 'Y';
                    Product::where('id', $ad_id)->update($ad_data);
                    $response = APIHelpers::createApiResponse(false, 200, 'republish done', 'تم اعادة النشر بنجاح', null, $request->lang);
                    return response()->json($response, 200);
                }
            } else {
                $response = APIHelpers::createApiResponse(true, 406, 'Your wallet does not contain enough amount to create an ad',
                    'محفظتك لا تحتوى على المبلغ الكافى لانشاء الاعلانا', (object)[], $request->lang);
                return response()->json($response, 406);
            }
        } else {
            $response = APIHelpers::createApiResponse(true, 406, '', 'يجب تسجيل الدخول اولا', (object)[], $request->lang);
            return response()->json($response, 406);
        }
    }

    // add balance to wallet
    public function save_third_step_with_money(Request $request)
    {
        $plan = Plan::where('id', $request->plan_id)->first();
        if ($plan == null) {
            $response = APIHelpers::createApiResponse(true, 406, 'يجب اختيار خطة صحيحة', 'plan not found ', (object)[], $request->lang);
            return response()->json($response, 406);
        }

        $products = Product::where('id', $request->ad_id)->first();

        if ($products == null) {
            $response = APIHelpers::createApiResponse(true, 406, 'يجب اختيار اعلان صحيحة', 'Ad not found ', (object)[], $request->lang);
            return response()->json($response, 406);
        }
        $user = auth()->user();
        $root_url = $request->root();
        $path = 'https://apitest.myfatoorah.com/v2/SendPayment';
        $token = "bearer rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";
        $headers = array(
            'Authorization:' . $token,
            'Content-Type:application/json'
        );
        $call_back_url = $root_url . "/api/ad/save_third_step/excute_pay?user_id=" . $user->id . "&plan_id=" . $request->plan_id . "&ad_id=" . $request->ad_id;
        $error_url = $root_url . "/api/pay/error";
        $fields = array(
            "CustomerName" => $user->name,
            "NotificationOption" => "LNK",
            "InvoiceValue" => $plan->price,
            "CallBackUrl" => $call_back_url,
            "ErrorUrl" => $error_url,
            "Language" => "AR",
            "CustomerEmail" => $user->email
        );

        $payload = json_encode($fields);
        $curl_session = curl_init();
        curl_setopt($curl_session, CURLOPT_URL, $path);
        curl_setopt($curl_session, CURLOPT_POST, true);
        curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURLOPT_IPRESOLVE);
        curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);
        $result = curl_exec($curl_session);
        curl_close($curl_session);
        $result = json_decode($result);

        $data['url'] = $result->Data->InvoiceURL;
        $response = APIHelpers::createApiResponse(false, 200, '', '', $data, $request->lang);
        return response()->json($response, 200);
    }

    // add balance to wallet
    public function save_third_step_with_money2(Request $request)
    {
        $plan = Plan::where('id', $request->plan_id)->first();
        if ($plan == null) {
            $response = APIHelpers::createApiResponse(true, 406, 'يجب اختيار خطة صحيحة', 'plan not found ', (object)[], $request->lang);
            return response()->json($response, 406);
        }

        $products = Product::where('id', $request->ad_id)->first();

        if ($products == null) {
            $response = APIHelpers::createApiResponse(true, 406, 'يجب اختيار اعلان صحيحة', 'Ad not found ', (object)[], $request->lang);
            return response()->json($response, 406);
        }
        $user = auth()->user();
        $root_url = $request->root();
        $price = $plan->price;
        $qty = 1;
        $TranAmount = $price * $qty;
        $TranTrackid=mt_rand();
        $TranportalId=env('TRANPORTAL_ID');
        $ReqTranportalId="id=".$TranportalId;
        $ReqTranportalPassword="password=" . env('TRANPORTAL_PASSWORD');
        $ReqAmount="amt=".$TranAmount;
        $ReqTrackId="trackid=".$TranTrackid;
        $ReqCurrency="currencycode=414";
        $ReqLangid="langid=AR";
        $ReqAction="action=1";
        /* Response URL where Payment gateway will send response once transaction processing is completed
        Merchant MUST esure that below points in Response URL
        1- Response URL must start with http://
        2- the Response URL SHOULD NOT have any additional paramteres or query string  */
        $ResponseUrl=$root_url . "/api/ad/save_third_step/excute_pay2";
        $ReqResponseUrl="responseURL=".$ResponseUrl;

        /* Error URL where Payment gateway will send response in case any issues while processing the transaction
        Merchant MUST esure that below points in ErrorURL
        1- error url must start with http://
        2- the error url SHOULD NOT have any additional paramteres or query string
        */

        $ErrorUrl=$root_url . "/api/pay/error";
        $ReqErrorUrl="errorURL=".$ErrorUrl;

        $ReqUdf1="udf1=" . $user->id;
        $ReqUdf2="udf2=" . $request->plan_id;
        $ReqUdf3="udf3=" . $request->ad_id;
        $ReqUdf4="udf4=Test4";
        $ReqUdf5="udf5=Test5";
        $param=$ReqTranportalId."&".$ReqTranportalPassword."&".$ReqAction."&".$ReqLangid."&".$ReqCurrency."&".$ReqAmount."&".$ReqResponseUrl."&".$ReqErrorUrl."&".$ReqTrackId."&".$ReqUdf1."&".$ReqUdf2."&".$ReqUdf3."&".$ReqUdf4."&".$ReqUdf5;
        /*Terminal Resource Key is generated while creating terminal, And this the Key that is used for encryting
	  the request/response from Merchant To PG and vice Versa*/

        $termResourceKey=env("KNET_RESOURCE_KEY");
        $param=$this->encryptAES($param,$termResourceKey)."&tranportalId=".$TranportalId."&responseURL=".$ResponseUrl."&errorURL=".$ErrorUrl;

        $data['url'] = "https://kpay.com.kw/kpg/PaymentHTTP.htm?param=paymentInit" . "&trandata=".$param;

        $response = APIHelpers::createApiResponse(false, 200, '', '', $data, $request->lang);
        return response()->json($response, 200);
    }

    // excute pay
    public function third_step_excute_pay(Request $request)
    {
        //after customer pay the price of plan ..
        $plan_id = $request->plan_id;
        if (auth()->user() != null) {
            $user = User::where('id', $request->user_id)->first();
            $product = Product::where('id',$request->ad_id)->first();
            $selected_plan = Plan::where('id', $plan_id)->first();
            $plan_ads_number = $selected_plan->ads_count;
            $plan_price = $selected_plan->price;
            //to select expire days of selected plane
            $plan_detail = Plan_details::where('plan_id', $plan_id)->where('type', 'expier_num')->first();
            $expire_days = $plan_detail->expire_days;
            //to get the expire_date of ad
            $mytime = Carbon::now();
            $today = Carbon::parse($mytime->toDateTimeString())->format('Y-m-d H:i');
            $ad_data = null;
            $pin = Plan_details::where('plan_id', $plan_id)->where('type', 'pin')->first();
            if ($pin != null) {
                $expire_pin_date = $pin->expire_days;
                $ad_data['pin'] = 1;
                //to create expire pin date
                $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                $final_expire_pin_date = $final_pin_date->addDays($expire_pin_date);
                $ad_data['expire_pin_date'] = $final_expire_pin_date;
            }
            $re_post = Plan_details::where('plan_id', $plan_id)->where('type', 're_post')->first();
            if ($re_post != null) {
                $expire_re_post_date = $re_post->expire_days;
                $ad_data['re_post'] = '1';
                //to create expire pin date
                $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                $final_expire_re_post_date = $final_pin_date->addDays($expire_re_post_date);
                $ad_data['re_post_date'] = $final_expire_re_post_date;
            }
            $special = Plan_details::where('plan_id', $plan_id)->where('type', 'special')->first();
            if ($special != null) {
                $expire_special_date = $special->expire_days;
                $ad_data['is_special'] = '1';
                //to create expire pin date
                $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                $final_expire_special_date = $final_pin_date->addDays($expire_special_date);
                $ad_data['expire_special_date'] = $final_expire_special_date;
            }

            $final_today = Carbon::createFromFormat('Y-m-d H:i', $today);
            $expire_date = $final_today->addDays($expire_days);

            $ad_data['publish'] = 'Y';
            $ad_data['plan_id'] = $plan_id;
            $ad_data['publication_date'] = $today;
            $ad_data['expiry_date'] = $expire_date;
            if($product->status == 1) {
                Product::where('id', $request->ad_id)->update($ad_data);
            }else{
                $ad_data['status'] = 1;
                $ad_data['publish'] = 'Y';
                Product::where('id', $request->ad_id)->update($ad_data);
            }
            return redirect('api/pay/success');
        } else {
            return redirect('api/pay/error');
        }
    }

    // excute pay
    public function third_step_excute_pay2(Request $request)
    {
        //$ResErrorText= $_REQUEST['ErrorText']; 	  	//Error Text/message
        $ResPaymentId = $_REQUEST['paymentid'];		//Payment Id
        $ResTrackID = $_REQUEST['trackid'];       	//Merchant Track ID
        //$ResErrorNo = $_REQUEST['Error'];           //Error Number
        $ResResult =  $_REQUEST['result'];          //Transaction Result
        $ResPosdate = $_REQUEST['postdate'];        //Postdate
        $ResTranId = $_REQUEST['tranid'];           //Transaction ID
        $ResAuth = $_REQUEST['auth'];               //Auth Code
        $ResAVR = $_REQUEST['avr'];                 //TRANSACTION avr
        $ResRef = $_REQUEST['ref'];                 //Reference Number also called Seq Number
        $ResAmount = $_REQUEST['amt'];              //Transaction Amount
        $Resudf1 = $_REQUEST['udf1'];               //UDF1
        $Resudf2 = $_REQUEST['udf2'];               //UDF2

        //Below Terminal resource Key is used to decrypt the response sent from Payment Gateway.
        $termResourceKey=env("KNET_RESOURCE_KEY");

        //if($ResErrorText==null && $ResErrorNo==null)
        // {

        /*IMPORTANT NOTE - MERCHANT SHOULD UPDATE
                        TRANACTION PAYMENT STATUS IN MERCHANT DATABASE AT THIS POSITION
                        AND THEN REDIRECT CUSTOMER ON RESULT PAGE*/
        $ResTranData= $_REQUEST['trandata'];

        if($ResTranData !=null)
        {
            //Decryption logice starts
            $decrytedData=$this->decrypt($ResTranData,$termResourceKey);

            return redirect()->route('addAdExecuteResponse', $decrytedData);
        }
    }

    public function execute_response(Request $request) {
        $data = $request->all();
        $data['today'] = Carbon::now()->format('d-m-Y');
        $data['setting'] = Setting::where('id', 1)->first();
        $package = Plan::findOrFail($data['udf2']);
        $user = User::where('id', $data['udf1'])->first();
        $data['user'] = $user;
        $data['package'] = $package;
        $plan_id = $data['udf2'];
        if ($request->result == 'CAPTURED') {

            if ($package != null) {
                $product = Product::where('id',$data['udf3'])->first();
                $selected_plan = Plan::where('id', $plan_id)->first();
                $plan_ads_number = $selected_plan->ads_count;
                $plan_price = $selected_plan->price;
                //to select expire days of selected plane
                $plan_detail = Plan_details::where('plan_id', $plan_id)->where('type', 'expier_num')->first();
                $expire_days = $plan_detail->expire_days;
                //to get the expire_date of ad
                $mytime = Carbon::now();
                $today = Carbon::parse($mytime->toDateTimeString())->format('Y-m-d H:i');
                $ad_data = null;
                $pin = Plan_details::where('plan_id', $plan_id)->where('type', 'pin')->first();
                if ($pin != null) {
                    $expire_pin_date = $pin->expire_days;
                    $ad_data['pin'] = 1;
                    //to create expire pin date
                    $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                    $final_expire_pin_date = $final_pin_date->addDays($expire_pin_date);
                    $ad_data['expire_pin_date'] = $final_expire_pin_date;
                }
                $re_post = Plan_details::where('plan_id', $plan_id)->where('type', 're_post')->first();
                if ($re_post != null) {
                    $expire_re_post_date = $re_post->expire_days;
                    $ad_data['re_post'] = '1';
                    //to create expire pin date
                    $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                    $final_expire_re_post_date = $final_pin_date->addDays($expire_re_post_date);
                    $ad_data['re_post_date'] = $final_expire_re_post_date;
                }
                $special = Plan_details::where('plan_id', $plan_id)->where('type', 'special')->first();
                if ($special != null) {
                    $expire_special_date = $special->expire_days;
                    $ad_data['is_special'] = '1';
                    //to create expire pin date
                    $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                    $final_expire_special_date = $final_pin_date->addDays($expire_special_date);
                    $ad_data['expire_special_date'] = $final_expire_special_date;
                }

                $final_today = Carbon::createFromFormat('Y-m-d H:i', $today);
                $expire_date = $final_today->addDays($expire_days);

                $ad_data['publish'] = 'Y';
                $ad_data['plan_id'] = $plan_id;
                $ad_data['publication_date'] = $today;
                $ad_data['expiry_date'] = $expire_date;
                if($product->status == 1) {
                    Product::where('id', $request->ad_id)->update($ad_data);
                }else{
                    $ad_data['status'] = 1;
                    $ad_data['publish'] = 'Y';
                    Product::where('id', $request->ad_id)->update($ad_data);
                }
                Mail::send('invoice_mail', $data, function($message) use ($user) {
                    $message->to($user->email, $user->name)->subject
                    ('Invoice');
                    $message->from('q8carads@gmail.com','carsoq.com');
                });
                // dd("SSS");
                // $currentTransaction = WalletTransaction::where('payment_id', $data['paymentid'])->where('tran_id', $data['tranid'])->first();
                // if (!$currentTransaction) {
                //     WalletTransaction::create([
                //         'payment_id' => $data['paymentid'],
                //         'tran_id' => $data['tranid'],
                //         'price' => $data['amt'],
                //         'value' => $package->amount,
                //         'user_id' => $request->user_id,
                //         'package_id' => $request->balance
                //     ]);
                //     Mail::send('invoice_mail', $data, function($message) use ($selected_user) {
                //         $message->to($selected_user->email, $selected_user->name)->subject
                //            ('Invoice');
                //         $message->from('q8carads@gmail.com','carsoq.com');
                //      });
                // }

                // dd($data);
            }

        }

        return view('invoice', compact('data'));
    }

    public function select_ended_ads(Request $request)
    {
        $ads = Product::where('status', 2)
            ->where('deleted', 0)
            ->where('user_id', auth()->user()->id)
            ->select('id', 'title', 'price', 'main_image', 'pin', 'publication_date','created_at')
            ->get();
        if (count($ads) == 0) {
            $response = APIHelpers::createApiResponse(false, 200, 'no ended ads yet !', ' !لا يوجد اعلانات منتهيه حتى الان', null, $request->lang);
            return response()->json($response, 200);
        } else {
            $response = APIHelpers::createApiResponse(false, 200, '', '', $ads, $request->lang);
            return response()->json($response, 200);
        }
    }

    public function select_current_ads(Request $request)
    {
        $ads = Product::where('status', 1)
            ->where('publish', 'Y')
            ->where('deleted', 0)
            ->where('user_id', auth()->user()->id)
            ->select('id', 'title', 'price', 'main_image', 'pin', 'publication_date','created_at')
            ->orderBy('pin', 'desc')
            ->orderBy('created_at', 'desc')
            ->simplePaginate(12)
            ->map(function ($ads) {
                $ads->views = Product_view::where('product_id', $ads->id)->count();
                return $ads;
            });
        if (count($ads) == 0) {
            $response = APIHelpers::createApiResponse(false, 200, 'no ads yet !', ' !لا يوجد اعلانات حتى الان', null, $request->lang);
            return response()->json($response, 200);
        } else {
            $response = APIHelpers::createApiResponse(false, 200, '', '', $ads, $request->lang);
            return response()->json($response, 200);
        }
    }

    public function select_all_ads(Request $request)
    {
        $ads = Product::where('status', 1)
            ->where('publish', 'Y')
            ->where('deleted', 0)
            ->where('user_id', auth()->user()->id)
            ->select('id', 'title', 'price', 'main_image', 'pin', 'user_id','publication_date as created_at')
            ->orderBy('pin', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        if (count($ads) == 0) {
            $response = APIHelpers::createApiResponse(false, 200, 'no  ads until now !', ' !لا يوجد اعلانات حتى الان', null, $request->lang);
            return response()->json($response, 200);
        } else {
            $response = APIHelpers::createApiResponse(false, 200, '', '', $ads, $request->lang);
            return response()->json($response, 200);
        }
    }

    public function delete_my_ad(Request $request, $id)
    {
        $user = auth()->user();
        if ($user != null) {
            $product = Product::where('id', $id)->first();
            if ($product != null) {
                if ($product->user_id == $user->id) {
                    $product->deleted = 1;
                    $product->save();
                    $response = APIHelpers::createApiResponse(false, 200, 'deleted', 'تم الحذف بنجاح', null, $request->lang);
                    return response()->json($response, 200);
                } else {
                    $response = APIHelpers::createApiResponse(true, 406, 'this not your ad', 'لا تمتلك هذا الاعلان !!', null, $request->lang);
                    return response()->json($response, 406);
                }
            } else {
                $response = APIHelpers::createApiResponse(true, 406, 'no ad of this id', 'لا يوجد اعلان بهذا ال id', null, $request->lang);
                return response()->json($response, 406);
            }
        } else {
            $response = APIHelpers::createApiResponse(true, 406, 'you should login first ', 'يجب تسجيل الدخول أولا !!', null, $request->lang);
            return response()->json($response, 406);
        }
    }

    // get first step data
    public function getFirstStepData(Request $request, $id) {
        Session::put('lang', $request->lang);
        $data['ad'] = Product::where('id', $id)
            ->select('id', 'category_id', 'sub_category_id', 'sub_category_two_id', 'sub_category_three_id', 'sub_category_four_id', 'sub_category_five_id', 'title', 'price', 'description', 'main_image')
            ->first();

            $features = Product_feature::where('product_id', $request->id)
            ->select('id', 'type', 'product_id', 'target_id', 'option_id')
            ->orderBy('option_id', 'asc')
            ->get();

        foreach ($features as $key => $feature) {
            if ($feature->type == 'manual') {
                $features[$key]['type'] = 'input';
                $features[$key]['value'] = '';
            } else if ($feature->type == 'option') {
                $features[$key]['type'] = 'select';
                $target_data = Category_option_value::where('id', $feature->target_id)->first();
                if ($request->lang == 'ar')
                    $features[$key]['value'] = $target_data->value_ar;
                else {
                    $features[$key]['value'] = $target_data->value_en;
                }
            }
        }

        $data['features'] = $features;

        // to select options view
        if ($request->lang == 'en') {
            $data['options'] = Category_option::where('cat_id', $data['ad']->category_id)->where('deleted', '0')->select('id as option_id', 'title_en as title', 'is_required')->orderBy('id', 'asc')->get();
            if (count($data['options']) > 0) {
                for ($i = 0; $i < count($data['options']); $i++) {
                    $data['options'][$i]['type'] = 'input';
                    $pro_feature = Product_feature::with('Option_value')->where('product_id', $id)->where('option_id', $data['options'][$i]['option_id'])->first();
                    if ($pro_feature != null) {
                        $target_id = $pro_feature->target_id;
                    } else {
                        $target_id = "";
                    }
                    if ($data['options'][$i]['type'] == 'input') {
                        if ($pro_feature != null) {
                            $data['options'][$i]['inserted'] = $target_id;
                        } else {
                            $data['options'][$i]['inserted'] = '';
                        }
                    }
                    if ($pro_feature != null) {
                        if ($pro_feature->target_id > 0) {
                            $cate_op_value = Category_option_value::where('id', $pro_feature->target_id)->first();
                            if ($cate_op_value != null) {
                                $data['options'][$i]['inserted_value'] = $cate_op_value->value_en;
                            } else {
                                $data['options'][$i]['inserted_value'] = $pro_feature->target_id;
                            }
                        } else {
                            $data['options'][$i]['inserted_value'] = $pro_feature->target_id ;
                        }
                    } else {
                        $data['options'][$i]['inserted_value'] = '';
                    }
                    $optionValues = Category_option_value::where('option_id', $data['options'][$i]['option_id'])
                        ->where('deleted', '0')
                        ->select('id as value_id', 'value_en as value')
                        ->get()
                        ->map(function ($data) use ($target_id) {
                            if ($data->value_id == $target_id) {
                                $data->selected = true;
                            } else {
                                $data->selected = false;
                            }
                            return $data;
                        });
                    if (count($optionValues) > 0) {
                        $data['options'][$i]['type'] = 'select';
                        $data['options'][$i]['values'] = $optionValues;
                    }
                }
            }
        } else {
            $data['options'] = Category_option::where('cat_id', $data['ad']->category_id)->where('deleted', '0')->select('id as option_id', 'title_ar as title', 'is_required')->get();
            if (count($data['options']) > 0) {
                for ($i = 0; $i < count($data['options']); $i++) {
                    $data['options'][$i]['type'] = 'input';
                    $pro_feature = Product_feature::with('Option_value')->where('product_id', $id)->where('option_id', $data['options'][$i]['option_id'])->first();
                    if ($pro_feature != null) {
                        $target_id = $pro_feature->target_id;
                    } else {
                        $target_id = "";
                    }
                    if ($data['options'][$i]['type'] == 'input') {
                        if ($pro_feature != null) {
                            $data['options'][$i]['inserted'] = $target_id;
                        } else {
                            $data['options'][$i]['inserted'] = '';
                        }
                    }
                    if ($pro_feature != null) {
                        if ($pro_feature->target_id > 0) {
                            $cate_op_value = Category_option_value::where('id', $pro_feature->target_id)->first();
                            if ($cate_op_value != null) {
                                $data['options'][$i]['inserted_value'] = $cate_op_value->value_ar;
                            } else {
                                $data['options'][$i]['inserted_value'] = $pro_feature->target_id;
                            }
                        } else {
                            $data['options'][$i]['inserted_value'] = $pro_feature->target_id ;
                        }
                    } else {
                        $data['options'][$i]['inserted_value'] = '';
                    }
                    $optionValues = Category_option_value::where('option_id', $data['options'][$i]['option_id'])
                        ->where('deleted', '0')
                        ->select('id as value_id', 'value_ar as value')
                        ->get()
                        ->map(function ($data) use ($target_id) {
                            if ($data->value_id == $target_id) {
                                $data->selected = true;
                            } else {
                                $data->selected = false;
                            }
                            return $data;
                        });

                    if (count($optionValues) > 0) {
                        $data['options'][$i]['type'] = 'select';
                        $data['options'][$i]['values'] = $optionValues;
                    }
                }
            }
        }

        $response = APIHelpers::createApiResponse(false, 200, 'data shown', 'تم أظهار البيانات', $data, $request->lang);
        return response()->json($response, 200);
    }

    public function select_ad_data(Request $request, $id)
    {
        Session::put('lang', $request->lang);
        $data['ad'] = Product::where('id', $id)
            ->select('id', 'category_id', 'sub_category_id', 'sub_category_two_id', 'sub_category_three_id', 'sub_category_four_id', 'sub_category_five_id', 'title', 'price', 'description', 'main_image')
            ->first();
        $data['ad_images'] = ProductImage::where('product_id', $id)->select('id', 'image', 'product_id')->get();

        if ($request->lang == 'ar') {
            if ($data['ad']->category_id != null) {
                $cat_data = Category::find($data['ad']->category_id);
                $data['category_names'] = $cat_data->title_ar;
            }
            if ($data['ad']->sub_category_id != null) {
                $scat_data = SubCategory::find($data['ad']->sub_category_id);
                $data['category_names'] = $data['category_names'] . '/' . $scat_data->title_ar;
            }
            if ($data['ad']->sub_category_two_id != null) {
                $sscat_data = SubTwoCategory::find($data['ad']->sub_category_two_id);
                $data['category_names'] = $data['category_names'] . '/' . $sscat_data->title_ar;
            }
            if ($data['ad']->sub_category_three_id != null) {
                $ssscat_data = SubThreeCategory::find($data['ad']->sub_category_three_id);
                $data['category_names'] = $data['category_names'] . '/' . $ssscat_data->title_ar;
            }
            if ($data['ad']->sub_category_four_id != null) {
                $sssscat_data = SubFourCategory::find($data['ad']->sub_category_four_id);
                $data['category_names'] = $data['category_names'] . '/' . $sssscat_data->title_ar;
            }
            if ($data['ad']->sub_category_five_id != null) {
                $ssssscat_data = SubFiveCategory::find($data['ad']->sub_category_five_id);
                $data['category_names'] = $data['category_names'] . '/' . $ssssscat_data->title_ar;
            }
        } else {
            if ($data['ad']->category_id != null) {
                $cat_data = Category::find($data['ad']->category_id);
                $data['category_names'] = $cat_data->title_en;
            }
            if ($data['ad']->sub_category_id != null) {
                $scat_data = SubCategory::find($data['ad']->sub_category_id);
                $data['category_names'] = $data['category_names'] . '/' . $scat_data->title_en;
            }
            if ($data['ad']->sub_category_two_id != null) {
                $sscat_data = SubTwoCategory::find($data['ad']->sub_category_two_id);
                $data['category_names'] = $data['category_names'] . '/' . $sscat_data->title_en;
            }
            if ($data['ad']->sub_category_three_id != null) {
                $ssscat_data = SubThreeCategory::find($data['ad']->sub_category_three_id);
                $data['category_names'] = $data['category_names'] . '/' . $ssscat_data->title_en;
            }
            if ($data['ad']->sub_category_four_id != null) {
                $sssscat_data = SubFourCategory::find($data['ad']->sub_category_four_id);
                $data['category_names'] = $data['category_names'] . '/' . $sssscat_data->title_en;
            }
            if ($data['ad']->sub_category_five_id != null) {
                $ssssscat_data = SubFiveCategory::find($data['ad']->sub_category_five_id);
                $data['category_names'] = $data['category_names'] . '/' . $ssssscat_data->title_en;
            }
        }
        $features = Product_feature::where('product_id', $request->id)
            ->select('id', 'type', 'product_id', 'target_id', 'option_id')
            ->orderBy('option_id', 'asc')
            ->get();

        foreach ($features as $key => $feature) {
            if ($feature->type == 'manual') {
                $features[$key]['type'] = 'input';
                $features[$key]['value'] = '';
            } else if ($feature->type == 'option') {
                $features[$key]['type'] = 'select';
                $target_data = Category_option_value::where('id', $feature->target_id)->first();
                if ($request->lang == 'ar')
                    $features[$key]['value'] = $target_data->value_ar;
                else {
                    $features[$key]['value'] = $target_data->value_en;
                }
            }
        }

        $data['features'] = $features;

        // to select options view
        if ($request->lang == 'en') {
            $data['options'] = Category_option::where('cat_id', $data['ad']->category_id)->where('deleted', '0')->select('id as option_id', 'title_en as title', 'is_required')->orderBy('id', 'asc')->get();
            if (count($data['options']) > 0) {
                for ($i = 0; $i < count($data['options']); $i++) {
                    $data['options'][$i]['type'] = 'input';
                    $pro_feature = Product_feature::with('Option_value')->where('product_id', $id)->where('option_id', $data['options'][$i]['option_id'])->first();
                    if ($pro_feature != null) {
                        $target_id = $pro_feature->target_id;
                    } else {
                        $target_id = "";
                    }
                    if ($data['options'][$i]['type'] == 'input') {
                        if ($pro_feature != null) {
                            $data['options'][$i]['inserted'] = $target_id;
                        } else {
                            $data['options'][$i]['inserted'] = '';
                        }
                    }
                    if ($pro_feature != null) {
                        if ($pro_feature->target_id > 0) {
                            $cate_op_value = Category_option_value::where('id', $pro_feature->target_id)->first();
                            if ($cate_op_value != null) {
                                $data['options'][$i]['inserted_value'] = $cate_op_value->value_en;
                            } else {
                                $data['options'][$i]['inserted_value'] = $pro_feature->target_id;
                            }
                        } else {
                            $data['options'][$i]['inserted_value'] = $pro_feature->target_id ;
                        }
                    } else {
                        $data['options'][$i]['inserted_value'] = '';
                    }
                    $optionValues = Category_option_value::where('option_id', $data['options'][$i]['option_id'])
                        ->where('deleted', '0')
                        ->select('id as value_id', 'value_en as value')
                        ->get()
                        ->map(function ($data) use ($target_id) {
                            if ($data->value_id == $target_id) {
                                $data->selected = true;
                            } else {
                                $data->selected = false;
                            }
                            return $data;
                        });
                    if (count($optionValues) > 0) {
                        $data['options'][$i]['type'] = 'select';
                        $data['options'][$i]['values'] = $optionValues;
                    }
                }
            }
        } else {
            $data['options'] = Category_option::where('cat_id', $data['ad']->category_id)->where('deleted', '0')->select('id as option_id', 'title_ar as title', 'is_required')->get();
            if (count($data['options']) > 0) {
                for ($i = 0; $i < count($data['options']); $i++) {
                    $data['options'][$i]['type'] = 'input';
                    $pro_feature = Product_feature::with('Option_value')->where('product_id', $id)->where('option_id', $data['options'][$i]['option_id'])->first();
                    if ($pro_feature != null) {
                        $target_id = $pro_feature->target_id;
                    } else {
                        $target_id = "";
                    }
                    if ($data['options'][$i]['type'] == 'input') {
                        if ($pro_feature != null) {
                            $data['options'][$i]['inserted'] = $target_id;
                        } else {
                            $data['options'][$i]['inserted'] = '';
                        }
                    }
                    if ($pro_feature != null) {
                        if ($pro_feature->target_id > 0) {
                            $cate_op_value = Category_option_value::where('id', $pro_feature->target_id)->first();
                            if ($cate_op_value != null) {
                                $data['options'][$i]['inserted_value'] = $cate_op_value->value_ar;
                            } else {
                                $data['options'][$i]['inserted_value'] = $pro_feature->target_id;
                            }
                        } else {
                            $data['options'][$i]['inserted_value'] = $pro_feature->target_id ;
                        }
                    } else {
                        $data['options'][$i]['inserted_value'] = '';
                    }
                    $optionValues = Category_option_value::where('option_id', $data['options'][$i]['option_id'])
                        ->where('deleted', '0')
                        ->select('id as value_id', 'value_ar as value')
                        ->get()
                        ->map(function ($data) use ($target_id) {
                            if ($data->value_id == $target_id) {
                                $data->selected = true;
                            } else {
                                $data->selected = false;
                            }
                            return $data;
                        });

                    if (count($optionValues) > 0) {
                        $data['options'][$i]['type'] = 'select';
                        $data['options'][$i]['values'] = $optionValues;
                    }
                }
            }
        }
        //end options
        $response = APIHelpers::createApiResponse(false, 200, 'data shown', 'تم أظهار البيانات', $data, $request->lang);
        return response()->json($response, 200);
    }

    public function remove_main_image(Request $request, $id)
    {
        $data['main_image'] = null;
        $final_data = Product::where('id', $id)->update($data);

        if ($final_data == 1) {
            $data_f['status'] = true;
            $response = APIHelpers::createApiResponse(false, 200, 'data updated', 'تم تعديل البيانات', $data_f, $request->lang);
            return response()->json($response, 200);
        } else {
            $data_f['status'] = false;
            $response = APIHelpers::createApiResponse(true, 406, 'not updated', 'لم يتم التعديل', $data_f, $request->lang);
            return response()->json($response, 406);
        }
    }

    public function remove_product_image(Request $request, $image_id)
    {

        $final_data = ProductImage::where('id', $image_id)->delete();
        if ($final_data == 1) {
            $data_f['status'] = true;
            $response = APIHelpers::createApiResponse(false, 200, 'data deleted', 'تم الحذف البيانات', $data_f, $request->lang);
            return response()->json($response, 200);
        } else {
            $data_f['status'] = false;
            $response = APIHelpers::createApiResponse(true, 406, 'not deleted', 'لم يتم الحذف', $data_f, $request->lang);
            return response()->json($response, 406);
        }
    }

    public function update_ad(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'category_id' => 'required',
            'sub_category_id' => '',
            'sub_category_two_id' => '',
            'sub_category_three_id' => '',
            'sub_category_four_id' => '',
            'sub_category_five_id' => '',
            'title' => 'required',
            'options' => '',
            'price' => 'required|numeric',
            'description' => '',
            'main_image' => '',
            // 'images' => ''
        ]);
        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true, 406, $validator->messages()->first(), $validator->messages()->first(), null, $request->lang);
            return response()->json($response, 406);
        } else {
            $user = auth()->user();
            if ($user != null) {
                $input['user_id'] = $user->id;
            } else {
                $response = APIHelpers::createApiResponse(true, 406, 'you should login first', 'يجب تسجيل الدخول اولا', null, $request->lang);
                return response()->json($response, 406);
            }
            if ($request->main_image != null) {
                $image = $request->main_image;
                Cloudder::upload("data:image/jpeg;base64," . $image, null);
                $imagereturned = Cloudder::getResult();
                $image_id = $imagereturned['public_id'];
                $image_format = $imagereturned['format'];
                $image_new_name = $image_id . '.' . $image_format;
                $input['main_image'] = $image_new_name;
            }
            if ($request->images != null) {
                foreach ($request->images as $image) {
                    Cloudder::upload("data:image/jpeg;base64," . $image, null);
                    $imagereturned = Cloudder::getResult();
                    $image_id = $imagereturned['public_id'];
                    $image_format = $imagereturned['format'];
                    $image_name = $image_id . '.' . $image_format;
                    $data['product_id'] = $id;
                    $data['image'] = $image_name;
                    ProductImage::create($data);
                }
            }
            unset($input['images']);
            unset($input['options']);
            $updated = Product::where('id', $id)->update($input);

            if ($request->options != null) {
                Product_feature::where('product_id', $id)->delete();
                foreach ($request->options as $key => $option) {
                    if($option['option_value'] != null) {
                        if (is_numeric($option['option_value'])) {
                            $option_values = Category_option_value::where('id', $option['option_value'])->first();
                            if ($option_values != null) {
                                $feature_data['type'] = 'option';
                            } else {
                                $feature_data['type'] = 'manual';
                            }
                        } else {
                            $feature_data['type'] = 'manual';
                        }
                        $feature_data['product_id'] = $id;
                        $feature_data['target_id'] = $option['option_value'];
                        $feature_data['option_id'] = $option['option_id'];
                        Product_feature::create($feature_data);
                    }
                }
            }
            if ($updated == 1) {
                $final_data['status'] = true;
                $response = APIHelpers::createApiResponse(false, 200, 'updated successfuly', 'تم التعديل بنجاح', $final_data, $request->lang);
                return response()->json($response, 200);
            } else {
                $data_f['status'] = false;
                $response = APIHelpers::createApiResponse(true, 406, 'not updated', 'لم يتم التعديل', $data_f, $request->lang);
                return response()->json($response, 406);
            }
        }
    }

    public function update_image(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'ad_id' => 'required|exists:products,id',
            // 'images' => '',
            'main_image' => '',
        ]);
        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true, 406, $validator->messages()->first(), $validator->messages()->first(), null, $request->lang);
            return response()->json($response, 406);
        } else {
            $user = auth()->user();
            if ($user != null) {
                $input['user_id'] = $user->id;
            } else {
                $response = APIHelpers::createApiResponse(true, 406, 'you should login first', 'يجب تسجيل الدخول اولا', null, $request->lang);
                return response()->json($response, 406);
            }
            if ($request->main_image != null) {
                $main_image = $request->main_image;
                Cloudder::upload("data:image/jpeg;base64," . $main_image, null);
                $imagereturned = Cloudder::getResult();
                $image_id = $imagereturned['public_id'];
                $image_format = $imagereturned['format'];
                $image_new_name = $image_id . '.' . $image_format;
                $main_image_data['main_image'] = $image_new_name;
                Product::find($request->ad_id)->update($main_image_data);
            }
            ProductImage::where('product_id',$request->ad_id)->delete();
            if ($request->images != null) {
                foreach ($request->images as $image) {
                    Cloudder::upload("data:image/jpeg;base64," . $image, null);
                    $imagereturned = Cloudder::getResult();
                    $image_id = $imagereturned['public_id'];
                    $image_format = $imagereturned['format'];
                    $image_name = $image_id . '.' . $image_format;
                    $data['product_id'] = $request->ad_id;
                    $data['image'] = $image_name;
                    ProductImage::create($data);
                }
            }
            $response = APIHelpers::createApiResponse(false, 200, 'updated successfully', 'تم التعديل بنجاح', null, $request->lang);
            return response()->json($response, 200);
        }
    }

    public function republish_ad(Request $request)
    {
        $user = auth()->user();
        if ($user == null) {
            $response = APIHelpers::createApiResponse(true, 406, 'you should login first', 'يجب تسجيل الدخول اولا', null, $request->lang);
            return response()->json($response, 406);
        }
        if ($user->active == 0) {
            $response = APIHelpers::createApiResponse(true, 406, 'your account un actived', 'تم حظر حسابك', null, $request->lang);
            return response()->json($response, 406);
        }
        $plan = Plan::where('id', $request->plan_id)->first();
        if ($user->my_wallet < $plan->price) {
            $response = APIHelpers::createApiResponse(true, 406, 'you don`t have enough balance to republish ad , please buy ads package', 'ليس لديك رصيد إعلانات لتجديد الإعلان يرجي شراء باقه إعلانات', null, $request->lang);
            return response()->json($response, 406);
        }
        $product = Product::where('id', $request->product_id)->where('user_id', $user->id)->first();
        if ($product->status == 1) {
            $response = APIHelpers::createApiResponse(true, 406, 'this ad not ended yet', 'هذا الاعلان لم ينتهى بعد', null, $request->lang);
            return response()->json($response, 406);
        }
        if ($product->deleted == 1) {
            $response = APIHelpers::createApiResponse(true, 406, 'this ad deleted before', 'هذا الاعلان تم حذفة', null, $request->lang);
            return response()->json($response, 406);
        }
        if ($product) {
            $plan_price = $plan->price;
            //to select expire days of selected plane
            $plan_detail = Plan_details::where('plan_id', $request->plan_id)->where('type', 'expier_num')->first();
            $expire_days = $plan_detail->expire_days;

            $user->my_wallet = $user->my_wallet - $plan_price;
            if ($user->free_balance >= $plan_price) {
                $user->free_balance = $user->free_balance - $plan_price;
            } else if ($user->payed_balance >= $plan_price) {
                $user->payed_balance = $user->payed_balance - $plan_price;
            } else {
                $free_balance = $user->free_balance;  //70
                $payed_balance = $user->payed_balance;  //30
                $price = $plan_price; //100
                $after_min_free = $price - $free_balance;  // 100 - 70 = 30
                if ($after_min_free <= $payed_balance && $after_min_free > 0) {
                    // 30 <= 30 && 30 < 0
                    $user->free_balance = 0;
                    $user->payed_balance = $user->payed_balance - $after_min_free;
                } else if ($after_min_free > $payed_balance && $after_min_free > 0) {
                    $after_min_payed = $price - $payed_balance;
                    $user->free_balance = $user->free_balance - $after_min_payed;
                    $user->payed_balance = 0;
                }
            }
            $user->save();
            //to get the expire_date of ad
            $mytime = Carbon::now();
            $today = Carbon::parse($mytime->toDateTimeString())->format('Y-m-d H:i');
            $pin = Plan_details::where('plan_id', $request->plan_id)->where('type', 'pin')->first();
            if ($pin != null) {
                $expire_pin_date = $pin->expire_days;
                $product->pin = 1;
                //to create expire pin date
                $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                $final_expire_pin_date = $final_pin_date->addDays($expire_pin_date);
                $product->expire_pin_date = $final_expire_pin_date;
            }
            $re_post = Plan_details::where('plan_id', $request->plan_id)->where('type', 're_post')->first();
            if ($re_post != null) {
                $expire_re_post_date = $re_post->expire_days;
                $product->re_post = '1';
                //to create expire pin date
                $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                $final_expire_re_post_date = $final_pin_date->addDays($expire_re_post_date);
                $product->re_post_date = $final_expire_re_post_date;
            }
            $special = Plan_details::where('plan_id', $request->plan_id)->where('type', 'special')->first();
            if ($special != null) {
                $expire_special_date = $special->expire_days;
                $product->is_special = '1';
                //to create expire pin date
                $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                $final_expire_special_date = $final_pin_date->addDays($expire_special_date);
                $product->expire_special_date = $final_expire_special_date;
            }

            $final_today = Carbon::createFromFormat('Y-m-d H:i', $today);
            $expire_date = $final_today->addDays($expire_days);
            $product->plan_id = $request->plan_id;
            $product->expiry_date = $expire_date;
            $product->status = 1;
            $product->publish = 'Y';
            $product->save();
            $response = APIHelpers::createApiResponse(false, 200, 'republish done', 'تم اعادة النشر بنجاح', null, $request->lang);
            return response()->json($response, 200);

        } else {
            $response = APIHelpers::createApiResponse(true, 406, 'ليس لديك الصلاحيه لتجديد هذا الاعلان', '', null, $request->lang);
            return response()->json($response, 406);
        }
    }
}
