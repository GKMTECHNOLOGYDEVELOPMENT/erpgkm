<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Models\Clientegeneral;
use App\Models\ContactoFinal;
use App\Models\Tipodocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContactoFinalController extends Controller
{
    public function index()
    {
        $tiposDocumento = Tipodocumento::all();
        return view('administracion.asociados.contactofinal.index', compact('tiposDocumento'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nombre_completo' => 'required|string|max:255',
                'idTipoDocumento' => 'required|integer|exists:tipodocumento,idTipoDocumento',
                'numero_documento' => 'required|string|max:20|unique:contactofinal,numero_documento',
                'correo' => 'nullable|email|max:255',
                'telefono' => 'nullable|string|max:15',
            ]);

            $validatedData['estado'] = true;

            $contactoFinal = ContactoFinal::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Contacto final agregado correctamente',
                'data' => $contactoFinal,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al guardar el contacto final: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar el contacto final.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getAll(Request $request)
    {
        $query = ContactoFinal::with('tipoDocumento');

        $total = ContactoFinal::count();

        // Buscador general
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre_completo', 'like', "%$search%")
                  ->orWhere('numero_documento', 'like', "%$search%")
                  ->orWhere('correo', 'like', "%$search%")
                  ->orWhere('telefono', 'like', "%$search%");
            });
        }

        $filtered = $query->count();

        $contactos = $query
            ->skip($request->start)
            ->take($request->length)
            ->get();

        $data = $contactos->map(function ($contacto) {
            return [
                'idContactoFinal' => $contacto->idContactoFinal,
                'tipo_documento' => $contacto->tipoDocumento->nombre,
                'numero_documento' => $contacto->numero_documento,
                'nombre_completo' => $contacto->nombre_completo,
                'correo' => $contacto->correo,
                'telefono' => $contacto->telefono,
                'estado' => $contacto->estado ? 'Activo' : 'Inactivo',
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    public function destroy($id)
    {
        try {
            $contactoFinal = ContactoFinal::findOrFail($id);
            $contactoFinal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Contacto final eliminado correctamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el contacto final.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

public function edit($id)
{
    $contactoFinal = ContactoFinal::with(['tipoDocumento', 'clientesGenerales' => function($query) {
        $query->select(
            'clientegeneral.idClienteGeneral', // 🔴 ESPECIFICAR TABLA
            'clientegeneral.descripcion', 
            'clientegeneral.estado'
        )
        ->where('clientegeneral.estado', 1); // 🔴 ESPECIFICAR TABLA
    }])->findOrFail($id);
    
    $tiposDocumento = Tipodocumento::all();
    
    // Obtener todos los clientes generales disponibles (solo activos)
    $clientesGenerales = ClienteGeneral::select('idClienteGeneral', 'descripcion', 'estado')
        ->where('estado', 1)
        ->get();
    
    // Obtener los clientes generales ya asociados a este contacto final
    $clientesGeneralesAsociados = $contactoFinal->clientesGenerales;

    return view('administracion.asociados.contactofinal.edit', compact(
        'contactoFinal', 
        'tiposDocumento', 
        'clientesGenerales', 
        'clientesGeneralesAsociados'
    ));
}public function update(Request $request, $id)
{
    try {
        $validatedData = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'idTipoDocumento' => 'required|integer|exists:tipodocumento,idTipoDocumento',
            'numero_documento' => 'required|string|max:20|unique:contactofinal,numero_documento,' . $id . ',idContactoFinal',
            'correo' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:15',
            'estado' => 'nullable|boolean',
            'idClienteGeneral' => 'nullable|array',
            'idClienteGeneral.*' => 'nullable|integer|exists:clientegeneral,idClienteGeneral',
        ]);

        $contactoFinal = ContactoFinal::findOrFail($id);
        $contactoFinal->update($validatedData);

        // 🔴 SINCRONIZAR CLIENTES GENERALES
        if ($request->has('idClienteGeneral') && is_array($request->idClienteGeneral)) {
            // Filtrar valores vacíos o nulos
            $clienteIds = array_filter($request->idClienteGeneral, function($value) {
                return !is_null($value) && $value !== '';
            });
            
            // Convertir a enteros
            $clienteIds = array_map('intval', $clienteIds);
            
            // Sincronizar los clientes generales
            $contactoFinal->clientesGenerales()->sync($clienteIds);
        } else {
            // Si no se envió el array o está vacío, eliminar todas las relaciones
            $contactoFinal->clientesGenerales()->detach();
        }

        // 🔴 Obtener clientes generales actualizados sin ambigüedad
        $clientesActualizados = DB::table('cliente_general_contacto_final')
            ->join('clientegeneral', 'cliente_general_contacto_final.idClienteGeneral', '=', 'clientegeneral.idClienteGeneral')
            ->where('cliente_general_contacto_final.idContactoFinal', $id)
            ->where('clientegeneral.estado', 1)
            ->select('clientegeneral.idClienteGeneral', 'clientegeneral.descripcion', 'clientegeneral.estado')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Contacto final y clientes generales actualizados correctamente',
            'data' => [
                'contacto' => $contactoFinal,
                'clientes_generales' => $clientesActualizados
            ],
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        Log::error('Error al actualizar contacto final: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el contacto final.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
}