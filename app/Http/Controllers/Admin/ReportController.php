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
use App\Repositories\Paid\PaidRepository;
use App\Models\Received;
use App\Models\Payment;
use App\Models\Tds;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DaybookExport;

class ReportController extends Controller
{
    private $paids;
	public function __construct(PaidRepository $paids, SalesRepository $sales,PurchaseRepository $purchase,DaybookRepository $daybook,
    BalanceRepository $balance,nepali_date $calendar,TdsRepository $tds,VatRepository $vat,VendorRepository $vendor,
    ClientRepository $client,InvoiceRepository $invoice,SettingRepository $setting,ReceivedRepository $received,PaymentRepository $payment,OtherReceivedRepository $other_received){
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
        $this->paids = $paids;
	}

    public function getNepaliDate(){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        return $todaysNepaliDate;
    }

    public function salesReport(){
    	$details = $this->sales->orderBy('created_at','desc')->get();
    	return view('admin.report.salesReport',compact('details'));
    }

    public function salesReportPdf(Request $request){
        $details = $this->sales->orderBy('created_at','desc')->whereMonth('vat_date', $request->month)->whereYear('vat_date', $request->year)->get();
        $pdf = PDF::loadView('admin.report.salesReportPdf', compact('details'));
        return $pdf->stream('sales-report.pdf');
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

    public function purchaseReportPdf(Request $request){
        $details=$this->purchase->orderBy('created_at','desc')->get();

        $pdf = PDF::loadView('admin.purchase.reports.purchaseReportPdf', compact('details'));
        return $pdf->stream('purchase-report.pdf');
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
// dd(234);
        return view('admin.report.daybook',compact('balances','daybook'));
    }

    public function daybookPdf(Request $request){
        $balances=$this->balance->whereMonth('date', $request->month)->orderBy('created_at','desc')->get();
        $daybook = $this->daybook->whereMonth('date', $request->month)->orderBy('created_at','desc')->get();
        $pdf = PDF::loadView('pdf.daybookpdf', compact('balances', 'daybook'));
        return $pdf->stream('purchase-report.pdf');
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
        $todaysNepaliDate = $this->getNepaliDate();
        return view('admin.report.vatPaid',compact('purchases', 'todaysNepaliDate'));
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
        $todaysNepaliDate = $this->getNepaliDate();
        $details = $this->payment->orderBy('created_at','desc')->get();
        return view('admin.report.paymentList',compact('details', 'todaysNepaliDate'));
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
        $todaysNepaliDate = $this->getNepaliDate();
        $vats=$this->vat->orderBy('created_at','desc')->whereYear('vat_date', $request->year)->whereMonth('vat_date', $request->month)->get();
        
        return view('admin.report.include.customCollectedVatSearch',compact('vats','todaysNepaliDate'));
    }

    public function monthlyVatCollectedPDF(Request $request){
        $todaysNepaliDate = $this->getNepaliDate();
        $details=$this->vat->orderBy('created_at','desc')->whereYear('vat_date', $request->year)->whereMonth('vat_date', $request->month)->get();
        $year = $request->year; 
        $month = $request->month;       
        $pdf = PDF::loadView('admin.report.monthly-vat-collected-pdf', compact('details', 'year', 'month'));
        return $pdf->stream('monthly-vat-collected-report.pdf');
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
        $todaysNepaliDate = $this->getNepaliDate();
        return view('admin.report.include.customVatPaidSearch',compact('purchases','todaysNepaliDate'));
    }

    public function reportMonthlyVatPaidPDF(Request $request){
        $year = $request->year;
        $month = $request->month;
        $details=$this->purchase->orderBy('created_at','desc')->whereYear('vat_date',$year)->whereMonth('vat_date',$month)->get();
        $pdf = PDF::loadView('admin.report.monthlyVatPaidPdf', compact('details', 'year', 'month'));
        return $pdf->stream('monthly-vat-paid.pdf');
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

    public function vatInvoice($val){
        return $this->invoice->whereMonth('nepali_date',$val)->where('vat', '>', 0)->sum('grand_total');
    }

    public function nonVatInvoice($val){
        return $this->invoice->whereMonth('nepali_date',$val)->where('vat', '=', 0)->sum('grand_total');
    }

    public function invoiceByMonth($val){
        return $this->invoice->whereMonth('nepali_date', $val)->sum('grand_total');
    }

    public function otherReveivedNonVatInvoice($val){
        return $this->other_received->whereMonth('date',$val)->sum('amount');
    }

    public function purchaseNonVatInvoice($val){
        return $this->purchase->whereMonth('vat_date',$val)->where('not_vat', 1)->sum('total');
    }

    public function purchaseVatInvoice($val){
        return $this->purchase->whereMonth('vat_date',$val)->where('not_vat', 0)->sum('total');
    }

    public function vatPaids($val){
        $purchaseslist = [];
        $purchases = $this->purchase->whereMonth('vat_date',$val)->where('not_vat', 0)->get();
        foreach($purchases as $purchase){
            array_push($purchaseslist, $purchase->id);
        }

        return $this->paids->whereIn('purchase_id', $purchases)->sum('amount');
    }

    public function nonVatPaids($val){
        $purchaseslist = [];
        $purchases = $this->purchase->whereMonth('vat_date',$val)->where('not_vat', 1)->get();
        foreach($purchases as $purchase){
            array_push($purchaseslist, $purchase->id);
        }

        return $this->paids->whereIn('purchase_id', $purchaseslist)->sum('amount');
    }

    public function profitAndLoss(){
        $invoices = [];
        $vatInvoices = [];
        $nonVatInvoices = [];
        $other_receiveds = [];
        $other_receivedsNonInvoice = [];
        $nonVatPurchases = [];
        $purchases = [];
        $payments = [];
        $vatPaids = [];
        $nonVatPaids = [];
        $months = ['baishak','jesth','asar','shrawan','bhadra','ashoj','kartik','mangsir','poush','magh','falgun','chaitra'];
        for($i=1;$i<13;$i++){
            if((strlen($i) == 2)){
               $value = $i;
               $invoice = $this->invoiceByMonth($value);
               $vatInvoice = $this->vatInvoice($value);
               $vatPaid = $this->vatPaids($value);
               $nonVatPaid = $this->nonVatPaids($value);

               //   not vat
               $nonVatInvoice = $this->nonVatInvoice($value);
               $otherReceivedAmt = $this->otherReveivedNonVatInvoice($value);
               $nonVatPurchase = $this->purchaseNonVatInvoice($value);
               //   non vat end
               $other_received = $this->other_received->whereMonth('date',$value)->sum('amount');
               $purchase = $this->purchase->whereMonth('vat_date',$value)->sum('total');
               $payment = $this->payment->whereMonth('date',$value)->sum('amount');
               array_push($invoices,$invoice);
               array_push($other_receiveds,$other_received);
               array_push($purchases,$purchase);
               array_push($payments,$payment);
               array_push($vatInvoices, $vatInvoice);
               array_push($nonVatInvoices, $nonVatInvoice);
               array_push($nonVatPurchases, $nonVatPurchase);

               //nonVat Invoice
               array_push($other_receivedsNonInvoice, $otherReceivedAmt);
               array_push($vatPaids, $vatPaid);
               array_push($nonVatPaids, $nonVatPaid);
               
            }else{
               $value = "0".$i;
               $invoice = $this->invoiceByMonth($value);
            //    vat invoices
                $vatPaid = $this->vatPaids($value);
                $vatInvoice = $this->vatInvoice($value);
                $nonVatPaid = $this->nonVatPaids($value);
            // end

            //   not vat
                $nonVatInvoice = $this->nonVatInvoice($value);
                $nonVatPurchase = $this->purchaseNonVatInvoice($value);
            //   non vat end
               $other_received = $this->other_received->whereMonth('date',$value)->sum('amount');
               $purchase = $this->purchase->whereMonth('vat_date',$value)->sum('total');
               $payment = $this->payment->whereMonth('date',$value)->sum('amount');
               array_push($invoices, $invoice);
               array_push($other_receiveds,$other_received);
               array_push($purchases,$purchase);
               array_push($payments,$payment);
               array_push($nonVatInvoices, $nonVatInvoice);
               array_push($vatInvoices, $vatInvoice);
               array_push($nonVatPurchases, $nonVatPurchase);
               array_push($vatPaids, $vatPaid);
               array_push($nonVatPaids, $nonVatPaid);
            }
        }
        
        return view('admin.report.profitAndLoss',compact('invoices', 'nonVatPaids', 'vatPaids','purchases', 'nonVatPurchases', 'other_receiveds','payments','months', 'vatInvoices', 'nonVatInvoices', 'other_receivedsNonInvoice'));
    }
    public function profitAndLossByMonth(Request $request){
        $months = ['01'=>'baishak', '02'=>'jesth', '03'=>'asar', '04'=>'shrawan', '05'=>'bhadra', '06'=>'ashoj', '07'=>'kartik',
        '08'=>'mangsir', '09'=>'poush', '10'=>'magh', '11'=>'falgun', '12'=>'chaitra'];

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $invoice = $this->invoice->whereMonth('nepali_date',$request->value)->sum('grand_total');
        $other_received = $this->other_received->whereMonth('date',$request->value)->sum('amount');
        $purchase = $this->purchase->whereMonth('vat_date',$request->value)->sum('total');
        $payment = $this->payment->whereMonth('date',$request->value)->sum('amount');
        $total = ($invoice+$other_received)-($purchase+$payment);
        $monthValue = $request->value;
        // dd($invoice, $other_received, $purchase, $payment, $total);
        return view('admin.report.include.customProfitAndLoss',compact('months', 'monthValue', 'end_date', 'invoice','other_received','purchase','payment','total'));
    }

    public function profitAndLossPdf(Request $request){
        $months = ['01'=>'baishak', '02'=>'jesth', '03'=>'asar', '04'=>'shrawan', '05'=>'bhadra', '06'=>'ashoj', '07'=>'kartik',
        '08'=>'mangsir', '09'=>'poush', '10'=>'magh', '11'=>'falgun', '12'=>'chaitra'];
        $month = $request->month;
        $invoice = $this->invoice->whereMonth('nepali_date',$month)->sum('grand_total');
        $other_received = $this->other_received->whereMonth('date',$month)->sum('amount');
        $purchase = $this->purchase->whereMonth('vat_date',$month)->sum('total');
        $payment = $this->payment->whereMonth('date',$month)->sum('amount');
        $total = ($invoice+$other_received)-($purchase+$payment);

        $pdf = PDF::loadView('pdf.profit-and-loss-pdf', compact('month', 'months', 'invoice','other_received','purchase','payment','total'));
        return $pdf->stream('profit-and-loss.pdf');
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
        $details=$this->purchase->orderBy('created_at','desc')->whereYear('vat_date', $request->year)->whereMonth('vat_date', $request->month)->get();
        return view('admin.report.include.customPurchaseSearch',compact('details'));
    }
    public function purchaseView(Request $request){
        $purchase = $this->purchase->findOrFail($request->value);
        return view('admin.report.include.purchaseView',compact('purchase'));
    }
    public function dayBookExport(Request $request){
        $year = $request->year;
        $month = $request->month;
        return Excel::download(new DaybookExport($month, $year), 'Daybook.xlsx');
    }
    //Annual Reports
    public function annualBookExport(Request $request){
        $value = $request->year;
        $details = $this->sales->orderBy('created_at','desc')->whereYear('vat_date', $value)->get();
        $pdf = PDF::loadView('pdf.annual-report', compact('details'));
        return $pdf->stream('annual-invoice.pdf');
    }

    //Annual Reports
    public function annualSalesExport(Request $request){
        // dd($request->all());
        $value = $request->year;
        $details = $this->sales->orderBy('created_at','desc')->whereYear('vat_date',$value)->get();
        $pdf = PDF::loadView('pdf.annual-report', compact('details'));
        return $pdf->stream('annual-invoice.pdf');
    }

    //Receipt list Reports
    public function receiptListExport(Request $request){
        $value = $request->year;
        $details = $this->received->orderBy('date','desc')->whereYear('date',$value)->get();
        $pdf = PDF::loadView('pdf.receipt-list', compact('details'));
        return $pdf->stream('receipt-list.pdf');
    }

    public function  receiptListMonthExport(Request $request){
        $year = $request->year;
        $month = $request->month;
        $details = $this->received->orderBy('date','desc')->whereYear('date',$year)->whereMonth('date',$month)->get();
        $pdf = PDF::loadView('pdf.receipt-list', compact('details'));
        return $pdf->stream('receipt-list.pdf');
    }

    //Invoice Reports
    public function invoiceListExport(Request $request){
        $value = $request->year;
        // dd($this->invoice->orderBy('created_at','desc')->whereYear('date', '=', $value)->get());
        $details = $this->invoice->orderBy('created_at','desc')->get();
        // dd($details);/
        $pdf = PDF::loadView('pdf.invoice', compact('details'));
        return $pdf->stream('invoice-report-list.pdf');
    }

    //Invoice Reports
    public function monthInvoiceListExport(Request $request){
        $year = $request->year;
        $month = $request->month;
        // dd($this->invoice->orderBy('created_at','desc')->whereYear('date', '=', $value)->get());
        $details = $this->invoice->whereMonth('nepali_date', $month)->whereYear('nepali_date', $year)->orderBy('created_at','desc')->get();
        $pdf = PDF::loadView('pdf.invoice', compact('details'));
        return $pdf->stream('invoice-report-list.pdf');
    }

    //tdsToBeCollected Reports
    public function tdsToBeCollectedExport(Request $request){
        // dd($request->all());
        $details=$this->tds->where('tds_to_be_paid',0)->whereYear('date', '=', $request->year)
        ->whereMonth('date', '=', $request->month)->orderBy('date','desc')->get();

        $pdf = PDF::loadView('pdf.tdsToBeCollected_pdf', compact('details'));
        return $pdf->stream('tdsToBeCollected_pdf.pdf');
    }

    public function salesSearchByYear(Request $request){
        $value = $request->value;
        $details = $this->sales->orderBy('created_at','desc')->whereYear('vat_date', $value)->get();
        return view('admin.report.include.salesSearchByYear',compact('details'));
    }

    public function salesSearchByDates(Request $request){
        $details = $this->sales->whereBetween('vat_date',[$request->start_date,$request->end_date])->get();
        return view('admin.report.include.salesSearchByMonth',compact('details'));
    }


}
