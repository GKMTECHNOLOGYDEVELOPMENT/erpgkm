<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nombre' => 'required',
            'documento' => 'required',
            'direccion' => 'nullable',
            'departamento' => 'nullable',
            'provincia' => 'nullable',
            'distrito' => 'nullable',
            'pais' => 'nullable',
            'cod_postal' => 'nullable',
            'telefono' => 'nullable',
            'celular' => 'nullable',
            'telefono' => 'nullable',
            'email' => 'email|nullable',
            // 'telContact' => 'nullable',
            // 'emailContact' => 'email|nullable',
        ];
    }

    public function attributes()
    {
        return[
            'nombre' => 'nombre del cliente',
            'cod_postal' => 'código postal'
        ];
    }

    public function messages()
    {
        return[
            'documento.required' => 'Debe ingresar documento del cliente'
        ];
    }
}
