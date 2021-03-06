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
            $table->string('tipo_descuento',30)->default('porcentaje');
            $table->decimal('descuento',10,2)->nullable()->default(0,00);
            $table->boolean('especial')->default(0); //no comprobante
            $table->string('estado',50)->default('Iniciado');
            $table->smallInteger('pax')->nullable()->default('1');

            $table->integer('mozo_id')->unsigned();
            $table->foreign('mozo_id')->references('id')->on('users');
            $table->integer('mesa_id')->unsigned();
            $table->foreign('mesa_id')->references('id')->on('mesas');


            $table->timestamps();
        
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
