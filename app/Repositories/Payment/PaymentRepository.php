<?php
namespace App\Repositories\Payment;
use App\Models\Payment;
use App\Repositories\Crud\CrudRepository;
class PaymentRepository extends CrudRepository implements PaymentInterface{
	public function __construct(Payment $payment){
		$this->model=$payment;
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