<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Mesa, Pedido,Platillo,DetallePedido};
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
        $mesas = Mesa::where('estado','=','Disponible')->get();
        $pedidos=Pedido::join('detalles_pedido','pedidos.id','=','detalles_pedido.pedido_id')
        ->join('mesas','detalles_pedido.mesa_id','=','mesas.id')
        ->select('pedidos.id','pedidos.estado','pedidos.tipo_pedido','pedidos.numero_orden','pedidos.fecha_registro','pedidos.total','pedidos.descuento','pedidos.especial','pedidos.mozo_id')
        ->groupBy('pedidos.id','pedidos.estado')
        ->where('mesas.estado','=','Ocupado')
        ->where('pedidos.estado','=','Pendiente')->get();
        $pedidosFiltrados=[];
        
        foreach($pedidos as $pedido){
        
            
            $dp=$pedido->DetallesPedidos;
            foreach ($dp as $p){
                $p->platillo;
            }
            $response=[
                'id'                => $pedido->id,
                'tipo_pedido'       => $pedido->tipo_pedido,
                "estado"            => $pedido->estado,
                "numero_orden"      => $pedido->numero_orden,
                "total"             => $pedido->total,
                "descuento"         => $pedido->descuento,
                "especial"          => $pedido->especial,
                "nombre_mozo"       => $pedido->user->empleado->persona['nombres'],
                "rol_mozo"          => $pedido->user->rol['nombre'],
                "detalles_pedidos"  =>$dp,
            ];
            array_push( $pedidosFiltrados,$response);
        }
                 
        return ['libres'=>$mesas,'pedidos'=>$pedidosFiltrados];
    }
}
