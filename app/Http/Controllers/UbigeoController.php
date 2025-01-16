<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UbigeoController extends Controller
{
    // Esta función se puede usar para filtrar provincias por departamento
    public function getProvinciasByDepartamento(Request $request)
    {
        // Cargar los datos desde el archivo provincias.json
        $provincias = json_decode(file_get_contents(public_path('ubigeos/provincias.json')), true);
    
        // Verificar que el departamento tenga provincias asociadas
        if (isset($provincias[$request->departamento_id])) {
            return response()->json($provincias[$request->departamento_id]); // Retornar las provincias asociadas a ese departamento
        } else {
            return response()->json([]); // Retornar un array vacío si no se encuentran provincias
        }
    }
    

    public function getDistritosByProvincia(Request $request)
    {
        // Cargar los datos desde el archivo distritos.json
        $distritos = json_decode(file_get_contents(public_path('ubigeos/distritos.json')), true);
    
        // Verificar que la provincia tenga distritos asociados
        if (isset($distritos[$request->provincia_id])) {
            return response()->json($distritos[$request->provincia_id]); // Retornar los distritos asociados a esa provincia
        } else {
            return response()->json([]); // Retornar un array vacío si no se encuentran distritos
        }
    }


    public function getProvincias($departamentoId)
    {
        // Obtener todas las provincias del archivo JSON
        $provincias = json_decode(file_get_contents(public_path('ubigeos/provincias.json')), true);
    
        // Verifica que las provincias existen para el departamentoId
        if (isset($provincias[$departamentoId])) {
            // Si existen, devuelve las provincias correspondientes
            return response()->json(['provincias' => $provincias[$departamentoId]]);
        }
    
        // Si no hay provincias, devolver un mensaje de error
        return response()->json(['error' => 'No se encontraron provincias para este departamento']);
    }
    
    public function getDistritos($provinciaId)
    {
        // Obtener todos los distritos del archivo JSON
        $distritos = json_decode(file_get_contents(public_path('ubigeos/distritos.json')), true);
    
        // Verifica si existen distritos para la provinciaId
        if (isset($distritos[$provinciaId])) {
            return response()->json(['distritos' => $distritos[$provinciaId]]);
        }
    
        return response()->json(['error' => 'No se encontraron distritos para esta provincia']);
    }
    
    

    
    
}
