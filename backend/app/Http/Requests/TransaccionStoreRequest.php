<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransaccionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'persona_id' => 'required|integer',
            'fecha_inicio' => 'required|date',
            'fecha_transaccion'    => 'required|date',
            'tipo' => 'required|max:60',
            'monto' => 'required|numeric',
            'motivo'=> 'max:254'
        ];
    }
    public function message(){
        return[
            'persona_id.required'   =>  'El id del empleado es requerido.',
            'persona_id.integer'    =>  'El id del empleado debe ser un entero.',

            'fecha_inicio.required' =>  'La fecha_inicio  es requerido.',
            'fecha_inicio.date'     =>  'La fecha_inicio debe ser tipo fecha.',

            'fecha_transaccion.required' =>  'La fecha_inicio  es requerido.',
            'fecha_transaccion.date'     =>  'La fecha_inicio debe ser tipo fecha.',

            'tipo.required' =>  'El tipo de transaccion es requerido',
            'tipo.max'      =>  'El tipo de transaccion debe tener maximo 60 letras.',

            'monto.required' =>  'El monto es requerido.',
            'monto.numeric'  =>  'El monto debe ser de tipo numerico.',

            'motivo.max'    =>  'El motivo debe tener máximo 255 letras',
        ];
    }
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json(['message'=>'Errores de validación de datos en el servidor'], 422));
    }
}
