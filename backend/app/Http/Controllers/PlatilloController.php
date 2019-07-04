<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Platillo;
class PlatilloController extends Controller
{
    public function index(Request $request )
    {
        //listar platillos
        $platillos   = Platillo::paginate(10);
        $categorias  = [];
        foreach($platillos as $platillo){
            array_push($categorias,$platillo->categoria);
        }
        return $platillos;
    }
    public function buscarPlatillo(Request $request){


        $filtro = $request->filtro;
        $platillos = Platillo::where('codigo','=', $filtro)
        ->select('id','nombre','precio')
        ->orderBy('nombre', 'asc')->take(1)->get();

        return ['platillos' => $platillos];
    }
    public function listarPlatillo(Request $request)
    {


        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $platillos = Platillo::join('categorias','platillos.idcategoria','=','categorias_platillos.id')
            ->select('platillos.id','platillos.idcategoria','platillos.codigo','platillos.nombre'
            ,'categorias_platillos.nombre as nombre_categoria','platillos.precio',
            'platillos.descripcion','platillos.condicion')
            ->orderBy('platillos.id', 'desc')->paginate(15);
        }
        else{
            $platillos = Platillo::join('categorias_platillos','platillos.idcategoria','=','categorias_platillos.id')
            ->select('platillos.id','platillos.idcategoria','platillos.codigo','platillos.nombre'
            ,'categorias_platillos.nombre as nombre_categoria','platillos.precio',
            'platillos.descripcion','platillos.condicion')
            ->where('platillos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('platillos.id', 'desc')->paginate(15);
        }


        return [
            'platillos' => $platillos
        ];
    }
    public function store(Request $request)
    {
        //validar datos
        $v = \Validator::make($request->all(), [

            'categoria_id' => 'required|integer',
            'codigo' => 'required|string|max:5',
            'nombre'    => 'required|string|max:60',
            'area' => 'required|max:150',
            'precio' => 'required|numeric',

        ]);

        if ($v->fails())
        {
            return response()->json(['message'=>'Errores de validación de datos en el servidor']);
        }

        $platillo = new Platillo();
        $platillo->categoria_id =$request->categoria_id;
        $platillo->codigo       =$request->codigo;
        $platillo->nombre       =$request->nombre;
        $platillo->area         =$request->area;
        $platillo->precio       =$request->precio;
        if($request->descripcion!=''||$request->descripcion!=null){
            $platillo->descripcion=$request->descripcion;
        }
        $platillo->condicion    ='1';
        $platillo->save();

        return ['Platillo creado correctamente'];

    }
    public function update($id, Request $request)
    {
        //VALIDACION DE ACTUALIZACIÓN
        $v = \Validator::make($request->all(), [

            'categoria_id' => 'required|integer',
            'codigo' => 'required|string|max:5',
            'nombre'    => 'required|string|max:60',
            'area' => 'required|max:150',
            'precio' => 'required|integer',

        ]);

        if ($v->fails())
        {
            return response()->json(['message'=>'Errores de validación de datos en el servidor']);
        }

        $platillo = Platillo::findOrFail($id);
        $platillo->categoria_id =$request->categoria_id;
        $platillo->codigo       =$request->codigo;
        $platillo->nombre       =$request->nombre;
        $platillo->area         =$request->area;
        $platillo->precio       =$request->precio;
        $platillo->descripcion  =$request->descripcion;
        $platillo->condicion    ='1';
        $platillo->save();
    }
    public function desactivar($id)
    {
        $platillo = Platillo::findOrFail($id);
        $platillo->condicion='0';
        $platillo->save();
    }
    public function activar($id)
    {
        $platillo = Platillo::findOrFail($id);
        $platillo->condicion='1';
        $platillo->save();
    }

    public function getAllPlatillos(){
        $platillos = Platillo::orderBy('id')->get();
        return $platillos;
    }
}
