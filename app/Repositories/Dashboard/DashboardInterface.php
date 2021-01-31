<?php
namespace App\Repositories\Dashboard;
use App\Repositories\Crud\CrudInterface;
interface DashboardInterface extends CrudInterface{
	public function update($input,$id);
}