<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\{Persona,Empleado};

use App\Http\Requests\{EmpleadoStoreRequest,EmpleadoUpdateRequest};

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {     
        //listar personas
        $personas = Persona::join('empleados','personas.id','=','empleados.id')
        ->select('personas.id','personas.nombres','personas.apellidos','personas.dni','personas.direccion','personas.celular','personas.email',
                    'empleados.area_trabajo','empleados.puesto_trabajo','empleados.tipo_contrato','empleados.sueldo','empleados.fecha_registro','empleados.condicion')
        ->orderBy('id', 'desc')
        ->paginate(10);

        return  $personas;
    }
    public function store(EmpleadoStoreRequest $request)
    {
        //validar datos de empleados
        try{
            DB::beginTransaction();

            //crear un mozo
            $persona = new Persona();
            $persona->nombres       = $request->nombres;
            $persona->apellidos     = $request->apellidos;
            $persona->dni           = $request->dni;
            $persona->direccion     = $request->direccion;
            $persona->celular       = $request->celular;
            $persona->email         = $request->email;
            $persona->save();

            $empleado = new Empleado();
            $empleado->id               = $persona->id;
            $empleado->area_trabajo     = $request->area_trabajo;
            $empleado->puesto_trabajo   = $request->puesto_trabajo;
            $empleado->tipo_contrato    = $request->tipo_contrato;
            $empleado->sueldo           = $request->sueldo;
            $empleado->fecha_registro   = $request->fecha_registro;
            $empleado->condicion        = '1';
            $empleado->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
        
    }
    public function update($id,EmpleadoUpdateRequest $request)
    {
        
        //actualizar los datos de los empleados
        try{
            DB::beginTransaction();

            $empleado = Empleado::findOrFail($id);
            $persona = Persona::findOrFail($id);
            $persona->nombres   = $request->nombres;
            $persona->apellidos = $request->apellidos;
            $persona->dni       = $request->dni;
            $persona->direccion = $request->direccion;
            $persona->celular   = $request->celular;
            $persona->email     = $request->email;
            $persona->save();

            
            $empleado->area_trabajo     = $request->area_trabajo;
            $empleado->puesto_trabajo   = $request->puesto_trabajo;
            $empleado->tipo_contrato    = $request->tipo_contrato;
            $empleado->sueldo           = $request->sueldo;   
            $empleado->condicion        = '1';
            $empleado->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function desactivar($id)
    {
        $user = Empleado::findOrFail($id);
        $user->condicion = '0';
        $user->save();
    }

    public function activar($id)
    {
        $user = Empleado::findOrFail($id);
        $user->condicion = '1';
        $user->save();
    }
}
