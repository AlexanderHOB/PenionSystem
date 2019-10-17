<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Comensal;
use Faker\Generator as Faker;

$factory->define(App\Comensal::class, function (Faker $faker) {
    return [
        'id' => rand(1,40),
        'tipo_comensal' => $faker->randomElement(['Normal','Convenio']),
        'institucion' => $faker->company,
        'cargo' => $faker->jobtitle,
        'tipo_contrato' => $faker->randomElement(['Pago Mensual','Pago Semanal','Pago Anual']),
        'limite_credito' => $faker->randomFloat(2,0,3000),
        'condicion' => rand(0,1),
    ];
});
