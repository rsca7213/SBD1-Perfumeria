@extends('productores.layout-productor')

@section('head')
    <title> Perfumes | Compras </title>
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
                Compras
            </div>
            <div class="card-body" style="background-color: #FDFDFD">
                <div class="row border-bottom" style="border-color: #707070">
                    {{-- Contratos vigentes --}}
                    <div class="col-12 border-right" style="border-color: #707070">
                        <div class="align-items-center h5 text-center"> 
                            <b class="text-center"> Contratos vigentes </b> 
                        </div>
                        <hr>
                        @if ($contratosVigentes != [])
                            <table class="table table-striped border border-info">
                                <thead class="bg-primary text-white">
                                    <tr  class="text-center align-items-center">
                                        <th scope="col">Fecha Apetura</th>
                                        <th scope="col">Tipo de Contrato</th>
                                        <th scope="col">Proveedor</th>
                                        <th scope="col">Pedidos</th>
                                        <th scope="col">Facturas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contratosVigentes as $contrato)
                                        <tr class="text-center">
                                            <td>{{ date("d/m/Y", strtotime($contrato->fechacontrato)) }}</td>
                                            <td>{{$contrato->tipo_contrato}}</td>
                                            <td>{{$contrato->prov}}</td>
                                            <td>
                                                <a href="/productor/{{$id_prod}}/compras/pedido/{{$contrato->id_prov}}/{{$contrato->fecha}}" class="mr-2">
                                                    <img data-toggle="tooltip" title="Realizar Pedidos" src="{{ asset('/img/iconos/add.svg') }}" width="24" class="mb-1">
                                                </a>
                                            <a href="/productor/{{$id_prod}}/pedidos/{{$contrato->id_prov}}/{{$contrato->fecha}}" class="ml-2">
                                                    <img data-toggle="tooltip" title="Consultar Pedidos" src="{{ asset('/img/iconos/list.svg') }}" width="24" class="mb-1">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="/productor/{{$id_prod}}/contratos/detalle/{{$contrato->id_prov}}/{{$contrato->fecha}}" class="">
                                                    <img data-toggle="tooltip" title="Consultar Facturas" src="{{ asset('/img/iconos/list.svg') }}" width="24" class="mb-1">
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