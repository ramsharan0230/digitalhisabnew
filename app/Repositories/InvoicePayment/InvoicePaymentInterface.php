<?php
namespace App\Repositories\InvoicePayment;
use App\Repositories\Crud\CrudInterface;
interface InvoicePaymentInterface extends CrudInterface{
	public function create($data);
}