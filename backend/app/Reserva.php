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
        'persona_id',
        'estado'
        ];
   public function persona(){
       return $this->belongsTo('App\Persona','persona_id');
   }
}
