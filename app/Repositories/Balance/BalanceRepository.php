<?php
namespace App\Repositories\Balance;
use App\Models\Balance;
use App\Repositories\Crud\CrudRepository;
class BalanceRepository extends CrudRepository implements BalanceInterface{
	public function __construct(Balance $balance){
		$this->model=$balance;
	}
	public function create($data){
		$this->model->create($data);
	}
	public function update($data,$id){
		$this->model->find($id)->update($data);
	}
}