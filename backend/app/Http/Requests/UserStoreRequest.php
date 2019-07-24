<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\UserStoreRequest;

class UserStoreRequest extends FormRequest
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
            'nombre' => 'required|string|',
            'dni' => 'required|string|max:5',
            'celular'    => 'required|string|max:60',
            'email' => 'required|max:150',
            'password' => 'required|integer',
            'color' => 'required|string|max:7',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json(['message'=>'Errores de validación de datos en el servidor'], 422));
    }
}
