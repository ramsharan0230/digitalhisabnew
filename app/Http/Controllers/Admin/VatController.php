<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Vat\VatRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VatExport;
use App\Exports\SalesVatExport;
use App\Models\Invoice;
use App\Repositories\Invoice\InvoiceRepository;
use App\Repositories\nepalicalendar\nepali_date;
use App\Repositories\Tds\TdsRepository;
use App\Repositories\Received\ReceivedRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Balance\BalanceRepository;
use App\Repositories\Daybook\DaybookRepository;
use App\Repositories\PaymentGateway\PaymentGatewayRepository;
use App\Repositories\Paid\PaidRepository;
use Carbon\Carbon;
use DB;

class VatController extends Controller
{
    public function __construct(VatRepository $vat,InvoiceRepository $invoice,nepali_date $calendar,TdsRepository $tds,ReceivedRepository $received,PaymentRepository $payment,BalanceRepository $balance,DaybookRepository $daybook,PaymentGatewayRepository $gateway,PaidRepository $paid){
        $this->vat = $vat;
        $this->invoice = $invoice;
        $this->calendar = $calendar;
        $this->tds = $tds;
        $this->payment = $payment;
        $this->balance = $balance;
        $this->received = $received;
        $this->daybook=$daybook;
        $this->gateway=$gateway;
        $this->paid = $paid;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $details=$this->vat->orderBy('created_at','desc')->where('type',null)->get();
        $salesVat=$this->vat->orderBy('created_at','desc')->where('type','sales')->get();
        $total_sales=Invoice::sum('grand_total');
        return view('admin.vat.list',compact('details','salesVat','total_sales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.vat.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        try{
            DB::beginTransaction();
            $this->validate($request,[
                'purchased_from'=>'required',
                'bill_no'=>'required|numeric',
                'round_total'=>'sometimes|nullable|numeric',
                //'vat_date'=>'required|date_format:Y-m-d',
                'vat_paid'=>'sometimes|nullable|numeric',
                //'total'=>'required|numeric',
                'taxable_amount'=>'nullable|sometimes|numeric',
                'total_paid'=>'required_if:notVat,==,1|numeric',
            ]);

            $value=$request->except('bill_image');
            $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
            
            $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
            if($request->bill_image){
                $image=$request->file('bill_image');
                $name=time().'bill_image.'.$image->getClientOriginalExtension();
                $image->move(public_path('images'),$name);
                $value['bill_image']=$name;
            }
            if($request->notVat==1){
                $value['not_vat']=1;
                $value['total']=$request->total_paid;
            }
            $value['vat_date']=$todaysNepaliDate;

            $vat = $this->vat->create($value);
            
            if($request->tdsShow==1){
                $this->saveTds($request->tds,$todaysNepaliDate,$request->purchased_from,$request->bill_no);
            }
            //$this->createBalanceSheet($todaysNepaliDate);
            //$this->createDaybook($vat);
            DB::commit();
            
            return redirect()->route('vat.index')->with('message','Vat added successfully');
        }catch(\Exception $e){
            // An error occured; cancel the transaction...
            DB::rollback();

            // and throw the error again.
            throw $e;
            //return redirect()->back()->with('message','Something went wrong');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $detail=$this->vat->findOrFail($id);
        return view('admin.vat.edit',compact('detail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{

            DB::beginTransaction();
            $today=Carbon::today();
            $this->validate($request,[
                'purchased_from'=>'required',
                'bill_no'=>'required|numeric',
                'round_total'=>'sometimes|nullable|numeric',
                //'vat_date'=>'required|date_format:Y-m-d',
                'vat_paid'=>'sometimes|nullable|numeric',
                //'total'=>'required|numeric',
                'taxable_amount'=>'nullable|sometimes|numeric',
                'total_paid'=>'required_if:notVat,==,1',
            ]);
            $value=$request->except('bill_image');
            $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
            
            $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
            if($request->bill_image){
                $image=$request->file('bill_image');
                $name=time().'bill_image.'.$image->getClientOriginalExtension();
                $image->move(public_path('images'),$name);
                $value['bill_image']=$name;
            }
            if($request->notVat==1){
                $value['not_vat']=1;
                $value['total']=$request->total_paid;
            }
            $this->vat->update($value,$id);
            // $this->updateDaybook($id,$value);
            // $this->updateBalanceSheet($today);
            $vat=$this->vat->findOrFail($id);
            DB::commit();
            if($vat->type=='sales'){
                return redirect()->route('salesVat')->with('message','Vat Updated Successfully');
            }else{
                return redirect()->route('vat.index')->with('message','Vat Updated Successfully');
            }
        }catch(\Exception $e){
            DB::rollback();

            throw $e;
        }
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->vat->destroy($id);
        return redirect()->back()->with('message','Vat Deleted Successfully');
    }
    
    public function updateDaybook($id,$value){
        $today=Carbon::now();
        $daybook=$this->daybook->where('purchase_id',$id)->first();
        $daybook['purchase_data']=1;
        $daybook['purchase_from']=$value['purchased_from'];
        $daybook['purchase_item']=$value['purchased_item'];
        $daybook['purchase_amount']=$value['total'];
        $daybook->save();
        
    }
    public function searchByMonth(Request $request){
        $value=$request->value;
        if($request->segment=='sales-vat'){
            $details=$this->vat->whereMonth('vat_date',$request->value)->where('type','sales')->orderBy('created_at','desc')->get();
            $purchase=$this->vat->whereMonth('vat_date',$request->value)->where('type',null)->orderBy('created_at','desc')->get();
            $sales_vat = $details->sum('vat_paid');
            $purchase_vat = $purchase->sum('vat_paid');
            $total_taxable_amount = $details->sum('taxable_amount');
            $total = $details->sum('total');
            $total_round_total = $details->sum('round_total');
            $difference = $sales_vat-$purchase_vat;
            $purchase_total=$purchase->sum('total');
            $invoice = Invoice::whereMonth('date',$request->value)->sum('grand_total');
            return view('admin.vat.include.salesVat',compact('details','value','sales_vat','purchase_vat','total_taxable_amount','total','total_round_total','difference','purchase_total','purchase','invoice'));
        }else{
            //purchase
            $details=$this->vat->whereMonth('vat_date',$request->value)->where('type',null)->orderBy('created_at','desc')->get();
            //sales
            $sales=$this->vat->whereMonth('vat_date',$request->value)->where('type','sales')->orderBy('created_at','desc')->get();
            $purchase_vat = $details->sum('vat_paid');
            $sales_vat= $sales->sum('vat_paid');
            $total_taxable_amount = $details->sum('taxable_amount');
            $total = $details->sum('total');
            $total_round_total = $details->sum('round_total');
            $difference = $sales_vat-$purchase_vat;
            $total_sales=Invoice::whereMonth('date',$request->value)->sum('grand_total');
            
            return view('admin.vat.include.table',compact('details','value','purchase_vat','sales_vat','total_taxable_amount','total','total_round_total','difference','sales','total_sales'));
        }
        
        
    }
    public function saveTds($tds,$nepali_date,$client,$bill_no){
        $value['amount']=$tds;
        $value['date']=$nepali_date;
        $value['company_name']=$client;
        $value['bill_no']=$bill_no;
        $value['purchase_id']=$bill_no;
        $value['tds_to_be_paid']=1;
        $this->tds->create($value);
    }

    public function exportVat(Request $request){

        $message=['month.required'=>'select Month'];
        $this->validate($request,['month'=>'required'],$message);
        if($request->type==1){
            return Excel::download(new SalesVatExport($request->month), 'vat.xlsx');
        }else{
            return Excel::download(new VatExport($request->month), 'vat.xlsx');
        }
        
    }
    public function salesVat(){

        $details=$this->vat->orderBy('created_at','desc')->where('type','sales')->get();

        $purchaseVat=$this->vat->orderBy('created_at','desc')->where('type',Null)->get();
        $sales=1;
        $invoice=Invoice::where('collected',0)->sum('grand_total');
        $total_sales=Invoice::sum('grand_total');
        return view('admin.vat.list',compact('details','sales','purchaseVat','invoice','total_sales'));
    }
    public function salesWithoutVat(){
        $details=Invoice::where('vat',0)->get();
        return view('admin.vat.salesWithoutVat',compact('details'));
    }
    public function searchSalesWithoutVat(Request $request){
        $details=Invoice::whereMonth('nepali_date',$request->value)->where('vat',0)->orderBy('created_at','desc')->get();
        return view('admin.vat.include.salesWithoutvat',compact('details'));
    }
    public function createBalanceSheet($todaysNepaliDate){
        //creating balance sheet

        $today=Carbon::today();
        

        $balance_today=$this->balance->whereDate('created_at',$today)->first();

        $balance_yesterday=$this->balance->orderBy('created_at','desc')->where('created_at','!=',$today)->first();
        $purchase=$this->paid->whereDate('created_at',$today)->sum('amount');
        $receivedAmount=$this->received->whereDate('created_at',$today)->sum('amount');
        $paymentAmount=$this->payment->whereDate('created_at',$today)->sum('amount');
        if(!$balance_yesterday){
                $yesterdays_balance=0;
        }else{
            $yesterdays_balance=$balance_yesterday->balance;
        }
        $todays_balance = $receivedAmount-($paymentAmount+$purchase);
        $balancesheet['received'] = $receivedAmount;
        $balancesheet['payment'] = $paymentAmount+$purchase;
        
        $balancesheet['date'] = $todaysNepaliDate;
        if($balance_today){
            $this->updateBalanceSheet($today);
        }else{
            $balancesheet['balance'] = $todays_balance+$yesterdays_balance;
            $this->balance->create($balancesheet);
        }
    }
    public function updateBalanceSheet($today){
    
        $todays_balance=$this->balance->whereDate('created_at',$today)->delete();
       
        $balance_yesterday=$this->balance->orderBy('created_at','desc')->where('created_at','!=',$today)->first();
        $purchase=$this->paid->whereDate('created_at',$today)->sum('amount');
        $receivedAmount=$this->received->whereDate('created_at',$today)->sum('amount');
        $paymentAmount=$this->payment->whereDate('created_at',$today)->sum('amount');
        $balancesheet['received'] = $receivedAmount;
        $balancesheet['payment'] = $paymentAmount+$purchase;
        $balancesheet['balance'] = $receivedAmount-($paymentAmount+$purchase) ;
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        $balancesheet['date']=$todaysNepaliDate;
        if($balance_yesterday){
            $balancesheet['balance'] = ($receivedAmount-($paymentAmount+$purchase))+$balance_yesterday->balance;    
        }
        
        $this->balance->create($balancesheet);
        

    }
    public function partialPurchasePayment(Request $request){
        $vat=$this->vat->find($request->id);
        if($vat){
            $digital_wallet = $this->gateway->all();
            return response()->json(['message'=>'success','html'=>view('admin.purchasePayment.partialPaymentModalData',compact('vat','digital_wallet'))->render()]);
            
        }else{
            return response()->json(['message'=>'fail']);
        }
    }
    public function payPartialPurchasePayment(Request $request){
        

        try{
            $message=[
                'tds.required_if'=>'please fill tds amount',
                'transfer_bank.required_if'=>'Please select bank if transfer type is bank'
            ];

            $this->validate($request,[
                
                //'tds'=>'required_if:tdsShow,==,1',
                //  'cheque_of_bank'=>'required_if:payment_type,==,Cheque',
                'bank'=>'required_if:payment_type,==,Cheque',
                'amount'=>'required|numeric',
                'deposited_at_bank'=>'required_if:payment_type,==,Cheque',
                //  
                'transfer_bank'=>'required_if:transfer_type,==,bank',
                'digital_wallet'=>'required_if:transfer_type,==,wallet',
            ],$message);
            DB::beginTransaction();
            $vat=$this->vat->findOrFail($request->vat_id);
            $vat->collected_type='partial';
            if($request->tds==1){
                $vat->tds_amount=$request->tds;
                $data['amount']=$request->amount-$request->tds;
            }
            $vat->total_amount_of_purchase_amount_paid+=$request->amount;
            if($vat->total_amount_of_purchase_amount_paid==$vat->total){
                $vat->collected=1;
            }
            $vat->save();
            $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
            
            $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);

            //paid
            $data['date']=$todaysNepaliDate;
            $data['particular']=$request->particular;
            $data['payment_type']=$request->payment_type;
            $data['amount']=$request->amount;
            
            $data['narration']=$request->narration;
            
            $data['received_from']=$vat->purchased_from;
            $data['purchase_id']=$vat->id;
            if($request->payment_type=='Cash'){
                $data['paid_through_bank']=null;
            }
            if($request->payment_type=='Cheque'){
                $data['paid_through_bank']=$request->bank;
            }

            
            if($request->payment_type=='Transfered'){
                if($request->transfer_type=='wallet'){
                    $data['paid_through_bank']=null;
                    $data['paymentgateway_id']=$request->digital_wallet;
                }
                if($request->transfer_type=='bank'){
                    $data['paid_through_bank']=$request->transfer_bank;   
                }
            }
            
            $paid = $this->paid->create($data);
            $this->createDaybook($paid,$todaysNepaliDate);
            $this->createBalanceSheet($todaysNepaliDate);
            //tds
            if($request->tdsShow==1){
                $this->saveTds($request->tds,$todaysNepaliDate,$vat->purchased_from,$vat->id);
            }

            DB::commit();
            return redirect()->route('vat.index')->with('message','Payment made');


        }catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
        
    }
    public function fullPurchasePayment(Request $request){
        $vat=$this->vat->find($request->id);
        if($vat){
            $digital_wallet = $this->gateway->all();
            return response()->json(['message'=>'success','html'=>view('admin.purchasePayment.fullPaymentModalData',compact('vat','digital_wallet'))->render()]);
            
        }else{
            return response()->json(['message'=>'fail']);
        }
    }
    public function payFullPurchasePayment(Request $request){
        $message=[
            'tds.required_if'=>'please fill tds amount',
            'transfer_bank.required_if'=>'Please select bank if transfer type is bank'
        ];

        $this->validate($request,[
            
            //'tds'=>'required_if:tdsShow,==,1',
            //  'cheque_of_bank'=>'required_if:payment_type,==,Cheque',
            'bank'=>'required_if:payment_type,==,Cheque',
            //'amount'=>'required|numeric',
            'deposited_at_bank'=>'required_if:payment_type,==,Cheque',
            //  
            'transfer_bank'=>'required_if:transfer_type,==,bank',
            'digital_wallet'=>'required_if:transfer_type,==,wallet',
        ],$message);

        try{
            DB::beginTransaction();
            $vat=$this->vat->findOrFail($request->vat_id);
            $vat->collected_type='full';
            if($request->tds==1){
                $vat->tds_amount=$request->tds;
                $data['amount']=$request->amount-$request->tds;
            }
            $vat->total_amount_of_purchase_amount_paid+=$vat->total;
            if($vat->total_amount_of_purchase_amount_paid==$vat->total){
                $vat->collected=1;
            }
            
            $vat->save();
    
            $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
            
            $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);

            //paid
            $data['date']=$todaysNepaliDate;
            $data['particular']=$request->particular;
            $data['payment_type']=$request->payment_type;
            $data['amount']=$vat->total;
            
            $data['narration']=$request->narration;
            $data['purchase_id']=$vat->id;
            $data['received_from']=$vat->purchased_from;
            if($request->payment_type=='Cash'){
                $data['paid_through_bank']=null;
            }
            if($request->payment_type=='Cheque'){
                $data['paid_through_bank']=$request->bank;
            }

            
            if($request->payment_type=='Transfered'){
                if($request->transfer_type=='wallet'){
                    $data['paid_through_bank']=null;
                    $data['paymentgateway_id']=$request->digital_wallet;
                }
                if($request->transfer_type=='bank'){
                    $data['paid_through_bank']=$request->transfer_bank;   
                }
            }
            
            $paid = $this->paid->create($data);
            $this->createDaybook($paid,$todaysNepaliDate);
            $this->createBalanceSheet($todaysNepaliDate);
            
            //tds
            if($request->tdsShow==1){
                $this->saveTds($request->tds,$todaysNepaliDate,$vat->purchased_from,$vat->id);
            }

            DB::commit();
            return redirect()->route('vat.index')->with('message','Payment made');

        }catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
    }
    public function createDaybook($value,$nepalidate){
        
        $today=Carbon::now();
        $daybook=$this->daybook->whereDate('date',$nepalidate)->where('purchase_data',0)->first();
        
        if($daybook){
            $daybook['purchase_data']=1;
            $daybook['purchase_from']=$value->purchase->purchased_from;
            $daybook['purchase_item']=$value->purchase->purchased_item;
            $daybook['purchase_amount']=$value->amount;
            $daybook['purchase_id']=$value->purchase->id;
            $daybook['date']=$nepalidate;
            $daybook->save();
        }else{
            $data['purchase_data']=1;
            $data['purchase_from']=$value->purchase->purchased_from;
            $data['purchase_item']=$value->purchase->purchased_item;
            $data['purchase_amount']=$value->amount;
            $data['purchase_id']=$value->purchase->id;
            $data['date']=$nepalidate;
            $this->daybook->create($data);
        }
        
    }
    public function purchasePaymentList($id){
        $purchase = $this->vat->findOrFail($id);
        
        return view('admin.purchasePayment.list',compact('purchase'));
    }
}
