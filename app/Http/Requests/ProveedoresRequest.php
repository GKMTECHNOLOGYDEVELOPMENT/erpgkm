<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProveedoresRequest extends FormRequest
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
            'estado' => 'required|boolean',                   // El estado debe ser un valor booleano
            'departamento' => 'nullable|string|max:255',      // Departamento es opcional
            'provincia' => 'nullable|string|max:255',         // Provincia es opcional
            'distrito' => 'nullable|string|max:255',          // Distrito es opcional
            'direccion' => 'nullable|string|max:255',         // Dirección es opcional
            'codigoPostal' => 'nullable|string|max:255',      // Código Postal es opcional
            'telefono' => 'nullable|string|max:20',           // Teléfono es opcional y con longitud máxima de 20
            'email' => 'nullable|email|max:255',              // Email es opcional pero si se ingresa debe ser válido
            'numeroDocumento' => 'nullable|string|max:50',   // Número de documento es opcional
            'idArea' => 'required|integer|exists:area,idArea', // Validamos si el idArea existe en la tabla 'area'
            'idTipoDocumento' => 'required|integer|exists:tipodocumento,idTipoDocumento', // Validamos si el idTipoDocumento existe
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
            'nombre' => 'nombre del proveedor',
            'departamento' => 'departamento',
            'provincia' => 'provincia',
            'distrito' => 'distrito',
            'direccion' => 'dirección',
            'codigoPostal' => 'código postal',
            'telefono' => 'teléfono',
            'email' => 'correo electrónico',
            'numeroDocumento' => 'número de documento',
            'idArea' => 'área',
            'idTipoDocumento' => 'tipo de documento',
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
            'nombre.required' => 'El nombre del proveedor es obligatorio.',
            'idArea.required' => 'Debe seleccionar un área.',
            'idArea.exists' => 'El área seleccionada no existe.',
            'idTipoDocumento.required' => 'Debe seleccionar un tipo de documento.',
            'idTipoDocumento.exists' => 'El tipo de documento seleccionado no existe.',
            'email.email' => 'Debe ingresar una dirección de correo electrónico válida.',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'numeroDocumento.max' => 'El número de documento no puede tener más de 50 caracteres.',
        ];
    }
}
