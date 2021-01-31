<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Vendor\VendorRepository;
use App\Repositories\nepalicalendar\nepali_date;
use App\Repositories\Purchase\PurchaseRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VendorTransactionExport;
use App\Repositories\Setting\SettingRepository;

class VendorController extends Controller
{
    public function __construct(VendorRepository $vendor,PurchaseRepository $purchase,nepali_date $calendar,SettingRepository $setting){
        $this->vendor=$vendor;
        $this->purchase=$purchase;
        $this->calendar=$calendar;
        $this->setting=$setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $details = $this->vendor->orderBy('created_at','desc')->get();
        return view('admin.vendor.list',compact('details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.vendor.create');
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
            'email'=>'required|email|unique:vendors',
            'phone'=>'required|numeric',
            'address'=>'required|max:200',
            'vat_no'=>'required|string',
            'contact_person'=>'required|string|max:200'
        ]);
        $this->vendor->create($request->all());
        return redirect()->route('vendor.index')->with('message','Vendor Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail=$this->vendor->findOrFail($id);
        return view('admin.vendor.vendorView',compact('detail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $detail = $this->vendor->findOrFail($id);
        return view('admin.vendor.edit',compact('detail'));
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
            'email'=>'required|email|unique:vendors,email,'.$id,
            'phone'=>'required|numeric',
            'address'=>'required|max:200',
            'vat_no'=>'required|string',
            'contact_person'=>'required|string|max:200',
            
        ]);
        $this->vendor->update($request->all(),$id);
        return redirect()->route('vendor.index')->with('message','Vendor Deleted Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->vendor->destroy($id);
        return redirect()->back()->with('message','Vendor Deleted Successfully');
    }
    public function transactionView($id){
        $purchase = $this->purchase->findOrFail($id);
        $purchasePayments = $purchase->purchasePayments;
        return view('admin.vendor.viewTransaction',compact('purchase','purchasePayments'));

    }
    public function searchMonthlyVendorBusinessDetail(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        
        $purchases = $this->purchase->whereMonth('vat_date',$request->month)->whereYear('vat_date',$nepali_date['year'])->get();
        
        return view('admin.vendor.monthlyView',compact('purchases'));
    }
    public function vendorTransactionExport(){
        
        if($request->type==1){
            return Excel::download(new SalesVatExport($request->month), 'vat.xlsx');
        }else{
            return Excel::download(new VatExport($request->month), 'vat.xlsx');
        }
    }
    public function exportVendorTransaction(Request $request){
        $vendor= $this->vendor->findOrFail($request->id);
        return Excel::download(new VendorTransactionExport($vendor->id), 'Vendor Transaction.xlsx');

        
        
    }
    public function vendorInvoicePreview($id){
        $data=$this->invoice->find($id);
        $description=$data->invoiceDetail;
        $dashboard_setting=$this->setting->first();
        return view('admin.invoice.preview',compact('data','description','dashboard_setting'));
    }
    public function customVendorSearch(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $todaysNepaliDate=$nepali_date['year'].'-'.((strlen($nepali_date['month']) == 2) ? $nepali_date['month'] : "0".$nepali_date['month']).'-'.((strlen($nepali_date['date']) == 2) ? $nepali_date['date'] : "0".$nepali_date['date']);
        $vendor = $this->vendor->findOrFail($request->vendor_id);

        $purchases = $vendor->purchases()->whereBetween('vat_date',[$request->start_date,$request->end_date])->get();
        return view('admin.vendor.monthlyView',compact('purchases'));
    }
}
