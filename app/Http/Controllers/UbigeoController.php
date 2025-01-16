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
    
        // Filtrar las provincias que corresponden al departamentoId
        $provinciasDelDepartamento = [];
        foreach ($provincias as $provincia) {
            // Verificar que la clave 'id_padre_ubigeo' exista antes de acceder a ella
            if (isset($provincia['id_padre_ubigeo']) && $provincia['id_padre_ubigeo'] == $departamentoId) {
                $provinciasDelDepartamento[] = $provincia;
            }
        }
    
        // Devolver las provincias como respuesta JSON
        return response()->json($provinciasDelDepartamento);
    }
    
    
}
