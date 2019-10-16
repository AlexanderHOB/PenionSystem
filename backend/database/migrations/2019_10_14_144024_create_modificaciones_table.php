<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModificacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modificaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('modificaciones',100);
            $table->integer('usuario_id');
            $table->datetime('fecha');
            $table->string('sustento');
            $table->smallinteger('cantidad_antigua');
            $table->integer('detalle_pedido_id')->unsigned();
            $table->foreign('detalle_pedido_id')->references('id')->on('detalles_pedido');
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
        Schema::dropIfExists('modificaciones');
    }
}
