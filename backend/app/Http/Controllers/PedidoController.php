<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Pedido,DetallePedido,Modificacion};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index(Request $request){
        $pedidos = Pedido::orderBy('fecha_registro','desc')->where('estado','=','Produccion')->paginate(40);
        $pedidoSimplificado=[];
        foreach ($pedidos as $pedido){
            $response=[
                'id'            =>$pedido->id,
                'estado'        =>$pedido->estado,
                'mozo_id'       =>$pedido->mozo_id,
                'mesa_id'       =>$pedido->mesa_id,
                'mesa_numero'   =>$pedido->mesa->numero,
                'mesa_capacidad'=>$pedido->mesa->capacidad,
                'mozo_color'    =>$pedido->user->color
            ];
            array_push($pedidoSimplificado,$response);
        }
        return $pedidoSimplificado;
    }
    public function detallePedido($id){
        $pedido = Pedido::findOrFail($id);
        $detallesPedidos=[];
        $detalles=$pedido->detallesPedidos;
        $pedidoSimplificado=[
            'id'            =>$pedido->id,
            'tipo_pedido'   =>$pedido->tipo_pedido,
            'fecha_registro'=>$pedido->fecha_registro,
            'total'         =>$pedido->total,
            'estado'        =>$pedido->estado,
            'mozo_id'       =>$pedido->mozo_id,
            'mozo_nombre'   =>$pedido->user->empleado->persona->nombres,
            'rol'           =>$pedido->user->rol->nombre,
            'numero_orden'  =>$pedido->id,
        ];
    
        foreach($detalles as $d){
            $response=[
                'nombre_platillo'   =>$d->platillo->nombre,
                'comentario'        =>$d->comentario,
                'cantidad'          =>$d->cantidad,
                'unidad_medida'     =>$d->platillo->unidad_de_medida,
                'valor_unitario'    =>$d->platillo->precio,
                'subtotal'          =>($d->platillo->precio * $d->cantidad),
                'platillo_id'       =>$d->platillo->id,
                'detalle_pedido_id' =>$d->id,
                
            ];
            array_push($detallesPedidos,$response);
        }
        $pedidoSimplificado['detalles_pedidos']=$detallesPedidos;
        return $pedidoSimplificado;
    
    }
    public function split(Request $request){
        $detalle_pedido_id = $request->detalle_pedido_id;
        $pedido = $request->pedido_id;
        $mesa   = $request->mesa_id;

        $detalle_pedido = DetallePedido::findOrFail($detalle_pedido_id);
        $detalle_pedido->pedido_id  = $pedido;
        $detalle_pedido->mesa_id    = $mesa;
        $detalle_pedido->save();

        return['Exito'];
    }
    public function store(Request $request){
        //if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();

            $pedido = new Pedido();
            $pedido->tipo_pedido    =$request->tipo_pedido;
            $pedido->numero_orden   ='1';
            $pedido->fecha_registro =Carbon::now('America/Lima');
            $pedido->mozo_id        =$request->user_id;
            $pedido->total          =$request->total;
            $pedido->descuento      =$request->descuento;
            $pedido->especial       =$request->especial;
            $pedido->mesa_id        =$request->mesa_id;
            $pedido->estado         ='Produccion';
            $pedido->save();
            $pedido->numero_orden   =$pedido->id;
            $pedido->save();

            $detalles =$request->detalles_pedido;
                //Array de detalles
            //Recorro todos los elementos

            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetallePedido();
                $detalle->pedido_id         = $pedido->id;
                $detalle->mesa_id           = $det['mesa_id'];
                $detalle->platillo_id       = $det['platillo_id'];
                $detalle->cantidad          = $det['cantidad'];
                $detalle->valor_unitario    = $det['valor_unitario'];
                $detalle->precio_unitario   = $det['precio_unitario'];
                $detalle->comentario        = $det['comentario'];
                $detalle->estado            = $det['estado'];
                $detalle->tipo_de_igv       = '8';
                $detalle->igv               = 0;
                $detalle->subtotal          = $det['subtotal'];
                $detalle->total             = $det['total'];      
                $detalle->save();
            }       
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
        
    }
    public function aumentarPedido(Request $request,$id){
        $pedido=Pedido::findOrFail($id);
        try{
            DB::beginTransaction();

            $detalles =$request->detalles_pedido;
            foreach($detalles as $ep=>$det)
                {
                    $detalle = new DetallePedido();
                    $detalle->pedido_id         = $pedido->id;
                    $detalle->mesa_id           = $det['mesa_id'];
                    $detalle->platillo_id       = $det['platillo_id'];
                    $detalle->cantidad          = $det['cantidad'];
                    $detalle->valor_unitario    = $det['valor_unitario'];
                    $detalle->precio_unitario   = $det['precio_unitario'];
                    $detalle->comentario        = $det['comentario'];
                    $detalle->estado            = $det['estado'];
                    $detalle->tipo_de_igv       = '8';
                    $detalle->igv               = 0;
                    $detalle->subtotal          = $det['subtotal'];
                    $detalle->total             = $det['total'];      
                    $detalle->save();
                }  
            DB::commit();    
        } catch (Exception $e){
            DB::rollBack();
        }
}
    public function update(Request $request){
        //if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();

            $pedido =Pedido::findOrFail($request->id);
            $pedido->tipo_pedido    =$request->tipo_pedido;
            $pedido->numero_orden   =$request->numero_orden;
            $pedido->fecha_registro =Carbon::now('America/Lima');
            $pedido->mozo_id        =$request->user_id;
            $pedido->total          =$request->total;
            $pedido->descuento      =$request->descuento;
            $pedido->especial       =$request->especial;
            $pedido->estado         ='Pendiente';
            $pedido->save();
            $detalles =$request->detalles_pedido;
                //Array de detalles
            //Recorro todos los elementos

            foreach($detalles as $ep=>$det)
            {
                $detalle = DetallePedido::findOrFail($det['id']);
                $detalle->pedido_id         = $pedido->id;
                $detalle->mesa_id           = $det['mesa_id'];
                $detalle->platillo_id       = $det['platillo_id'];
                $detalle->cantidad          = $det['cantidad'];
                $detalle->valor_unitario    = $det['valor_unitario'];
                $detalle->precio_unitario   = $det['precio_unitario'];
                $detalle->comentario        = $det['comentario'];
                $detalle->estado            = $det['estado'];
                $detalle->tipo_de_igv       = '8';
                $detalle->igv               = 0;
                $detalle->subtotal          = $det['subtotal'];
                $detalle->total             = $det['total'];      
                $detalle->save();
                $modificaciones=$det['modificaciones'];
                foreach($modificaciones as $md=>$mod){
                    $modificacion = new Modificacion();
                    $modificacion->modificaciones       = $mod['item'];
                    $modificacion->usuario_id           = $mod['user_id'];
                    $modificacion->fecha                = Carbon::now('America/Lima');
                    $modificacion->sustento             = $mod['sustento'];
                    $modificacion->cantidad_antigua     = $mod['cantidad_antigua'];
                    $modificacion->detalle_pedido_id    = $det['id'];
                    $modificacion->save();
                }
            }       
            DB::commit();
            return [
                'id' => $pedido->id
            ];
        } catch (Exception $e){
            DB::rollBack();
        }
        
    }
    public function anular($id){
        $pedido = Pedido::findOrFail($id);
        $pedido->estado='Anulado';
        $pedido->save();

    }
    public function especial($id){
        $pedido = Pedido::findOrFail($id);
        $pedido->especial = 1;
        $pedido->save();
    }
} 
