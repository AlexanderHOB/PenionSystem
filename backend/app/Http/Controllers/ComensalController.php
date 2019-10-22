<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\{Comensal,Persona};
class ComensalController extends Controller
{
    public function index(){
        if (!$request->ajax()) return redirect('/');
        $comensales = Comensal::orderBy('id','desc')->paginate(40);
        foreach ($comensales as $comensal){
            $comensal->persona;
        }
        return ['comensales'=>$comensales];
    }
    public function store(Request $request){
        if (!$request->ajax()) return redirect('/');
        
        try{
            DB::beginTransaction();
            $persona = new Persona();
            $persona->nombres = $request->nombres;
            $persona->apellidos = $request->apellidos;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->celular = $request->celular;
            $persona->email = $request->email;

            $persona->save();

            $comensal = new Comensal();
            $comensal->id=$persona->id;
            $comensal->tipo_comensal = $request->tipo_comensal;
            $comensal->institucion = $request->institucion;
            $comensal->cargo = $request->cargo;
            $comensal->tipo_contrato = $request->tipo_contrato;
            $comensal->limite_credito = $request->limite_credito; 
            $comensal->condicion='1';


            $comensal->save();

            DB::commit();
            return [
                'id' => $persona->id
            ];

        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function update(Request $request){
        if (!$request->ajax()) return redirect('/');
        
        try{
            DB::beginTransaction();
            $persona =Persona::findOrFail($request->id);
            $persona->nombres = $request->nombres;
            $persona->apellidos = $request->apellidos;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->celular = $request->celular;
            $persona->email = $request->email;

            $persona->save();

            $comensal =Comensal::findOrFail($request->id);
            $comensal->tipo_comensal = $request->tipo_comensal;
            $comensal->institucion = $request->institucion;
            $comensal->cargo = $request->cargo;
            $comensal->tipo_contrato = $request->tipo_contrato;
            $comensal->limite_credito = $request->limite_credito; 
            $comensal->condicion='1';


            $comensal->save();

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function desactivar($id)
    {
        $user = Comensal::findOrFail($id);
        $user->condicion = '0';
        $user->save();
    }

    public function activar($id)
    {
        $user = comensal::findOrFail($id);
        $user->condicion = '1';
        $user->save();
    }
    
}
