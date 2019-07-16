<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class EmpleadoUpdateRequest extends FormRequest
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
            'nombres'           =>  'required|string',
            'apellidos'         =>  'required|string',
            'tipo_documento'    =>  'required|string',
            'documento'               =>  'required|string|max:11',
            'celular'           =>  'max:9',
            'email'             =>  'email',
            'area_trabajo'      =>  'required|string|max:80',
            'puesto_trabajo'    =>  'required|string|max:80',
            'tipo_contrato'     =>  'required|string|max:80',
            'sueldo'            =>  'required|numeric',
        ];
    }
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json(['message'=>'Errores de validación de datos en el servidor'], 422));
    }
}
