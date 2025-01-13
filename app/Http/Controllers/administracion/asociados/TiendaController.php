<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TiendaController extends Controller
{
    public function index()
    {
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.tienda.index'); 
    }
    

    // En el controlador TiendaController.php
public function store(Request $request)
{
    try {
        // Validar y obtener los datos de la tienda
        $dataTienda = [
            'nombre' => $request->nombre, // Solo 'nombre' se almacenará
        ];

        // Guardar la tienda en la base de datos
        Log::info('Insertando tienda:', $dataTienda);
        Tienda::insert($dataTienda);

        // Obtener la última tienda insertada para obtener su ID
        $data = Tienda::latest('idTienda')->first();
        $idTienda = $data->idTienda;
        Log::info('Tienda insertada con ID: ' . $idTienda);

        // Responder con JSON
        return response()->json([
            'success' => true,
            'message' => 'Tienda agregada correctamente',
            'data' => $dataTienda,
        ]);
    } catch (\Exception $e) {
        // Log para capturar el error
        Log::error('Error al guardar la tienda: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Ocurrió un error al guardar la tienda.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    public function getAll()
    {
        // Obtén todos los datos de la tabla tienda
        $tiendas = Tienda::all();

        // Procesa los datos
        $tiendasData = $tiendas->map(function ($tienda) {
            return [
                'idTienda' => $tienda->idTienda,
                'nombre' => $tienda->nombre,
            ];
        });

        // Retorna los datos en formato JSON
        return response()->json($tiendasData);
    }


}