<?php

use Illuminate\Database\Seeder;

class TipoEventoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $evento = ['Boda','Bautizo','Cumpleaños'];
        for($i=0;$i<3;$i++){

        DB::table('tipo_evento')->insert([
            'nombre'        => $evento[$i],
            'descripcion'   => 'Descripcion',
            'condicion'     =>  1, 
        ]);
        }
    }
}
