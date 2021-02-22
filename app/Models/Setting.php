<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable=['organization_name','logo','phone_number','email','address','website','email_to_collect_invoice','sms_token','sms_content','module','invoice_logo','start_date','end_date','display_sales_without_vat','email_to_send_invoice'];
    protected $table='settings';
}
