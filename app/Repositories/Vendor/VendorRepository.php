<?php
namespace App\Repositories\Vendor;
use App\Repositories\Crud\CrudRepository;
use App\Models\Vendor;
class VendorRepository extends CrudRepository implements VendorInterface{
	public function __construct(Vendor $vendor){
		$this->model=$vendor;
	}
	public function create($data){
		$this->model->create($data);
	}
	public function update($data,$id){
		$this->model->find($id)->update($data);
	}
}