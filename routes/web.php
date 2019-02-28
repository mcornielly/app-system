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

Route::group(['middleware' => ['auth']], function(){

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
		Route::get('/producto/buscar-producto',	'ProductController@search_product');
		Route::get('/producto/listar-productos','ProductController@list_product');
		Route::get('/producto/listar-pdf',		'ProductController@list_pdf')->name('products_pdf');
		

		//Módulo de Providers
		Route::resource('proveedores', 				'ProviderController');
		Route::put('/proveedor/actualizar', 		'ProviderController@update');
		Route::get('/proveedor/seleccionar-proveedor', 	'ProviderController@select_provider');

		//Módulo de Incomens
		Route::resource('ingresos', 			'IncomeController');
		Route::put('/ingreso/desactivar', 		'IncomeController@disable');
		Route::get('/ingreso/detalle_ingreso', 'IncomeController@get_header');
		Route::get('/ingreso/detalle_producto', 'IncomeController@get_detail');


		
	});

	Route::group(['middleware' => ['seller']], function(){
		//Módulo de Clients
		Route::resource('clientes', 					'ClientController');
		Route::put('/clientes/actualizar', 				'ClientController@update');
		Route::get('/cliente/seleccionar-cliente', 	'ClientController@select_client');

		//Módulo de Ventas, para mostrar los productos.
		Route::get('/producto/buscar-producto-venta',	'ProductController@search_product_sale');
		Route::get('/producto/listar-productos-venta','ProductController@list_product_sale');

		//Módulo de Ventas
		Route::resource('ventas', 				'SaleController');
		Route::put('/ventas/desactivar', 		'SaleController@disable');
		Route::get('/venta/detalle_venta', 		'SaleController@get_header');
		Route::get('/venta/detalle_producto', 	'SaleController@get_detail'); 
		Route::get('/venta/pdf/{id}', 			'SaleController@invoice_pdf')->name('sale_pdf');

	});


	Route::group(['middleware' => ['admin']], function(){

		//Módulo de Clients
		Route::resource('clientes', 					'ClientController');
		Route::put('/clientes/actualizar', 				'ClientController@update');
		Route::get('/cliente/seleccionar-cliente', 	'ClientController@select_client');

		//Módulo de Ventas
		Route::resource('ventas', 				'SaleController');
		Route::put('/venta/desactivar', 		'SaleController@disable');
		Route::get('/venta/detalle_venta', 		'SaleController@get_header');
		Route::get('/venta/detalle_producto', 	'SaleController@get_detail');
		Route::get('/venta/pdf/{id}', 			'SaleController@invoice_pdf')->name('sale_pdf');


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
		Route::get('/producto/buscar-producto',	'ProductController@search_product');
		Route::get('/producto/listar-productos','ProductController@list_product');
		Route::get('/producto/buscar-producto-venta',	'ProductController@search_product_sale');
		Route::get('/producto/listar-productos-venta',	'ProductController@list_product_sale');
		Route::get('/producto/listar-pdf',				'ProductController@list_pdf')->name('products_pdf');


		//Módulo de Providers
		Route::resource('proveedores', 			'ProviderController');
		Route::put('/proveedor/actualizar', 	'ProviderController@update');	
		Route::get('/proveedor/seleccionar-proveedor', 	'ProviderController@select_provider');

		//Módulo de Roles
		Route::resource('roles', 				'RoleController');
		Route::get('/rol/seleccionar', 			'RoleController@select_role');

		//Módulo de Usuarios
		Route::resource('usuarios', 			'UserController');
		Route::put('/usuarios/actualizar', 		'UserController@update');
		Route::put('/usuario/desactivar', 		'UserController@disable');
		Route::put('/usuario/activar', 			'UserController@enable');
		Route::get('/usuario/seleccionar', 		'UserController@select_categories');

		//Módulo de Incomens
		Route::resource('ingresos', 				'IncomeController');
		Route::put('/ingreso/desactivar', 			'IncomeController@disable');
		Route::get('/ingresos/detalle_ingreso', 	'IncomeController@get_header');
		Route::get('/ingresos/detalle_producto', 	'IncomeController@get_detail');

	});

}); 


// Route::get('/home', 'HomeController@index')->name('home');


