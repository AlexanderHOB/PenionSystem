<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallesPedidoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_pedido', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('cantidad');
            $table->decimal('descuentos',10,2);
            $table->decimal('valor_unitario',10,2);
            $table->decimal('precio_unitario',10,2);
            $table->string('comentario',100);
            $table->string('estado',80);
            $table->smallInteger('tipo_de_igv');
            $table->decimal('igv',10,2);
            $table->decimal('subtotal',10,2);
            $table->decimal('total',10,2);
            $table->integer('pedido_id')->unsigned();
            $table->foreign('pedido_id')->references('id')->on('pedidos');
            $table->integer('mesa_id')->unsigned();
            $table->foreign('mesa_id')->references('id')->on('mesas');
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
        Schema::dropIfExists('detalles_pedido');
    }
}
