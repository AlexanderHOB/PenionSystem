<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Pedido;
use Faker\Generator as Faker;

$factory->define(App\Pedido::class, function (Faker $faker) {
    return [
        'tipo_pedido'       => $faker->randomElement(['Normal','Para Llevar','Delivery']),
        'numero_orden'      => $faker->unique()->randomNumber(5,false),
        'fecha_registro'    => $faker->dateTime('now',null),
        'total'             => $faker->randomFloat(2,5,2000),
        'descuento'         => $faker->randomFloat(2,0,2000)*0.18,
        'especial'          => rand(0,1),
        'estado'            => $faker->randomELement(['Nuevo','Produccion','Pendiente','Finalizado']),
        'mozo_id'           => rand(1,10),
        'mesa_id'           => rand(1,15)
    ];
});
