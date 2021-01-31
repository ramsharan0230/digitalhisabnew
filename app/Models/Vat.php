<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vat extends Model
{
    protected $fillable=['particular','bill_no','vat_date','taxable_amount','vat_paid','total','round_total','type','vat_pan','sales_to','bill_image','collected','purchased_from','purchased_item','total_paid','payment_type','paid_from_bank','not_vat','total_amount_of_purchase_amount_paid','collected_type'];

    public function purchasePayments(){
    	return $this->hasMany('App\Models\Paid','purchase_id');
    }
    public function purchaseTds(){
    	return $this->hasMany('App\Models\Tds','purchase_id');
    }


}
