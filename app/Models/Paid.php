<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paid extends Model
{
    protected $fillable = ['date','particular','payment_type','amount','bank_id','paid_through_bank','transfered_through-wallet','paymentgateway_id','cheque_of_bank','narration','paid_to','purchase_id'];

    public function purchase(){
    	return $this->belongsTo('App\Models\Purchase','purchase_id');
    }
    public function bank(){
    	return $this->belongsTo('App\Models\Bank','paid_through_bank');
    }
}
