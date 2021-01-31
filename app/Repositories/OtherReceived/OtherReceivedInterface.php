<?php
namespace App\Repositories\OtherReceived;
use App\Repositories\Crud\CrudInterface;
interface OtherReceivedInterface extends CrudInterface{
	public function create($data);
	public function update($data,$id);
}