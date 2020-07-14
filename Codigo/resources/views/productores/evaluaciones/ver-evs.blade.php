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
                <div class="row d-flex justify-content-center">
                    <span class="h5 mr-3"> Filtrar: </span>
                    <input type="radio" id="todas" name="filtro" class="mt-1" value="Todas" checked>
                    <label class="h5 ml-2 mr-4" for="todas"> Todas </label>
                    <input type="radio" id="iniciales" name="filtro" class="mt-1" value="Iniciales">
                    <label class="h5 ml-2 mr-4" for="iniciales"> Iniciales </label>
                    <input type="radio" id="anuales" name="filtro" class="mt-1" value="Anuales">
                    <label class="h5 ml-2 mr-4" for="anuales"> Anuales </label>
                </div>
                <hr>
                <table class="table table-striped border border-info">
                    <thead class="bg-primary text-white"> 
                        <th scope="col" class="text-center"> Fecha </th>
                        <th scope="col"> Proveedor </th>
                        <th scope="col" class="text-center"> Tipo</th>
                        <th scope="col" class="text-center"> Puntaje </th>
                        <th scope="col"> Resultado </th>
                        <th scope="col" class="text-center"> Acción </th>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">25/05/2020 </td>
                            <td>Firmenich</td>
                            <td class="text-center">Inicial</td>
                            <td class="text-center">5 de 100</td>
                            <td>Reprobado</td>
                            <td class="text-center"> N/A </td>
                        </tr>
                    </tbody>
                </table>
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