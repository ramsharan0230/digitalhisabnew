<?php
namespace App\Repositories\Received;
use App\Repositories\Crud\CrudInterface;
interface ReceivedInterface extends CrudInterface{
	public function create($data);	
}