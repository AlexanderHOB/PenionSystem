<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evento;
class EventoController extends Controller
{
    public function index(){
        $eventos = Evento::orderBy('fecha_evento','desc')->get();
        $eventoSimplificado=[];
        $func= function($valor){
            echo $valor[0];
        };
        foreach($eventos as $e){
            $tipo       =   $e->tipoEvento;
            $cliente    =   $e->cliente;
            $adelanto   =  $e->adelantos;
            //Start Calculate the total advance
            $total_adelanto=0;
            foreach($adelanto as $a){
                $total_adelanto+=$a['monto'];
            }
            //End Calculate the total advance
            $response=[
                'id'                => $e->id,
                'evento'            => $tipo->nombre,
                'nombres'           => $cliente->nombres . ' ' . $cliente->apellidos,
                'fecha_reserva'     => $e->fecha_reserva,
                'fecha_evento'      => $e->fecha_evento,
                'ubicacion'         => $e->ubicacion,
                'total'             => $e->total,
                'monto_adelantado'  => $total_adelanto,
                'saldo'             => ($e->total-$total_adelanto),
                'adelantos'         => $adelanto

            ];
            array_push($eventoSimplificado,$response);
        }
        return $eventoSimplificado;
    }
    
}
