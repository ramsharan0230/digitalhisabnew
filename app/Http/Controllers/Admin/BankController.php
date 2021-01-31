<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Bank\BankRepository;
use DB;

class BankController extends Controller
{
    public function __construct(BankRepository $bank){
        $this->bank=$bank;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $details=$this->bank->orderBy('created_at','desc')->with('received','payments','purchasePayments')->get();
        //dd($details);
        return view('admin.bank.list',compact('details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.bank.create');
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
            'name'=>'required|max:200',
            'branch'=>'required_if:our_bank,==,on',
            'account_number'=>'required_if:our_bank,==,on'
        ]);
        try{
            DB::beginTransaction();
            
            
            $data=$request->except('our_bank');
            $data['our_bank']=is_null($request->our_bank)?0:1;
            $this->bank->create($data);
            DB::commit();
            return redirect()->route('bank.index')->with('message','Bank Added Successfully');
        }catch(\Exception $e){
            DB::rollback();
            return redirect()->with('message','Something went wrong');
        }
        
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
        $detail=$this->bank->findOrFail($id);
        return view('admin.bank.edit',compact('detail'));
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
            'name'=>'required|max:200',
            'branch'=>'required|string|max:300',
            'account_number'=>'required|string|max:300'
        ]);
        try{
            DB::beginTransaction();
            
            $data=$request->except('our_bank');
            $data['our_bank']=is_null($request->our_bank)?0:1;

            $this->bank->update($data,$id);
            DB::commit();
            return redirect()->route('bank.index')->with('message','Bank Added Successfully');
        }catch(\Exception $e){
            DB::rollback();
            return redirect()->back()->with('message','Something Went Wrong');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->bank->destroy($id);
        return redirect()->back()->with('message','Bank deleted successfully');
    }
}
