<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
class CategoriaController extends Controller
{
    public function index(Request $request)
    {
<<<<<<< HEAD
        //Obtener todas las categorías
        $categorias = Categoria::orderBy('id', 'desc')->paginate(10);
    
=======
        //if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $categorias = Categoria::orderBy('id', 'desc')->paginate(10);
        }
        else{
            $categorias = Categoria::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }

>>>>>>> 1c8fe470da6568ccf889c7e8456befc18f589f95
        return $categorias;
    }
    public function selectCategoria(Request $request){
        //listar las categorias disponibles en el modulo platillo
        $categorias = Categoria::where('condicion','=','1')
        ->select('id','nombre')->orderBy('id', 'asc')->get();
        return $categorias;
    }

    public function store(Request $request)
    {
        //Validar datos de creacion
        $v = \Validator::make($request->all(), [

            'nombre' => 'required|string|max:60',

        ]);

        if ($v->fails())
        {
            return response()->json(['message'=>'Errores de validación de datos en el servidor']);
        }
        //Crear una categoria nueva
        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        if($request->descripcion!=''||$request->descripcion!=null){
            $categoria->descripcion=$request->descripcion;
        }
        $categoria->condicion = '1';
        $categoria->save();
    }


    public function update($id, Request $request)
<<<<<<< HEAD
    {   
        //Actualizar una categoría
=======
    {
        //if (!$request->ajax()) return redirect('/');

>>>>>>> 1c8fe470da6568ccf889c7e8456befc18f589f95
        $categoria = Categoria::findOrFail($id);
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        $categoria->save();
    }

    public function desactivar($id)
    {
        //Desactivar la categoria
        $categoria = Categoria::findOrFail($id);
        $categoria->condicion = '0';
        $categoria->save();
        return $categoria;
    }

    public function activar($id)
    {
        //Activar una categoría
        $categoria = Categoria::findOrFail($id);
        $categoria->condicion = '1';
        $categoria->save();
    }
}
