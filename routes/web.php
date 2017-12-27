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

Route::get('/', function () {
    return view('layouts.default');
});


Route::post('/import-excel', 'ExcelController@import');
Route::name('validar')->post('/validar/{campo}/{dato}/{longitud}/{lugar}', 'ExcelController@validar');
Route::name('exportar')->post('/exportar', 'ExcelController@exportar');
