<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable =['particular','purchased_from','purchased_item','bill_no','vat_date','taxable_amount','vat_paid','total_paid','total','round_total','bill_image','total_amount_of_purchase_amount_paid','not_vat','collected','collected_type','vendor_id'];
    protected $table = 'purchases';


    public function purchasePayments(){
    	return $this->hasMany('App\Models\Paid','purchase_id');
    }
    public function purchaseTds(){
    	return $this->hasMany('App\Models\Tds','purchase_id');
    }
}
