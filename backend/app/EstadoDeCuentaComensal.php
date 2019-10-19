<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoDeCuentaComensal extends Model
{
    protected $table ="estado_cuenta_comensal";
    protected $fillable = [
        'saldo_inicial',
        'monto_consumido',
        'saldo_final',
        'fecha',
        'numero_orden',
        'comensal_id',
        'pedido_id'
        ];
    public function comensal(){
        return $this->belongsTo('App\Comensal');
    }
       
}
