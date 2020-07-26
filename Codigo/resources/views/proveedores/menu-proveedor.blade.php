@extends('proveedores.layout-proveedor')

@section('head')
    <title> Perfumes | Menú Proveedor </title>
@endsection

@section('nav')
    <li class="nav-item mx-2">
       <a href="{{ route('menuProveedor', ['id_prov' => $id_prov]) }}" class="nav-item-active"> Menú Principal </a>
    </li>
    <span class="nav-item"> | </span>
    <li class="nav-item mx-2"> 
        <a href="{{ route('verContratosPv', ['id_prov' => $id_prov]) }}" class="nav-item"> Contratos </a>  
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
@endsection