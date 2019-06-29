<?php

use Illuminate\Database\Seeder;
use App\Transaccion;
class TransaccionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 100;
        factory(Transaccion::class, $count)->create();
    }
}
