<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class FormulasController extends Controller
{
    public function verFormulas ($id_prod) {

        /* Devuelve la formula inicial mas reciente del productor especifico que este activa */
        $formInicial = DB::select(DB::raw("SELECT c.nombre, c.descripcion AS desc, h.peso, MAX(h.fecha_inicio) AS fecha 
        FROM rdj_criterios c, rdj_hist_formulas h, rdj_productores p 
        WHERE c.id=h.id_criterio AND h.id_productor=? AND h.tipo='i' AND h.fecha_fin IS NULL
        GROUP BY c.id, c.nombre, c.descripcion, h.peso, h.id_productor ORDER BY c.id"),[$id_prod]);

        /* Devuelve la formula anual mas reciente del proveedor especifico que este activa */
        $formAnual = DB::select(DB::raw("SELECT c.nombre, c.descripcion AS desc, h.peso, MAX(h.fecha_inicio) AS fecha 
        FROM rdj_criterios c, rdj_hist_formulas h, rdj_productores p 
        WHERE c.id=h.id_criterio AND p.id=? AND h.tipo='a' AND h.fecha_fin IS NULL
        GROUP BY c.id, c.nombre, c.descripcion, h.peso, h.id_productor ORDER BY c.id;"),[$id_prod]);

        /* Devuelve la escala mas reciente del proveedor */
        $escala = DB::select(DB::raw("SELECT MAX(e.fecha_inicio) AS fecha, e.rango_inicio AS ri, e.rango_fin AS rf
        FROM rdj_escalas e, rdj_productores p
        WHERE e.id_productor=? AND e.fecha_fin IS NULL GROUP BY e.rango_inicio, e.rango_fin;"),[$id_prod]);

        return view('productores.formulas.ver-formulas', [
            'id_prod' => $id_prod,
            'form_inicial' => $formInicial,
            'form_anual' => $formAnual,
            'escala' => $escala
        ]);
    }
}
