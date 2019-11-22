<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = [
        'id',
        'nombres',
        'apellidos',
        'tipo_documento',
        'documento',
        'direccion',
        'celular',
        'email'
        ];
    // public function user()
    // {
    //     return $this->hasOne('App\User');
    // }

    public function empleado()
    {
        return $this->hasOne('App\Empleado','id');
    }
    public function comensal()
    {
        return $this->hasOne('App\Comensal');
    }
    public function reservas()
    {
        return $this->hasMany('App\Reserva','persona_id');
    }
    public function evento()
    {
        return $this->hasMany('App\Evento');
    }
    public function comprobante()
    {
        return $this->hasMany('App\Comprobante');
    }
}
