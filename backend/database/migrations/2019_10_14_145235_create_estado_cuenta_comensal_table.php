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
            $table->decimal('saldo_inicial',10,2);
            $table->decimal('monto_consumido',10,2)->default(0);
            $table->decimal('monto_adelanto',10,2)->default(0);
            $table->decimal('saldo_final',10,2);
            $table->date('fecha');
            $table->integer('pedido_id')->unsigned();
            $table->foreign('pedido_id')->references('id')->on('pedidos');
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
