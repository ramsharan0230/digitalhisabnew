<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Dashboard\DashboardRepository;
use App\Repositories\Invoice\InvoiceRepository;
use App\Repositories\Tds\TdsRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Received\ReceivedRepository;
use App\Repositories\Vat\VatRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Purchase\PurchaseRepository;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function __construct(DashboardRepository $dashboard,InvoiceRepository $invoice,TdsRepository $tds,PaymentRepository $payment,ReceivedRepository $received,VatRepository $vat,UserRepository $user,PurchaseRepository $purchase){
        $this->dashboard=$dashboard;
        $this->invoice=$invoice;
        $this->tds=$tds;
        $this->received=$received;
        $this->payment=$payment;
    
        $this->vat=$vat;
        $this->user=$user;
        $this->purchase=$purchase;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices=$this->invoice->orderBy('date','desc')->take(5)->get();
        $receiveds=$this->received->orderBy('created_at','desc')->take(5)->get();
        
        
        $salesAndPurchaseVat = $this->salesAndPurchaseVat();
    
        $users=$this->user->where('role','staff')->get();
        $totalInvoice = $this->invoice->sum('grand_total');
        $totalReceived = $this->received->sum('amount');
        $amountToBeCollected=$totalInvoice-$totalReceived;
        $purchase=$this->purchase->sum('total');
        $totalPayment = $this->payment->sum('amount');
        $totalPurchasePaymentMade = $this->purchase->sum('total_amount_of_purchase_amount_paid');

        
        $purchaseItem=$this->purchase->orderBy('created_at','desc')->take(4)->get();
        return view('admin.dashboard',compact('invoices','receiveds','salesAndPurchaseVat','users','amountToBeCollected','purchaseItem','totalPayment','totalReceived','purchase','totalPurchasePaymentMade'));
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
        $this->validate($request,['meta_title'=>'max:250']);
        $data=$request->except('logo','fav_icon');
        if($request->fav_icon){
            $image=$request->file('fav_icon');
            $imageName = time().'.favicom'.$image->getClientOriginalExtension();

            $image->move(public_path('images/thumbnail'),$imageName);
            $data['fav_icon']=$imageName;
        }
        if($request->logo){
            $logo=$request->file('logo');
            $imageName = time().'.logo'.$logo->getClientOriginalExtension();
            $logo->move(public_path('images/thumbnail'),$imageName);
            $data['logo']=$imageName;
        }
        $this->dashboard->update($data,$id);
        return redirect()->back()->with('message','Dashboard Updated Successfully');
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
    public function salesAndPurchaseVat(){
        $salesVat = [];
        $purchaseVat= [];
        for($i=1;$i<=12;$i++){
            $details=$this->invoice->orderBy('created_at','desc')->whereMonth('nepali_date',$i)->sum('grand_total');
            array_push($salesVat,$details);
            $detail=$this->purchase->orderBy('created_at','desc')->whereMonth('vat_date',$i)->sum('total');
            array_push($purchaseVat,$detail);

        }
        return $data=['salesVat'=>$salesVat,'purchasevat'=>$purchaseVat];
    }
    public function salesAndPurchaseVatByYear(Request $request){
        $this->validate($request,['value'=>'required|numeric']);
        $salesVat = [];
        $purchaseVat= [];
        $year=$request->value;
        for($i=1;$i<=12;$i++){
            
            $details=$this->invoice->orderBy('created_at','desc')->whereYear('nepali_date',$year)->whereMonth('nepali_date',$i)->sum('grand_total');
            array_push($salesVat,$details);
            $detail=$this->vat->orderBy('created_at','desc')->where('type',null)->whereYear('vat_date',$year)->whereMonth('vat_date',$i)->sum('total');
            array_push($purchaseVat,$detail);

        }
        
        return $data=['salesVat'=>$salesVat,'purchasevat'=>$purchaseVat];
    }
}
