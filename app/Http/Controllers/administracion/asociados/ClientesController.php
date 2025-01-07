<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function index()
    {
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.clientes'); 
    }
    // Crear un nuevo Cliente
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'fecha_registro' => 'required|date',
            'direccion' => 'required|string|max:255',
            'nacionalidad' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'distrito' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:255',
            'estado' => 'required|boolean',
        ]);

        $cliente = new Cliente();
        $cliente->nombre = $request->nombre;
        $cliente->documento = $request->documento;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->fecha_registro = $request->fecha_registro;
        $cliente->direccion = $request->direccion;
        $cliente->nacionalidad = $request->nacionalidad;
        $cliente->departamento = $request->departamento;
        $cliente->provincia = $request->provincia;
        $cliente->distrito = $request->distrito;
        $cliente->codigo_postal = $request->codigo_postal;
        $cliente->estado = $request->estado;
        $cliente->save();

        return response()->json(['message' => 'Cliente creado correctamente'], 201);
    }

    // Actualizar un Cliente
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'fecha_registro' => 'required|date',
            'direccion' => 'required|string|max:255',
            'nacionalidad' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'distrito' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:255',
            'estado' => 'required|boolean',
        ]);

        $cliente = Cliente::findOrFail($id);
        $cliente->nombre = $request->nombre;
        $cliente->documento = $request->documento;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->fecha_registro = $request->fecha_registro;
        $cliente->direccion = $request->direccion;
        $cliente->nacionalidad = $request->nacionalidad;
        $cliente->departamento = $request->departamento;
        $cliente->provincia = $request->provincia;
        $cliente->distrito = $request->distrito;
        $cliente->codigo_postal = $request->codigo_postal;
        $cliente->estado = $request->estado;
        $cliente->save();

        return response()->json(['message' => 'Cliente actualizado correctamente']);
    }

    // Eliminar un Cliente
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return response()->json(['message' => 'Cliente eliminado correctamente']);
    }

}
