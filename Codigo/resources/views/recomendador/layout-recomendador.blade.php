<!DOCTYPE html>
<html lang="es">
    <head>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <script src="{{ asset('js/app.js') }}" defer></script>
            <link rel="dns-prefetch" href="//fonts.gstatic.com">
            <link href="{{ asset('css/app.css') }}" rel="stylesheet">
            <link rel="stylesheet" href=" {{ asset('css/recomendador/recomendador.css') }}">
            <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
            <link rel="icon" href=" {{ asset('img/icono-app.svg') }}">
            @yield('head')
        </head>
    </head>
    <body>
        <div id="app">
            <div class="container-fluid">
                <div class="row d-flex">
                    <a href="/" class="mt-2 ml-2 banner">  
                        <img src="{{ asset('img/icono-app.svg') }}" width="72">
                        <span class="ml-2 h2 mt-4"> Recomendador de Perfumes </span>
                    </a>
                </div>
            </div>
            @yield('content')
        </div>
    </body>
</html>