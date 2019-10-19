<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EstadoDeCuentaComensal;
use Carbon\Carbon;

class EstadoDeCuentaComensalController extends Controller
{
    public function index(){
        $estadosDeCuentas=EstadoDeCuentaComensal::orderBy('fecha')->get();
        return [$estadosDeCuentas];
    }
    public function store(){
        $estadoDeCuenta=new EstadoDeCuentaComensal();
        $estadosDeCuentas->saldo_inicial    =$estadosDeCuentas->saldo_inicial;
        $estadosDeCuentas->monto_consumido  =$estadosDeCuentas->monto_consumido;
        $estadosDeCuentas->monto_adelanto   =$estadosDeCuentas->monto_consumido;
        $estadosDeCuentas->saldo_final      =$estadosDeCuentas->saldo_final;
        $estadosDeCuentas->fecha            =Carbon::now('America/Lima');
        $estadosDeCuentas->pedido_id        =$estadosDeCuentas->pedido_id;
        $estadosDeCuentas->comensal_id        =$estadosDeCuentas->comensal_id;
        $estadoDeCuenta->save();

    }

}
