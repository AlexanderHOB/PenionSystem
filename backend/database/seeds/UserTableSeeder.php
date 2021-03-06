<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1;$i<=10;$i++){

            DB::table('users')->insert([
                'empleado_id'               =>$i,
                'email'            => 'usuario-'.$i.'@gmail.com',
                'password'         => bcrypt('admin1234'),
                'color'            => '#'.rand(111111,999999), 
                'condicion'        => 1,
                'rol_id'            =>rand(1,6),

            ]);
        }
    }
}
