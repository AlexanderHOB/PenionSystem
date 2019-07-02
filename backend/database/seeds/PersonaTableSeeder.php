<?php

use Illuminate\Database\Seeder;

class PersonaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1;$i<=40;$i++){

            DB::table('personas')->insert([
                'nombres'           => 'Persona Nº'.$i,
                'apellidos'         =>  'Apellido Nº'.$i,
                'dni'               => rand(11111111,99999999),
                'direccion'         => Str::random(50),
                'celular'           => rand(90000000,999999999),  
                'email'             => 'usuario-'.$i.'@gmail.com',
            ]);
        }
    }
}
