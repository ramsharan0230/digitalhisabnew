<?php
namespace App\Repositories\Received;
use App\Models\Received;
use App\Repositories\Crud\CrudRepository;
class ReceivedRepository extends CrudRepository implements ReceivedInterface{
	public function __construct(Received $received){
		$this->model=$received;
	}
	public function create($data){
		$value = $this->model->create($data);
		if($value){
			return $value;
		}
	}
}