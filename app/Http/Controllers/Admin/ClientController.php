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
use PDF, DB;


class ClientController extends Controller
{
    public function __construct(PaymentRepository $payment, ClientRepository $client,nepali_date $calendar,InvoiceRepository $invoice,SettingRepository $setting){
        $this->client=$client;
        $this->calendar=$calendar;
        $this->invoice=$invoice;
        $this->setting = $setting;
        $this->payment=$payment;
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
        $this->validate($request,[
            'name'=>'required|string|max:200',
            'email'=>'required|email|unique:clients',
            'phone'=>'required|numeric',
            'address'=>'required|max:200',
            'vat_no'=>'required|string',
            'contact_person'=>'required|string|max:200'
        ]);
        $this->client->create($request->all());
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
            'phone'=>'required|numeric',
            'address'=>'required|max:200',
            'vat_no'=>'required|string',
        ]);
        $this->client->update($request->all(),$id);
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
        if($year!=null ||  $month !=null)
            $details=DB::table('payments')->whereYear('date', $request->year)->whereMonth('date', $request->month)->orderBy('created_at','desc')->get();
        if($year==null ||  $month !=null)
            $details=DB::table('payments')->whereMonth('date', $request->month)->orderBy('created_at','desc')->get();

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
}
