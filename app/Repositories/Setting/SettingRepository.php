<?php
namespace App\Repositories\Setting;
use App\Repositories\Crud\CrudRepository;
use App\Models\Setting;

class SettingRepository extends CrudRepository implements SettingInterface{
	public function __construct(Setting $setting){
		$this->model=$setting;
	}
	public function update($data,$id){
		$this->model->find($id)->update($data);
	}
}