<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Tds\TdsRepository;
use App\Repositories\nepalicalendar\nepali_date;
use App\Models\Tds;

class TdsController extends Controller
{
    public function __construct(TdsRepository $tds,nepali_date $calendar){
        $this->tds=$tds;
        $this->calendar=$calendar;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $details=$this->tds->orderBy('date','desc')->where('tds_to_be_paid',0)->get();
        return view('admin.tds.list',compact('details'));
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
    public function monthlyTdsReport(Request $request){
        $nepali_date=$this->calendar->eng_to_nep(date('Y'),date('m'),date('d'));
            
        
        if($request->Tdstobepaid==1){
            $details=Tds::whereYear('date',$nepali_date['year'])->whereMonth('date',$request->value)->orderBy('created_at','desc')->where('tds_to_be_paid',1)->get();
        }else{
            $details=Tds::whereYear('date',$nepali_date['year'])->whereMonth('date',$request->value)->orderBy('created_at','desc')->get();
        }
        
        return view('admin.tds.include.monthlyTdsReport',compact('details'));
    }
    public function tdsToBePaid(){
        $details=$this->tds->orderBy('date','desc')->where('tds_to_be_paid',1)->get();
        return view('admin.tds.tdsToBePaid',compact('details'));
    }
}
