<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Balance\BalanceRepository;
use App\Repositories\nepalicalendar\nepali_date;
use App\Repositories\Daybook\DaybookRepository;

use App\Models\Balance;

class DayBookController extends Controller
{
    public function __construct(BalanceRepository $balance,nepali_date $calendar,DaybookRepository $daybook){
        $this->balance=$balance;
        $this->calendar=$calendar;
        $this->daybook=$daybook;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $balances=$this->balance->orderBy('created_at','desc')->get();
        $daybook = $this->daybook->orderBy('created_at','desc')->get();
        return view('admin.daybook.daybook',compact('balances','daybook'));
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
    public function monthlyReport(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        $daybook = $this->daybook->whereMonth('date', $request->value)->orderBy('created_at','desc')->get();
        $balances=Balance::whereMonth('date',$request->value)->orderBy('created_at','desc')->get();
        $result = $daybook;
        return view('admin.daybook.include.daybookSearchResult',compact('balances', 'daybook', 'result'));
    }
    public function yearlyReport(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
        
        
        $balances=Balance::whereYear('date',$request->value)->orderBy('created_at','desc')->get();
        return view('admin.daybook.include.monthlyReport',compact('balances'));
    }
    public function searchDaybook(Request $request){
        $result = $this->daybook->where('collection_from', 'LIKE', "%{$request->value}%")
        ->orWhere('purchase_from', 'LIKE', "%{$request->value}%") 
        ->orWhere('purchase_from', 'LIKE', "%{$request->value}%") 
        ->orWhere('date', 'LIKE', "%{$request->value}%") 
        ->orWhere('payment_to', 'LIKE', "%{$request->value}%") 
        ->get(); 

        return view('admin.daybook.include.daybookSearchResult',compact('result'));
       

    }
}
