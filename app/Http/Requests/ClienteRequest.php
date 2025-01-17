<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ClienteRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check(); // Esto asegura que solo los usuarios autenticados puedan hacer la solicitud
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255',          // El nombre es obligatorio
            'documento' => 'required|string|max:255',       // El documento es obligatorio
            'direccion' => 'nullable|string|max:255',       // Direcxdción es opcional
            'departamento' => 'nullable|string|max:255',    // Departamento es opcional
            'provincia' => 'nullable|string|max:255',       // Provincia es opcional
            'distrito' => 'nullable|string|max:255',        // Distrito es opcional
            'telefono' => 'nullable|string|max:255',        // Teléfono es opcional
            'email' => 'nullable|email|max:255',            // Email es opcional pero si se ingresa debe ser un email válido
            'idTipoDocumento' => 'required|integer|exists:tipodocumento,idTipoDocumento', // Validamos si el idTipoDocumento existe en la tabla 'tipodocumento'
            'idClienteGeneral' => 'required|integer|exists:clientegeneral,idClienteGeneral', // Validamos si el idClienteGeneral existe en la tabla 'clientegeneral'
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
            'nombre' => 'nombre del cliente',
            'documento' => 'documento del cliente',
            'direccion' => 'dirección',
            'departamento' => 'departamento',
            'provincia' => 'provincia',
            'distrito' => 'distrito',
            'telefono' => 'teléfono',
            'email' => 'correo electrónico',
            'idTipoDocumento' => 'tipo de documento',
            'idClienteGeneral' => 'cliente general',
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
            'documento.required' => 'Debe ingresar el documento del cliente.',
            'nombre.required' => 'El nombre del cliente es obligatorio.',
            'idTipoDocumento.required' => 'Debe seleccionar un tipo de documento.',
            'idClienteGeneral.required' => 'Debe seleccionar un cliente general.',
            'email.email' => 'Debe ingresar una dirección de correo electrónico válida.',
            'telefono.max' => 'El teléfono no puede tener más de 255 caracteres.',
        ];
    }
}
