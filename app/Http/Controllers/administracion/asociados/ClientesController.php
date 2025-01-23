<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use App\Models\Clientegeneral;
use App\Models\Tipodocumento;
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
            ]);

            // Establecer valores predeterminados
            $validatedData['estado'] = 1; // Valor predeterminado para 'estado'
            $validatedData['fecha_registro'] = now(); // Fecha actual para 'fecha_registro'

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






    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id); // Buscar cliente por ID

        $clientesGenerales = ClienteGeneral::all(); // Obtener todos los clientes generales
        $tiposDocumento = TipoDocumento::all(); // Obtener todos los tipos de documento

        // Obtener los datos de los archivos JSON
        $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
        $provincias = json_decode(file_get_contents(public_path('ubigeos/provincias.json')), true);
        $distritos = json_decode(file_get_contents(public_path('ubigeos/distritos.json')), true);

        // Buscar el departamento correspondiente a la cli$cliente
        $departamentoSeleccionado = array_filter($departamentos, function ($departamento) use ($cliente) {
            return $departamento['id_ubigeo'] == $cliente->departamento;
        });
        $departamentoSeleccionado = reset($departamentoSeleccionado);  // Obtener el primer valor del array filtrado

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

        // Definir distritoSeleccionado como null si no es necesario
        $distritoSeleccionado = null;  // Si no es necesario, puedes omitir esta línea también

        return view('administracion.asociados.clientes.edit', compact(
            'cliente',
            'clientesGenerales',
            'tiposDocumento',
            'departamentos',
            'provinciasDelDepartamento',
            'provinciaSeleccionada',
            'distritosDeLaProvincia',
            'distritoSeleccionado'
        ));
    }



    // Método para actualizar el cliente
    public function update(Request $request, $id)
    {
        try {
            // Log inicial: datos recibidos en la solicitud
            Log::info('Datos recibidos en la solicitud:', $request->all());

            // Validación de los datos
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'idTipoDocumento' => 'required|exists:tipodocumento,idTipoDocumento',
                'documento' => 'required|string|max:255',
                'telefono' => 'nullable|string|max:15',
                'email' => 'nullable|email|max:255',
                'departamento' => 'required|string|max:255', // Validar el campo 'departamento'
                'provincia' => 'required|string|max:255',    // Validar el campo 'provincia'
                'distrito' => 'required|string|max:255',     // Validar el campo 'distrito'
                'direccion' => 'required|string|max:255',
            ]);
            Log::info('Datos validados correctamente:', $validatedData);

            // Buscar el cliente
            $cliente = Cliente::find($id);

            if (!$cliente) {
                Log::error("Cliente con ID {$id} no encontrado.");
                return redirect()->route('administracion.clientes')->with('error', 'Cliente no encontrado.');
            }
            Log::info("Cliente encontrado con ID {$id}:", $cliente->toArray());

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
            ]);
            Log::info("Cliente con ID {$id} actualizado exitosamente.");

            // Redirigir con un mensaje de éxito
            return redirect()->route('administracion.clientes')->with('success', 'Cliente actualizado correctamente');
        } catch (\Exception $e) {
            // Log de error para capturar excepciones
            Log::error("Error al actualizar el cliente con ID {$id}: " . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
            ]);
            return redirect()->route('administracion.clientes')->with('error', 'Hubo un error al actualizar el cliente.');
        }
    }


    public function getAll()
    {
        // Obtener todos los clientes con sus relaciones (TipoDocumento y ClienteGeneral)
        $clientes = Cliente::with(['tipoDocumento'])->get();

        // Procesa los datos para incluir los campos necesarios, mostrando los nombres relacionados
        $clientesData = $clientes->map(function ($cliente) {
            return [
                'idCliente' => $cliente->idCliente,
                'idTipoDocumento' => $cliente->tipoDocumento->nombre, // Mostrar nombre del tipo de documento
                'documento'       => $cliente->documento,
                'nombre'          => $cliente->nombre,
                'telefono'        => $cliente->telefono,
                'email'           => $cliente->email,
                'direccion'       => $cliente->direccion,
                'estado'          => $cliente->estado == 1 ? 'Activo' : 'Inactivo',
            ];
        });

        // Retorna los datos en formato JSON
        return response()->json($clientesData);
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
        // Intentar encontrar al cliente
        $cliente = Cliente::find($id);

        // Verificar si el cliente existe
        if (!$cliente) {
            // Log para depuración
            Log::error("Cliente con ID {$id} no encontrado.");

            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        // Eliminar el cliente
        try {
            $cliente->delete();

            // Log para depuración
            Log::info("Cliente con ID {$id} eliminado con éxito.");

            return response()->json([
                'message' => 'Cliente eliminado con éxito'
            ], 200);
        } catch (\Exception $e) {
            // Log para errores durante la eliminación
            Log::error("Error al eliminar el cliente con ID {$id}: " . $e->getMessage());

            return response()->json(['error' => 'Error al eliminar el cliente'], 500);
        }
    }
}
