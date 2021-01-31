<?php

use Illuminate\Database\Seeder;

class SettingSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $start_date=date('Y-m-d');
        $end_date=date('Y-m-d', strtotime($start_date. ' + 30 days'));
        DB::table('settings')->delete();
        $data=new App\Setting([
        	'organization_name'=>'',
        	'logo'=>'',
        	'phone_number'=>'',
            'email'=>'',
            'address'=>'',
            'website'=>'',
            'module'=>'officewebhouse',
            'start_date'=>$start_date,
            'end_date'=>$end_date,
        	]);
        $data->save();
    }
}
