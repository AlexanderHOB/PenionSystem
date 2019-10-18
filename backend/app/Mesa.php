<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{

    protected $fillable = [
        'numero',
        'capacidad',
        'descripcion',
        'condicion',
        'estado'
        ];
        public function DetallesPedido(){
            return $this->hasMany('App\DetallePedido');
        }
        public $timestamps = false;
}
