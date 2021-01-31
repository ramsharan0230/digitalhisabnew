<?php
namespace App\Repositories\Purchase;
use App\Models\Purchase;
use App\Repositories\Crud\CrudRepository;
class PurchaseRepository extends CrudRepository implements PurchaseInterface{
	public function __construct(Purchase $purchase){
		$this->model = $purchase;
	}
	public function create($data){
		$this->model->create($data);
	}
	public function update($data,$id){
		$this->model->find($id)->update($data);
	}
}