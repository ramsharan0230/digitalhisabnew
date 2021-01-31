<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Setting\SettingRepository;
use Auth;
use DB;

class SettingController extends Controller
{
    public function __construct(SettingRepository $setting){
        $this->setting=$setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $detail=$this->setting->first();
        return view('admin.setting.create',compact('detail'));
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
        $this->validate($request,['email'=>'email','email_to_collect_invoice'=>'email']);
        $data=$request->except('publish');
        $data['display_sales_without_vat']=is_null($request->display_sales_without_vat)?0:1;
        if($request->logo){
            $documents=$request->file('logo');
            $filename=time().'.'.$documents->getClientOriginalName();
            $documents->move(public_path('images'),$filename);   
            $data['logo']=$filename;
            
        }
        if($request->invoice_logo){
            $documents=$request->file('invoice_logo');
            $filename=time().'.invoice'.$documents->getClientOriginalName();
            $documents->move(public_path('images'),$filename);   
            $data['invoice_logo']=$filename;
            
        }
        $this->setting->update($data,$id);
        return redirect()->back()->with('message','Setting Updated Successfully');
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
    public function changePassword(){
        return view('admin.setting.changePassword');
    }
    public function savePassword(Request $request){
        $this->validate($request,['password'=>'required|min:6']);
        $user=Auth::user();
        if($request->password){
            $user->password=bcrypt($request->password);
            $user->save();
        }
        
        return redirect()->back()->with('message','Password Updated Successfully');

        
    }
    public function changeSetting(Request $request){

        if($request->headers->has('Module')){

            $header=$request->header('Module');
            
            $decoded_value=base64_decode($header);

            $setting=$this->setting->first();
                
            if($setting->module==$decoded_value){
                
                if($setting->end_date<date('Y-m-d')){
                    $today=date('Y-m-d');
                    $add='+'.$request->time .'month';
                    $new_end_date=date('Y-m-d', strtotime($today. $add));
                    $setting->end_date=$new_end_date;
                    $setting->save();
                    return response()->json(['message'=>'setting changes','setting'=>$setting]);
                }if($setting->end_date>date('Y-m-d')){
                    $add='+'.$request->time .'month';
                    $new_end_date=date('Y-m-d', strtotime($setting->end_date. $add));
                    $setting->end_date=$new_end_date;
                    $setting->save();
                    return response()->json(['message'=>'setting changes','setting'=>$setting]);
                }
            }
        }else{
            return response()->json([ 'message' => 'something went wrong']);
        }
    }
    public function viewSetting(Request $request){
        if($request->headers->has('Module')){
            $header=$request->header('Module');
            $decoded_value=base64_decode($header);
            $setting=$this->setting->first();
            if($setting->module==$decoded_value){
                return response()->json(['setting'=>$setting,'message'=>'success']);
            }else{
                return response()->json([ 'message' => 'something went wrong']);
            }
        }else{
            return response()->json([ 'message' => 'something went wrong']);
        }
    }
    public function sortMenu(Request $request){
            $sections=DB::table('menu_orders')->orderBy('order','ASC')->get();
            $itemID=$request->itemID;
            $itemIndex=$request->itemIndex;
            foreach($sections as $value){
                return DB::table('menu_orders')->where('id','=',$itemID)->update(array('order'=>$itemIndex));
            }
    }

}
