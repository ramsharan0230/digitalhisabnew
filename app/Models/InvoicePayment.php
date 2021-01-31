<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    protected $fillable=['invoice_id','paid_amount','total_amount','paid_date'];
    protected $table='invoice_payments';
}
