<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class ReservaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {       
        $estado=['Cerrado','Abierto'];
        for($i=0;$i<=14;$i++){
            DB::table('reservas')->insert([
                'nombre'        =>  'Nombre '. $i+1,
                'fecha_reserva' =>  Carbon::create('2019', '12', '01'),
                'fecha_evento'  =>  Carbon::create('2012', '12', '24'),
                'adelanto'      =>  250.5,
                'persona_id'    =>  rand(1,20),
                'estado'        =>  $estado[Rand(0,1)],
                'detalles'      =>   'Mesa para '.$i+1 .' Personas'
            ]);
        }
    }
}
