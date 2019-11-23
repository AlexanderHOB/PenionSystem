<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class AdelantoEventoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1;$i<=30;$i++){

            DB::table('adelanto_evento')->insert([
                'numero_adelanto'   =>'1',
                'fecha_adelanto'    => Carbon::create('2019', '12', '23'),
                'contacto'          => Str::random(20),
                'monto'             => rand(10,100),
                'evento_id'         => rand(1,10),
            ]);
            }
    }
}
