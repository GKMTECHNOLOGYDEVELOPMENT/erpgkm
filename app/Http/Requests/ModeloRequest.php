<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ModeloRequest extends FormRequest
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
                'id_tipo_producto' => 'required',
                'id_marca' => 'required',
                'descripcion' => 'required',
        ];
    }

    public function messages()
    {
        return[
            'descripcion.required' => 'Debe ingresar descripción del modelo'
        ];
    }
}
