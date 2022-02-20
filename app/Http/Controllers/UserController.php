<?php

namespace App\Http\Controllers;

use App\Balance_package;
use App\WalletTransaction;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\APIHelpers;
use App\UserNotification;
use App\Notification;
use App\Product;
use App\ProductImage;
use App\Setting;
use App\Favorite;
use App\Category;
use JD\Cloudder\Facades\Cloudder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;




class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['checkphoneexistanceandroid','pay_sucess','pay_error','excute_pay', 'excute_pay2', 'execute_response', 'my_account','my_balance','resetforgettenpassword' , 'checkphoneexistance' , 'getownerprofile']]);
    }

    public function getprofile(Request $request){
        $user = auth()->user();
        $returned_user['user_name'] = $user['name'];
        $returned_user['name'] = $user['name'];
        $returned_user['phone'] = $user['phone'];
        $returned_user['email'] = $user['email'];
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $returned_user, $request->lang );
        return response()->json($response , 200);
    }

    public function updateprofile(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            "email" => 'required',
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'بعض الحقول مفقودة', '' , null, $request->lang );
            return response()->json($response , 406);
        }

        $currentuser = auth()->user();
        $user_by_phone = User::where('phone' , '!=' , $currentuser->phone )->where('phone', $request->phone)->first();
        if($user_by_phone){
            $response = APIHelpers::createApiResponse(true , 409 ,  'رقم الهاتف موجود من قبل', '' , null, $request->lang );
            return response()->json($response , 409);
        }

        $user_by_email = User::where('email' , '!=' ,$currentuser->email)->where('email' , $request->email)->first();
        if($user_by_email){
            $response = APIHelpers::createApiResponse(true , 409 , 'البريد الإلكتروني موجود من قبل', '' , null, $request->lang );
            return response()->json($response , 409);
        }

        User::where('id' , $currentuser->id)->update([
            'name' => $request->name ,
            'phone' => $request->phone ,
            'email' => $request->email  ]);

        $newuser = User::find($currentuser->id);
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $newuser, $request->lang );
        return response()->json($response , 200);
    }


    public function resetpassword(Request $request){
        $validator = Validator::make($request->all() , [
            'password' => 'required',
            "old_password" => 'required'
        ]);

        if($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'بعض الحقول مفقودة', '' , null, $request->lang );
            return response()->json($response , 406);
        }

        $user = auth()->user();
        if(!Hash::check($request->old_password, $user->password)){
            $response = APIHelpers::createApiResponse(true , 406 ,  'كلمه المرور السابقه خطأ', '' , null, $request->lang );
            return response()->json($response , 406);
        }
        if($request->old_password == $request->password){
            $response = APIHelpers::createApiResponse(true , 406 ,  'لا يمكنك تعيين نفس كلمه المرور السابقه', '' , null, $request->lang );
            return response()->json($response , 406);
        }
        User::where('id' , $user->id)->update(['password' => Hash::make($request->password)]);
        $newuser = User::find($user->id);
        $response = APIHelpers::createApiResponse(false , 200 , '', '' , $newuser, $request->lang );
        return response()->json($response , 200);
    }

    public function resetforgettenpassword(Request $request){
        $validator = Validator::make($request->all() , [
            'password' => 'required',
            'phone' => 'required'
        ]);

        if($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'بعض الحقول مفقودة', '' , null, $request->lang );
            return response()->json($response , 406);
        }

        $user = User::where('phone', $request->phone)->first();
        if(! $user){
            $response = APIHelpers::createApiResponse(true , 403 ,  'رقم الهاتف غير موجود', '' , null, $request->lang );
            return response()->json($response , 403);
        }

        User::where('phone' , $user->phone)->update(['password' => Hash::make($request->password)]);
        $newuser = User::where('phone' , $user->phone)->first();

        $token = auth()->login($newuser);
        $newuser->token = $this->respondWithToken($token);

        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $newuser, $request->lang );
        return response()->json($response , 200);
    }

    // check if phone exists before or not
    public function checkphoneexistance(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'phone' => 'required'
        ]);
        if($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'حقل الهاتف اجباري', '' , null, $request->lang );
            return response()->json($response , 406);
        }
        $user = User::where('phone' , $request->phone)->first();
        if($user){
            $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $user, $request->lang );
            return response()->json($response , 200);
        }
        $response = APIHelpers::createApiResponse(true , 403 ,  'الهاتف غير موجود من قبل', '' , null, $request->lang );
        return response()->json($response , 403);
    }

    public function checkphoneexistanceandroid(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'phone' => 'required'
        ]);

        if($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'حقل الهاتف اجباري' , (object)[] , $request->lang);
            return response()->json($response , 406);
        }

        $user = User::where('phone' , $request->phone)->first();
        if($user){

            if($request->email){
                $user_email = User::where('email' , $request->email)->first();
                if($user_email){
                    $response = APIHelpers::createApiResponse(false , 200 , '' , '' , (object)[] , $request->lang);
                    $response['phone'] = true;
                    $response['email'] = true;
                    return response()->json($response , 200);
                }else{
                    $response = APIHelpers::createApiResponse(false , 200 , '' , '' ,(object)[] , $request->lang);
                    $response['phone'] = true;
                    $response['email'] = false;
                    return response()->json($response , 200);
                }

            }
            $response = APIHelpers::createApiResponse(false , 200 , '' , '' , (object)[] , $request->lang);
            return response()->json($response , 200);
        }
        if($request->email){
            $user_email = User::where('email' , $request->email)->first();
            if($user_email){
                $response = APIHelpers::createApiResponse(false , 200 , '' , '' , (object)[] , $request->lang);
                $response['phone'] = false;
                $response['email'] = true;
                return response()->json($response , 200);
            }

        }

        $response = APIHelpers::createApiResponse(false , 200 , 'Phone and Email Not Exists Before' , 'الهاتف و البريد غير موجودين من قبل' , (object)[] , $request->lang);
        $response['phone'] = false;
        $response['email'] = false;

        return response()->json($response , 200);

    }

    // get notifications
    public function notifications(Request $request){
        $user = auth()->user();
        if($user->active == 0){
            $response = APIHelpers::createApiResponse(true , 406 ,  'تم حظر حسابك من الادمن', '' , null, $request->lang );
            return response()->json($response , 406);
        }

        $user_id = $user->id;
        $notifications_ids = UserNotification::where('user_id' , $user_id)->orderBy('id' , 'desc')->select('notification_id')->get();
        $notifications = [];
        for($i = 0; $i < count($notifications_ids); $i++){
            $notifications[$i] = Notification::select('id','title' , 'body' ,'image' , 'created_at')->find($notifications_ids[$i]['notification_id']);
        }
        $data['notifications'] = $notifications;
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' ,$data['notifications'], $request->lang );
        return response()->json($response , 200);
    }

    // get ads count
    public function getadscount(Request $request){
        $user = auth()->user();
        $returned_user['free_ads_count'] = $user->free_ads_count;
        $returned_user['paid_ads_count'] = $user->paid_ads_count;
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $returned_user, $request->lang );
        return response()->json($response , 200);
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 432000
        ];
    }

    // get current ads
    public function getcurrentads(Request $request){
        $user = auth()->user();
        if($user->active == 0){
            $response = APIHelpers::createApiResponse(true , 406 ,  'تم حظر حسابك من الادمن', '' , null, $request->lang );
            return response()->json($response , 406);
        }

        $user = auth()->user();

        $products = Product::where('user_id' , $user->id)->where('status' , 1)->orderBy('publication_date' , 'DESC')->select('id' , 'title' , 'price' , 'publication_date as date' , 'type')->simplePaginate(12);
        for($i =0 ; $i < count($products); $i++){
            $products[$i]['image'] = ProductImage::where('product_id' , $products[$i]['id'])->select('image')->first()['image'];
            $favorite = Favorite::where('user_id' , $user->id)->where('product_id' , $products[$i]['id'])->first();
            if($favorite){
                $products[$i]['favorite'] = true;
            }else{
                $products[$i]['favorite'] = false;
            }
            $date = date_create($products[$i]['date']);
            $products[$i]['date'] = date_format($date , 'd M Y');
        }
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $products, $request->lang );
        return response()->json($response , 200);
    }

    // get history date
    public function getexpiredads(Request $request){
        $user = auth()->user();
        if($user->active == 0){
            $response = APIHelpers::createApiResponse(true , 406 ,  'تم حظر حسابك من الادمن', '' , null, $request->lang );
            return response()->json($response , 406);
        }

        $user = auth()->user();

        $products = Product::where('user_id' , $user->id)->where('status' , 2)->orderBy('publication_date' , 'DESC')->select('id' , 'title' , 'price' , 'publication_date as date' , 'type')->simplePaginate(12);
        for($i =0 ; $i < count($products); $i++){
            $products[$i]['image'] = ProductImage::where('product_id' , $products[$i]['id'])->select('image')->first()['image'];
            $favorite = Favorite::where('user_id' , $user->id)->where('product_id' , $products[$i]['id'])->first();
            if($favorite){
                $products[$i]['favorite'] = true;
            }else{
                $products[$i]['favorite'] = false;
            }
            $date = date_create($products[$i]['date']);
            $products[$i]['date'] = date_format($date , 'd M Y');
        }
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $products, $request->lang );
        return response()->json($response , 200);
    }

    public function renewad(Request $request){
        $user = auth()->user();
        if($user->active == 0){
            $response = APIHelpers::createApiResponse(true , 406 ,  'تم حظر حسابك', '' , null, $request->lang );
            return response()->json($response , 406);
        }

        if($user->free_ads_count == 0 && $user->paid_ads_count == 0){
            $response = APIHelpers::createApiResponse(true , 406 ,  'ليس لديك رصيد إعلانات لتجديد الإعلان يرجي شراء باقه إعلانات', '' , null, $request->lang );
            return response()->json($response , 406);
        }

        $validator = Validator::make($request->all() , [
            'product_id' => 'required',
        ]);

        if($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'بعض الحقول مفقودة', '' , null, $request->lang );
            return response()->json($response , 406);
        }

        $product = Product::where('id' , $request->product_id)->where('user_id' , $user->id)->first();

        if($product){
            if($user->free_ads_count > 0){
                $count = $user->free_ads_count;
                $user->free_ads_count = $count - 1;
            }else{
                $count = $user->paid_ads_count;
                $user->paid_ads_count = $count - 1;
            }

            $user->save();
            $ad_period = Setting::find(1)['ad_period'];
            $product->publication_date = date("Y-m-d H:i:s");
            $product->expiry_date = date('Y-m-d H:i:s', strtotime('+'.$ad_period.' days'));
            $product->status = 1;
            $product->save();

            $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $product, $request->lang );
            return response()->json($response , 200);

        }else{

            $response = APIHelpers::createApiResponse(true , 406 ,  'ليس لديك الصلاحيه لتجديد هذا الاعلان', '' , null, $request->lang );
            return response()->json($response , 406);

        }

    }

    public function deletead(Request $request){
        $user = auth()->user();
        if($user->active == 0){
            $response = APIHelpers::createApiResponse(true , 406 ,  'تم حظر حسابك', '' , null, $request->lang );
            return response()->json($response , 406);
        }

        $validator = Validator::make($request->all() , [
            'product_id' => 'required',
        ]);

        if($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'بعض الحقول مفقودة', '' , null, $request->lang );
            return response()->json($response , 406);
        }

        $product = Product::where('id' , $request->product_id)->where('user_id' , $user->id)->first();

        if($product){
            $product->delete();
            $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , null, $request->lang );
            return response()->json($response , 200);
        }else{
            $response = APIHelpers::createApiResponse(true , 406 ,  'ليس لديك الصلاحيه لحذف هذا الاعلان', '' , null, $request->lang );
            return response()->json($response , 406);
        }

    }

    public function editad(Request $request){
        $user = auth()->user();
        if($user->active == 0){
            $response = APIHelpers::createApiResponse(true , 406 ,  'تم حظر حسابك', '' , null, $request->lang );
            return response()->json($response , 406);
        }

        $validator = Validator::make($request->all() , [
            'product_id' => 'required',
        ]);

        if($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'بعض الحقول مفقودة', '' , null, $request->lang );
            return response()->json($response , 406);
        }

        $product = Product::where('id' , $request->product_id)->where('user_id' , $user->id)->first();
        if($product){
            if($request->title){
                $product->title = $request->title;
            }

            if($request->description){
                $product->description = $request->description;
            }

            if($request->price){
                $product->price = $request->price;
            }

            if($request->category_id){
                $product->category_id = $request->category_id;
            }

            if($request->type){
                $product->type = $request->type;
            }

            $product->save();

            if($request->image){
                $product_image = ProductImage::where('product_id' , $request->product_id)->first();
                $image = $request->image;
                Cloudder::upload("data:image/jpeg;base64,".$image, null);
                $imagereturned = Cloudder::getResult();
                $image_id = $imagereturned['public_id'];
                $image_format = $imagereturned['format'];
                $image_new_name = $image_id.'.'.$image_format;
                $product_image->image = $image_new_name;
                $product_image->save();
            }

            $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $product, $request->lang );
            return response()->json($response , 200);
        }else{
            $response = APIHelpers::createApiResponse(true , 406 ,  'ليس لديك الصلاحيه لتعديل هذا الاعلان', '' , null, $request->lang );
            return response()->json($response , 406);
        }

    }

    public function delteadimage(Request $request){
        $user = auth()->user();
        if($user->active == 0){
            $response = APIHelpers::createApiResponse(true , 406 ,  'تم حظر حسابك', '' , null, $request->lang );
            return response()->json($response , 406);
        }

        $validator = Validator::make($request->all() , [
            'image_id' => 'required',
        ]);

        if($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'بعض الحقول مفقودة', '' , null, $request->lang );
            return response()->json($response , 406);
        }

        $image = ProductImage::find($request->image_id);
        if($image){
            $image->delete();
            $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , null, $request->lang);
            return response()->json($response , 200);

        }else{
            $response = APIHelpers::createApiResponse(true , 406 ,  'Invalid Image Id', '' , null, $request->lang );
            return response()->json($response , 406);
        }

    }

    public function getaddetails(Request $request){
        $ad_id = $request->id;
        $ad = Product::select('id' , 'title' , 'description' , 'price' , 'type' , 'category_id')->find($ad_id);
        $ad['category_name'] = Category::find($ad['category_id'])['title_ar'];
        $images = ProductImage::where('product_id' , $ad_id)->select('id' , 'image')->get()->toArray();

        $ad['image'] =  array_shift($images)['image'];
        $ad['images'] = $images;
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' ,$ad, $request->lang );
        return response()->json($response , 200);
    }

    public function getownerprofile(Request $request){
        $user_id = $request->id;
        $data['user'] = User::select('id' , 'name' , 'phone' , 'email')->find($user_id);
        $products = Product::where('status' , 1)->where('user_id' , $user_id)->orderBy('publication_date' , 'DESC')->select('id' , 'title' , 'price','type' , 'publication_date as date')->get();
        for($i =0; $i < count($products); $i++){
            $products[$i]['image'] = ProductImage::where('product_id' , $products[$i]['id'])->first()['image'];
            $date = date_create($products[$i]['date']);
            $products[$i]['date'] = date_format($date , 'd M Y');

            $user = auth()->user();
            if($user){
                $favorite = Favorite::where('user_id' , $user->id)->where('product_id' , $products[$i]['id'])->first();
                if($favorite){
                    $products[$i]['favorite'] = true;
                }else{
                    $products[$i]['favorite'] = false;
                }
            }else{
                $products[$i]['favorite'] = false;
            }

        }
        $data['products'] = $products;

        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' ,$data, $request->lang );
        return response()->json($response , 200);
    }
//nasser code
    public function my_account(Request $request){
        $user = auth()->user();
        $data = User::where('id',$user->id)
            ->select('my_wallet as my_balance','name as user_name','phone','free_balance','payed_balance')
            ->first();
        $data['my_ads'] = Product::where('user_id',$user->id)->where('publish','Y')->where('deleted',0)->get()->count();
        $data['my_fav'] = Favorite::where('user_id',$user->id)->get()->count();
        $data['app_data'] = Setting::select('phone','watsapp','instegram')->where('id',1)->first();
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    public function my_balance(Request $request){
        $data = User::where('id',auth()->user()->id)->select('id' , 'my_wallet as my_balance')->first();
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // add balance to wallet
    public function addBalance(Request $request) {

        $validator = Validator::make($request->all(), [
            'package_id' => 'required|exists:balance_packages,id'
        ]);
        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , $validator->messages()->first() , $validator->messages()->first() , null , $request->lang);
            return response()->json($response , 406);
        }
        $package = Balance_package::find($request->package_id);
        $user = auth()->user();
        $root_url = $request->root();
        $path='https://apitest.myfatoorah.com/v2/SendPayment';
        $token="bearer rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";
        $headers = array(
            'Authorization:' .$token,
            'Content-Type:application/json'
        );
        $call_back_url = $root_url."/api/wallet/excute_pay?user_id=".$user->id."&balance=".$request->package_id;
        $error_url = $root_url."/api/pay/error";
//        dd($call_back_url);
        $fields =array(
            "CustomerName" => $user->name,
            "NotificationOption" => "LNK",
            "InvoiceValue" => $package->price,
            "CallBackUrl" => $call_back_url,
            "ErrorUrl" => $error_url,
            "Language" => "AR",
            "CustomerEmail" => $user->email
        );

        $payload =json_encode($fields);
        $curl_session =curl_init();
        curl_setopt($curl_session,CURLOPT_URL, $path);
        curl_setopt($curl_session,CURLOPT_POST, true);
        curl_setopt($curl_session,CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_session,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl_session,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_session,CURLOPT_IPRESOLVE, CURLOPT_IPRESOLVE);
        curl_setopt($curl_session,CURLOPT_POSTFIELDS, $payload);
        $result=curl_exec($curl_session);
        curl_close($curl_session);
        $result = json_decode($result);

        $data['url'] = $result->Data->InvoiceURL;
        $response = APIHelpers::createApiResponse(false , 200 ,  '' , '' , $data , $request->lang );
        return response()->json($response , 200);
    }

    // add balance to wallet
    public function addBalance2(Request $request) {
        // dd("sssdsdds");
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|exists:balance_packages,id'
        ]);
        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , $validator->messages()->first() , $validator->messages()->first() , null , $request->lang);
            return response()->json($response , 406);
        }
        $package = Balance_package::where('id', $request->package_id)->select('id', 'name_' . $request->lang . ' as name', 'desc_' . $request->lang . ' as desc', 'price', 'amount')->first();

        $user = auth()->user();
        $root_url = $request->root();
        $price = $package->price;
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
        $ResponseUrl=$root_url . "/api/package-pay/excute_pay";
        $ReqResponseUrl="responseURL=".$ResponseUrl;

        /* Error URL where Payment gateway will send response in case any issues while processing the transaction
        Merchant MUST esure that below points in ErrorURL
        1- error url must start with http://
        2- the error url SHOULD NOT have any additional paramteres or query string
        */

        $ErrorUrl=$root_url . "/api/pay/error";
        $ReqErrorUrl="errorURL=".$ErrorUrl;

        $ReqUdf1="udf1=" . $user->id;
        $ReqUdf2="udf2=" . $request->package_id;
        $ReqUdf3="udf3=Test3";
        $ReqUdf4="udf4=Test4";
        $ReqUdf5="udf5=Test5";
        $param=$ReqTranportalId."&".$ReqTranportalPassword."&".$ReqAction."&".$ReqLangid."&".$ReqCurrency."&".$ReqAmount."&".$ReqResponseUrl."&".$ReqErrorUrl."&".$ReqTrackId."&".$ReqUdf1."&".$ReqUdf2."&".$ReqUdf3."&".$ReqUdf4."&".$ReqUdf5;

        /*Terminal Resource Key is generated while creating terminal, And this the Key that is used for encryting
	  the request/response from Merchant To PG and vice Versa*/

        $termResourceKey=env("KNET_RESOURCE_KEY");
        $param=$this->encryptAES($param,$termResourceKey)."&tranportalId=".$TranportalId."&responseURL=".$ResponseUrl."&errorURL=".$ErrorUrl;

        $package['url'] = "https://kpay.com.kw/kpg/PaymentHTTP.htm?param=paymentInit" . "&trandata=".$param;

        $response = APIHelpers::createApiResponse(false , 200 ,  '' , '' , $package , $request->lang );
        return response()->json($response , 200);
    }


    //AES Encryption Method Starts
    public function encryptAES($str,$key) {
        $str = $this->pkcs5_pad($str);
        $encrypted = openssl_encrypt($str, 'AES-128-CBC', $key, OPENSSL_ZERO_PADDING, $key);
        $encrypted = base64_decode($encrypted);
        $encrypted=unpack('C*', ($encrypted));
        $encrypted=$this->byteArray2Hex($encrypted);
        $encrypted = urlencode($encrypted);
        return $encrypted;
    }

    public function pkcs5_pad ($text) {
        $blocksize = 16;
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    public function byteArray2Hex($byteArray) {
        $chars = array_map("chr", $byteArray);
        $bin = join($chars);
        return bin2hex($bin);
    }

    // excute pay
    public function excute_pay2(Request $request) {
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

            return redirect()->route('executeResponse', $decrytedData);
        }

    }

    public function execute_response(Request $request) {
        $data = $request->all();
        $data['today'] = Carbon::now()->format('d-m-Y');
        $data['setting'] = Setting::where('id', 1)->first();
        $package = Balance_package::findOrFail($data['udf2']);
        $selected_user = User::where('id', $data['udf1'])->first();
        $data['user'] = $selected_user;
        $data['package'] = $package;
        if ($request->result == 'CAPTURED') {

            if ($package != null) {
                $selected_user->my_wallet = $selected_user->my_wallet + $package->amount;
                $selected_user->payed_balance = $selected_user->payed_balance + $package->amount;
                $selected_user->save();
                $currentTransaction = WalletTransaction::where('payment_id', $data['paymentid'])->where('tran_id', $data['tranid'])->first();
                if (!$currentTransaction) {
                    WalletTransaction::create([
                        'payment_id' => $data['paymentid'],
                        'tran_id' => $data['tranid'],
                        'price' => $data['amt'],
                        'value' => $package->amount,
                        'user_id' => $request->user_id,
                        'package_id' => $request->balance
                    ]);
                    Mail::send('invoice_mail', $data, function($message) use ($selected_user) {
                        $message->to($selected_user->email, $selected_user->name)->subject
                        ('Invoice');
                        $message->from('q8carads@gmail.com','carsoq.com');
                    });
                }

                // dd($data);
            }

        }

        return view('invoice', compact('data'));
    }

    function decrypt($code,$key) {
        $code =  $this->hex2ByteArray(trim($code));
        $code=$this->byteArray2String($code);
        $iv = $key;
        $code = base64_encode($code);
        $decrypted = openssl_decrypt($code, 'AES-128-CBC', $key, OPENSSL_ZERO_PADDING, $iv);
        return $this->pkcs5_unpad($decrypted);
    }

    function hex2ByteArray($hexString) {
        $string = hex2bin($hexString);
        return unpack('C*', $string);
    }


    function byteArray2String($byteArray) {
        $chars = array_map("chr", $byteArray);
        return join($chars);
    }


//    function pkcs5_unpad($text) {
//        $pad = ord($text{strlen($text)-1});
//        if ($pad > strlen($text)) {
//            return false;
//        }
//        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
//            return false;
//        }
//        return substr($text, 0, -1 * $pad);
//    }

    // excute pay
    public function excute_pay(Request $request) {
        $package = Balance_package::findOrFail($request->balance);
        if ($package != null) {
            $user = auth()->user();
            $selected_user = User::findOrFail($user->id);
            $selected_user->my_wallet = $selected_user->my_wallet + $package->amount;
            $selected_user->payed_balance = $selected_user->payed_balance + $package->amount;
            $selected_user->save();
            WalletTransaction::create([
                'price' => $package->price,
                'value' => $package->amount,
                'user_id' => $request->user_id,
                'package_id' => $request->balance
            ]);
            return redirect('api/pay/success');
        }
    }

    public function pay_error(){
        return "Please wait error ...";
    }
    public function pay_sucess(){
        return "Please wait success ...";
    }


}
