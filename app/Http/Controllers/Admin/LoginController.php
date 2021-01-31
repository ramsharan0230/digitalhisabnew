<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use DB;
use Artisan;
class LoginController extends Controller
{
    public function login(){
        
        return view('admin.login');
    }
    public function postLogin(Request $request){
        $this->validate($request, [
            'email'    => 'required',
            'password' => 'required',
        ]); 
        $result=User::where('email',$request->email)->first();
        if($result){
            if(!\Hash::check($request->password, $result->password)){
                return back()->with('message','Invalid Username\Password');
            }elseif($result->role!='admin' && $result->publish==0){
                return back()->with('message',"Your account is inactive!<br> Please contact  Team.");
            }else{
                if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
                           return redirect()->route('dashboard.index');
                       } else {
                           return back()->withInput()->withErrors(['email'=>'something is wrong!']);
                       }
            }
        }
        return back()->with('message','Invalid Username\Password');
    }
    public function Logout(){
        Auth::logout();
        return redirect()->route('login');
    }
    
}
