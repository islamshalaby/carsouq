<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
     protected $fillable = ['payment_id', 'tran_id', 'value','price','user_id','type','package_id'];


    public function User()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function Package()
    {
        return $this->belongsTo('App\Balance_package', 'package_id');
    }
}
