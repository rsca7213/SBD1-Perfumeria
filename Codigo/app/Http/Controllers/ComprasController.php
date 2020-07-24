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

        /*$contratosVigentes=DB::select(DB::raw(
            "SELECT c.fecha_apertura AS fecha, pv.nombre AS prov, c.id_proveedor AS id_prov 
            FROM rdj_contratos c, rdj_proveedores pv
            WHERE c.id_productor=? AND c.cancelacion=false AND pv.id=c.id_proveedor  
            GROUP BY c.id_productor, c.fecha_apertura, pv.nombre, c.id_proveedor ORDER BY c.fecha_apertura"
        ),[$id_prod]);*/

        $contratosVigentes=DB::SELECT(DB::RAW(
            "SELECT cont.fecha_apertura as fechaContrato,prod.nombre AS productor,prov.nombre AS prov,cont.id_proveedor AS id_prov, 'Inicial' AS tipo_contrato,cont.fecha_apertura AS fecha , to_char(cont.fecha_apertura::DATE + INTERVAL '1 year','dd/mm/yyyy') AS fecha_final 
            FROM rdj_proveedores AS prov, rdj_productores AS prod, rdj_contratos AS cont 
            WHERE prod.id=? AND prov.id=cont.id_proveedor AND cont.cancelacion=false AND prod.id = cont.id_productor AND ((NOW() - cont.fecha_apertura) < '1 YEAR')
            UNION
            SELECT reno.fecha_apertura as fechaContrato,prod.nombre AS productor,prov.nombre AS prov,reno.id_proveedor AS id_prov, 'Renovación' AS tipo_contrato, reno.fecha_renovacion AS fecha , to_char(reno.fecha_renovacion::DATE + INTERVAL '1 year','dd/mm/yyyy') AS fecha_final
            FROM rdj_proveedores AS prov, rdj_productores AS prod, rdj_renovaciones AS reno 
            WHERE prod.id=? AND prov.id=reno.id_proveedor AND prod.id = reno.id_productor AND ((NOW() - reno.fecha_renovacion) < '1 YEAR') ORDER BY fechaContrato ASC"
        ),[$id_prod,$id_prod]);

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
        return view('productores.compras.generar-pedido',[
            'id_prod' => $id_prod,
            'proveedor' => $proveedor,
            'fecha_apertura' => $fecha,
            'enviosContratados' => $enviosContratados,
            'pagosContratados' => $pagosContratados,
        ]);
    }

    public function enviosPagosPedido($id_prod,$id_proveedor,$fecha){
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

        /* Se buscan los extras de envio del contrato respectivo*/
        $extrasEnvio= DB::SELECT(DB::RAW(
            "SELECT det.id_envio,det.nombre,det.mod_precio,det.mod_duracion
            FROM rdj_detalles_metodos_envios AS det, rdj_metodos_contratos AS metCont
            WHERE det.id_proveedor=metCont.id_proveedor AND metCont.fecha_cont=? AND det.id_envio=metCont.id_envio;"
        ),[$fecha]);

         /* Se buscan los productos(esencias y componentes) del contrato respectivo con sus respectivas presentaciones*/
         $productos=DB::select(DB::RAW(
            "SELECT ing.cas_ing_esencia AS cas_prod, presIng.id AS presentacionId, det.fecha_apertura as fechaApert,ing.nombre as nombreProd, to_char(ing.cas_ing_esencia,'9999900-00-0') AS ncas, presIng.volumen AS presentacion, presIng.precio AS precioIng, CASE WHEN ing.naturaleza = 's' THEN 'Esencia sintética' WHEN ing.naturaleza = 'n' THEN 'Esencia natural' END AS tipo 
            FROM rdj_ingredientes_esencias AS ing, rdj_presents_ings_esencias AS presIng, rdj_detalles_contratos as det, rdj_contratos as cont
            WHERE det.id_productor=? AND ing.id_proveedor=det.id_proveedor AND ing.cas_ing_esencia=presIng.cas_ing_esencia AND det.fecha_apertura=? AND det.cas_ing_esencia = ing.cas_ing_esencia GROUP BY cas_prod, det.fecha_apertura,nombre,ncas,precioIng,tipo,presentacion,presentacionId
            UNION
            SELECT otro.cas_otro_ing AS cas_prod,presOtro.id AS presentacionId,det.fecha_apertura as fechaApert,otro.nombre as nombreProd, to_char(otro.cas_otro_ing,'9999900-00-0') AS ncas, presOtro.volumen AS presentacion, presOtro.precio AS precioIng, 'Componente' AS tipo 
            FROM rdj_otros_ingredientes AS otro, rdj_present_otros_ings AS presOtro, rdj_detalles_contratos as det, rdj_contratos as cont
            WHERE det.id_productor=? AND otro.id_proveedor=det.id_proveedor AND otro.cas_otro_ing=presOtro.cas_otro_ing AND det.fecha_apertura=? AND det.cas_otro_ing = otro.cas_otro_ing GROUP BY cas_prod,det.fecha_apertura,nombre,ncas,precioIng,tipo,presentacion,presentacionId ORDER BY nombreProd,presentacion ASC"
        ),[$id_prod,$fecha,$id_prod,$fecha]);

        /* Se buscan los productos(componentes) del contrato respectivo*/
        /*$componentesContratados= DB::select(DB::RAW(
            "SELECT det.fecha_apertura,otro.nombre, to_char(otro.cas_otro_ing,'9999900-00-0') AS ncas, presOtro.volumen || ' ml' AS presentacion, presOtro.precio || ' $' AS precioIng, 'Componente' AS tipo 
            FROM rdj_otros_ingredientes AS otro, rdj_present_otros_ings AS presOtro, rdj_detalles_contratos as det, rdj_contratos as cont
            WHERE det.id_productor=? AND otro.id_proveedor=det.id_proveedor AND otro.cas_otro_ing=presOtro.cas_otro_ing AND det.fecha_apertura=? AND det.cas_otro_ing = otro.cas_otro_ing GROUP BY det.fecha_apertura,nombre,ncas,precioIng,tipo,presentacion"
        ),[$id_prod,$fecha]);*/

        return response([$enviosContratados,$pagosContratados,$extrasEnvio,$productos],200);
    }

}
