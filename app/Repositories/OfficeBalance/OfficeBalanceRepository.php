<?php
namespace App\Repositories\OfficeBalance;
use App\Repositories\Crud\CrudRepository;
use App\Models\OfficeBalance;
class OfficeBalanceRepository extends CrudRepository implements OfficeBalanceInterface{
	public function __construct(OfficeBalance $office_balance){
		$this->model=$office_balance;
	}
	public function create($data){
		$this->model->create($data);
	}
	public function update($data,$id){
		$this->model->find($id)->update($data);
	}
}