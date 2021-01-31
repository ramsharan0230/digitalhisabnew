<?php
namespace App\Repositories\Paid;
use App\Repositories\Crud\CrudInterface;
interface PaidInterface extends CrudInterface{
	public function create($data);
}