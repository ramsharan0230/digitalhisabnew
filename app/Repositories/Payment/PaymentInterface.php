<?php
namespace App\Repositories\Payment;
use App\Repositories\Crud\CrudInterface;
interface PaymentInterface extends CrudInterface{
	public function create($data);
	public function update($data,$id);
}