<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Sales;

class SalesVatExport implements FromArray,ShouldAutoSize, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $months;

    public function __construct($month)
    {
        $this->month = $month;
    }
    public function headings(): array
        {
                return [
                    '#',
                    'Sales To',
                    'Bill No',
                    'Date',
                    'Taxable Amount',
                    'Vat Received',
                    'Total',
                    'Vat No.',
                ];
        }
        public function array(): array
	    {

	        $details=Sales::whereMonth('vat_date',$this->month)->where('type','sales')->orderBy('created_at','desc')->get();
	        
	    	
	        $data=[];
	        $value=[];
	        $i=1;
	        foreach($details as $detail){
	        	
	        	$value['#']=$i;
	        	$value['sales_to']=$detail->sales_to;
	        	$value['bill_no']=$detail->bill_no;
	        	$value['date']=$detail->vat_date;
	        	$value['taxable_amount']=$detail->taxable_amount;
	        	$value['vat_paid']=$detail->vat_paid;
	        	$value['total']=$detail->total;
	        	$value['vat_pan']=$detail->vat_pan;
	        	array_push($data,$value);
	        $i++;
	        }
	        
	        
	        return $data;
	    }
}
