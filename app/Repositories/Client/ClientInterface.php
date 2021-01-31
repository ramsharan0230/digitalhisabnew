<?php
namespace App\Repositories\Client;
use App\Repositories\Crud\CrudInterface;
interface ClientInterface extends CrudInterface{
	public function create($data);
	public function update($data,$id);
}