<?php

use Illuminate\Database\Seeder;

class CategoriaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cat = ['Entradas','Ceviches','Chupes','Combos Marinos','Jaleas','Chicharrones','Arroces','Planchas Y Milanesas','Fin De Semana','Platos Familiares','Cervezas','Gaseosas','Agua','Refrescos','Postres'];
        for($i=0;$i<=14;$i++){

            DB::table('categorias')->insert([
                'nombre'=> $cat[$i],
                'descripcion' => 'Categoria -> '.$cat[$i],
                'condicion' => 1,           
            ]);
        }
    }
}
