<?php
namespace App\Repositories\Tds;
use App\Repositories\Crud\CrudInterface;
interface TdsInterface extends CrudInterface{
	public function create($data);
}