<?php

use Illuminate\Database\Seeder;

class EmpleadoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $areas      =['Área Fria','Área Caliente','Área mixta','Área de Ventas','Área de almacen'];
        $puestos    =['Mozo','Caja','Cocinero','Ayudante de Cocina','Almacen'];
        $contrato   =['planilla','mensual','semanal'];
        $semana     =['2019-06-15','2019-06-8','2019-06-1'];
        
        for($i=1;$i<=10;$i++){
            DB::table('empleados')->insert([
                'id'                => $i,
                'area_trabajo'      => $areas[rand(0,4)],
                'puesto_trabajo'    => $puestos[rand(0,4)],
                'tipo_contrato'     => $contrato[rand(0,2)],  
                'sueldo'            => rand(980,1500),
                'fecha_registro'    => $semana[rand(0,2)]
            ]);
        }
    }

}
