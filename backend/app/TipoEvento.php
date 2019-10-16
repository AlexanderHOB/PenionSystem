<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoEvento extends Model
{
    protected $table="tipo_evento";
    protected $fillable =[
        'nombre',
        'descripcion',
        'condicion',
    ];
    public $timestamps = false;
    //Relacion con la tabla categorias 
    public function eventos(){
        return $this->hasMany('App\Evento');
    }
}
