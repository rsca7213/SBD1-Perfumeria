@extends('productores.layout-productor')

@section('head')
    <title> Perfumes | Contratos </title>
@endsection

@section('nav')
    <li class="nav-item mx-2">
       <a href="{{ route('menuProductor', ['id_prod' => $id_prod]) }}" class="nav-item"> Menú Principal </a>
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="{{ route('verContratos', ['id_prod' => $id_prod]) }}" class="nav-item-active"> Contratos </a> 
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
        <a href="{{ route('verEvaluaciones', ['id_prod' => $id_prod]) }}" class="nav-item"> Evaluaciones </a> 
    </li>
@endsection

@section('content')
<div class="row d-flex justify-content-center mt-4 rounded">
    <div class="col-10">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center h4">
                Contratos
            </div>
            <div class="card-body" style="background-color: #FDFDFD">
                <div class="row border-bottom" style="border-color: #707070">
                    {{-- Contratos vigentes --}}
                    <div class="col-6 border-right" style="border-color: #707070">
                        <span class="h5"> 
                            <b class="mr-2"> Contratos vigentes </b> 
                        </span>
                        <hr>
                        @if ($contratosVigentes != [] || $contratosEspera != [])
                            <table class="table table-striped border border-info">
                                <thead class="bg-primary text-white">
                                    <tr  class="text-center align-items-center">
                                        <th scope="col">Fecha creación</th>
                                        <th scope="col">Proveedor</th>
                                        <th scope="col">Estatus</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contratosVigentes as $contrato)
                                        <tr class="text-center">
                                            <td>{{ date("d/m/Y", strtotime($contrato->fecha)) }}</td>
                                            <td>{{$contrato->prov}}</td>
                                            <td>
                                                <img src="{{ asset('img/iconos/check_green.svg') }}" width="24" class="mt-1">
                                                <span class="ml-2"> Activo </span>
                                            </td>
                                            <td>
                                                <a href="/productor/{{$id_prod}}/contratos/detalle/{{$contrato->id_prov}}/{{$contrato->fecha}}" class="btn btn-sm btn-primary">
                                                    <img src="{{ asset('/img/iconos/list_white.svg') }}" width="24" class="mb-1">
                                                    <span class="ml-2"> Detalles </span>
                                                </a>
                                            </td>
                                        </tr> 
                                    @endforeach
                                    @foreach ($contratosEspera as $contrato)
                                        <tr class="text-center">
                                            <td>{{ date("d/m/Y", strtotime($contrato->fecha)) }}</td>
                                            <td>{{$contrato->prov}}</td>
                                            <td>
                                                <img src="{{ asset('img/iconos/pending.svg') }}" width="24" class="mt-1">
                                                <span class="ml-2"> Por confirmar </span>
                                            </td>
                                            <td>
                                                <a href="/productor/{{$id_prod}}/contratos/detalle/{{$contrato->id_prov}}/{{$contrato->fecha}}" class="btn btn-sm btn-primary">
                                                    <img src="{{ asset('/img/iconos/list_white.svg') }}" width="24" class="mb-1">
                                                    <span class="ml-2"> Detalles </span>
                                                </a>
                                            </td>
                                        </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                        @else 
                            <h5> No tiene contratos activos en estos momentos. </h5>
                        @endif
                    </div>
                    {{-- Final Contratos vigentes --}}
                    {{-- Contratos no vigentes --}}
                    <div class="col-6" >
                        <span class="h5"> 
                            <b class="mr-2"> Contratos no vigentes </b> 
                        </span>
                        <hr>
                        @if ($contratosNoVigentes != [])
                            <table class="table table-striped border border-info">
                                <thead class="bg-primary text-white">
                                    <tr  class="text-center align-items-center">
                                        <th scope="col">Fecha creación</th>
                                        <th scope="col">Proveedor</th>
                                        <th scope="col">Cancelado o Expirado</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contratosNoVigentes as $contrato)
                                        <tr class="text-center">
                                            <td>{{ date("d/m/Y", strtotime($contrato->fecha)) }}</td>
                                            <td>{{$contrato->prov}}</td>
                                            @if ($contrato->cancelacion==true)
                                                <td>
                                                    <img src="{{ asset('img/iconos/close.svg') }}" alt="atras" width="24">
                                                    <span class="ml-2"> Cancelado </span>
                                                </td>
                                            @else
                                                <td>
                                                    <img src="{{ asset('img/iconos/clock.svg') }}" alt="atras" width="24">
                                                    <span class="ml-2"> Expirado </span>
                                                </td>
                                            @endif
                                            <td>
                                                <a href="/productor/{{$id_prod}}/contratos/detalle/{{$contrato->id_prov}}/{{$contrato->fecha}}" class="btn btn-sm btn-primary">
                                                    <img src="{{ asset('/img/iconos/list_white.svg') }}" width="24" class="mb-1">
                                                    <span class="ml-2"> Detalles </span>
                                                </a>
                                            </td>
                                        </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                        @else 
                            <h5> No has realizado contratos o todos tus contratos aún son vigentes. </h5>
                        @endif
                    </div>
                    {{-- Final Contratos no vigentes --}}
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