<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Http\Requests\CastRequest;
use App\Http\Requests\GeneralRequests;
use App\Models\Cast;
use App\Models\Clientegeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;

class CastController extends Controller
{
    public function index()
    {
        $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.cast.index', compact('departamentos'));
    }

    // Método para editar un cast
    public function edit($id)
    {
        $cast = Cast::findOrFail($id); // Buscar cast por ID



        // Obtener los datos de los archivos JSON
        $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
        $provincias = json_decode(file_get_contents(public_path('ubigeos/provincias.json')), true);
        $distritos = json_decode(file_get_contents(public_path('ubigeos/distritos.json')), true);

        // Buscar el departamento correspondiente al cast
        $departamentoSeleccionado = array_filter($departamentos, function ($departamento) use ($cast) {
            return $departamento['id_ubigeo'] == $cast->departamento;
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
            if (isset($provincia['id_ubigeo']) && $provincia['id_ubigeo'] == $cast->provincia) {
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

        return view('administracion.asociados.cast.edit', compact(
            'cast',
            'departamentos',
            'provinciasDelDepartamento',
            'provinciaSeleccionada',
            'distritosDeLaProvincia',
            'distritoSeleccionado'
        ));
    }

    public function destroy($id)
    {
        // Intentar encontrar al cast
        $cast = Cast::find($id);

        // Verificar si el cast existe
        if (!$cast) {
            // Log para depuración
            Log::error("Cast con ID {$id} no encontrado.");

            return response()->json(['error' => 'Cast no encontrado'], 404);
        }

        // Eliminar el cast
        try {
            $cast->delete();

            // Log para depuración
            Log::info("Cast con ID {$id} eliminado con éxito.");

            return response()->json([
                'message' => 'Cast eliminado con éxito'
            ], 200);
        } catch (\Exception $e) {
            // Log para errores durante la eliminación
            Log::error("Error al eliminar el cast con ID {$id}: " . $e->getMessage());

            return response()->json(['error' => 'Error al eliminar el cast'], 500);
        }
    }



    // Método para actualizar el cast
    public function update(Request $request, $id)
    {
        // Validación de los datos
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'numeroDocumento' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'departamento' => 'required|string|max:255', // Validar el campo 'departamento'
            'provincia' => 'required|string|max:255',    // Validar el campo 'provincia'
            'distrito' => 'required|string|max:255',     // Validar el campo 'distrito'
            'direccion' => 'required|string|max:255',
            'estado' => 'required|boolean', // Validar el campo 'estado'
        ]);

        // Buscar el cast
        $cast = Cast::findOrFail($id);

        // Actualizar los campos del cast
        $cast->update([
            'nombre' => $validatedData['nombre'],
            'numeroDocumento' => $validatedData['numeroDocumento'],
            'telefono' => $validatedData['telefono'],
            'email' => $validatedData['email'],
            'departamento' => $validatedData['departamento'],
            'provincia' => $validatedData['provincia'],
            'distrito' => $validatedData['distrito'],
            'direccion' => $validatedData['direccion'],
            'estado' => $validatedData['estado'], // Actualizar el estado
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('administracion.cast')->with('success', 'Cast actualizado correctamente');
    }



    public function store(CastRequest $request)
    {
        try {
            // Datos validados del request
            $dataCast = $request->validated();
    
            // Establecer valores predeterminados para 'estado'
            $dataCast['estado'] = 1; // Asegura que el estado siempre sea 1 (activo)
    
            // Verificar los datos validados con los valores predeterminados
            Log::debug('Datos validados recibidos con estado predeterminado:', $dataCast);
    
            // Crear el registro en la base de datos
            $cast = Cast::create($dataCast);
    
            // Verificar si el cast se guardó correctamente
            Log::debug('Cast insertado:', $cast->toArray());
    
            // Responder con JSON
            return response()->json([
                'success' => true,
                'message' => 'Cast agregado correctamente',
                'data' => $cast,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al guardar el cast: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar el cast.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function exportAllPDF()
    {
        $casts = Cast::all(); // Obtén todos los registros de CAST

        try {
            $pdf = PDF::loadView('administracion.asociados.cast.pdf.reporte-cast', compact('casts'))
                ->setPaper('a4', 'landscape'); // Configura el tamaño y orientación del PDF

            return $pdf->download('reporte-cast.pdf'); // Devuelve el PDF para visualizarlo en el navegador
        } catch (\Exception $e) {
            Log::error('Error al generar el PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al generar el PDF.');
        }
    }

    public function getAll()
    {
        // Cargar los datos de departamentos, provincias y distritos desde los JSON
        $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
        $provincias = json_decode(file_get_contents(public_path('ubigeos/provincias.json')), true);
        $distritos = json_decode(file_get_contents(public_path('ubigeos/distritos.json')), true);

        // Obtener todos los registros de la tabla 'cast'
        $casts = Cast::all();

        // Procesar los datos para incluir nombres de departamento, provincia y distrito
        $castsData = $casts->map(function ($cast) use ($departamentos, $provincias, $distritos) {
            $departamentoNombre = collect($departamentos)->firstWhere('id_ubigeo', $cast->departamento)['nombre_ubigeo'] ?? 'N/A';

            $provinciaNombre = isset($provincias[$cast->departamento])
                ? collect($provincias[$cast->departamento])->firstWhere('id_ubigeo', $cast->provincia)['nombre_ubigeo'] ?? 'N/A'
                : 'N/A';

            $distritoNombre = isset($distritos[$cast->provincia])
                ? collect($distritos[$cast->provincia])->firstWhere('id_ubigeo', $cast->distrito)['nombre_ubigeo'] ?? 'N/A'
                : 'N/A';

            return [
                'idCast'       => $cast->idCast,
                'nombre'       => $cast->nombre,
                'ruc'          => $cast->ruc,
                'telefono'     => $cast->telefono,
                'email'        => $cast->email,
                'direccion'    => $cast->direccion,
                'departamento' => $departamentoNombre,
                'provincia'    => $provinciaNombre,
                'distrito'     => $distritoNombre,
                'estado'       => $cast->estado == 1 ? 'Activo' : 'Inactivo',
            ];
        });

        // Retorna los datos en formato JSON
        return response()->json($castsData);
    }
}
