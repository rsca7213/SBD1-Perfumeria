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
    <div class="col-10">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center h4">
                Contratos
            </div>
            <div class="card-body" style="background-color: #FDFDFD">
                <div class="row border-bottom" style="border-color: #707070">
                    {{-- Contratos vigentes --}}
                    <div class="col-6 border-right" style="border-color: #707070">
                        <span class="h5"> 
                            <b class="mr-2"> Contratos vigentes </b> 
                        </span>
                        <hr>
                        @if ($contratosVigentes != [])
                            <table class="table table-striped border border-info">
                                <thead class="bg-primary text-white">
                                    <tr  class="text-center align-items-center">
                                        <th scope="col">Fecha creación</th>
                                        <th scope="col">Productor</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contratosVigentes as $contrato)
                                        <tr class="text-center">
                                            <td>{{ date("d/m/Y", strtotime($contrato->fecha)) }}</td>
                                            <td>{{$contrato->prod}}</td>
                                            <td>
                                                <a href="/proveedor/{{$id_prov}}/contratos/detalle/{{$contrato->id_prod}}/{{$contrato->fecha}}" class="btn btn-sm btn-primary">
                                                    <img src="{{ asset('/img/iconos/list_white.svg') }}" width="24" class="mb-1">
                                                    <span class="ml-2"> Detalles </span>
                                                </a>
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
                    {{-- Solicitud de contratos --}}
                    <div class="col-6" >
                        <span class="h5"> 
                            <b class="mr-2"> Solucitud de Contrato </b> 
                        </span>
                        <hr>
                        @if ($contratosSolicitud != [] || $contratosSolicitudRenovacion != [])
                            <table class="table table-striped border border-info">
                                <thead class="bg-primary text-white">
                                    <tr  class="text-center align-items-center">
                                        <th scope="col">Fecha creación</th>
                                        <th scope="col">Productor</th>
                                        <th scope="col">Petición</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contratosSolicitud as $contrato)
                                        <tr class="text-center">
                                            <td>{{ date("d/m/Y", strtotime($contrato->fecha)) }}</td>
                                            <td>{{$contrato->prod}}</td>
                                            <td><span class="ml-2"> Generar </span></td>
                                            <td>
                                                <a href="/proveedor/{{$id_prov}}/contratos/confirmar/{{$contrato->id_prod}}/{{$contrato->fecha}}" class="btn btn-sm btn-primary">
                                                    <img src="{{ asset('/img/iconos/list_white.svg') }}" width="24" class="mb-1">
                                                    <span class="ml-2"> Detalles </span>
                                                </a>
                                            </td>
                                        </tr> 
                                    @endforeach
                                    @foreach ($contratosSolicitudRenovacion as $contrato)
                                        <tr class="text-center">
                                            <td>{{ date("d/m/Y", strtotime($contrato->fecha)) }}</td>
                                            <td>{{$contrato->prod}}</td>
                                            <td><span class="ml-2"> Renovar </span></td>
                                            <td>
                                                <a href="/proveedor/{{$id_prov}}/contratos/confirmar/{{$contrato->id_prod}}/{{$contrato->fecha}}" class="btn btn-sm btn-primary">
                                                    <img src="{{ asset('/img/iconos/list_white.svg') }}" width="24" class="mb-1">
                                                    <span class="ml-2"> Detalles </span>
                                                </a>
                                            </td>
                                        </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                        @else 
                            <h5> No tienes solicitudes de contratos en estos momentos. </h5>
                        @endif
                    </div>
                    {{-- Final Solucitud de Contratos --}}
                </div>
            </div>
            <div class="card-footer bg-primary text-white">
                <a href="{{ route('menuProveedor', ['id_prov' => $id_prov]) }}">
                    <img src="{{ asset('img/iconos/back.svg') }}" alt="atras" width="24">
                    <span class="text-white h6 ml-2 mt-1"> Volver al Menú Principal </span>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection