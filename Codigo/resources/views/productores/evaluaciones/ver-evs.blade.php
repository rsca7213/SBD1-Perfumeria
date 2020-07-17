@extends('productores.layout-productor')

@section('head')
    <title> Perfumes | Evaluaciones </title>
@endsection

@section('nav')
    <li class="nav-item mx-2">
       <a href="{{ route('menuProductor', ['id_prod' => $id_prod]) }}" class="nav-item"> Menú Principal </a>
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="#" class="nav-item"> Contratos </a> 
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="#" class="nav-item"> Compras </a> 
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="{{ route('verFormulas', ['id_prod' => $id_prod]) }}" class="nav-item"> Formulas </a> 
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="{{ route('verEvaluaciones', ['id_prod' => $id_prod]) }}" class="nav-item-active"> Evaluaciones </a> 
    </li>
@endsection

@section('content')
<div class="row d-flex justify-content-center mt-4 rounded">
    <div class="col-8">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center h4">
                Lista de Evaluaciones
            </div>
            <div class="card-body" style="background-color: #FDFDFD">
                <ver-evaluaciones> </ver-evaluaciones>
                <hr>
                <div class="row d-flex justify-content-center">
                    <a class="btn btn-primary mx-4" href="{{ route('realizarEvaluacion', ['id_prod' => $id_prod]) }}">
                        <img src="{{ asset('/img/iconos/evaluation.svg') }}" alt="inicial" width="24" class="pb-1">
                        <span class="ml-2"> Realizar Evaluación </span>
                    </a>
                </div>
            </div>
            <div class="card-footer bg-primary text-white">
                <a href="{{ route('menuProductor', ['id_prod' => $id_prod]) }}">
                    <img src="{{ asset('img/iconos/back.svg') }}" alt="atras" width="24">
                    <span class="text-white h6 ml-2 mt-1"> Volver al Menú Principal </span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection