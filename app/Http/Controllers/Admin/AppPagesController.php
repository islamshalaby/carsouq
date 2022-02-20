<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Setting;

class AppPagesController extends AdminController{

    // get about app edit page
    public function GetAboutApp(){
        $data['setting'] = Setting::find(1);
        return view('admin.aboutapp' , ['data' => $data]);
    }

    // post about app edit page
    public function PostAboutApp(Request $request){
        if(!$request->aboutapp_ar){
            return redirect('admin-panel/app_pages/aboutapp')->with('status' , 'About App Text in Arabic Required');
        }
        $setting = Setting::find(1);
        $setting->aboutapp_ar = $request->aboutapp_ar;
        $setting->aboutapp_en = $request->aboutapp_en;
        // return $setting;
        $setting->save();
        return redirect('admin-panel/app_pages/aboutapp');
    }

    // get Terms And Conditions edit page
    public function GetTermsAndConditions(){
        $data['setting'] = Setting::find(1);
        return view('admin.termsandconditions' , ['data' => $data]);
    }

    // get Terms And Conditions edit page
    public function PostTermsAndConditions(Request $request){
        if(!$request->termsandconditions_ar || !$request->termsandconditions_en ){
            return redirect('admin-panel/app_pages/termsandconditions')->with('status' , 'Terms And Conditions Text in Arabic Required');
        }
        $setting = Setting::find(1);
        $setting->termsandconditions_ar = $request->termsandconditions_ar;
        $setting->termsandconditions_en = $request->termsandconditions_en;
        $setting->save();
        session()->flash('success', trans('messages.updated_s'));
        return redirect('admin-panel/app_pages/termsandconditions');
    }


}
