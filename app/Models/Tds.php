<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tds extends Model
{

   	protected $fillable=['amount','detail','date','company_name','tds_to_be_paid','to_be_paid_to','bill_no','sales_id','purchase_id','invoice_id'];

}
