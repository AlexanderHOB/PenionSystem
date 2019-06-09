<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('descripcion');
            $table->timestamps();
        });
        DB::table('roles')->insert(array('id'=>'1','nombre'=>'Administrador', 'descripcion'=>'Administradores de áreas'));
        DB::table('roles')->insert(array('id'=>'2','nombre'=>'Supervisor', 'descripcion'=>'Supervisor de las áreas'));
        DB::table('roles')->insert(array('id'=>'3','nombre'=>'Cajero', 'descripcion'=>'Encargado del área de caja'));
        DB::table('roles')->insert(array('id'=>'4','nombre'=>'Mozo', 'descripcion'=>'Encargado de las área de ventas'));
        DB::table('roles')->insert(array('id'=>'5','nombre'=>'Almacen Central', 'descripcion'=>'Encargado de Almacén Central'));
        DB::table('roles')->insert(array('id'=>'6','nombre'=>'Almacen PV', 'descripcion'=>'Encargado de Almacén PV'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
