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


Route::get('/','App\Http\Controllers\TarefaController@index')->name('home');

Route::get('tarefa/exportacaoCliente', 'App\Http\Controllers\TarefaController@exportacaoCliente')
    ->name('tarefa.exportacaoCliente');
    
Route::get('tarefa/exportacaoAnimal', 'App\Http\Controllers\TarefaController@exportacaoAnimal')
    ->name('tarefa.exportacaoAnimal');

Route::post('tarefa/importacao', 'App\Http\Controllers\TarefaController@importacao')
->name('tarefa.importacao');
