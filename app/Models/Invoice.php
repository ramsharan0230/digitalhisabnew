<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable=['client_name','client_address','total','client_id','date','email','contact','number','filename','vat','grand_total','words','cc','serialized_cc','collected','sales_without_vat','sales_without_vat_collected','nepali_date','collected_type','collected_amount','tds_amount','sales_id','remaining_amount_to_be_collected','client_id'];
    protected $table='invoices';

    public function invoiceDetail(){
    	return $this->hasMany('App\Models\InvoiceDetail');
    }
    public function vatDetail(){
    	return $this->belongsTo('App\Models\Vat');
    }
    public function invoicePayments(){
    	return $this->hasMany('App\Models\InvoicePayment','invoice_id');
    }

    public function sales(){
    	return $this->hasOne('App\Models\Sales', 'invoice_id');
    }
}
