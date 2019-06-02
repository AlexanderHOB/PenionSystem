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
        for($i=21;$i<=99;$i++){

        DB::table('mesas')->insert([
            'numero'=>$i,
            'capacidad' => rand(5,25),
            'descripcion' => 'mesa Ubicado en '.Str::random(10),
            'condicion' => 1,           
        ]);
    }
    }
}
