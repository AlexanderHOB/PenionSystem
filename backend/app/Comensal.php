<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comensal extends Model
{
    protected $table="comensales";
    protected $fillable = [
        'id',
        'tipo_comensal',
        'institucion',
        'cargo',
        'tipo_contrato',
        'limite_credito',
        'condicion',
        ];
       

    public function persona(){
        return $this->belongsTo('App\Persona', 'id');
    }
    public function estadoCuentaComensal(){
        return $this->hasMany('App\EstadoDeCuentaComensal');

    }
   
}
