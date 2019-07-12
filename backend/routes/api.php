<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'cors'], function(){
    //Routas Mesas
    Route::get('/mesa','MesaController@index');
    Route::get('/mesas','MesaController@getAllMesas'); // aomine
    Route::post('/mesa/registrar','MesaController@store');
    Route::post('/mesa/actualizar/{id}','MesaController@update');
    Route::put('/mesa/desactivar/{id}','MesaController@desactivar');
    Route::put('/mesa/activar/{id}','MesaController@activar');
    Route::get('/mesa/selectMesa','MesaController@selectMesa');
    //Routas Categorias
    Route::get('/categoria/platillo','CategoriaController@index');
    Route::get('/categoria/platillos','CategoriaController@getAllCategorias');  // aomine
    Route::post('/categoria/platillo/registrar','CategoriaController@store');
    Route::post('/categoria/platillo/actualizar/{id}','CategoriaController@update');
    Route::put('/categoria/platillo/desactivar/{id}','CategoriaController@desactivar');
    Route::put('/categoria/platillo/activar/{id}','CategoriaController@activar');
    Route::get('/categoria/platillo/selectCategoria','CategoriaController@selectCategoria');
     //Platillos
     Route::get('/platillo','PlatilloController@index');
     Route::get('/platillos','PlatilloController@getAllPlatillos'); //  aomine
     Route::post('/platillo/registrar','PlatilloController@store');
     Route::put('/platillo/actualizar/{id}','PlatilloController@update');
     Route::put('/platillo/desactivar/{id}','PlatilloController@desactivar');
     Route::put('/platillo/activar/{id}','PlatilloController@activar');
     Route::get('/platillo/buscarPlatillo','PlatilloController@buscarPlatillo');
     Route::get('/platillo/listarPlatillo','PlatilloController@listarPlatillo');
     //usuarios
     Route::get('/user','UserController@index');
     Route::post('/user/registrar', 'UserController@store');
     Route::put('/user/actualizar/{id}', 'UserController@update');
     Route::put('/user/desactivar/{id}', 'UserController@desactivar');
     Route::put('/user/activar/{id}', 'UserController@activar');
     //Empleados
    Route::get('/empleado','EmpleadoController@index');
    Route::post('/empleado/registrar','EmpleadoController@store');
    Route::put('/empleado/actualizar/{id}','EmpleadoController@update');
    Route::put('/empleado/desactivar/{id}', 'EmpleadoController@desactivar');
    Route::put('/empleado/activar/{id}', 'EmpleadoController@activar');
     //Transacciones de pagos y adelantos de los empleados
     Route::get('/historial','TransaccionController@historial');
     Route::get('/stateSalary','TransaccionController@stateSalary');
     Route::post('/discount','TransaccionController@discount');

});
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
  
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});