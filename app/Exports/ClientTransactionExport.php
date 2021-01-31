<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Invoice;
use App\Models\Client;

class ClientTransactionExport implements FromArray,ShouldAutoSize, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $months;

    public function __construct($client_id)
    {
     	$this->client_id = $client_id;   
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

	    	$vendor = Client::find($this->client_id);
	    	$invoices = $vendor->invoice;
	        
	        
	    	
	        $data=[];
	        $value=[];
	        $i=1;
	        foreach($invoices as $detail){
	        	
	        	$value['#']=$i;
	        	$value['created_at']=$detail->nepali_date;
	        	$value['billing_status']=$detail->collected==$detail->grand_total?'Closed':'Open';
	        	$value['amount']=$detail->grand_total;
	        	
	        	array_push($data,$value);
	        $i++;
	        }
	        
	        
	        return $data;
	    }
}
