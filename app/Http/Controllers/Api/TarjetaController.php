<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Tarjeta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TarjetaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ptarjeta)
    {
        //

    }


    public function obtenerLimiteTarjeta($ptarjeta)
    {
        $cliente = User::find(auth()->user->id);
        $tarjeta = Tarjeta::where('numero', $ptarjeta)->get()->first();
        if (!$tarjeta) {
            return response()->json(['status' => 'error 404', 'data' => "La tarjeta no existe"], 404);
        }
        if ($cliente->id == $tarjeta->cliente->id) {
            return response()->json(['status' => 'ok', 'tarjeta' => $ptarjeta, 'limite' => $tarjeta->limite], 200);
        }
        return response()->json(['status' => 'error 403', 'data' => "El propietario no coincide"], 403);
    }


    public function obtenerTarjetas()
    {
        $cliente = auth()->user();
        $enviarcliente["id"] = $cliente->id;
        $enviarcliente["name"] = $cliente->name;
        $enviarcliente["email"] = $cliente->email;


        if ($cliente->tarjetas->count() > 0) {
            foreach ($cliente->tarjetas as $eltotarjeta) {
                $tarjetaaux["numero"] = $eltotarjeta->numero;
                $tarjetaaux["limite"] = $eltotarjeta->limite;
                $tarjetaaux["cuenta"] = $eltotarjeta->cuenta->numero;
                $tarjetas[] = $tarjetaaux;
            }
            return response()->json(['status' => 'ok 200', 'cliente' => $enviarcliente, 'data' => $tarjetas], 200);
        } else {
            return response()->json(["status" => "error 404", "data" => "No tiene tarjetas"], 404);
        }
    }
}
