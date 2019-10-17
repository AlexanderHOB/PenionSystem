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
        $estado = ['Ocupado','Disponible'];
        for($i=1;$i<=30;$i++){

        DB::table('mesas')->insert([
            'numero'=>$i,
            'capacidad' => rand(5,25),
            'descripcion' => 'Mesa ubicado en '.Str::random(10),
            'estado' => $estado[rand(0,1)],
            'condicion' => 1,     
        ]);
    }
    }
}
