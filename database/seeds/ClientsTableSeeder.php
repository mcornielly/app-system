<?php

use Illuminate\Database\Seeder;
use App\Client;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	Client::create([
        	'name' => 'Administrador',
        	'type_document' => '----------',
        	'num_document' => '----------',
        	'address' => '----------',
        	'num_phone' => '----------',
        	'email' => 'adminy@----------.com',
        ]);

        Client::create([
        	'name' => 'Vendedor',
        	'type_document' => '----------',
        	'num_document' => '----------',
        	'address' => '----------',
        	'num_phone' => '----------',
        	'email' => 'vendedor@----------.com',
        ]);

        Client::create([
        	'name' => 'deposito',
        	'type_document' => '----------',
        	'num_document' => '----------',
        	'address' => '----------',
        	'num_phone' => '----------',
        	'email' => 'deposito@----------.com',
        ]);
    }
}
