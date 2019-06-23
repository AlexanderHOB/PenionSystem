<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaccion;

class TransaccionController extends Controller
{
    public function historial(Request $request)
    {
       //listar historial
        $historial=Transaccion::where('persona_id','=','2')
        ->whereDate('fecha_inicio', '2019-06-01')
        ->orderBy('id', 'asc')
        ->paginate(10);

        $total=Transaccion::selectRaw('sum(monto),persona_id,fecha_inicio')->groupBy('persona_id','fecha_inicio')->get();
       return [$historial,$total];
    }
}
