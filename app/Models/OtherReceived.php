<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherReceived extends Model
{
    protected $fillable =['from','amount','for','remark','date','client_id', 'payment_type', 'bank_id', 'paymentgateway_id', 'cheque_number','keep_at_office'];
}
