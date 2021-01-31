<?php
namespace App\Repositories\Dashboard;
use App\Models\Dashboard;
use App\Repositories\Crud\CrudRepository;
class DashboardRepository extends CrudRepository implements DashboardInterface{
	public function __construct(Dashboard $dashboard){
		$this->model=$dashboard;
	}
	public function update($input,$id){
		$this->model->find($id)->update($input);
	}
}