<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;

class PersonalController extends Controller
{
    public function index(Request $request)
    {     
        //listar personas
        $personas = Persona::orderBy('id', 'desc')->paginate(10);

        return  $personas;
    }
    public function store(Request $request)
    {
        //crear un mozo
        $persona = new Persona();
        $persona->nombre = $request->nombre;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;

        $persona->save();
    }

}
