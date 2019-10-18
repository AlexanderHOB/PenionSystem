<?php

use Illuminate\Database\Seeder;

class DetallePedidoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\DetallePedido::class, 100)->create();
    }
}
