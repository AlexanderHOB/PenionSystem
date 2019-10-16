<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table="detalles_pedido";
    protected $fillable = [
        'id',
        'cantidad',
        'descuentos',
        'valor_unitario',
        'precio_unitario',
        'comentario',
        'estado',
        'tipo_de_igv',
        'igv',
        'subtotal',
        'total',
        'pedido_id',
        'mesa_id'
        ];
    protected function Pedido(){
        return $this->belongsTo('App\Pedido');
    }
    protected function Mesa(){
        return $this->belongsTo('App\Mesa');
    }
    protected function modificaciones(){
        return $this->hasMany('App\Modificacion');

    }
}
