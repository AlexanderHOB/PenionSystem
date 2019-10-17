<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Persona;
use Faker\Generator as Faker;

$factory->define(App\Persona::class, function (Faker $faker) {
    $n1 = $faker->firstName();
    $n2 = $faker->firstName();
    $l1 = $faker->lastName();
    $l2 = $faker->lastName();
    return [
        'nombres'    => $n1.' '.$n2,
        'apellidos' => $l1.' '.$l2,
        'tipo_documento' => $faker->randomELement(['DNI','Pasaporte','RUC']),
        'documento' => rand(1111111,99999999),
        'direccion' => $faker->address,
        'celular' => rand(900000000,999999999),
        'email' => $faker->email
    ];
});
