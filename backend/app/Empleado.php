<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';
    protected $fillable = [
        'area_trabajo',
        'puesto_trabajo',
        'tipo_contrato',
        'fecha_registro',
        'sueldo'
    ];

    public function persona(){
        return $this->belongsTo('App\Persona', 'id');
    }
    public function transacciones(){
        return $this->hasMany('App\Transaccion','persona_id','id');
    }
    public function Pedidos(){
        return $this->hasMany('App\Pedido');
    }
    public function user(){
        return $this->hasOne('App\User');
    }

}
