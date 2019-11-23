<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $fillable =[
        'id',
        'descripcion',
        'fecha_reserva',
        'fecha_evento',
        'ubicacion',
        'tipo_evento',
        'cliente_id',
        'total',
        'estado'
    ];
    protected function cliente(){
        return $this->belongsTo('App\Persona','cliente_id');
    }
    //Relacion con la tabla categorias 
    public function tipoEvento(){
        return $this->belongsTo('App\TipoEvento','tipo_evento');
    }
    public function adelantos(){
        return $this->hasMany('App\AdelantoEvento');

    }
}
