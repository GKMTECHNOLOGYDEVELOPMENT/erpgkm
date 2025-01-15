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
            'nombre' => 'required|string|max:255',
            'ruc' => 'required|string|size:11', // Asumiendo que RUC tiene una longitud fija de 11 caracteres
            'celular' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
            'referencia' => 'nullable|string|max:255',
            'lat' => 'nullable|size:18', // Si el campo "lat" debe tener una longitud fija de 18
            'lng' => 'nullable|size:18', // Similar a "lat", validamos que tenga longitud de 18
            'idCliente' => 'required|integer|exists:clientes,id', // Asumiendo que idCliente hace referencia a una tabla 'clientes'
        ];
    }

    /**
     * Get the custom validation messages for the request.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'nombre.required' => 'Debe ingresar el nombre de la tienda.',
            'ruc.required' => 'Debe ingresar el RUC de la tienda.',
            'ruc.size' => 'El RUC debe tener 11 caracteres.',
            'celular.required' => 'Debe ingresar el número de celular de la tienda.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'idCliente.required' => 'Debe seleccionar un cliente.',
            'idCliente.exists' => 'El cliente seleccionado no existe.',
            'lat.size' => 'La latitud debe tener una longitud de 18 caracteres.',
            'lng.size' => 'La longitud debe tener una longitud de 18 caracteres.',
        ];
    }
}
