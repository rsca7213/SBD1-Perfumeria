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

/* Rutas referentes al recomendador de perfumes */
Route::get('/recomendador', 'RecomendadorController@inicio')->name('inicioRecomendador');
Route::get('/recomendador/iniciar', 'RecomendadorController@iniciar')->name('iniciarRecomendador');
Route::post('/recomendador/resultados', 'RecomendadorController@resultados');

/* Rutas para acceder a los menus principales de las empresas */
Route::get('/productor/{id_prod}', 'MenuController@menuProductor')->name('menuProductor');
Route::get('/proveedor/{id_prov}', 'MenuController@menuProveedor')->name('menuProveedor');

/* Rutas referentes a las formulas de evaluacion */
Route::get('/productor/{id_prod}/formulas', 'FormulasController@verFormulas')->name('verFormulas');
Route::get('/productor/{id_prod}/formulas/crear/inicial', 'FormulasController@crearFormulaInicial')->name('crearFormulaInicial');
Route::post('/productor/{id_prod}/formulas/crear/inicial', 'FormulasController@insertFormulaInicial');
Route::get('/productor/{id_prod}/formulas/cambiar/inicial', 'FormulasController@cambiarFormulaInicial')->name('cambiarFormulaInicial');
Route::patch('/productor/{id_prod}/formulas/cambiar/inicial', 'FormulasController@updateFormulaInicial');
Route::get('/productor/{id_prod}/formulas/crear/anual', 'FormulasController@crearFormulaAnual')->name('crearFormulaAnual');
Route::post('/productor/{id_prod}/formulas/crear/anual', 'FormulasController@insertFormulaAnual');
Route::get('/productor/{id_prod}/formulas/cambiar/anual', 'FormulasController@cambiarFormulaAnual')->name('cambiarFormulaAnual');
Route::patch('/productor/{id_prod}/formulas/cambiar/anual', 'FormulasController@updateFormulaAnual');

/* Rutas referentes a la escala de evaluacion */
Route::get('/productor/{id_prod}/escala/crear', 'FormulasController@crearEscala')->name('crearEscala');
Route::post('/productor/{id_prod}/escala/crear', 'FormulasController@insertEscala');
Route::get('/productor/{id_prod}/escala/cambiar', 'FormulasController@cambiarEscala')->name('cambiarEscala');
Route::patch('/productor/{id_prod}/escala/cambiar', 'FormulasController@updateEscala');

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
Route::get('/productor/{id_prod}/evaluaciones/data/anual/{id_prov}/{fecha_ap_ts}', 'EvaluacionesController@dataProveedoresAnual');
Route::post('/productor/{id_prod}/evaluaciones/anual', 'EvaluacionesController@guardarAnual');

/* Rutas referentes a la confirmación de acción de generación o renovación de contrato */
Route::get('/productor/{id_prod}/evaluaciones/confirmar', 'EvaluacionesController@confirmacionContrato');

/* Rutas referentes a los contratos*/
/*Productor*/
Route::get('/productor/{id_prod}/contratos', 'ContratosController@verContratos')->name('verContratos');
Route::get('/productor/{id_prod}/contratos/generar/{id_prov}', 'ContratosController@generarContrato')->name('generarContrato');
Route::post('/productor/{id_prod}/contratos/generar/{id_prov}', 'ContratosController@insertContrato');
Route::get('/productor/{id_prod}/contratos/detalle/{id_prov}/{fecha}', 'ContratosController@detalleContrato');
Route::delete('/productor/{id_prod}/contratos/cancelar/{fecha}', 'ContratosController@cancelarContrato');
Route::get('/productor/{id_prod}/contratos/renovar/{id_prov}/{fecha}', 'ContratosController@renovarContrato');
Route::get('/productor/{id_prod}/contratos/generar_nuevo/{id_prov}/{fecha}', 'ContratosController@generarNuevoContrato');
/*Proveedor*/
Route::get('/proveedor/{id_prov}/contratos', 'ContratosController@verContratosPv')->name('verContratosPv');
Route::get('/proveedor/{id_prov}/contratos/confirmar/{id_prod}/{fecha}', 'ContratosController@confirmarContratoPv');
Route::post('/proveedor/{id_prov}/contratos/aceptar/{fecha}', 'ContratosController@aceptarContratoPv');
Route::get('/proveedor/{id_prov}/contratos/rechazar/{fecha}', 'ContratosController@rechazarContratoPv');
Route::get('/proveedor/{id_prov}/contratos/detalle/{id_prod}/{fecha}', 'ContratosController@detalleContratoPv');
Route::get('/proveedor/{id_prov}/contratos/renovar/{fecha}', 'ContratosController@renovarContratoPv');
Route::get('/proveedor/{id_prov}/contratos/no_renovar/{fecha}', 'ContratosController@rechazarRenovacionContratoPv');


/* Rutas referentes a las compras,pedidos,facturas */
/* Productor */
Route::get('/productor/{id_prod}/pedidos/{id_prov}/{fecha}', 'ComprasController@verPedidosProductor')->name('verPedidosProductor');
Route::get('/productor/{id_prod}/pedidos/detalle/{id_prov}/{num_pedido}', 'ComprasController@detallePedidoProductor');
Route::get('/productor/{id_prod}/pedidos/cancelar/{id_prov}/{num_pedido}', 'ComprasController@rechazarPedidoProductor');
Route::post('/productor/{id_prod}/compras/pedido/{id_proveedor}/crear','ComprasController@crearPedido');
Route::get('/productor/{id_prod}/compras', 'ComprasController@verContratosVigentes')->name('verContratosCompras');
Route::get('/productor/{id_prod}/compras/pedido/{id_proveedor}/{fecha}','ComprasController@mostrarDetallesPedido');
Route::get('/productor/{id_prod}/compras/pedido/{id_proveedor}/{fecha}/enviosPagos','ComprasController@enviosPagosPedido');
/* Proveedor */
Route::get('/proveedor/{id_prov}/pedidos', 'ComprasController@verPedidos')->name('verPedidos');
Route::get('/proveedor/{id_prov}/pedidos/detalle/{id_prod}/{num_pedido}', 'ComprasController@detallePedido');
Route::get('/proveedor/{id_prov}/pedidos/aceptar/{id_prod}/{num_pedido}', 'ComprasController@aceptarPedido');
Route::get('/proveedor/{id_prov}/pedidos/rechazar/{id_prod}/{num_pedido}', 'ComprasController@rechazarPedido');
Route::get('/proveedor/{id_prov}/pedidos/enviar/{id_prod}/{num_pedido}', 'ComprasController@enviarPedido');
/* Facturas */
Route::get('/proveedor/{id_prov}/facturas', 'ComprasController@verFacturas')->name('verFacturas');

/* Ruta de pagina de inicio */
Route::get('/', function () {
    return view('index');
});
