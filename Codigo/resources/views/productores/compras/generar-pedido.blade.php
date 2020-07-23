@extends('productores.layout-productor')

@section('head')
    <title> Perfumes | Generar Contrato </title>
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
    <div class="col-8">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center h4">
                Generación de Pedido
            </div>
                <div class="col-12">
                    <table class="col-12 table border border-secondary mt-2">
                        <thead class="text-white">
                            <tr>
                                <th scope="col" class="text-center bg-primary border border-secondary">Contrato</th>
                                <th scope="col" class="text-center border border-secondary" style="background-color:#ebecec; color:#707070;">{{ date("d/m/Y", strtotime($fecha_apertura))}}</th>
                                <th scope="col" class="text-center border border-secondary" style="background-color:#ebecec; color:#707070;">{{ date("d/m/Y", strtotime($fecha_apertura . " +1 year"))}}</th>
                                <th scope="col" class="text-center border border-secondary" style="background-color:#ebecec; color:#707070;">{{$proveedor}}</th>
                            </tr>
                      </thead>
                        <tbody>
                       </tbody>
                    </table>
                    <realizar-pedido csrf="{{ csrf_token() }}"></realizar-pedido>
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
</div>
@endsection