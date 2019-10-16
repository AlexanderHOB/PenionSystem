<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstadoCuentaComensalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estado_cuenta_comensal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('disponible',10,2);
            $table->decimal('consumido',10,2);
            $table->date('fecha');
            $table->decimal('monto_consumido');
            $table->integer('numero_orden');
            $table->integer('comensal_id')->unsigned();
            $table->foreign('comensal_id')->references('id')->on('personas');
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
        Schema::dropIfExists('estado_cuenta_comensal');
    }
}
