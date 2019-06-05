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
//Routas Mesas
Route::group(['middleware' => 'cors'], function(){
    
    Route::get('/mesa','MesaController@index');
    Route::post('/mesa/registrar','MesaController@store');
    Route::post('/mesa/actualizar/{id}','MesaController@update');
    Route::put('/mesa/desactivar','MesaController@desactivar');
    Route::put('/mesa/activar','MesaController@activar');
    Route::get('/mesa/selectMesa','MesaController@selectMesa');

    Route::get('/categoria/platillo','CategoriaController@index');
    Route::post('/categoria/platillo/registrar','CategoriaController@store');
    Route::post('/categoria/platillo/actualizar/{id}','CategoriaController@update');
    Route::put('/categoria/platillo/desactivar','CategoriaController@desactivar');
    Route::put('/categoria/platillo/activar','CategoriaController@activar');
    Route::get('/categoria/platillo/selectCategoria','CategoriaController@selectCategoria');
}); 
