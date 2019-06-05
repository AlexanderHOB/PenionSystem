<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mesa;
class MesaController extends Controller
{
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == ''){
            $mesas=Mesa::orderBy('id', 'asc')->paginate(10);
        }
        else{
            $mesas=Mesa::where($criterio, 'like', '%'.$buscar.'%')->orderBy('id', 'asc')->paginate(10);
        }
        return[
            'pagination'    =>[
                'total'         =>$mesas->total(),
                'current_page'  =>$mesas->currentPage(),
                'per_page'      =>$mesas->perPage(),
                'last_page'     =>$mesas->lastPage(),
                'from'          =>$mesas->firstItem(),
                'to'            =>$mesas->lastItem(),
            ],
            'mesas' =>  $mesas
        ];
    }
    
    public function selectMesa(Request $request){

        if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $mesas=Mesa::where('numero','like','%'.$filtro .'%')
        ->select('id','numero','capacidad','descripcion')
        ->orderBy('id','asc')
        ->get();
        return ['mesas'=> $mesas];
    }

    public function store(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $mesa = new Mesa();
        $mesa->numero=$request->numero;
        $mesa->capacidad=$request->capacidad;
        $mesa->descripcion=$request->descripcion;
        $mesa->condicion='1';
        $mesa->save();
        return $mesa;
    }

    public function update($id, Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $mesa = Mesa::findOrFail($id);
        $mesa->numero=$request->numero;
        $mesa->capacidad=$request->capacidad;
        $mesa->descripcion=$request->descripcion;
        $mesa->condicion='1';
        $mesa->save();
        return $mesa;
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $mesa = Mesa::findOrFail($request->id);
        $mesa->condicion='0';
        $mesa->save();
    }
    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $mesa = Mesa::findOrFail($request->id);
        $mesa->condicion='1';
        $mesa->save();
    }
}
