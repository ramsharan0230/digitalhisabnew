<?php
namespace App\Repositories\Daybook;
use App\Repositories\Crud\CrudInterface;
interface DaybookInterface extends CrudInterface{
	public function create($data);
	public function update($data,$id);	
}