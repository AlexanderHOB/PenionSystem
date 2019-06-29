<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    protected $table = 'transacciones';
    protected $fillable = ['fecha_inicio','persona_id','fecha_transaccion','tipo','monto','motivo'];
}
