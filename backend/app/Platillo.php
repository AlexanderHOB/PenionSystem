<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Platillo extends Model
{
    protected $fillable =[
        'categoria_id',
        'nombre',
        'codigo',
        'unidad_de_medida',
        'area',
        'precio',
        'descripcion',
        'condicion'
    ];
   
    //Relacion con la tabla categorias 
    public function categoria(){
        return $this->belongsTo('App\Categoria','categoria_id');
    }
    public function detallesPedido(){
        return $this->hasMany('App\DetallePedido');
    }
}
