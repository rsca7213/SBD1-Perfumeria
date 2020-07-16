<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class EvaluacionesController extends Controller
{
    // Devuelve la vista para ver evaluaciones
    public function verEvaluaciones ($id_prod) {
        return view('productores.evaluaciones.ver-evs', [
            'id_prod' => $id_prod
        ]);
    }

    //Devuelve la vista para realizar evaluaciones
    public function realizarEvaluacion ($id_prod) {
        return view('productores.evaluaciones.realizar-evs', [
            'id_prod' => $id_prod
        ]);
    }

    /* Devuelve toda la data necesaria para que el productor escoga los 
       proveedores a evaluar en una evaluacion inicial */
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

        /* juntamos ambos queries para enviar la data final */
        foreach ($proveedores as $proveedor) {
            $proveedor->paises = [];
            $proveedor->memb = date("d/m/Y", strtotime($proveedor->memb));
            foreach($prov_pais as $pais) {
                if($pais->id_proveedor == $proveedor->idp) {
                    array_push($proveedor->paises,$pais->pais);
                }
            }
        } 

        /* Devolvemos la data requerida para la seleccion de proveedores en una
           evaluacion inicial */
        return response([$proveedores], 200);

    }

    // Funcion encargada de buscar toda la data necesaria para evaluaciones iniciales 
    public function dataProveedoresInicial (Request $request, $id_prod) {

        //Busqueda de todos los paises donde quiere recibir el productor para filtrar los metodos de envio
        $paises_prod = DB::select(DB::raw("SELECT id_pais FROM rdj_productores_paises
        WHERE id_productor=? ORDER BY id_pais"),[$id_prod]);
        
        $id_provs = $request->input('provs'); //Se buscan los ids de proveedores solicitados

        $provs = []; //Variable donde se almacenan los datos a responder de la solicitud HTTP

        // Para cada id de proveedor que se recibe
        foreach($id_provs as $prov) {

            $aux = []; //Variable que ira almacenando los queries hechos
            
            //Busqueda de información basica del proveedor
            $query = DB::select(
                DB::raw("SELECT pr.id AS idp, pr.nombre AS prov, pa.nombre AS pais FROM 
                    rdj_paises pa, rdj_proveedores pr WHERE pa.id=pr.id_pais AND pr.id=?"),
                    [$prov]);

            //Almacenamiento de la info basica en aux
            $aux["idp"] = $query[0]->idp;
            $aux["prov"] = $query[0]->prov;
            $aux["pais"] = $query[0]->pais;
            
            //Busqueda de los metodos de pago del proveedor
            $query = DB::select(
                DB::raw("SELECT CASE WHEN pa.tipo='c' THEN 'Cuota única' WHEN pa.tipo='p'
                THEN 'Por cuotas' END AS tipo, pa.num_cuotas AS numc, pa.porcentaje AS porc,
                pa.meses AS meses, pa.id FROM rdj_metodos_pagos pa WHERE pa.id_proveedor=?"),
                [$aux["idp"]]);
            
            //Almacenamiento de arreglo de metodos de pagos del proveedor
            $aux["pagos"] = $query;

            //Para los metodos de envio, creamos un arreglo ya que pueden ser varios
            $aux["envios"] = [];
            foreach($paises_prod as $pais) { //Para cada pais del productor buscamos si hay metodo de envio

                $query = DB::select(
                    DB::raw("SELECT e.duracion, e.precio, e.id AS id_envio, p.id AS id_pais, p.nombre AS pais,
                    CASE WHEN e.tipo='t' THEN 'Terrestre' WHEN e.tipo='a' THEN 'Aéreo'
                    WHEN e.tipo='m' THEN 'Marítimo' END AS tipo FROM rdj_paises p,
                    rdj_metodos_envios e WHERE e.id_pais=? AND e.id_proveedor=?
                    AND e.id_pais=p.id ORDER BY e.id_pais"),[$pais->id_pais, $aux["idp"]]);

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
                        array_push($aux["envios"],$metodo);
                    }
                }
            }

            //Para los detalles o modificadores de los metodos de envios conseguidos
            for($i = 0; $i <= sizeof($aux["envios"]) - 1; $i++) {

                //Busqueda de los detalles de un metodo de envio
                $query = DB::select(
                    DB::raw("SELECT d.id, d.nombre AS det, d.mod_precio AS precio, d.mod_duracion AS duracion
                    FROM rdj_detalles_metodos_envios d WHERE d.id_envio=? AND d.id_proveedor=?
                    AND d.id_pais=?"),[$aux["envios"][$i]["id_envio"], $prov, $aux["envios"][$i]["id_pais"]]
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
                        array_push($aux["envios"][$i]["detalles"],$detalle);
                    }
                }          

            }

            //Agregamos toda la data buscada del proveedor al arreglo final
            array_push($provs,$aux);
        }

        /* Busqueda de formula actual activa para evaluaciones iniciales del productor */
        $formInicial = DB::select(DB::raw("SELECT c.nombre, h.peso, MAX(h.fecha_inicio) AS fecha 
        FROM rdj_criterios c, rdj_hist_formulas h, rdj_productores p 
        WHERE c.id=h.id_criterio AND h.id_productor=? AND h.tipo='i' AND h.fecha_fin IS NULL
        GROUP BY c.id, c.nombre, c.descripcion, h.peso, h.id_productor ORDER BY c.id"),[$id_prod]);

        /* Busqueda de escala actual activa del productor */
        $escala = DB::select(DB::raw("SELECT MAX(e.fecha_inicio) AS fecha,
        e.rango_inicio AS ri, e.rango_fin AS rf FROM rdj_escalas e, rdj_productores p
        WHERE e.id_productor=? AND e.fecha_fin IS NULL
        GROUP BY e.rango_inicio, e.rango_fin;"),[$id_prod]);

        //Devolvemos a la interfaz la data necesaria para continuar
        return response([$provs, $formInicial, $escala[0]],200);
    }
}
