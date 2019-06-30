<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function empleado()
    {
        return $this->hasOne('App\Empleado');
    }

}
