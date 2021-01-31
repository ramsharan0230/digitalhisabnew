<?php
namespace App\Repositories\Tds;
use App\Models\Tds;
use App\Repositories\Crud\CrudRepository;
class TdsRepository extends CrudRepository implements TdsInterface{
	public function __construct(Tds $tds){
		$this->model=$tds;
	}
	public function create($data){
		$this->model->create($data);
	}
	
}