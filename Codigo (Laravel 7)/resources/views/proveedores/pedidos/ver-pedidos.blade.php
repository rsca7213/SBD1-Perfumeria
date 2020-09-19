@extends('proveedores.layout-proveedor')

@section('head')
    <title> Perfumes | Contratos </title>
@endsection

@section('nav')
    <li class="nav-item mx-2"> 
        <a href="{{ route('verContratosPv', ['id_prov' => $id_prov]) }}" class="nav-item"> Contratos </a> 
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="{{ route('verPedidos', ['id_prov' => $id_prov]) }}" class="nav-item-active"> Pedidos </a> 
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="{{ route('verFacturas', ['id_prov' => $id_prov]) }}" class="nav-item"> Facturas </a> 
    </li>
@endsection

@section('content')
<div class="row d-flex justify-content-center mx-4 my-4 rounded">
    <div class="col-12">
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
                                            <td>{{$pedido->prod}}</td>
                                            @if ($pedido->id_factura!=NULL)
                                                <td><span class="ml-2">  Por Enviar </span></td>
                                            @else
                                                <td><span class="ml-2">  Por Aceptar </span></td>
                                            @endif
                                            <td>
                                                <a href="/proveedor/{{$id_prov}}/pedidos/detalle/{{$pedido->id_prod}}/{{$pedido->num_pedido}}" class="btn btn-sm btn-primary">
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
                                            <td>{{$pedido->prod}}</td>
                                            @if ($pedido->estatus!='cprod' && $pedido->estatus!='cprov')
                                                <td><span class="ml-2"> Enviado </span></td>
                                            @else
                                                <td><span class="ml-2"> Cancelado </span></td>
                                            @endif
                                            
                                            <td>
                                                <a href="/proveedor/{{$id_prov}}/pedidos/detalle/{{$pedido->id_prod}}/{{$pedido->num_pedido}}" class="btn btn-sm btn-primary">
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
                <a href="/">
                    <img src="{{ asset('img/iconos/back.svg') }}" alt="atras" width="24">
                    <span class="text-white h6 ml-2 mt-1"> Volver a la Página de Inicio </span>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection