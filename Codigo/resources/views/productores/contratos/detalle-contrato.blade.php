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
        <a href="#" class="nav-item"> Compras </a> 
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
                Detalle Contrato
            </div>
            <div class="card-body" style="background-color: #FDFDFD">
                <div class="row border-bottom" style="border-color: #707070">
                    {{-- Contratos vigentes --}}
                    <div class="col-12" style="border-color: #707070">
                        <div class="text-center">
                            <span class="h4"> 
                                <b class="mr-2"> Contrato con </b> 
                            </span>
                            @include('productores.contratos.imagenes-proveedores')
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-4 text-center">
                                <span class="h5"> 
                                    <img src="{{ asset('img/iconos/date.svg') }}" alt="fecha" width="24" class="mb-1">
                                    Fecha de apertura: {{ date("d/m/Y", strtotime($detalles[0]->fecha)) }} 
                                </span>
                            </div>
                            <div class="col-4 text-center">
                                {{-- <span class="h5"> 
                                    <img src="{{ asset('img/iconos/date.svg') }}" alt="fecha" width="24" class="mb-1">
                                    Fecha de renovacion:
                                </span> --}}
                            </div>
                            <div class="col-4 text-center">
                                <span class="h5"> 
                                    @if ($detalles[0]->exc==true)
                                        Exclusivo: Si 
                                    @else
                                        Exclusivo: No
                                    @endif
                                </span>
                            </div>
                        </div>
                        <hr>
                        <br>
                        <div class="text-center">
                            <span class="h3">
                                Ingredientes
                            </span>
                        </div>
                        <br>
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
                                            @if ($detalle->descuento!=NULL)
                                                <b>{{$detalle->descuento}}%</b>
                                            @else
                                                <b>N/A</b>
                                            @endif
                                        </td>
                                    </tr> 
                                @endforeach
                                @foreach ($otros_ingredientes as $detalle)
                                    <tr class="text-center">
                                        <td><b>{{$detalle->cas}}</b></td>
                                        <td><b>{{$detalle->o_nombre}}</b></td>
                                        <td>
                                            @if ($detalle->descuento!=NULL)
                                                <b>{{$detalle->descuento}}%</b>
                                            @else
                                                <b>N/A</b>
                                            @endif
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
                                    <th scope="col">Pais</th>
                                    <th scope="col">Precio de envio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($metodo_envio as $metodo)
                                    <tr class="text-center">
                                        @switch($metodo->tipo)
                                            @case('t')
                                                <td><b> Envio Terrestre </b></td>
                                                @break
                                            @case('m')
                                                <td><b> Envio Maritimo </b></td>
                                                @break
                                            @case('a')
                                                <td><b> Envio Aereo </b></td>
                                                @break
                                        @endswitch
                                        <td><b>{{$metodo->duracion}} meses</b></td>
                                        <td><b>{{$metodo->pais}}</b></td>
                                        <td><b>{{$metodo->precio}}$</b></td>
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
                                    <th scope="col">N° cuotas</th>
                                    <th scope="col">Porcentaje por cuota</th>
                                    <th scope="col">Pago cada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($metodo_pago as $metodo)
                                    <tr class="text-center">
                                        @switch($metodo->tipo)
                                            @case('p')
                                                <td><b>Parcial</b></td>
                                                @break
                                            @case('c')
                                                <td><b>Contado</b></td>
                                                @break
                                        @endswitch

                                        @if ($metodo->cuotas==NULL)
                                            <td><b>1</b></td>
                                        @else
                                            <td><b>{{$metodo->cuotas}}</b></td>
                                        @endif

                                        @if ($metodo->porcentaje==NULL)
                                            <td><b>100 %</b></td>
                                        @else
                                            <td><b>{{$metodo->porcentaje}} %</b></td>
                                        @endif

                                        @if ($metodo->meses==NULL)
                                            <td><b></b></td>
                                        @else
                                            <td><b>{{$metodo->meses}} meses</b></td>
                                        @endif
                                    </tr> 
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        @if ($detalles[0]->razon!=NULL)
                            <span class="h4"> 
                                <b class="mr-2"> Razon de cancelación </b> 
                            </span>
                            <p class="h5">
                                <br>
                                {{$detalles[0]->razon}}
                            </p>
                        @endif
                        <br>
                    </div>
                </div>
                @if ($detalles[0]->razon==NULL)
                    <div class="row d-flex justify-content-center mt-4"> 
                        <a href="#" class="btn btn-primary mb-4"> 
                            <img src="{{ asset('img/iconos/add_white.svg') }}" alt="agregar" width="24">
                            Solicitar pedido 
                        </a>
                    </div>
                @endif
            </div>
            <div class="card-footer bg-primary text-white">
                <a href="{{ route('verContratos', ['id_prod' => $id_prod]) }}">
                    <img src="{{ asset('img/iconos/back.svg') }}" alt="atras" width="24">
                    <span class="text-white h6 ml-2 mt-1"> Volver a lista de Contratos </span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection