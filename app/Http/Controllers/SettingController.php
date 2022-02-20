<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\APIHelpers;
use App\Setting;


class SettingController extends Controller
{
    public function getappnumber(Request $request){
        $setting = Setting::select('phone','instegram','watsapp','contact_info')->find(1);
        $response = APIHelpers::createApiResponse(false , 200 , '', '' , $setting, $request->lang );
        return response()->json($response , 200);
    }

    public function getwhatsapp(Request $request){
        $setting = Setting::select('app_phone')->find(1);
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $setting['app_phone'], $request->lang );
        return response()->json($response , 200);
    }

	public function showbuybutton(Request $request){
	        $setting = Setting::select('show_buy')->find(1);
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $setting, $request->lang );
        return response()->json($response , 200);
	}
	public function terms(Request $request){
        if($request->lang == 'en'){
            $terms = Setting::where('id',1)->select('id' , 'termsandconditions_en as terms','phone','fax','post_address')->get();
        }else{
            $terms = Setting::where('id',1)->select('id' , 'termsandconditions_ar as terms','phone','fax','post_address')->get();
        }
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $terms , $request->lang);
        return response()->json($response , 200);
	}
	public function about_app(Request $request){
        if($request->lang == 'en'){
            $terms = Setting::where('id',1)->select('id' , 'aboutapp_en as phone')->get();
        }else{
            $terms = Setting::where('id',1)->select('id' , 'aboutapp_ar as phone')->get();
        }
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $terms , $request->lang);
        return response()->json($response , 200);
	}
	public function app_address(Request $request){
        if($request->lang == 'en'){
            $terms = Setting::where('id',1)->select('id' , 'address_en as address')->get();
        }else{
            $terms = Setting::where('id',1)->select('id' , 'address_en as address')->get();
        }
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $terms , $request->lang);
        return response()->json($response , 200);
	}
}
