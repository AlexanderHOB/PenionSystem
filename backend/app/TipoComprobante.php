<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoComprobante extends Model
{
    protected $table="tipo_comprobante";
    protected $fillable = [
        'id',
        'nombre',
        'descripcion'
    ];
    
    protected function comprobante(){
        return $this->hasMany('App\Comprobante');
    }
}
