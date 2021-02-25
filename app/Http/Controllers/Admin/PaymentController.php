<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\nepalicalendar\nepali_date;
use App\Repositories\Balance\BalanceRepository;
use App\Repositories\Received\ReceivedRepository;
use App\Repositories\PaymentGateway\PaymentGatewayRepository;
use App\Repositories\Daybook\DaybookRepository;
use App\Repositories\Vat\VatRepository;
use App\Repositories\Paid\PaidRepository;
use App\Repositories\OfficeBalance\OfficeBalanceRepository;
use Carbon\Carbon;
use App\Models\Payment;
use DB;
class PaymentController extends Controller
{
    public function __construct(PaymentRepository $payment,nepali_date $calendar,BalanceRepository $balance,ReceivedRepository $received,PaymentGatewayRepository $gateway,VatRepository $vat,DaybookRepository $daybook,PaidRepository $paid,OfficeBalanceRepository $office_balance){
        $this->payment=$payment;
        $this->calendar=$calendar;
        $this->received=$received;
        $this->payment=$payment;
        $this->balance=$balance;
        $this->gateway=$gateway;
        $this->vat=$vat;
        $this->daybook=$daybook;
        $this->paid=$paid;
        $this->office_balance=$office_balance;
    }
    /**
     * Display a listing of the resource.
     *pay
     * @return \Illuminate\Http\Response
     */

    public function getNepaliDate(){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));    
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
    }

    public function index()
    {
        $details=$this->payment->orderBy('created_at','desc')->get();
        $todaysNepaliDate = $this->getNepaliDate();
        return view('admin.payment.list',compact('details','todaysNepaliDate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function paymentFilterAccDate(Request $request){
        $details=$this->payment->whereBetween('date', [$request->start_date, $request->end_date])->orderBy('created_at','desc')->get();
        $todaysNepaliDate = $this->getNepaliDate();
        return view('admin.payment.list',compact('details','todaysNepaliDate'));
    }

    public function create()
    {
        $digital_wallet=$this->gateway->all();
        return view('admin.payment.create',compact('digital_wallet'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate($request,[
            'paid_to'=>'required',
            'amount'=>'required|numeric',
            'cheque_number'=>'required_if:payment_type,==,Cheque',
            'bank'=>'required_if:payment_type,==,Cheque',
            'digital_wallet'=>'required_if:payment_type,==,Digital Wallet',
        ]);
        
        try{
            
            DB::beginTransaction();
            //nepali date
            $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));    
            $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
            $data=$request->all();
            //$data['date']=$todaysNepaliDate;

            
            if($request->payment_type=='Cheque'){
                $data['bank_id']=$request->bank;
                $data['paymentgateway_id'] = null;
            }
            if($request->payment_type == 'Digital Wallet'){
                $data['bank_id']=null;
                $data['paymentgateway_id'] = $request->digital_wallet;
            }

            
            $value = $this->payment->create($data);
            if($request->payment_type=='Cash'){
                $officeBalance['amount']=$request->amount;
                $officeBalance['type']='payment';
                
                $this->office_balance->create($officeBalance);
            }
            $this->createBalanceSheet($todaysNepaliDate);
            $this->createDaybook($value,$request->date);
            DB::commit();

            return redirect()->route('payment.index')->with('message','Payment Added Successfully');
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
        $today=Carbon::today();
        $detail=$this->payment->whereDate('created_at',$today)->findOrFail($id);
        $digital_wallet=$this->gateway->all();

        
        return view('admin.payment.edit',compact('detail','digital_wallet'));
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
        $this->validate($request,[
                'paid_to'=>'required',
                'amount'=>'required|numeric',
                'cheque_number'=>'required_if:payment_type,==,Cheque'
            ]);
        try{
            $today=Carbon::today();
            DB::beginTransaction();
            $detail=$this->payment->whereDate('created_at',$today)->findOrFail($id);
            
            $value=$request->all();
            if($request->payment_type=='Cash'){
                $value['bank']=null;
                $value['cheque_number']=null;
                $value['paymentgateway_id']=null;
            }
            if($request->payment_type=='Cheque'){
                $value['paymentgateway_id']==null;
            }
            
            $this->payment->update($value,$id);
            $this->updateDaybook($id,$value);
            $this->updateBalanceSheet($today);
            DB::commit();
            return redirect()->route('payment.index')->with('message','Payment Updated Successfully');
        }catch(\Exception $e){
            DB::rollback();

            throw $e;
            //return redirect()->back()->with('message','Something went wrong');

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
        try{
            DB::beginTransaction();
            $today=Carbon::today();
            $detail=$this->payment->whereDate('created_at',$today)->findOrFail($id);
            $daybook=$this->daybook->where('payment_id',$id)->first();
            $daybook['payment_data']=0;
            $daybook['payment_to']=null;
            $daybook['payment_for']=null;
            $daybook['payment_amount']=null;
            $daybook->save();
            $this->payment->destroy($id);
            $this->updateBalanceSheet($today);
            
            DB::commit();
            return redirect()->back()->with('message','Payment Deleted Sucessfully');
        }catch(\Exception $e){
            DB::rollback();
            return redirect()->back()->with('message','something went wrong');
        }
        
    }
    public function createDaybook($value,$nepalidate){
        $today=Carbon::now();
        $daybook=$this->daybook->whereDate('date',$nepalidate)->where('payment_data',0)->first();
        if($daybook){
            $daybook['payment_data']=1;
            $daybook['payment_to']=$value->paid_to;
            $daybook['payment_for']=$value->payment_for;
            $daybook['payment_amount']=$value->amount;
            $daybook['payment_id']=$value->id;
            $daybook['date']=$nepalidate;
            $daybook->save();
        }else{
            $data['payment_data']=1;
            $data['payment_to']=$value->paid_to;
            $data['payment_for']=$value->payment_for;
            $data['payment_amount']=$value->amount;
            $data['payment_id']=$value->id;
            $data['date']=$nepalidate;
            $this->daybook->create($data);
        }
        
    }
    public function updateDaybook($id,$value){
        $today=Carbon::now();
        $daybook=$this->daybook->where('payment_id',$id)->first();
        $daybook['payment_data']=1;
        $daybook['payment_to']=$value['paid_to'];
        $daybook['payment_for']=$value['payment_for'];
        $daybook['payment_amount']=$value['amount'];
        $daybook->save();
        
    }
    public function monthlyPaymentReport(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        
        
        $details=Payment::whereYear('date',$nepali_date['year'])->whereMonth('date',$request->value)->orderBy('created_at','desc')->get();
        return view('admin.payment.include.monthlyPaymentReport',compact('details'));
    }
    public function yearlyPaymentReport(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $details=Payment::whereYear('date',$request->value)->orderBy('created_at','desc')->get();
        return view('admin.payment.include.monthlyPaymentReport',compact('details'));
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
    public function customDateDaybookSearch(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        $details = $this->payment->whereBetween('date',[$request->start_date,$request->end_date])->get();
        return view('admin.payment.customSearch',compact('details','todaysNepaliDate'));

    }
}
