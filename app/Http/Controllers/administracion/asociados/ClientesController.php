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
    

    public function store(ClienteRequest $request)
    {
        try {
            // Datos del cliente, ya validados
            $dataClientes = $request->validated();
        
            // Establecer valores predeterminados para 'estado' y 'fecha_registro'
            $dataClientes['estado'] = 1; // Valor predeterminado para 'estado'
            $dataClientes['fecha_registro'] = now(); // Fecha actual para 'fecha_registro'
        
            // Verificar los datos validados con los valores predeterminados
            Log::debug('Datos validados recibidos:', $dataClientes);
        
            // Guardar el cliente
            $cliente = Cliente::create($dataClientes);
        
            // Verificar si el cliente se guardó correctamente
            Log::debug('Cliente insertado:', $cliente->toArray()); // Convertir el cliente a array
        
            // Responder con JSON
            return response()->json([
                'success' => true,
                'message' => 'Cliente agregado correctamente',
                'data' => $cliente,
            ]);
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
    $departamentoSeleccionado = array_filter($departamentos, function($departamento) use ($cliente) {
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

    return view('administracion.asociados.clientes.edit', compact('cliente', 'clientesGenerales', 'tiposDocumento','departamentos', 
        'provinciasDelDepartamento', 
        'provinciaSeleccionada', 
        'distritosDeLaProvincia', 
        'distritoSeleccionado' ));
}



    // Método para actualizar el cliente
    public function update(Request $request, $id)
    {
        // Validación de los datos
        $request->validate([
            'idClienteGeneral' => 'required|exists:clientegeneral,idClienteGeneral',
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

        // Buscar el cliente
        $cliente = Cliente::findOrFail($id);

        // Actualizar los campos del cliente
        $cliente->update([
            'idClienteGeneral' => $request->idClienteGeneral,
            'nombre' => $request->nombre,
            'idTipoDocumento' => $request->idTipoDocumento,
            'documento' => $request->documento,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'departamento' => $request->departamento,
            'provincia' => $request->provincia,
            'distrito' => $request->distrito,
            'direccion' => $request->direccion,
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('administracion.clientes')->with('success', 'Cliente actualizado correctamente');
    }

    public function getAll()
    {
        // Obtener todos los clientes con sus relaciones (TipoDocumento y ClienteGeneral)
        $clientes = Cliente::with(['tipoDocumento', 'clienteGeneral'])->get();
    
        // Procesa los datos para incluir los campos necesarios, mostrando los nombres relacionados
        $clientesData = $clientes->map(function ($cliente) {
            return [
                'idCliente' =>$cliente->idCliente,
                'idTipoDocumento' => $cliente->tipoDocumento->nombre, // Mostrar nombre del tipo de documento
                'documento'       => $cliente->documento,
                'nombre'          => $cliente->nombre,
                'telefono'        => $cliente->telefono,
                'email'           => $cliente->email,
                'clienteGeneral'  => $cliente->clienteGeneral->descripcion, // Mostrar descripción de cliente general
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
            // Cargar los clientes con sus relaciones necesarias (optimización con eager loading)
            $clientes = Cliente::with('tipoDocumento', 'clienteGeneral')->get();
    
            // Verificar si hay datos para exportar
            if ($clientes->isEmpty()) {
                return redirect()->back()->with('error', 'No hay clientes para generar el reporte.');
            }
    
            // Generar el PDF usando la vista
            $pdf = PDF::loadView('reporte.clientes', compact('clientes'))
                      ->setPaper('a4', 'landscape'); // Configuración de tamaño y orientación del PDF
    
            // Retornar el PDF para su descarga o visualización
            return $pdf->stream('reporte-clientes.pdf');
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error('Error al generar el PDF: ' . $e->getMessage());
    
            // Redirigir con un mensaje de error
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