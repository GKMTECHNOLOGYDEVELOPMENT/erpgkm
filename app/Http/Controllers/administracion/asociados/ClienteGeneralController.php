<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralRequests;
use App\Models\Clientegeneral;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ClienteGeneralController extends Controller
{
    public function index()
    {
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.clienteGeneral.index');
    }

    // public function store(GeneralRequests $request)
    // {
    //     try {
    //         // Datos básicos del cliente
    //         $dataClientes = [
    //             'descripcion' => $request->descripcion,
    //             'estado' => 1,
    //         ];

    //         // Guardar el cliente en la base de datos (sin la imagen por ahora)
    //         Log::info('Insertando cliente:', $dataClientes);
    //         $cliente = Clientegeneral::create($dataClientes);

    //         // Procesar la imagen si se ha subido
    //         if ($request->hasFile('logo')) {
    //             // Obtener el contenido binario de la imagen
    //             $binaryImage = file_get_contents($request->file('logo')->getRealPath());

    //             // Actualizar el cliente con la imagen en formato binario
    //             DB::table('clientegeneral')
    //                 ->where('idClienteGeneral', $cliente->idClienteGeneral)
    //                 ->update(['foto' => $binaryImage]);

    //             Log::info('Imagen guardada como longblob en la base de datos.');
    //         }

    //         // Responder con JSON
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Cliente agregado correctamente',
    //             'data' => $cliente,
    //         ]);
    //     } catch (\Exception $e) {
    //         // Log para capturar el error
    //         Log::error('Error al guardar el cliente: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Ocurrió un error al guardar el cliente.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }


    public function store(GeneralRequests $request)
{
    try {
        // Datos básicos del cliente
        $dataClientes = [
            'descripcion' => $request->descripcion,
            'estado' => 1,
        ];

        // Guardar el cliente en la base de datos (sin la imagen por ahora)
        Log::info('Insertando cliente:', $dataClientes);
        $cliente = Clientegeneral::create($dataClientes);

        // Procesar la imagen si se ha subido
        if ($request->hasFile('logo')) {
            // Obtener el contenido binario de la imagen
            $binaryImage = file_get_contents($request->file('logo')->getRealPath());

            // Actualizar el cliente con la imagen en formato binario
            DB::table('clientegeneral')
                ->where('idClienteGeneral', $cliente->idClienteGeneral)
                ->update(['foto' => $binaryImage]);

            Log::info('Imagen guardada como longblob en la base de datos.');
        }

        // Responder con JSON, incluyendo el idClienteGeneral
        return response()->json([
            'success' => true,
            'message' => 'Cliente agregado correctamente',
            'id' => $cliente->idClienteGeneral,  // Incluye el id del cliente recién creado
            'data' => $cliente,
        ]);
    } catch (\Exception $e) {
        // Log para capturar el error
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
    $cliente = ClienteGeneral::findOrFail($id); // Buscar cliente por ID
    $marcas = Marca::all(); // Traer todas las marcas

    // Si hay una imagen, convertirla a base64
    if ($cliente->foto) {
        $cliente->foto = 'data:image/jpeg;base64,' . base64_encode($cliente->foto);
    }

    $marcasAsociadas = Marca::whereIn('idMarca', function ($query) use ($cliente) {
        $query->select('idMarca')
              ->from('marca_clientegeneral')
              ->where('idClienteGeneral', $cliente->idClienteGeneral); // Asociado al cliente actual
    })->get(); // Marcas asociadas al cliente general
    

    // Retornar vista con los datos del cliente y las marcas
    return view('administracion.asociados.clienteGeneral.edit', compact('cliente', 'marcas','marcasAsociadas'));
}






// Método para agregar una marca a un cliente general
public function agregarMarcaClienteGeneral($idClienteGeneral, $idMarca)
{
    // Verificar si la relación ya existe
    $exists = DB::table('marca_clientegeneral')
        ->where('idClienteGeneral', $idClienteGeneral)
        ->where('idMarca', $idMarca)
        ->exists();

    if (!$exists) {
        // Si no existe la relación, insertarla en la tabla intermedia
        DB::table('marca_clientegeneral')->insert([
            'idClienteGeneral' => $idClienteGeneral,
            'idMarca' => $idMarca
        ]);
        return response()->json(['success' => true]);
    } else {
        return response()->json(['success' => false, 'message' => 'La relación ya existe.']);
    }
}

// Método para eliminar una marca de un cliente general
public function eliminarMarcaClienteGeneral($idClienteGeneral, $idMarca)
{
    // Verificar si existe la relación antes de eliminarla
    $relacionExistente = DB::table('marca_clientegeneral')
        ->where('idClienteGeneral', $idClienteGeneral)
        ->where('idMarca', $idMarca)
        ->exists();

    if (!$relacionExistente) {
        return response()->json(['success' => false, 'message' => 'No se encontró la relación']);
    }

    // Eliminar la relación de la tabla marca_clientegeneral
    $deleted = DB::table('marca_clientegeneral')
        ->where('idClienteGeneral', $idClienteGeneral)
        ->where('idMarca', $idMarca)
        ->delete();

    if ($deleted) {
        return response()->json(['success' => true]);
    } else {
        return response()->json(['success' => false, 'message' => 'Error al eliminar la relación']);
    }
}


public function marcasAsociadas($idClienteGeneral)
{
    // Obtener las marcas asociadas al cliente general a través de la tabla intermedia
    $marcasAsociadas = Marca::whereIn('idMarca', function ($query) use ($idClienteGeneral) {
        $query->select('idMarca')
              ->from('marca_clientegeneral')
              ->where('idClienteGeneral', $idClienteGeneral); // Asociado al cliente general específico
    })->get();

    // Retornar las marcas en formato JSON
    return response()->json($marcasAsociadas);
}

    


    public function update(Request $request, $id)
{
    // Validar los datos del formulario
    $validatedData = $request->validate([
        'descripcion' => 'required|string|max:255',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'estado' => 'nullable|boolean',
    ]);

    // Obtener el cliente
    $cliente = Clientegeneral::findOrFail($id);

    // Log para verificar si el cliente se obtiene correctamente
    Log::info("Actualizando cliente con ID: $id");

    // Actualizar los datos básicos del cliente
    $cliente->descripcion = $validatedData['descripcion'];
    $cliente->estado = $request->estado;

    // Log para verificar la foto antes de proceder
    Log::info("Ruta almacenada en la base de datos para la foto del cliente $id: " . $cliente->foto);

    // Manejar la actualización de la imagen
    if ($request->hasFile('foto')) {
        // Log para saber que se está subiendo una nueva imagen
        Log::info("Se ha recibido una nueva imagen para el cliente $id.");

        // Eliminar la imagen anterior si existe
        if ($cliente->foto) {
            // Verificar si la foto anterior existe antes de eliminarla
            $fotoPath = str_replace('storage/', '', $cliente->foto); // Quitar el prefijo 'storage/'

            // Generar la ruta completa para eliminar el archivo físico
            $fotoPathCompleta = storage_path('app/public/' . $fotoPath); // Ahora generamos la ruta completa para el archivo

            // Log para verificar la ruta completa generada
            Log::info("Ruta completa para eliminar la imagen anterior: $fotoPathCompleta");

            // Verificar si el archivo existe en la ruta completa
            if (file_exists($fotoPathCompleta)) {
                // Eliminar la imagen anterior
                unlink($fotoPathCompleta);
                Log::info("Imagen eliminada exitosamente: $fotoPathCompleta");
            } else {
                Log::warning("La imagen anterior no fue encontrada para eliminar: $fotoPathCompleta");
            }
        }

        // Leer el contenido de la nueva imagen como binario
        $binaryImage = file_get_contents($request->file('foto')->getRealPath());

        // Log para verificar que la imagen fue leída correctamente
        Log::info("Imagen leída correctamente para el cliente $id.");

        // Actualizar la base de datos con la nueva imagen en formato binario
        $cliente->foto = $binaryImage;
    }

    // Guardar los cambios en la base de datos
    $cliente->save();

    // Log para confirmar que los datos han sido actualizados correctamente
    Log::info("Cliente actualizado correctamente con ID: $id");

    // Redireccionar con un mensaje de éxito
    return redirect()->route('administracion.cliente-general')
        ->with('success', 'Cliente actualizado exitosamente.');
}








    public function destroy($id)
    {
        $cliente = ClienteGeneral::find($id);

        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        $fotoEliminada = false; // Variable para saber si la foto fue eliminada
        $carpetaEliminada = false; // Variable para saber si la carpeta fue eliminada

        // Verificar si el cliente tiene una imagen y eliminarla
        if ($cliente->foto) {
            // Eliminar el prefijo 'storage/' de la ruta almacenada en la base de datos
            $fotoPath = str_replace('storage/', '', $cliente->foto);  // Eliminar 'storage/' si está presente

            // Crear la ruta completa para la foto utilizando el storage_path
            $fotoPathCompleta = storage_path('app/public/' . $fotoPath);

            // Log para ver la ruta de la imagen
            Log::info("Ruta completa de la foto del cliente $id: " . $fotoPathCompleta);

            // Verificar si el archivo existe
            if (file_exists($fotoPathCompleta)) {
                // Eliminar la imagen
                unlink($fotoPathCompleta); // Usamos unlink para eliminar archivos en el sistema de archivos
                Log::info("Imagen del cliente $id eliminada: " . $fotoPathCompleta);
                $fotoEliminada = true; // Si se eliminó la foto, cambiamos la variable
            } else {
                Log::warning("La imagen no fue encontrada en la ruta: " . $fotoPathCompleta);
            }

            // Eliminar la carpeta que contiene la imagen (si está vacía)
            $directorio = dirname($fotoPathCompleta); // Obtener el directorio de la foto
            if (is_dir($directorio) && count(scandir($directorio)) == 2) { // Verificamos si la carpeta está vacía
                rmdir($directorio); // Eliminar la carpeta vacía
                Log::info("Carpeta vacía eliminada: " . $directorio);
                $carpetaEliminada = true; // Si la carpeta fue eliminada, cambiamos la variable
            }
        }

        // Eliminar el cliente
        $cliente->delete();

        // Responder con el estado de la eliminación
        return response()->json([
            'message' => 'Cliente y su imagen eliminados con éxito',
            'fotoEliminada' => $fotoEliminada, // Indicamos si la foto fue eliminada
            'carpetaEliminada' => $carpetaEliminada // Indicamos si la carpeta fue eliminada
        ], 200);
    }

    public function exportAllPDF()
    {
        // Obtener todos los registros de ClienteGeneral
        $clientes = ClienteGeneral::all();

        // Convertir las imágenes a base64
        foreach ($clientes as $cliente) {
            if ($cliente->foto) {
                // Convertir la imagen binaria en base64
                $cliente->foto_base64 = base64_encode($cliente->foto);
            }
        }

        // Generar el PDF con la colección completa
        $pdf = Pdf::loadView('administracion.asociados.clienteGeneral.pdf.cliente-general', compact('clientes'))
            ->setPaper('a4', 'portrait'); // Define orientación vertical

        // Retornar el PDF como descarga
        return $pdf->download('reporte-cliente-generales.pdf');
    }

    public function getAll()
    {
        // Obtén todos los datos de la tabla clientegeneral
        $clientes = Clientegeneral::all();

        // Procesa los datos e incluye la columna 'foto'
        $clientesData = $clientes->map(function ($cliente) {
            return [
                'idClienteGeneral' => $cliente->idClienteGeneral,
                'descripcion' => $cliente->descripcion,
                'estado' => $cliente->estado ? 'Activo' : 'Inactivo', // Convertir estado a texto
                'foto' => $cliente->foto ? base64_encode($cliente->foto) : null, // Codificar la foto en base64
            ];
        });

        // Retorna los datos en formato JSON
        return response()->json($clientesData);
    }
    // futuras validaciones
    public function checkNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $exists = Clientegeneral::where('descripcion', $nombre)->exists();

        return response()->json(['unique' => !$exists]);
    }




    public function guardarClienteSmart(GeneralRequests $request)
    {
        try {
            // Datos básicos del cliente
            $dataClientes = [
                'descripcion' => $request->descripcion,
                'estado' => 1,
            ];

            // Guardar el cliente en la base de datos (sin la imagen por ahora)
            Log::info('Insertando cliente:', $dataClientes);
            $cliente = Clientegeneral::create($dataClientes);

            // Procesar la imagen si se ha subido
            if ($request->hasFile('logo')) {
                // Obtener el contenido binario de la imagen
                $binaryImage = file_get_contents($request->file('logo')->getRealPath());

                // Actualizar el cliente con la imagen en formato binario
                DB::table('clientegeneral')
                    ->where('idClienteGeneral', $cliente->idClienteGeneral)
                    ->update(['foto' => $binaryImage]);

                Log::info('Imagen guardada como longblob en la base de datos.');
            }

            // Responder con JSON
            return response()->json([
                'success' => true,
                'message' => 'Cliente agregado correctamente',
                'data' => $cliente,
            ]);
        } catch (\Exception $e) {
            // Log para capturar el error
            Log::error('Error al guardar el cliente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar el cliente.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


 // En tu controlador
public function obtenerClientesGenerales()
{
    // Obtener los clientes generales
    $clientesGenerales = DB::table('clientegeneral')
                            ->where('estado', 1) // Asegúrate de filtrar si es necesario
                            ->get(['idClienteGeneral', 'descripcion']); // Solo seleccionamos los campos que necesitamos

    // Retornar los datos en formato JSON
    return response()->json($clientesGenerales);
}





}
