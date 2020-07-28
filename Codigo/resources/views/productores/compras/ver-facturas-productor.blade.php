@extends('productores.layout-productor')

@section('head')
    <title> Perfumes | Facturas </title>
@endsection

@section('nav')
    <li class="nav-item mx-2">
       <a href="{{ route('menuProductor', ['id_prod' => $id_prod]) }}" class="nav-item"> Menú Principal </a>
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="{{ route('verContratos', ['id_prod' => $id_prod]) }}" class="nav-item"> Contratos </a> 
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="{{ route('verContratosCompras', ['id_prod' => $id_prod])}}" class="nav-item-active"> Compras </a> 
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="{{ route('verFormulas', ['id_prod' => $id_prod]) }}" class="nav-item"> Formulas </a> 
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="{{ route('verEvaluaciones', ['id_prod' => $id_prod]) }}" class="nav-item"> Evaluaciones </a> 
    </li>
@endsection

@section('content')
<div class="row d-flex justify-content-center mt-4 rounded">
    <div class="col-10">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center h4">
                Mis Facturas
            </div>
            <div class="card-body" style="background-color: #FDFDFD">
                <div class="row border-bottom" style="border-color: #707070">
                    
                        <ver-facturas csrf="{{ csrf_token() }}"></ver-facturas>
                    
                    
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