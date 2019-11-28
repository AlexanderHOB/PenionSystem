<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\{Evento,AdelantoEvento};
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
    public function store(Request $request){
        try{
            DB::beginTransaction();

            $evento = new Evento();
            $evento->tipo_evento    =$request->tipo_evento;
            $evento->descripcion    =$request->descripcion;
            $evento->fecha_reserva  =Carbon::now('America/Lima');
            $evento->fecha_evento   =$request->fecha_evento;
            $evento->ubicacion      =$request->ubicacion;
            $evento->total          =$request->total;
            $evento->estado         ='Pendiente';
            $evento->mozo_id        =$request->mozo_id;
            $evento->cliente_id     =$request->cliente_id;
            $evento->save();

            $adelantos =$request->adelantos;
            //Array de detalles
            //Recorro todos los elementos

            foreach($adelantos as $ep=>$det)
            {
                $detalle = new AdelantoEvento();
                $detalle->evento_id         = $evento->id;
                $detalle->numero_adelanto   = $det['numero_adelanto'];
                $detalle->fecha_adelanto    = $det['fecha_adelanto'];
                $detalle->contacto          = $det['contacto'];
                $detalle->monto             = $det['monto'];       
                $detalle->save();
            }       
            DB::commit();
            return[
                'evento_id'=>$evento->id
            ]; 
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function update(Request $request,$id){
        try{
            DB::beginTransaction();

            $evento = Evento::findOrFail($id);
            $evento->tipo_evento    =$request->tipo_evento;
            $evento->descripcion    =$request->descripcion;
            $evento->fecha_reserva  =Carbon::now('America/Lima');
            $evento->fecha_evento   =$request->fecha_evento;
            $evento->ubicacion      =$request->ubicacion;
            $evento->total          =$request->total;
            $evento->estado         ='Pendiente';
            $evento->mozo_id        =$request->mozo_id;
            $evento->cliente_id     =$request->cliente_id;
            $evento->save();

            $adelantos =$request->adelantos;
            //Array de detalles
            //Recorro todos los elementos

            foreach($adelantos as $ep=>$det)
            {
                $detalle = new AdelantoEvento();
                $detalle->evento_id         = $evento->id;
                $detalle->numero_adelanto   = $det['numero_adelanto'];
                $detalle->fecha_adelanto    = $det['fecha_adelanto'];
                $detalle->contacto          = $det['contacto'];
                $detalle->monto             = $det['monto'];       
                $detalle->save();
            }       
            DB::commit();
            return[
                'evento_id'=>$evento->id
            ]; 
        } catch (Exception $e){
            DB::rollBack();
        }
    }
}
