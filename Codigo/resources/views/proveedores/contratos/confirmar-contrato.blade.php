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
        <a href="{{ route('verContratosPv', ['id_prov' => $id_prov]) }}" class="nav-item-active"> Contratos </a> 
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="#" class="nav-item"> Pedidos </a> 
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="#" class="nav-item"> Facturas </a> 
    </li>
@endsection

@section('content')
<div class="row d-flex justify-content-center mt-4 rounded">
    <div class="col-7">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center h4">
                Detalles de Contrato
            </div>
            <div class="card-body" style="background-color: #FDFDFD">
                <div class="text-center">
                    <span class="h4"> 
                        <b class="mr-2"> Contrato con </b> 
                    </span>
                    @include('proveedores.contratos.imagenes-productores')
                </div>
                <hr>
                <div class="col-4 text-center">
                    <span class="h5"> 
                        @if ($detalles[0]->exc==true)
                            Exclusivo: Si 
                        @else
                            Exclusivo: No
                        @endif
                    </span>
                </div>
                    <br>
                    <div class="text-center">
                        <span class="h3">
                            Ingredientes
                        </span>
                    </div>
                    <br>
                <form action="/proveedor/{{$id_prov}}/contratos/aceptar/{{$fecha}}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <table class="table table-striped border border-info">
                        <thead class="bg-primary text-white">
                            <tr  class="text-center">
                                <th scope="col">#cas</th>
                                <th scope="col">Nombre del Ingrediente</th>
                                <th scope="col">Descuento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ingredientes_esencia as $detalle)
                                <tr class="text-center">
                                    <td><b>{{$detalle->cas}}</b></td>
                                    <td><b>{{$detalle->i_nombre}}</b></td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" id={{$detalle->i_cas}} name="i_descuentos[]">
                                        </div>
                                    </td>
                                </tr> 
                            @endforeach
                            @foreach ($otros_ingredientes as $detalle)
                                <tr class="text-center">
                                    <td><b>{{$detalle->cas}}</b></td>
                                    <td><b>{{$detalle->o_nombre}}</b></td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" id={{$detalle->o_cas}} name="o_descuentos[]">
                                        </div>
                                    </td>
                                </tr> 
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <br>
                    <div class="text-center">
                        <span class="h3">
                            Metodos de Envio
                        </span>
                    </div>
                    <br>
                    <table class="table table-striped border border-info">
                        <thead class="bg-primary text-white">
                            <tr  class="text-center">
                                <th scope="col">Tipo de envio</th>
                                <th scope="col">Duracion de envio</th>
                                <th scope="col">Pais de envio</th>
                                <th scope="col">Precio de envio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($metodo_envio as $detalle)
                                <tr class="text-center">
                                    @switch($detalle->tipo)
                                        @case('m')
                                            <td><b>Maritimo</b></td>
                                            @break
                                        @case('t')
                                            <td><b>Terrestre</b></td>
                                            @break
                                        @case('a')
                                            <td><b>Aereo</b></td>
                                            @break
                                    @endswitch
                                    <td><b>{{$detalle->duracion}} meses</b></td>
                                    <td><b>{{$detalle->pais}}</b></td>
                                    <td><b>{{$detalle->precio}}</b></td>
                                </tr> 
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <br>
                    <div class="text-center">
                        <span class="h3">
                            Metodos de Pago
                        </span>
                    </div>
                    <br>
                    <table class="table table-striped border border-info">
                        <thead class="bg-primary text-white">
                            <tr  class="text-center">
                                <th scope="col">Tipo de pago</th>
                                <th scope="col">N° de cuotas</th>
                                <th scope="col">Porcentaje por cuota</th>
                                <th scope="col">Pago cada</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($metodo_pago as $detalle)
                                <tr class="text-center">

                                    @switch($detalle->tipo)
                                        @case('p')
                                            <td><b>Parcial</b></td>
                                            @break
                                        @case('c')
                                            <td><b>Contado</b></td>
                                            @break
                                    @endswitch

                                    @if ($detalle->cuotas==NULL)
                                        <td><b>1</b></td>
                                    @else
                                        <td><b>{{$detalle->cuotas}}</b></td>
                                    @endif

                                    @if ($detalle->porcentaje==NULL)
                                        <td><b>100 %</b></td>
                                    @else
                                        <td><b>{{$detalle->porcentaje}} %</b></td>
                                    @endif

                                    @if ($detalle->meses==NULL)
                                        <td><b></b></td>
                                    @else
                                        <td><b>{{$detalle->meses}} meses</b></td>
                                    @endif
                                </tr> 
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    @if (session('mensaje'))
                        <div class="text-center">
                            <span class="text-danger h5">
                                {{ session('mensaje') }}
                            </span>
                        </div>
                    @endif
                    <br>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success">
                            <img src="{{ asset('img/iconos/add_white.svg') }}" alt="aceptar" width="24">
                            <span class="ml-2"> Aceptar Contrato </span>
                        </button>
                    </div>
                </form>
                {{-- <div class="row d-flex justify-content-center mt-4"> 
                    <a href="/proveedor/{{$id_prov}}/contratos/rechazar/{{$fecha}}" class="btn btn-danger mb-4"> 
                        <img src="{{ asset('img/iconos/trash_white.svg') }}" alt="rechazar" width="24">
                        Reechazar Contrato 
                    </a>
                </div> --}}
            </div>
            <div class="card-footer bg-primary text-white">
                <a href="{{ route('verContratosPv', ['id_prov' => $id_prov]) }}">
                    <img src="{{ asset('img/iconos/back.svg') }}" alt="atras" width="24">
                    <span class="text-white h6 ml-2 mt-1"> Volver al Menú Principal </span>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection