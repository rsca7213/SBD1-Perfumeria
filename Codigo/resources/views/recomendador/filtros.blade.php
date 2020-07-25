@extends('recomendador.layout-recomendador')

@section('head')
    <title> Perfumes | Recomendador </title>
@endsection

@section('content')
<div class="row d-flex justify-content-center mt-4 mb-4">
    <div class="col-8">
        <div class="card shadow-lg">
            <div class="card-body" style="background-color: whitesmoke">
                <filtros-recomendador> </filtros-recomendador>
            </div>
        </div>
    </div>
</div>
@endsection