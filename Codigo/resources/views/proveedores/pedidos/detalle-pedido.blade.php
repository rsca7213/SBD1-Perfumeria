@extends('proveedores.layout-proveedor')

@section('head')
    <title> Perfumes | Contratos </title>
@endsection

@section('nav')
    <li class="nav-item mx-2">
       <a href="{{ route('menuProveedor', ['id_prov' => $id_prov]) }}" class="nav-item"> Menú Principal </a>
    </li>
    <span class="nav-item"> | </span>
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
<div class="row d-flex justify-content-center mt-4 rounded">
    <div class="col-10">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center h4">
                Detalle Pedido
            </div>
            <div class="card-body" style="background-color: #FDFDFD">
                <div class="row border-bottom" style="border-color: #707070">
                    <div class="col-12" style="border-color: #707070">
                        <div class="text-center">
                            <span class="h4"> 
                                <b class="mr-2"> Pedido con </b> 
                            </span>
                            @include('proveedores.contratos.imagenes-productores')
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-4 text-center">
                                <span class="h5"> 
                                    <img src="{{ asset('img/iconos/date.svg') }}" alt="fecha" width="24" class="mb-1">
                                    Fecha de pedido: {{ date("d/m/Y", strtotime($pedido[0]->fecha)) }} 
                                </span>
                            </div>
                            <div class="col-4 text-center">
                                @if ($pedido[0]->estatus=='cprov')
                                    <span class="h5"> 
                                        Cancelado por: {{$pedido[0]->prov}}
                                    </span>
                                @endif
                                @if ($pedido[0]->estatus=='cprod')
                                    <span class="h5"> 
                                        Cancelado por: {{$pedido[0]->prod}} 
                                    </span>
                                @endif
                            </div>
                            <div class="col-4 text-center">
                                @if ($pedido[0]->fecha_envio!=NULL)
                                    <span class="h5"> 
                                        <img src="{{ asset('img/iconos/date.svg') }}" alt="fecha" width="24" class="mb-1">
                                        Fecha de envio: {{ date("d/m/Y", strtotime($pedido[0]->fecha_envio)) }} 
                                    </span>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <br>
                        <div class="text-center">
                            <span class="h3">
                                Ingredientes Solicitados
                            </span>
                        </div>
                        <br>
                        <table class="table table-striped border border-info">
                            <thead class="bg-primary text-white">
                                <tr  class="text-center">
                                    <th scope="col">#cas</th>
                                    <th scope="col">Nombre del Ingrediente</th>
                                    <th scope="col">Presentacion</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Subtotal $</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ingredientesPedido as $ingrediente)
                                    <tr class="text-center">
                                        <td>{{$ingrediente->cas}}</td>
                                        <td>{{$ingrediente->nombre}}</td>
                                        <td>{{$ingrediente->presentacion}} ml</td>
                                        <td>{{$ingrediente->cantidad}}</td>
                                        <td>{{$ingrediente->precio}} $</td>
                                    </tr> 
                                @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <br>
                        <div class="text-center">
                            @if ($pedido[0]->estatus=='p    ')
                                <span class="h3">
                                    Metodo de Envio a utilizar
                                </span>
                            @else
                                <span class="h3">
                                    Metodo de Envio utilizado
                                </span>
                            @endif
                        </div>
                        <br>
                        <table class="table table-striped border border-info">
                            <thead class="bg-primary text-white">
                                <tr  class="text-center">
                                    <th scope="col">Tipo de envio</th>
                                    <th scope="col">Duracion de envio</th>
                                    <th scope="col">Pais</th>
                                    <th scope="col">Subtotal $</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($enviosPedido as $envio)
                                    <tr class="text-center">
                                        @switch($envio->tipo)
                                            @case('t')
                                                <td> Terrestre </td>
                                                @break
                                            @case('m')
                                                <td> Maritimo </td>
                                                @break
                                            @case('a')
                                                <td> Aereo </td>
                                                @break
                                        @endswitch
                                        @if ($envio->duracion==1)
                                            <td>{{$envio->duracion}} día</td>
                                        @else
                                            <td>{{$envio->duracion}} días</td>
                                        @endif
                                        <td>{{$envio->pais}}</td>
                                        <td>{{$envio->precio}}</td>
                                    </tr> 
                                @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <br>
                        <div class="text-center">
                            @if ($pedido[0]->estatus=='p    ')
                                <span class="h3">
                                    Metodo de Pago a utilizar
                                </span>
                            @else
                                <span class="h3">
                                    Metodo de Pago utilizado
                                </span>
                            @endif
                        </div>
                        <br>
                        <table class="table table-striped border border-info">
                            <thead class="bg-primary text-white">
                                <tr  class="text-center">
                                    <th scope="col">Tipo de pago</th>
                                    <th scope="col">N° cuotas</th>
                                    <th scope="col">Porcentaje por cuota</th>
                                    <th scope="col">Pago cada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pagosPedido as $pago)
                                    <tr class="text-center">
                                        @switch($pago->tipo)
                                            @case('p')
                                                <td>Parcial</td>
                                                @break
                                            @case('c')
                                                <td>Completo</td>
                                                @break
                                        @endswitch

                                        @if ($pago->cuotas==NULL)
                                            <td>1</td>
                                        @else
                                            <td>{{$pago->cuotas}}</td>
                                        @endif

                                        @if ($pago->porcentaje==NULL || $pago->porcentaje==0)
                                            <td>100 %</td>
                                        @else
                                            <td>{{$pago->porcentaje}} %</td>
                                        @endif

                                        @if ($pago->meses==NULL)
                                            <td>N/A</td>
                                        @else
                                            @if ($pago->meses==1)
                                                <td>{{$pago->meses}} mes</td>
                                            @else
                                                <td>{{$pago->meses}} meses</td>
                                            @endif
                                        @endif
                                    </tr> 
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="text-center">
                            <span class="h4"> 
                                <b class="mr-2"> Monto Total del Pedido = {{$pedido[0]->monto}} $ </b> 
                            </span>
                        </div>
                        <br>
                        @if ($pedido[0]->estatus=='p    ' && $pedido[0]->id_factura==NULL)
                            <div class="row d-flex justify-content-center mt-4"> 
                                <a href="/proveedor/{{$id_prov}}/pedidos/aceptar/{{$id_prod}}/{{$pedido[0]->num_pedido}}" class="btn btn-primary mb-4"> 
                                    <img src="{{ asset('img/iconos/add_white.svg') }}" alt="rechazar" width="24">
                                    <span class="ml-2"> Aceptar Pedido </span> 
                                </a>
                            </div>
                            <div class="row d-flex justify-content-center"> 
                                <a href="/proveedor/{{$id_prov}}/pedidos/rechazar/{{$id_prod}}/{{$pedido[0]->num_pedido}}" class="btn btn-danger mb-4"> 
                                    <img src="{{ asset('img/iconos/trash_white.svg') }}" alt="rechazar" width="24">
                                    <span class="ml-2"> Rechazar Pedido </span> 
                                </a>
                            </div>
                        @endif
                        @if ($pedido[0]->estatus=='p    ' && $pedido[0]->id_factura!=NULL)
                            <div class="row d-flex justify-content-center mt-4"> 
                                <a href="/proveedor/{{$id_prov}}/pedidos/enviar/{{$id_prod}}/{{$pedido[0]->num_pedido}}" class="btn btn-primary mb-4"> 
                                    <img src="{{ asset('img/iconos/edit_white.svg') }}" alt="rechazar" width="24">
                                    <span class="ml-2"> Enviar Pedido </span> 
                                </a>
                            </div>
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