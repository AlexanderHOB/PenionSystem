<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Transaccion,Empleado};
use App\Http\Requests\TransaccionStoreRequest;

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

        $total=Transaccion::selectRaw('sum(monto) AS total,persona_id,fecha_inicio')
            ->groupBy('persona_id','fecha_inicio')
            ->where('persona_id','=',$buscar)
            ->whereDate('fecha_inicio', $fecha)
            ->get();

            return [$historiales,$total];    
    }
    public function stateSalary(Request $request)
    {
       //Buscar sueldo restante de la semana  por ID 
        $buscar =   $request->id;

        $empleado   =Empleado::join('personas','personas.id','=','empleados.id')
                    ->select('personas.nombres','personas.apellidos','personas.dni','personas.celular','empleados.sueldo','empleados.puesto_trabajo','empleados.area_trabajo','empleados.tipo_contrato')
                    ->where('empleados.id','=',$buscar)->first();
        $sueldo     =Transaccion::where('persona_id','=',$buscar)
                    ->where('persona_id','=',$buscar)
                    ->orderBy('fecha_inicio','desc')
                    ->first();
        //Obtenemos la fecha de semana mas temprana
        $fecha=$sueldo->fecha_inicio;
        $sueldo=$empleado->sueldo;
        //obtenemos la suma de todos los decuentos de una fecha de inicio determinada
        $total=Transaccion::selectRaw('sum(monto) AS total,persona_id,fecha_inicio')
            ->groupBy('persona_id','fecha_inicio')
            ->where('persona_id','=',$buscar)
            ->whereDate('fecha_inicio', $fecha)
            ->get();
        //asignamos la variable de total a una variable
        $adelanto=$total[0]->total;
        //saldo que le queda en la semana
        $saldo  = $sueldo-$adelanto;
        //asignamos saldo al objeto
        $empleado['restante'] = round($saldo,2);
        $empleado['adelanto'] = round($adelanto,2);
        $empleado['fecha_inicio'] = $fecha;

        return [$empleado];
    }
    public function discount(TransaccionStoreRequest $request){
        
        //transaccion
        $transaccion = new Transaccion();
        $transaccion->persona_id = $request->persona_id;
        $transaccion->fecha_inicio = $request->fecha_inicio;
        $transaccion->fecha_transaccion = $request->fecha_transaccion;
        $transaccion->tipo = $request->tipo;
        $transaccion->monto = $request->monto;
        $transaccion->motivo = $request->motivo;
        $transaccion->save();

    }
    public function pay(Request $request){
        //transaccion
        $transaccion = new Transaccion();
        $transaccion->persona_id            = $request->persona_id;
        $transaccion->fecha_inicio          = $request->fecha_inicio;
        $transaccion->fecha_transaccion     = $request->fecha_transaccion;
        $transaccion->tipo                  = $request->tipo;
        $transaccion->monto                 = $request->monto;
        $transaccion->bono                  = $request->bono;
        $transaccion->motivo                = 'Pago de su sueldo';
        $transaccion->save();

        if($request->continuidad=='si'){
            $transaccion = new Transaccion();
            $transaccion->persona_id    =   $request->persona_id;
            $transaccion->fecha_inicio  =   $request->fecha_nueva; //la nueva fecha que empieza!
            $transaccion->fecha_transaccion = $request->fecha_nueva;
            $transaccion->tipo              = 'Renovacion';
            $transaccion->monto             = $request->monto;
            $transaccion->bono              = $request->bono;
            $transaccion->motivo            = 'Pago de su sueldo';
            $transaccion->save();
        }
    }
    public function getAdelantoDescuento(Request $request){
        $tipo=$request->tipo;
        $adelantos  =   Transaccion::where('tipo','=',$tipo)->orderBy('id','desc')->paginate(10);
        return $adelantos;
    }

}
