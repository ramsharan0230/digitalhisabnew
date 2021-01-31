<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeBalance extends Model
{
    protected $fillable = ['amount','type'];
    protected $table='office_balances';
}
