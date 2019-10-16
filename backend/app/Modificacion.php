<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modificacion extends Model
{
    protected $table="modificaciones";
    protected $fillable = [
        'id',
        'modificaciones',
        'usuario_id', //insertado por backend
        'fecha',
        'sustento',
        'cantidad_antigua',
        'detalle_pedido_id',
        ];
       
    protected function detallePedido(){
        return $this->belongsTo('App\DetallePedido');
    }
   
}
