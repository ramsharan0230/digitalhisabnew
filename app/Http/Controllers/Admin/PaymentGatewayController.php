<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\PaymentGateway\PaymentGatewayRepository;

class PaymentGatewayController extends Controller
{
    public function __construct(PaymentGatewayRepository $gateway){
        $this->gateway=$gateway;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $details=$this->gateway->orderBy('created_at','desc')->with('receiveds','payments','purchasePayments')->get();
        
        return view('admin.paymentGateway.list',compact('details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.paymentGateway.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,['name'=>'required']);
        $data=$request->except('publish');
        $data['publish']=is_null($request->publish)?0:1;
        $this->gateway->create($data);
        return redirect()->route('payment-gateway.index')->with('message','PaymentGateway Added Successfully');

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
        $detail=$this->gateway->findOrFail($id);
        return view('admin.paymentGateway.edit',compact('detail'));
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
        $this->validate($request,['name'=>'required']);
        $data=$request->except('publish');
        $data['publish']=is_null($request->publish)?0:1;
        $this->gateway->update($data,$id);
        return redirect()->route('payment-gateway.index')->with('message','PaymentGateway Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->gateway->destroy($id);
        return redirect()->back()->with('message','PaymentGateway Updated Successfully');

    }
}
