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
            ->join('roles','users.rol_id','=','roles.id')
            ->select('personas.id','personas.nombre','personas.dni','personas.direccion','personas.celular','personas.email','users.email','users.password','users.condicion','users.rol_id','roles.nombre as rol')
            ->orderBy('personas.id', 'desc')->paginate(10);
        }
        else{
            $personas = User::join('personas','users.id','=','personas.id')
            ->join('roles','users.rol_id','=','roles.id')
            ->select('personas.id','personas.nombre','personas.dni','personas.direccion','personas.celular','personas.email','users.email','users.password','users.condicion','users.rol_id','roles.nombre as rol')
            ->where('personas.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }

        return $personas;
    }
    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
            'color'    => 'required|string|max:7'
        ]);
        $user = new User([
            'email'         => $request->email,
            'color'         => $request->color,
            'empleado_id'   => $request->empleado_id,
            'rol_id'        => $request->rol_id,
            'password' => bcrypt($request->password),
        ]);
        $user->save();

        $newuser = [
            'apellidos' => $user->empleado->persona->apellidos,
            'celular' => $user->empleado->persona->celular,
            'condicion' => 1,
            'direccion' => $user->empleado->persona->direccion,
            'documento' => $user->empleado->persona->documento,
            'email' => $user->empleado->persona->email,
            'id' => $user->id,
            'color' => $user->color,
            'empleado_id' => $user->empleado_id,
            'nombres' => $user->empleado->persona->nombres,
            'rol' => $user->rol->nombre,
            'rol_id' => $user->rol_id
        ];
        return $newuser;
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'color'    => 'required|string|max:7'
        ]);
        $user = User::findOrFail($id);
        $user->color = $request->color;
        $user->rol_id = $request->rol_id;

        $user->save();

        return $user;
    }

    public function updatePass($id, Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);
        $user = User::findOrFail($id);
        $user->password = bcrypt($request->pasword);

        $user->save();

        return $user;
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

    public function getAllUsers(){
        $personas = User::join('personas','users.empleado_id','=','personas.id')
        ->join('roles','users.rol_id','=','roles.id')
        ->select('users.id', 'personas.id as empleado_id', 'personas.apellidos','personas.nombres','personas.documento','personas.direccion','personas.celular','personas.email','users.email','users.password','users.condicion','users.rol_id','roles.nombre as rol','users.color')
        ->orderBy('personas.id', 'desc')->get();

        return $personas;
    }
}
