<?php
namespace App\Repositories\Daybook;
use App\Models\Daybook;
use App\Repositories\Crud\CrudRepository;
class DaybookRepository extends CrudRepository implements DaybookInterface{
	public function __construct(Daybook $daybook){
		$this->model=$daybook;
	}
	public function create($data){
		$this->model->create($data);
	}
	public function update($data,$id){
		$this->model->find($id)->update($data);
	}
}