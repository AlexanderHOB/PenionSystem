<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MesaStoreRequest extends FormRequest
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
            'numero' => 'required|integer',
            'capacidad' => 'required',
        ];
        
    }
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json(['message'=>'Errores de validación de datos en el servidor'], 422));
    }
}
