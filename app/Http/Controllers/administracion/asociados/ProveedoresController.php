<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProveedoresRequest;
use App\Models\Area;
use App\Models\Cliente;
use App\Models\Proveedore;
use App\Models\Tipodocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProveedoresController extends Controller
{
    public function index()
    {
        $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
        $tiposDocumento = Tipodocumento::all();
        $areas = Area::all();
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.proveedores.index', compact('departamentos', 'tiposDocumento','areas') ); 
    }

    public function store(ProveedoresRequest $request)
{
    try {
        // Datos del proveedor, ya validados
        $dataProveedores = $request->validated();
    
        // Establecer valores predeterminados para 'estado' y 'fecha_registro'
        $dataProveedores['estado'] = 1; // Valor predeterminado para 'estado' (activo)
    
    
        // Verificar los datos validados con los valores predeterminados
        Log::debug('Datos validados recibidos:', $dataProveedores);
    
        // Guardar el proveedor
        $proveedor = Proveedore::create($dataProveedores);
    
        // Verificar si el proveedor se guardó correctamente
        Log::debug('Proveedor insertado:', $proveedor->toArray()); // Convertir el proveedor a array
    
        // Responder con JSON
        return response()->json([
            'success' => true,
            'message' => 'Proveedor agregado correctamente',
            'data' => $proveedor,
        ]);
    } catch (\Exception $e) {
        Log::error('Error al guardar el proveedor: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Ocurrió un error al guardar el proveedor.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

 


    public function getAll()
    {
        // Obtener todos los proveedores con sus relaciones (TipoDocumento y Area)
        $proveedores = Proveedore::with(['tipoDocumento', 'area'])->get();
    
        // Procesa los datos para incluir los campos necesarios, mostrando los nombres relacionados
        $proveedoresData = $proveedores->map(function ($proveedor) {
            return [
                'idProveedor'    => $proveedor->idProveedor,
                'nombre'         => $proveedor->nombre,
                'estado'         => $proveedor->estado == 1 ? 'Activo' : 'Inactivo',
                'departamento'   => $proveedor->departamento,
                'provincia'      => $proveedor->provincia,
                'distrito'       => $proveedor->distrito,
                'direccion'      => $proveedor->direccion,
                'codigoPostal'   => $proveedor->codigoPostal,
                'telefono'       => $proveedor->telefono,
                'email'          => $proveedor->email,
                'numeroDocumento'=> $proveedor->numeroDocumento,
                'idArea'         => $proveedor->area->nombre, // Mostrar nombre del área
                'idTipoDocumento'=> $proveedor->tipoDocumento->nombre, // Mostrar nombre del tipo de documento
            ];
        });
    
        // Retorna los datos en formato JSON
        return response()->json($proveedoresData);
    }
    


}