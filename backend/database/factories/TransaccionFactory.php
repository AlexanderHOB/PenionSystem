<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Transaccion;
use Faker\Generator as Faker;

$factory->define(Transaccion::class, function (Faker $faker) {
    $tipo=['planilla','semanal'];
    $semana=['2019-06-15','2019-06-8','2019-06-1'];
    return [
        'fecha_inicio'      =>$semana[rand(0,2)],
        'persona_id'        =>rand(1,10),
        'fecha_transaccion' =>$faker->date($format = 'Y-m-d',$min = '2019-06-1', $max = 'now'),
        'tipo'              =>$tipo[rand(0,1)],
        'monto'             =>$faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 500),
        'motivo'            =>$faker->text($maxNbChars = 200) 
    ];
});
