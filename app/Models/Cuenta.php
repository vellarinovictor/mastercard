<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cuenta extends Model
{
    use HasFactory;

    public function tarjetas () {
        return $this->hasMany(Tarjeta::class);
    }
    public function cliente() {
        return $this->belongsTo(User::class);
    }

    public function saldo() {
        $sentenciaSQL = "SELECT 
        cuentas.numero as cuenta, (cuentas.saldoinicial - sum(movimientos.cantidad)) as saldo FROM cuentas
        LEFT JOIN tarjetas ON tarjetas.cuenta_id = cuentas.id
        LEFT JOIN movimientos ON movimientos.tarjeta_id = tarjetas.id
        GROUP BY (cuentas.id) HAVING  cuentas.id = '" . $this->id . "'";
        $respuesta = DB::select($sentenciaSQL);
        return $respuesta[0]->saldo;
    }

}    
