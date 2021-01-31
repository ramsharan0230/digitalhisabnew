<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $fillable=['invoice_id','fee_description','fee_amount'];
    protected $table='invoice_details';

    public function invoice(){
    	return $this->belongsTo('App\Models\Invoice');
    }
}
