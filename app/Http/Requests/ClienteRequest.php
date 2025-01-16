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
            'direccion' => 'nullable|string|max:255',       // Dirección es opcional
            'departamento' => 'nullable|string|max:255',    // Departamento es opcional
            'provincia' => 'nullable|string|max:255',       // Provincia es opcional
            'distrito' => 'nullable|string|max:255',        // Distrito es opcional
            'pais' => 'nullable|string|max:255',            // País es opcional
            'cod_postal' => 'nullable|string|max:20',       // Código postal es opcional
            'telefono' => 'nullable|string|max:255',        // Teléfono es opcional
            'celular' => 'nullable|string|max:255',         // Celular es opcional
            'email' => 'nullable|email|max:255',            // Email es opcional pero si se ingresa debe ser un email válido
            'fecha_registro' => 'required|date',            // Fecha de registro es obligatorio
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
            'pais' => 'país',
            'cod_postal' => 'código postal',
            'telefono' => 'teléfono',
            'celular' => 'celular',
            'email' => 'correo electrónico',
            'fecha_registro' => 'fecha de registro',
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
            'fecha_registro.required' => 'La fecha de registro es obligatoria.',
            'idTipoDocumento.required' => 'Debe seleccionar un tipo de documento.',
            'idClienteGeneral.required' => 'Debe seleccionar un cliente general.',
            'email.email' => 'Debe ingresar una dirección de correo electrónico válida.',
            'cod_postal.max' => 'El código postal no puede tener más de 20 caracteres.',
            'telefono.max' => 'El teléfono no puede tener más de 255 caracteres.',
        ];
    }
}
