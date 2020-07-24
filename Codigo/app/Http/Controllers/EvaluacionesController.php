<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class EvaluacionesController extends Controller
{

    // Devuelve la vista para ver evaluaciones
    public function verEvaluaciones ($id_prod) {
        return view('productores.evaluaciones.ver-evs', [
            'id_prod' => $id_prod
        ]);
    }

    /* Devuelve los resultados de evaluaciones realizadas para un proveedor
       llamada por axios y vue */
    public function verResultados ($id_prod) {
        $time = Carbon::now()->toDateTimeString();

        /* Buscamos los resultados de evaluaciones que ha realizado el productor */
        $resultados = DB::select(
                        DB::raw("SELECT r.fecha, p.nombre AS prov, 
                        r.resultado AS res,CASE WHEN r.tipo='i' THEN 'Inicial' WHEN r.tipo='a' 
                        THEN 'Anual' END AS tipo FROM rdj_resultados r, rdj_proveedores p WHERE 
                        r.id_productor=? AND r.id_proveedor=p.id ORDER BY r.fecha DESC"),[$id_prod]);

        foreach($resultados as $res) {
            if($res->tipo == "Inicial") {
               /* Buscamos la formula de evaluacion inicial, en especifico el criterio de exito de la formula */
               $exito = DB::select(DB::raw("SELECT f.peso FROM rdj_hist_formulas f WHERE f.id_criterio=5
               AND f.id_productor=? AND f.tipo='i' AND f.fecha_inicio < ? AND COALESCE(f.fecha_fin,?) > ?"),
               [$id_prod,$res->fecha,$time,$res->fecha]); 

               $res->exito = $exito[0]->peso;
            }
            else {
                /* Buscamos la formula de evaluacion inicial, en especifico el criterio de exito de la formula */
               $exito = DB::select(DB::raw("SELECT f.peso FROM rdj_hist_formulas f WHERE f.id_criterio=5
               AND f.id_productor=? AND f.tipo='a' AND f.fecha_inicio < ? AND COALESCE(f.fecha_fin,?) > ?"),
               [$id_prod,$res->fecha,$time,$res->fecha]); 

               if($exito !=[]) $res->exito = $exito[0]->peso;
            }
            $res->fecha = date("d/m/Y", strtotime($res->fecha));
        }

        return response([$resultados], 200);
    }

    //Devuelve la vista para realizar evaluaciones
    public function realizarEvaluacion ($id_prod) {
        return view('productores.evaluaciones.realizar-evs', [
            'id_prod' => $id_prod
        ]);
    }

    /* Funcion que es llamada al cargar la pagina de realizar evaluaciones
       que le pide a laravel que devuelva las formulas actuales del productor
       junto con su escala, si no existe laravel devolverá un arreglo vacio */
    public function buscarFormulasActuales ($id_prod) {

        /* Buscamos la formula inicial del productor */
        $formInicial = DB::select(
                        DB::raw("SELECT c.nombre, h.peso, MAX(h.fecha_inicio) AS fecha 
                          FROM rdj_criterios c, rdj_hist_formulas h, rdj_productores p 
                          WHERE c.id=h.id_criterio AND h.id_productor=? AND h.tipo='i' 
                          AND h.fecha_fin IS NULL GROUP BY c.id, c.nombre, c.descripcion, 
                          h.peso, h.id_productor ORDER BY c.id"),
                       [$id_prod]);

        /* Buscamos la formula anual del productor */
        $formAnual = DB::select(
                      DB::raw("SELECT c.nombre, h.peso, MAX(h.fecha_inicio) AS fecha 
                         FROM rdj_criterios c, rdj_hist_formulas h, rdj_productores p 
                         WHERE c.id=h.id_criterio AND h.id_productor=? AND h.tipo='a' 
                         AND h.fecha_fin IS NULL GROUP BY c.id, c.nombre, c.descripcion, 
                         h.peso, h.id_productor ORDER BY c.id"),
                     [$id_prod]);

        /* Buscamos la escala actual del productor */
        $escala = DB::select(
                   DB::raw("SELECT MAX(e.fecha_inicio) AS fecha,
                      e.rango_inicio AS ri, e.rango_fin AS rf FROM rdj_escalas e, rdj_productores p
                      WHERE e.id_productor=? AND e.fecha_fin IS NULL
                      GROUP BY e.rango_inicio, e.rango_fin;"),
                  [$id_prod]);
        
        $aux = [];
        if(sizeof($escala) > 0) {
            $aux["fecha"] = $escala[0]->fecha;
            $aux["ri"] = $escala[0]->ri;
            $aux["rf"] = $escala[0]->rf;
        }

        $escala = $aux;

        return response([$formInicial,$formAnual,$escala],200);
    }

    /* Devuelve toda la data necesaria para que el productor escoga al
       proveedor a evaluar en una evaluacion inicial */
    public function dataEvaluacionInicial ($id_prod) {
        
        /* Buscamos proveedores que cumplan con los filtros para una evaluacion inicial
           Estos son: Ser miembro activo IFRA, tener metodos de envio que envien a los
                      paises donde recibe el productor */
        $proveedores = DB::select(DB::raw(
        "SELECT prov.id AS idp, prov.nombre AS prov, MAX(m.fecha_inicio) AS memb,
        CASE WHEN m.tipo='p' THEN 'Primario' WHEN m.tipo='s' THEN 'Secundario' ELSE 
        'De una asociacion' END AS tipo, a.nombre AS asoc FROM rdj_miembros_ifra m, 
        rdj_proveedores prov  LEFT OUTER JOIN rdj_asociaciones a ON prov.id_asociacion=a.id 
        WHERE m.id_proveedor=prov.id AND m.fecha_fin IS NULL AND prov.id IN(SELECT me.id_proveedor
        AS pais FROM rdj_proveedores prov,  rdj_metodos_envios me, rdj_paises pais 
        WHERE prov.id=me.id_proveedor AND pais.id=me.id_pais AND me.id_pais 
        IN(SELECT id_pais FROM rdj_productores_paises WHERE id_productor=?)) 
        GROUP BY prov.id, prov.nombre, m.tipo, a.nombre ORDER BY prov.id;"),[$id_prod]);

        /* Buscamos los paises a donde envia el proveedor que coincidan con los
           de recepcion del productor */
        $prov_pais = DB::select(DB::raw("SELECT DISTINCT me.id_proveedor, pais.nombre AS pais FROM rdj_proveedores prov, 
        rdj_metodos_envios me, rdj_paises pais WHERE prov.id=me.id_proveedor AND pais.id=me.id_pais
        AND me.id_pais IN(SELECT id_pais FROM rdj_productores_paises WHERE id_productor=?)"),[$id_prod]);

        /* juntamos ambos queries para enviar la data final y agregamos
           los ingredientes tipo esencia y tipo otro de c/u */
        foreach ($proveedores as $proveedor) {

            $proveedor->paises = [];
            $proveedor->memb = date("d/m/Y", strtotime($proveedor->memb));
            foreach($prov_pais as $pais) {
                if($pais->id_proveedor == $proveedor->idp) {
                    array_push($proveedor->paises,$pais->pais);
                }
            }
        
        } 

        /* Devolvemos la data requerida para la seleccion de un proveedor en una
           evaluacion inicial */
        return response([$proveedores], 200);

    }

    /* Devuelve toda la data necesaria para que el productor escoga al 
       proveedor a evaluar en una evaluacion anual */
    public function dataEvaluacionAnual ($id_prod) {
        
        $time = Carbon::now()->toDateTimeString();
        /* Buscamos en la base de datos los contratos del productor 
           que no esten cancelados para luego ir filtrando aun mas */
        $contratos = DB::select(DB::raw("SELECT c.fecha_apertura AS fecha, c.id_proveedor AS idp,
                                p.nombre AS prov, CASE WHEN c.exclusivo='t' THEN 'exclusivo'
                                WHEN c.exclusivo='f' THEN 'no exclusivo' END AS ex FROM rdj_contratos c,
                                rdj_proveedores p WHERE c.id_productor=? AND c.id_proveedor=p.id
                                AND COALESCE(c.cancelacion,'f') != 't' ORDER BY c.fecha_apertura ASC"),[$id_prod]);

        /* Realizamos una separacion del arreglo que nos devuelve postgres, aquellos contratos en los cuales
           la fecha de apertura no sea mayor a 1 año se colocan en contratos activos, sino se colocan en contratos
           con posible renovacion */

        $contActivos = [];
        $contPosibles = [];

        foreach($contratos as $cont) {
            
            $fecha = Carbon::createFromDate($cont->fecha);

            if ($fecha->diffInDays($time) > 365) {
                array_push($contPosibles,$cont);
            }

            else {
                $cont->renov = 0;
                $cont->fechaR = '';
                $cont->exp = 365 - $fecha->diffInDays($time);
                array_push($contActivos,$cont);
            }

        }


        $contActivosSinR = [];
        /* Si existiera algun contrato que fue renovado pero su fecha de apertura no se ha vencido, sera
           filtrado por este foreach */
        foreach($contActivos as $cont) {
            $query = DB::select(DB::raw("SELECT COUNT(*) FROM rdj_renovaciones WHERE id_proveedor=?
            AND id_productor=? AND fecha_apertura=? GROUP BY id_proveedor,id_productor,fecha_apertura"),
            [$cont->idp,$id_prod,$cont->fecha]);

            if(sizeof($query) == 0) array_push($contActivosSinR,$cont);
        }

        $contActivos = $contActivosSinR;

        /* Para los posibles, buscamos su renovacion mas reciente, si no tiene renovacion skippeamos, si tiene
           buscamos la cantidad de veces que se ha renovado con count y luego revisamos si la renovacion mas reciente
           no ha expirado tambien, si no ha expirado la agregamos a contratos activos, sino skippeamos */

        foreach($contPosibles as $cont) {

            $renovs = DB::select(DB::raw("SELECT MAX(r.fecha_renovacion) AS fecha FROM rdj_renovaciones r
            WHERE r.id_proveedor=? AND r.id_productor=? AND r.fecha_apertura=? GROUP BY r.id_proveedor,
            r.id_productor, r.fecha_apertura"),[$cont->idp,$id_prod,$cont->fecha]);

            if(sizeOf($renovs) == 0) continue;

            $count = DB::select(DB::raw("SELECT COUNT(*) FROM rdj_renovaciones r WHERE r.id_proveedor=?
            AND r.id_productor=? AND r.fecha_apertura=? GROUP BY r.id_proveedor, r.id_productor, 
            r.fecha_apertura"),[$cont->idp,$id_prod,$cont->fecha])[0]->count;

            $fecha = Carbon::createFromDate($renovs[0]->fecha);

            if ($fecha->diffInDays($time) <= 365) {
                $cont->fechaR = $renovs[0]->fecha;
                $cont->renov = $count;
                $cont->exp = 365 - $fecha->diffInDays($time);
                array_push($contActivos,$cont);
            }
        }

        /* Timestamp --> date y se eliminan aquellos contratos que no estan apunto de expirar (mas de 1 mes)*/

        $data = [];
        foreach($contActivos as $cont) {
            $cont->fecha_ap_ts = $cont->fecha;
            $cont->fecha = date("d/m/Y", strtotime($cont->fecha));
            $cont->fechaR = date("d/m/Y", strtotime($cont->fechaR));

            if ($cont->exp <= 31) array_push($data, $cont); 

        }

        return response([$data],200);
    }

    // Funcion encargada de buscar toda la data necesaria para evaluaciones iniciales 
    public function dataProveedoresInicial ($id_prod, $id_prov) {

        //Busqueda de todos los paises donde quiere recibir el productor para filtrar los metodos de envio
        $paises_prod = DB::select(DB::raw("SELECT id_pais FROM rdj_productores_paises
        WHERE id_productor=? ORDER BY id_pais"),[$id_prod]);

        $prov = []; //Variable donde se almacenan los datos a responder de la solicitud HTTP

        //Busqueda de información basica del proveedor
        $query = DB::select(
            DB::raw("SELECT pr.id AS idp, pr.nombre AS prov, pa.nombre AS pais FROM 
                rdj_paises pa, rdj_proveedores pr WHERE pa.id=pr.id_pais AND pr.id=?"),
                [$id_prov]);

        //Almacenamiento de la info basica en aux
        $prov["idp"] = $query[0]->idp;
        $prov["prov"] = $query[0]->prov;
        $prov["pais"] = $query[0]->pais;
            
        //Busqueda de los metodos de pago del proveedor
        $query = DB::select(
            DB::raw("SELECT CASE WHEN pa.tipo='c' THEN 'Cuota única' WHEN pa.tipo='p'
            THEN 'Por cuotas' END AS tipo, pa.num_cuotas AS numc, pa.porcentaje AS porc,
            pa.meses AS meses, pa.id FROM rdj_metodos_pagos pa WHERE pa.id_proveedor=?"),
            [$prov["idp"]]);
            
        //Almacenamiento de arreglo de metodos de pagos del proveedor
        $prov["pagos"] = $query;

        //Para los metodos de envio, creamos un arreglo ya que pueden ser varios
        $prov["envios"] = [];
        foreach($paises_prod as $pais) { //Para cada pais del productor buscamos si hay metodo de envio

            $query = DB::select(
                DB::raw("SELECT e.duracion, e.precio, e.id AS id_envio, p.id AS id_pais, p.nombre AS pais,
                CASE WHEN e.tipo='t' THEN 'Terrestre' WHEN e.tipo='a' THEN 'Aéreo'
                WHEN e.tipo='m' THEN 'Marítimo' END AS tipo FROM rdj_paises p,
                rdj_metodos_envios e WHERE e.id_pais=? AND e.id_proveedor=?
                AND e.id_pais=p.id ORDER BY e.id_pais"),[$pais->id_pais, $prov["idp"]]);

            //Agregamos al arreglo si conseguimos un metodo de envio para el pais
            //como pueden haber varios metodos de envio con el mismo pais necesitamos
            //hacer otro foreach
            if($query != []) {
                foreach($query as $met) {
                    $metodo = [];
                    $metodo["detalles"] = [];
                    $metodo["id_envio"] = $met->id_envio;
                    $metodo["id_pais"] = $met->id_pais;
                    $metodo["duracion"] = $met->duracion;
                    $metodo["precio"] = $met->precio;
                    $metodo["pais"] = $met->pais;
                    $metodo["tipo"] = $met->tipo;
                    array_push($prov["envios"],$metodo);
                }
            }
        }

        //Para los detalles o modificadores de los metodos de envios conseguidos
        for($i = 0; $i <= sizeof($prov["envios"]) - 1; $i++) {
            //Busqueda de los detalles de un metodo de envio
            $query = DB::select(
                DB::raw("SELECT d.id, d.nombre AS det, d.mod_precio AS precio, d.mod_duracion AS duracion
                FROM rdj_detalles_metodos_envios d WHERE d.id_envio=? AND d.id_proveedor=?
                AND d.id_pais=?"),[$prov["envios"][$i]["id_envio"], $id_prov, $prov["envios"][$i]["id_pais"]]
            );
            //Agregamos al arreglo si conseguimos un detalle de envio para el metodo
            //como pueden haber varios detalles de envio con el mismo metodo necesitamos
            //hacer otro foreach
            if($query != []) {
                foreach($query as $det) {
                    $detalle = [];
                    $detalle["id"] = $det->id;
                    $detalle["det"] = $det->det;
                    $detalle["precio"] = $det->precio;
                    $detalle["duracion"] = $det->duracion;
                    array_push($prov["envios"][$i]["detalles"],$detalle);
                }
            }          
        }

        /* Buscamos todas las esencias del proveedor y las guardamos */
        $prov["esencias"] = $query = DB::select(
            DB::raw("SELECT e.cas_ing_esencia AS cas, e.nombre AS ing, CASE WHEN
            e.naturaleza='n' THEN 'natural' WHEN e.naturaleza='s' THEN 'sintetica' END
            AS tipo FROM rdj_ingredientes_esencias e WHERE e.id_proveedor=? 
            ORDER BY e.cas_ing_esencia"), 
           [$id_prov]);
         
        /* Buscamos todos los otros ingredientes del proveedor y los guardamos */
        $prov["otros"] = $query = DB::select(
                    DB::raw("SELECT o.cas_otro_ing AS cas, o.nombre AS ing
                    FROM rdj_otros_ingredientes o WHERE o.id_proveedor=? 
                    ORDER BY o.cas_otro_ing"), 
                   [$id_prov]);

        /* Proceso que elimina ingredientes contratados exclusivamente de la lista de ingredientes disponibles */
        $ingsExclusivos = DB::select(DB::raw("SELECT dc.cas_ing_esencia AS cas_esen, dc.cas_otro_ing AS cas_otro 
                   FROM rdj_contratos c, rdj_detalles_contratos dc WHERE c.fecha_apertura=dc.fecha_apertura 
                   AND c.id_proveedor=dc.id_proveedor AND c.id_proveedor=? AND c.exclusivo=true AND c.cancelacion=false"),[$id_prov]);

        $esenFiltradas = [];
        $otrosFiltrados = [];

        foreach($prov["esencias"] as $esen) {
            $cond = false;
            foreach($ingsExclusivos as $ing) {
                if($ing->cas_esen != null && $esen->cas == $ing->cas_esen) 
                    $cond = true;
            }
            if($cond == false) array_push($esenFiltradas, $esen);
        }

        foreach($prov["otros"] as $otro) {
            $cond = false;
            foreach($ingsExclusivos as $ing) {
                if($ing->cas_otro != null && $otro->cas == $ing->cas_otro) 
                    $cond = true;
            }
            if($cond == false) array_push($otrosFiltrados, $otro);
        }

        $prov["esencias"] = $esenFiltradas;
        $prov["otros"] = $otrosFiltrados;
        
        /* Buscamos las presentaciones de cada esencia y las guardamos */
        foreach($prov["esencias"] as $esen) {
            $query = DB::select(
                      DB::raw("SELECT p.id, p.volumen AS vol, p.precio
                      FROM rdj_presents_ings_esencias p WHERE p.cas_ing_esencia=?
                      ORDER BY p.id"),
                     [$esen->cas]);
            $esen->cas = Controller::stringifyCas($esen->cas);
            $esen->pres = $query;
        }
    
        /* Buscamos las presentaciones de cada otro ing y las guardamos */
        foreach($prov["otros"] as $otro) {
            $query = DB::select(
                      DB::raw("SELECT p.id, p.volumen AS vol, p.precio
                      FROM rdj_present_otros_ings p WHERE p.cas_otro_ing=?
                      ORDER BY p.id"),
                     [$otro->cas]);
            $otro->cas = Controller::stringifyCas($otro->cas);
            $otro->pres = $query;
        }

    
        //Devolvemos a la interfaz la data necesaria para continuar
        return response([$prov],200);
    }

    // Funcion encargada de buscar toda la data necesaria para evaluaciones anuales
    public function dataProveedoresAnual ($id_prod, $id_prov, $fecha_ap_ts) {

        $prov = []; //Variable donde se almacenan los datos a responder de la solicitud HTTP

        //Busqueda de información basica del proveedor
        $query = DB::select(
            DB::raw("SELECT pr.id AS idp, pr.nombre AS prov FROM 
                rdj_proveedores pr WHERE pr.id=?"),
                [$id_prov]);

        //Almacenamiento de la info basica en aux
        $prov["idp"] = $query[0]->idp;
        $prov["prov"] = $query[0]->prov;
    

        $prov["fecha_ap_ts"] = $fecha_ap_ts;
        $prov["pedidosCump"] = 0;
        $prov["pedidosRec"] = 0;
    
        //Devolvemos a la interfaz la data necesaria para continuar
        return response([$prov],200);
    }

    /*Funcion que recibe el proveedor evaluado en una evalucion inicial, y tambien
      recibe los puntajes que obtuvo para almacenarlos en la bd */
    public function guardarInicial(Request $request, $id_prod) {
        //Pequeña validacion back end
        $data = $request->validate([
            'idp' => 'numeric|required',
            'inicUbic' => 'numeric|required',
            'inicPago' => 'numeric|required',
            'inicEnvio' => 'numeric|required'        
        ]);

        /* Buscamos la formula inicial del productor */
        $formInicial = DB::select(
            DB::raw("SELECT c.nombre, h.peso, MAX(h.fecha_inicio) AS fecha 
              FROM rdj_criterios c, rdj_hist_formulas h, rdj_productores p 
              WHERE c.id=h.id_criterio AND h.id_productor=? AND h.tipo='i' 
              AND h.fecha_fin IS NULL GROUP BY c.id, c.nombre, c.descripcion, 
              h.peso, h.id_productor ORDER BY c.id"),
           [$id_prod]);

        /* Buscamos la escala actual del productor */
        $escala = DB::select(
            DB::raw("SELECT MAX(e.fecha_inicio) AS fecha,
               e.rango_inicio AS ri, e.rango_fin AS rf FROM rdj_escalas e, rdj_productores p
               WHERE e.id_productor=? AND e.fecha_fin IS NULL
               GROUP BY e.rango_inicio, e.rango_fin;"),
           [$id_prod]);

        if(sizeof($escala) == 0 || sizeof($formInicial)  != 4) return back();
        else $escala = $escala[0];

        if(intval($escala->ri) != 0) {
            $escala->rf -= intval($escala->ri);
            $data['inicUbic'] -= intval($escala->ri);
            $data['inicPago'] -= intval($escala->ri);
            $data['inicEnvio'] -= intval($escala->ri);
            $escala->ri = 0;
        }
        
        $resultado = (($data['inicUbic'] * $formInicial[0]->peso) / ($escala->rf)) +
                     (($data['inicPago'] * $formInicial[1]->peso) / ($escala->rf)) +
                     (($data['inicEnvio'] * $formInicial[2]->peso) / ($escala->rf));

        $time = Carbon::now()->toDateTimeString();

        DB::insert(DB::raw("INSERT INTO rdj_resultados(fecha,id_productor,id_proveedor,resultado,tipo) VALUES
                   (?,?,?,?,'i')"),[$time,$id_prod,$data['idp'],$resultado]);

        $prov = DB::select(DB::raw("SELECT nombre FROM rdj_proveedores WHERE id=?"),[$data['idp']])[0]->nombre;

        $respuesta = [];
        $respuesta["res"] = $resultado;
        $respuesta["exito"] = $formInicial[3]->peso;
        $respuesta["id_prod"] = $id_prod;
        $respuesta["id_prov"] = $data['idp'];
        $respuesta["prov"] = $prov;

        return response([$respuesta],201);
    }

    /*Funcion que recibe el proveedor evaluado en una evalucion anual, y tambien
      recibe los puntajes que obtuvo para almacenarlos en la bd */
      public function guardarAnual(Request $request, $id_prod) {

        //Pequeña validacion back end
        $data = $request->validate([
            'idp' => 'numeric|required',
            'anualCump' => 'numeric|required',
            'fecha_ap_ts' => 'required'     
        ]);

        /* Buscamos la formula anual del productor */
        $formAnual = DB::select(
            DB::raw("SELECT c.nombre, h.peso, MAX(h.fecha_inicio) AS fecha 
              FROM rdj_criterios c, rdj_hist_formulas h, rdj_productores p 
              WHERE c.id=h.id_criterio AND h.id_productor=? AND h.tipo='a' 
              AND h.fecha_fin IS NULL GROUP BY c.id, c.nombre, c.descripcion, 
              h.peso, h.id_productor ORDER BY c.id"),
           [$id_prod]);

        /* Buscamos la escala actual del productor */
        $escala = DB::select(
            DB::raw("SELECT MAX(e.fecha_inicio) AS fecha,
               e.rango_inicio AS ri, e.rango_fin AS rf FROM rdj_escalas e, rdj_productores p
               WHERE e.id_productor=? AND e.fecha_fin IS NULL
               GROUP BY e.rango_inicio, e.rango_fin;"),
           [$id_prod]);

        if(sizeof($escala) == 0 || sizeof($formAnual)  == 0) return response('a',422);
        else $escala = $escala[0];

        if(intval($escala->ri) != 0) {
            $escala->rf -= intval($escala->ri);
            $data['anualCump'] -= intval($escala->ri);
            $escala->ri = 0;
        }
        
        $resultado = (($data['anualCump'] * $formAnual[0]->peso) / ($escala->rf));

        $time = Carbon::now()->toDateTimeString();

        DB::insert(DB::raw("INSERT INTO rdj_resultados(fecha,id_productor,id_proveedor,resultado,tipo) VALUES
                   (?,?,?,?,'a')"),[$time,$id_prod,$data['idp'],$resultado]);

        $prov = DB::select(DB::raw("SELECT nombre FROM rdj_proveedores WHERE id=?"),[$data['idp']])[0]->nombre;

        $ex = DB::SELECT(DB::raw("SELECT CASE WHEN c.exclusivo='t' THEN 'Exclusivo' ELSE 'No exclusivo' END
        AS ex FROM rdj_contratos c WHERE c.id_productor=? AND c.id_proveedor=? AND c.fecha_apertura=?"),[$id_prod, $data['idp'], $data['fecha_ap_ts']]);

        $renovs= DB::select(DB::raw("SELECT MAX(r.fecha_renovacion) AS fecha FROM rdj_renovaciones r
        WHERE r.id_proveedor=? AND r.id_productor=? AND r.fecha_apertura=? GROUP BY r.id_proveedor,
        r.id_productor, r.fecha_apertura"),[$data['idp'],$id_prod,$data['fecha_ap_ts']]);

        $respuesta = [];

        if(sizeof($renovs) == 0) {
            $respuesta["fechaR"] = null;
            $respuesta["renov"] = 0;
            $time = Carbon::now();
            $respuesta["exp"] = 365 - $time->diffInDays($data['fecha_ap_ts']);
        }

        else {
            $count = DB::select(DB::raw("SELECT COUNT(*) FROM rdj_renovaciones r WHERE r.id_proveedor=?
            AND r.id_productor=? AND r.fecha_apertura=? GROUP BY r.id_proveedor, r.id_productor, 
            r.fecha_apertura"),[$data['idp'],$id_prod,$data['fecha_ap_ts']]);
            $fecha = Carbon::createFromDate($renovs[0]->fecha);
            $respuesta["fechaR"] = date('d/m/Y', strtotime($renovs[0]->fecha));
            $respuesta["renov"] = $count[0]->count;
            $respuesta["exp"] = 365 - $fecha->diffInDays($time);
        }

        $respuesta["res"] = $resultado;
        $respuesta["exito"] = $formAnual[1]->peso;
        $respuesta["id_prod"] = $id_prod;
        $respuesta["id_prov"] = $data['idp'];
        $respuesta["prov"] = $prov;
        $respuesta["fecha_ap_ts"] = $data['fecha_ap_ts'];
        $respuesta["fechaA"] = date("d/m/Y", strtotime($data['fecha_ap_ts']));
        $respuesta["ex"] = $ex[0]->ex;

        return response([$respuesta],201);
    }
}
