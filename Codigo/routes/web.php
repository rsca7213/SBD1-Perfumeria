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
Route::delete('/productor/{id_prod}/formulas/borrar/inicial', 'FormulasController@borrarFormulaInicial');
Route::get('/productor/{id_prod}/formulas/crear/anual', 'FormulasController@crearFormulaAnual')->name('crearFormulaAnual');
Route::post('/productor/{id_prod}/formulas/crear/anual', 'FormulasController@insertFormulaAnual');
Route::get('/productor/{id_prod}/formulas/editar/anual', 'FormulasController@editarFormulaAnual')->name('editarFormulaAnual');
Route::patch('/productor/{id_prod}/formulas/editar/anual', 'FormulasController@updateFormulaAnual');
Route::delete('/productor/{id_prod}/formulas/borrar/anual', 'FormulasController@borrarFormulaAnual');

/* Rutas referentes a la escala de evaluacion */
Route::get('/productor/{id_prod}/escala/crear', 'FormulasController@crearEscala')->name('crearEscala');
Route::post('/productor/{id_prod}/escala/crear', 'FormulasController@insertEscala');
Route::get('/productor/{id_prod}/escala/editar', 'FormulasController@editarEscala')->name('editarEscala');
Route::patch('/productor/{id_prod}/escala/editar', 'FormulasController@updateEscala');
Route::delete('/productor/{id_prod}/escala/borrar', 'FormulasController@borrarEscala');

/* Rutas referentes al proceso de evaluaciones */
Route::get('/productor/{id_prod}/evaluaciones', 'EvaluacionesController@verEvaluaciones')->name('verEvaluaciones');
Route::get('/productor/{id_prod}/evaluaciones/resultados', 'EvaluacionesController@verResultados');
Route::get('/productor/{id_prod}/evaluaciones/realizar', 'EvaluacionesController@realizarEvaluacion')->name('realizarEvaluacion');
Route::get('/productor/{id_prod}/evaluaciones/formulas', 'EvaluacionesController@buscarFormulasActuales');

/* Rutas referentes al proceso de evaluacion inicial */
Route::get('/productor/{id_prod}/evaluaciones/inicial', 'EvaluacionesController@dataEvaluacionInicial');
Route::get('/productor/{id_prod}/evaluaciones/data/inicial/{id_prov}', 'EvaluacionesController@dataProveedoresInicial');
Route::post('/productor/{id_prod}/evaluaciones/inicial', 'EvaluacionesController@guardarInicial');

/* Rutas referentes al proceso de evaluacion anual */
Route::get('/productor/{id_prod}/evaluaciones/anual', 'EvaluacionesController@dataEvaluacionAnual');
Route::get('/productor/{id_prod}/evaluaciones/data/anual/{id_prov}', 'EvaluacionesController@dataProveedoresAnual');
Route::post('/productor/{id_prod}/evaluaciones/anual', 'EvaluacionesController@guardarAnual');

/* Rutas referentes a la confirmación de acción de generación o renovación de contrato */
Route::get('/productor/{id_prod}/evaluaciones/confirmar', 'EvaluacionesController@confirmacionContrato');


/* Ruta de pagina de inicio */
Route::get('/', function () {
    return view('index');
});
