<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipo_pedido',80);
            $table->integer('numero_orden');
            $table->date('fecha_registro');
            $table->decimal('total',10,2);
            $table->decimal('descuento',10,2);
            $table->boolean('especial')->default(0); //no comprobante
            $table->integer('mozo_id')->unsigned();
            $table->foreign('mozo_id')->references('id')->on('users');
        
        });
    }
     /*
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
