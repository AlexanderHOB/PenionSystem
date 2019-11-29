<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'id',
        'tipo_pedido',
        'numero_orden',
        'fecha_registro',
        'total',
        'descuento',
        'pax',
        'especial',
        'mozo_id'
        ];
        
    protected function user(){
        return $this->belongsTo('App\User','mozo_id','empleado_id');
    }
    protected function detallesPedidos(){
        return $this->hasMany('App\DetallePedido');

    }
    protected function comprobantes(){
        return $this->hasMany('App\Comprobante');
    }
    protected function mesa(){
        return $this->belongsTo('App\Mesa');
    }
    
}
