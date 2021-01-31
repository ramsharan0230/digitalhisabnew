<?php
namespace App\Repositories\PaymentGateway;
use App\Models\PaymentGateway;
use App\Repositories\Crud\CrudRepository;
class PaymentGatewayRepository extends CrudRepository implements PaymentGatewayInterface{
	public function __construct(PaymentGateway $gateway){
		$this->model=$gateway;
	}
	public function create($data){
		$this->model->create($data);
	}
	public function update($data,$id){
		$this->model->find($id)->update($data);
	}
}