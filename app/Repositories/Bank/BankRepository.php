<?php
namespace App\Repositories\Bank;
use App\Models\Bank;
use App\Repositories\Crud\CrudRepository;
class BankRepository extends CrudRepository implements BankInterface{
	public function __construct(Bank $bank){
		$this->model=$bank;
	}
	public function create($data){
		$this->model->create($data);
	}
	public function update($data,$id){
		$this->model->find($id)->update($data);
	}
}