@extends('recomendador.layout-recomendador')

@section('head')
    <title> Perfumes | Recomendador </title>
@endsection

@section('content')
    <div class="row d-flex justify-content-center mt-4">
        <div class="col-8">
            <div class="card shadow-lg">
                <div class="card-body" style="background-color: whitesmoke">
                    <div class="row d-flex justify-content-center">
                        <div class="h3"> <b> ¡Bienvenido al recomendador de perfumes! </b> </div>
                    </div>
                    <div class="row d-flex justify-content-center h5 text-info">
                         <b> Encuentra tu fragancia ideal. </b>
                    </div>
                    <hr>
                    <div class="h5 mx-4"> 
                        <b>
                        A continuación se le planteará con una serie de preguntas que ayudarán a escoger
                        tu perfume ideal. El orden de las preguntas es el siguiente:
                        </b>
                    </div>
                    <div class="row ml-4 h5">
                        <span class="ml-4"> <b class="mr-1 text-primary"> 1. Género: </b> El perfume es para hombre, mujer o unisex. </span>
                    </div>
                    <div class="row ml-4 h5">
                        <span class="ml-4"> <b class="mr-1 text-primary"> 2. Edad: </b> Cual es el rango de edad de tu perfume ideal. </span>
                    </div>
                    <div class="row ml-4 h5">
                        <span class="ml-4"> <b class="mr-1 text-primary"> 3. Intensidad: </b> Cual es la intensidad de olor de tu perfume ideal. </span>
                    </div>
                    <div class="row ml-4 h5">
                        <span class="ml-4"> <b class="mr-1 text-primary"> 4. Carácter: </b> Cual es el carácter que deseas que genere tu perfume ideal. </span>
                    </div>
                    <div class="row ml-4 h5">
                        <span class="ml-4"> <b class="mr-1 text-primary"> 5. Familia Olfativa: </b> Qué familias de olores debería tener tu perfume ideal. </span>
                    </div>
                    <div class="row ml-4 h5">
                        <span class="ml-4"> <b class="mr-1 text-primary"> 6. Aromas más importantes: </b> Cuales son los aromas más importantes de tu perfume ideal. </span>
                    </div>
                    <div class="row ml-4 h5">
                        <span class="ml-4"> <b class="mr-1 text-primary"> 7. Preferencia de uso: </b> Cuando deseas usar tu perfume ideal. </span>
                    </div>
                    <div class="row ml-4 h5">
                        <span class="ml-4"> <b class="mr-1 text-primary"> 8. Personalidad: </b> Cuales son los aspectos de tu personalidad que deseas que el perfume emita. </span>
                    </div>
                    <hr>
                    <div class="row d-flex justify-content-center mt-4">
                        <a class="btn btn-primary btn-lg" href="{{ route('iniciarRecomendador') }}">
                            <img src="{{ asset('img/iconos/iniciar.svg') }}" width="30" class="mb-1"> 
                            <span class="ml-2 h4"> Iniciar </span>
                        </a>
                    </div>
                    <div class="row d-flex justify-content-center text-info">
                        <span> <b> Puede detenerse cuando desee, no todos los filtros son obligatorios. </b> </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection