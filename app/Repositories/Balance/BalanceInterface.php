<?php
namespace App\Repositories\Balance;
use App\Repositories\Crud\CrudInterface;
interface BalanceInterface extends CrudInterface{
	public function create($data);
	public function update($data,$id);
}