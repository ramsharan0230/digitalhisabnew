<?php
namespace App\Repositories\Paid;
use App\Models\Paid;
use App\Repositories\Crud\CrudRepository;
class PaidRepository extends CrudRepository implements PaidInterface{
	public function __construct(Paid $paid){
		$this->model=$paid;
	}
	public function create($data){
		$value=$this->model->create($data);
		if($value){
			return $value;
		}
	}
	
}