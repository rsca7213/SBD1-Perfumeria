@extends('productores.layout-productor')

@section('head')
    <title> Perfumes | Evaluaciones </title>
@endsection

@section('nav')
    <li class="nav-item mx-2"> 
        <a href="{{ route('verContratos', ['id_prod' => $id_prod]) }}" class="nav-item"> Contratos </a> 
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="{{ route('verContratosCompras', ['id_prod' => $id_prod])}}" class="nav-item"> Compras </a> 
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
<div class="row d-flex justify-content-center my-4 rounded">
    <div class="col-8">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center h4">
                Realizar Evaluación
            </div>

            <div class="card-body" style="background-color: #F8F8F8">
                <realizar-evaluacion csrf="{{ csrf_token() }}"> </realizar-evaluacion>
            </div>
            <div class="card-footer bg-primary text-white">
                <a href="{{ route('verEvaluaciones', ['id_prod' => $id_prod]) }}">
                    <img src="{{ asset('img/iconos/back.svg') }}" alt="atras" width="24">
                    <span class="text-white h6 ml-2 mt-1"> Volver a la Lista de Evaluaciones </span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection