<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['guest']], function(){

	//Módulo de login de Usurios
	Route::get('/', 'Auth\LoginController@showLoginForm');
	Route::post('/login', 'Auth\LoginController@login')->name('login');

});



Route::group(['middleware' => ['Auth']], function(){

	Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

	Route::get('/main', function () {
	    return view('container/container');
	})->name('main');


	Route::group(['middleware' => ['storage']], function(){

		//Módulo de Categorias
		Route::resource('categorias', 			'CategoryController');
		Route::put('/categorias/actualizar', 	'CategoryController@update');
		Route::put('/categoria/desactivar', 	'CategoryController@disable');
		Route::put('/categoria/activar', 		'CategoryController@enable');
		Route::get('/categoria/seleccionar', 	'CategoryController@select_categories');

		//Módulo de Productos
		Route::resource('productos', 			'ProductController');
		Route::put('/producto/actualizar', 		'ProductController@update');
		Route::put('/producto/desactivar', 		'ProductController@disable');
		Route::put('/producto/activar', 		'ProductController@enable');

		//Módulo de Providers
		Route::resource('proveedores', 			'ProviderController');
		Route::put('/proveedor/actualizar', 	'ProviderController@update');
		
	});

	Route::group(['middleware' => ['seller']], function(){
		//Módulo de Clients
		Route::resource('clientes', 			'ClientController');
		Route::put('/clientes/actualizar', 		'ClientController@update');

	});


	Route::group(['middleware' => ['admin']], function(){

		//Módulo de Clients
		Route::resource('clientes', 			'ClientController');
		Route::put('/clientes/actualizar', 		'ClientController@update'); 

		//Módulo de Categorias
		Route::resource('categorias', 			'CategoryController');
		Route::put('/categorias/actualizar', 	'CategoryController@update');
		Route::put('/categoria/desactivar', 	'CategoryController@disable');
		Route::put('/categoria/activar', 		'CategoryController@enable');
		Route::get('/categoria/seleccionar', 	'CategoryController@select_categories');

		//Módulo de Productos
		Route::resource('productos', 			'ProductController');
		Route::put('/producto/actualizar', 		'ProductController@update');
		Route::put('/producto/desactivar', 		'ProductController@disable');
		Route::put('/producto/activar', 		'ProductController@enable');

		//Módulo de Providers
		Route::resource('proveedores', 			'ProviderController');
		Route::put('/proveedor/actualizar', 	'ProviderController@update');	


		//Módulo de Roles
		Route::resource('roles', 				'RoleController');
		Route::get('/rol/seleccionar', 			'RoleController@select_role');
		//Módulo de Usuarios
		Route::resource('usuarios', 			'UserController');
		Route::put('/usuarios/actualizar', 		'UserController@update');
		Route::put('/usuario/desactivar', 		'UserController@disable');
		Route::put('/usuario/activar', 			'UserController@enable');
		Route::get('/usuario/seleccionar', 		'UserController@select_categories');

	});

}); 


// Route::get('/home', 'HomeController@index')->name('home');


