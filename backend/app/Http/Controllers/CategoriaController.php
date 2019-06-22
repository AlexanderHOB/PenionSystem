<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $categorias = Categoria::orderBy('id', 'desc')->paginate(10);
        }
        else{
            $categorias = Categoria::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }

        return $categorias;
    }
    public function selectCategoria(Request $request){
        //if (!$request->ajax()) return redirect('/');
        $categorias = Categoria::where('condicion','=','1')
        ->select('id','nombre')->orderBy('nombre', 'asc')->get();
        return ['categorias' => $categorias];
    }

    public function store(Request $request)
    {
        //Validar datos de creacion
        $v = \Validator::make($request->all(), [

            'nombre' => 'required|string|max:60',

        ]);

        if ($v->fails())
        {
            return ['Errores de validación de datos en el servidor'];
        }
        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        $categoria->save();
    }


    public function update($id, Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $categoria = Categoria::findOrFail($id);
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        $categoria->save();
    }

    public function desactivar($id)
    {
        //if (!$request->ajax()) return redirect('/');

        $categoria = Categoria::findOrFail($id);
        $categoria->condicion = '0';
        $categoria->save();
        return $categoria;
    }

    public function activar($id)
    {
        //if (!$request->ajax()) return redirect('/');

        $categoria = Categoria::findOrFail($id);
        $categoria->condicion = '1';
        $categoria->save();
    }
}
