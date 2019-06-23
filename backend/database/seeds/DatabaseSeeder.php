<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(MesaTableSeeder::class);
        $this->call(CategoriaTableSeeder::class);
        $this->call(PlatilloTableSeeder::class);
        $this->call(PersonaTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(TransaccionesTableSeeder::class);





    }
}