<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprobantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('tipo_comprobante')->unsigned();
            $table->foreign('tipo_comprobante')->references('id')->on('tipo_comprobante');
            $table->string('serie',4);
            $table->integer('numero');
            $table->smallInteger('sunat_transaccion')->default(1);
            $table->integer('cliente_id')->unsigned();
            $table->foreign('cliente_id')->references('id')->on('personas');
            $table->date('fecha_emision');
            $table->smallinteger('moneda')->default('1');
            $table->decimal('porcentaje_de_igv',5,2);
            $table->decimal('descuento_total',10,2);
            $table->decimal('total',10,2);
            $table->integer('pedido_id')->unsigned();
            $table->foreign('pedido_id')->references('id')->on('pedidos');

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
        Schema::dropIfExists('comprobantes');
    }
}
