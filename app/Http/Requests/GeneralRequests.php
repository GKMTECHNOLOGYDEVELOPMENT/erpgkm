<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GeneralRequests extends FormRequest
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
            'descripcion' => 'required',
            'logo' => 'mimes:jpeg,jpg,png'
        ];
    }


    public function messages()
    {
        return[
            'descripcion.required' => 'Debe ingresar nombre del cliente',
            'logo' => 'Solo acepta archivos de tipo imagen'
        ];
    }
}
