<?php

namespace App\Http\Controllers\Api;

use App\Models\Cuenta;
use App\Models\Tarjeta;
use App\Models\Movimiento;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $pcliente, $pcuenta)
    {
        //
        $cadena ="vcg";
        $cuenta = Cuenta::whereRaw(' id = "' . $pcuenta . '"')->get()->first();
        return $pcliente;
        if ($cuenta->cliente_id == $pcliente) {
            foreach ($cuenta->tarjetas as $tarjeta) {
                foreach ($tarjeta->movimientos() as $movimiento) {
                    $cadena .= "Cliente:" . $movimiento->Cliente()->nombre . " >> cuenta: ".
                        $movimiento->cuenta->numero . " >> tarjeta:  " . $movimiento->tarjeta->numero .
                        " >> cantidad: " . $movimiento->cantidad . " €<br>";
                }
            }
            return response()->json(['status'=>'ok','data'=>$cadena],200);
            //return $cadena;
        }

        return "";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sacarDinero(Request $request, $pcliente, $ptarjeta, $ppin)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    public function realizarMovimientoCajero($ptarjeta) {
        $cantidad = request()->cantidad;

        // La tarjeta existe
        $tarjeta = Tarjeta::where("numero",$ptarjeta)->get()->first();
        if (!($tarjeta)) {
            return response()->json(['status'=> 'error 404', 'data'=>"La tarjeta no existe"],404);
        }

        // No supera el límite
        if (($cantidad < 0) and ($tarjeta->limite < abs($cantidad))) {
            return response()->json(['status'=> 'error 403', 'data'=>"La cantidad supera el limite de la tarjeta"],404);
        }

        // Tiene saldo
        if (($cantidad < 0) and ($tarjeta->cuenta->saldo() < abs($cantidad))) {
            return response()->json(['status'=> 'error 403', 'data'=>"La cantidad supera el saldo en la cuenta"],404);
        }

        // La fecha de validez de la tarjeta es posterior
        if (! $tarjeta->nocaducada()) {
            return response()->json(['status'=> 'error 403', 'data'=>"La tarjeta está caducada"],404);
        }

        $movimiento = new Movimiento;
        $movimiento->cantidad = request()->cantidad;
        $movimiento->tarjeta_id = $tarjeta->id;
        $movimiento->fecha = now();
        $movimiento->save();
        if ($cantidad > 0) {
            return response()->json(['status'=> 'éxito 200', 'data'=>"La cantidad se ha ingresado satisfactoriamente"],200);
        } else {
            return response()->json(['status'=> 'éxito 200', 'data'=>"La cantidad se ha extraido satisfactoriamente"],200);
        }
    }
}
