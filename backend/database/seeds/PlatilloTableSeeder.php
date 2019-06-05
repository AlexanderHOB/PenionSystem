<?php

use Illuminate\Database\Seeder;

class PlatilloTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $area=['Área Fría ','Área Caliente','Área Mixta'];
        for($i=1;$i<=40;$i++){

            DB::table('platillos')->insert([
                'idcategoria'   => rand(1,10),
                'nombre'        => 'Platillo de ' .Str::random(5),
                'codigo'        => Str::random(5),
                'area'          => $area[rand(0,2)],  
                'precio'        => rand(0,35),
                'descripcion'   => 'Descripción del platillo: '.Str::random(30),
                'condicion'     => 1,
            ]);
        }
    }
}
