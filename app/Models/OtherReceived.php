<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherReceived extends Model
{
    protected $fillable =['from','amount','for','remark','date','client_id'];
}
