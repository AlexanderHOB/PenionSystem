<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        //Obtener todas las categorías
        $categorias = Categoria::orderBy('id', 'desc')->paginate(10);
    
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
    {   
        //Actualizar una categoría
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
