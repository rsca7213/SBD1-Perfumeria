<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

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

    public function crearFormulaInicial ($id_prod) {
        
        $formInicial = DB::select(DB::raw("SELECT c.nombre, c.descripcion AS desc, h.peso, MAX(h.fecha_inicio) AS fecha 
        FROM rdj_criterios c, rdj_hist_formulas h, rdj_productores p 
        WHERE c.id=h.id_criterio AND h.id_productor=? AND h.tipo='i' AND h.fecha_fin IS NULL
        GROUP BY c.id, c.nombre, c.descripcion, h.peso, h.id_productor ORDER BY c.id"),[$id_prod]);

        /* valida que no exista una formula inicial activa usando el query de arriba */
        if(sizeof($formInicial) == 0)
        return view('productores.formulas.ce-formula-i',[
            'id_prod' => $id_prod
        ]);
        
        else return redirect ('productor/'.$id_prod.'/formulas');
    }

    public function insertFormulaInicial (Request $request, $id_prod) {
        /* validacion server-side */
        $data = $request->validate([
            'ubicacion' => 'numeric|required|max:100|min:0',
            'pagos' => 'numeric|required|max:100|min:0',
            'envios' => 'numeric|required|max:100|min:0',
            'exito' => 'numeric|required|max:100|min:0' 
        ]);
        $time = Carbon::now()->toDateTimeString();
        /* mas validacion server-side */
        if($data['ubicacion'] + $data['pagos'] + $data['envios'] > 100) return back();
        else {
            /* insert de la formula */
            DB::insert(DB::raw("INSERT INTO rdj_hist_formulas (fecha_inicio, id_criterio, id_productor, peso, tipo) VALUES
            (?,1,?,?,'i'),(?,2,?,?,'i'),(?,3,?,?,'i'),(?,5,?,?,'i')"),[$time,$id_prod,$data['ubicacion'],
            $time,$id_prod,$data['pagos'],$time,$id_prod,$data['envios'],$time,$id_prod,$data['exito']]);
            return redirect ('productor/'.$id_prod.'/formulas');
        }

    }
}
