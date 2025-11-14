<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use App\Models\Clientegeneral;
use App\Models\Tipodocumento;
use App\Models\Tienda;
use App\Models\Contactosform;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF;

class ClientesController extends Controller
{
    public function index()
    {
        $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
        $clientesGenerales = Clientegeneral::all();
        $tiposDocumento = Tipodocumento::all();

        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.clientes.index', compact('departamentos', 'clientesGenerales', 'tiposDocumento'));
    }


    public function store(Request $request)
    {
        try {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'idTipoDocumento' => 'required|integer|exists:tipodocumento,idTipoDocumento',
                'documento' => 'required|string|max:255|unique:cliente,documento',
                'telefono' => 'nullable|string|max:15',
                'email' => 'nullable|email|max:255',
                'departamento' => 'required|string|max:255',
                'provincia' => 'required|string|max:255',
                'distrito' => 'required|string|max:255',
                'direccion' => 'required|string|max:255',
                'idClienteGeneral' => 'required|array', // Asegurarse de que es un array
                'idClienteGeneral.*' => 'integer|exists:clientegeneral,idClienteGeneral', // Cada elemento debe ser válido
                'esTienda' => 'nullable|string', // Validar el campo esTienda
            ]);

            // Establecer valores predeterminados
            $validatedData['estado'] = 1; // Valor predeterminado para 'estado'
            $validatedData['fecha_registro'] = now(); // Fecha actual para 'fecha_registro'

            // Convertir el valor de esTienda a booleano
            $validatedData['esTienda'] = isset($validatedData['esTienda']) && $validatedData['esTienda'] == 1 ? true : false;

            // Extraer y eliminar 'idClienteGeneral' del array validado
            $idClienteGenerales = $validatedData['idClienteGeneral'];
            unset($validatedData['idClienteGeneral']);

            // Verificar los datos validados
            Log::debug('Datos validados recibidos:', $validatedData);

            // Crear el cliente en la base de datos
            $cliente = Cliente::create($validatedData);

            // Asociar los idClienteGeneral en la tabla pivote
            if (!empty($idClienteGenerales)) {
                $clienteGenerales = collect($idClienteGenerales)->map(function ($idClienteGeneral) use ($cliente) {
                    return [
                        'idCliente' => $cliente->idCliente,
                        'idClienteGeneral' => $idClienteGeneral,
                    ];
                });

                // Insertar los datos en la tabla pivote
                DB::table('cliente_clientegeneral')->insert($clienteGenerales->toArray());
            }

            // Verificar si el cliente y las relaciones se guardaron correctamente
            Log::debug('Cliente insertado:', $cliente->toArray());
            if (!empty($clienteGenerales)) {
                Log::debug('Relaciones cliente_clientegeneral insertadas:', $clienteGenerales->toArray());
            }

            // Responder con JSON
            return response()->json([
                'success' => true,
                'message' => 'Cliente agregado correctamente',
                'data' => $cliente,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Errores de validación:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al guardar el cliente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar el cliente.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function agregarClientesGenerales(Request $request, $idCliente)
    {
        $clientesGenerales = $request->input('clientesGenerales');

        // Eliminar todos los clientes generales actuales de la base de datos para este cliente
        DB::table('cliente_clientegeneral')->where('idCliente', $idCliente)->delete();

        // Insertar los nuevos clientes generales seleccionados
        foreach ($clientesGenerales as $idClienteGeneral) {
            DB::table('cliente_clientegeneral')->insert([
                'idCliente' => $idCliente,
                'idClienteGeneral' => $idClienteGeneral,
            ]);
        }

        return response()->json(['success' => true]);
    }






public function edit($id)
{
    $cliente = Cliente::with('contactos')->findOrFail($id); // 👈 Agregar with('contactos')
    
    $clientesGenerales = ClienteGeneral::all();
    $clientesGeneralesAsociados = ClienteGeneral::whereIn('idClienteGeneral', function ($query) use ($cliente) {
        $query->select('idClienteGeneral')
            ->from('cliente_clientegeneral')
            ->where('idCliente', $cliente->idCliente);
    })->get();

    $tiposDocumento = TipoDocumento::all();

    // ✅ AGREGAR: Obtener todos los contactos disponibles
    $todosLosContactos = Contactosform::all();

    // Obtener los datos de los archivos JSON
    $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
    $provincias = json_decode(file_get_contents(public_path('ubigeos/provincias.json')), true);
    $distritos = json_decode(file_get_contents(public_path('ubigeos/distritos.json')), true);

    // Buscar el departamento correspondiente a la cli$cliente
    $departamentoSeleccionado = array_filter($departamentos, function ($departamento) use ($cliente) {
        return $departamento['id_ubigeo'] == $cliente->departamento;
    });
    $departamentoSeleccionado = reset($departamentoSeleccionado);

    // Obtener provincias del departamento seleccionado
    $provinciasDelDepartamento = [];
    foreach ($provincias as $provincia) {
        if (isset($provincia['id_padre_ubigeo']) && $provincia['id_padre_ubigeo'] == $departamentoSeleccionado['id_ubigeo']) {
            $provinciasDelDepartamento[] = $provincia;
        }
    }

    // Buscar la provincia seleccionada en el array de provinciasDelDepartamento
    $provinciaSeleccionada = null;
    foreach ($provinciasDelDepartamento as $provincia) {
        if (isset($provincia['id_ubigeo']) && $provincia['id_ubigeo'] == $cliente->provincia) {
            $provinciaSeleccionada = $provincia;
            break;
        }
    }

    // Obtener los distritos correspondientes a la provincia seleccionada
    $distritosDeLaProvincia = [];
    foreach ($distritos as $distrito) {
        if (isset($distrito['id_padre_ubigeo']) && $distrito['id_padre_ubigeo'] == $provinciaSeleccionada['id_ubigeo']) {
            $distritosDeLaProvincia[] = $distrito;
        }
    }

    $distritoSeleccionado = null;

    return view('administracion.asociados.clientes.edit', compact(
        'cliente',
        'clientesGenerales',
        'tiposDocumento',
        'departamentos',
        'provinciasDelDepartamento',
        'provinciaSeleccionada',
        'distritosDeLaProvincia',
        'distritoSeleccionado',
        'clientesGeneralesAsociados',
        'todosLosContactos' // 👈 AGREGAR esta variable
    ));
}

    public function clientesGeneralesAsociados($idCliente)
    {
        $clientesGeneralesAsociados = ClienteGeneral::whereIn('idClienteGeneral', function ($query) use ($idCliente) {
            $query->select('idClienteGeneral')
                ->from('cliente_clientegeneral')
                ->where('idCliente', $idCliente); // Asociado al cliente específico
        })->get();

        return response()->json($clientesGeneralesAsociados);
    }

    public function obtenerClientesGeneralesAsociados($idCliente)
    {
        // Obtener los clientes generales asociados
        $clientesGenerales = DB::table('cliente_clientegeneral')
            ->join('clientes_generales', 'cliente_clientegeneral.idClienteGeneral', '=', 'clientes_generales.idClienteGeneral')
            ->where('cliente_clientegeneral.idCliente', $idCliente)
            ->select('clientes_generales.idClienteGeneral', 'clientes_generales.descripcion')
            ->get();

        return response()->json($clientesGenerales);
    }
    public function obtenerCliente($idCliente)
    {
        $cliente = Cliente::find($idCliente);
    
        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }
    
        return response()->json([
            'idCliente' => $cliente->idCliente,
            'nombre' => $cliente->nombre,
            'documento' => $cliente->documento,
            'direccion' => $cliente->direccion, // 👈 AÑADIDO
            'idTipoDocumento' => $cliente->idTipoDocumento, // Asegúrate de devolver el idTipoDocumento
            'esTienda' => $cliente->esTienda == 1 ? "SI" : "NO", // Convertimos el 1 en "SI" y 0 en "NO"
        ]);
    }
    

    /**
     * Obtener las tiendas asociadas a un cliente si es tienda
     */
    public function obtenerTiendas($idCliente)
{
    $cliente = Cliente::find($idCliente);

    if (!$cliente) {
        return response()->json(['error' => 'Cliente no encontrado'], 404);
    }

    // Si el cliente tiene idTipoDocumento 2, traer todas las tiendas
    if ($cliente->idTipoDocumento == 8) {
        // Obtener todas las tiendas
        $tiendas = Tienda::all();
    } else {
        // Si el cliente tiene idTipoDocumento 1, traer solo las tiendas relacionadas
        $tiendas = Tienda::where('idCliente', $idCliente)->get();
    }

    return response()->json($tiendas);
}


    // Método para agregar cliente general
    public function agregarClienteGeneral($idCliente, $idClienteGeneral)
    {
        // Verificar si la relación ya existe
        $exists = DB::table('cliente_clientegeneral')
            ->where('idCliente', $idCliente)
            ->where('idClienteGeneral', $idClienteGeneral)
            ->exists();

        if (!$exists) {
            DB::table('cliente_clientegeneral')->insert([
                'idCliente' => $idCliente,
                'idClienteGeneral' => $idClienteGeneral
            ]);
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'La relación ya existe.']);
        }
    }






    public function eliminarClienteGeneral($idCliente, $idClienteGeneral)
    {
        // Log para verificar los valores de los parámetros
        Log::debug('Eliminando cliente general', [
            'idCliente' => $idCliente,
            'idClienteGeneral' => $idClienteGeneral
        ]);

        // Verificar si existe la relación antes de eliminarla
        $relacionExistente = DB::table('cliente_clientegeneral')
            ->where('idCliente', $idCliente)
            ->where('idClienteGeneral', $idClienteGeneral)
            ->exists();

        if (!$relacionExistente) {
            Log::warning('No se encontró la relación entre cliente y cliente general', [
                'idCliente' => $idCliente,
                'idClienteGeneral' => $idClienteGeneral
            ]);
            return response()->json(['success' => false, 'message' => 'No se encontró la relación']);
        }

        // Eliminar la relación en la tabla cliente_clientegeneral
        $deleted = DB::table('cliente_clientegeneral')
            ->where('idCliente', $idCliente)
            ->where('idClienteGeneral', $idClienteGeneral)
            ->delete();

        // Verificar si la eliminación fue exitosa
        if ($deleted) {
            Log::debug('Cliente general eliminado con éxito', [
                'idCliente' => $idCliente,
                'idClienteGeneral' => $idClienteGeneral
            ]);
            return response()->json(['success' => true]);
        } else {
            Log::error('No se pudo eliminar el cliente general', [
                'idCliente' => $idCliente,
                'idClienteGeneral' => $idClienteGeneral
            ]);
            return response()->json(['success' => false, 'message' => 'Error al eliminar la relación']);
        }
    }



public function update(Request $request, $id)
{
    try {
        Log::info('Datos recibidos en la solicitud:', $request->all());

        // Validación de los datos
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'idTipoDocumento' => 'required|exists:tipodocumento,idTipoDocumento',
            'documento' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'departamento' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'distrito' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'esTienda' => 'nullable|boolean',
            'estado' => 'nullable|boolean',
            'contactos_sync' => 'nullable|string' // 👈 AGREGAR validación para contactos
        ]);

        Log::info('Datos validados correctamente:', $validatedData);

        // Buscar el cliente
        $cliente = Cliente::find($id);

        if (!$cliente) {
            Log::error("Cliente con ID {$id} no encontrado.");
            return redirect()->route('administracion.clientes')->with('error', 'Cliente no encontrado.');
        }

        Log::info("Cliente encontrado con ID {$id}:", $cliente->toArray());

        // Determinar el valor de 'esTienda' y 'estado'
        $esTienda = $request->has('esTienda') && $request->esTienda == '1' ? '1' : '0';
        $estado = $request->has('estado') && $request->estado == '1' ? '1' : '0';

        // Actualizar los campos del cliente
        $cliente->update([
            'nombre' => $validatedData['nombre'],
            'idTipoDocumento' => $validatedData['idTipoDocumento'],
            'documento' => $validatedData['documento'],
            'telefono' => $validatedData['telefono'],
            'email' => $validatedData['email'],
            'departamento' => $validatedData['departamento'],
            'provincia' => $validatedData['provincia'],
            'distrito' => $validatedData['distrito'],
            'direccion' => $validatedData['direccion'],
            'esTienda' => $esTienda,
            'estado' => $estado,
        ]);

        // ✅ AGREGAR: Sincronizar contactos
        if ($request->has('contactos_sync') && !empty($request->contactos_sync)) {
            $contactosIds = explode(',', $request->contactos_sync);
            $cliente->contactos()->sync($contactosIds);
            Log::info("Contactos sincronizados para cliente {$id}: " . implode(', ', $contactosIds));
        } else {
            $cliente->contactos()->detach();
            Log::info("Todos los contactos desvinculados del cliente {$id}");
        }

        Log::info("Cliente con ID {$id} actualizado exitosamente.");

        return redirect()->route('administracion.clientes')->with('success', 'Cliente actualizado correctamente');
        
    } catch (\Exception $e) {
        Log::error("Error al actualizar el cliente con ID {$id}: " . $e->getMessage(), [
            'stack' => $e->getTraceAsString(),
        ]);
        return redirect()->route('administracion.clientes')->with('error', 'Hubo un error al actualizar el cliente.');
    }
}


    public function getAll(Request $request)
    {
        $query = Cliente::with('tipoDocumento');
    
        $total = Cliente::count(); // Total sin filtros
    
        // Buscador general
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                  ->orWhere('documento', 'like', "%$search%")
                  ->orWhere('telefono', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
    
        $filtered = $query->count(); // Total después de aplicar filtros
    
        $clientes = $query
            ->skip($request->start)
            ->take($request->length)
            ->get();
    
        $data = $clientes->map(function ($cliente) {
            return [
                'idCliente' => $cliente->idCliente,
                'idTipoDocumento' => $cliente->tipoDocumento->nombre,
                'documento' => $cliente->documento,
                'nombre' => $cliente->nombre,
                'telefono' => $cliente->telefono,
                'email' => $cliente->email,
                'direccion' => $cliente->direccion,
                'estado' => $cliente->estado == 1 ? 'Activo' : 'Inactivo',
            ];
        });
    
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }
    
    

    public function exportAllPDF()
    {
        try {
            $clientes = Cliente::with('tipoDocumento', 'clienteGeneral')->get();

            if ($clientes->isEmpty()) {
                return redirect()->back()->with('error', 'No hay clientes para generar el reporte.');
            }

            // Asegúrate de que la ruta de la vista es correcta
            $pdf = PDF::loadView('administracion.asociados.clientes.pdf.clientes', compact('clientes'))
                ->setPaper('a4', 'landscape');

            return $pdf->download('reporte-clientes.pdf');
        } catch (\Exception $e) {
            Log::error('Error al generar el PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al generar el reporte.');
        }
    }


    public function destroy($id)
    {
        $cliente = Cliente::find($id);
    
        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }
    
        // 🔴 Verificar si tiene relaciones activas
        $relaciones = DB::table('cliente_clientegeneral')->where('idCliente', $id)->exists();
    
        if ($relaciones) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar este cliente porque está asociado a uno o más clientes generales.'
            ], 400); // Código HTTP 400: mala solicitud
        }
    
        try {
            $cliente->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Cliente eliminado con éxito.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el cliente.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}
