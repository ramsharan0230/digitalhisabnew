<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;
use App\Repositories\Setting\SettingRepository;
use Mail;
use App\Password;
use DB;

class PasswordResetController extends Controller
{
	private $user;
	public function __construct(UserRepository $user,SettingRepository $setting){
		$this->user=$user;
		$this->setting = $setting;
	}
    public function resetForm(){
    	return view('admin.sendLink');
    }
    public function sendEmailLink(Request $request){
    	$this->validate($request,['email'=>'required']);
    	$details=$this->user->where('email',$request->email)->first();
    	$setting = $this->setting->first();
    	if($setting->email!=null){
			if($details){
		        $randomNumber= str_random(10);
		        
				$token_withSlash = bcrypt($randomNumber);

				$token = str_replace ('/', '', $token_withSlash);
		        // saving token and user name
				$savedata=['email'=>$request->email,'token'=>$token,'created_at'=>\Carbon\Carbon::now()->toDateTimeString()];
		    	Password::insert($savedata);
		        //sending email link
				$data=['email'=>$request->email,'token'=>$token,'from'=>$setting->email];
		    	Mail::send('email.emailLinkTemplate', $data,function ($message) use ($data) {
		            $message->to($data['email'])->from($data['from']);
		            $message->subject('Password Resent link');
		    });
				return redirect()->route('login')->with('message','Email has been sent to your email');
			}else{
				return redirect()->route('login')->with('message','Email does not exist');
			}
    	}else{
    		return redirect()->route('login')->with('message','Sending email is not set by admin');
    	}
    	

    }
    public function passwordResetForm(Request $request,$token){
        if(isset($token) && $token!=""){
            $data=DB::table('password_resets')->where('token',$token)->first();
            if($data){
                return view('admin.passwordReset',compact('data'));
            }else{  
                echo "token is wrong";
            }
        }else{
            echo "token not found";
        }
    	
    }
    public function updatePassword(Request $request){

        $details=$this->user->where('email',$request->email)->first();
        $value=$request->all();
        if($request->password){
            $value['password']=bcrypt($request->password);
        }
        $this->user->update($value,$details->id);
        if($details->role=='customer'){
            return redirect()->route('signin')->with('message',"Password has been changed");
        }
        return redirect()->route('login')->with('message',"Password has been changed");

    }
}
