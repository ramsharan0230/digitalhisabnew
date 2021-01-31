<?php
namespace App\Repositories\OfficeBalance;
use App\Repositories\Crud\CrudInterface;
interface OfficeBalanceInterface extends CrudInterface{
	public function create($data);
	public function update($data,$id);
}