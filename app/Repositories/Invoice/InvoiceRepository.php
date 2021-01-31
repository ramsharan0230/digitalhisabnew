<?php
namespace App\Repositories\Invoice;
use App\Repositories\Crud\CrudRepository;
use App\Models\InvoiceDetail;
use App\Models\Invoice;
class InvoiceRepository extends CrudRepository implements InvoiceInterface{
	public function __construct(Invoice $invoice,InvoiceDetail $details){
		$this->model=$invoice;
		$this->details=$details;
	}
	public function create($data){
	
		$result=$this->model->create($data);
		if($result){
			return $result->id;
		}
		return false;
	}
	public function saveInvoiceDetails($data){
		$this->details->create($data);
	}
	public function deleteInvoiceDetail($id){
		$this->details->where('invoice_id',$id)->delete();
	}
	public function update($data,$id){
		$this->model->find($id)->update($data);
	}
}
