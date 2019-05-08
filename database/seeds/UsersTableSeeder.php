<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
        	'id' => 1,
        	'role_id' => 1,
        	'user_name' => 'admin',
        	'password' => bcrypt('123456'),
        ]);

        User::create([
        	'id' => 2,
        	'role_id' => 2,
        	'user_name' => 'vendedor',
        	'password' => bcrypt('123456'),
        ]);

        User::create([
        	'id' => 3,
        	'role_id' => 3,
        	'user_name' => 'deposito',
        	'password' => bcrypt('123456'),
        ]);
    }
}
