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
            <link rel="stylesheet" href=" {{ asset('css/proveedores/layout-proveedor.css') }}">
            <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
            <link rel="icon" href=" {{ asset('img/icono-app.svg') }}">
            @yield('head')
        </head>
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-expand-lg navbar-light shadow" style="background-color: #F5F5F5">
                <a href="{{ url('/') }}" class="navbar-brand mx-4 pr-4">
                    @if ($id_prov == 5)
                        <img src="{{ asset('img/empresas/emerald.png') }}" alt="Emerald Performance Materials" width="270">
                    @endif
                    @if ($id_prov == 1)
                        <img src="{{ asset('img/empresas/firmenich.png') }}" alt="Firmenich" width="135">
                    @endif
                    @if ($id_prov == 4)
                        <img src="{{ asset('img/empresas/indesso.png') }}" alt="Indesso" width="150">
                    @endif
                    @if ($id_prov == 3)
                        <img src="{{ asset('img/empresas/privi.png') }}" alt="Privi Organics" width="140">
                    @endif
                    @if ($id_prov == 2)
                        <img src="{{ asset('img/empresas/keva.png') }}" alt="Kelhar & Co" width="70">
                    @endif
                    @if ($id_prov == 6)
                        <img src="{{ asset('img/empresas/prinova.png') }}" alt="Prinova Group" width="150">
                    @endif
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarID" aria-controls="navbarID" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarID">
                    <ul class="navbar-nav ml-auto">
                        @yield('nav')
                    </ul>
                </div>
            </nav>
            @yield('content')
        </div>
    </body>
</html>
