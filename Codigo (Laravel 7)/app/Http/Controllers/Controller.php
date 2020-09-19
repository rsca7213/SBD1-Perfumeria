<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function stringifyCas ($cas) {
        $cas = (string)$cas;
        $checksum = $cas[strlen($cas) - 1];
        $val1 = $cas[strlen($cas) - 2];
        $val2 = $cas[strlen($cas) - 3];
        $cas[strlen($cas) - 3] = "-";
        $cas[strlen($cas) - 2] = $val2;
        $cas[strlen($cas) - 1] = $val1;
        $cas[strlen($cas)] = "-";
        $cas[strlen($cas)] = $checksum;
        return $cas;
    }

    public function separarPalabrasClaves ($palabras) {
        $car = [];
        $ar = [];
        $per = [];

        $arrCar = ["Informal","Natural","Clasico","Seductor","Moderno"];
        $arrAr = ["Floral","Frutal","Verde","Herbal","Citrico","Herbal aromatico","Amaderado","Otros"];  

        foreach ($palabras as $pal) {
            
            if (in_array($pal->palabra,$arrCar)) {
                array_push($car,$pal->palabra);
            }
            else if (in_array($pal->palabra,$arrAr)) {
                array_push($ar,$pal->palabra);
            }
            else {
                array_push($per,$pal->palabra);
            }
        }

        sort($car);
        sort($ar);
        sort($per);

        return [$car,$ar,$per];
    }
}
