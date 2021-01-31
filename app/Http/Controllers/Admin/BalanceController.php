<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Bank\BankRepository;
use App\Repositories\PaymentGateway\PaymentGatewayRepository;
use App\Repositories\OfficeBalance\OfficeBalanceRepository;

class BalanceController extends Controller
{
    public function __construct(BankRepository $bank,PaymentGatewayRepository $gateway,OfficeBalanceRepository $office_balance){
        $this->bank=$bank;
        $this->gateway=$gateway;
        $this->office_balance=$office_balance;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks=$this->bank->orderBy('created_at','desc')->where('our_bank',1)->with('received','payments','purchasePayments')->get();
        $payment_gateways=$this->gateway->orderBy('created_at','desc')->with('receiveds','payments','purchasePayments')->get();
        $office_balance=$this->office_balance->orderBy('created_at','desc')->get();

        $officeBalance=\DB::table('office_balances')
        ->groupBy('type')
        ->select('type', \DB::raw("SUM(amount) as totalamount"))    
    
        ->orderBy('type', 'desc')
        ->get();

        

        
        return view('admin.balance.list',compact('banks','payment_gateways','officeBalance'));
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
}
