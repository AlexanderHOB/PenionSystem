<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdelantoEventoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adelanto_evento', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallinteger('numero_adelanto');
            $table->date('fecha_adelanto');
            $table->string('contacto',80);
            $table->decimal('monto',10,2);
            $table->integer('evento_id')->unsigned();
            $table->foreign('evento_id')->references('id')->on('eventos');
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
        Schema::dropIfExists('adelanto_evento');
    }
}
