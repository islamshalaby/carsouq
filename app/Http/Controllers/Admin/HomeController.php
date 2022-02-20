<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ContactUs;
use App\User;
use App\Product;
use App\Plan;
use App\Ad;

class HomeController extends AdminController{

    // get all contact us messages
    public function show(){
        $data['users'] = User::count();
        $data['products'] = Product::where('deleted',0)->count();
        $data['plans'] = Plan::count();
        $data['ads'] = Ad::count();
        $data['contact_us'] = ContactUs::count();
        return view('admin.home' , ['data' => $data]);   
    }

}