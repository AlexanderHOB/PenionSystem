<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Persona;
use Faker\Generator as Faker;

$factory->define(Persona::class, function (Faker $faker) {
    return [
        'nombre'    =>$faker->name,
        'dni'       =>rand(1111111,99999999),
        'direccion' =>$faker->address,
        'celular'   =>$faker->cellphone,
        'email'     =>$faker->email
    ];
});
