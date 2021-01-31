<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable=['name','publish','balance'];

    public function receiveds(){
    	return $this->hasMany('App\Models\Received','paymentgateway_id');
    }
    public function payments(){
    	return $this->hasMany('App\Models\Payment','paymentgateway_id');
    }
    public function purchasePayments(){
    	return $this->hasmany('App\Models\Paid','paymentgateway_id');
    }
}
