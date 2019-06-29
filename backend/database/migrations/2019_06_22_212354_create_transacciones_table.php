<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('persona_id')->unsigned();
            $table->foreign('persona_id')->references('id')->on('personas');
            $table->date('fecha_inicio');
            $table->date('fecha_transaccion');
            $table->string('tipo',60);
            $table->decimal('monto',11,2);
            $table->string('motivo',254);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transacciones');
    }
}
