<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoDeCuentaComensal extends Model
{
    protected $table ="estado_cuenta_comensal";
    protected $fillable = [
        'disponible',
        'consumido',
        'fecha',
        'monto_consumido',
        'numero_orden',
        'comensal_id'
        ];
    public function comensal(){
        return $this->belongsTo('App\Comensal');
    }
       
}
