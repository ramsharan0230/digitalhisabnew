<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        $data = [
            [
                'name'=>'Digital office',
                'email'=>'info@user.com',
                'password'=>bcrypt('secret'),
                'publish'=>1,
                'role'=>'admin',
                'flag'=>1,
            ],
            [
                'name'=>'Be The Best',
                'email'=>'info@bethebest.com',
                'password'=>bcrypt('bethebest@1234'),
                'publish'=>1,
                'role'=>'admin',
                'flag'=>1,
            ]
            ];
        \App\User::insert($data);
    }
}
