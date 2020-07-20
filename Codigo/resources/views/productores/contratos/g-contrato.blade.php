@extends('productores.layout-productor')

@section('head')
    <title> Perfumes | Generar Contrato </title>
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
    <div class="col-7">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center h4">
                Generación de Contrato
            </div>
            <div class="card-body" style="background-color: #FDFDFD">
                <div class="text-center">
                    <span class="h4"> 
                        <b class="mr-2"> Contrato con </b> 
                    </span>
                    @include('productores.contratos.imagenes-proveedores')
                </div>
                <hr>
                <span>
                    <h5>Ingredientes disponibles:</h5>
                </span>
                <br>
                <form action="/productor/{{$id_prod}}/contratos/generar/{{$id_prov}}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <table class="table table-striped border border-info">
                        <thead class="bg-primary text-white">
                            <tr  class="text-center">
                                <th scope="col">#cas</th>
                                <th scope="col">Nombre del Ingrediente</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detallesIng as $detalle)
                                <tr class="text-center">
                                    <td><b>{{$detalle->cas}}</b></td>
                                    <td><b>{{$detalle->i_nombre}}</b></td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value={{$detalle->i_cas}} id={{$detalle->i_cas}} name="ingredientes_esencia[]">
                                        </div>
                                    </td>
                                </tr> 
                            @endforeach
                            @foreach ($detallesOIng as $detalle)
                                <tr class="text-center">
                                    <td><b>{{$detalle->cas}}</b></td>
                                    <td><b>{{$detalle->o_nombre}}</b></td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value={{$detalle->o_cas}} id={{$detalle->o_cas}} name="otros_ingredientes[]">
                                        </div>
                                    </td>
                                </tr> 
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <span>
                        <h5>Escoja los metodos de envio que guste:</h5>
                    </span>
                    <table class="table table-striped border border-info">
                        <thead class="bg-primary text-white">
                            <tr  class="text-center">
                                <th scope="col">Tipo de envio</th>
                                <th scope="col">Duracion de envio</th>
                                <th scope="col">Pais de envio</th>
                                <th scope="col">Precio de envio</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detallesMEnvio as $detalle)
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
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value={{$detalle->id}} id={{$detalle->id}} name="metodos_envio[]">
                                        </div>
                                    </td>
                                </tr> 
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <span>
                        <h5>Escoja el metodo de pago:</h5>
                    </span>
                    <table class="table table-striped border border-info">
                        <thead class="bg-primary text-white">
                            <tr  class="text-center">
                                <th scope="col">Tipo de pago</th>
                                <th scope="col">N° de cuotas</th>
                                <th scope="col">Porcentaje por cuota</th>
                                <th scope="col">Pago cada</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detallesMPago as $detalle)
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

                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value={{$detalle->id}} id={{$detalle->id}} name="metodos_pago[]">
                                        </div>
                                    </td>
                                </tr> 
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="exc" id="exc" name="exc">
                        <label class="form-check-label"> Marcar para que el contrato sea exclusivo </label>
                    </div>
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
                        <button type="submit" class="btn btn-primary">
                            <img src="{{ asset('img/iconos/add_white.svg') }}" alt="agregar" width="24">
                            <span class="ml-2"> Generar </span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-primary text-white">
                <a href="{{ route('verContratos', ['id_prod' => $id_prod]) }}">
                    <img src="{{ asset('img/iconos/back.svg') }}" alt="atras" width="24">
                    <span class="text-white h6 ml-2 mt-1"> Volver a la Lista de Contratos </span>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection