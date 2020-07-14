<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class EvaluacionesController extends Controller
{
    public function verEvaluaciones ($id_prod) {
        return view('productores.evaluaciones.ver-evs', [
            'id_prod' => $id_prod
        ]);
    }

    public function realizarEvaluacion ($id_prod) {
        return view('productores.evaluaciones.realizar-evs', [
            'id_prod' => $id_prod
        ]);
    }

    public function dataEvaluacionInicial ($id_prod) {
        
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

        $prov_pais = DB::select(DB::raw("SELECT me.id_proveedor, pais.nombre AS pais FROM rdj_proveedores prov, 
        rdj_metodos_envios me, rdj_paises pais WHERE prov.id=me.id_proveedor AND pais.id=me.id_pais
        AND me.id_pais IN(SELECT id_pais FROM rdj_productores_paises WHERE id_productor=?)"),[$id_prod]);

        foreach ($proveedores as $proveedor) {
            $proveedor->paises = [];
            $proveedor->memb = date("d/m/Y", strtotime($proveedor->memb));
            foreach($prov_pais as $pais) {
                if($pais->id_proveedor == $proveedor->idp) {
                    array_push($proveedor->paises,$pais->pais);
                }
            }
        } 

        return response([$proveedores], 200);

    }
}
