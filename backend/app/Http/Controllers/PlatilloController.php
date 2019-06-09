<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Platillo;
class PlatilloController extends Controller
{
    public function index(Request $request )
    {

        $buscar= $request->buscar;
        $criterio=$request->criterio;
        if($buscar==''){
            $platillos=Platillo::join('categorias','platillos.idcategoria','=','categorias.id')
            ->select('platillos.id','platillos.idcategoria','platillos.codigo','platillos.area',
            'platillos.nombre','categorias.nombre as nombre_categoria',
            'platillos.precio','platillos.descripcion','platillos.condicion')
            ->orderBy('platillos.id','desc')->paginate(10);
        }else{
            $platillos=Platillo::join('categorias','platillos.idcategoria','=','categorias.id')
            ->select('platillos.id','platillos.idcategoria','platillos.codigo',
            'platillos.nombre','categorias.nombre as nombre_categoria',
            'platillos.precio','platillos.descripcion','platillos.condicion')
            ->where('platillos.'.$criterio,'like','%'.$buscar.'%')
            ->orderBy('platillos.id','desc')->paginate(10);           
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
        $v = \Validator::make($request->all(), [
            
            'idcategoria' => 'required|integer',
            'codigo' => 'required|string|max:5',
            'nombre'    => 'required|string|max:60',
            'area' => 'required|max:150',
            'precio' => 'required|integer',

        ]);
 
        if ($v->fails())
        {
            return ['Errores de validación de datos en el servidor'];
        }

        $platillo = new Platillo();
        $platillo->idcategoria=$request->idcategoria;
        $platillo->codigo=$request->codigo;
        $platillo->nombre=$request->nombre;
        $platillo->area=$request->area;
        $platillo->precio=$request->precio;
        $platillo->descripcion=$request->descripcion;
        $platillo->condicion='1';
        $platillo->save();

        return ['Platillo creado correctamente'];

    }
    public function update($id, Request $request)
    {
        //VALIDACION DE ACTUALIZACIÓN
        $v = \Validator::make($request->all(), [
            
            'idcategoria' => 'required|integer',
            'codigo' => 'required|string|max:5',
            'nombre'    => 'required|string|max:60',
            'area' => 'required|max:150',
            'precio' => 'required|integer',

        ]);
 
        if ($v->fails())
        {
            return ['Errores de validación de datos en el servidor'];
        }

        $platillo = Platillo::findOrFail($id);
        $platillo->idcategoria=$request->idcategoria;
        $platillo->codigo=$request->codigo;
        $platillo->nombre=$request->nombre;
        $platillo->area=$request->area;
        $platillo->precio=$request->precio;
        $platillo->descripcion=$request->descripcion;
        $platillo->condicion='1';
        $platillo->save();
    }
    public function desactivar($id)
    {
        //if(!$request->ajax()) return redirect('/');
        $platillo = Platillo::findOrFail($id);
        $platillo->condicion='0';
        $platillo->save();
    }
    public function activar($id)
    {
        //if(!$request->ajax()) return redirect('/');
        $platillo = Platillo::findOrFail($id);
        $platillo->condicion='1';
        $platillo->save();
    }
}
