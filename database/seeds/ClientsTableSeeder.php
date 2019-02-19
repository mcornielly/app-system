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
        	'name' => 'Miguel Angel Cornielly',
        	'type_document' => 'CI',
        	'num_document' => '11558731',
        	'address' => 'Santa Fe',
        	'num_phone' => '04142303956',
        	'email' => 'mcornielly@gmail.com',
        ]);

        Client::create([
        	'name' => 'Joshua Miguel Cornielly',
        	'type_document' => 'CI',
        	'num_document' => '27625512',
        	'address' => 'Santa Fe',
        	'num_phone' => '04149148169',
        	'email' => 'jcornielly@gmail.com',
        ]);

        Client::create([
        	'name' => 'Magdalena Martínez',
        	'type_document' => 'CI',
        	'num_document' => '11738218',
        	'address' => 'Santa Fe',
        	'num_phone' => '04242535718',
        	'email' => 'mmartinez@gmail.com',
        ]);
    }
}
