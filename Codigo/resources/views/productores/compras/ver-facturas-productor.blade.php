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
                                    @foreach ($facturas as $factura)
                                    <tr  class="text-center">
                                        <td>{{$factura->num_factura}}</td>
                                        <td>{{$factura->num_pedido}}</td>
                                        <td>{{$factura->prov}}</td>
                                        <td>{{$factura->monto . " $"}}</td>
                                        @if ($factura->por_pagar==0)
                                            <td><span class="ml-2"> Pagado </span></td>
                                        @else
                                            <td><span class="ml-2"> Por pagar </span></td>
                                        @endif
                                        <td>
                                            <img src="/img/iconos/list.svg"alt="ver" width="24" class="iconobtn" data-toggle="modal" data-target="#Pagos_por_hacer{{$factura->num_pedido}}">
                                            <!-- Modal para mostrar los detalles de un ingrediente -->
                                            <div class="modal fade" id="Pagos_por_hacer{{$factura->num_pedido}}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content" style="background-color: #F5F5F5">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"> Pagos por realizar  </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                    <div class="modal-body h5 text-center">
                                                        <br>
                                                        
                                                        <table class="table table-striped border border-info">
                                                            <thead class="bg-primary text-white">
                                                                <tr  class="text-center">
                                                                    <th scope="col">Cuotas</th>
                                                                    <th scope="col">Porcentaje por cuota</th>
                                                                    <th scope="col">Monto por cuota</th>
                                                                    
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($pagos as $pago)
                                                                  @if($pago->cuotas>0)
                                                                    <tr class="text-center">
                                                                        @if ($pago->num_pedido==$factura->num_pedido)
                                                                            @if ($factura->por_pagar!=0)
                                                                                <td> {{$pago->cuotas}} </td>
                                                                                <td> {{$pago->porcentaje}} % </td>
                                                                                <td>{{round($factura->monto/$pago->cuotas,2) . " $"}}</td>
                                                                            @else
                                                                                <td><span class="ml-2"> N/A </span></td>
                                                                                <td><span class="ml-2"> N/A  </span></td>
                                                                            @endif
                                                                            
                                                                        @endif
                                                                        
                                                                    </tr>
                                                                    @else
                                                    <div>La factura fue pagada en su totalidad</div>
                                                    @endif
                                                                @endforeach
                                                                
                                                            </tbody>
                                                        </table>
                                                    <a href="/productor/pagar/{{$factura->num_pedido}}/{{$factura->monto}}/" class="btn btn-primary">Pagar</a>
                                                    
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
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
                <a href="{{ route('menuProductor', ['id_prod' => $id_prod]) }}">
                    <img src="{{ asset('img/iconos/back.svg') }}" alt="atras" width="24">
                    <span class="text-white h6 ml-2 mt-1"> Volver al Menú Principal </span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection