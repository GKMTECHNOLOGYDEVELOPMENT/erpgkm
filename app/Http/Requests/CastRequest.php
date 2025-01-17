<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CastRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check(); // Asegura que solo los usuarios autenticados puedan hacer la solicitud
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255',           // El nombre es obligatorio
            'ruc' => 'required|string|max:50',                 // RUC es obligatorio y máximo 50 caracteres
            'departamento' => 'nullable|string|max:255',      // Departamento es opcional
            'provincia' => 'nullable|string|max:255',         // Provincia es opcional
            'distrito' => 'nullable|string|max:255',          // Distrito es opcional
            'direccion' => 'nullable|string|max:255',         // Dirección es opcional
            'telefono' => 'nullable|string|max:20',           // Teléfono es opcional y con longitud máxima de 20
            'email' => 'nullable|email|max:255',              // Email es opcional pero si se ingresa debe ser válido
        ];
    }

    /**
     * Obtiene los nombres personalizados para los atributos.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'nombre' => 'nombre del cast',
            'ruc' => 'RUC',
            'departamento' => 'departamento',
            'provincia' => 'provincia',
            'distrito' => 'distrito',
            'direccion' => 'dirección',
            'telefono' => 'teléfono',
            'email' => 'correo electrónico',
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'nombre.required' => 'El nombre del cast es obligatorio.',
            'ruc.required' => 'El RUC es obligatorio.',
            'ruc.max' => 'El RUC no puede tener más de 50 caracteres.',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'email.email' => 'Debe ingresar una dirección de correo electrónico válida.',
            'departamento.max' => 'El departamento no puede tener más de 255 caracteres.',
            'provincia.max' => 'La provincia no puede tener más de 255 caracteres.',
            'distrito.max' => 'El distrito no puede tener más de 255 caracteres.',
            'direccion.max' => 'La dirección no puede tener más de 255 caracteres.',
        ];
    }
}
