<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'condicion'
    ];
    
    //Relacion con la tabla Platillos
    protected function platillos(){
        return $this->hasMany('App\Platillo');
    }
}
