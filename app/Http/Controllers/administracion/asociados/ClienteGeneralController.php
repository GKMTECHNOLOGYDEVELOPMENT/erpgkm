<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralRequests;
use App\Models\Clientegeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ClienteGeneralController extends Controller
{
    public function index()
    {
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.clienteGeneral.index'); 
    }

    public function store(GeneralRequests $request)
    {
        try {
            // Datos básicos del cliente sin la imagen
            $dataClientes = [
                'descripcion' => $request->descripcion,
                'estado' => 1,
            ];
    
            // Guardar el cliente en la base de datos (sin la imagen por ahora)
            Log::info('Insertando cliente:', $dataClientes);
            Clientegeneral::insert($dataClientes);
    
            // Obtener el último cliente insertado (para obtener su ID)
            $data = Clientegeneral::latest('idClienteGeneral')->first();
            $idCliente = $data->idClienteGeneral;
            Log::info('Cliente insertado con ID: ' . $idCliente);
    
            // Procesar la imagen si se ha subido
            if ($request->hasFile('logo')) {
                // Obtener la extensión del archivo
                $extension = $request->file('logo')->getClientOriginalExtension();
                Log::info('Extensión de la imagen: ' . $extension);
    
                // Generar un nombre de archivo único para evitar sobrescribir archivos
                $file_name = mt_rand(0, 999) . '.' . $extension;
    
                // Definir la carpeta de destino dentro de storage/app/public
                $directorio = "img/general/{$idCliente}";
    
                // Guardar la imagen en el directorio usando el almacenamiento de Laravel
                $path = $request->file('logo')->storeAs($directorio, $file_name, 'public');
                Log::info('Ruta de la imagen almacenada: ' . $path);
    
                // Crear la ruta pública para la imagen
                $rutaImg = "storage/" . $path;
    
                // Actualizar el cliente con la ruta de la imagen
                Clientegeneral::where('idClienteGeneral', $idCliente)->update(['foto' => $rutaImg]);
                Log::info('Ruta de la imagen actualizada en la base de datos.');
            }
    
            // Responder con JSON
            return response()->json([
                'success' => true,
                'message' => 'Cliente agregado correctamente',
                'data' => $dataClientes,
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
    
        // Retornar vista con los datos del cliente
        return view('administracion.asociados.clienteGeneral.edit', compact('cliente'));
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

        // Crear un directorio basado en el ID del cliente
        $directory = 'img/general/' . $id; // Este es el directorio específico para cada cliente

        // Subir la nueva imagen al directorio específico para el cliente
        $filePath = $request->file('foto')->store($directory, 'public');
        $nuevoFoto = 'storage/' . $filePath; // Crear la ruta con el prefijo 'storage/'

        // Log para saber la ruta de la nueva imagen
        Log::info("Nueva imagen subida para el cliente $id: $nuevoFoto");

        // Actualizar la base de datos con la nueva imagen
        $cliente->foto = $nuevoFoto;
    }

    // Guardar los cambios en la base de datos
    $cliente->save();

    // Log para confirmar que los datos han sido actualizados correctamente
    Log::info("Cliente actualizado correctamente con ID: $id");

    // Redireccionar con un mensaje de éxito
    return redirect()->route('administracion.cliente-general')
        ->with('success', 'Cliente actualizado exitosamente.');
}




// public function destroy($id) {
//     $cliente = ClienteGeneral::find($id);

//     if (!$cliente) {
//         return response()->json(['error' => 'Cliente no encontrado'], 404);
//     }

//     // Verificar si el cliente tiene una imagen y eliminarla
//     if ($cliente->foto && Storage::exists($cliente->foto)) {
//         // Eliminar la imagen de la ruta especificada en la columna 'foto'
//         Storage::delete($cliente->foto);
//         // Opcional: Puedes hacer un log para confirmar que se eliminó la imagen
//         Log::info("Imagen del cliente $id eliminada: " . $cliente->foto);
//     }

//     // Eliminar el cliente
//     $cliente->delete();

//     return response()->json(['message' => 'Cliente y su imagen eliminados con éxito'], 200);
// }
 


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
                'foto' => $cliente->foto ? asset('/' .  $cliente->foto) : null, // URL completa de la foto
            ];
        });
    
        // Retorna los datos en formato JSON
        return response()->json($clientesData);
    }
    
    public function checkNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $exists = Clientegeneral::where('descripcion', $nombre)->exists();

        return response()->json(['unique' => !$exists]);
    }

    


}