<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Platillo extends Model
{
    protected $fillable =[
        'idcategoria',
        'nombre',
        'codigo',
        'area',
        'precio',
        'descripcion',
        'condicion'
    ];

    //Relacion con la tabla categorias
    public function categoria(){
        return $this->belongsTo('App\Categoria');
    }
}
