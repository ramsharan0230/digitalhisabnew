<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Purchase;

class VatExport implements FromArray,ShouldAutoSize, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $monts;

    public function __construct($month)
    {
        $this->month = $month;
    }
    public function headings(): array
        {
                return [
                    '#',
                    'Purchased_from',
                    'Bill No',
                    'Date',
                    'Taxable Amount',
                    'Vat Paid',
                    'Total',
                    'Round Total',
                ];
        }
        public function array(): array
	    {

	        $details=Purchase::whereMonth('vat_date',$this->month)->orderBy('created_at','desc')->get();
	        
	    	
	        $data=[];
	        $value=[];
	        $i=1;
	        foreach($details as $detail){
	        	
	        	$value['#']=$i;
	        	$value['purchased_from']=$detail->purchased_from;
	        	$value['bill_no']=$detail->bill_no;
	        	$value['date']=$detail->vat_date;
	        	$value['taxable_amount']=$detail->taxable_amount;
	        	$value['vat_paid']=$detail->vat_paid;
	        	$value['total']=$detail->total;
	        	$value['round_total']=$detail->round_total;
	        	array_push($data,$value);
	        $i++;
	        }
	        
	        
	        return $data;
	    }
}
