<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Proveedore;
use Illuminate\Http\Request;

class ProveedoresController extends Controller
{
    public function index()
    {
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.proveedores'); 
    }

    // Crear un nuevo Proveedor
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nombre' => 'required|string|max:255',
    //         'estado' => 'required|boolean',
    //         'pais' => 'required|string|max:255',
    //         'departamento' => 'required|string|max:255',
    //         'provincia' => 'required|string|max:255',
    //         'distrito' => 'required|string|max:255',
    //         'direccion' => 'required|string|max:255',
    //         'codigoPostal' => 'required|string|max:255',
    //         'telefono' => 'required|string|max:20',
    //         'email' => 'required|email|max:255',
    //         'numeroDocumento' => 'required|string|max:50',
    //         'idCompra' => 'nullable|integer',
    //         'idSucursal' => 'nullable|integer',
    //         'idTipoDocumento' => 'nullable|integer',
    //     ]);

    //     $proveedor = new Proveedore();
    //     $proveedor->nombre = $request->nombre;
    //     $proveedor->estado = $request->estado;
    //     $proveedor->pais = $request->pais;
    //     $proveedor->departamento = $request->departamento;
    //     $proveedor->provincia = $request->provincia;
    //     $proveedor->distrito = $request->distrito;
    //     $proveedor->direccion = $request->direccion;
    //     $proveedor->codigoPostal = $request->codigoPostal;
    //     $proveedor->telefono = $request->telefono;
    //     $proveedor->email = $request->email;
    //     $proveedor->numeroDocumento = $request->numeroDocumento;
    //     $proveedor->idCompra = $request->idCompra;
    //     $proveedor->idSucursal = $request->idSucursal;
    //     $proveedor->idTipoDocumento = $request->idTipoDocumento;
    //     $proveedor->save();

    //     return response()->json(['message' => 'Proveedor creado correctamente'], 201);
    // }


    // Actualizar un Proveedor
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'nombre' => 'required|string|max:255',
    //         'estado' => 'required|boolean',
    //         'pais' => 'required|string|max:255',
    //         'departamento' => 'required|string|max:255',
    //         'provincia' => 'required|string|max:255',
    //         'distrito' => 'required|string|max:255',
    //         'direccion' => 'required|string|max:255',
    //         'codigoPostal' => 'required|string|max:255',
    //         'telefono' => 'required|string|max:20',
    //         'email' => 'required|email|max:255',
    //         'numeroDocumento' => 'required|string|max:50',
    //         'idCompra' => 'nullable|integer',
    //         'idSucursal' => 'nullable|integer',
    //         'idTipoDocumento' => 'nullable|integer',
    //     ]);

    //     $proveedor = Proveedore::findOrFail($id);
    //     $proveedor->nombre = $request->nombre;
    //     $proveedor->estado = $request->estado;
    //     $proveedor->pais = $request->pais;
    //     $proveedor->departamento = $request->departamento;
    //     $proveedor->provincia = $request->provincia;
    //     $proveedor->distrito = $request->distrito;
    //     $proveedor->direccion = $request->direccion;
    //     $proveedor->codigoPostal = $request->codigoPostal;
    //     $proveedor->telefono = $request->telefono;
    //     $proveedor->email = $request->email;
    //     $proveedor->numeroDocumento = $request->numeroDocumento;
    //     $proveedor->idCompra = $request->idCompra;
    //     $proveedor->idSucursal = $request->idSucursal;
    //     $proveedor->idTipoDocumento = $request->idTipoDocumento;
    //     $proveedor->save();

    //     return response()->json(['message' => 'Proveedor actualizado correctamente']);
    // }

    // // Eliminar un Proveedor
    // public function destroy($id)
    // {
    //     $proveedor = Proveedore::findOrFail($id);
    //     $proveedor->delete();

    //     return response()->json(['message' => 'Proveedor eliminado correctamente']);
    // }


    public function getAll()
{
    // Obtén todos los datos de la tabla cliente
    $clientes = Cliente::all();

    // Procesa los datos
    $clientesData = $clientes->map(function ($cliente) {
        return [
            'idCliente' => $cliente->idCliente,
            'nombre' => $cliente->nombre,
            'documento' => $cliente->documento,
            'telefono' => $cliente->telefono,
            'email' => $cliente->email,
            'fecha_registro' => $cliente->fecha_registro ? $cliente->fecha_registro->format('d/m/Y') : '',
            'direccion' => $cliente->direccion,
            'nacionalidad' => $cliente->nacionalidad,
            'departamento' => $cliente->departamento,
            'provincia' => $cliente->provincia,
            'distrito' => $cliente->distrito,
            'codigo_postal' => $cliente->codigo_postal,
            'estado' => $cliente->estado ? 'Activo' : 'Inactivo',
        ];
    });

    // Retorna los datos en formato JSON
    return response()->json($clientesData);
}


}
