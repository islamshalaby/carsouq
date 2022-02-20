<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
// use Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\APIHelpers;
use App\Plan;
use App\User;
use Illuminate\Support\Facades\Http;




class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => [ 'select_all_plans','getpricing' , 'excute_pay' , 'pay_sucess' , 'pay_error']]);
    }

    public function getpricing(Request $request){
        $plans = Plan::select('id' , 'ads_count' , 'price')->get();
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $plans, $request->lang );
        return response()->json($response , 200);
    }

    public function buyplan(Request $request){
        $user = auth()->user();
        if($user->active == 0){
            $response = APIHelpers::createApiResponse(true , 406 ,  'تم حظر حسابك', 'تم حظر حسابك' , null, $request->lang );
            return response()->json($response , 406);
        }

        $validator = Validator::make($request->all() , [
            'plan_id' => 'required',
        ]);

        if($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'بعض الحقول مفقودة', 'بعض الحقول مفقودة' , null, $request->lang );
            return response()->json($response , 406);
        }

        $plan = Plan::find($request->plan_id);
            $root_url = $request->root();

            $path='https://api.myfatoorah.com/v2/SendPayment';
            $token="bearer AeIvp3c6BK2hHMYhFeFSMEavoQdS65m_b8B_HyInpkJBInEEa_LeYH6kllPD_doZVm9Vpye370HhGrLtz6hGeoThWJrsJiHqrn_ezFRGjcvsZ1EyeHQ7S1DUP6AkYVdhbVb2jpS03wrlSnv82_Gj8U9FyA0HU-Epi3OZkwk4GZ7F7F0vwZdVD-ZkPpYtNephrWip4v-cI0nsUbAtP1ntkk7W17FGc3dSYSnCofLRWgqO04Dl1orOZSsiWlZV6upE3SwiQFJfZQXRVxDEnrpjkD7GCejDVjKJJG3hByuI0LHLyY9GAfAikDFKffZPeqjDQLqcFUFdEPHlrzh27JQvM_gGwPGJnNKspzgI1rzevSUBYSqKWj4R0fwXuCtu2rrKk8ZmcaLnTRbtwsl1oO_ZUBoZMZkAx0-H3b-vQHYC5Ti2W406-rCZ0Oayea6S0uyHGvafL7CEhrr_zBizI4SjD-s7pinUUyohw2OiPN5J7dmksJtXKVDrxPLO1Uy8nQ0vvlcDcuRaBbAW1ekis49zGTceIPKs01fA6juHpiYGSmd2NfFCYMuYTGS9NaTPbhAka3a7ipoQnwKogexvUqCAr9ttpi7X8Vb3JnlpYmUq6e1y2KQTc0YPsrkPloDYbQMAZUIleuAS0pxvIFbrk9N45gDsIiPhF_Rq4jE6BCBp2V0nMKqKBZf2zrMSD1EZdw0gPwG2GXZYs-f-xQXPCs9JFAHipOK_OS8_hzCHji8leokMW8Mt";

            $headers = array(
                'Authorization:' .$token,
                'Content-Type:application/json'
            );
            $price = $plan->price;
            $call_back_url = $root_url."/api/excute_pay?user_id=".$user->id."&plan_id=".$request->plan_id;
            $error_url = $root_url."/api/pay/error";
            $fields =array(
                "CustomerName" => $user->name,
                "NotificationOption" => "LNK",
                "InvoiceValue" => $price,
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
            $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $result->Data->InvoiceURL, $request->lang );
            return response()->json($response , 200);

    }

    public function excute_pay(Request $request){
        $plan = Plan::find($request->plan_id);
        $new_ads_count = $plan->ads_count;
        $user = User::find($request->user_id);
        $paid_ads = $user->paid_ads_count;
        $user->paid_ads_count = $paid_ads + $new_ads_count;
        $user->save();
        return redirect('api/pay/success');
    }

    public function pay_sucess(){
        return "Please wait ...";
    }

    public function pay_error(){
        return "Please wait ...";
    }

    //Nasser Code
    public function select_all_plans(Request $request,$cat_id) {
        $lang = $request->lang;
        $cat = Product::findOrFail($cat_id);
            $data['plans'] = Plan::with('Details')
                ->where('deleted','0')
                ->where('status' , 'show')
                ->where('cat_id' , $cat->category_id)
                ->orwhere('cat_id' , 'all')
                ->select('id' ,'title_ar' , 'title_en' ,'cat_id','price')
                ->get()
                ->map(function($plans) use ($lang) {
                    if($lang == 'en'){
                        foreach($plans->Details as $plan_detail){
                            $plan_detail->title = $plan_detail->title_en;
                        }
                        $plans->title = $plans->title_en;
                    }else{
                        foreach($plans->Details as $plan_detail){
                            $plan_detail->title = $plan_detail->title_ar;
                        }
                        $plans->title = $plans->title_ar;
                    }
                    $plans->price = 'KWD ' . $plans->price;
                    return $plans;
                });
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

}
