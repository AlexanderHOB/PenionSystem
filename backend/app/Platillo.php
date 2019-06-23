<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Platillo extends Model
{
    protected $fillable =[
        'categoria_id',
        'nombre',
        'codigo',
        'area',
        'precio',
        'descripcion',
        'condicion'
    ];

    //Relacion con la tabla categorias 
    public function categoria(){
        return $this->belongsTo('App\Categoria','categoria_id');
    }
}
