<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Daybook;
use App\Models\Client;

class DaybookExport implements FromArray,ShouldAutoSize, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $months;

    public function __construct()
    {
     	 
    }
    public function headings(): array
        {
                return [
                    'Date',
                    'Collection From',
                    'Collected Amount',
                    'Purchased From',
                    'Purchased Item',
                    'Purchase Amount',
                    'Paid To',
                    'Paid For',
                    'Paid Amount'
                    
                ];
        }
        public function array(): array
	    {

	    	$daybooks = Daybook::all();
	    	
	        
	        
	    	
	        $data=[];
	        $value=[];
	        $i=1;
	        foreach($daybooks as $detail){
	        	
	        	$value['date']=$detail->date;
	        	$value['Collected From']=$detail->collection_from?$detail->collection_from:'------';
	        	$value['Collected Amount']=$detail->collection_amount?$detail->collection_amount:'------';
	        	$value['Purchased From']=$detail->purchase_from?$detail->purchase_from:'------';
	        	$value['Purchased Item']=$detail->purchase_item?$detail->purchase_item:'------';
	        	$value['Purchase Amount']=$detail->purchase_amount?$detail->purchase_amount:'------';
	        	$value['Paid To']=$detail->payment_to?$detail->payment_to:'------';
	        	$value['Paid For']=$detail->payment_for?$detail->payment_for:'------';
	        	$value['Paid Amount']=$detail->payment_amount?$detail->payment_amount:'------';
	        	array_push($data,$value);
	        $i++;
	        }
	        
	        
	        return $data;
	    }
}
