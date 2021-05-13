<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable =['name','email','phone','address','vat_no','contact_person','designation'];

    public function invoice(){
    	return $this->hasMany('App\Models\Invoice','client_id');
    }
    public function otherReceipts(){
    	return $this->hasMany('App\Models\OtherReceived','client_id');
    }

    public function getDesignation($data){
        return $data;
    }
}
