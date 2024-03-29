<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Client\ClientRepository;
use App\Repositories\nepalicalendar\nepali_date;
use App\Repositories\Invoice\InvoiceRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientTransactionExport;
use App\Repositories\Setting\SettingRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Received\ReceivedRepository;
use App\Repositories\Vendor\VendorRepository;
use PDF, DB;


class ClientController extends Controller
{
    public function __construct(VendorRepository $vendor, ReceivedRepository $received, PaymentRepository $payment, ClientRepository $client,nepali_date $calendar,InvoiceRepository $invoice,SettingRepository $setting){
        $this->client=$client;
        $this->calendar=$calendar;
        $this->invoice=$invoice;
        $this->setting = $setting;
        $this->payment=$payment;
        $this->received=$received;
        $this->vendor=$vendor;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $details = $this->client->orderBy('created_at','desc')->get();

        return view('admin.client.list',compact('details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.client.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required|string|max:200',
            'email'=>'required|email|unique:clients',
            'address'=>'required|max:200',
            'vat_no'=>'sometimes|string'
        ]);

        $data = $request->except(['contact_person', '_token', 'designation', 'phone', 'submit']);
        $contact_persons = array_filter($request->contact_person);
        $designations = array_filter($request->designation);
        $phones = array_filter($request->phone);

        $data['contact_person'] = json_encode($contact_persons);
        $data['designation'] = json_encode($designations);
        $data['phone'] = json_encode($phones);

        $this->client->create($data);

        return redirect()->route('client.index')->with('message','Client Added Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = $this->client->findOrFail($id);
        
        return view('admin.client.clientView',compact('detail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $detail = $this->client->findOrFail($id);
        return view('admin.client.edit', compact('detail'));
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
            'name'=>'required|string|max:200',
            'email'=>'required|email|unique:clients,email,'.$id,
            'phone'=>'required',
            'address'=>'required|max:200',
            'vat_no'=>'required|string',
        ]);
        
        $data = $request->except(['contact_person', '_token', 'designation', 'phone', 'submit']);
        $data['contact_person'] = $this->filterArray($request->contact_person);
        $data['designation'] = $this->filterArray($request->designation);
        $data['phone'] = $this->filterArray($request->phone);

        $this->client->update($data, $id);
        return redirect()->route('client.index')->with('message','Client Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->client->destroy($id);
        return redirect()->back()->with('message','Client Deleted Successfully');
    }
    public function searchMonthlyClientBusinessDetail(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        
        $invoices = $this->invoice->whereMonth('nepali_date', $request->month)->get();
        
        return view('admin.client.monthlyView',compact('invoices'));
    }
    public function exportClientTransaction(Request $request){
        $year = $request->year;
        $month = $request->month;
        
        if($year!=null &&  $month !=null)
            $details=DB::table('receiveds')->whereYear('date', $request->year)->whereMonth('date', $request->month)->orderBy('created_at','desc')->get();
        if($year==null ||  $month !=null)
            $details=DB::table('receiveds')->whereMonth('date', $request->month)->orderBy('created_at','desc')->get();

        $pdf = PDF::loadView('admin.payment.paymentsPdf', compact('details', 'year'));
        return $pdf->stream('payments.pdf');
    }

    public function exportClientTransactionPdf(Request $request){
        $year = $request->year;
        $month = $request->month;
        
        if($year!=null &&  $month !=null){
            $invoices = $this->invoice->whereMonth('nepali_date', $month)->whereYear('nepali_date', $year)->get();
        }
        if($year==null ||  $month !=null){
            $invoices=$this->invoice->whereMonth('nepali_date', $month)->orderBy('created_at','desc')->get();
        }

        $pdf = PDF::loadView('admin.client.exportClientTransactionPdf', compact('invoices', 'year', 'month'));
        return $pdf->stream('client-transactions.pdf');
    }


    public function clientInvoicePreview($id){
        $data=$this->invoice->find($id);
        $description=$data->invoiceDetail;
        $dashboard_setting=$this->setting->first();
        return view('admin.invoice.preview',compact('data','description','dashboard_setting'));
    }
    public function otherReceipts($id){
        $client = $this->client->findOrfail($id);
        $otherReceipts = $client->otherReceipts;
        return view('admin.client.otherReceipts',compact('client','otherReceipts'));
    }
    public function clientCustomSearch(Request $request){

        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        $client = $this->client->findOrFail($request->client_id);

        $invoices = $client->invoice()->whereBetween('nepali_date',[$request->start_date,$request->end_date])->get();
        return view('admin.client.monthlyView',compact('invoices'));
    }

    public function filterArray($data){
        return $this->jsonEncode(array_filter($data));
    }

    public function jsonEncode($data){
        return json_encode($data);
    }

    public function ledger(Request $request){
        $client = $this->client->where('id', $request->id)->first();
        $invoices = $this->invoice->where('id', $client->id)->get();
        $invoice_detail = [];   
        $invoice_payments = [];
        $invoice_receiveds = [];
        $sales = [];
        foreach($invoices as $invoice){
            array_push($invoice_detail, \DB::table('invoice_details')->where('invoice_id', $invoice->id)->get());
            array_push($invoice_payments, \DB::table('invoice_payments')->where('invoice_id', $invoice->id)->get());
            array_push($invoice_receiveds, \DB::table('receiveds')->where('invoice_id', $invoice->id)->get());
            array_push($sales, \DB::table('sales')->where('invoice_id', $invoice->id)->get());
        }
        
        return response()->json(['message'=>'success','html'=>view('admin.client.ledgerData',
        compact('client','invoices', 'invoice_receiveds', 'invoice_detail', 'invoice_payments', 'sales'))->render()]);
    }
}
