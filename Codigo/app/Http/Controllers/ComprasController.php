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
            "SELECT met.id AS metodo_envio,envios.id as idEnvio, envios.duracion AS duracionEnvio, envios.precio AS precioEnvio, envios.tipo AS tipoEnvio, p.nombre as paisEnvio
            FROM rdj_metodos_envios AS envios, rdj_metodos_contratos as met, rdj_paises as p
            WHERE met.fecha_cont=? and met.id_productor=? and met.id_prov_envio=? and envios.id = met.id_envio and
            p.id=envios.id_pais"
        ),[$fecha,$id_prod,$id_proveedor]);

        /* Se buscan los metodos de pago del contrato respectivo*/
        $pagosContratados= DB::SELECT(DB::RAW(
            "SELECT met.id AS metodo_pago,pagos.id as idPago,pagos.tipo AS tipoPago, pagos.num_cuotas AS cuotas, pagos.porcentaje AS porcentaje, pagos.meses AS meses
            FROM rdj_metodos_pagos AS pagos, rdj_metodos_contratos AS met
            WHERE met.fecha_cont=? AND met.id_productor=? AND met.id_prov_pago=? AND pagos.id = met.id_pago"
        ),[$fecha,$id_prod,$id_proveedor]);

        /* Se buscan los extras de envio del contrato respectivo*/
        $extrasEnvio= DB::SELECT(DB::RAW(
            "SELECT det.id,det.id_envio as idEnvio,det.nombre AS nombre,det.mod_precio AS precio,det.mod_duracion AS duracion
            FROM rdj_detalles_metodos_envios AS det, rdj_metodos_contratos AS metCont
            WHERE det.id_proveedor=metCont.id_proveedor AND metCont.fecha_cont=? AND det.id_envio=metCont.id_envio;"
        ),[$fecha]);

         /* Se buscan los productos(esencias y componentes) del contrato respectivo con sus respectivas presentaciones*/
         $productos=DB::select(DB::RAW(
            "SELECT detcont.descuento AS descuento_producto,ing.cas_ing_esencia AS cas_prod, presIng.id AS presentacionId, det.fecha_apertura as fechaApert,ing.nombre as nombreProd, to_char(ing.cas_ing_esencia,'9999900-00-0') AS ncas, presIng.volumen AS presentacion, presIng.precio AS precioIng, CASE WHEN ing.naturaleza = 's' THEN 'Esencia sintética' WHEN ing.naturaleza = 'n' THEN 'Esencia natural' END AS tipo 
            FROM rdj_ingredientes_esencias AS ing, rdj_presents_ings_esencias AS presIng, rdj_detalles_contratos AS det, rdj_contratos AS cont,rdj_detalles_contratos AS detcont
            WHERE det.id_productor=? AND ing.id_proveedor=det.id_proveedor AND ing.cas_ing_esencia=presIng.cas_ing_esencia
            AND det.fecha_apertura=? AND det.fecha_apertura=detcont.fecha_apertura AND det.cas_ing_esencia = ing.cas_ing_esencia AND cont.fecha_apertura=detcont.fecha_apertura AND ing.cas_ing_esencia = detcont.cas_ing_esencia
            GROUP BY cas_prod, det.fecha_apertura,nombre,ncas,precioIng,tipo,presentacion,presentacionId,descuento_producto
            UNION
            SELECT detcont.descuento AS descuento_producto,otro.cas_otro_ing AS cas_prod,presOtro.id AS presentacionId,det.fecha_apertura AS fechaApert,otro.nombre AS nombreProd, to_char(otro.cas_otro_ing,'9999900-00-0') AS ncas, presOtro.volumen AS presentacion, presOtro.precio AS precioIng, 'Componente' AS tipo 
            FROM rdj_otros_ingredientes AS otro, rdj_present_otros_ings AS presOtro, rdj_detalles_contratos AS det, rdj_contratos AS cont,rdj_detalles_contratos AS detcont
            WHERE det.id_productor=? AND otro.id_proveedor=det.id_proveedor AND otro.cas_otro_ing=presOtro.cas_otro_ing 
            AND det.fecha_apertura=? AND det.fecha_apertura=detcont.fecha_apertura AND det.cas_otro_ing = otro.cas_otro_ing AND cont.fecha_apertura=detcont.fecha_apertura AND otro.cas_otro_ing = detcont.cas_otro_ing
            GROUP BY cas_prod,det.fecha_apertura,nombre,ncas,precioIng,tipo,presentacion,presentacionId,descuento_producto 
            ORDER BY nombreProd,presentacion ASC"
        ),[$id_prod,$fecha,$id_prod,$fecha]);

        /* Se buscan los productos(componentes) del contrato respectivo*/
        /*$componentesContratados= DB::select(DB::RAW(
            "SELECT det.fecha_apertura,otro.nombre, to_char(otro.cas_otro_ing,'9999900-00-0') AS ncas, presOtro.volumen || ' ml' AS presentacion, presOtro.precio || ' $' AS precioIng, 'Componente' AS tipo 
            FROM rdj_otros_ingredientes AS otro, rdj_present_otros_ings AS presOtro, rdj_detalles_contratos as det, rdj_contratos as cont
            WHERE det.id_productor=? AND otro.id_proveedor=det.id_proveedor AND otro.cas_otro_ing=presOtro.cas_otro_ing AND det.fecha_apertura=? AND det.cas_otro_ing = otro.cas_otro_ing GROUP BY det.fecha_apertura,nombre,ncas,precioIng,tipo,presentacion"
        ),[$id_prod,$fecha]);*/

        return response([$enviosContratados,$pagosContratados,$extrasEnvio,$productos,$id_prod,$id_proveedor,$fecha],200);
    }

    //Funcion para crear un pedido
    public function crearPedido(Request $request){

        $numeroPedido= DB::SELECT(DB::RAW("SELECT nextval('rdj_pedido_sec')"));

        try {
            DB::INSERT(DB::RAW(
                "INSERT INTO rdj_pedidos (num_pedido,fecha_pedido,estatus,id_proveedor,id_productor,monto,
                id_envio,fecha_ap_envio,id_prod_envio,id_prov_envio,id_pago,fecha_ap_pago,id_prod_pago,id_prov_pago) 
                VALUES (?,NOW()::DATE,'p',?,?,?,?,?,?,?,?,?,?,?)"
            ),[$numeroPedido[0]->nextval,$request["proveedor"],$request["productor"],$request["montoTotal"],$request["envio"],
            $request["fecha"],$request["productor"],$request["proveedor"],$request["pago"],
            $request["fecha"],$request["productor"],$request["proveedor"]]);
    
            foreach ($request["productos"] as $producto) {
                 
                if($producto["tipo"]=="Componente"){
                    DB::INSERT(DB::RAW("INSERT INTO rdj_detalles_pedidos(id,num_pedido,cantidad,id_pres_otro,
                    cas_otro,precio)
                    VALUES (nextval('rdj_det_pedido_sec'),?,?,?,?,?)"),[$numeroPedido[0]->nextval,$producto["cantidad"],
                    $producto["idPresentacion"],$producto["idProducto"],$producto["precio"]]);
                }
                else{
                    DB::INSERT(DB::RAW("INSERT INTO rdj_detalles_pedidos(id,num_pedido,cantidad,id_pres_esencia,
                    cas_esencia,precio)
                    VALUES (nextval('rdj_det_pedido_sec'),?,?,?,?,?)"),[$numeroPedido[0]->nextval,$producto["cantidad"],
                    $producto["idPresentacion"],$producto["idProducto"],$producto["precio"]]);
                }
            }
        } catch (\Throwable $th) {
            return ($th);
        }
        
        return response(["Producto Creado con Éxito"],200);
    }

    public function verPedidosProductor($id_prod,$id_prov,$fecha){

        $pedidosPendientes=DB::SELECT(DB::raw(
            "SELECT p.num_pedido AS num_pedido, p.fecha_pedido AS fecha, p.id_productor AS id_prod, pd.id AS id_prov, pd.nombre AS prov, p.factura AS id_factura   
            FROM rdj_pedidos p, rdj_proveedores pd
            WHERE p.id_productor=? AND p.estatus='p' AND p.id_proveedor=pd.id AND p.fecha_ap_envio=? AND p.factura IS NULL
            UNION
            SELECT p.num_pedido AS num_pedido, p.fecha_pedido AS fecha, p.id_productor AS id_prod, pd.id AS id_prov, pd.nombre AS prov, p.factura AS id_factura   
            FROM rdj_pedidos p, rdj_proveedores pd
            WHERE p.id_productor=? AND p.estatus='p' AND p.id_proveedor=pd.id AND p.fecha_ap_envio=? AND p.factura IS NOT NULL
            ORDER BY fecha"
        ),[$id_prod,$fecha,$id_prod,$fecha]);

        $pedidosNoPendientes=DB::SELECT(DB::raw(
            "SELECT p.num_pedido AS num_pedido, p.fecha_pedido AS fecha, p.id_productor AS id_prod, pd.id AS id_prov, pd.nombre AS prov, p.factura AS id_factura, p.estatus AS estatus   
            FROM rdj_pedidos p, rdj_proveedores pd
            WHERE p.id_productor=? AND p.estatus='e' AND p.fecha_ap_envio=? AND p.id_proveedor=pd.id
            UNION
            SELECT p.num_pedido AS num_pedido, p.fecha_pedido AS fecha, p.id_productor AS id_prod, pd.id AS id_prov, pd.nombre AS prov, p.factura AS id_factura, p.estatus AS estatus   
            FROM rdj_pedidos p, rdj_proveedores pd
            WHERE p.id_productor=? AND p.estatus='cprod' AND p.fecha_ap_envio=? AND p.id_proveedor=pd.id
            UNION
            SELECT p.num_pedido AS num_pedido, p.fecha_pedido AS fecha, p.id_productor AS id_prod, pd.id AS id_prov, pd.nombre AS prov, p.factura AS id_factura, p.estatus AS estatus   
            FROM rdj_pedidos p, rdj_proveedores pd
            WHERE p.id_productor=? AND p.estatus='cprov' AND p.fecha_ap_envio=? AND p.id_proveedor=pd.id
            ORDER BY fecha"
        ),[$id_prod,$fecha,$id_prod,$fecha,$id_prod,$fecha]);

        return view('productores.compras.ver-pedidos-productor',[
            'id_prod' => $id_prod,
            'pedidosPendientes' => $pedidosPendientes,
            'pedidosNoPendientes' => $pedidosNoPendientes,
        ]);
    } 

    public function detallePedidoProductor($id_prod,$id_prov,$num_pedido){

        $pedido=DB::SELECT(DB::raw(
            "SELECT p.fecha_pedido AS fecha, p.num_pedido As num_pedido, pd.nombre AS prod, pv.nombre AS prov, p.estatus AS estatus, p.factura AS id_factura, p.fecha_envio AS fecha_envio, p.monto AS monto       
            FROM rdj_pedidos p, rdj_productores pd, rdj_proveedores pv
            WHERE p.num_pedido=? AND p.id_proveedor=? AND p.id_productor=pd.id"
        ),[$num_pedido,$id_prov]);
        
        $ingredientesPedido=DB::SELECT(DB::raw(
            "SELECT i.cas_ing_esencia AS cas, i.nombre AS nombre, presentIng.volumen AS presentacion, dp.cantidad AS cantidad, dp.precio AS precio       
            FROM rdj_pedidos p, rdj_detalles_pedidos dp, rdj_presents_ings_esencias presentIng, rdj_ingredientes_esencias i
            WHERE dp.num_pedido=? AND p.id_proveedor=? AND dp.num_pedido=p.num_pedido AND i.cas_ing_esencia=dp.cas_esencia AND dp.id_pres_esencia=presentIng.id AND i.cas_ing_esencia=presentIng.cas_ing_esencia
            UNION
            SELECT o.cas_otro_ing AS cas, o.nombre AS nombre, presentOIng.volumen AS presentacion, dp.cantidad AS cantidad, dp.precio AS precio       
            FROM rdj_pedidos p, rdj_detalles_pedidos dp, rdj_present_otros_ings presentOIng, rdj_otros_ingredientes o
            WHERE dp.num_pedido=? AND p.id_proveedor=? AND dp.num_pedido=p.num_pedido AND o.cas_otro_ing=dp.cas_otro AND dp.id_pres_otro=presentOIng.id AND o.cas_otro_ing=presentOIng.cas_otro_ing
            ORDER BY cas,presentacion"
        ),[$num_pedido,$id_prov,$num_pedido,$id_prov]);

        $enviosPedido=DB::SELECT(DB::raw(
            "SELECT mc.fecha_cont AS fecha_cont,me.tipo AS tipo, me.duracion AS duracion, me.precio AS precio, pa.nombre AS pais       
            FROM rdj_pedidos p, rdj_metodos_envios me, rdj_paises pa, rdj_metodos_contratos mc
            WHERE p.num_pedido=? AND mc.id=p.id_envio AND mc.id_envio=me.id AND pa.id=me.id_pais"
        ),[$num_pedido]);

        $pagosPedido=DB::SELECT(DB::raw(
            "SELECT pa.tipo AS tipo, pa.num_cuotas AS cuotas, pa.porcentaje AS porcentaje, pa.meses AS meses       
            FROM rdj_pedidos p, rdj_metodos_pagos pa, rdj_metodos_contratos mc
            WHERE p.num_pedido=? AND p.id_pago=mc.id AND mc.id_pago=pa.id"
        ),[$num_pedido]);

        foreach($ingredientesPedido as $ing){
            $ing->cas=Controller::stringifyCas($ing->cas);
        }

        $precioEnvio=$pedido[0]->monto;

        foreach($ingredientesPedido as $ingrediente){
            $precioEnvio=$precioEnvio-$ingrediente->precio;
        }

        $enviosPedido[0]->precio=$precioEnvio;

        return view('productores.compras.detalle-pedido-productor',[
            'id_prov' => $id_prov,
            'id_prod' => $id_prod,
            'pedido' => $pedido,
            'ingredientesPedido' => $ingredientesPedido,
            'enviosPedido' => $enviosPedido,
            'pagosPedido' => $pagosPedido,
        ]);
    
    }

    public function rechazarPedidoProductor($id_prod,$id_prov,$num_pedido){

        $fecha=DB::SELECT(DB::RAW("SELECT fecha_ap_envio AS fecha FROM rdj_pedidos WHERE num_pedido=?"),[$num_pedido]);

        DB::update(DB::raw(
            "UPDATE rdj_pedidos SET estatus='cprod' WHERE num_pedido=?"
        ),[$num_pedido]);

        return redirect('/productor/'.$id_prod.'/pedidos/'.$id_prov.'/'.$fecha[0]->fecha);

    }

    //Ver facturas de un productor filtradas por proveedor
    public function verFacturasProductor($id_prod,$fecha){

        //Metodos de pago de un pedido
        $pagos=DB::SELECT(DB::raw(
            "SELECT p.fecha_pedido AS fecha_inicial,pa.id AS id_pago, pa.tipo AS tipo,
             pa.num_cuotas AS cuotas, pa.porcentaje AS porcentaje,pa.meses AS meses,
              p.num_pedido AS num_pedido      
            FROM rdj_metodos_pagos pa, rdj_pedidos p, rdj_metodos_contratos AS met
            WHERE p.id_pago=met.id AND pa.id=met.id_pago AND p.fecha_ap_envio=? AND p.factura IS NOT NULL"
        ),[$fecha]);

        //Todos los pagos realizados para un pedido
        $pagados=DB::SELECT(DB::raw(
            "SELECT pag.num_pago AS num_pago,pag.fecha_pago as fecha, pag.num_pedido AS num_pedido, pag.monto AS monto       
            FROM rdj_pagos pag, rdj_pedidos p
            WHERE pag.num_pedido=p.num_pedido AND p.fecha_ap_envio=? AND p.factura IS NOT NULL
            ORDER BY pag.num_pedido"
        ),[$fecha]);

        $facturas=DB::SELECT(DB::RAW(
            "SELECT p.factura AS num_factura,pa.num_cuotas AS cuotas, p.num_pedido AS num_pedido,pv.id AS id_prod, pv.nombre AS prov, p.monto AS monto, p.id_pago AS id_pago, p.monto AS por_pagar        
            FROM rdj_pedidos p, rdj_proveedores pv,rdj_metodos_pagos pa,rdj_metodos_contratos AS met
            WHERE p.id_productor=? AND p.id_proveedor=pv.id AND p.fecha_ap_envio=? AND p.factura IS NOT NULL AND p.id_pago=met.id AND pa.id=met.id_pago
            ORDER BY num_factura,num_pedido"
        ),[$id_prod,$fecha]);

        $numeroCuotas=[];
        $tienePagos=[]; // para saber si la factura tiene pagos

        foreach ($pagados as $pagado) {
            $pagado->fecha = date("d/m/Y", strtotime((Carbon::createFromDate($pagado->fecha))));
        }
        $acumuladoresPagos=0;
        foreach ($facturas as $factura) {
            $acumuladorPagos=0;
            foreach ($pagos as $pago) {
                if($pago->num_pedido==$factura->num_pedido){
                    if($pago->cuotas>0 && $pago->cuotas!= null){
                        array_push($numeroCuotas,$pago->cuotas);  
                    }
                    else{
                        array_push($numeroCuotas,-1);
                        $pago->cuotas=-1;
                        $factura->cuotas=-1;
                    }
                    foreach ($pagados as $pagado) {
                        if($pagado->num_pedido==$factura->num_pedido && $pago->cuotas>0 && $pago->cuotas !=null){
                            $acumuladorPagos++;
                            $pago->cuotas=$pago->cuotas-1;
                            $factura->por_pagar=($factura->monto)-($pagado->monto*$acumuladorPagos);
                            
                        }
                        else if($pagado->num_pedido==$factura->num_pedido && $pago->cuotas==-1){
                            $acumuladorPagos++;
                            $pago->cuotas=-2;
                            $factura->por_pagar=0;
                        }
                    }
                    if($acumuladorPagos!=0){
                        array_push($tienePagos,true);
                    }
                    else{
                        array_push($tienePagos,false);
                    }
                }
                
            }
        }
        

        return view('productores.compras.ver-facturas-productor',[
            'id_prod' => $id_prod,
            'facturas' => $facturas,
            'pagos' => $pagos,
            'pagados' => $pagados,
            'numero_cuotas' =>$numeroCuotas,
            'tiene_pagos' => $tienePagos,
            //'cuotas_desde'=>$cuotasDesde
    
        ]);
    }



    /*public function vistaFacturas($id_prod){
        return view('productores.compras.ver-facturas-productor',['id_prod'=>$id_prod]);
    }*/

    //Realizar pago
    public function realizarPagoProductor($num_pedido,$monto){

        DB::INSERT(DB::RAW("
        INSERT INTO rdj_pagos (num_pago,num_pedido,fecha_pago,monto)
        VALUES(nextval('rdj_pago_sec'),?,NOW()::DATE,?)
        "),[$num_pedido,$monto]);

        $id_prod=DB::SELECT(DB::RAW("SELECT id_productor AS id_prod FROM rdj_pedidos WHERE num_pedido=?"),[$num_pedido]);
       //dd(intval($id_prod[0]->id_prod));
       $id_prod=$id_prod[0]->id_prod;
        //verContratosVigentes($id_prod->id_prod);
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


    /* Proveedor */

    public function verPedidos($id_prov){

        $pedidosPendientes=DB::SELECT(DB::raw(
            "SELECT p.num_pedido AS num_pedido, p.fecha_pedido AS fecha, p.id_proveedor AS id_prov, pd.id AS id_prod, pd.nombre AS prod, p.factura AS id_factura   
            FROM rdj_pedidos p, rdj_productores pd
            WHERE p.id_proveedor=? AND p.estatus='p' AND p.id_productor=pd.id AND p.factura IS NULL
            UNION
            SELECT p.num_pedido AS num_pedido, p.fecha_pedido AS fecha, p.id_proveedor AS id_prov, pd.id AS id_prod, pd.nombre AS prod, p.factura AS id_factura   
            FROM rdj_pedidos p, rdj_productores pd
            WHERE p.id_proveedor=? AND p.estatus='p' AND p.id_productor=pd.id AND p.factura IS NOT NULL
            ORDER BY fecha"
        ),[$id_prov,$id_prov]);

        $pedidosNoPendientes=DB::SELECT(DB::raw(
            "SELECT p.num_pedido AS num_pedido, p.fecha_pedido AS fecha, p.id_proveedor AS id_prov, pd.id AS id_prod, pd.nombre AS prod, p.factura AS id_factura, p.estatus AS estatus   
            FROM rdj_pedidos p, rdj_productores pd
            WHERE p.id_proveedor=? AND p.estatus='e' AND p.id_productor=pd.id
            UNION
            SELECT p.num_pedido AS num_pedido, p.fecha_pedido AS fecha, p.id_proveedor AS id_prov, pd.id AS id_prod, pd.nombre AS prod, p.factura AS id_factura, p.estatus AS estatus   
            FROM rdj_pedidos p, rdj_productores pd
            WHERE p.id_proveedor=? AND p.estatus='cprod' AND p.id_productor=pd.id
            UNION
            SELECT p.num_pedido AS num_pedido, p.fecha_pedido AS fecha, p.id_proveedor AS id_prov, pd.id AS id_prod, pd.nombre AS prod, p.factura AS id_factura, p.estatus AS estatus   
            FROM rdj_pedidos p, rdj_productores pd
            WHERE p.id_proveedor=? AND p.estatus='cprov' AND p.id_productor=pd.id
            ORDER BY fecha"
        ),[$id_prov,$id_prov,$id_prov]);

        return view('proveedores.pedidos.ver-pedidos',[
            'id_prov' => $id_prov,
            'pedidosPendientes' => $pedidosPendientes,
            'pedidosNoPendientes' => $pedidosNoPendientes,
        ]);
    } 

    public function detallePedido($id_prov,$id_prod,$num_pedido){

        $pedido=DB::SELECT(DB::raw(
            "SELECT p.fecha_pedido AS fecha, p.num_pedido As num_pedido, pd.nombre AS prod, pv.nombre AS prov, p.estatus AS estatus, p.factura AS id_factura, p.fecha_envio AS fecha_envio, p.monto AS monto       
            FROM rdj_pedidos p, rdj_productores pd, rdj_proveedores pv
            WHERE p.num_pedido=? AND p.id_proveedor=? AND p.id_productor=pd.id"
        ),[$num_pedido,$id_prov]);
        
        $ingredientesPedido=DB::SELECT(DB::raw(
            "SELECT i.cas_ing_esencia AS cas, i.nombre AS nombre, presentIng.volumen AS presentacion, dp.cantidad AS cantidad, dp.precio AS precio       
            FROM rdj_pedidos p, rdj_detalles_pedidos dp, rdj_presents_ings_esencias presentIng, rdj_ingredientes_esencias i
            WHERE dp.num_pedido=? AND p.id_proveedor=? AND dp.num_pedido=p.num_pedido AND i.cas_ing_esencia=dp.cas_esencia AND dp.id_pres_esencia=presentIng.id AND i.cas_ing_esencia=presentIng.cas_ing_esencia
            UNION
            SELECT o.cas_otro_ing AS cas, o.nombre AS nombre, presentOIng.volumen AS presentacion, dp.cantidad AS cantidad, dp.precio AS precio       
            FROM rdj_pedidos p, rdj_detalles_pedidos dp, rdj_present_otros_ings presentOIng, rdj_otros_ingredientes o
            WHERE dp.num_pedido=? AND p.id_proveedor=? AND dp.num_pedido=p.num_pedido AND o.cas_otro_ing=dp.cas_otro AND dp.id_pres_otro=presentOIng.id AND o.cas_otro_ing=presentOIng.cas_otro_ing
            ORDER BY cas,presentacion"
        ),[$num_pedido,$id_prov,$num_pedido,$id_prov]);

        $enviosPedido=DB::SELECT(DB::raw(
            "SELECT me.tipo AS tipo, me.duracion AS duracion, me.precio AS precio, pa.nombre AS pais       
            FROM rdj_pedidos p, rdj_metodos_envios me, rdj_paises pa, rdj_metodos_contratos mc
            WHERE p.num_pedido=? AND mc.id=p.id_envio AND mc.id_envio=me.id AND pa.id=me.id_pais"
        ),[$num_pedido]);

        $pagosPedido=DB::SELECT(DB::raw(
            "SELECT pa.tipo AS tipo, pa.num_cuotas AS cuotas, pa.porcentaje AS porcentaje, pa.meses AS meses       
            FROM rdj_pedidos p, rdj_metodos_pagos pa, rdj_metodos_contratos mc
            WHERE p.num_pedido=? AND p.id_pago=mc.id AND mc.id_pago=pa.id"
        ),[$num_pedido]);

        foreach($ingredientesPedido as $ing){
            $ing->cas=Controller::stringifyCas($ing->cas);
        }

        $precioEnvio=$pedido[0]->monto;

        foreach($ingredientesPedido as $ingrediente){
            $precioEnvio=$precioEnvio-$ingrediente->precio;
        }

        $enviosPedido[0]->precio=$precioEnvio;

        return view('proveedores.pedidos.detalle-pedido',[
            'id_prov' => $id_prov,
            'id_prod' => $id_prod,
            'pedido' => $pedido,
            'ingredientesPedido' => $ingredientesPedido,
            'enviosPedido' => $enviosPedido,
            'pagosPedido' => $pagosPedido,
        ]);
    
    }

    public function aceptarPedido($id_prov,$id_prod,$num_pedido){

        $pedido=DB::SELECT(DB::raw(
            "SELECT p.factura AS factura       
            FROM rdj_pedidos p
            WHERE p.id_proveedor=? AND p.factura IS NOT NULL
            ORDER BY factura DESC"
        ),[$id_prov]);

        if($pedido!=[]){
            $factura=$pedido[0]->factura;
        }else{
            $factura=0;
        }

        $time=Carbon::now();

        DB::update(DB::raw(
            "UPDATE rdj_pedidos SET factura=?, estatus='e', fecha_envio=? WHERE num_pedido=?"
        ),[$factura+1,$time,$num_pedido]);

        return redirect('/proveedor/'.$id_prov.'/pedidos');

    }

    public function rechazarPedido($id_prov,$id_prod,$num_pedido){

        DB::update(DB::raw(
            "UPDATE rdj_pedidos SET estatus='cprov' WHERE num_pedido=?"
        ),[$num_pedido]);

        return redirect('/proveedor/'.$id_prov.'/pedidos');

    }

    public function enviarPedido($id_prov,$id_prod,$num_pedido){

        $time=Carbon::now();

        DB::update(DB::raw(
            "UPDATE rdj_pedidos SET estatus='e', fecha_envio=? WHERE num_pedido=?"
        ),[$time,$num_pedido]);

        return redirect('/proveedor/'.$id_prov.'/pedidos');

    }

    public function verFacturas($id_prov){

        $pagos=DB::SELECT(DB::raw(
            "SELECT pa.id AS id_pago, pa.tipo AS tipo, pa.num_cuotas AS cuotas, pa.porcentaje AS porcentaje, p.num_pedido AS num_pedido      
            FROM rdj_metodos_pagos pa, rdj_pedidos p,rdj_metodos_contratos AS met
            WHERE p.id_pago=met.id AND pa.id=met.id_pago AND p.factura IS NOT NULL
            ORDER BY num_pedido"
        ));

        $pagados=DB::SELECT(DB::raw(
            "SELECT pag.num_pago AS num_pago,pag.fecha_pago as fecha, pag.num_pedido AS num_pedido, pag.monto AS monto       
            FROM rdj_pagos pag, rdj_pedidos p
            WHERE pag.num_pedido=p.num_pedido AND p.factura IS NOT NULL
            ORDER BY pag.num_pedido"
        ));

        $facturas=DB::SELECT(DB::raw(
            "SELECT p.factura AS num_factura,pa.num_cuotas AS cuotas, p.num_pedido AS num_pedido, pd.nombre AS prod, p.monto AS monto, p.id_pago AS id_pago, p.monto AS por_pagar        
            FROM rdj_pedidos p, rdj_productores pd, rdj_metodos_pagos pa,rdj_metodos_contratos AS met
            WHERE p.id_proveedor=? AND p.id_productor=pd.id AND p.factura IS NOT NULL AND p.id_pago=met.id AND pa.id=met.id_pago
            ORDER BY num_factura,num_pedido"
        ),[$id_prov]);

        //CICLO DAVID
        /*foreach($facturas as $factura){
            foreach($pagados as $pagado){
                if($pagado->num_pedido==$factura->num_pedido){
                    $factura->por_pagar=$factura->por_pagar-$pagado->monto;
                    foreach($pagos as $pago){
                        if($pago->num_pedido==$pagado->num_pedido && $pago->cuotas>0){
                            $pago->cuotas=$pago->cuotas-1;
                        }
                    }
                }
            }
        }*/
        $numeroCuotas=[];
        $tienePagos=[]; // para saber si la factura tiene pagos

        foreach ($pagados as $pagado) {
            $pagado->fecha = date("d/m/Y", strtotime((Carbon::createFromDate($pagado->fecha))));
        }
        $acumuladoresPagos=0;
        foreach ($facturas as $factura) {
            $acumuladorPagos=0;
            foreach ($pagos as $pago) {
                if($pago->num_pedido==$factura->num_pedido){
                    if($pago->cuotas>0 && $pago->cuotas!= null){
                        array_push($numeroCuotas,$pago->cuotas);  
                    }
                    else{
                        array_push($numeroCuotas,-1);
                        $pago->cuotas=-1;
                        $factura->cuotas=-1;
                    }
                    foreach ($pagados as $pagado) {
                        if($pagado->num_pedido==$factura->num_pedido && $pago->cuotas>0 && $pago->cuotas !=null){
                            $acumuladorPagos++;
                            $pago->cuotas=$pago->cuotas-1;
                            $factura->por_pagar=($factura->monto)-($pagado->monto*$acumuladorPagos);
                            
                        }
                        else if($pagado->num_pedido==$factura->num_pedido && $pago->cuotas==-1){
                            $acumuladorPagos++;
                            $pago->cuotas=-2;
                            $factura->por_pagar=0;
                        }
                    }
                    if($acumuladorPagos!=0){
                        array_push($tienePagos,true);
                    }
                    else{
                        array_push($tienePagos,false);
                    }
                }
                
            }
        }

        return view('proveedores.pedidos.ver-facturas',[
            'id_prov' => $id_prov,
            'facturas' => $facturas,
            'pagos' => $pagos,
            'pagados' => $pagados,
            'numero_cuotas' =>$numeroCuotas,
            'tiene_pagos' => $tienePagos,
        ]);
    }


}
