<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class ContratosController extends Controller
{

    /* Productores */

    public function verContratos ($id_prod) {


        $contratosVigentes=DB::select(DB::raw(
            "SELECT c.fecha_apertura AS fecha, pv.nombre AS prov, c.id_proveedor AS id_prov, c.cancelacion AS cancelacion 
            FROM rdj_contratos c, rdj_proveedores pv
            WHERE c.id_productor=? AND c.cancelacion=false AND pv.id=c.id_proveedor   
            GROUP BY c.id_productor, c.fecha_apertura, pv.nombre, c.id_proveedor ORDER BY c.fecha_apertura"
        ),[$id_prod]);

        $contratosNoVigentes=DB::select(DB::raw(
            "SELECT c.fecha_apertura AS fecha, pv.nombre AS prov, c.id_proveedor AS id_prov, c.cancelacion AS cancelacion 
            FROM rdj_contratos c, rdj_proveedores pv, rdj_productores pd
            WHERE c.id_productor=? AND c.cancelacion=true AND pv.id=c.id_proveedor
            GROUP BY c.id_productor, c.fecha_apertura, pv.nombre, c.id_proveedor ORDER BY c.fecha_apertura"
        ),[$id_prod]);

        $contratosRenovados=DB::select(DB::raw(
            "SELECT c.fecha_apertura AS fecha, pv.nombre AS prov, c.id_proveedor AS id_prov, r.fecha_renovacion AS renovacion, c.cancelacion AS cancelacion 
            FROM rdj_contratos c, rdj_proveedores pv, rdj_renovaciones r
            WHERE c.id_productor=? AND pv.id=c.id_proveedor AND r.fecha_apertura=c.fecha_apertura   
            GROUP BY c.id_productor, c.fecha_apertura, pv.nombre, c.id_proveedor, r.fecha_renovacion ORDER BY c.fecha_apertura"
        ),[$id_prod]);

        $contratosNoRenovados=[];

        foreach($contratosRenovados as $renovado){
            $i=0;
            foreach($contratosRenovados as $contrato){
                if($contrato->fecha == $renovado->fecha && $renovado->renovacion > $contrato->renovacion){
                    unset($contratosRenovados[$i]);
                }
                $i++;
            }
            
            $contratosRenovados=array_values($contratosRenovados);
        }

        // dd($contratosRenovados);

        if($contratosRenovados==[]){
            $contratosNoRenovados=$contratosVigentes;
        }else{
            foreach($contratosVigentes as $contrato){
                $noRenovado=[];
                foreach($contratosRenovados as $renovado){
                    if($contrato->fecha==$renovado->fecha){
                        $noRenovado=null;
                        break;
                    }else{
                        $noRenovado=$contrato;
                    }
                }
                if($noRenovado)
                    array_push($contratosNoRenovados,$noRenovado);
            }
        }

        foreach($contratosNoRenovados as $noRenovado){
            $i=0;
            foreach($contratosVigentes as $contrato){
                if($contrato->fecha == $noRenovado->fecha && Carbon::parse($contrato->fecha)->addYear(1) < Carbon::now()){
                    array_push($contratosNoVigentes,$contrato);
                    unset($contratosVigentes[$i]);
                }
                $i++;
            }
            
            $contratosVigentes=array_values($contratosVigentes);
        }

        foreach($contratosRenovados as $renovado){
            $i=0;
            foreach($contratosVigentes as $contrato){
                if($contrato->fecha == $renovado->fecha && Carbon::parse($renovado->renovacion)->addYear(1) < Carbon::now()){
                    array_push($contratosNoVigentes,$contrato);
                    unset($contratosVigentes[$i]);
                }
                $i++;
            }
            
            $contratosVigentes=array_values($contratosVigentes);
        }

        $contratosEspera=DB::select(DB::raw(
            "SELECT c.fecha_apertura AS fecha, pv.nombre AS prov, c.id_proveedor AS id_prov, c.cancelacion AS cancelacion 
            FROM rdj_contratos c, rdj_proveedores pv
            WHERE c.id_productor=?  AND c.cancelacion IS NULL AND c.id_proveedor=pv.id  
            GROUP BY c.id_productor, c.fecha_apertura, pv.nombre, c.id_proveedor ORDER BY c.fecha_apertura"
        ),[$id_prod]);

        return view('productores.contratos.ver-contratos',[
            'id_prod' => $id_prod,
            'contratosVigentes' => $contratosVigentes,
            'contratosEspera' => $contratosEspera,
            'contratosNoVigentes' => $contratosNoVigentes,
        ]);

    }

    public function generarContrato($id_prod,$id_prov){

        $detallesIngContrato=[];
        $detallesOIngContrato=[];

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

        $ingsDisponibles=$detallesIng;

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

        $otrosIngsDisponibles=$detallesOIng;

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
            'detallesIngContrato' => $detallesIngContrato,
            'detallesOIngContrato' => $detallesOIngContrato,
            'otrosIngsDisponibles' => $otrosIngsDisponibles,
            'ingsDisponibles' => $ingsDisponibles,
        ]);

    }

    public function insertContrato(Request $request, $id_prod, $id_prov){

        $ingredientes_esencia = $request->input('ingredientes_esencia');
        $otros_ingredientes = $request->input('otros_ingredientes');
        $metodos_envio = $request->input('metodos_envio');
        $metodos_pago = $request->input('metodos_pago');

        $time = Carbon::now();

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

    public function generarNuevoContrato($id_prod,$id_prov,$fecha){

        $detallesIngContrato=DB::select(DB::raw(
            "SELECT dc.cas_ing_esencia AS i_cas, c.cancelacion AS cancelacion  
            FROM rdj_detalles_contratos dc, rdj_proveedores pv, rdj_contratos c 
            WHERE dc.id_proveedor=? AND dc.fecha_apertura=? AND dc.fecha_apertura=c.fecha_apertura AND dc.cas_ing_esencia IS NOT NULL 
            GROUP BY dc.cas_ing_esencia, c.cancelacion"
        ),[$id_prov,$fecha]);

        $detallesOIngContrato=DB::select(DB::raw(
            "SELECT dc.cas_otro_ing AS o_cas, c.cancelacion AS cancelacion 
            FROM rdj_detalles_contratos dc, rdj_proveedores pv, rdj_contratos c 
            WHERE dc.id_proveedor=? AND dc.fecha_apertura=? AND dc.fecha_apertura=c.fecha_apertura AND dc.cas_otro_ing IS NOT NULL 
            GROUP BY dc.cas_otro_ing, c.cancelacion"
        ),[$id_prov,$fecha]);

        DB::update(DB::raw(
            "UPDATE rdj_contratos 
            SET cancelacion=TRUE
            WHERE id_productor=? AND fecha_apertura=?"),[$id_prod,$fecha]);

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

        $ingsDisponibles=$detallesIng;

        foreach($detallesIngContrato as $contrato){
            $i=0;
            foreach($detallesIng as $ing){
                if($contrato->i_cas==$ing->i_cas){
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

        $otrosIngsDisponibles=$detallesOIng;

        foreach($detallesOIngContrato as $contrato){
            $i=0;
            foreach($detallesOIng as $ing){
                if($contrato->o_cas==$ing->o_cas){
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
            'detallesIngContrato' => $detallesIngContrato,
            'detallesOIngContrato' => $detallesOIngContrato,
            'otrosIngsDisponibles' => $otrosIngsDisponibles,
            'ingsDisponibles' => $ingsDisponibles,
        ]);

    }

    public function detalleContrato($id_prod,$id_prov,$fecha){

        $contratosEspera=DB::select(DB::raw(
            "SELECT c.fecha_apertura AS fecha, pv.nombre AS prov, c.id_proveedor AS id_prov, c.cancelacion AS cancelacion 
            FROM rdj_contratos c, rdj_proveedores pv
            WHERE c.id_productor=?  AND c.cancelacion IS NULL AND c.id_proveedor=pv.id  
            GROUP BY c.id_productor, c.fecha_apertura, pv.nombre, c.id_proveedor ORDER BY c.fecha_apertura"
        ),[$id_prod]);

        $detallesNoRenovado=DB::select(DB::raw(
            "SELECT dc.fecha_apertura AS fecha, pv.nombre AS prod, dc.id_proveedor AS id_prov, c.exclusivo AS exc, c.cancelacion AS cancel, c.razon_cierre AS razon 
            FROM rdj_detalles_contratos dc, rdj_proveedores pv, rdj_contratos c
            WHERE dc.fecha_apertura=? AND dc.id_productor=? AND dc.id_proveedor=pv.id AND c.fecha_apertura=dc.fecha_apertura      
            GROUP BY dc.fecha_apertura, pv.nombre, dc.id_proveedor, c.exclusivo, c.cancelacion, c.razon_cierre"
        ),[$fecha,$id_prod]);

        $detallesRenovado=DB::select(DB::raw(
            "SELECT dc.fecha_apertura AS fecha, pv.nombre AS prod, dc.id_proveedor AS id_prov, c.exclusivo AS exc, c.cancelacion AS cancel, c.razon_cierre AS razon, r.fecha_renovacion AS renovacion 
            FROM rdj_detalles_contratos dc, rdj_proveedores pv, rdj_contratos c, rdj_renovaciones r
            WHERE dc.fecha_apertura=? AND dc.id_productor=? AND dc.id_proveedor=pv.id AND c.fecha_apertura=dc.fecha_apertura AND r.id_proveedor=dc.id_proveedor AND c.fecha_apertura=r.fecha_apertura      
            GROUP BY dc.fecha_apertura, pv.nombre, dc.id_proveedor, c.exclusivo, c.cancelacion, c.razon_cierre, r.fecha_renovacion ORDER BY r.fecha_renovacion"
        ),[$fecha,$id_prod]);  

        foreach($detallesRenovado as $renovado){
            $i=0;
            foreach($detallesRenovado as $contrato){
                if($contrato->fecha == $renovado->fecha && $renovado->renovacion > $contrato->renovacion){
                    unset($detallesRenovado[$i]);
                }
                $i++;
            }
            
            $detallesRenovado=array_values($detallesRenovado);
        }

        // dd($detallesRenovado);

        $i=0;
        foreach($detallesRenovado as $contrato){
            
            foreach($detallesRenovado as $renovado){
                if($contrato->fecha==$renovado->fecha && $contrato->renovacion<$renovado->renovacion){
                    unset($detallesRenovado[$i]);
                }
            }
            $i++;
            $detallesRenovado=array_values($detallesRenovado);
        }

        $k=false;
        
        if($detallesRenovado==[]){
            $flag=true;
            $detalles=$detallesNoRenovado;
            if(Carbon::parse($detalles[0]->fecha)->addYear(1) < Carbon::now() && $detalles[0]->cancel==false){  
                $k=true;
            }
        }else{
            $flag=false;
            $detalles=$detallesRenovado;
            if(Carbon::parse($detalles[0]->renovacion)->addYear(1) < Carbon::now() && $detalles[0]->cancel==false){  
                $k=true;
            }
        }

        $i=false;
        foreach($contratosEspera as $espera){
            if($espera->fecha==$detalles[0]->fecha){
                $i=true;
            }
        }

        $ingredientes_esencia=DB::select(DB::raw(
            "SELECT dc.cas_ing_esencia AS i_cas, i.cas_ing_esencia AS cas, i.nombre AS i_nombre, i.naturaleza AS naturaleza, dc.descuento AS descuento 
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
            "SELECT dc.cas_otro_ing AS cas, dc.cas_otro_ing AS o_cas, o.nombre AS o_nombre, dc.descuento AS descuento 
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

        return view('productores.contratos.detalle-contrato',[
            'id_prod' => $id_prod,
            'id_prov' => $id_prov,
            'i' => $i,
            'k' => $k,
            'flag' => $flag,
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

    public function renovarContrato ($id_prod,$id_prov,$fecha) {

        $renovacion=DB::select(DB::raw(
            "SELECT r.fecha_renovacion AS fecha, r.fecha_renovacion AS renovacion 
            FROM rdj_renovaciones r
            WHERE r.fecha_apertura=? AND r.id_productor=? AND r.id_proveedor=?      
            GROUP BY r.fecha_renovacion"
        ),[$fecha,$id_prod,$id_prov]);

        
        foreach($renovacion as $contrato){
            $i=0;
            foreach($renovacion as $renovado){
                if($contrato->renovacion<$renovado->renovacion){
                    unset($renovacion[$i]);
                }
            }
            $i++;
            $renovacion=array_values($renovacion);
        }

        if($renovacion!=[]){
            $fecha_renovacion=Carbon::parse($renovacion[0]->fecha)->addYear(1);
        }else{
            $fecha_renovacion=Carbon::parse($fecha)->addYear(1);
        }

        DB::insert(DB::raw(
            "INSERT INTO rdj_renovaciones(id,fecha_apertura,id_proveedor,id_productor,fecha_renovacion) VALUES
            (nextval('rdj_renovacion_sec'),?,?,?,?);"),[$fecha,$id_prov,$id_prod,$fecha_renovacion]);

        DB::update(DB::raw(
            "UPDATE rdj_contratos 
            SET cancelacion=NULL
            WHERE id_productor=? AND fecha_apertura=?"),[$id_prod,$fecha]);

        return redirect('/productor/'.$id_prod.'/contratos');

    }

    /* Proveedores */

    public function verContratosPv ($id_prov) {


        $contratosVigentes=DB::select(DB::raw(
            "SELECT c.fecha_apertura AS fecha, pd.nombre AS prod, c.id_productor AS id_prod 
            FROM rdj_contratos c, rdj_productores pd, rdj_detalles_contratos dc, rdj_proveedores pv
            WHERE c.id_proveedor=? AND c.cancelacion=false AND pd.id=c.id_productor 
            GROUP BY c.fecha_apertura, pd.nombre, c.id_productor ORDER BY c.fecha_apertura"
        ),[$id_prov]);

        $contratosRenovados=DB::select(DB::raw(
            "SELECT c.fecha_apertura AS fecha, pd.nombre AS prod, c.id_productor AS id_prod, r.fecha_renovacion AS renovacion 
            FROM rdj_contratos c, rdj_productores pd, rdj_renovaciones r
            WHERE c.id_productor=pd.id AND c.id_proveedor=? AND r.fecha_apertura=c.fecha_apertura AND c.cancelacion=false   
            GROUP BY c.id_productor, c.fecha_apertura, pd.nombre, r.fecha_renovacion ORDER BY c.fecha_apertura"
        ),[$id_prov]);

        $contratosNoRenovados=[];

        if($contratosRenovados==[]){
            $contratosNoRenovados=$contratosVigentes;
        }else{
            foreach($contratosVigentes as $contrato){
                $noRenovado=[];
                foreach($contratosRenovados as $renovado){
                    if($contrato->fecha==$renovado->fecha){
                        $noRenovado=null;
                        break;
                    }else{
                        $noRenovado=$contrato;
                    }
                }
                if($noRenovado)
                    array_push($contratosNoRenovados,$noRenovado);
            }
        }

        foreach($contratosRenovados as $renovado){
            $i=0;
            foreach($contratosRenovados as $contrato){
                if($contrato->fecha == $renovado->fecha && $renovado->renovacion > $contrato->renovacion){
                    unset($contratosRenovados[$i]);
                }
                $i++;
            }
            
            $contratosRenovados=array_values($contratosRenovados);
        }

        // dd($detallesRenovado);

        foreach($contratosNoRenovados as $noRenovado){
            $i=0;
            foreach($contratosVigentes as $contrato){
                if($contrato->fecha == $noRenovado->fecha && Carbon::parse($contrato->fecha)->addYear(1) < Carbon::now()){
                    unset($contratosVigentes[$i]);
                }
                $i++;
            }
            
            $contratosVigentes=array_values($contratosVigentes);
        }

        foreach($contratosRenovados as $renovado){
            $i=0;
            foreach($contratosVigentes as $contrato){
                if($contrato->fecha == $renovado->fecha && Carbon::parse($renovado->renovacion)->addYear(1) < Carbon::now()){
                    unset($contratosVigentes[$i]);
                }
                $i++;
            }
            
            $contratosVigentes=array_values($contratosVigentes);
        }

        $contratosSolicitud=DB::select(DB::raw(
            "SELECT c.fecha_apertura AS fecha, pd.nombre AS prod, c.id_productor AS id_prod 
            FROM rdj_contratos c, rdj_proveedores pv, rdj_productores pd
            WHERE c.id_proveedor=? AND c.cancelacion IS NULL AND pd.id=c.id_productor AND pv.id=c.id_proveedor
            ORDER BY c.fecha_apertura"
        ),[$id_prov]);

        $contratosSolicitudRenovacion=DB::select(DB::raw(
            "SELECT r.fecha_apertura AS fecha, pd.nombre AS prod, c.id_productor AS id_prod, r.fecha_renovacion AS renovacion
            FROM rdj_contratos c, rdj_proveedores pv, rdj_productores pd, rdj_renovaciones r
            WHERE c.id_proveedor=? AND c.cancelacion IS NULL AND pd.id=c.id_productor AND pv.id=c.id_proveedor AND c.fecha_apertura=r.fecha_apertura AND c.id_proveedor=r.id_proveedor
            ORDER BY c.fecha_apertura"
        ),[$id_prov]);

        // dd($contratosSolicitudRenovacion);

        $i=0;
        foreach($contratosSolicitud as $solucitud){
            foreach($contratosSolicitudRenovacion as $renovacion){
                if($solucitud->fecha==$renovacion->fecha){
                    unset($contratosSolicitud[$i]);
                }
            }
            $i++;
        }

        $i=0;
        foreach($contratosSolicitudRenovacion as $solucitud){
            
            foreach($contratosSolicitudRenovacion as $renovacion){
                if($solucitud->fecha==$renovacion->fecha && $solucitud->renovacion<$renovacion->renovacion){
                    unset($contratosSolicitudRenovacion[$i]);
                }
            }
            $i++;
        }

        // dd($contratosSolicitudRenovacion);

        return view('proveedores.contratos.ver-contratos',[
            'id_prov' => $id_prov,
            'contratosVigentes' => $contratosVigentes,
            'contratosSolicitud' => $contratosSolicitud,
            'contratosSolicitudRenovacion' => $contratosSolicitudRenovacion,
        ]);

    }

    public function confirmarContratoPv ($id_prov, $id_prod, $fecha){

        $detallesNoRenovados=DB::select(DB::raw(
            "SELECT dc.fecha_apertura AS fecha, pd.nombre AS prod, dc.id_productor AS id_prod, c.exclusivo AS exc, c.cancelacion AS cancelacion 
            FROM rdj_detalles_contratos dc, rdj_productores pd, rdj_contratos c
            WHERE dc.fecha_apertura=? AND dc.id_proveedor=? AND dc.id_productor=pd.id AND c.fecha_apertura=dc.fecha_apertura      
            "
        ),[$fecha,$id_prov]);

        $detallesRenovados=DB::select(DB::raw(
            "SELECT dc.fecha_apertura AS fecha, pd.nombre AS prod, dc.id_productor AS id_prod, c.exclusivo AS exc, r.fecha_renovacion AS renovacion, c.cancelacion AS cancelacion  
            FROM rdj_detalles_contratos dc, rdj_productores pd, rdj_contratos c, rdj_renovaciones r
            WHERE dc.fecha_apertura=? AND dc.id_proveedor=? AND dc.id_productor=pd.id AND c.fecha_apertura=dc.fecha_apertura AND c.fecha_apertura=r.fecha_apertura AND r.id_proveedor=dc.id_proveedor      
            "
        ),[$fecha,$id_prov]);

        if($detallesRenovados==[]){
            $flag=false;
            $detalles=$detallesNoRenovados;
        }else{
            $flag=true;
            $detalles=$detallesRenovados;
        }

        $ingredientes_esencia=DB::select(DB::raw(
            "SELECT dc.cas_ing_esencia AS cas, dc.cas_ing_esencia AS i_cas, i.nombre AS i_nombre, i.naturaleza AS naturaleza, dc.descuento AS descuento  
            FROM rdj_detalles_contratos dc, rdj_productores pd, rdj_ingredientes_esencias i, rdj_contratos c
            WHERE dc.fecha_apertura=? AND dc.id_proveedor=? AND dc.id_productor=pd.id AND dc.cas_ing_esencia=i.cas_ing_esencia AND c.fecha_apertura=dc.fecha_apertura     
            "
        ),[$fecha,$id_prov]);

        $otros_ingredientes=DB::select(DB::raw(
            "SELECT dc.cas_otro_ing AS cas, dc.cas_otro_ing AS o_cas, o.nombre AS o_nombre, dc.descuento AS descuento 
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

        return view('proveedores.contratos.confirmar-contrato',[
            'id_prod' => $id_prod,
            'id_prov' => $id_prov,
            'fecha' => $fecha,
            'detalles' => $detalles,
            'flag' => $flag,
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

        return redirect('/proveedor/'.$id_prov.'/contratos'); 
    }

    public function rechazarContratoPv ($id_prov, $fecha) {

        DB::delete(DB::raw(
            "DELETE FROM rdj_metodos_contratos
            WHERE fecha_cont=? AND id_proveedor=?"
        ),[$fecha,$id_prov]);

        DB::delete(DB::raw(
            "DELETE FROM rdj_detalles_contratos
            WHERE fecha_apertura=? AND id_proveedor=?"
        ),[$fecha,$id_prov]);

        DB::delete(DB::raw(
            "DELETE FROM rdj_contratos
            WHERE fecha_apertura=? AND id_proveedor=?"
        ),[$fecha,$id_prov]);

        return redirect('/proveedor/'.$id_prov.'/contratos'); 
    }

    public function renovarContratoPv (Request $request,$id_prov, $fecha) {
        
        DB::update(DB::raw(
            "UPDATE rdj_contratos 
            SET cancelacion=FALSE
            WHERE id_proveedor=? AND fecha_apertura=?"),[$id_prov,$fecha]);

        return redirect('/proveedor/'.$id_prov.'/contratos'); 

    }

    public function rechazarRenovacionContratoPv ($id_prov, $fecha) {

        $detallesRenovados=DB::select(DB::raw(
            "SELECT dc.fecha_apertura AS fecha, pd.nombre AS prod, dc.id_productor AS id_prod, c.exclusivo AS exc, r.fecha_renovacion AS renovacion, c.cancelacion AS cancelacion  
            FROM rdj_detalles_contratos dc, rdj_productores pd, rdj_contratos c, rdj_renovaciones r
            WHERE dc.fecha_apertura=? AND dc.id_proveedor=? AND dc.id_productor=pd.id AND c.fecha_apertura=dc.fecha_apertura AND c.fecha_apertura=r.fecha_apertura AND r.id_proveedor=dc.id_proveedor      
            "
        ),[$fecha,$id_prov]);

        $i=0;
        foreach($detallesRenovados as $contrato){
            
            foreach($detallesRenovados as $renovado){
                if($contrato->fecha==$renovado->fecha && $contrato->renovacion<$renovado->renovacion){
                    unset($detallesRenovados[$i]);
                }
            }
            $i++;
            $detallesRenovados=array_values($detallesRenovados);
        }

        DB::update(DB::raw(
            "UPDATE rdj_contratos 
            SET cancelacion=FALSE
            WHERE id_proveedor=? AND fecha_apertura=?"),[$id_prov,$fecha]);

        DB::delete(DB::raw(
            "DELETE FROM rdj_renovaciones
            WHERE fecha_renovacion=? AND id_proveedor=?"
        ),[$detallesRenovados[0]->renovacion,$id_prov]);

        return redirect('/proveedor/'.$id_prov.'/contratos'); 
    }

    public function detalleContratoPv ($id_prov, $id_prod,$fecha){

        $detallesNoRenovados=DB::select(DB::raw(
            "SELECT dc.fecha_apertura AS fecha, pd.nombre AS prod, dc.id_productor AS id_prod, c.exclusivo AS exc, c.cancelacion AS cancelacion 
            FROM rdj_detalles_contratos dc, rdj_productores pd, rdj_contratos c
            WHERE dc.fecha_apertura=? AND dc.id_proveedor=? AND dc.id_productor=pd.id AND c.fecha_apertura=dc.fecha_apertura      
            "
        ),[$fecha,$id_prov]);

        $detallesRenovados=DB::select(DB::raw(
            "SELECT dc.fecha_apertura AS fecha, pd.nombre AS prod, dc.id_productor AS id_prod, c.exclusivo AS exc, r.fecha_renovacion AS renovacion, c.cancelacion AS cancelacion  
            FROM rdj_detalles_contratos dc, rdj_productores pd, rdj_contratos c, rdj_renovaciones r
            WHERE dc.fecha_apertura=? AND dc.id_proveedor=? AND dc.id_productor=pd.id AND c.fecha_apertura=dc.fecha_apertura AND c.fecha_apertura=r.fecha_apertura AND r.id_proveedor=dc.id_proveedor      
            "
        ),[$fecha,$id_prov]);

        foreach($detallesRenovados as $renovado){
            $i=0;
            foreach($detallesRenovados as $contrato){
                if($contrato->fecha == $renovado->fecha && $renovado->renovacion > $contrato->renovacion){
                    unset($detallesRenovados[$i]);
                }
                $i++;
            }
            
            $detallesRenovados=array_values($detallesRenovados);
        }

        // dd($detallesRenovado);

        if($detallesRenovados==[]){
            $flag=false;
            $detalles=$detallesNoRenovados;
        }else{
            $flag=true;
            $detalles=$detallesRenovados;
        }
        
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
            "SELECT dc.cas_otro_ing AS cas, dc.cas_otro_ing AS o_cas, o.nombre AS o_nombre, dc.descuento AS descuento 
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
            'flag' => $flag,
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
