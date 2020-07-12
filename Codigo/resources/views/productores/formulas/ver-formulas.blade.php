@extends('productores.layout-productor')

@section('head')
    <title> Perfumes | Formulas </title>
@endsection

@section('nav')
    <li class="nav-item mx-2">
       <a href="{{ route('menuProductor', ['id_prod' => $id_prod]) }}" class="nav-item"> Menú Principal </a>
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="#" class="nav-item"> Contratos </a> 
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="#" class="nav-item"> Compras </a> 
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="{{ route('verFormulas', ['id_prod' => $id_prod]) }}" class="nav-item-active"> Formulas </a> 
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="#" class="nav-item"> Evaluaciones </a> 
    </li>
@endsection

@section('content')
    <div class="row d-flex justify-content-center mt-4 rounded">
        <div class="col-10">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center h4">
                    Ver Formulas de Evaluación
                </div>
                <div class="card-body" style="background-color: #FDFDFD">
                    <div class="row border-bottom" style="border-color: #707070">

                        <!-- para formulas iniciales -->
                        <div class="col-6 border-right" style="border-color: #707070">
                            <span class="h5"> 
                                <b class="mr-2"> Formula de Evaluación Inicial </b> 
                                @if ($form_inicial != [])
                                    <a href="{{ route('editarFormulaInicial', ['id_prod' => $id_prod]) }}"> <img src="{{ asset('img/iconos/edit.svg') }}" alt="editar" width="24" class="mb-1 iconobtn"> </a>
                                    <img src="{{ asset('img/iconos/trash.svg') }}" alt="borrar" width="24" class="mb-1 iconobtn"> 
                                @endif
                            </span>
                            <hr>
                            @if ($form_inicial != [])
                                <span class="h5"> 
                                    <img src="{{ asset('img/iconos/date.svg') }}" alt="fecha" width="24" class="mb-1">
                                    Fecha de creación: <span> {{ date("d/m/Y", strtotime($form_inicial[0]->fecha)) }} </span> 
                                </span>
                                <br>
                                <table class="table table-striped border border-info mt-2">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th scope="col"> Criterio </th>
                                            <th scope="col"> Descripción </th>
                                            <th scope="col" class="text-center"> Peso </th>
                                        </tr>
                                  </thead>
                                    <tbody>
                                        @for ($i = 0; $i <= 2; $i++)
                                            <tr> 
                                                <td> {{ $form_inicial[$i]->nombre }}</td>
                                                <td> {{ $form_inicial[$i]->desc }}</td>
                                                <td class="text-center"> {{ $form_inicial[$i]->peso }} %</td>
                                            </tr>
                                        @endfor
                                   </tbody>
                                </table>
                                <span class="h5"> 
                                    <img src="{{ asset('img/iconos/check_green.svg') }}" alt="exito" width="24" class="mb-1">
                                    Criterio de Aprobación: <span> {{ $form_inicial[3]->peso }} % </span> 
                                </span>
                                <hr>
                            @else 
                                <h5> No tiene una formula de evaluación inicial activa. </h5>
                                <a href="{{ route('crearFormulaInicial', ['id_prod' => $id_prod]) }}" class="btn btn-primary mb-4"> 
                                    <img src="{{ asset('img/iconos/add_white.svg') }}" alt="agregar" width="24">
                                    Crear Formula 
                                </a>
                            @endif
                        </div>
                        <!-- fin formula inicial -->

                        <!-- para formulas anuales -->
                        <div class="col-6">
                            <span class="h5"> 
                                <b class="mr-2"> Formula de Evaluación Anual </b> 
                                @if ($form_anual != [])
                                    <a href="{{ route('editarFormulaAnual', ['id_prod' => $id_prod]) }}"> <img src="{{ asset('img/iconos/edit.svg') }}" alt="editar" width="24" class="mb-1 iconobtn"> </a>
                                    <img src="{{ asset('img/iconos/trash.svg') }}" alt="borrar" width="24" class="mb-1 iconobtn">
                                @endif
                            </span>
                            <hr>
                            @if ($form_anual != [])
                                <span class="h5"> 
                                    <img src="{{ asset('img/iconos/date.svg') }}" alt="fecha" width="24" class="mb-1">
                                    Fecha de creación: <span> {{ date("d/m/Y", strtotime($form_inicial[0]->fecha)) }} </span> 
                                </span>
                                <br>
                                <table class="table table-striped border border-info mt-2">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th scope="col"> Criterio </th>
                                            <th scope="col"> Descripción </th>
                                            <th scope="col" class="text-center"> Peso </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i <= 3; $i++)
                                            <tr> 
                                                <td> {{ $form_anual[$i]->nombre }}</td>
                                                <td> {{ $form_anual[$i]->desc }}</td>
                                                <td class="text-center"> {{ $form_inicial[$i]->peso }} %</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                                <span class="h5"> 
                                    <img src="{{ asset('img/iconos/check_green.svg') }}" alt="exito" width="24" class="mb-1">
                                    Criterio de Aprobación: <span> {{ $form_anual[4]->peso }} %  </span> 
                                </span>
                            <hr>
                            @else
                            <h5> No tiene una formula de evaluación anual activa. </h5>
                            <a href="{{ route('crearFormulaAnual', ['id_prod' => $id_prod]) }}" class="btn btn-primary mb-4"> 
                                <img src="{{ asset('img/iconos/add_white.svg') }}" alt="agregar" width="22">
                                <span class="ml-2"> Crear Formula </span> 
                            </a>
                            @endif
                        </div>
                        <!-- fin formulas anuales -->

                    </div>
                    <div class="row d-flex justify-content-center mt-4"> 
                        <span class="h5"> 
                            <img src="{{ asset('img/iconos/gear.svg') }}" alt="escala" width="24" class="mb-1">
                            <span class="ml-2"> 
                                Escala de Evaluación:
                                @if ($escala != [])
                                    {{ $escala[0]->ri }} a {{ $escala[0]->rf }}
                                    <a href="{{ route('editarEscala', ['id_prod' => $id_prod]) }}"> <img src="{{ asset('img/iconos/edit.svg') }}" alt="editar" width="24" class="mb-1 iconobtn"> </a>
                                    <img src="{{ asset('img/iconos/trash.svg') }}" alt="borrar" width="24" class="mb-1 iconobtn">
                                @else
                                    <a href="{{ route('crearEscala', ['id_prod' => $id_prod]) }}"> <img src="{{ asset('img/iconos/add.svg') }}" alt="agregar" width="24" class="mb-1 iconobtn"> </a>
                                @endif 
                            </span>
                        </span>
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