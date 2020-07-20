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
                Contratos
            </div>
            <div class="card-body" style="background-color: #FDFDFD">
                <div class="row border-bottom" style="border-color: #707070">
                    {{-- Contratos vigentes --}}
                    <div class="col-7 border-right" style="border-color: #707070">
                        <span class="h5"> 
                            <b class="mr-2"> Contratos vigentes </b> 
                        </span>
                        <hr>
                        @if ($contratosVigentes != [])
                            <table class="table table-striped border border-info">
                                <thead class="bg-primary text-white">
                                    <tr  class="text-center">
                                        <th scope="col">Fecha creación</th>
                                        <th scope="col">Proveedor</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contratosVigentes as $contrato)
                                        <tr class="text-center">
                                            <td><b>{{ date("d/m/Y", strtotime($contrato->fecha)) }}</b></td>
                                            <td><b>{{$contrato->prov}}</b></td>
                                            <td>
                                                <a href="/productor/{{$id_prod}}/contratos/detalle/{{$contrato->id_prov}}/{{$contrato->fecha}}" class="btn btn-primary">Detalles</a>
                                                <a href="#" class="btn btn-success">Renovar</a>
                                                <a href="#" data-toggle="modal" data-target="#Cancelar" class="btn btn-danger">Cancelar</a>

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
                                                            <form action="/productor/{{$id_prod}}/contratos/cancelar/{{$contrato->fecha}}" method="POST">
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

                                            </td>
                                        </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                        @else 
                            <h5> No tiene contratos activos en estos momentos. </h5>
                        @endif
                    </div>
                    {{-- Final Contratos vigentes --}}
                    {{-- Contratos no vigentes --}}
                    <div class="col-5" >
                        <span class="h5"> 
                            <b class="mr-2"> Contratos no vigentes </b> 
                        </span>
                        <hr>
                        @if ($contratosNoVigentes != [])
                            <table class="table table-striped border border-info">
                                <thead class="bg-primary text-white">
                                    <tr  class="text-center">
                                        <th scope="col">Fecha creación</th>
                                        <th scope="col">Proveedor</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contratosNoVigentes as $contrato)
                                        <tr class="text-center">
                                            <td><b>{{ date("d/m/Y", strtotime($contrato->fecha)) }}</b></td>
                                            <td><b>{{$contrato->prov}}</b></td>
                                            <td>
                                                <a href="/productor/{{$id_prod}}/contratos/detalle/{{$contrato->id_prov}}/{{$contrato->fecha}}" class="btn btn-primary">Detalles</a>
                                            </td>
                                        </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                        @else 
                            <h5> No has realizado contratos o todos tus contratos aún son vigentes. </h5>
                        @endif
                    </div>
                    {{-- Final Contratos no vigentes --}}
                </div>
                <div class="row d-flex justify-content-center mt-4"> 
                    <a href="#" data-toggle="modal" data-target="#Generar" class="btn btn-primary mb-4"> 
                        <img src="{{ asset('img/iconos/add_white.svg') }}" alt="agregar" width="24">
                        Generar Contrato 
                    </a>
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

<!-- Modal para generar Contrato -->
<div class="modal fade" id="Generar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background-color: #F5F5F5">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> <b> Generar Contrato </b> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body h5 text-center">
            <b> Escoja al proveedor a contratar </b>
            <br>
            @foreach ($proveedores as $proveedor)
                <br>
                @switch($proveedor->id_prov)
                    @case(1)
                        <a href="/productor/{{$id_prod}}/contratos/generar/{{$proveedor->id_prov}}">
                            <img src=" {{ asset('img/empresas/firmenich.png') }}" width="150" alt="Firmenich" >
                        </a>
                        @break
                    @case(2)
                        <a href="/productor/{{$id_prod}}/contratos/generar/{{$proveedor->id_prov}}">
                            <img src=" {{ asset('img/empresas/keva.png') }}" width="100" alt="Kelhar & Co" >
                        </a>
                        @break
                    @case(3)
                        <a href="/productor/{{$id_prod}}/contratos/generar/{{$proveedor->id_prov}}">
                            <img src=" {{ asset('img/empresas/privi.png') }}" width="150" alt="Privi Organics" >
                        </a>
                        @break
                    @case(4)
                        <a href="/productor/{{$id_prod}}/contratos/generar/{{$proveedor->id_prov}}">
                            <img src=" {{ asset('img/empresas/indesso.png') }}" width="150" alt="Indesso" >
                        </a>
                        @break
                    @case(5)
                        <a href="/productor/{{$id_prod}}/contratos/generar/{{$proveedor->id_prov}}">
                            <img src=" {{ asset('img/empresas/emerald.png') }}" width="150" alt="Emerald Performance Materials" >
                        </a>
                        @break
                    @case(6)
                        <a href="/productor/{{$id_prod}}/contratos/generar/{{$proveedor->id_prov}}">
                            <img src=" {{ asset('img/empresas/prinova.png') }}" width="150" alt="Prinova Group" >
                        </a>
                        @break
                @endswitch
                <br>
            @endforeach
        </div>
    </div>
</div>
<!-- Final Modal para generar Contrato -->

@endsection