<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use App\Visitor;
use App\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Helpers\APIHelpers;
use JD\Cloudder\Facades\Cloudder;
use App\User;
use App\Notification;
use App\UserNotification;
use App\Setting;


class UserController extends AdminController{

    // get all users
    public function show(Request $request){
        $data['users'] = User::orderBy('id','desc')->get();
        return view('admin.users.users' , ['data' => $data]);
    }

    // get user details
    public function details(Request $request){
        $data['user'] = User::find($request->id);
        $data['user']->seen = 1;
        $data['user']->save();
        return view('admin.users.user_details' , ['data' => $data]);
    }

    // edit user details
    public function edit(Request $request){
        $data['user'] = User::find($request->id);
        return view('admin.users.user_edit' , ['data' => $data]);
    }

    // edit user Post Method
    public function EditPost(Request $request){
        $check_user_phone = User::where('phone' , $request->phone)->where('id' , '!=' , $request->id)->first();
        if($check_user_phone){
            return redirect('admin-panel/users/edit/'.$request->id)->with('status', 'Phone Exists Before');
        }

        $check_user_mail = User::where('email' , $request->email)->where('id' , '!=' , $request->id)->first();
        if($check_user_mail){
            return redirect('admin-panel/users/edit/'.$request->id)->with('status', 'Email Exists Before');
        }

        $current_user = User::find($request->id);
        $current_user->name = $request->name;
        $current_user->phone = $request->phone;
        $current_user->email = $request->email;
		$current_user->free_ads_count = $request->free_ads_count;
		$current_user->paid_ads_count = $request->paid_ads_count;
        if($request->password){
            $current_user->password = Hash::make($request->password);
        }
        $current_user->save();
        return redirect('admin-panel/users/show');
    }

    // get add user
    public function AddGet(Request $request){
        return view('admin.users.user_form');
    }

    // post add user
    public function AddPost(Request $request){
        $check_user_phone = User::where('phone' , $request->phone)->first();
        if($check_user_phone){
            return redirect('admin-panel/users/add')->with('status', 'Phone Exists Before');
        }

        $check_user_mail = User::where('email' , $request->email)->first();
        if($check_user_mail){
            return redirect('admin-panel/users/add')->with('status', 'Email Exists Before');
        }

        $setting = Setting::find(1);
        $free_ads = $setting['free_ads_count'];

        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->free_ads_count = $free_ads;
        $user->save();
        return redirect('admin-panel/users/show');
    }

    // send notifications
    public function SendNotifications(Request $request){
        $user = User::find($request->id);
        $fcm_token = $user->fcm_token;

        if(!$fcm_token){
            return redirect('admin-panel/users/details/'.$request->id)->with('error', 'Empty Fcm Token');
        }

        if($request->file('image')){
            $image_name = $request->file('image')->getRealPath();
            Cloudder::upload($image_name, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];
            $image_new_name = $image_id.'.'.$image_format;
        }else{
            $image_new_name = null;
        }

        $insert_notification = new Notification();
        $insert_notification->image = $image_new_name;
        $insert_notification->title = $request->title;
        $insert_notification->body = $request->body;
        $insert_notification->save();

        $user_notification = new UserNotification();
        $user_notification->notification_id = $insert_notification->id;
        $user_notification->user_id = $request->id;
        $user_notification->save();

		$the_image = "https://res.cloudinary.com/duwmvqjpo/image/upload/w_100,q_100/v1581928924/".$image_new_name;

        $notification = APIHelpers::send_notification($request->title , $request->body , $the_image , null , [$fcm_token]);
        $json_notification = json_decode($notification);
        if($json_notification->success){
             return redirect('admin-panel/users/details/'.$request->id)->with('status', 'Sent');
        }else{
             return redirect('admin-panel/users/details/'.$request->id)->with('error', 'Failed');
        }

    }

	// add credit
	public function addcredit(Request $request){
		$user = User::find($request->id);
		return $user;
	}
//send balance manual to single user
    public function send_balance(Request $request){
        $data = $this->validate(\request(),
            [
                'user_id' => 'required',
                'ammount' => 'required'
            ]);

        $user = User::where('id',$request->user_id)->first();

        $final_data['free_balance'] = $user->free_balance + $request->ammount;
        $final_data['my_wallet'] = $user->my_wallet + $request->ammount;
        $result =  User::where('id',$request->user_id)->update($final_data);
        if($result >0){
            $trans_data['user_id'] =  $request->user_id;
            $trans_data['value'] =  $request->ammount;
            $trans_data['type'] = 'free';
            WalletTransaction::create($trans_data);
            $lastToken = Visitor::where('user_id', $request->user_id)->where('fcm_token' ,'!=' , null)->latest('updated_at')->select('id', 'fcm_token')->first();
            $title = "";
            $body = "لقد حصلت على " . $request->ammount . " دينار رصيد مجانى";
            $notification = Notification::create(['title' => $title, 'body' => $body]);
            UserNotification::create([
                'user_id' => $user->id,
                'notification_id' => $notification->id,
                'visitor_id' => $lastToken->id
                ]);
            $notificationss = APIHelpers::send_notification($title , $body , "", null , [$lastToken->fcm_token]);
            
            session()->flash('success', trans('messages.sent_s'));
            return back();
        }else{
            session()->flash('danger', trans('messages.dont_sent_s'));
            return back();
        }
    }
//send balance manual to group of users
    public function send_group_balance(Request $request){
        $data = $this->validate(\request(),
            [
                'ammount' => 'required'
            ]);
        $users = User::where('active',1)->get();
        foreach ($users as $user){
            $user_data = User::find($user->id);
            $user_data->free_balance = $user_data->free_balance + $request->ammount;
            $user_data->my_wallet = $user_data->my_wallet + $request->ammount;
            if($user_data->save()){
                $trans_data['user_id'] =  $user->id;
                $trans_data['value'] =  $request->ammount;
                $trans_data['type'] = 'free';
                WalletTransaction::create($trans_data);
                $lastToken = Visitor::where('user_id', $user->id)->where('fcm_token' ,'!=' , null)->latest('updated_at')->select('id', 'fcm_token')->first();
                $title = "Q8Ad";
                $body = "لقد حصلت على " . $request->ammount . " دينار رصيد مجانى";
                $notification = Notification::create(['title' => $title, 'body' => $body]);
                UserNotification::create([
                    'user_id' => $user->id,
                    'notification_id' => $notification->id,
                    'visitor_id' => $lastToken->id
                    ]);
                $notificationss = APIHelpers::send_notification($title , $body , "", null , [$lastToken->fcm_token]);
            }
        }
        session()->flash('success', trans('messages.sent_s'));
        return back();
    }

    // block user
    public function block(Request $request){
        $user = User::find($request->id);
        $user->active = 0;
        $user->save();
        return redirect()->back();
    }

    // active user
    public function active(Request $request){
        $user = User::find($request->id);
        $user->active = 1;
        $user->save();
        return redirect()->back();
    }

    // get user products
    public function get_user_products(User $user) {
        $data['products'] = $user->products;
        $data['user'] = $user->name;

        return view('admin.products.products', ['data' => $data]);
    }
    public function get_user_ended_products(User $user) {
        $data['products'] = $user->Ended_products;
        $data['user'] = $user->name;
        return view('admin.products.products', ['data' => $data]);
    }
}
