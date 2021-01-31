<?php
namespace App\Repositories\Client;
use App\Models\Client;
use App\Repositories\Crud\CrudRepository;
class ClientRepository extends CrudRepository implements ClientInterface{
	public function __construct(Client $client){
		$this->model=$client;
	}
	public function create($data){
		$this->model->create($data);
	}
	public function update($data,$id){
		$this->model->find($id)->update($data);
	}
}