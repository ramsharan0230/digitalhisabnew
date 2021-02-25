<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Purchase\PurchaseRepository;
use App\Repositories\nepalicalendar\nepali_date;
use App\Repositories\Tds\TdsRepository;
use App\Repositories\Received\ReceivedRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Balance\BalanceRepository;
use App\Repositories\Daybook\DaybookRepository;
use App\Repositories\PaymentGateway\PaymentGatewayRepository;
use App\Repositories\Paid\PaidRepository;
use App\Repositories\Sales\SalesRepository;
use App\Repositories\OfficeBalance\OfficeBalanceRepository;
use App\Repositories\Vendor\VendorRepository;
use Carbon\Carbon;
use DB, PDF;
use Illuminate\Support\Facades\Input; 


class PurchaseController extends Controller
{
    public function __construct(PurchaseRepository $purchase,nepali_date $calendar,TdsRepository $tds,ReceivedRepository $received,PaymentRepository $payment,BalanceRepository $balance,DaybookRepository $daybook,PaymentGatewayRepository $gateway,PaidRepository $paid,SalesRepository $sales,OfficeBalanceRepository $office_balance,VendorRepository $vendor){
        $this->purchase=$purchase;
        $this->calendar = $calendar;
        $this->tds = $tds;
        $this->payment = $payment;
        $this->balance = $balance;
        $this->received = $received;
        $this->daybook=$daybook;
        $this->gateway=$gateway;
        $this->paid=$paid;
        $this->sales=$sales;
        $this->office_balance=$office_balance;
        $this->vendor=$vendor;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!empty($request->all())){
            // search
            // dd($request->start_date);
            $start_date = $request->start_date;
            $start_date = explode('-', $start_date);
            $sYear = $start_date[0];
            $sMonth = $start_date[1];
            $sDay = $start_date[2];
            $start_date = $this->calendar->nep_to_eng($sYear, $sMonth, $sDay);
            $month = ($start_date['month']>9?'':'0').$start_date['month'];
            $day = ($start_date['date']>9?'':'0').$start_date['date'];
            $start_date = $start_date['year'].'-'.$month.'-'.$day;

            // dd($start_date);
            // dd($end_date);
            $end_date = $request->end_date;
            $end_date = explode('-', $end_date);
            $eYear = $end_date[0];
            $eMonth = $end_date[1];
            $eDay = $end_date[2];
            $end_date = $eYear.'-'.$eMonth.'-'.$eDay;

            $end_date=$this->calendar->nep_to_eng($eYear, $eMonth, $eDay);

            $emonth = ($end_date['month']>9?'':'0').$end_date['month'];
            $eday = ($end_date['date']>9?'':'0').$end_date['date'];
            $end_date = $end_date['year'].'-'.$emonth.'-'.$eday;
            // dd($start_date, $end_date);
            $details=$this->purchase->whereBetween('created_at',[$start_date, $end_date])->orderBy('created_at','desc')->get();
            //search end
        }else{
            $details=$this->purchase->orderBy('created_at','desc')->get();
        }
        return view('admin.purchase.list',compact('details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = $this->vendor->orderBy('created_at','desc')->get();
        return view('admin.purchase.create',compact('vendors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = ['vendor_id.required'=>'Purchased from is required'];   
        $rules=[
            'bill_no'=>'required|numeric',
            'bill_image'=>'sometimes|nullable||mimes:jpg,jpeg,png|max:2000',
            'round_total'=>'sometimes|nullable|numeric',
            //'vat_date'=>'required|date_format:Y-m-d',
            'vat_paid'=>'sometimes|nullable|numeric',
            //'total'=>'required|numeric',
            
            'total_paid'=>'required_if:notVat,==,1',
            'vendor_id'=>'required|numeric',
        ];

        if(!isset($request->notVat)){
            $rules['taxable_amount']='required|numeric';

        }
        if($request->notVat==1){
            $rules['total_paid']='required|numeric';
        }

        $this->validate($request,$rules,$message);
        

        try{
            DB::beginTransaction();
            
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
            $vendor=$this->vendor->find($request->vendor_id);
            $value['purchased_from']=$vendor->name;
            $value['purchase_item']=$request->purchased_item;

            $vat = $this->purchase->create($value);
            
            DB::commit();
            
            return redirect()->route('purchase.index')->with('message','Vat added successfully');
        
        }
        // catch(\PDOException $exception){
        //     return $exception->getMessage();
        // }
        catch (\Exception $e) {
            // An error occured; cancel the transaction...
            DB::rollback();

            // and throw the error again.
            throw $e;
            // return redirect()->back()->with('message','Something went wrong');
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
        $detail = $this->purchase->findOrfail($id);
        $vendors = $this->vendor->orderBy('created_at','desc')->get();
        return view('admin.purchase.edit',compact('detail','vendors'));
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
        $rules=[
            'purchased_from'=>'required',
            'bill_no'=>'required|numeric',
            'bill_image'=>'sometimes|nullable||mimes:jpg,jpeg,png|max:2000',
            'round_total'=>'sometimes|nullable|numeric',
            //'vat_date'=>'required|date_format:Y-m-d',
            'vat_paid'=>'sometimes|nullable|numeric',
            //'total'=>'required|numeric',
            
            'total_paid'=>'required_if:notVat,==,1',
        ];

        if(!isset($request->notVat)){
            $rules['taxable_amount']='required|numeric';

        }
        if($request->notVat==1){
            $rules['total_paid']='required|numeric';
        }

        $this->validate($request,$rules);
        try{
            $purchase = $this->purchase->findOrFail($id);
            if(count($purchase->purchasePayments)==0){
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
                    $value['taxable_amount']=null;
                    $value['vat_paid']=null;
                    $value['round_total']=null;

                }
                $value['vat_date']=$todaysNepaliDate;
                
                $vat = $this->purchase->update($value,$id);
                DB::commit();
                
                return redirect()->route('purchase.index')->with('message','Purchase Updated successfully');
            }else{
                return redirect()->route('purchase.index')->with('message','This purchase cannot be updated');   
            }
            
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
            return redirect()->route('purchase.index')->with('message','something went wrong');
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
        $purchase = $this->purchase->findOrFail($id);
        
        if(count($purchase->purchasePayments)==0){
            $this->purchase->destroy($id);
            return redirect()->back()->with('message','purchase Deleted Successfully');
            
        }else{
            return redirect()->back()->with('message','This cannot be deleted');
        }
    }
    public function partialPurchasePayment(Request $request){
        $purchase=$this->purchase->find($request->id);
        if($purchase){
            $digital_wallet = $this->gateway->all();
            return response()->json(['message'=>'success','html'=>view('admin.purchasePayment.partialPaymentModalData',compact('purchase','digital_wallet'))->render()]);
            
        }else{
            return response()->json(['message'=>'fail']);
        }
    }
    public function payPartialPurchasePayment(Request $request){
        

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

        try{
            
            DB::beginTransaction();
            $purchase=$this->purchase->findOrFail($request->purchase_id);
            $purchase->collected_type='partial';
            if($request->tdsShow==1){
                // $purchase->tds_amount=$request->tds;
                $data['amount']=$request->amount-$request->tds;
            }else{
                $data['amount']=$request->amount;
            }

            $purchase->total_amount_of_purchase_amount_paid=$purchase->total_amount_of_purchase_amount_paid+($request->amount+$request->tds);

            
            if($purchase->total_amount_of_purchase_amount_paid==$purchase->total){
                $purchase->collected=1;
            }

            $purchase->save();
            $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
            
            $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);

            //paid
            $data['date']=$request->date;
            $data['particular']=$request->particular;
            $data['payment_type']=$request->payment_type;
            
            
            $data['narration']=$request->narration;
            
            $data['received_from']=$purchase->purchased_from;
            $data['purchase_id']=$purchase->id;

            if($request->payment_type=='Cash'){
                $officeBalance['amount']=$request->amount;
                $officeBalance['type']='payment';
                $this->office_balance->create($officeBalance);
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
            $this->createDaybook($paid,$request->date);
            $this->createBalanceSheet($todaysNepaliDate);
            //tds
            if($request->tdsShow==1){
                $this->saveTds($request->tds,$request->date,$purchase->purchased_from,$purchase->id);
            }

            DB::commit();
            return redirect()->route('purchase.index')->with('message','Payment made');


        }catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
        
    }
    public function fullPurchasePayment(Request $request){
        $vat=$this->purchase->find($request->id);
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
            //dd($request->all());
            DB::beginTransaction();
            $purchase=$this->purchase->findOrFail($request->vat_id);
            $purchase->collected_type='full';
            if($request->tds==1){
                $purchase->tds_amount=$request->tds;
                $data['amount']=$purchase->total_paid-$request->tds;
            }
            $purchase->total_amount_of_purchase_amount_paid+=$purchase->total;
            if($purchase->total_amount_of_purchase_amount_paid==$purchase->total){
                $purchase->collected=1;
            }
            
            $purchase->save();
    
            $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
            
            $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);

            //paid
            $data['date']=$request->date;
            $data['particular']=$request->particular;
            $data['payment_type']=$request->payment_type;
            
            
            $data['narration']=$request->narration;
            $data['purchase_id']=$purchase->id;
            $data['received_from']=$purchase->purchased_from;
            if($request->payment_type=='Cash'){
                $officeBalance['amount']=$purchase->total;
                $officeBalance['type']='payment';
                $this->office_balance->create($officeBalance);
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
            $this->createDaybook($paid,$request->date);
            $this->createBalanceSheet($todaysNepaliDate);
            
            //tds
            if($request->tdsShow==1){
                $this->saveTds($request->tds,$request->date,$purchase->purchased_from,$purchase->id);
            }

            DB::commit();
            return redirect()->route('purchase.index')->with('message','Payment made');

        }catch(\Exception $e){
            DB::rollback();
            throw $e;
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
    public function purchasePaymentList($id){
        $purchase = $this->purchase->findOrFail($id);
        return view('admin.purchasePayment.list',compact('purchase'));
    }
    public function purchaseSearchByMonth(Request $request){
        $value=$request->value;
        $total_sales = $this->sales->whereMonth('vat_date',$request->value)->orderBy('created_at','desc')->get();
        $sales_vat = $total_sales->sum('vat_paid');

        $details=$this->purchase->whereMonth('vat_date',$request->value)->orderBy('created_at','desc')->get();
        $purchase_vat = $details->sum('vat_paid');


        
        
        return view('admin.purchase.include.searchByMonthResult',compact('total_sales','sales_vat','details','purchase_vat'));
    }
    public function toBePaid(){
        $details = $this->purchase->orderBy('created_at','desc')->where('collected',0)->get();
        return view('admin.purchase.toBePaid',compact('details'));
    }

    public function toBePaidDateFilter(){
        $start_date = Input::get('start_date', 'end_date');
        $end_date = Input::get('end_date');
        $details = $this->purchase->whereBetween('vat_date', [$start_date, $end_date])->orderBy('created_at','desc')->where('collected',0)->get();
        return view('admin.purchase.toBePaid',compact('details'));
    }

    public function toBePaidPdf(Request $request){
        $year = $request->year;
        $month = $request->month;
        $details=$this->purchase->orderBy('created_at','desc')->whereYear('vat_date', $year)->whereMonth('vat_date', $month)->get();
        $pdf = PDF::loadView('admin.purchase.to-be-paid-pdf', compact('details', 'year', 'month'));
        return $pdf->stream('monthly-vat-to-be-paid.pdf');
    }

    public function customSearched(Request $request){
        $year = $request->year;
        $month = $request->month;
        $details=$this->purchase->orderBy('created_at','desc')->whereYear('vat_date', $year)->whereMonth('vat_date', $month)->get();

        return view('admin.purchase.include.toBePaidCustomSearched',compact('details'));
    }

    public function saveVendor(Request $request){
        
        $this->validate($request,[
            'name'=>'required|string|max:200',
            'email'=>'required|email|unique:vendors',
            'phone'=>'required|numeric',
            'address'=>'required|string|max:200',
            'vat_no'=>'required|string',
            'contact_person'=>'required|string|max:200',
        ]);
        $this->vendor->create($request->all());
        return redirect()->back()->with('message','Vendor Added Successfully');
    }
    
}
