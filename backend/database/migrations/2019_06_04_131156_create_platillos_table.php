<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatillosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platillos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('categoria_id')->unsigned();
            $table->string('nombre',60)->unique();
            $table->string('codigo',5);
            $table->string('area',40);
            $table->decimal('precio',11,2);
            $table->string('descripcion',200)->nullable()->default('Sin Descripción');
            $table->boolean('condicion')->default(1);

            //Relacion Categoria-Platillos
            $table->foreign('categoria_id')->references('id')->on('categorias');

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
        Schema::dropIfExists('platillos');
    }
}
