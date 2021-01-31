<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable=['paid_to','payment_type','amount','narration','date','cheque_number','bank','paymentgateway_id','payment_gateway','payment_for','bank_id'];
    protected $table='payments';

    public function wallet(){
    	return $this->belongsTo('App\Models\PaymentGateway','paymentgateway_id');
    }
}
