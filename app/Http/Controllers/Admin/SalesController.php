<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Sales\SalesRepository;
use App\Repositories\Purchase\PurchaseRepository;
use App\Repositories\Invoice\InvoiceRepository;
use App\Repositories\Setting\SettingRepository;
use App\Models\Invoice;

class SalesController extends Controller
{
    public function __construct(SalesRepository $sales,PurchaseRepository $purchase,InvoiceRepository $invoice,SettingRepository $setting){
        $this->sales=$sales;
        $this->purchase=$purchase;
        $this->invoice=$invoice;
        $this->setting=$setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd($this->sales->orderBy('created_at', 'desc')->get());
        $details = $this->sales->orderBy('created_at','desc')->get();
        
        return view('admin.sales.list',compact('details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function salesWithoutVat(){
        $details=Invoice::where('vat',0)->get();
        return view('admin.sales.salesWithoutVat',compact('details'));
    }
    public function searchSalesWithoutVat(Request $request){
        $details=Invoice::whereMonth('nepali_date',$request->value)->where('vat',0)->orderBy('created_at','desc')->get();
        return view('admin.sales.include.salesWithOutVat',compact('details'));
    }
    public function salesSearchByMonth(Request $request){
        $value = $request->value;
        $details = $this->sales->orderBy('created_at','desc')->whereMonth('vat_date',$value)->get();
        $total_sales = $details->sum('vat_paid');
        $sales_vat = $details->sum('vat_paid');
        $purchase = $this->purchase->whereMonth('vat_date',$request->value)->orderBy('created_at','desc')->get();
        $purchase_vat = $purchase->sum('vat_paid');

        return view('admin.sales.include.salesSearchByMonth',compact('details','total_sales','sales_vat','purchase','purchase_vat'));
    }
    public function toBeCollected(){
        $details = $this->invoice->orderBy('created_at','desc')->where('collected',0)->get();
        return view('admin.sales.toBeCollected',compact('details'));
    }
    public function salesView(Request $request){
        $sale = $this->sales->findOrFail($request->value);
        return view('admin.sales.saleView',compact('sale'));
    }
    public function invoiceView($id){
        $data=$this->invoice->find($id);
        $description=$data->invoiceDetail;
        $dashboard_setting=$this->setting->first();
        return view('admin.invoice.preview',compact('data','description','dashboard_setting'));
    }
    
}
