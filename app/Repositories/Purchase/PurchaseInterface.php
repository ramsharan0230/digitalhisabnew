<?php
namespace App\Repositories\Purchase;
use App\Repositories\Crud\CrudInterface;
interface PurchaseInterface extends CrudInterface{
	public function create($data);
	public function update($data,$id);
}