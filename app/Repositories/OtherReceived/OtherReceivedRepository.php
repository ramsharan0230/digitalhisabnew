<?php
namespace App\Repositories\OtherReceived;
use App\Models\OtherReceived;
use App\Repositories\Crud\CrudRepository;
class OtherReceivedRepository extends CrudRepository implements OtherReceivedInterface{
	public function __construct(OtherReceived $other_received){
		$this->model=$other_received;
	}
	public function create($data){
		$value = $this->model->create($data);
		if($value){
			return $value;
		}
	}
	public function update($data,$id){
		$this->model->find($id)->update($data);
	}
}