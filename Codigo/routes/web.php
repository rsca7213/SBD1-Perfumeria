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

/* Rutas para acceder a los menus principales de las empresas */
Route::get('/productor/{id_prod}', 'MenuController@menuProductor')->name('menuProductor');
Route::get('/proveedor/{id_prov}', 'MenuController@menuProveedor')->name('menuProveedor');

/* Rutas referentes a las formulas de evaluacion */
Route::get('/productor/{id_prod}/formulas', 'FormulasController@verFormulas')->name('verFormulas');
Route::get('/productor/{id_prod}/formulas/crear/inicial', 'FormulasController@crearFormulaInicial')->name('crearFormulaInicial');
Route::post('/productor/{id_prod}/formulas/crear/inicial', 'FormulasController@insertFormulaInicial');
Route::get('/productor/{id_prod}/formulas/editar/inicial', 'FormulasController@editarFormulaInicial')->name('editarFormulaInicial');
Route::patch('/productor/{id_prod}/formulas/editar/inicial', 'FormulasController@updateFormulaInicial');
Route::get('/productor/{id_prod}/formulas/crear/anual', 'FormulasController@crearFormulaAnual')->name('crearFormulaAnual');
Route::post('/productor/{id_prod}/formulas/crear/anual', 'FormulasController@insertFormulaAnual');
Route::get('/productor/{id_prod}/formulas/editar/anual', 'FormulasController@editarFormulaAnual')->name('editarFormulaAnual');
Route::patch('/productor/{id_prod}/formulas/editar/anual', 'FormulasController@updateFormulaAnual');

Route::get('/productor/{id_prod}/escala/crear', 'FormulasController@crearEscala')->name('crearEscala');
Route::post('/productor/{id_prod}/escala/crear', 'FormulasController@insertEscala');
Route::get('/productor/{id_prod}/escala/editar', 'FormulasController@editarEscala')->name('editarEscala');
Route::patch('/productor/{id_prod}/escala/editar', 'FormulasController@updateEscala');

/* Ruta de pagina de inicio */
Route::get('/', function () {
    return view('index');
});
