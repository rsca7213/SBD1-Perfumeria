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

    public function crearPedido ($id_prod,$fecha) {

        /* Se busca el proveedor del contrato*/
        $id_proveedor= DB::SELECT(DB::RAW(
            "SELECT id_proovedor 
            FROM rdj_contratos 
            WHERE fecha_apertura=? and id_productor=?"
        ),[$fecha,$id_prod]);

        /* Se buscan los productos del contrato respectivo*/
        $productosContratados=DB::select(DB::RAW(
            "SELECT "
        ));

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

    }
}
