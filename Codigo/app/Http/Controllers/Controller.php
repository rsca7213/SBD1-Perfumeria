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
}
