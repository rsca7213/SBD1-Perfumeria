<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class RecomendadorController extends Controller
{
    public function inicio () {
        return view ('recomendador.inicio');
    }

    public function iniciar () {
        return view ('recomendador.filtros');
    }

    public function resultados (Request $request) {

        /*$data = $request->validate([
            'pregunta' => 'required|numeric',
            'respuestas' => 'required'
        ]);*/

        $data["pregunta"] = 8;
        $data["respuestas"][0] = 'm';
        $data["respuestas"][1] = 'ad';
        $data["respuestas"][2] = 'lig';
        $data["respuestas"][3] = ["Informal","Natural"];
        $data["respuestas"][4] = ["Verde","Citrica"];
        $data["respuestas"][5] = ["Floral","Herbal"];
        $data["respuestas"][6] = ["es"];
        $data["respuestas"][7] = ["Libertad"];

        $perfumesFiltros = [];
        
        /* Filtro genero, es obligatorio */
        array_push($perfumesFiltros, DB::select(DB::raw("SELECT pe.id, pe.nombre AS perfume, pr.nombre AS productor FROM
        rdj_perfumes pe, rdj_productores pr WHERE pe.id_productor=pr.id AND pe.genero=? ORDER BY pr.nombre"),
        [$data["respuestas"][0]]));

        /* Filtro rango de edad */
        if($data["pregunta"] > 1) {
            array_push($perfumesFiltros, DB::select(DB::raw("SELECT pe.id, pe.nombre AS perfume, pr.nombre AS productor FROM
            rdj_perfumes pe, rdj_productores pr WHERE pe.id_productor=pr.id AND pe.edad=? ORDER BY pr.nombre"),
            [$data["respuestas"][1]]));
        }

        /* Filtro intensidad de olor */
        if($data["pregunta"] > 2) {
            $perfumesFiltros[2] = DB::select(DB::raw("SELECT pe.id, pe.nombre AS perfume, pr.nombre AS productor FROM
            rdj_perfumes pe, rdj_productores pr WHERE pe.id_productor=pr.id  ORDER BY pr.nombre"));

            foreach($perfumesFiltros[2] as $perfume) {
                $perfume->ints = DB::select(DB::raw("SELECT tipo FROM rdj_intensidades WHERE id_perfume=?"),[$perfume->id]);

                for ($i = 0; $i < sizeof($perfume->ints); $i++) {
                    $perfume->ints[$i] = $perfume->ints[$i]->tipo;
                }
            }

            $temp = [];
            for ($i = 0; $i < sizeof($perfumesFiltros[2]); $i++) {
                $cond = false;

                foreach($perfumesFiltros[2][$i]->ints as $int) {
                        if($data["respuestas"][2] == "lig") {
                            if($int == "EdS" || $int == "EdC") $cond = true;
                        }
                        else if($data["respuestas"][2] == "inter") {
                            if($int == "EdT") $cond = true;
                        }
                        else {
                            if($int == "EdP" || $int == "P  ") $cond = true;
                        }
                }

                if($cond) {
                    array_push($temp,$perfumesFiltros[2][$i]);
                }
            }

            $perfumesFiltros[2] = $temp;
            
        }

        /* Filtro de caracteres */
        if($data["pregunta"] > 3) {
            $perfumesFiltros[3] = DB::select(DB::raw("SELECT pe.id, pe.nombre AS perfume, pr.nombre AS productor 
            FROM rdj_perfumes pe, rdj_productores pr WHERE pe.id_productor=pr.id ORDER BY pe.id"));
            
            $temp = [];
            foreach($perfumesFiltros[3] as $perfume) {
                $perfume->palabras = DB::select(DB::raw("SELECT DISTINCT pa.palabra AS palabra FROM rdj_familias_olfativas fa, 
                rdj_palabras_claves pa, rdj_perfumes pe, rdj_perfumes_familias pf,
                rdj_familias_palabras fp WHERE fp.id_familia=fa.id AND pe.id=? AND
                fp.id_palabra=pa.id AND pf.id_perfume=pe.id AND pf.id_familia=fa.id ORDER BY pa.palabra"),[$perfume->id]);

                for ($i = 0; $i < sizeof($perfume->palabras); $i++) {
                    $perfume->palabras[$i] = $perfume->palabras[$i]->palabra;
                }
            }

            foreach($perfumesFiltros[3] as $perfume) {
                $cumplim = true;
                foreach($data["respuestas"][3] as $caracter) {
                    if(!in_array($caracter,$perfume->palabras)) $cumplim = false;
                }
                if($cumplim) array_push($temp,$perfume);
            }

            $perfumesFiltros[3] = $temp;
        }

        /* Filtro de familias olfativas */
        if($data["pregunta"] > 4) {
            $perfumesFiltros[4] = DB::select(DB::raw("SELECT pe.id, pe.nombre AS perfume, pr.nombre AS productor 
            FROM rdj_perfumes pe, rdj_productores pr WHERE pe.id_productor=pr.id ORDER BY pe.id"));
            
            $temp = [];
            foreach($perfumesFiltros[4] as $perfume) {
                $perfume->familias = DB::select(DB::raw("SELECT pe.nombre AS perfume, fa.nombre AS familia FROM rdj_perfumes pe,
                rdj_familias_olfativas fa, rdj_perfumes_familias pf WHERE fa.id=pf.id_familia
                AND pe.id=pf.id_perfume AND pe.id=? ORDER BY fa.nombre;"),[$perfume->id]);

                for ($i = 0; $i < sizeof($perfume->familias); $i++) {
                    $perfume->familias[$i] = $perfume->familias[$i]->familia;
                }
            }

            foreach($perfumesFiltros[4] as $perfume) {
                $cumplim = true;
                foreach($data["respuestas"][4] as $familia) {
                    if(!in_array($familia,$perfume->familias)) $cumplim = false;
                }
                if($cumplim) array_push($temp,$perfume);
            }

            $perfumesFiltros[4] = $temp;
        }

        /* FIltro de aromas prevalecientes */
        if($data["pregunta"] > 5) {
            $perfumesFiltros[5] = DB::select(DB::raw("SELECT pe.id, pe.nombre AS perfume, pr.nombre AS productor 
            FROM rdj_perfumes pe, rdj_productores pr WHERE pe.id_productor=pr.id ORDER BY pe.id"));
            
            $temp = [];
            foreach($perfumesFiltros[5] as $perfume) {
                $perfume->palabras = DB::select(DB::raw("SELECT DISTINCT pa.palabra AS palabra FROM rdj_familias_olfativas fa, 
                rdj_palabras_claves pa, rdj_perfumes pe, rdj_perfumes_familias pf,
                rdj_familias_palabras fp WHERE fp.id_familia=fa.id AND pe.id=? AND
                fp.id_palabra=pa.id AND pf.id_perfume=pe.id AND pf.id_familia=fa.id ORDER BY pa.palabra"),[$perfume->id]);

                for ($i = 0; $i < sizeof($perfume->palabras); $i++) {
                    $perfume->palabras[$i] = $perfume->palabras[$i]->palabra;
                }
            }

            foreach($perfumesFiltros[5] as $perfume) {
                $cumplim = true;
                foreach($data["respuestas"][5] as $caracter) {
                    if(!in_array($caracter,$perfume->palabras)) $cumplim = false;
                }
                if($cumplim) array_push($temp,$perfume);
            }

            $perfumesFiltros[5] = $temp;
        }

        /* Filtro de preferencias de uso */
        if($data["pregunta"] > 6) {
            $perfumesFiltros[6] = DB::select(DB::raw("SELECT pe.id, pe.nombre AS perfume, pr.nombre AS productor FROM
            rdj_perfumes pe, rdj_productores pr WHERE pe.id_productor=pr.id  ORDER BY pr.nombre"));

            foreach($perfumesFiltros[6] as $perfume) {
                $perfume->ints = DB::select(DB::raw("SELECT tipo FROM rdj_intensidades WHERE id_perfume=?"),[$perfume->id]);

                for ($i = 0; $i < sizeof($perfume->ints); $i++) {
                    $perfume->ints[$i] = $perfume->ints[$i]->tipo;
                }
            }

            $temp = [];
            for ($i = 0; $i < sizeof($perfumesFiltros[6]); $i++) {
                $cond = false;

                foreach($perfumesFiltros[6][$i]->ints as $int) {
                        if($data["respuestas"][6] == "di") {
                            if($int == "EdS") $cond = true;
                        }
                        else if($data["respuestas"][6] == "tr") {
                            if($int == "EdT" || $int == "EdC") $cond = true;
                        }
                        else {
                            if($int == "EdP" || $int == "P  ") $cond = true;
                        }
                }

                if($cond) {
                    array_push($temp,$perfumesFiltros[6][$i]);
                }
            }

            $perfumesFiltros[6] = $temp;
            
        }

        /* FIltro de personalidades */
        if($data["pregunta"] > 7) {
            $perfumesFiltros[7] = DB::select(DB::raw("SELECT pe.id, pe.nombre AS perfume, pr.nombre AS productor 
            FROM rdj_perfumes pe, rdj_productores pr WHERE pe.id_productor=pr.id ORDER BY pe.id"));
            
            $temp = [];
            foreach($perfumesFiltros[7] as $perfume) {
                $perfume->palabras = DB::select(DB::raw("SELECT DISTINCT pa.palabra AS palabra FROM rdj_familias_olfativas fa, 
                rdj_palabras_claves pa, rdj_perfumes pe, rdj_perfumes_familias pf,
                rdj_familias_palabras fp WHERE fp.id_familia=fa.id AND pe.id=? AND
                fp.id_palabra=pa.id AND pf.id_perfume=pe.id AND pf.id_familia=fa.id ORDER BY pa.palabra"),[$perfume->id]);

                for ($i = 0; $i < sizeof($perfume->palabras); $i++) {
                    $perfume->palabras[$i] = $perfume->palabras[$i]->palabra;
                }
            }

            foreach($perfumesFiltros[7] as $perfume) {
                $cumplim = true;
                foreach($data["respuestas"][7] as $caracter) {
                    if(!in_array($caracter,$perfume->palabras)) $cumplim = false;
                }
                if($cumplim) array_push($temp,$perfume);
            }

            $perfumesFiltros[7] = $temp;
        }

        dd($perfumesFiltros);
        //return response([$perfumesFiltros],200);
    }
}
