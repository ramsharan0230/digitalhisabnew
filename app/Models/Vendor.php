<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable =['name','email','phone','address','vat_no','contact_person','designation'];

    public function purchases(){
    	return $this->hasMany('App\Models\Purchase','vendor_id');
    }
    
}
