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

Route::middleware(['auth'])->group(function () {

Route::get('/', 'HomeController@index')->name('home');


//Propostas
Route::get('/listapropostas', 'PropostaController@index');
Route::delete('proposta/{id}','PropostaController@destroy');
Route::post('/cadastrarproposta', 'PropostaController@store');
Route::post('/attproposta/{id}', 'PropostaController@update');
Route::get('/editar/proposta/{id}','PropostaController@show');
Route::get('/comissoes/vendedor','PropostaController@comissoes');
Route::get('/comissoes','PropostaController@comissoesEscritorio');

//Saldo
Route::get('/getsaldo', 'ContaController@index');
Route::post('/attsaldo/{id}', 'ContaController@update');

//Relatórios
Route::get('/relatorios', 'HomeController@relatorios')->name('relatorios');
Route::get('/relatorio/{vendedor}/{mes}','PropostaController@comissoesVendedor');

});