<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnualBook extends Model
{
    protected $fillable=['date','collection_data','purchase_data','payment_data','collection_from','collection_amount',
    'purchase_from','purchase_item','purchase_amount','payment_to','payment_for','payment_amount','payment_id','received_id',
    'purchase_id'];
}
