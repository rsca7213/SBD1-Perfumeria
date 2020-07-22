<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class ComprasController extends Controller
{
    //Muestra los contratos vigentes del productor respectivo
    public function verContratosVigentes ($id_prod) {

        $contratosVigentes=DB::select(DB::raw(
            "SELECT c.fecha_apertura AS fecha, pv.nombre AS prov, c.id_proveedor AS id_prov 
            FROM rdj_contratos c, rdj_proveedores pv
            WHERE c.id_productor=? AND c.cancelacion=false AND pv.id=c.id_proveedor  
            GROUP BY c.id_productor, c.fecha_apertura, pv.nombre, c.id_proveedor ORDER BY c.fecha_apertura"
        ),[$id_prod]);

        $proveedores=DB::select(DB::raw(
            "SELECT pv.id AS id_prov, pv.nombre AS prov 
            FROM rdj_proveedores pv
            ORDER BY pv.id"
        ));

        return view('productores.compras.contratos-compras',[
            'id_prod' => $id_prod,
            'contratosVigentes' => $contratosVigentes,
            'proveedores' => $proveedores,
        ]);

    }

    public function mostrarDetallesPedido ($id_prod,$id_proveedor,$fecha) {

        /* Se busca el proveedor del contrato*/
        $proveedor= DB::SELECT(DB::RAW(
            "SELECT nombre 
            FROM rdj_proveedores 
            WHERE id=?"
        ),[$id_proveedor]);

        $proveedor=$proveedor[0]->nombre;

        /* Se buscan los productos(esencia) del contrato respectivo con sus respectivas presentaciones*/
        $esenciasContratadas=DB::select(DB::RAW(
            "SELECT det.fecha_apertura,ing.nombre, to_char(ing.cas_ing_esencia,'9999900-00-0') AS ncas, presIng.volumen || ' ml' AS presentacion, presIng.precio || ' $' AS precioIng, CASE WHEN ing.naturaleza = 's' THEN 'Esencia Sintética' WHEN ing.naturaleza = 'n' THEN 'Esencia Natural' END AS tipo 
            FROM rdj_ingredientes_esencias AS ing, rdj_presents_ings_esencias AS presIng, rdj_detalles_contratos as det, rdj_contratos as cont
            WHERE det.id_productor=? AND ing.id_proveedor=det.id_proveedor AND ing.cas_ing_esencia=presIng.cas_ing_esencia AND det.fecha_apertura=? AND det.cas_ing_esencia = ing.cas_ing_esencia GROUP BY det.fecha_apertura,nombre,ncas,precioIng,tipo,presentacion"
        ),[$id_prod,$fecha]);

        /* Se buscan los productos(componentes) del contrato respectivo*/
        $componentesContratados= DB::select(DB::RAW(
            "SELECT det.fecha_apertura,otro.nombre, to_char(otro.cas_otro_ing,'9999900-00-0') AS ncas, presOtro.volumen || ' ml' AS presentacion, presOtro.precio || ' $' AS precioIng, 'Componente' AS tipo 
            FROM rdj_otros_ingredientes AS otro, rdj_present_otros_ings AS presOtro, rdj_detalles_contratos as det, rdj_contratos as cont
            WHERE det.id_productor=? AND otro.id_proveedor=det.id_proveedor AND otro.cas_otro_ing=presOtro.cas_otro_ing AND det.fecha_apertura=? AND det.cas_otro_ing = otro.cas_otro_ing GROUP BY det.fecha_apertura,nombre,ncas,precioIng,tipo,presentacion"
        ),[$id_prod,$fecha]);

        /* Se buscan los metodos de envio del contrato respectivo*/

        $enviosContratados= DB::SELECT(DB::RAW(
            "SELECT envios.id as idEnvio, envios.duracion AS duracionEnvio, envios.precio AS precioEnvio, envios.tipo AS tipoEnvio, p.nombre as paisEnvio
            FROM rdj_metodos_envios AS envios, rdj_metodos_contratos as met, rdj_paises as p
            WHERE met.fecha_cont=? and met.id_productor=? and met.id_prov_envio=? and envios.id = met.id_envio and
            p.id=envios.id_pais"
        ),[$fecha,$id_prod,$id_proveedor]);

        /* Se buscan los metodos de pago del contrato respectivo*/

        $pagosContratados= DB::SELECT(DB::RAW(
            "SELECT pagos.id idPago,pagos.tipo AS tipoPago, pagos.num_cuotas AS cuotas, pagos.porcentaje AS porcentaje, pagos.meses AS meses
            FROM rdj_metodos_pagos AS pagos, rdj_metodos_contratos AS met
            WHERE met.fecha_cont=? AND met.id_productor=? AND met.id_prov_pago=? AND pagos.id = met.id_pago"
        ),[$fecha,$id_prod,$id_proveedor]);

        //Devolvemos a la interfaz la data necesaria para continuar
        //return response([$esenciasContratadas,$componentesContratados,$enviosContratados,$pagosContratados],200);
        return view('productores.compras.generar-pedido',[
            'id_prod' => $id_prod,
            'proveedor' => $proveedor,
            'fecha_apertura' => $fecha,
            'esenciasContratadas' => $esenciasContratadas,
            'componentesContratados' => $componentesContratados,
            'enviosContratados' => $enviosContratados,
            'pagosContratados' => $pagosContratados,
        ]);
    }
}
