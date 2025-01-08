<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TiendasRequest extends FormRequest
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
            'id_cliente' => 'required',
            'numero' => 'required',
            'nombre' => 'required',
            'direccion' => 'required',
            'telefono' => 'nullable',
            'email' => 'email|nullable'
        ];
    }

    public function attributes()
    {
        return[
            'nombre' => 'nombre de la tienda',
            'direccion' => 'dirección de la tienda',
            'numero' => 'número de la tienda',
        ];
    }

    public function messages()
    {
        return[
            'id_cliente.required' => 'Debe seleccionar cliente'
        ];
    }
}
