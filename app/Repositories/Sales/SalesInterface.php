<?php
namespace App\Repositories\Sales;
use App\Repositories\Crud\CrudInterface;
interface SalesInterface extends CrudInterface{
	public function create($data);
	public function update($data,$id);
}