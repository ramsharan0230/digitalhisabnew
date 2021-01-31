<?php
namespace App\Repositories\Sales;
use App\Repositories\Crud\CrudRepository;
use App\Models\Sales;
class SalesRepository extends CrudRepository implements SalesInterface{
	public function __construct(Sales $sales){
		$this->model=$sales;
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