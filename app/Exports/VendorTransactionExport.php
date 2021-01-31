<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Invoice;
use App\Models\Vendor;

class VendorTransactionExport implements FromArray,ShouldAutoSize, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $months;

    public function __construct($vendor_id)
    {
     	$this->vendor_id = $vendor_id;   
    }
    public function headings(): array
        {
                return [
                    '#',
                    'Created Date',
                    'Billing Status',
                    'Amount',
                    
                ];
        }
        public function array(): array
	    {

	    	$vendor = Vendor::find($this->vendor_id);
	    	$purchases = $vendor->purchases;
	        
	        
	    	
	        $data=[];
	        $value=[];
	        $i=1;
	        foreach($purchases as $detail){
	        	
	        	$value['#']=$i;
	        	$value['created_at']=$detail->vat_date;
	        	$value['billing_status']=$detail->total_amount_of_purchase_amount_paid==$detail->total?'Closed':'Open';
	        	$value['amount']=$detail->total;
	        	
	        	array_push($data,$value);
	        $i++;
	        }
	        
	        
	        return $data;
	    }
}
