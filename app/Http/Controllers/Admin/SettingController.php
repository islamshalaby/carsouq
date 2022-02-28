<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use App\Setting;

class SettingController extends AdminController{

    // get setting
    public function GetSetting(){
        $data['setting'] = Setting::find(1);
        return view('admin.setting' , ['data' => $data]);
    }

    // post setting
    public function PostSetting(Request $request){
        $setting = Setting::find(1);
        if($request->file('logo')){
            $logo = $setting->logo;
            $publicId = substr($logo , 0 , strrpos($logo , "."));
            Cloudder::delete($publicId);
            $logo_name = $request->file('logo')->getRealPath();
            Cloudder::upload($logo_name , null);
            $logoreturned = Cloudder::getResult();
            $logo_id = $logoreturned['public_id'];
            $logo_format = $logoreturned['format'];
            $logo_new_name = $logo_id.'.'.$logo_format;
            $setting->logo = $logo_new_name;
        }
        $setting->app_name_en = $request->app_name_en;
        $setting->app_name_ar = $request->app_name_ar;
        $setting->email = $request->email;
        $setting->phone = $request->phone;
        $setting->address_en = $request->address_en;
        $setting->address_ar = $request->address_ar;
        $setting->app_name_ar = $request->app_name_ar;
        $setting->facebook = $request->facebook;
        $setting->youtube = $request->youtube;
        $setting->twitter = $request->twitter;
        $setting->instegram = $request->instegram;
        $setting->snap_chat = $request->snap_chat;
        $setting->watsapp = $request->watsapp;
        $setting->map_url = $request->map_url;
//		$setting->ad_period = $request->ad_period;
//		$setting->free_ads_count = $request->free_ads_count;
        $setting->latitude = $request->latitude;
        $setting->longitude = $request->longitude;
        $setting->fax = $request->fax;
        $setting->post_address = $request->post_address;
        $setting->free_balance = $request->free_balance;
        $setting->contact_info = $request->contact_info;
        $show_buy = 0; 
        if ($request->show_buy) {
            $show_buy = 1;
        }
        $setting->show_buy = $show_buy;
//		if($request->show_buy){
//			$setting->show_buy = $request->show_buy;
//		}else{
//			$setting->show_buy = 0;
//		}
		if($request->is_loop_free_balance){
			$setting->is_loop_free_balance = 'y';
		}else{
			$setting->is_loop_free_balance = 'n';
		}
        $setting->free_loop_period = $request->free_loop_period;
        $setting->free_loop_balance = $request->free_loop_balance;


		$setting->save();
        return  back();
    }
}
