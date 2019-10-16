<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = [
        'id',
        'fecha_reserva',
        'fecha_evento',
        'adelanto',
        'cliente_id',
        ];
   protected function cliente(){
       return $this->belongsTo('App\Persona','cliente_id');
   }
}
