<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title> Perfumes | Página de Inicio </title>
        <script src="{{ asset('js/app.js') }}" defer></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href=" {{ asset('css/index/index.css') }}">
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
        <link rel="icon" href=" {{ asset('img/icono-app.svg') }}">
    </head>
    <body>
        <div class="container-fluid" id="app"> 
            <div class="row d-flex justify-content-center"> 
                <div class="col text-center">
                    <h2> Proveedores </h2> 
                </div>
            </div>
            <div class="row mt-1"> 
                <div class="col-4 justify-content-center text-center mt-4 pt-2">
                    <a href="{{ route('menuProveedor', ['id_prov' => 5]) }}"><img src=" {{ asset('img/empresas/emerald.png') }}" width="350" alt="Emerald Performance Materials" ></a>
                </div>
                <div class="col-4 justify-content-center text-center">
                    <a href="{{ route('menuProveedor', ['id_prov' => 1]) }}"><img src=" {{ asset('img/empresas/firmenich.png') }}" width="275" alt="Firmenich"></a>
                </div>
                <div class="col-4 justify-content-center text-center pt-4">
                    <a href="{{ route('menuProveedor', ['id_prov' => 4]) }}"><img src=" {{ asset('img/empresas/indesso.png') }}" width="275" alt="Indesso"></a>
                </div>
            </div>
            <div class="row mt-1"> 
                <div class="col-4 justify-content-center text-center">
                    <a href="{{ route('menuProveedor', ['id_prov' => 3]) }}"><img src=" {{ asset('img/empresas/privi.png') }}" width="220" alt="Privi Organics" ></a>
                </div>
                <div class="col-4 justify-content-center text-center">
                    <a href="{{ route('menuProveedor', ['id_prov' => 2]) }}"><img src=" {{ asset('img/empresas/keva.png') }}" width="150" alt="Kelhar & Co"></a>
                </div>
                <div class="col-4 justify-content-center text-center mt-4">
                    <a href="{{ route('menuProveedor', ['id_prov' => 6]) }}"><img src=" {{ asset('img/empresas/prinova.png') }}" width="245" alt="Prinova Group"></a>
                </div>
            </div>
        </div>
        <hr>
        <div class="row d-flex justify-content-center"> 
            <div class="col text-center">
                <h2> Productores </h2> 
            </div>
        </div>
        <div class="row mt-1"> 
            <div class="col-4 justify-content-center text-center mt-4 pt-2">
                <a href="{{ route('menuProductor', ['id_prod' => 1]) }}"> <img src=" {{ asset('img/empresas/givaudan.png') }}" width="220" alt="Givaudan" > </a>
            </div>
            <div class="col-4 justify-content-center text-center">
                <a href="{{ route('menuProductor', ['id_prod' => 2]) }}"><img src=" {{ asset('img/empresas/memphis.png') }}" width="160" alt="Memphis"></a>
            </div>
            <div class="col-4 justify-content-center text-center">
                <a href="{{ route('menuProductor', ['id_prod' => 3]) }}"><img src=" {{ asset('img/empresas/chanel.png') }}" width="200" alt="Chanel"></a>
            </div>
        </div>
        <hr>
        <div class="row d-flex justify-content-center"> 
            <div class="col text-center">
                <a href="{{ route('inicioRecomendador') }}" class="recomendador">
                    <img src="{{ asset('img/icono-app.svg') }}" alt="Recomendador de Perfumes" width="70">
                    <h2> Recomendador de Perfumes </h2> 
                </a>
            </div>
        </div>
    </body>
</html>
