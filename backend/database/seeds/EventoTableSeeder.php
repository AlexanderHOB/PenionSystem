<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        for($i=1;$i<=30;$i++){

        DB::table('eventos')->insert([
            'descripcion'   =>'Descripcion '.Str::random(10) . $i,
            'fecha_reserva' =>  Carbon::create('2019', '12', '01'),
            'fecha_evento'  =>  Carbon::create('2012', '12', '24'),
            'ubicacion'     => Str::random(40),
            'tipo_evento'   => rand(1,3),
            'cliente_id'    => rand(1,15),
            'mozo_id'       => rand(1,15),
            'total'         => rand(100,1000),   
        ]);
        }
    }
}
