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

// Route::get('/', function () {
//     return view('welcome');
// });
//Mesas
// Route::get('/mesa','MesaController@index');
// Route::post('/mesa/registrar','MesaController@store');
// Route::put('/mesa/actualizar','MesaController@update');
// Route::put('/mesa/desactivar','MesaController@desactivar');
// Route::put('/mesa/activar','MesaController@activar');
// Route::get('/mesa/selectMesa','MesaController@selectMesa');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware'=>['guest']],function(){
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});