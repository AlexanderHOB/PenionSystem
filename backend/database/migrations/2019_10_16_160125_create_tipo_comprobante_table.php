<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoComprobanteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_comprobante', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('nombre',80);
            $table->string('descripcion',150);
            $table->timestamps();
        });
        DB::table('tipo_comprobante')->insert(array('id'=>'1','nombre'=>'FACTURA', 'descripcion'=>'Administradores de áreas'));
        DB::table('tipo_comprobante')->insert(array('id'=>'2','nombre'=>'BOLETA', 'descripcion'=>'Supervisor de las áreas'));
        DB::table('tipo_comprobante')->insert(array('id'=>'3','nombre'=>'NOTA DE CRÉDITO', 'descripcion'=>'Encargado del área de caja'));
        DB::table('tipo_comprobante')->insert(array('id'=>'4','nombre'=>'NOTA DE DÉBITO', 'descripcion'=>'Encargado de las área de ventas'));
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_comprobante');
    }
}
