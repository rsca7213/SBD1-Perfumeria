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
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Presentaciones</th>
                                    <th scope="col">Descuento</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ingredientes_esencia as $detalle)
                                    <tr class="text-center">
                                        <td><b>{{$detalle->cas}}</b></td>
                                        <td><b>{{$detalle->i_nombre}}</b></td>
                                        @if ($detalle->naturaleza=='n')
                                            <td><b>Esencia natrual</b></td>
                                        @else
                                            <td><b>Esencia sintetica</b></td>
                                        @endif
                                        <td>
                                            <img src="/img/iconos/list.svg"alt="ver" width="24" class="iconobtn" data-toggle="modal" data-target="#Detalles_i{{$detalle->i_cas}}">
                                            <!-- Modal para mostrar los detalles de un ingrediente -->
                                            <div class="modal fade" id="Detalles_i{{$detalle->i_cas}}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content" style="background-color: #F5F5F5">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"> <b> Presentaciones del Producto </b> </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                    <div class="modal-body h5 text-center">
                                                        <b> {{$detalle->i_nombre}} </b>
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
                                                                                <b>{{$presentacion->volumen}} ml</b>
                                                                            </td>
                                                                            <td>
                                                                                <b>{{$presentacion->precio}} $</b>
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
                                        <td><b>Componente</b></td>
                                        <td>
                                            <img src="/img/iconos/list.svg" alt="ver" width="24" class="iconobtn" data-toggle="modal" data-target="#Detalles_o{{$detalle->o_cas}}">
                                            <!-- Modal para mostrar los detalles de un ingrediente -->
                                            <div class="modal fade" id="Detalles_o{{$detalle->o_cas}}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content" style="background-color: #F5F5F5">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"> <b> Presentaciones del Producto </b> </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                    <div class="modal-body h5 text-center">
                                                        <b> {{$detalle->o_nombre}} </b>
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
                                                                                <b>{{$presentacion->volumen}} ml</b>
                                                                            </td>
                                                                            <td>
                                                                                <b>{{$presentacion->precio}} $</b>
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
                                            @if ($detalle->descuento!=NULL || $detalle->descuento==0)
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
                                    <th scope="col">Extra</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($metodo_envio as $metodo)
                                    <tr class="text-center">
                                        @switch($metodo->tipo)
                                            @case('t')
                                                <td><b> Terrestre </b></td>
                                                @break
                                            @case('m')
                                                <td><b> Maritimo </b></td>
                                                @break
                                            @case('a')
                                                <td><b> Aereo </b></td>
                                                @break
                                        @endswitch
                                        @if ($metodo->duracion==1)
                                            <td><b>{{$metodo->duracion}} mes</b></td>
                                        @else
                                            <td><b>{{$metodo->duracion}} meses</b></td>
                                        @endif
                                        <td><b>{{$metodo->pais}}</b></td>
                                        <td><b>{{$metodo->precio}} $</b></td>
                                        <td>
                                            <img src="/img/iconos/list.svg" alt="ver" width="24" class="iconobtn" data-toggle="modal" data-target="#Extras{{$metodo->id}}">
                                            <!-- Modal para mostrar los extras de un envio -->
                                            <div class="modal fade" id="Extras{{$metodo->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content" style="background-color: #F5F5F5">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"> <b> Extra de Envío </b> </h5>
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
                                                                    @if ($extra->id_envio==$metodo->id)
                                                                        <tr class="text-center">
                                                                            <td>
                                                                                <b>{{$extra->nombre}}</b>
                                                                            </td>
                                                                            <td>
                                                                                @if ($extra->duracion==1 || $extra->duracion==-1)
                                                                                    <b>{{$extra->duracion}} día</b>
                                                                                @else
                                                                                    <b>{{$extra->duracion}} días</b>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                <b>{{$extra->precio}} $</b>
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
                                            <td><b>N/A</b></td>
                                        @else
                                            @if ($metodo->meses==1)
                                                <td><b>{{$metodo->meses}} mes</b></td>
                                            @else
                                                <td><b>{{$metodo->meses}} meses</b></td>
                                            @endif
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
                @if ($detalles[0]->cancel==NULL)
                    <div class="row d-flex justify-content-center mt-4"> 
                        <a href="#" class="btn btn-primary mb-4"> 
                            <img src="{{ asset('img/iconos/add_white.svg') }}" alt="agregar" width="24">
                            Solicitar pedido 
                        </a>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <a href="#" data-toggle="modal" data-target="#Cancelar" class="btn btn-danger">
                            <img src="{{ asset('img/iconos/trash_white.svg') }}" alt="cancelar" width="24">
                            Cancelar 
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

<!-- Modal para cancelar Contrato -->
<div class="modal fade" id="Cancelar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background-color: #F5F5F5">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> <b> Cancelar Contrato </b> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body h5 text-center">
            <b> ¿Está seguro que desea cancelar el contrato?<br>Explique sus razones de cancelación si así lo desea </b>
        </div>
        <div class="modal-footer text-center justify-content-center">
            <form action="/productor/{{$id_prod}}/contratos/cancelar/{{$detalles[0]->fecha}}" method="POST">
                @method('DELETE')
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <textarea class="form-control" id="razon" name="razon" rows="10" cols="80"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-danger">
                        <img src="{{ asset('img/iconos/trash_white.svg') }}" alt="borrar" width="24" class="mb-1">
                        <span class="ml-2"> Cancelar Contrato </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Final Modal para cancelar Contrato -->

@endsection