<?php

namespace App\Http\Controllers\Api;

use App\Models\Cuenta;
use App\Http\Controllers\Controller;

class CuentaController extends Controller
{
    public function obtenerSaldo($pcuenta) {
        $cuenta = Cuenta::where("numero", $pcuenta)->get()->first();
        if (!$cuenta) {
            return response()->json(['status'=>'error 404','data'=> "La cuenta no existe"],404);
        } else {
            return response()->json(['status'=>'ok','data'=>$cuenta->saldo()],200);            
        }
    }
}
