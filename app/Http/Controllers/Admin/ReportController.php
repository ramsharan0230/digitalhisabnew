<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Sales\SalesRepository;
use App\Repositories\Purchase\PurchaseRepository;
use App\Repositories\Daybook\DaybookRepository;
use App\Repositories\Balance\BalanceRepository;
use App\Repositories\nepalicalendar\nepali_date;
use App\Repositories\Vat\VatRepository;
use App\Repositories\Tds\TdsRepository;
use App\Repositories\Vendor\VendorRepository;
use App\Repositories\Client\ClientRepository;
use App\Repositories\Invoice\InvoiceRepository;
use App\Repositories\Setting\SettingRepository;
use App\Repositories\Received\ReceivedRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\OtherReceived\OtherReceivedRepository;
use App\Models\Received;
use App\Models\Payment;
use App\Models\Tds;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DaybookExport;

class ReportController extends Controller
{
	public function __construct(SalesRepository $sales,PurchaseRepository $purchase,DaybookRepository $daybook,BalanceRepository $balance,nepali_date $calendar,TdsRepository $tds,VatRepository $vat,VendorRepository $vendor,ClientRepository $client,InvoiceRepository $invoice,SettingRepository $setting,ReceivedRepository $received,PaymentRepository $payment,OtherReceivedRepository $other_received){
		$this->sales = $sales;
		$this->purchase = $purchase;
        $this->daybook=$daybook;
        $this->balance = $balance;
        $this->calendar = $calendar;
        $this->tds = $tds;
        $this->vat = $vat;
        $this->vendor = $vendor;
        $this->client = $client;
        $this->invoice=$invoice;
        $this->setting = $setting;
        $this->received = $received;
        $this->payment = $payment;
        $this->other_received = $other_received;
	}
    public function salesReport(){
    	$details = $this->sales->orderBy('created_at','desc')->get();
        // dd($details);
    	return view('admin.report.salesReport',compact('details'));
    }
    public function salesSearchByMonth(Request $request){
        $value = $request->value;
        $details = $this->sales->orderBy('created_at','desc')->whereMonth('vat_date',$value)->get();
        

        return view('admin.report.include.salesSearchByMonth',compact('details'));
    }
    public function customSalesSearch(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        $details = $this->sales->whereBetween('vat_date',[$request->start_date,$request->end_date])->get();
        return view('admin.report.include.salesSearchByMonth',compact('details'));
    }
    public function reportInvoiceView($id){
        $data=$this->invoice->find($id);
        $description=$data->invoiceDetail;
        $dashboard_setting=$this->setting->first();
        return view('admin.invoice.preview',compact('data','description','dashboard_setting'));
    }
    public function purchaseReport(){
    	$details=$this->purchase->orderBy('created_at','desc')->get();
    	return view('admin.report.purchaseReport',compact('details'));
    }
    public function customPurchaseReport(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        $details = $this->purchase->whereBetween('vat_date',[$request->start_date,$request->end_date])->get();
        return view('admin.report.include.customPurchaseSearch',compact('details','todaysNepaliDate'));
    }

    public function daybook(){
        $balances=$this->balance->orderBy('created_at','desc')->get();
        $daybook = $this->daybook->orderBy('created_at','desc')->get();
        return view('admin.report.daybook',compact('balances','daybook'));
    }
    public function customDaybookSearch(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        $daybook = $this->daybook->whereBetween('date',[$request->start_date,$request->end_date])->get();
        return view('admin.report.include.customDaybookSearch',compact('daybook','todaysNepaliDate'));
    }
    public function tdsToBeCollected(){
        $details=$this->tds->orderBy('date','desc')->where('tds_to_be_paid',0)->get();
        return view('admin.report.tdsToBeCollected',compact('details'));
    }
    public function tdsToBePaid(){
        $details=$this->tds->orderBy('date','desc')->where('tds_to_be_paid',1)->get();
        return view('admin.report.tdsToBePaid',compact('details'));
    }
    public function vatCollected(){
        $vats = $this->vat->orderBy('created_at','desc')->get();
        return view('admin.report.vatCollected',compact('vats'));
    }
    public function vatPaid(){
        $purchases = $this->purchase->orderBy('created_at','desc')->get();
        return view('admin.report.vatpaid',compact('purchases'));
    }
    public function vendorList(){
        $vendors = $this->vendor->orderBy('created_at','desc')->get();
        return view('admin.report.vendorList',compact('vendors'));
    }
    public function clientList(){
        $clients = $this->client->orderBy('created_at','desc')->get();
        return view('admin.report.clientList',compact('clients'));
    }
    public function receiptList(){
        $details=$this->received->orderBy('date','desc')->get();
        return view('admin.report.receiptList',compact('details'));
    }
    public function receiptSearchByMonth(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        
        $details=Received::whereYear('date',$nepali_date['year'])->whereMonth('date',$request->value)->orderBy('created_at','desc')->get();
        return view('admin.report.include.monthlyReceivedReport',compact('details'));
    }
    public function customReceiptSearch(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        $details = $this->received->whereBetween('date',[$request->start_date,$request->end_date])->get();
        
        return view('admin.report.include.receiptCustomSearch',compact('details'));
    }
    public function paymentList(){
        $details = $this->payment->orderBy('created_at','desc')->get();
        return view('admin.report.paymentList',compact('details'));
    }
    public function reportMonthlyPayment(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $details=Payment::whereYear('date',$nepali_date['year'])->whereMonth('date',$request->value)->orderBy('created_at','desc')->get();
        return view('admin.report.include.monthlyPaymentReport',compact('details'));
    }
    public function customPaymentSearch(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        $details = $this->payment->whereBetween('date',[$request->start_date,$request->end_date])->get();
        return view('admin.report.include.customPaymentSearch',compact('details','todaysNepaliDate'));
    }
    public function invoiceList(){
        $details = $this->invoice->orderBy('created_at','desc')->get();
        return view('admin.report.invoiceList',compact('details'));
    }
    public function customInvoiceSearch(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        $details = $this->invoice->whereBetween('nepali_date',[$request->start_date,$request->end_date])->get();
        return view('admin.report.include.customInvoiceSearch',compact('details','todaysNepaliDate'));
    }
    public function reportPrintInvoice(Request $request){
        $data=$this->invoice->find($request->value);
        $description=$data->invoiceDetail;
        $dashboard_setting=$this->setting->first();
        return view('admin.invoice.preview',compact('data','description','dashboard_setting'));
    }
    public function reportInvoiceSearchByMonth(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $details=$this->invoice->orderBy('created_at','desc')->whereYear('nepali_date',$nepali_date['year'])->whereMonth('nepali_date',$request->value)->get();
        return view('admin.report.include.customInvoiceSearch',compact('details'));
    }
    public function customCollectedVatSearch(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        $vats = $this->vat->whereBetween('vat_date',[$request->start_date,$request->end_date])->get();
        return view('admin.report.include.customCollectedVatSearch',compact('vats','todaysNepaliDate'));
    }
    public function monthlyVatCollected(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $vats=$this->vat->orderBy('created_at','desc')->whereYear('vat_date',$nepali_date['year'])->whereMonth('vat_date',$request->value)->get();
        return view('admin.report.include.customCollectedVatSearch',compact('vats','todaysNepaliDate'));
    }
    public function customVatPaidSearch(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        $purchases = $this->purchase->whereBetween('vat_date',[$request->start_date,$request->end_date])->get();
        return view('admin.report.include.customVatPaidSearch',compact('purchases','todaysNepaliDate'));
    }
    public function reportMonthlyVatPaid(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $purchases=$this->purchase->orderBy('created_at','desc')->whereYear('vat_date',$nepali_date['year'])->whereMonth('vat_date',$request->month)->get();
        
        return view('admin.report.include.customVatPaidSearch',compact('purchases','todaysNepaliDate'));
    }
    public function monthlyTdsToBeCollected(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $details=Tds::where('tds_to_be_paid',0)->whereYear('date',$nepali_date['year'])->whereMonth('date',$request->month)->orderBy('created_at','desc')->get();
        return view('admin.tds.include.monthlyTdsReport',compact('details'));
          
    }
    public function customTdsCollected(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        $details = Tds::where('tds_to_be_paid',0)->whereBetween('date',[$request->start_date,$request->end_date])->get();
        return view('admin.tds.include.monthlyTdsReport',compact('details'));
    }
    public function customTdsPaid(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        $details = Tds::where('tds_to_be_paid',1)->whereBetween('date',[$request->start_date,$request->end_date])->get();
        return view('admin.tds.include.monthlyTdsReport',compact('details'));
    }
    public function monthlyTdsToBePaid(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $details=Tds::where('tds_to_be_paid',0)->whereYear('date',$nepali_date['year'])->whereMonth('date',$request->month)->orderBy('created_at','desc')->get();
        return view('admin.tds.include.monthlyTdsReport',compact('details'));
          
    }
    public function profitAndLoss(){
        $invoices = [];
        $other_receiveds = [];
        $purchases = [];
        $payments = [];
        $months = ['baishak','jesth','asar','shrawan','bhadra','ashoj','kartik','mangsir','poush','magh','falgun','chaitra'];
        for($i=1;$i<13;$i++){
            if((strlen($i) == 2)){
              
               $value = $i;
               $invoice = $this->invoice->whereMonth('nepali_date',$value)->sum('grand_total');
               $other_received = $this->other_received->whereMonth('date',$value)->sum('amount');
               $purchase = $this->purchase->whereMonth('vat_date',$value)->sum('total');
               $payment = $this->payment->whereMonth('date',$value)->sum('amount');
               array_push($invoices,$invoice);
               array_push($other_receiveds,$other_received);
               array_push($purchases,$purchase);
               array_push($payments,$payment);
            }else{
              $value = "0".$i;
               $invoice = $this->invoice->whereMonth('nepali_date',$value)->sum('grand_total');
               $other_received = $this->other_received->whereMonth('date',$value)->sum('amount');
               $purchase = $this->purchase->whereMonth('vat_date',$value)->sum('total');
               $payment = $this->payment->whereMonth('date',$value)->sum('amount');
               array_push($invoices,$invoice);
               array_push($other_receiveds,$other_received);
               array_push($purchases,$purchase);
               array_push($payments,$payment);
            }
        }
        
        
        return view('admin.report.profitAndLoss',compact('invoices','purchases','other_receiveds','payments','months'));
    }
    public function profitAndLossByMonth(Request $request){
        dd('hello');
    }
    public function customProfitAndLoss(Request $request){
       
        $start_date = $request->start_date;
        $end_date = $request->end_date;
       $invoice = $this->invoice->whereBetween('nepali_date',[$request->start_date,$request->end_date])->sum('grand_total');
       $other_received = $this->other_received->whereBetween('date',[$request->start_date,$request->end_date])->sum('amount');
       $purchase = $this->purchase->whereBetween('vat_date',[$request->start_date,$request->end_date])->sum('total');
       $payment = $this->payment->whereBetween('date',[$request->start_date,$request->end_date])->sum('amount');
        $total = ($invoice+$other_received)-($purchase+$payment);
        return view('admin.report.include.customProfitAndLoss',compact('start_date','end_date','invoice','other_received','purchase','payment','total'));
    }
    public function monthlyPurchaseReport(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $details=$this->purchase->orderBy('created_at','desc')->whereYear('vat_date',$nepali_date['year'])->whereMonth('vat_date',$request->month)->get();
        return view('admin.report.include.customPurchaseSearch',compact('details','todaysNepaliDate'));
    }
    public function purchaseView(Request $request){
        $purchase = $this->purchase->findOrFail($request->value);
        return view('admin.report.include.purchaseView',compact('purchase'));
    }
    public function dayBookExport(Request $request){

    
        return Excel::download(new DaybookExport(), 'Daybook.xlsx');
    }
    


}
