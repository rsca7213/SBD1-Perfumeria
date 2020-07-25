<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecomendadorController extends Controller
{
    public function inicio () {
        return view ('recomendador.inicio');
    }

    public function iniciar () {
        return view ('recomendador.filtros');
    }
}
