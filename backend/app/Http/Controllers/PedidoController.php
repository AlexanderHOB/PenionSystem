<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Pedido,DetallePedido,Modificacion};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index(){
        //if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $pedidos = Pedido::orderBy('fecha_registro','desc')->paginate(40);
        }
        else{
            $pedidos = Pedido::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(40);
        }
        return ['pedidos'=>$pedidos];
    }
    public function store(Request $request){
        //if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();

            $pedido = new Pedido();
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
            return [
                'id' => $pedido->id
            ];
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
} 
