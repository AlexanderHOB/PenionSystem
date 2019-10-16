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
        'especial',
        'mozo_id'
        ];
        
    protected function mozo(){
        return $this->belongsTo('App\Empleado','mozo_id');
    }
    protected function DetallesPedidos(){
        return $this->hasMany('App\DetallePedido');

    }
    protected function comprobantes(){
        return $this->hasMany('App\Comprobante');

    }
    
}
