<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CuentaController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\TarjetaController;
use App\Http\Controllers\Api\MovimientoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//autorizacion por usuario/pass
Route::post('/register', [AuthController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'loginUser']);
Route::get("/error/login", function (){
    return response()->json(["status" => false, "message"=>"Necesita darse de alta o autentificarse mediante la API"],401);
})->name("login");


//Ingreso o extracción desde cajero
Route::post("tarjetas/{tarjeta}/movimientos", [MovimientoController::class, "realizarMovimientoCajero"]);

// Obtener saldo de cuenta
Route::get("cuentas/{cuenta}/saldo", [CuentaController::class, "obtenerSaldo"]);

Route::middleware('auth:sanctum')->group( function () {
// Obtener las cuentas de un cliente
    Route::get("clientes/cuentas", [ClienteController::class, "obtenerCuentas"]);
// Obtener las tarjetas de un cliente
    Route::get("clientes/tarjetas", [TarjetaController::class, "obtenerTarjetas"]);
//obtenerLimiteTarjeta
Route::get("/tarjeta/{tarjeta}/limite", [TarjetaController::class, "obtenerLimiteTarjeta"]);
//cambiar PIN
Route::patch('/tarjetas/{tarjeta}/pin', [TarjetaController::class, 'update']);  // o changePin
});

//obtenerMovimientosTarjeta
Route::get("/cuenta/{cuenta}/tarjeta/{tarjeta}/movimientos", [MovimientoController::class, "index"]);

//obtenerMovimientosCuenta
//obtenerMovimientosCliente


//autorizacion por tarjeta+PIN
Route::post("/logintarjetapin", [AuthController::class, "loginTarjetaPIN"]);


//cambiarPIN




Route::middleware("auth:sanctum")->get("/pediralgo", function (){
    // return request()->server("REMOTE_ADDR");
    // return request()->header("authorization");
    //  dd(auth());
            if(!request()->user()->currentAccessToken()->expires_at->isPast()) {
                return "hola";
            } else {
                return "adios";
            };

    });
