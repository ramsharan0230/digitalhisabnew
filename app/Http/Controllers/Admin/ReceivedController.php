<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Received\ReceivedRepository;
use App\Repositories\nepalicalendar\nepali_date;
use App\Repositories\Client\ClientRepository;
use App\Repositories\OtherReceived\OtherReceivedRepository;
use App\Repositories\Daybook\DaybookRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\PaymentGateway\PaymentGatewayRepository;
use App\Models\Received;
use DB;

class ReceivedController extends Controller
{
    public function __construct(PaymentRepository $payment,PaymentGatewayRepository $gateway, ReceivedRepository $received,nepali_date $calendar,ClientRepository $client,OtherReceivedRepository $other_received,DaybookRepository $daybook){
        $this->received=$received;
        $this->calendar=$calendar;
        $this->client=$client;
        $this->other_received = $other_received;
        $this->daybook=$daybook;
        $this->gateway=$gateway;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $details=$this->received->orderBy('date','desc')->get();
        return view('admin.received.list',compact('details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $digital_wallet=$this->gateway->all();
        $clients = $this->client->orderBy('created_at','desc')->get();
        return view('admin.received.create',compact('clients', 'digital_wallet'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = ['client_id.required'=>'Client is required'];
        $this->validate($request,['client_id'=>'required|numeric','amount'=>'required','for'=>'required','date'=>'required'],$message);
        try{
            DB::beginTransaction();
            $client = $this->client->findOrFail($request->client_id);       
            $data= $request->all();

            if($data['payment_type']=='Cash' && $data['payment_type'] !='Digital Wallet' && $data['payment_type'] !='Cheque'){
                $data['keep_at_office']=1;
            }
            else{
                $data['keep_at_office']=0;

            }

            $data['payment_type'] =='Digital Wallet'?$data['paymentgateway_id']=1:$data['paymentgateway_id']=NULL;
            
            $data['from']=$client->name;

            $received = $this->other_received->create($data);
            $this->createDaybook($received, $request->date);

            DB::commit();
            return redirect()->route('received.index')->with('message','Received Created Successfully');
        }catch(\Exception $e){
            throw $e;
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function monthlyReceivedReport(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        
        $details=Received::whereYear('date', $nepali_date['year'])->whereMonth('date',$request->value)->orderBy('created_at','desc')->get();
        return view('admin.received.include.monthlyReceivedReport',compact('details'));
    }
    public function yearlyReceivedReport(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        
        $details=Received::whereYear('date',$request->value)->orderBy('created_at','desc')->get();
        
        return view('admin.received.include.monthlyReceivedReport',compact('details'));
    }
    public function receiptModal(Request $request){
        $detail=$this->received->find($request->id);
        return view('admin.received.include.view')->with('detail',$detail);
    }

    public function createDaybook($value,$nepalidate){
        
        $daybook=$this->daybook->whereDate('date',$nepalidate)->where('collection_data',0)->first();
        if($daybook){
            $daybook['collection_data']=1;
            $daybook['collection_from']=$value->from;
            $daybook['collection_amount']=$value->amount;
            $daybook['received_id']=null;
            $daybook['date']=$nepalidate;
            $daybook->save();
        }else{
            $data['collection_data']=1;
            $data['collection_from']=$value->from;
            $data['collection_amount']=$value->amount;
            $data['date']=$nepalidate;
            
            $this->daybook->create($data);
        }
        
    }
    public function customReceivedSearch(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        $details = $this->received->whereBetween('date',[$request->start_date,$request->end_date])->get();
        return view('admin.received.customSearch',compact('details'));
    }
}
