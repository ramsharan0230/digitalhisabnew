<?php
namespace App\Repositories\InvoicePayment;
use App\Models\InvoicePayment;
use App\Repositories\Crud\CrudRepository;
class InvoicePaymentRepository extends CrudRepository implements InvoicePaymentInterface{
	public function __construct(InvoicePayment $invoice_payment){
		$this->model=$invoice_payment;
	}
	public function create($data){
		$this->model->create($data);
	}
}