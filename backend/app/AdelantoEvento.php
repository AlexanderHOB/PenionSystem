<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdelantoEvento extends Model
{
    protected $table="adelanto_evento";
    protected $fillable = [
        'id',
        'numero_adelanto',
        'fecha_adelanto',
        'contacto',
        'monto',
        'evento_id'
    ];
   
    //Relacion con la tabla Platillos
    protected function evento(){
        return $this->belognsTo('App\Evento');
    }
}
