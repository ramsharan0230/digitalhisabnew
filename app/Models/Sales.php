<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $fillable=['vat_pan','particular','sales_to','bill_no','type','month','year','vat_date','taxable_amount','vat_paid','total_paid','payment_type','paid_from_bank','total','round_total','bill_image','not_vat','collected','invoice_id'];

    public function invoice(){
    	return $this->belongsTo('App\Models\Invoice');
    }
}
