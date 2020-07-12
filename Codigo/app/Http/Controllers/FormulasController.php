<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class FormulasController extends Controller
{
    /* para devolver la lista de formulas */
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

    /* para devolver la interfaz de creacion */
    public function crearFormulaInicial ($id_prod) {
        
        $formInicial = DB::select(DB::raw("SELECT c.nombre, c.descripcion AS desc, h.peso, MAX(h.fecha_inicio) AS fecha 
        FROM rdj_criterios c, rdj_hist_formulas h, rdj_productores p 
        WHERE c.id=h.id_criterio AND h.id_productor=? AND h.tipo='i' AND h.fecha_fin IS NULL
        GROUP BY c.id, c.nombre, c.descripcion, h.peso, h.id_productor ORDER BY c.id"),[$id_prod]);

        /* valida que no exista una formula inicial activa usando el query de arriba */
        if(sizeof($formInicial) == 0)
        return view('productores.formulas.c-formula-i',[
            'id_prod' => $id_prod
        ]);
        
        else return redirect ('productor/'.$id_prod.'/formulas');
    }

    /* para realizar el insert de una nueva formula */
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

    /* para devolver la interfaz de modificacion */
    public function editarFormulaInicial ($id_prod) {
        $formInicial = DB::select(DB::raw("SELECT c.nombre, c.descripcion AS desc, h.peso, MAX(h.fecha_inicio) AS fecha 
        FROM rdj_criterios c, rdj_hist_formulas h, rdj_productores p 
        WHERE c.id=h.id_criterio AND h.id_productor=? AND h.tipo='i' AND h.fecha_fin IS NULL
        GROUP BY c.id, c.nombre, c.descripcion, h.peso, h.id_productor ORDER BY c.id"),[$id_prod]);

        if(sizeof($formInicial) != 0)
        {
            $formInicialArr['ubicacion'] = $formInicial[0]->peso;
            $formInicialArr['pagos'] = $formInicial[1]->peso;
            $formInicialArr['envios'] = $formInicial[2]->peso;
            $formInicialArr['exito'] = $formInicial[3]->peso;
    
            /* valida que exista una formula inicial activa usando el query de arriba */
            if(sizeof($formInicial) != 0)
            return view('productores.formulas.e-formula-i',[
                'id_prod' => $id_prod,
                'formula' => $formInicialArr
            ]);
        }
        
        else return redirect ('productor/'.$id_prod.'/formulas');
    }

    /* para realizar el update desactivando la formula vieja
       y insertando una formula nueva */
    public function updateFormulaInicial (Request $request, $id_prod) {
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
            /* busca la formula actual y devuelve sus datos para poder asignar fecha_fin */
            $formInicial = DB::select(DB::raw("SELECT h.id_productor AS idp, MAX(h.fecha_inicio) AS fecha
            FROM rdj_criterios c, rdj_hist_formulas h, rdj_productores p 
            WHERE c.id=h.id_criterio AND h.id_productor=? AND h.tipo='i' AND h.fecha_fin IS NULL
            GROUP BY c.id, h.id_criterio, c.nombre, c.descripcion, h.peso, h.id_productor ORDER BY c.id"),[$id_prod]);

            /* desactivacion de la formula actual */
            DB::update(DB::raw("UPDATE rdj_hist_formulas SET fecha_fin = ? WHERE
            id_productor=? AND tipo='i' AND fecha_inicio=?"),[$time, $formInicial[0]->idp, $formInicial[0]->fecha]);

            /* insert de la formula nueva */
            DB::insert(DB::raw("INSERT INTO rdj_hist_formulas (fecha_inicio, id_criterio, id_productor, peso, tipo) VALUES
            (?,1,?,?,'i'),(?,2,?,?,'i'),(?,3,?,?,'i'),(?,5,?,?,'i')"),[$time,$id_prod,$data['ubicacion'],
            $time,$id_prod,$data['pagos'],$time,$id_prod,$data['envios'],$time,$id_prod,$data['exito']]);

            return redirect ('productor/'.$id_prod.'/formulas');
        }
    }

    /* para devolver la interfaz de creacion */
    public function crearFormulaAnual ($id_prod) {
        
        $formAnual = DB::select(DB::raw("SELECT c.nombre, c.descripcion AS desc, h.peso, MAX(h.fecha_inicio) AS fecha 
        FROM rdj_criterios c, rdj_hist_formulas h, rdj_productores p 
        WHERE c.id=h.id_criterio AND h.id_productor=? AND h.tipo='a' AND h.fecha_fin IS NULL
        GROUP BY c.id, c.nombre, c.descripcion, h.peso, h.id_productor ORDER BY c.id"),[$id_prod]);

        /* valida que no exista una formula inicial activa usando el query de arriba */
        if(sizeof($formAnual) == 0)
        return view('productores.formulas.c-formula-a',[
            'id_prod' => $id_prod
        ]);
        
        else return redirect ('productor/'.$id_prod.'/formulas');
    }

    /* para realizar el insert de una nueva formula anual */
    public function insertFormulaAnual (Request $request, $id_prod) {
        /* validacion server-side */
        $data = $request->validate([
            'ubicacion' => 'numeric|required|max:100|min:0',
            'pagos' => 'numeric|required|max:100|min:0',
            'envios' => 'numeric|required|max:100|min:0',
            'cumplim' => 'numeric|required|max:100|min:0',
            'exito' => 'numeric|required|max:100|min:0' 
        ]);
        $time = Carbon::now()->toDateTimeString();
        /* mas validacion server-side */
        if($data['ubicacion'] + $data['pagos'] + $data['envios'] + $data['cumplim'] > 100) return back();
        else {
            /* insert de la formula */
            DB::insert(DB::raw("INSERT INTO rdj_hist_formulas (fecha_inicio, id_criterio, id_productor, peso, tipo) VALUES
            (?,1,?,?,'a'),(?,2,?,?,'a'),(?,3,?,?,'a'),(?,4,?,?,'a'),(?,5,?,?,'a')"),[$time,$id_prod,$data['ubicacion'],
            $time,$id_prod,$data['pagos'],$time,$id_prod,$data['envios'],$time,$id_prod,$data['cumplim'],$time,$id_prod,$data['exito']]);

            return redirect ('productor/'.$id_prod.'/formulas');
        }

    }

    /* para devolver la interfaz de modificacion */
    public function editarFormulaAnual ($id_prod) {
        $formAnual = DB::select(DB::raw("SELECT c.nombre, c.descripcion AS desc, h.peso, MAX(h.fecha_inicio) AS fecha 
        FROM rdj_criterios c, rdj_hist_formulas h, rdj_productores p 
        WHERE c.id=h.id_criterio AND h.id_productor=? AND h.tipo='a' AND h.fecha_fin IS NULL
        GROUP BY c.id, c.nombre, c.descripcion, h.peso, h.id_productor ORDER BY c.id"),[$id_prod]);

        if(sizeof($formAnual) != 0)
        {
            $formAnualArr['ubicacion'] = $formAnual[0]->peso;
            $formAnualArr['pagos'] = $formAnual[1]->peso;
            $formAnualArr['envios'] = $formAnual[2]->peso;
            $formAnualArr['cumplim'] = $formAnual[3]->peso;
            $formAnualArr['exito'] = $formAnual[4]->peso;
    
            /* valida que exista una formula anual activa usando el query de arriba */
            if(sizeof($formAnual) != 0)
            return view('productores.formulas.e-formula-a',[
                'id_prod' => $id_prod,
                'formula' => $formAnualArr
            ]);
        }
        
        else return redirect ('productor/'.$id_prod.'/formulas');
    }

    /* para realizar el update desactivando la formula vieja
       y insertando una formula nueva */
    public function updateFormulaAnual (Request $request, $id_prod) {
        /* validacion server-side */
        $data = $request->validate([
            'ubicacion' => 'numeric|required|max:100|min:0',
            'pagos' => 'numeric|required|max:100|min:0',
            'envios' => 'numeric|required|max:100|min:0',
            'cumplim' => 'numeric|required|max:100|min:0',
            'exito' => 'numeric|required|max:100|min:0' 
        ]);

        $time = Carbon::now()->toDateTimeString();

        /* mas validacion server-side */
        if($data['ubicacion'] + $data['pagos'] + $data['envios'] + $data['cumplim'] > 100) return back();
        else {
            /* busca la formula actual y devuelve sus datos para poder asignar fecha_fin */
            $formInicial = DB::select(DB::raw("SELECT h.id_productor AS idp, MAX(h.fecha_inicio) AS fecha
            FROM rdj_criterios c, rdj_hist_formulas h, rdj_productores p 
            WHERE c.id=h.id_criterio AND h.id_productor=? AND h.tipo='a' AND h.fecha_fin IS NULL
            GROUP BY c.id, h.id_criterio, c.nombre, c.descripcion, h.peso, h.id_productor ORDER BY c.id"),[$id_prod]);

            /* desactivacion de la formula actual */
            DB::update(DB::raw("UPDATE rdj_hist_formulas SET fecha_fin = ? WHERE
            id_productor=? AND tipo='a' AND fecha_inicio=?"),[$time, $formInicial[0]->idp, $formInicial[0]->fecha]);

            /* insert de la formula nueva */
            DB::insert(DB::raw("INSERT INTO rdj_hist_formulas (fecha_inicio, id_criterio, id_productor, peso, tipo) VALUES
            (?,1,?,?,'a'),(?,2,?,?,'a'),(?,3,?,?,'a'),(?,4,?,?,'a'),(?,5,?,?,'a')"),[$time,$id_prod,$data['ubicacion'],
            $time,$id_prod,$data['pagos'],$time,$id_prod,$data['envios'],$time,$id_prod,$data['cumplim'],$time,$id_prod,$data['exito']]);

            return redirect ('productor/'.$id_prod.'/formulas');
        }
    }

    /* para devolver la interfaz de creacion de escala */
    public function crearEscala ($id_prod) {
        $escala = DB::select(DB::raw("SELECT MAX(e.fecha_inicio) AS fecha FROM rdj_escalas e, rdj_productores p
        WHERE e.id_productor=? AND e.fecha_fin IS NULL;"),[$id_prod]);

        /* valida que no exista una formula inicial activa usando el query de arriba */
        if($escala[0]->fecha == null)
        return view('productores.formulas.c-escala',[
            'id_prod' => $id_prod
        ]);
        
        else return redirect ('productor/'.$id_prod.'/formulas');
    }

    /* para insertar la escala nueva */
    public function insertEscala (Request $request, $id_prod) {
        /* validacion server-side */
        $data = $request->validate([
            'ri' => 'numeric|required|max:999|min:0',
            'rf' => 'numeric|required|max:999|min:0' 
        ]);
        $time = Carbon::now()->toDateTimeString();
        /* mas validacion server-side */
        if($data['ri'] >= $data['rf']) return back();
        else {
            /* insert de la escala */
            DB::insert(DB::raw("INSERT INTO rdj_escalas (fecha_inicio, id_productor, rango_inicio, rango_fin) 
            VALUES (?,?,?,?)"),[$time,$id_prod,$data['ri'],$data['rf']]);

            return redirect ('productor/'.$id_prod.'/formulas');
        }
    }

    public function editarEscala ($id_prod) {
        $escala = DB::select(DB::raw("SELECT MAX(e.fecha_inicio) AS fecha,
        e.rango_inicio AS ri, e.rango_fin AS rf FROM rdj_escalas e, rdj_productores p
        WHERE e.id_productor=? AND e.fecha_fin IS NULL
        GROUP BY e.rango_inicio, e.rango_fin;"),[$id_prod]);

        /* valida que exista una formula inicial activa usando el query de arriba */
        if(sizeof($escala) != 0)
        {
            return view('productores.formulas.e-escala',[
                'id_prod' => $id_prod,
                'ri' => $escala[0]->ri,
                'rf' => $escala[0]->rf
            ]);
        }
        
        else return redirect ('productor/'.$id_prod.'/formulas');
    }

    public function updateEscala (Request $request, $id_prod) {
        /* validacion server side */
        $data = $request->validate([
            'ri' => 'numeric|required|max:999|min:0',
            'rf' => 'numeric|required|max:999|min:0' 
        ]);
        $time = Carbon::now()->toDateTimeString();
        /* mas validacion server-side */
        if($data['ri'] >= $data['rf']) return back();
        else {
            /* busca la escala activa para poder agregarle su fecha_fin */
            $escala = DB::select(DB::raw("SELECT MAX(e.fecha_inicio) AS fecha
            FROM rdj_escalas e, rdj_productores p
            WHERE e.id_productor=? AND e.fecha_fin IS NULL
            GROUP BY e.rango_inicio, e.rango_fin;"),[$id_prod]);

            /* desactiva la escala actual */
            DB::update(DB::raw("UPDATE rdj_escalas SET fecha_fin=? 
            WHERE id_productor=? AND fecha_inicio=?"),[$time,$id_prod,$escala[0]->fecha]);

            /* crea la nueva escala */
            DB::insert(DB::raw("INSERT INTO rdj_escalas (fecha_inicio,id_productor,rango_inicio,rango_fin)
            VALUES (?,?,?,?)"),[$time,$id_prod,$data['ri'],$data['rf']]);

            return redirect('productor/'.$id_prod.'/formulas');
        }
    }
}
