<?php

use Illuminate\Database\Seeder;

class ComensalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Comensal::class, 20)->create();
    }
}
