<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    protected $fillable = [
        'id',
        'tipo_comprobante',
        'serie',
        'numero',
        'sunat_transaccion',
        'cliente_id',
        'fecha_emision',
        'moneda',
        'porcentaje_de_igv',
        'descuento_total',
        'total',
        'pedido_id'
        ];

    public function cliente(){
        return $this->belognsTo('App\Persona','cliente_id');
    }
    public function tipoComprobante() {
        return $this->belognsTo('App\TipoComprobante','tipo_comprobante');
    }
    public function pedido(){
        return $this->belognsTo('App\Pedido');

    }
}
