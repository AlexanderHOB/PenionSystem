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
        for($i=1;$i<=10;$i++){

            DB::table('categorias')->insert([
                'nombre'=>'Categoria Nº ' . $i,
                'descripcion' => 'Categoria -> '.Str::random(10),
                'condicion' => 1,           
            ]);
        }
    }
}
