<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProveedoresRequest;
use App\Models\Area;
use App\Models\TipoArea;
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
        $tiposArea  = TipoArea::all();
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.proveedores.index', compact('departamentos', 'tiposDocumento', 'tiposArea'));
    }

    public function store(Request $request) // Cambiar ProveedoresRequest por Request
    {
        try {
            // Obtener todos los datos enviados en la solicitud sin validarlos
            $dataProveedores = $request->all();

            // Establecer valores predeterminados para 'estado' y 'fecha_registro'
            $dataProveedores['estado'] = 1; // Valor predeterminado para 'estado' (activo)

            // Verificar los datos recibidos
            Log::debug('Datos recibidos sin validar:', $dataProveedores);

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


    public function edit($id)
    {
        $proveedor = Proveedore::findOrFail($id); // Buscar cliente por ID

        $areas = Area::all(); // Obtener todos los clientes generales
        $tiposDocumento = TipoDocumento::all(); // Obtener todos los tipos de documento

        // Obtener los datos de los archivos JSON
        $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
        $provincias = json_decode(file_get_contents(public_path('ubigeos/provincias.json')), true);
        $distritos = json_decode(file_get_contents(public_path('ubigeos/distritos.json')), true);

        // Buscar el departamento correspondiente a la cli$cliente
        $departamentoSeleccionado = array_filter($departamentos, function ($departamento) use ($proveedor) {
            return $departamento['id_ubigeo'] == $proveedor->departamento;
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
            if (isset($provincia['id_ubigeo']) && $provincia['id_ubigeo'] == $proveedor->provincia) {
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

        return view('administracion.asociados.proveedores.edit', compact(
            'proveedor',
            'tiposDocumento',
            'departamentos',
            'areas',
            'provinciasDelDepartamento',
            'provinciaSeleccionada',
            'distritosDeLaProvincia',
            'distritoSeleccionado'
        ));
    }


    // Método para actualizar el proveedor
    public function update(Request $request, $id)
    {
        // Validación de los datos
        $request->validate([
            'idTipoDocumento' => 'required|exists:tipodocumento,idTipoDocumento',
            'nombre' => 'required|string|max:255',
            'numeroDocumento' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'departamento' => 'required|string|max:255', // Validar el campo 'departamento'
            'provincia' => 'required|string|max:255',    // Validar el campo 'provincia'
            'distrito' => 'required|string|max:255',     // Validar el campo 'distrito'
            'direccion' => 'required|string|max:255',
            'codigoPostal' => 'nullable|string|max:10',  // Validar el código postal
            'idArea' => 'required|exists:area,idArea',    // Validar el área
        ]);

        // Buscar el proveedor
        $proveedor = Proveedore::findOrFail($id);

        // Actualizar los campos del proveedor
        $proveedor->update([
            'idTipoDocumento' => $request->idTipoDocumento,
            'nombre' => $request->nombre,
            'numeroDocumento' => $request->numeroDocumento,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'departamento' => $request->departamento,
            'provincia' => $request->provincia,
            'distrito' => $request->distrito,
            'direccion' => $request->direccion,
            'codigoPostal' => $request->codigoPostal,
            'idArea' => $request->idArea,
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('administracion.proveedores')->with('success', 'Proveedor actualizado correctamente');
    }





    public function getAll()
    {
        // Obtener todos los proveedores con sus relaciones (TipoDocumento y Tipoarea)
        $proveedores = Proveedore::with(['tipoDocumento', 'area'])->get(); // Cambia 'tipoarea' por 'area' si tu relación se llama 'area'

        // Registrar los datos obtenidos (para depuración)
        Log::debug('Proveedores obtenidos:', $proveedores->toArray()); // Utiliza toArray() para registrar los datos completos

        // Procesa los datos para incluir los campos necesarios, mostrando los nombres relacionados
        $proveedoresData = $proveedores->map(function ($proveedor) {
            return [
                'idProveedor'      => $proveedor->idProveedor,
                'nombre'           => $proveedor->nombre,
                'estado'           => $proveedor->estado == 1 ? 'Activo' : 'Inactivo',
                'departamento'     => $proveedor->departamento,
                'provincia'        => $proveedor->provincia,
                'distrito'         => $proveedor->distrito,
                'direccion'        => $proveedor->direccion,
                'codigoPostal'     => $proveedor->codigoPostal,
                'telefono'         => $proveedor->telefono,
                'email'            => $proveedor->email,
                'numeroDocumento'  => $proveedor->numeroDocumento,
                'idArea'           => $proveedor->area->nombre ?? '', // Mostrar nombre del área (relación 'area')
                'idTipoDocumento'  => $proveedor->tipoDocumento->nombre ?? '', // Mostrar nombre del tipo de documento
            ];
        });

        // Retorna los datos en formato JSON
        return response()->json($proveedoresData);
    }

    public function exportAllPDF()
    {
        $proveedores = Proveedore::with('tipoDocumento', 'area')->get(); // Ahora la relación funcionará
    
        $pdf = PDF::loadView('proveedores.report', compact('proveedores'));
        return $pdf->download('reporte_proveedores.pdf');
    }
    
    public function destroy($id)
    {
        // Intentar encontrar al proveedor
        $proveedor = Proveedore::find($id);

        // Verificar si el proveedor existe
        if (!$proveedor) {
            // Log para depuración
            Log::error("Proveedor con ID {$id} no encontrado.");

            return response()->json(['error' => 'Proveedor no encontrado'], 404);
        }

        // Eliminar el proveedor
        try {
            $proveedor->delete();

            // Log para depuración
            Log::info("Proveedor con ID {$id} eliminado con éxito.");

            return response()->json([
                'message' => 'Proveedor eliminado con éxito'
            ], 200);
        } catch (\Exception $e) {
            // Log para errores durante la eliminación
            Log::error("Error al eliminar el proveedor con ID {$id}: " . $e->getMessage());

            return response()->json(['error' => 'Error al eliminar el proveedor'], 500);
        }
    }
}
