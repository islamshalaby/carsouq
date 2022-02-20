<?php

namespace App\Console\Commands;

use App\Plan_details;
use App\Product;
use App\Setting;
use App\User;
use App\Visitor;
use App\Notification;
use App\UserNotification;
use App\Helpers\APIHelpers;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Expire_ads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'expire product if time reached to expire date in product table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $expired = Product::where('status',1)->whereDate('expiry_date', '<', Carbon::now())->get();
        foreach ($expired as $row){
            $product = Product::find($row->id);
            $product->status = 2;
            $product->re_post = '0';
            $product->save();
        }

        $not_special = Product::where('status',1)->where('is_special','1')->whereDate('expire_special_date', '<', Carbon::now())->get();
        foreach ($not_special as $row){
            $product_special = Product::find($row->id);
            $product_special->is_special = '0';
            $product_special->save();
        }
        $mytime = Carbon::now();
        $today =  Carbon::parse($mytime->toDateTimeString())->format('Y-m-d H:i');
        $re_post_ad = Product::where('status',1)->where('re_post','1')->whereDate('re_post_date', '<', Carbon::now())->get();
        foreach ($re_post_ad as $row){

            $product_re_post = Product::find($row->id);
            $product_re_post->created_at = Carbon::now();
            // to generate new next repost date ...
            $re_post = Plan_details::where('plan_id',$row->plan_id)->where('type','re_post')->first();
            $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
            $final_expire_re_post_date = $final_pin_date->addDays($re_post->expire_days);

            $product_re_post->re_post_date = $final_expire_re_post_date;
            $product_re_post->save();
        }

        $pin_ad = Product::where('status',1)->where('pin','1')->whereDate('expire_pin_date', '<', Carbon::now())->get();
        foreach ($pin_ad as $row){
            $product_pined = Product::find($row->id);
            $product_pined->pin = '0';
            $product_pined->save();
        }

        $pin_ad = Setting::where('id',1)->whereDate('free_loop_date', '<', Carbon::now())->first();
        if ($pin_ad != null) {
            if($pin_ad->is_loop_free_balance == 'y') {
                $all_users = User::where('active', 1)->get();
                foreach ($all_users as $row) {
                    $user = User::find($row->id);
                    $user->my_wallet = $user->my_wallet + $pin_ad->free_loop_balance;
                    $user->free_balance = $user->free_balance + $pin_ad->free_loop_balance;
                    $user->save();
                    $lastToken = Visitor::where('user_id', $user->id)->where('fcm_token' ,'!=' , null)->latest('updated_at')->select('id', 'fcm_token')->first();
                    $title = "Q8Ad";
                    $body = "لقد حصلت على " . $pin_ad->free_loop_balance . " دينار رصيد مجانى";
                    $notification = Notification::create(['title' => $title, 'body' => $body]);
                    UserNotification::create([
                        'user_id' => $user->id,
                        'notification_id' => $notification->id,
                        'visitor_id' => $lastToken->id
                        ]);
                    $notificationss = APIHelpers::send_notification($title , $body , "", null , [$lastToken->fcm_token]);
                }

                $final_pin_date = Carbon::createFromFormat('Y-m-d H:i', $today);
                $final_free_loop_date = $final_pin_date->addDays($pin_ad->free_loop_period);
                $pin_ad->free_loop_date = $final_free_loop_date ;
                $pin_ad->save();
                
            }
        }
    }
}
