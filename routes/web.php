<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');


//Propostas
Route::get('/listapropostas', 'PropostaController@index');
Route::post('/cadastrarproposta', 'PropostaController@store');
Route::post('/attproposta/{id}', 'PropostaController@update');
Route::get('/proposta/{id}','PropostaController@show');

//Saldo
Route::get('/getsaldo', 'ContaController@index');
Route::post('/attsaldo/{id}', 'ContaController@update');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
