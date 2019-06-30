<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Transaccion,Empleado};

class TransaccionController extends Controller
{
    public function historial(Request $request)
    {
       //Buscar Historial por ID y Fecha
        $buscar =   $request->id;
        $fecha  =   $request->fecha;

        $empleado=Empleado::where('empleados.id','=',$buscar)->first();
        $descuentos=Transaccion::whereDate('fecha_inicio',$fecha)->where('persona_id','=',$buscar)->get();
        //Agregamos los datos a un arreglo
        $historiales[]=$empleado;
        $historiales[0]['transacciones']=$descuentos;

        $total=Transaccion::selectRaw('sum(monto),persona_id,fecha_inicio')
            ->groupBy('persona_id','fecha_inicio')
            ->where('persona_id','=',$buscar)
            ->whereDate('fecha_inicio', $fecha)
            ->get();

            return [$historiales,$total];

       

    }

}
