@extends('productores.layout-productor')

@section('head')
    <title> Perfumes | Generar Contrato </title>
@endsection

@section('nav')
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
<div class="row d-flex justify-content-center my-4 rounded">
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
                                <th>Tipo</th>
                                <th>Presentaciones</th>
                                <th>Añadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ingsDisponibles as $detalle)
                                <tr class="text-center">
                                    <td>{{$detalle->cas}}</td>
                                    <td>{{$detalle->i_nombre}}</td>
                                    @if ($detalle->naturaleza=='n')
                                        <td>Esencia natural</td>
                                    @else
                                        <td>Esencia sintetica</td>
                                    @endif
                                    <td>
                                        <img src="/img/iconos/list.svg"alt="ver" width="24" class="iconobtn" data-toggle="modal" data-target="#Detalles_i{{$detalle->i_cas}}">
                                        <!-- Modal para mostrar los detalles de un ingrediente -->
                                        <div class="modal fade" id="Detalles_i{{$detalle->i_cas}}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content" style="background-color: #F5F5F5">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">  Presentaciones del Producto  </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                <div class="modal-body h5 text-center">
                                                    {{$detalle->i_nombre}}
                                                    <br>
                                                    <br>
                                                    <table class="table table-striped border border-info">
                                                        <thead class="bg-primary text-white">
                                                            <tr  class="text-center">
                                                                <th scope="col">Volumen</th>
                                                                <th scope="col">Precio</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($presentIng as $presentacion)
                                                                @if ($presentacion->i_cas==$detalle->i_cas)
                                                                    <tr class="text-center">
                                                                        <td>
                                                                            {{$presentacion->volumen}} ml
                                                                        </td>
                                                                        <td>
                                                                            {{$presentacion->precio}} $
                                                                        </td>
                                                                    </tr> 
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @if ($detallesIngContrato != [])
                                        @foreach ($detallesIngContrato as $ingcontrato)
                                            @if ($ingcontrato->i_cas==$detalle->i_cas)
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value={{$detalle->i_cas}} id={{$detalle->i_cas}} name="ingredientes_esencia[]" checked>
                                                    </div>
                                                </td>
                                            @endif
                                        @endforeach
                                        @foreach ($detallesIng as $ingrediente)
                                            @if ($ingrediente->i_cas==$detalle->i_cas)
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value={{$detalle->i_cas}} id={{$detalle->i_cas}} name="ingredientes_esencia[]">
                                                    </div>
                                                </td>
                                            @endif
                                        @endforeach
                                    @else
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value={{$detalle->i_cas}} id={{$detalle->i_cas}} name="ingredientes_esencia[]">
                                            </div>
                                        </td>
                                    @endif
                                </tr> 
                            @endforeach
                            @foreach ($otrosIngsDisponibles as $detalle)
                                <tr class="text-center">
                                    <td>{{$detalle->cas}}</td>
                                    <td>{{$detalle->o_nombre}}</td>
                                    <td>Componente</td>
                                    <td>
                                        <img src="/img/iconos/list.svg" alt="ver" width="24" class="iconobtn" data-toggle="modal" data-target="#Detalles_o{{$detalle->o_cas}}">
                                        <!-- Modal para mostrar los detalles de un ingrediente -->
                                        <div class="modal fade" id="Detalles_o{{$detalle->o_cas}}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content" style="background-color: #F5F5F5">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">  Presentaciones del Producto  </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                <div class="modal-body h5 text-center">
                                                    {{$detalle->o_nombre}} 
                                                    <br>
                                                    <br>
                                                    <table class="table table-striped border border-info">
                                                        <thead class="bg-primary text-white">
                                                            <tr  class="text-center">
                                                                <th scope="col">Volumen</th>
                                                                <th scope="col">Precio</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($presentOIng as $presentacion)
                                                                @if ($presentacion->o_cas==$detalle->o_cas)
                                                                    <tr class="text-center">
                                                                        <td>
                                                                            {{$presentacion->volumen}} ml
                                                                        </td>
                                                                        <td>
                                                                            {{$presentacion->precio}} $
                                                                        </td>
                                                                    </tr> 
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @if ($detallesOIngContrato != [])
                                        @foreach ($detallesOIngContrato as $ingcontrato)
                                            @if ($ingcontrato->o_cas==$detalle->o_cas)
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value={{$detalle->o_cas}} id={{$detalle->o_cas}} name="otros_ingredientes[]" checked>
                                                    </div>
                                                </td>
                                            @endif
                                        @endforeach
                                        @foreach ($detallesOIng as $ingrediente)
                                            @if ($ingrediente->o_cas==$detalle->o_cas)
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value={{$detalle->o_cas}} id={{$detalle->o_cas}} name="otros_ingredientes[]">
                                                    </div>
                                                </td>
                                            @endif
                                        @endforeach
                                    @else
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value={{$detalle->o_cas}} id={{$detalle->o_cas}} name="otros_ingredientes[]">
                                            </div>
                                        </td>
                                    @endif
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
                                <th>Extra</th>
                                <th>Añadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detallesMEnvio as $detalle)
                                <tr class="text-center">
                                    @switch($detalle->tipo)
                                        @case('m')
                                            <td>Maritimo</td>
                                            @break
                                        @case('t')
                                            <td>Terrestre</td>
                                            @break
                                        @case('a')
                                            <td>Aereo</td>
                                            @break
                                    @endswitch
                                    @if ($detalle->duracion==1)
                                        <td>{{$detalle->duracion}} día</td> 
                                    @else
                                        <td>{{$detalle->duracion}} días</td>
                                    @endif
                                    <td>{{$detalle->pais}}</td>
                                    <td>{{$detalle->precio}} $</td>
                                    <td>
                                        <img src="/img/iconos/list.svg" alt="ver" width="24" class="iconobtn" data-toggle="modal" data-target="#Extras{{$detalle->id}}">
                                        <!-- Modal para mostrar los extras de un envio -->
                                        <div class="modal fade" id="Extras{{$detalle->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content" style="background-color: #F5F5F5">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">  Extra de Envío  </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                <div class="modal-body h5 text-center">
                                                    <table class="table table-striped border border-info">
                                                        <thead class="bg-primary text-white">
                                                            <tr  class="text-center">
                                                                <th scope="col">Nombre</th>
                                                                <th scope="col">Duración</th>
                                                                <th scope="col">Precio</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($extrasMEnvio as $extra)
                                                                @if ($extra->id_envio==$detalle->id)
                                                                    <tr class="text-center">
                                                                        <td>
                                                                            {{$extra->nombre}}
                                                                        </td>
                                                                        <td>
                                                                            @if ($extra->duracion==1 || $extra->duracion==-1)
                                                                                {{$extra->duracion}} día
                                                                            @else
                                                                                {{$extra->duracion}} días
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            {{$extra->precio}} $
                                                                        </td>
                                                                    </tr> 
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
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
                        <h5>Escoja los metodo de pago:</h5>
                    </span>
                    <table class="table table-striped border border-info">
                        <thead class="bg-primary text-white">
                            <tr  class="text-center">
                                <th scope="col">Tipo de pago</th>
                                <th scope="col">N° de cuotas</th>
                                <th scope="col">Porcentaje por cuota</th>
                                <th scope="col">Pago cada</th>
                                <th>Añadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detallesMPago as $detalle)
                                <tr class="text-center">

                                    @switch($detalle->tipo)
                                        @case('p')
                                            <td>Parcial</td>
                                            @break
                                        @case('c')
                                            <td>Completo</td>
                                            @break
                                    @endswitch

                                    @if ($detalle->cuotas==NULL)
                                        <td>1</td>
                                    @else
                                        <td>{{$detalle->cuotas}}</td>
                                    @endif

                                    @if ($detalle->porcentaje==NULL)
                                        <td>100 %</td>
                                    @else
                                        <td>{{$detalle->porcentaje}} %</td>
                                    @endif

                                    @if ($detalle->meses==NULL)
                                        <td>N/A</td>
                                    @else
                                        @if ($detalle->meses==1)
                                            <td>{{$detalle->meses}} mes</td>
                                        @else
                                            <td>{{$detalle->meses}} meses</td>
                                        @endif
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