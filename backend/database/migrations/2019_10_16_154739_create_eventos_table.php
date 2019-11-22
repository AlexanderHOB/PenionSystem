<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descripcion',255);
            $table->date('fecha_reserva');
            $table->date('fecha_evento');
            $table->string('ubicacion');
            $table->smallInteger('tipo_evento')->unsigned();
            $table->foreign('tipo_evento')->references('id')->on('tipo_evento');
            $table->integer('cliente_id')->unsigned();
            $table->foreign('cliente_id')->references('id')->on('personas');
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
        Schema::dropIfExists('eventos');
    }
}
