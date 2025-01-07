<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Models\Clientegeneral;
use Illuminate\Http\Request;

class ClienteGeneralController extends Controller
{
    public function index()
    {
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.cliente-general'); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'estado' => 'required|boolean',
            'foto' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $clienteGeneral = new Clientegeneral();
        $clienteGeneral->descripcion = $request->descripcion;
        $clienteGeneral->estado = $request->estado;
        if ($request->hasFile('foto')) {
            $clienteGeneral->foto = file_get_contents($request->file('foto'));
        }
        $clienteGeneral->save();

        return response()->json(['message' => 'Cliente general creado'], 201);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'estado' => 'required|boolean',
            'foto' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $clienteGeneral = ClienteGeneral::findOrFail($id);
        $clienteGeneral->descripcion = $request->descripcion;
        $clienteGeneral->estado = $request->estado;
        if ($request->hasFile('foto')) {
            $clienteGeneral->foto = file_get_contents($request->file('foto'));
        }
        $clienteGeneral->save();

        return response()->json(['message' => 'Cliente general actualizado']);
    }


    public function destroy($id)
    {
        ClienteGeneral::findOrFail($id)->delete();
        return response()->json(['message' => 'Cliente general eliminado']);
    }



    public function getAll()
    {
        // Obtén todos los datos de la tabla clientegeneral
        $clientes = Clientegeneral::all();
    
        // Procesa los datos sin la columna 'foto'
        $clientesData = $clientes->map(function ($cliente) {
            return [
                'idClienteGeneral' => $cliente->idClienteGeneral,
                'descripcion' => $cliente->descripcion,
                'estado' => $cliente->estado ? 'Activo' : 'Inactivo', // Convertir estado a texto
            ];
        });
    
        // Retorna los datos en formato JSON
        return response()->json($clientesData);
    }
    



}
