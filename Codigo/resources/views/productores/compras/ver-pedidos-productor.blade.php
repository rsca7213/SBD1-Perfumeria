@extends('productores.layout-productor')

@section('head')
    <title> Perfumes | Pedidos </title>
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
                Pedidos
            </div>
            <div class="card-body" style="background-color: #FDFDFD">
                <div class="row border-bottom" style="border-color: #707070">
                    <div class="col-6 border-right" style="border-color: #707070">
                        <span class="h5"> 
                            <b class="mr-2"> Pedidos Pendientes </b> 
                        </span>
                        <hr>
                        @if ($pedidosPendientes != [])
                            <table class="table table-striped border border-info">
                                <thead class="bg-primary text-white">
                                    <tr  class="text-center align-items-center">
                                        <th scope="col">Fecha creación</th>
                                        <th scope="col">Productor</th>
                                        <th scope="col">Estatus</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pedidosPendientes as $pedido)
                                        <tr class="text-center">
                                            <td>{{ date("d/m/Y", strtotime($pedido->fecha)) }}</td>
                                            <td>{{$pedido->prov}}</td>
                                            @if ($pedido->id_factura!=NULL)
                                                <td><span class="ml-2">  Por Enviar </span></td>
                                            @else
                                                <td><span class="ml-2">  Por Aceptar </span></td>
                                            @endif
                                            <td>
                                                <a href="/productor/{{$id_prod}}/pedidos/detalle/{{$pedido->id_prov}}/{{$pedido->num_pedido}}" class="btn btn-sm btn-primary">
                                                    <img src="{{ asset('/img/iconos/list_white.svg') }}" width="24" class="mb-1">
                                                    <span class="ml-2"> Detalles </span>
                                                </a>
                                            </td>
                                        </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                        @else 
                            <h5> No tiene pedidos pendientes. </h5>
                        @endif
                    </div>
                    <div class="col-6" >
                        <span class="h5"> 
                            <b class="mr-2"> Pedidos enviados o cancelados </b> 
                        </span>
                        <hr>
                        @if ($pedidosNoPendientes != [])
                            <table class="table table-striped border border-info">
                                <thead class="bg-primary text-white">
                                    <tr  class="text-center align-items-center">
                                        <th scope="col">Fecha creación</th>
                                        <th scope="col">Productor</th>
                                        <th scope="col">Enviado o cancelado</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pedidosNoPendientes as $pedido)
                                        <tr class="text-center">
                                            <td>{{ date("d/m/Y", strtotime($pedido->fecha)) }}</td>
                                            <td>{{$pedido->prov}}</td>
                                            @if ($pedido->estatus!='cprod' && $pedido->estatus!='cprov')
                                                <td><span class="ml-2"> Enviado </span></td>
                                            @else
                                                <td><span class="ml-2"> Cancelado </span></td>
                                            @endif
                                            
                                            <td>
                                                <a href="/productor/{{$id_prod}}/pedidos/detalle/{{$pedido->id_prov}}/{{$pedido->num_pedido}}" class="btn btn-sm btn-primary">
                                                    <img src="{{ asset('/img/iconos/list_white.svg') }}" width="24" class="mb-1">
                                                    <span class="ml-2"> Detalles </span>
                                                </a>
                                            </td>
                                        </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                        @else 
                            <h5> No tiene pedidos enviados o cancelados en este momento. </h5>
                        @endif
                    </div>
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