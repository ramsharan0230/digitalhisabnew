<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Received extends Model
{
    protected $fillable=['particular','payment_type','amount','narration','date','bank_id','received_from','deposited_at_bank','cheque_of_bank','transfered_to_wallet','paymentgateway_id','invoice_id'];
    protected $table='receiveds';

    public function bank(){
    	return $this->belongsTo('App\Models\Bank','deposited_at_bank');
    }
    public function wallet(){
    	return $this->belongsTo('App\Models\PaymentGateway','paymentgateway_id');
    }

    public function invoice(){
        return $this->belongsTo('App\Models\Invoice');
    }
}
