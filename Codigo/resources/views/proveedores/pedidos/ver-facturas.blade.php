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
        <a href="{{ route('verPedidos', ['id_prov' => $id_prov]) }}" class="nav-item"> Pedidos </a> 
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="{{ route('verFacturas', ['id_prov' => $id_prov]) }}" class="nav-item-active"> Facturas </a> 
    </li>
@endsection

@section('content')
<div class="row d-flex justify-content-center my-4 rounded">
    <div class="col-10">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center h4">
                Mis Facturas
            </div>
            <div class="card-body" style="background-color: #FDFDFD">
                <div class="row border-bottom" style="border-color: #707070">
                    <div class="col-12" style="border-color: #707070">
                        <br>
                        @if ($facturas!=[])
                            <table class="table table-striped border border-info">
                                <thead class="bg-primary text-white">
                                    <tr  class="text-center">
                                        <th scope="col">#Factura</th>
                                        <th scope="col">#Pedido</th>
                                        <th scope="col">Proveedor</th>
                                        <th scope="col">Monto Total</th>
                                        <th scope="col">Estatus</th>
                                        <th scope="col">Pagos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=0 @endphp
                                    @foreach ($facturas as $factura)
                                    <tr  class="text-center">
                                        <td>{{$factura->num_factura}}</td>
                                        <td>{{$factura->num_pedido}}</td>
                                        <td>{{$factura->prod}}</td>
                                        <td>{{$factura->monto . " $"}}</td>
                                        @if ($factura->por_pagar<=0)
                                            <td><span class="ml-2"> Pagado </span></td>
                                        @else
                                            <td><span class="ml-2"> Por pagar </span></td>
                                        @endif
                                        <td>
                                            <img src="/img/iconos/list.svg"alt="ver" width="24" class="iconobtn" data-toggle="modal" data-target="#Pagos_por_hacer{{$factura->num_pedido}}">
                                            <!-- Modal para mostrar los detalles de un ingrediente -->
                                            <div class="modal fade" id="Pagos_por_hacer{{$factura->num_pedido}}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content" style="background-color: #F5F5F5">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"> Pagos </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                    <div class="modal-body h5 text-center">
                                                            
                                                        @if($pagados!=[] && $tiene_pagos[$i]==true)
                                                            <div class="row mx-4 d-flex justify-content-center">
                                                                <p>Pagos Realizados</p>
                                                            </div>
                                                            <div class="row mx-4">

                                                            </div>
                                                            <div class="row mx-4">
                                                                <table class="table table-striped border border-info">
                                                                    <thead class="bg-primary text-white"> 
                                                                        <tr  class="text-center">
                                                                            <th class="p-1" scope="col">Pagado En</th>  
                                                                            <th class="p-1" scope="col">Monto Pagado</th> 
                                                                        </tr>
                                                                    </thead> 
                                                                    <tbody>
                                                            
                                                                    @foreach($pagados as $pagado)
                                                                        @if($pagado->num_pedido==$factura->num_pedido)
                                                                            <tr>
                                                                                <td class="p-1"> {{$pagado->fecha}} </td>
                                                                                <td class="p-1"> {{$pagado->monto. " $"}} </td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            
                                                        @endif
                                                        
                                                        @if ($factura->por_pagar>0)
                                                            <div class="row mx-4 d-flex justify-content-center">
                                                                <p>Método de Pago</p>
                                                            </div>
                                                            
                                                            <div class="row mx-4">
                                                                <table class="table table-striped border border-info">
                                                                    <thead class="bg-primary text-white">
                                                                        @if($factura->cuotas>0 && $factura->cuotas!=null)
                                                                        <tr  class="text-center">
                                                                            <th class="p-1" scope="col">Cuotas Por Pagar</th>
                                                                            <th class="p-1" scope="col">Porcentaje por cuota</th>
                                                                            <th class="p-1" scope="col">Monto por cuota</th> 
                                                                        </tr>
                                                                        @else
                                                                            <tr  class="text-center">
                                                                                <th class="p-1" scope="col">Monto a Pagar</th>
                                                                            </tr>
                                                                        @endif
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($pagos as $pago)
                                                                        @if($pago->cuotas>0 && $pago->num_pedido==$factura->num_pedido)
                                                                            <tr class="text-center">
                                                                                @if ($pago->num_pedido==$factura->num_pedido)
                                                                                    <td class="p-1"> {{$pago->cuotas}} </td>
                                                                                    <td class="p-1"> {{$pago->porcentaje}} % </td>
                                                                                    <td class="p-1">{{round($factura->monto/$factura->cuotas,2) . " $"}}</td>
                                                                                @endif 
                                                                            </tr>
                                                                        @elseif($pago->cuotas==-1 && $pago->num_pedido==$factura->num_pedido)
                                                                                @php $factura->cuotas=1 @endphp
                                                                                @if ($pago->num_pedido==$factura->num_pedido)
                                                                                    <td class="p-1">{{round($factura->monto,2) . " $"}}</td>
                                                                                @endif
                                                                        @endif 
                                                                        @endforeach       
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            
                                                        <div class="row mx-4 d-flex justify-content-center">
                                                            <button data-dismiss="modal" aria-label="Close" class="btn btn-primary btn-lg"> <img src="/img/iconos/check_white.svg" width="24" class="mb-1" />Aceptar</button>
                                                        </div>    
                                                    
                                                        
                                                        @php $cuota=0 @endphp
                                                        
                                                    
                                                    @php $cuota=$i @endphp
                                                    @else
                                                    <div>La factura fue pagada en su totalidad</div>         
                                                    <div class="d-flex modal-footer justify-content-center mt-2">
                                                        <button class="btn btn-primary btn-lg mx-4" data-dismiss="modal" aria-label="Close">
                                                            <img src="/img/iconos/check_white.svg" width="24" class="mb-1" /> Aceptar
                                                         </button>
                                                    </div>
                                                    @endif
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </td>
                                    </tr>
                                    @php $i++@endphp
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <h5> No tienes facturas en este momento. </h5>
                        @endif
                        <br>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-primary text-white">
                <a href="{{ route('verPedidos', ['id_prov' => $id_prov]) }}">
                    <img src="{{ asset('img/iconos/back.svg') }}" alt="atras" width="24">
                    <span class="text-white h6 ml-2 mt-1"> Volver al Menú de Pedidos </span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection