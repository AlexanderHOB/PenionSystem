<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Platillo;
class PlatilloController extends Controller
{
    public function index(Request $request )
    {

        //if(!$request->ajax()) return redirect('/');

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
        
        return [
            'pagination' => [
                'total' =>          $platillos->total(),
                'current_page'=>    $platillos->currentPage(),
                'per_page'=>        $platillos->perPage(),
                'last_page'=>       $platillos->lastPage(),
                'from' =>           $platillos->firstItem(),
                'to' =>             $platillos->lastItem()
            ],
            'platillos' => $platillos
        ];
        
    }
    public function buscarPlatillo(Request $request){
        //if (!$request->ajax()) return redirect('/');
 
        $filtro = $request->filtro;
        $platillos = Platillo::where('codigo','=', $filtro)
        ->select('id','nombre','precio')
        ->orderBy('nombre', 'asc')->take(1)->get();
 
        return ['platillos' => $platillos];
    }
    public function listarPlatillo(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

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
        //if(!$request->ajax()) return redirect('/');

        $v = \Validator::make($request->all(), [
            
            'full_name' => 'required',
            'address' => 'required',
            'email'    => 'required|email|unique:clients',
            'phone_number' => 'required',
            'type' => 'required|in:empresa,particular',
            'register' => 'required_if:type,empresa'
        ]);
 
        if ($v->fails())
        {
            return redirect()->back()->withInput()->withErrors($v->errors());
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

    }
    public function update(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $platillo = Platillo::findOrFail($request->id);
        $platillo->idcategoria=$request->idcategoria;
        $platillo->codigo=$request->codigo;
        $platillo->nombre=$request->nombre;
        $platillo->area=$request->area;
        $platillo->precio=$request->precio;
        $platillo->descripcion=$request->descripcion;
        $platillo->condicion='1';
        $platillo->save();
    }
    public function desactivar(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $platillo = Platillo::findOrFail($request->id);
        $platillo->condicion='0';
        $platillo->save();
    }
    public function activar(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $platillo = Platillo::findOrFail($request->id);
        $platillo->condicion='1';
        $platillo->save();
    }
}
