<?php
namespace App\Repositories\Vat;
use App\Repositories\Crud\CrudInterface;
interface VatInterface extends CrudInterface{
	public function create($data);
	public function update($data,$id);
}