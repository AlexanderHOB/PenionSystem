<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\DetallePedido;
use Faker\Generator as Faker;

$factory->define(DetallePedido::class, function (Faker $faker) {
    $precio = $faker->randomFloat(2,5,84);
    $cant = $faker->randomNumber(2,false);
    $desc = ($precio*$cant)/10;
    $igv = 0.18; 
    return [
        'cantidad' => $cant,
        'descuentos' => $desc,
        'valor_unitario' => $faker->randomFloat(2,5,84),
        'precio_unitario' => $precio,
        'comentario' => $faker->sentence(1),
        'estado' => $faker->randomELement(['Iniciado','Produccion','Finalizado']),
        'tipo_de_igv' => $faker->randomDigit(),
        'igv' => $igv,
        'subtotal' => $precio*$cant,
        'total' => ($precio*$cant)+($precio*$cant-$desc)*$igv,
        'pedido_id' => $faker->numberBetween($min = 1, $max = 100),
        'mesa_id' => $faker->numberBetween($min = 1, $max = 30),
        'platillo_id'=>$faker->numberBetween($min = 1, $max = 30)

    ];
});
