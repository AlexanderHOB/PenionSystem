<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Reserva;
class ReservaController extends Controller
{
    public function index(){
        $reservas = Reserva::orderBy('fecha_evento','desc')->get();
        $reservasSimplificada=[];
        foreach($reservas as $r){
            $r->persona;
            $response=[
                'numero_reserva'=>$r->id,
                'nombres'       =>$r->nombre,
                'detalles'      =>$r->detalles,
                'email'         =>$r->persona->email,
                'contacto'      =>$r->persona->nombres ." ".$r->persona->apellidos,
                'celular'       =>$r->persona->celular,
                'fecha_reserva' =>$r->fecha_reserva,
                'fecha_evento'  =>$r->fecha_evento,
                'adelanto'      =>$r->adelanto,
            ];
            array_push($reservasSimplificada,$response);
        }
        return $reservasSimplificada;
    }
    public function store(Request $request){
        try{
            DB::beginTransaction();

            $reserva = new Reserva();
            $reserva->nombre            =   $request->nombres;
            $reserva->fecha_reserva     =   $request->fecha_reserva;
            $reserva->fecha_evento      =   $request->fecha_evento;
            $reserva->adelanto          =   $request->adelanto;
            $reserva->estado            =   $request->estado;
            $reserva->detalles          =   $request->detalles;
            $reserva->persona_id        =   $request->persona_id;
            $reserva->save();
            DB::commit();
    
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function close($id){
        $reserva = Reserva::findOrFail($id);
        $reserva->estado='Cerrado';
        $reserva->save();
    }
}
