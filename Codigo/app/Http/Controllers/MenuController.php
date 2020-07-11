<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function menuProductor($id_prod) {
        return view('productores.menu-productor',[
            'id_prod' => $id_prod
        ]);
    }

    public function menuProveedor($id_prov) {
        return view('proveedores.menu-proveedor',[
            'id_prov' => $id_prov
        ]);
    }
}
