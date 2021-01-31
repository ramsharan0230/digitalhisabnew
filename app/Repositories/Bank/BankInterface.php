<?php
namespace App\Repositories\Bank;
use App\Repositories\Crud\CrudInterface;
interface BankInterface extends CrudInterface{
	public function create($data);
	public function update($data,$id);	
}