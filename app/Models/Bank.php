<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable=['name','branch','account_number','our_bank','balance'];

    public function received(){
    	return $this->hasMany('App\Models\Received','deposited_at_bank');
    }
    public function payments(){
    	return $this->hasMany('App\Models\Payment','bank_id');
    }
    public function purchasePayments(){
    	return $this->hasMany('App\Models\Paid','paid_through_bank');
    }
}
