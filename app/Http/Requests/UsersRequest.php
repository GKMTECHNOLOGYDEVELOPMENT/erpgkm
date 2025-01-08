<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UsersRequest extends FormRequest
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
            'name' => 'required',
            'bairro' => 'required',
            'rg' => 'required',
            'ciudad' => 'required',
            'celular' => 'required',
            'estado' => 'required',
            'situacao' => 'required',
            'rua' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'numero' => 'nullable',
            'id_permiso' => 'required',
        ];
    }
    public function attributes()
    {
        return[
            'name' => 'nombre del usuario',
            'bairro' => 'distrito del usuario',
            'rg' => 'DNI del usuario',
            'situacao' => 'estado del usuario',
            'rua' => 'dirección del usuario',
            'email' => 'correo del usuario',
            'id_permiso' => 'permisos del usuario',
        ];
    }
    public function messages()
    {
        return[
            'email.required.unique:users,email' => 'El correo debe ser único'
        ];
    }

}
