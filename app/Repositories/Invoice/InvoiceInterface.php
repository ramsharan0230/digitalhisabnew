<?php
namespace App\Repositories\Invoice;
use App\Repositories\Crud\CrudInterface;
interface InvoiceInterface extends CrudInterface{
	public function create($data);
	public function saveInvoiceDetails($data);
	public function deleteInvoiceDetail($id);
	public function update($data,$id);
}