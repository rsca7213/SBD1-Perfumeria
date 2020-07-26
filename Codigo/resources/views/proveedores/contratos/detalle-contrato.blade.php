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
        <a href="{{ route('verPedidos', ['id_prov' => $id_prov]) }}" class="nav-item"> Pedidos </a> 
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
                            @include('proveedores.contratos.imagenes-productores')
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
                                @if ($flag && $detalles[0]->cancelacion==false)
                                    <span class="h5"> 
                                        <img src="{{ asset('img/iconos/date.svg') }}" alt="fecha" width="24" class="mb-1">
                                        Fecha de renovacion: {{ date("d/m/Y", strtotime($detalles[0]->renovacion)) }}
                                    </span>
                                @endif
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
                                        <td>{{$detalle->cas}}</td>
                                        <td>{{$detalle->i_nombre}}</td>
                                        @if ($detalle->naturaleza)
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
                                                            <h5 class="modal-title" id="exampleModalLabel"> Presentaciones del Producto  </h5>
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
                                        <td>
                                            @if ($detalle->descuento!=NULL && $detalle->descuento>0)
                                                {{$detalle->descuento}} %
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr> 
                                @endforeach
                                @foreach ($otros_ingredientes as $detalle)
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
                                                            <h5 class="modal-title" id="exampleModalLabel"> Presentaciones del Producto  </h5>
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
                                        <td>
                                            @if ($detalle->descuento!=NULL && $detalle->descuento>0)
                                                {{$detalle->descuento}} %
                                            @else
                                                N/A
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
                                                <td> Terrestre </td>
                                                @break
                                            @case('m')
                                                <td> Maritimo </td>
                                                @break
                                            @case('a')
                                                <td> Aereo </td>
                                                @break
                                        @endswitch
                                        @if ($metodo->duracion==1)
                                            <td>{{$metodo->duracion}} día</td>
                                        @else
                                            <td>{{$metodo->duracion}} días</td>
                                        @endif
                                        <td>{{$metodo->pais}}</td>
                                        <td>{{$metodo->precio}} $</td>
                                        <td>
                                            <img src="/img/iconos/list.svg" alt="ver" width="24" class="iconobtn" data-toggle="modal" data-target="#Extras{{$metodo->id}}">
                                            <!-- Modal para mostrar los extras de un envio -->
                                            <div class="modal fade" id="Extras{{$metodo->id}}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                                                    @if ($extra->id_envio==$metodo->id)
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
                                                <td>Parcial</td>
                                                @break
                                            @case('c')
                                                <td>Completo</td>
                                                @break
                                        @endswitch

                                        @if ($metodo->cuotas==NULL)
                                            <td>1</td>
                                        @else
                                            <td>{{$metodo->cuotas}}</td>
                                        @endif

                                        @if ($metodo->porcentaje==NULL || $metodo->porcentaje==0)
                                            <td>100 %</td>
                                        @else
                                            <td>{{$metodo->porcentaje}} %</td>
                                        @endif

                                        @if ($metodo->meses==NULL)
                                            <td>N/A</td>
                                        @else
                                            @if ($metodo->meses==1)
                                                <td>{{$metodo->meses}} mes</td>
                                            @else
                                                <td>{{$metodo->meses}} meses</td>
                                            @endif
                                        @endif
                                    </tr> 
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{-- @if ($ingredientes_esencia[0]->razon!=NULL)
                            <span class="h4"> 
                                <b class="mr-2"> Razon de cancelación </b> 
                            </span>
                            <p class="h5">
                                <br>
                                {{$ingredientes_esencia[0]->razon}}
                            </p>
                        @endif --}}
                        <br>
                    </div>
                </div>
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