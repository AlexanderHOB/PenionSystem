<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Persona;
use App\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $personas = User::join('personas','users.id','=','personas.id')
            ->join('roles','users.idrol','=','roles.id')
            ->select('personas.id','personas.nombre','personas.dni','personas.direccion','personas.celular','personas.email','users.email','users.password','users.condicion','users.idrol','roles.nombre as rol')
            ->orderBy('personas.id', 'desc')->paginate(10);
        }
        else{
            $personas = User::join('personas','users.id','=','personas.id')
            ->join('roles','users.idrol','=','roles.id')
            ->select('personas.id','personas.nombre','personas.dni','personas.direccion','personas.celular','personas.email','users.email','users.password','users.condicion','users.idrol','roles.nombre as rol')
            ->where('personas.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }
        
        return $personas;
    }
    public function store(Request $request)
    {
        $v = \Validator::make($request->all(), [
            
            'nombre' => 'required|string|',
            'dni' => 'required|string|max:5',
            'celular'    => 'required|string|max:60',
            'email' => 'required|max:150',
            'password' => 'required|integer',
            'color' => 'required|integer',

        ]);
 
        if ($v->fails())
        {
            return ['Errores de validación de datos en el servidor'];
        }
        try{
            DB::beginTransaction();

            $persona = new Persona();
            $persona->nombre = $request->nombre;
            $persona->dni = $request->dni;
            $persona->direccion = $request->direccion;
            $persona->celular = $request->celular;
            $persona->email = $request->email;
            $persona->save();

            $user = new User();
            $user->id = $persona->id;
            $user->idrol = $request->idrol;
            $user->email = $request->email;
            $user->password = bcrypt( $request->password);
            $user->color=$request->color;
            $user->condicion = '1';            
            $user->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function update($id, Request $request)
    {
        try{
            DB::beginTransaction();

            $user = User::findOrFail($request->id);
            $persona = Persona::findOrFail($user->id);
            $persona->nombre    = $request->nombre;
            $persona->dni       = $request->dni;
            $persona->direccion = $request->direccion;
            $persona->celular   = $request->celular;
            $persona->email     = $request->email;
            $persona->save();

            
            $user->email = $request->email;
            $user->password = bcrypt( $request->password);
            $user->condicion = '1';
            $user->idrol = $request->idrol;
            $user->color = $request->color;
            $user->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function desactivar($id)
    {
        $user = User::findOrFail($id);
        $user->condicion = '0';
        $user->save();
    }

    public function activar($id)
    {
        $user = User::findOrFail($id);
        $user->condicion = '1';
        $user->save();
    }
}
