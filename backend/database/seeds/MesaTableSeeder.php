<?php

use Illuminate\Database\Seeder;

class MesaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mesas')->insert([
            'capacidad' => Int::random(40),
            'descripcion' => Str::random(10).'@gmail.com',
            'condicion' => 1,
        ]);
    }
}
