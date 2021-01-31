<?php
namespace App\Repositories\PaymentGateway;
use App\Repositories\Crud\CrudInterface;
interface PaymentGatewayInterface extends CrudInterface{
	public function create($data);
	public function update($data,$id);	
}