<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComensalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comensales', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->foreign('id')->references('id')->on('personas')->onDelete('cascade');
            $table->string('tipo_comensal',80);
            $table->string('institucion',80);
            $table->string('cargo',80);
            $table->string('tipo_contrato',80);
            $table->decimal('limite_credito',10,2);

            $table->boolean('condicion')->default('1');
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
        Schema::dropIfExists('comensales');
    }
}
