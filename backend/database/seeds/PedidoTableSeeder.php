<?php

use Illuminate\Database\Seeder;

class PedidoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Pedido::class, 500)->create();
    }
}
