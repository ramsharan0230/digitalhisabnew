<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Invoice\InvoiceRepository;
use App\Models\InvoiceDetail;
use App\Repositories\Setting\SettingRepository;
use App\Repositories\InvoicePayment\InvoicePaymentRepository;
use App\Repositories\nepalicalendar\nepali_date;
use App\Repositories\Vat\VatRepository;
use App\Repositories\Received\ReceivedRepository;
use App\Repositories\Tds\TdsRepository;
use App\Models\SalesWithoutVat;
use App\Repositories\Balance\BalanceRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\PaymentGateway\PaymentGatewayRepository;
use App\Repositories\Daybook\DaybookRepository;
use App\Repositories\Paid\PaidRepository;
use App\Repositories\Sales\SalesRepository;
use App\Repositories\OfficeBalance\OfficeBalanceRepository;
use App\Repositories\Client\ClientRepository;
use Illuminate\Support\Facades\Input;


use App\Models\Invoice;
use Mail;
use PDF;
use DB;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    private $invoice;
    private $client;
    public function __construct(InvoiceRepository $invoice,SettingRepository $setting,VatRepository $vat,InvoicePaymentRepository $invoice_payment,nepali_date $calendar,ReceivedRepository $received,TdsRepository $tds,BalanceRepository $balance,PaymentRepository $payment,PaymentGatewayRepository $gateway,DaybookRepository $daybook,PaidRepository $paid,SalesRepository $sales,OfficeBalanceRepository $office_balance,ClientRepository $client){
        $this->invoice=$invoice;
        $this->setting=$setting;
        $this->vat=$vat;
        $this->invoice_payment=$invoice_payment;
        $this->calendar = $calendar;
        $this->received=$received;
        $this->tds=$tds;
        $this->balance=$balance;
        $this->payment=$payment;
        $this->gateway=$gateway;
        $this->daybook=$daybook;
        $this->paid=$paid;
        $this->sales=$sales;
        $this->office_balance=$office_balance;
        $this->client=$client;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
         
        if($start_date && $end_date )
            $details=$this->invoice->orderBy('created_at','desc')->whereBetween('nepali_date',[$start_date, $end_date])->get();
        else
            $details=$this->invoice->orderBy('created_at','desc')->get();

        return view('admin.invoice.list',compact('details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $clients = $this->client->orderBy('created_at','desc')->get();
        return view('admin.invoice.create',compact('clients'));
    }

    public function invoiceExportPdf(Request $request){
        $year =$request->year;
        $month =$request->month;
        $details=$this->invoice->orderBy('created_at','desc')->whereYear('nepali_date',$year)->whereMonth('nepali_date',$month)->get();
        $pdf = PDF::loadView('admin.invoice.invoice-report-ym-pdf', compact('details', 'year', 'month'));
        return $pdf->stream('invoice-report.pdf');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    //    dd($request->all());
        $message=['client_id.required'=>'Client name is required'];
        $this->validate($request,[
            'email'=>'required|email',
            'client_address'=>'required|string',
            "fee_description"    => "required|array",
            "fee_description.*"  => "required|string|min:3",
            "fee_amount"    => "required|array",
            "fee_amount.*"  => "required|numeric|min:0",
            'cc'=>array('sometimes','nullable','regex:/^(\s?[^\s,]+@[^\s,]+\.[^\s,]+\s?,)*(\s?[^\s,]+@[^\s,]+\.[^\s,]+)$/'),
            'grand_total'=>'sometimes|nullable|numeric',
            'total'=>'sometimes|nullable|numeric',
            'client_id'=>'required|numeric',
        ],$message);
        try{
            DB::beginTransaction();
            $client = $this->client->find($request->client_id);
            $data=$request->except('fee_description','fee_amount', 'vat_pan', 'bill_no');
            $thumbPath = public_path('images/thumbnail/');
            $data['client_name']=$client->name;
            $data['date']=Carbon::now();
            $invID=$this->invoice->orderBy('id','desc')->pluck('id')->first();
            $newInvoiceID = $invID + 1;
            $invID = str_pad($newInvoiceID, 5, '0', STR_PAD_LEFT);
            $data['number']=$invID;
            $data['vatShow']=is_null($request->vatShow)?0:1;
            $data['grand_total']=$request->total;
            $data['remaining_amount_to_be_collected']=$request->total;
            $data['vat']=0;
            if($data['vatShow']==1){
                $data['vat']=$request->vat;
                $data['grand_total']=$data['vat']+$request->total;
                $data['remaining_amount_to_be_collected']=$data['vat']+$request->total;
            }else{
                $data['sales_without_vat']=1;
                $data['sales_without_vat_collected']=0;
            }
            $emails=explode(',',$request->cc);
                // dd($emails);
            $newEmails=[];
            foreach($emails as $user){
                $new=ltrim($user);
                if(filter_var($user, FILTER_VALIDATE_EMAIL)){
                    array_push($newEmails, $new);
                }
                

            }
            $data['serialized_cc']=serialize($newEmails);
            
            // $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
            // //dd($nepali_date);
            // //$nepali_date=$this->calendar->get_nepali_date(date('Y'),date('m'),date('d'));
            // $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
            // //$data['nepali_date']=$todaysNepaliDate;
            //dd($data);
            // $data['nepali_date']=$request->date;
            //dd($request->all());
            $id=$this->invoice->create($data);


            if($request->fee_amount){
                $this->saveFeeDetail($request->fee_description,$request->fee_amount,$id);
            }
            $invoiceRecentlyCreated = DB::table('invoices')->where('id', $id)->first();
            //  dd($invoiceRecentlyCreated);
            if($invoiceRecentlyCreated->vat !="0" ){
                $newSales = DB::table('sales')->insert(['vat_pan'=>$request->vat_pan?$request->vat_pan:'', 'sales_to'=>$invoiceRecentlyCreated->client_name, 'taxable_amount'=>$invoiceRecentlyCreated->total, 'vat_paid'=>$invoiceRecentlyCreated->vat, 'invoice_id'=>$id, 'type'=>'sales', 'particular'=>$request->fee_description[0]?$request->fee_description[0]:'', 'vat_date'=>$invoiceRecentlyCreated->nepali_date]);
            }else{
                // $newSales = DB::table('invoices')->insert(['sales_to'=>$invoiceRecentlyCreated->client_name, 'invoice_id'=>$id, 'type'=>'sales', 'particular'=>'asdf', 'vat_date'=>$invoiceRecentlyCreated->nepali_date]);
            }
            DB::commit();
            
            
            return redirect()->route('invoice.index')->with('message','Invoice Created Successfully');
        }catch(\Exception $e){
            DB::rollback();
            throw $e;

            //return redirect()->back()->with('message','Something Went Wrong');
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
        $detail=$this->invoice->findOrFail($id);
        $sales = $this->sales->where('invoice_id', $id)->first();
        return view('admin.invoice.vatDetail',compact('detail', 'sales'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $detail=$this->invoice->find($id);
        $datas=$detail->invoiceDetail;
        $clients = $this->client->orderBy('created_at','desc')->get();
        return view('admin.invoice.edit',compact('detail','datas','clients'));
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
        $message=['client_id.required'=>'Client name is required'];
        $this->validate($request,[
            'email'=>'required|email',
            'client_address'=>'required|string',
            "fee_description"    => "required|array",
            "fee_description.*"  => "required|string|min:3",
            "fee_amount"    => "required|array",
            "fee_amount.*"  => "required|numeric|min:0",
            'cc'=>array('sometimes','nullable','regex:/^(\s?[^\s,]+@[^\s,]+\.[^\s,]+\s?,)*(\s?[^\s,]+@[^\s,]+\.[^\s,]+)$/'),
            'grand_total'=>'sometimes|nullable|numeric',
            'total'=>'sometimes|nullable|numeric',
            'client_id'=>'required|numeric',
        ],$message);
        try{
            DB::beginTransaction();
            $client = $this->client->find($request->client_id);
            $data=$request->except('fee_description','fee_amount');
            $data['client_name']=$client->name;
            $data['grand_total']=$request->total;
            $data['remaining_amount_to_be_collected']=$request->total;
            if($request->vat){
                $data['vat']=$request->vat;
                $data['grand_total']=$data['vat']+$request->total;
                $data['remaining_amount_to_be_collected']=$data['vat']+$request->total;
            }
            
            $emails=explode(',',$request->cc);
            // dd($emails);
            $newEmails=[];
            foreach($emails as $user){
                $new=ltrim($user);

                if(filter_var($user, FILTER_VALIDATE_EMAIL)){
                    array_push($newEmails, $new);
                }

            }
            
            $data['serialized_cc']=serialize($newEmails);
            $this->invoice->update($data,$id);
            $this->deleteInvoiceDetail($id);
            if($request->fee_amount){
             $this->saveFeeDetail($request->fee_description,$request->fee_amount,$id);   
            }
            DB::commit();
            
            return redirect()->route('invoice.index')->with('message','Invoice Updated Successfully');
        }catch(\Exception $e){
            DB::rollback();
            return redirect()->back()->with('message','Something Went Wrong');
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
        $this->invoice->destroy($id);
        return redirect()->back()->with('message','Invoice Deleted Successfully');
    }
    public function deleteInvoiceDetail($id){
        $this->invoice->deleteInvoiceDetail($id);
        return;
    }
    public function saveFeeDetail($fee_description,$fee_amount,$id){
        for($i=0; $i< count($fee_description);$i++){
            $input = array('invoice_id' => $id, 'fee_description' => $fee_description[$i], 'fee_amount' => $fee_amount[$i]);
            $this->invoice->saveInvoiceDetails($input);
        }
    }
    public function previewInvoice($id){
        $data=$this->invoice->find($id);
        $description=$data->invoiceDetail;
        $dashboard_setting=$this->setting->first();
        return view('admin.invoice.preview',compact('data','description','dashboard_setting'));
    }
    public function sendInvoice(Request $request){
        try{
            $setting=$this->setting->first();
            set_time_limit(300);
            if($setting->email_to_send_invoice){
                $client=$this->invoice->find($request->id);
                $description=$client->invoiceDetail;
                $otheruser=unserialize($client->serialized_cc);
                
                $data = array(
                    'name'=>$client->client_name,
                    'email' => $client->email, 
                    'phone' => $client->contact, 
                    'total' => $client->total,
                    'description'=>$description,
                    'address'=>$client->client_address,
                    'number'=>$client->number,
                    'date'=>$client->date,
                    'grand_total'=>$client->grand_total,
                    'words'=>$client->words,
                    'vat'=>$client->vat,
                    'otheruser'=>$otheruser,
                    'cc'=>$client->cc,
                    'sender_email'=>$setting->email_to_send_invoice,
                    'collection_email'=>$setting->email_to_collect_invoice,
                    'organization_name'=>$setting->organization_name,
                    'image'=>$setting->logo,
                    'office_phone'=>$setting->phone_number,
                    'office_address'=>$setting->address,
                    'website'=>$setting->website,
                    'office_email'=>$setting->email
                );
                //dd($client->collected_amount);
                if($client->collected_amount==0){
                    
                    $pdf = PDF::loadView('testt',$data)->setPaper('a4'); 
                    Mail::send('admin.invoice.template', $data, function ($message) use ($data,$request,$pdf) {

                        $message->to($data['email'])->from($data['sender_email'],$data['organization_name'])->replyTo($data['email']);

                            $message->subject('Invoice');
                            $message->attachData($pdf->output(),'invoice.pdf'); 
                            if($data['cc']){
                                $message->cc($data['otheruser']);  
                            }
                            
                            
                    });
                    Mail::send('admin.invoice.template', $data, function ($message) use ($data,$request,$pdf) {

                        $message->to($data['collection_email'])->from($data['sender_email'],$data['organization_name']);

                            $message->subject($data['name']);
                            $message->attachData($pdf->output(),'invoice.pdf'); 
                    });
                    return "success";
                    
                }else{

                    //$pdf = PDF::loadView('testt',$data)->setPaper('a4'); 
                    $data['invoiceDetail'] = $client;
                    $pdf = PDF::loadView('reInvoicePdf',$data)->setPaper('a4'); 
                    Mail::send('admin.invoice.resendInvoice', $data, function ($message) use ($data,$request,$pdf) {

                        $message->to($data['email'])->from($data['sender_email'],$data['organization_name'])->replyTo($data['email']);

                            $message->subject('Invoice');
                            $message->attachData($pdf->output(),'invoice.pdf'); 
                            if($data['cc']){
                                $message->cc($data['otheruser']);  
                            }
                            
                            
                    });
                    Mail::send('admin.invoice.resendInvoice', $data, function ($message) use ($data,$request,$pdf) {

                        $message->to($data['collection_email'])->from($data['sender_email'],$data['organization_name']);

                            $message->subject($data['name']);
                            $message->attachData($pdf->output(),'invoice.pdf'); 
                    });
                    return "success";
                }
                
                //$pdf = PDF::loadView('testt',$data)->setPaper('a4'); 
                
                
            }else{
                return "fail";
            }
        }catch(\Exception $e){
            throw $e;
            //return response()->json(['message'=>'error']);
        }
    }
    public function saveVatDetail(Request $request, $id){
        // dd($request->all());

        try{
            $message=[
                'vat_pan.required'=>'vat or pan number is required'
            ];
            $this->validate($request,[
                'bill_no'=>'required|numeric',
                'vat_pan'=>'required',
                'bill_image'=>'sometimes|nullable|mimes:jpg,jpeg,png,bmp |max:2000'
            ]);
            $invoice=$this->invoice->findOrFail($id);
            $value['vat_pan']=$request->vat_pan;
            $value['sales_to']=$invoice->client_name;
            $value['bill_no']=$request->bill_no;
            
            $value['taxable_amount']=$invoice->total;
            $value['vat_paid']=$invoice->vat;
            $value['total']=$invoice->grand_total;
            $value['round_total']=$invoice->grand_total;
            $value['invoice_id']=$invoice->id;
            $value['type']='sales';
            if($request->bill_image){
                $image=$request->file('bill_image');
                $name=time().'bill_image.'.$image->getClientOriginalExtension();
                $image->move(public_path('images'),$name);
                $value['bill_image']=$name;
            }
            // $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
            // $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
            // $value['vat_date']=$todaysNepaliDate;
            $value['vat_date']=$request->date;

            $sales = $this->sales->where('id', $request->sales_id)->update($value);
            // dd($sales);
            $invoice->sales_id = $request->sales_id;
            
            $vat=$this->vat->create($value);
            $invoice->vat_id=$vat->id;

            $invoice->save();
            return redirect()->route('invoice.index')->with('message','Vat detail added successfully!');
        }catch(\Exception $e){
            DB::rollback();
             //throw $e;
            return redirect()->back()->with('message','something went wrong');
        }
        
    }
    
    public function saveSalesWithoutVat($id){

        $invoice=$this->invoice->findOrFail($id);
        $invoice->sales_without_vat_collected=1;
        $invoice->collected=1;
        $invoice->save();
        return redirect()->route('invoice.index')->with('message','collected successfully');
    }
    public function salesWithoutVat(){
        $details=$this->invoice->where('vat',0)->get();
        return view('admin.invoice.salesWithoutVat',compact(varname));
    }
    public function checkAmountToBeCollcted(Request $request){
        $invoice=$this->invoice->find($request->id);
        if($invoice){
            $digital_wallet = $this->gateway->all();
            return response()->json(['message'=>'success','html'=>view('admin.invoice.modalData',compact('invoice','digital_wallet'))->render()]);
            
        }else{
            return response()->json(['message'=>'fail']);
        }
    }
    public function payCollectedAmount(Request $request){
        // dd($request->all());
        $message=[
            'tds.required_if'=>'please fill tds amount',
            'transfer_bank.required_if'=>'Please select bank if transfer type is bank'
        ];
        $rules = [
            
            'tds'=>'required_if:tdsShow,==,1',
            'tds'=>'sometimes|nullable|numeric',
            'cheque_of_bank'=>'required_if:payment_type,==,Cheque',
            'bank'=>'required_if:payment_type,==,Diposited  ',
            'amount'=>'required|numeric',
            'deposited_at_bank'=>'required_if:payment_type,==,Cheque',
            'date'=>'required',
            // 'deposited_at_bank'=>'required_if:payment_type,==,Cash',
            'transfer_bank'=>'required_if:transfer_type,==,bank',
            'digital_wallet'=>'required_if:transfer_type,==,wallet',
        ];
        if(!isset($request->deposited_at_office)){
            $rules['deposited_at_bank']='required_if:payment_type,==,Cash';
        }
        
        $this->validate($request,$rules,$message);
        

        try {
            //for first time collection
            $invoice=$this->invoice->findOrFail($request->invoice_id);

            if($request->amount+$request->tds>$invoice->remaining_amount_to_be_collected){
                return redirect()->back()->with('message','Sum of amount paid and tds should not exceed total amount to be paid');
            }
            $today=Carbon::today();
            // Begin a transaction
            DB::beginTransaction();

            // Do something and save to the db...
            
            $invoice->collected_type='partial';
            if($request->tds==1){
                $invoice->tds_amount=$request->tds;
                $data['amount']=$request->amount-$request->tds;
            }
            $invoice->collected_amount+=($request->amount+$request->tds);
            $invoice->remaining_amount_to_be_collected = $invoice->grand_total-$invoice->collected_amount;
            if($invoice->collected_amount==$invoice->grand_total){
                $invoice->collected=1;
            }
            $invoice->save();

            if($request->deposited_at_office==1){
                $officeBalance['amount']=$request->amount;
                $officeBalance['type']='collection';
                $this->office_balance->create($officeBalance);
            }

            //invoice payment
            $data['invoice_id']=$invoice->id;
            $data['paid_amount']=$request->amount;
            $data['total_amount']=$invoice->grand_total;
            $data['remarks']=$request->particular;
            $data['payment_type']=$request->payment_type;
            $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
            //dd($nepali_date);
            //$nepali_date=$this->calendar->get_nepali_date(date('Y'),date('m'),date('d'));
            $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
            
            $data['paid_date']=$request->date;
            $this->invoice_payment->create($data);
            //received
            $data['payment_type']=$request->payment_type;
            $data['amount']=$request->amount;
            $data['particular']=$request->particular;
            $data['narration']=$request->narration;
            $data['date']=$request->date;
            $data['received_from']=$invoice->client_name;

            if($request->payment_type=='Cheque'){
                
                $data['cheque_of_bank']=$request->cheque_of_bank;
            }
            if($request->deposited_at_bank && $request->payment_type!='Diposited'){

                $data['deposited_at_bank']=$request->deposited_at_bank;
            }else{
                $data['deposited_at_bank']=$request->bank;   
            }
            if($request->payment_type=='Transfered'){
                if($request->transfer_type=='wallet'){
                    $data['deposited_at_bank']=null;
                    $data['paymentgateway_id']=$request->digital_wallet;
                }
                if($request->transfer_type=='bank'){
                    $data['deposited_at_bank']=$request->transfer_bank;   
                }
            }
            // if($request->tds){
            //     $data['amount']+=$request->tds;
            // }
            $data['invoice_id']=$invoice->id;
            $received = $this->received->create($data);
            //tds
            if($request->tdsShow==1){
                $this->saveTds($request->tds,$request->date,$invoice->client_name,$invoice->id);
            }
            //creating balancesheet
            $this->createBalanceSheet($todaysNepaliDate,$today);
            $this->createDaybook($received,$request->date);

            

            DB::commit();
            return redirect()->back()->with('message','Partial payment made successfully');
            // Commit the transaction
            
        } catch (\Exception $e) {
            // An error occured; cancel the transaction...
            DB::rollback();

            // and throw the error again.
            throw $e;
            // return redirect()->back()->with('message','Something went wrong');
        }
        
    }
    
    public function payFullAmount(Request $request){
        $invoice=$this->invoice->find($request->id);

        if($invoice){
            $digital_wallet = $this->gateway->all();
            return response()->json(['message'=>'success','html'=>view('admin.invoice.fullPaymentModalData',compact('invoice','digital_wallet'))->render()]);
        }else{
            return response()->json(['message'=>'fail']);
        }
        
    }
    public function saveFullAmount(Request $request){
        
        $this->validate($request,[
            'tds'=>'required_if:tdsShow,==,1',
            'cheque_of_bank'=>'required_if:payment_type,==,Cheque',
            'bank'=>'required_if:payment_type,==,Diposited  ',
            'deposited_at_bank'=>'required_if:payment_type,==,Cheque',
            'transfer_bank'=>'required_if:transfer_type,==,bank',
            'digital_wallet'=>'required_if:transfer_type,==,wallet',

        ]);
        if(!isset($request->deposited_at_office)){
            $rules['deposited_at_bank']='required_if:payment_type,==,Cash';
        }
        try {
            $invoice=$this->invoice->findOrFail($request->invoice_id);

            
            $today=Carbon::today();
            // Begin a transaction
            DB::beginTransaction();

            // Do something and save to the db...
            $invoice=$this->invoice->findOrFail($request->invoice_id);
            $invoice->collected_type='full';
            if($request->tdsShow==1){
                $invoice->tds_amount=$request->tds;
            }
            $invoice->collected_amount+=$invoice->grand_total;
             $invoice->remaining_amount_to_be_collected = $invoice->grand_total-$invoice->collected_amount;
            if($invoice->collected_amount==$invoice->grand_total){
                $invoice->collected=1;
            }
            $invoice->save();
            if($request->deposited_at_office==1){
                $officeBalance['amount']=$invoice->grand_total;
                $officeBalance['type']='collection';
                $this->office_balance->create($officeBalance);
            }
            //invoice payment detail
            $data['invoice_id']=$invoice->id;
            $data['paid_amount']=$request->amount;
            $data['total_amount']=$invoice->grand_total;
            $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
            //dd($nepali_date);
            //$nepali_date=$this->calendar->get_nepali_date(date('Y'),date('m'),date('d'));
            $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
            $data['paid_date']=$request->date;
            $this->invoice_payment->create($data);

            $data['payment_type']=$request->payment_type;
            $data['amount']=$invoice->grand_total-$request->tds;
            $data['particular']=$request->particular;
            $data['narration']=$request->narration;
            $data['date']=$request->date;
            $data['received_from']=$invoice->client_name;
            if($request->payment_type=='Cheque'){
                
                $data['cheque_of_bank']=$request->cheque_of_bank;
            }
            if($request->deposited_at_bank && $request->payment_type!='Diposited'){

                $data['deposited_at_bank']=$request->deposited_at_bank;
            }else{
                $data['deposited_at_bank']=$request->bank;   
            }
            if($request->payment_type=='Transfered'){
                if($request->transfer_type=='wallet'){
                    $data['deposited_at_bank']=null;
                    $data['paymentgateway_id']=$request->digital_wallet;
                }
                if($request->transfer_type=='bank'){
                    $data['deposited_at_bank']=$request->transfer_bank;  
                }
            }
            if($request->tds==1){
                $invoice->tds_amount=$request->tds;
                $data['amount']=$request->amount-$request->tds;
            }
            $received=$this->received->create($data);
            //tds
            if($request->tdsShow==1){
                $this->saveTds($request->tds,$request->date,$invoice->client_name,$invoice->id);
            }
            //creating balancesheet
            $this->createBalanceSheet($todaysNepaliDate,$today);
            $this->createDaybook($received,$todaysNepaliDate);
            DB::commit();
            return redirect()->back()->with('message','Full payment made successfully');
            // Commit the transaction
            
        } catch (\Exception $e) {
            // An error occured; cancel the transaction...
            DB::rollback();

            // and throw the error again.
            throw $e;
            // return redirect()->back()->with('message','Something went wrong');
        }
    }
    public function reInvoice($id){
        
    }
    public function report(){
        return view('admin.invoice.report');
    }
    public function invoiceMonthlyReport(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $details=$this->invoice->orderBy('created_at','desc')->whereMonth('nepali_date',$request->value)->get();
        return view('admin.invoice.include.monthlyInvoiceReport',compact('details'));
    }

    public function invoiceFilter(){
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');

        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $details=$this->invoice->orderBy('created_at','desc')->where('nepali_date',$start_date)->where('nepali_date',$end_date)->get();
        
        return view('admin.invoice.list',compact('details'));
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
    public function saveTds($tds,$nepali_date,$client,$id){
        $value['amount']=$tds;
        $value['date']=$nepali_date;
        $value['company_name']=$client;
        $value['bill_no']=$id;
        $value['invoice_id']=$id;
        $this->tds->create($value);
    }
    public function createDaybook($value,$nepalidate){
        $today=Carbon::now();
        $daybook=$this->daybook->whereDate('date',$nepalidate)->where('collection_data',0)->first();
        if($daybook){
            $daybook['collection_data']=1;
            $daybook['collection_from']=$value->received_from;
            $daybook['collection_amount']=$value->amount;
            $daybook['received_id']=$value->id;
            $daybook['date']=$nepalidate;
            $daybook->save();
        }else{
            $data['collection_data']=1;
            $data['collection_from']=$value->received_from;
            $data['collection_amount']=$value->amount;
            $data['date']=$nepalidate;
            
            $this->daybook->create($data);
        }
        
    }
    public function updateBalanceSheet($today){
        
        $todays_balance=$this->balance->whereDate('created_at',$today)->delete();
       
        $balance_yesterday=$this->balance->orderBy('created_at','desc')->where('created_at','!=',$today)->first();
        $purchase=$this->vat->where('type',null)->whereDate('created_at',$today)->sum('total');
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
    public function findClient(Request $request){
        $client= $this->client->find($request->value);
        if($client){
            return response()->json(['message'=>'success','client'=>$client]);
        }else{
            return response()->json(['message'=>'fail']);
        }
    }
    public function saveClient(Request $request){
        $this->validate($request,[
            'name'=>'required|string|max:200',
            'email'=>'required|email|unique:clients',
            'phone'=>'required|numeric',
            'address'=>'required|max:200',
            'vat_no'=>'required|string',
            'contact_person'=>'required|max:200',

        ]);
        $this->client->create($request->all());
        return redirect()->back()->with('message','Client Added Successfully');
    }
    public function printInvoice(Request $request){
        $data=$this->invoice->find($request->value);
        $description=$data->invoiceDetail;
        $dashboard_setting=$this->setting->first();
        return view('admin.invoice.preview',compact('data','description','dashboard_setting'));
    }

}
