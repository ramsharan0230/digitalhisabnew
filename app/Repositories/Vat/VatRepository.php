<?php
namespace App\Repositories\Vat;
use App\Repositories\Crud\CrudRepository;
use App\Models\Vat;
class VatRepository extends CrudRepository implements VatInterface{
	public function __construct(Vat $vat){
		$this->model=$vat;
	}
	public function create($data){
		$vat=$this->model->create($data);
		if($vat){
			return $vat;
		}
		return false;
	}
	public function update($data,$id){
		$this->model->find($id)->update($data);
	}
}