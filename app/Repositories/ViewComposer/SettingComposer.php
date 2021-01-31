<?php
namespace App\Repositories\ViewComposer;
use Illuminate\View\View;
use App\Repositories\Setting\SettingRepository;
use App\Repositories\Bank\BankRepository;
use Auth;

class SettingComposer {
	public function __construct(SettingRepository $setting,BankRepository $bank) {
		$this->setting=$setting;
		$this->bank=$bank;
	}
	public function compose(View $view) {
		$setting=$this->setting->first();
		$bank=$this->bank->orderBy('created_at','desc')->get();
		$view->with(['dashboard_setting'=>$setting,'dashboard_bank'=>$bank]);
		
	}
	
}
