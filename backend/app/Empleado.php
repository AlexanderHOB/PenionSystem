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
        return $this->belongsTo('App\Persona');
    }
    public function transacciones(){
        return $this->hasMany('App\Transaccion','persona_id','id');
    }
    protected $casts = [
        'fecha_registro' => 'date_format:d/m/yyyy   ',
    ];
    
}