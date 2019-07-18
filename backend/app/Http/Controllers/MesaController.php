<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mesa;
use App\Http\Requests\MesaStoreRequest;
class MesaController extends Controller
{
    public function index(Request $request)
    {
       //listar mesas
        $mesas=Mesa::orderBy('id', 'asc')->paginate(10);
        return  $mesas;
    }

    public function selectMesa(Request $request){
        //seleccionar mesa especifica donde estan los clientes comiendo
        $filtro = $request->filtro;
        $mesas  = Mesa::where('numero','like','%'.$filtro .'%')
                ->select('id','numero','capacidad','descripcion')
                ->orderBy('id','asc')
                ->get();
        return ['mesas'=> $mesas];
    }

    public function store(MesaStoreRequest $request)
    {
        $mesa = new Mesa();
        $mesa->numero    =$request->numero;
        $mesa->capacidad =$request->capacidad;
        if($request->descripcion!=''||$request->descripcion!=null){
            $mesa->descripcion=$request->descripcion;
        }
        $mesa->condicion ='1';
        $mesa->save();
        return $mesa;
    }

    public function update($id, MesaStoreRequest $request)
    {
        $mesa = Mesa::findOrFail($id);
        $mesa->numero       = $request->numero;
        $mesa->capacidad    = $request->capacidad;
        $mesa->descripcion  = $request->descripcion;
        $mesa->condicion    ='1';
        $mesa->save();
        return $mesa;
    }
    public function desactivar($id)
    {
        $mesa = Mesa::findOrFail($id);
        $mesa->condicion='0';
        $mesa->save();
    }
    public function activar($id)
    {
        $mesa = Mesa::findOrFail($id);
        $mesa->condicion='1';
        $mesa->save();
    }
    public function getAllMesas(){
        $mesas = Mesa::orderBy('numero')->get();
        return $mesas;
    }
}
