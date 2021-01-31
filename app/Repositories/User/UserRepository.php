<?php
namespace App\Repositories\User;
use App\User;
use App\Repositories\Crud\CrudRepository;
class UserRepository extends CrudRepository implements UserInterface{
	public function __construct(User $user){
		$this->model=$user;
	}
	public function create($input){
		
		$this->model->create($input);
	}
	public function update($input,$id){
		$this->model->find($id)->update($input);
	}
}