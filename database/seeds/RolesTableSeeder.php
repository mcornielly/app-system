<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Role::create([
       		'name' => 'Administrador',
       		'description' => 'Administrator de la Aplicación' 	
       ]);

       Role::create([
       		'name' => 'Vendedor',
       		'description' => 'Vendedor de la Empresa' 	
       ]); 

       Role::create([
       		'name' => 'Alamacen',
       		'description' => 'Encargado del Almacen' 	
       ]);   
    }
}
