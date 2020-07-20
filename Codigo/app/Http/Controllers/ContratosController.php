<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class ContratosController extends Controller
{

    public function verContratos ($id_prod) {


        $contratosVigentes=DB::select(DB::raw(
            "SELECT c.fecha_apertura AS fecha, pv.nombre AS prov, c.id_proveedor AS id_prov 
            FROM rdj_contratos c, rdj_proveedores pv, rdj_detalles_contratos dc
            WHERE c.id_productor=? AND c.cancelacion=false AND pv.id=c.id_proveedor  
            GROUP BY c.id_productor, c.fecha_apertura, pv.nombre, c.id_proveedor ORDER BY c.fecha_apertura"
        ),[$id_prod]);

        $contratosNoVigentes=DB::select(DB::raw(
            "SELECT c.fecha_apertura AS fecha, pv.nombre AS prov, c.id_proveedor AS id_prov 
            FROM rdj_contratos c, rdj_proveedores pv, rdj_productores pd
            WHERE c.id_productor=? AND c.cancelacion=true AND pv.id=c.id_proveedor
            GROUP BY c.id_productor, c.fecha_apertura, pv.nombre, c.id_proveedor ORDER BY c.fecha_apertura"
        ),[$id_prod]);

        $proveedores=DB::select(DB::raw(
            "SELECT pv.id AS id_prov, pv.nombre AS prov 
            FROM rdj_proveedores pv
            ORDER BY pv.id"
        ));

        return view('productores.contratos.ver-contratos',[
            'id_prod' => $id_prod,
            'contratosVigentes' => $contratosVigentes,
            'contratosNoVigentes' => $contratosNoVigentes,
            'proveedores' => $proveedores,
        ]);

    }

    public function generarContrato($id_prod,$id_prov){

        $detallesIng=DB::select(DB::raw(
            "SELECT i.cas_ing_esencia AS i_cas, i.cas_ing_esencia AS cas, i.nombre AS i_nombre, i.naturaleza AS naturaleza, pv.nombre AS prov  
            FROM rdj_ingredientes_esencias i, rdj_proveedores pv
            WHERE i.id_proveedor=? AND pv.id=i.id_proveedor
            ORDER BY i.cas_ing_esencia, i.nombre, i.naturaleza, pv.nombre"
        ),[$id_prov]);

        $presentIng=DB::select(DB::raw(
            "SELECT i.cas_ing_esencia AS i_cas, i.nombre AS i_nombre, pie.volumen AS volumen, pie.precio AS precio, pv.nombre AS prov  
            FROM rdj_ingredientes_esencias i, rdj_proveedores pv, rdj_presents_ings_esencias pie
            WHERE i.id_proveedor=? AND pv.id=i.id_proveedor AND pie.cas_ing_esencia=i.cas_ing_esencia
            ORDER BY i.cas_ing_esencia, i.nombre, pie.volumen, pie.precio, pv.nombre"
        ),[$id_prov]);

        $ingredientes_exclusivos=DB::select(DB::raw(
            "SELECT dc.cas_ing_esencia AS i_cas  
            FROM rdj_detalles_contratos dc, rdj_proveedores pv, rdj_contratos c 
            WHERE dc.id_proveedor=? AND dc.fecha_apertura=c.fecha_apertura AND c.cancelacion=false AND c.exclusivo=true AND dc.cas_ing_esencia IS NOT NULL 
            GROUP BY dc.cas_ing_esencia"
        ),[$id_prov]);

        foreach($ingredientes_exclusivos as $exclusivo){
            $i=0;
            foreach($detallesIng as $ing){
                if($exclusivo->i_cas==$ing->i_cas){
                    unset($detallesIng[$i]);
                }
                $i++;
            }
            $detallesIng=array_values($detallesIng);
        }

        $detallesOIng=DB::select(DB::raw(
            "SELECT o.cas_otro_ing AS o_cas, o.cas_otro_ing AS cas, o.nombre AS o_nombre, pv.nombre AS prov  
            FROM rdj_otros_ingredientes o, rdj_proveedores pv 
            WHERE o.id_proveedor=? AND pv.id=o.id_proveedor  
            "
        ),[$id_prov]);

        $presentOIng=DB::select(DB::raw(
            "SELECT o.cas_otro_ing AS o_cas, o.cas_otro_ing AS cas, o.nombre AS o_nombre, pv.nombre AS prov, poi.volumen AS volumen, poi.precio AS precio  
            FROM rdj_otros_ingredientes o, rdj_proveedores pv, rdj_present_otros_ings poi 
            WHERE o.id_proveedor=? AND pv.id=o.id_proveedor AND poi.cas_otro_ing=o.cas_otro_ing
            ORDER BY o.cas_otro_ing, o.nombre, poi.volumen, poi.precio, pv.nombre"
        ),[$id_prov]);

        $otros_ingredientes_exclusivos=DB::select(DB::raw(
            "SELECT dc.cas_otro_ing AS o_cas  
            FROM rdj_detalles_contratos dc, rdj_proveedores pv, rdj_contratos c 
            WHERE dc.id_proveedor=? AND dc.fecha_apertura=c.fecha_apertura AND c.cancelacion=false AND c.exclusivo=true AND dc.cas_otro_ing IS NOT NULL 
            GROUP BY dc.cas_otro_ing"
        ),[$id_prov]);

        foreach($otros_ingredientes_exclusivos as $exclusivo){
            $i=0;
            foreach($detallesOIng as $ing){
                if($exclusivo->o_cas==$ing->o_cas){
                    unset($detallesOIng[$i]);
                }
                $i++;
            }
            $detallesOIng=array_values($detallesOIng);
        }

        $detallesMEnvio=DB::select(DB::raw(
            "SELECT pv.nombre AS prov, me.tipo AS tipo, me.duracion AS duracion, me.precio AS precio, me.id AS id, p.nombre AS pais, me.id AS id   
            FROM rdj_metodos_envios me, rdj_proveedores pv, rdj_paises p, rdj_productores_paises pp
            WHERE me.id_proveedor=? AND pv.id=me.id_proveedor AND me.id_pais=p.id AND pp.id_productor=? AND pp.id_pais=me.id_pais  
            "
        ),[$id_prov,$id_prod]);

        $extrasMEnvio=DB::select(DB::raw(
            "SELECT dme.id AS id, dme.nombre AS nombre, dme.mod_precio AS precio, dme.mod_duracion AS duracion, dme.id_envio AS id_envio    
            FROM rdj_metodos_envios me, rdj_proveedores pv, rdj_paises p, rdj_productores_paises pp, rdj_detalles_metodos_envios dme
            WHERE dme.id_envio=me.id AND dme.id_proveedor=?
            GROUP BY dme.id, dme.nombre, dme.mod_precio, dme.mod_duracion, dme.id_envio ORDER BY dme.id"
        ),[$id_prov]);

        $detallesMPago=DB::select(DB::raw(
            "SELECT pv.nombre AS prov, pa.tipo AS tipo, pa.num_cuotas AS cuotas, pa.id AS id, pa.meses AS meses, pa.porcentaje AS porcentaje    
            FROM rdj_metodos_pagos pa, rdj_proveedores pv 
            WHERE pa.id_proveedor=? AND pv.id=pa.id_proveedor 
            "
        ),[$id_prov]);

        foreach($detallesIng as $detalle){
            $detalle->cas=Controller::stringifyCas($detalle->cas);
        }

        foreach($detallesOIng as $detalle){
            $detalle->cas=Controller::stringifyCas($detalle->cas);
        }

        return view('productores.contratos.g-contrato',[
            'id_prod' => $id_prod,
            'id_prov' => $id_prov,
            'detallesIng' => $detallesIng,
            'presentIng' => $presentIng,
            'presentOIng' => $presentOIng,
            'detallesOIng' => $detallesOIng,
            'detallesMEnvio' => $detallesMEnvio,
            'extrasMEnvio' => $extrasMEnvio,
            'detallesMPago' => $detallesMPago,
        ]);

    }

    public function insertContrato(Request $request, $id_prod, $id_prov){

        $ingredientes_esencia = $request->input('ingredientes_esencia');
        $otros_ingredientes = $request->input('otros_ingredientes');
        $metodos_envio = $request->input('metodos_envio');
        $metodos_pago = $request->input('metodos_pago');

        $time = Carbon::now()->toDateTimeString();

        if(is_null($ingredientes_esencia) && is_null($otros_ingredientes)){

            return back()->with('mensaje', 'Debes seleccionar al menos un ingrediente para generar el contrato.');

        }
        else{

            if(is_null($metodos_envio)){

                return back()->with('mensaje', 'Debes seleccionar al menos un metodo de envio para generar el contrato.');

            }
            else{

                if(is_null($metodos_pago)){

                    return back()->with('mensaje', 'Debes seleccionar un metodo de pago para generar el contrato.');

                }
                else{

                    if($request->input('exc')!=null){
                    
                        DB::insert(DB::raw(
                            "INSERT INTO rdj_contratos (fecha_apertura,id_proveedor,id_productor,exclusivo,cancelacion) VALUES
                            (?,?,?,TRUE,NULL);"
                        ),[$time,$id_prov,$id_prod]); 
                    
                    }
                    else{

                        DB::insert(DB::raw(
                            "INSERT INTO rdj_contratos (fecha_apertura,id_proveedor,id_productor,exclusivo,cancelacion) VALUES
                            (?,?,?,FALSE,NULL);"
                        ),[$time,$id_prov,$id_prod]);

                    }

                    if($request->input('ingredientes_esencia')!=null){
                        for($i=0; $i < count($request->input('ingredientes_esencia')); $i++){

                            DB::insert(DB::raw(
                                "INSERT INTO rdj_detalles_contratos (id,fecha_apertura,id_proveedor,id_productor,cas_ing_esencia)VALUES
                                (nextval('rdj_det_contrato_sec'),?,?,?,?);
                                "
                            ),[$time,$id_prov,$id_prod,$ingredientes_esencia[$i]]);
        
                        }
                    }

                    if($request->input('otros_ingredientes')!=null){
                        for($i=0; $i < count($request->input('otros_ingredientes')); $i++){

                            DB::insert(DB::raw(
                                "INSERT INTO rdj_detalles_contratos (id,fecha_apertura,id_proveedor,id_productor,cas_otro_ing)VALUES
                                (nextval('rdj_det_contrato_sec'),?,?,?,?);
                                "
                            ),[$time,$id_prov,$id_prod,$otros_ingredientes[$i]]);
        
                        }
                    }

                    for($i=0; $i<count($request->input('metodos_envio')); $i++){

                        $pais=DB::select(DB::raw(
                            "SELECT me.id_pais AS id  
                            FROM rdj_metodos_envios me 
                            WHERE me.id=? 
                            "
                        ),[$metodos_envio[$i]]);

                        DB::insert(DB::raw(
                            "INSERT INTO rdj_metodos_contratos (id,fecha_cont,id_proveedor,id_productor,id_envio,id_prov_envio,id_pais_envio)VALUES
                            (nextval('rdj_metodo_contrato_sec'),?,?,?,?,?,?);"
                        ),[$time,$id_prov,$id_prod,$metodos_envio[$i],$id_prov,$pais[0]->id]);

                    }

                    for($i=0; $i<count($request->input('metodos_pago')); $i++){

                        DB::insert(DB::raw(
                            "INSERT INTO rdj_metodos_contratos (id,fecha_cont,id_proveedor,id_productor,id_pago,id_prov_pago)VALUES
                            (nextval('rdj_metodo_contrato_sec'),?,?,?,?,?);
                            "
                        ),[$time,$id_prov,$id_prod,$metodos_pago[$i],$id_prov]);

                    }

                }

            }    

        }

        return redirect('/productor/'.$id_prod.'/contratos');

    }

    public function detalleContrato($id_prod,$id_prov,$fecha){

        $detalles=DB::select(DB::raw(
            "SELECT dc.fecha_apertura AS fecha, pv.nombre AS prod, dc.id_proveedor AS id_prov, c.exclusivo AS exc, c.cancelacion AS cancel, c.razon_cierre AS razon 
            FROM rdj_detalles_contratos dc, rdj_proveedores pv, rdj_contratos c
            WHERE dc.fecha_apertura=? AND dc.id_productor=? AND dc.id_proveedor=pv.id AND c.fecha_apertura=dc.fecha_apertura      
            GROUP BY dc.fecha_apertura, pv.nombre, dc.id_proveedor, c.exclusivo, c.cancelacion, c.razon_cierre"
        ),[$fecha,$id_prod]);

        $ingredientes_esencia=DB::select(DB::raw(
            "SELECT dc.cas_ing_esencia AS i_cas, i.cas_ing_esencia AS cas, i.nombre AS i_nombre, i.naturaleza AS naturaleza, dc.precio AS precio, dc.descuento AS descuento 
            FROM rdj_detalles_contratos dc, rdj_proveedores pv, rdj_ingredientes_esencias i, rdj_contratos c
            WHERE dc.fecha_apertura=? AND dc.id_productor=? AND dc.id_proveedor=pv.id AND dc.cas_ing_esencia=i.cas_ing_esencia AND c.fecha_apertura=dc.fecha_apertura     
            "
        ),[$fecha,$id_prod]);

        $presentIng=DB::select(DB::raw(
            "SELECT i.cas_ing_esencia AS i_cas, i.nombre AS i_nombre, pie.volumen AS volumen, pie.precio AS precio, pv.nombre AS prov  
            FROM rdj_ingredientes_esencias i, rdj_proveedores pv, rdj_presents_ings_esencias pie
            WHERE i.id_proveedor=? AND pv.id=i.id_proveedor AND pie.cas_ing_esencia=i.cas_ing_esencia
            ORDER BY i.cas_ing_esencia, i.nombre, pie.volumen, pie.precio, pv.nombre"
        ),[$id_prov]);

        $otros_ingredientes=DB::select(DB::raw(
            "SELECT dc.cas_otro_ing AS cas, dc.cas_otro_ing AS o_cas, o.nombre AS o_nombre, dc.precio AS precio, dc.descuento AS descuento 
            FROM rdj_detalles_contratos dc, rdj_proveedores pv, rdj_contratos c, rdj_otros_ingredientes o
            WHERE dc.fecha_apertura=? AND dc.id_productor=? AND dc.id_proveedor=pv.id AND dc.cas_otro_ing=o.cas_otro_ing AND c.fecha_apertura=dc.fecha_apertura     
            "
        ),[$fecha,$id_prod]);

        $presentOIng=DB::select(DB::raw(
            "SELECT o.cas_otro_ing AS o_cas, o.cas_otro_ing AS cas, o.nombre AS o_nombre, pv.nombre AS prov, poi.volumen AS volumen, poi.precio AS precio  
            FROM rdj_otros_ingredientes o, rdj_proveedores pv, rdj_present_otros_ings poi 
            WHERE o.id_proveedor=? AND pv.id=o.id_proveedor AND poi.cas_otro_ing=o.cas_otro_ing
            ORDER BY o.cas_otro_ing, o.nombre, poi.volumen, poi.precio, pv.nombre"
        ),[$id_prov]);

        $metodo_envio=DB::select(DB::raw(
            "SELECT me.id AS id, me.tipo AS tipo, me.duracion AS duracion, me.precio AS precio, p.nombre AS pais   
            FROM rdj_metodos_envios me, rdj_metodos_contratos mc, rdj_contratos c, rdj_paises p
            WHERE mc.fecha_cont=c.fecha_apertura AND mc.fecha_cont=? AND mc.id_proveedor=? AND mc.id_productor=? AND me.id=mc.id_envio AND p.id=me.id_pais   
            "
        ),[$fecha,$id_prov,$id_prod]);

        $extrasMEnvio=DB::select(DB::raw(
            "SELECT dme.id AS id, dme.nombre AS nombre, dme.mod_precio AS precio, dme.mod_duracion AS duracion, dme.id_envio AS id_envio    
            FROM rdj_metodos_envios me, rdj_proveedores pv, rdj_paises p, rdj_productores_paises pp, rdj_detalles_metodos_envios dme
            WHERE dme.id_envio=me.id AND dme.id_proveedor=?
            GROUP BY dme.id, dme.nombre, dme.mod_precio, dme.mod_duracion, dme.id_envio ORDER BY dme.id"
        ),[$id_prov]);

        $metodo_pago=DB::select(DB::raw(
            "SELECT mp.tipo AS tipo, mp.num_cuotas AS cuotas, mp.porcentaje AS porcentaje, mp.meses AS meses    
            FROM rdj_metodos_pagos mp, rdj_metodos_contratos mc, rdj_contratos c
            WHERE mc.fecha_cont=c.fecha_apertura AND mc.fecha_cont=? AND mc.id_proveedor=? AND mc.id_productor=? AND mp.id=mc.id_pago   
            "
        ),[$fecha,$id_prov,$id_prod]);

        foreach($ingredientes_esencia as $detalle){
            $detalle->cas=Controller::stringifyCas($detalle->cas);
        }

        foreach($otros_ingredientes as $detalle){
            $detalle->cas=Controller::stringifyCas($detalle->cas);
        }

        // dd($detalles);

        return view('productores.contratos.detalle-contrato',[
            'id_prod' => $id_prod,
            'id_prov' => $id_prov,
            'detalles' => $detalles,
            'presentIng' => $presentIng,
            'presentOIng' => $presentOIng,
            'ingredientes_esencia' => $ingredientes_esencia,
            'otros_ingredientes' => $otros_ingredientes,
            'metodo_envio' => $metodo_envio,
            'extrasMEnvio' => $extrasMEnvio,
            'metodo_pago' => $metodo_pago,
        ]);

    }

    public function cancelarContrato (Request $request,$id_prod,$fecha) {

        $data = $request->input('razon');

        DB::update(DB::raw(
            "UPDATE rdj_contratos 
            SET cancelacion=TRUE, razon_cierre=?
            WHERE id_productor=? AND fecha_apertura=?"),[$data,$id_prod,$fecha]);

        return redirect('/productor/'.$id_prod.'/contratos');

    }

    public function verContratosPv ($id_prov) {


        $contratosVigentes=DB::select(DB::raw(
            "SELECT c.fecha_apertura AS fecha, pd.nombre AS prod, c.id_productor AS id_prod 
            FROM rdj_contratos c, rdj_productores pd, rdj_detalles_contratos dc, rdj_proveedores pv
            WHERE c.id_proveedor=? AND c.cancelacion=false AND pd.id=c.id_productor 
            GROUP BY c.fecha_apertura, pd.nombre, c.id_productor ORDER BY c.fecha_apertura"
        ),[$id_prov]);

        $contratosSolicitud=DB::select(DB::raw(
            "SELECT c.fecha_apertura AS fecha, pd.nombre AS prod, c.id_productor AS id_prod 
            FROM rdj_contratos c, rdj_proveedores pv, rdj_productores pd
            WHERE c.id_proveedor=? AND c.cancelacion IS NULL AND pd.id=c.id_productor AND pv.id=c.id_proveedor
            ORDER BY c.fecha_apertura"
        ),[$id_prov]);

        // dd($contratosVigentes);

        return view('proveedores.contratos.ver-contratos',[
            'id_prov' => $id_prov,
            'contratosVigentes' => $contratosVigentes,
            'contratosSolicitud' => $contratosSolicitud,
        ]);

    }

    public function confirmarContratoPv ($id_prov, $id_prod, $fecha){

        $detalles=DB::select(DB::raw(
            "SELECT dc.fecha_apertura AS fecha, pd.nombre AS prod, dc.id_productor AS id_prod, c.exclusivo AS exc 
            FROM rdj_detalles_contratos dc, rdj_productores pd, rdj_contratos c
            WHERE dc.fecha_apertura=? AND dc.id_proveedor=? AND dc.id_productor=pd.id AND c.fecha_apertura=dc.fecha_apertura      
            "
        ),[$fecha,$id_prov]);

        $ingredientes_esencia=DB::select(DB::raw(
            "SELECT dc.cas_ing_esencia AS cas, dc.cas_ing_esencia AS i_cas, i.nombre AS i_nombre, i.naturaleza AS naturaleza 
            FROM rdj_detalles_contratos dc, rdj_productores pd, rdj_ingredientes_esencias i, rdj_contratos c
            WHERE dc.fecha_apertura=? AND dc.id_proveedor=? AND dc.id_productor=pd.id AND dc.cas_ing_esencia=i.cas_ing_esencia AND c.fecha_apertura=dc.fecha_apertura     
            "
        ),[$fecha,$id_prov]);

        $otros_ingredientes=DB::select(DB::raw(
            "SELECT dc.cas_otro_ing AS cas, dc.cas_otro_ing AS o_cas, o.nombre AS o_nombre, dc.precio AS precio, dc.descuento AS descuento 
            FROM rdj_detalles_contratos dc, rdj_productores pd, rdj_contratos c, rdj_otros_ingredientes o
            WHERE dc.fecha_apertura=? AND dc.id_proveedor=? AND dc.id_productor=pd.id AND dc.cas_otro_ing=o.cas_otro_ing AND c.fecha_apertura=dc.fecha_apertura     
            "
        ),[$fecha,$id_prov]);

        $metodo_envio=DB::select(DB::raw(
            "SELECT me.tipo AS tipo, me.duracion AS duracion, me.precio AS precio, p.nombre AS pais, me.id AS id   
            FROM rdj_metodos_envios me, rdj_metodos_contratos mc, rdj_contratos c, rdj_paises p
            WHERE mc.fecha_cont=c.fecha_apertura AND mc.fecha_cont=? AND mc.id_proveedor=? AND mc.id_productor=? AND me.id=mc.id_envio AND p.id=me.id_pais   
            "
        ),[$fecha,$id_prov,$id_prod]);

        $extrasMEnvio=DB::select(DB::raw(
            "SELECT dme.id AS id, dme.nombre AS nombre, dme.mod_precio AS precio, dme.mod_duracion AS duracion, dme.id_envio AS id_envio    
            FROM rdj_metodos_envios me, rdj_proveedores pv, rdj_paises p, rdj_productores_paises pp, rdj_detalles_metodos_envios dme
            WHERE dme.id_envio=me.id AND dme.id_proveedor=?
            GROUP BY dme.id, dme.nombre, dme.mod_precio, dme.mod_duracion, dme.id_envio ORDER BY dme.id"
        ),[$id_prov]);

        $metodo_pago=DB::select(DB::raw(
            "SELECT mp.tipo AS tipo, mp.num_cuotas AS cuotas, mp.porcentaje AS porcentaje, mp.meses AS meses    
            FROM rdj_metodos_pagos mp, rdj_metodos_contratos mc, rdj_contratos c
            WHERE mc.fecha_cont=c.fecha_apertura AND mc.fecha_cont=? AND mc.id_proveedor=? AND mc.id_productor=? AND mp.id=mc.id_pago   
            "
        ),[$fecha,$id_prov,$id_prod]);

        foreach($ingredientes_esencia as $detalle){
            $detalle->cas=Controller::stringifyCas($detalle->cas);
        }

        foreach($otros_ingredientes as $detalle){
            $detalle->cas=Controller::stringifyCas($detalle->cas);
        }

        // dd($detalles);

        return view('proveedores.contratos.confirmar-contrato',[
            'id_prod' => $id_prod,
            'id_prov' => $id_prov,
            'fecha' => $fecha,
            'detalles' => $detalles,
            'ingredientes_esencia' => $ingredientes_esencia,
            'otros_ingredientes' => $otros_ingredientes,
            'metodo_envio' => $metodo_envio,
            'extrasMEnvio' => $extrasMEnvio,
            'metodo_pago' => $metodo_pago,
        ]);
    }

    public function aceptarContratoPv (Request $request,$id_prov, $fecha){

        $i_descuentos=$request->input('i_descuentos');
        $o_descuentos=$request->input('o_descuentos');

        if($i_descuentos!= NULL && $o_descuentos!=NULL){
            $descuentos=array_merge($i_descuentos,$o_descuentos);
        }else{
            if($i_descuentos==NULL){
                $descuentos=$o_descuentos;
            }else{
                $descuentos=$i_descuentos;
            }
        }
        foreach($descuentos as $descuento){
            if($descuento<0 || $descuento>100){
                return back()->with('mensaje', 'Los porcentajes deben estar en un rango de 0% a 100%');
            }
        }
        
        $id_detalle=DB::select(DB::raw(
            "SELECT dc.id AS id  
            FROM rdj_detalles_contratos dc 
            WHERE dc.id_proveedor=? AND dc.fecha_apertura=?  
            "
        ),[$id_prov,$fecha]);
        
        for($i=0; $i<count($id_detalle);$i++){
            DB::update(DB::raw(
                "UPDATE rdj_detalles_contratos 
                SET descuento=?
                WHERE id=?"),[$descuentos[$i],$id_detalle[$i]->id]);
        }
        
        DB::update(DB::raw(
            "UPDATE rdj_contratos 
            SET cancelacion=FALSE
            WHERE id_proveedor=? AND fecha_apertura=?"),[$id_prov,$fecha]);

        // dd($id_detalle);

        return redirect('/proveedor/'.$id_prov.'/contratos'); 
    }

    // public function rechazarContratoPv ($id_prov, $fecha){
    //     DB::delete(DB::raw(
    //         "DELETE FROM rdj_contratos
    //         WHERE fecha_apertura=?"
    //         ),[$fecha]);

    //     return redirect('/proveedor/'.$id_prov.'/contratos'); 
    // }

    public function detalleContratoPv ($id_prov, $id_prod,$fecha){

        $detalles=DB::select(DB::raw(
            "SELECT dc.fecha_apertura AS fecha, pd.nombre AS prod, dc.id_productor AS id_prod, c.exclusivo AS exc 
            FROM rdj_detalles_contratos dc, rdj_productores pd, rdj_contratos c
            WHERE dc.fecha_apertura=? AND dc.id_proveedor=? AND dc.id_productor=pd.id AND c.fecha_apertura=dc.fecha_apertura      
            "
        ),[$fecha,$id_prov]);
        
        $ingredientes_esencia=DB::select(DB::raw(
            "SELECT dc.cas_ing_esencia AS cas, dc.cas_ing_esencia AS i_cas, i.nombre AS i_nombre, dc.descuento AS descuento, i.naturaleza AS naturaleza
            FROM rdj_detalles_contratos dc, rdj_productores pd, rdj_ingredientes_esencias i
            WHERE dc.fecha_apertura=? AND dc.id_proveedor=? AND dc.id_productor=pd.id AND dc.cas_ing_esencia=i.cas_ing_esencia     
            "
        ),[$fecha,$id_prov]);

        $presentIng=DB::select(DB::raw(
            "SELECT i.cas_ing_esencia AS i_cas, i.nombre AS i_nombre, pie.volumen AS volumen, pie.precio AS precio, pv.nombre AS prov  
            FROM rdj_ingredientes_esencias i, rdj_proveedores pv, rdj_presents_ings_esencias pie
            WHERE i.id_proveedor=? AND pv.id=i.id_proveedor AND pie.cas_ing_esencia=i.cas_ing_esencia
            ORDER BY i.cas_ing_esencia, i.nombre, pie.volumen, pie.precio, pv.nombre"
        ),[$id_prov]);

        $otros_ingredientes=DB::select(DB::raw(
            "SELECT dc.cas_otro_ing AS cas, dc.cas_otro_ing AS o_cas, o.nombre AS o_nombre, dc.precio AS precio, dc.descuento AS descuento 
            FROM rdj_detalles_contratos dc, rdj_productores pd, rdj_otros_ingredientes o
            WHERE dc.fecha_apertura=? AND dc.id_proveedor=? AND dc.id_productor=pd.id AND dc.cas_otro_ing=o.cas_otro_ing     
            "
        ),[$fecha,$id_prov]);

        $presentOIng=DB::select(DB::raw(
            "SELECT o.cas_otro_ing AS o_cas, o.cas_otro_ing AS cas, o.nombre AS o_nombre, pv.nombre AS prov, poi.volumen AS volumen, poi.precio AS precio  
            FROM rdj_otros_ingredientes o, rdj_proveedores pv, rdj_present_otros_ings poi 
            WHERE o.id_proveedor=? AND pv.id=o.id_proveedor AND poi.cas_otro_ing=o.cas_otro_ing
            ORDER BY o.cas_otro_ing, o.nombre, poi.volumen, poi.precio, pv.nombre"
        ),[$id_prov]);

        $metodo_envio=DB::select(DB::raw(
            "SELECT me.id AS id, me.tipo AS tipo, me.duracion AS duracion, me.precio AS precio, p.nombre AS pais   
            FROM rdj_metodos_envios me, rdj_metodos_contratos mc, rdj_contratos c, rdj_paises p
            WHERE mc.fecha_cont=c.fecha_apertura AND mc.fecha_cont=? AND mc.id_proveedor=? AND mc.id_productor=? AND me.id=mc.id_envio AND p.id=me.id_pais   
            "
        ),[$fecha,$id_prov,$id_prod]);

        $extrasMEnvio=DB::select(DB::raw(
            "SELECT dme.id AS id, dme.nombre AS nombre, dme.mod_precio AS precio, dme.mod_duracion AS duracion, dme.id_envio AS id_envio    
            FROM rdj_metodos_envios me, rdj_proveedores pv, rdj_paises p, rdj_productores_paises pp, rdj_detalles_metodos_envios dme
            WHERE dme.id_envio=me.id AND dme.id_proveedor=?
            GROUP BY dme.id, dme.nombre, dme.mod_precio, dme.mod_duracion, dme.id_envio ORDER BY dme.id"
        ),[$id_prov]);

        $metodo_pago=DB::select(DB::raw(
            "SELECT mp.tipo AS tipo, mp.num_cuotas AS cuotas, mp.porcentaje AS porcentaje, mp.meses AS meses    
            FROM rdj_metodos_pagos mp, rdj_metodos_contratos mc, rdj_contratos c
            WHERE mc.fecha_cont=c.fecha_apertura AND mc.fecha_cont=? AND mc.id_proveedor=? AND mc.id_productor=? AND mp.id=mc.id_pago   
            "
        ),[$fecha,$id_prov,$id_prod]);

        foreach($ingredientes_esencia as $detalle){
            $detalle->cas=Controller::stringifyCas($detalle->cas);
        }

        foreach($otros_ingredientes as $detalle){
            $detalle->cas=Controller::stringifyCas($detalle->cas);
        }

        // dd($ingredientes_esencia);

        return view('proveedores.contratos.detalle-contrato',[
            'id_prod' => $id_prod,
            'id_prov' => $id_prov,
            'detalles' => $detalles,
            'ingredientes_esencia' => $ingredientes_esencia,
            'otros_ingredientes' => $otros_ingredientes,
            'presentIng' => $presentIng,
            'presentOIng' => $presentOIng,
            'metodo_envio' => $metodo_envio,
            'extrasMEnvio' => $extrasMEnvio,
            'metodo_pago' => $metodo_pago,
        ]);

    }

}
