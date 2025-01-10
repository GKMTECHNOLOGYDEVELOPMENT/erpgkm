<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralRequests;
use App\Models\Clientegeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ClienteGeneralController extends Controller
{
    public function index()
    {
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.cliente-general'); 
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
    


    public function update(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'estado' => 'required|boolean',
            'foto' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $clienteGeneral = ClienteGeneral::findOrFail($id);
        $clienteGeneral->descripcion = $request->descripcion;
        $clienteGeneral->estado = $request->estado;
        if ($request->hasFile('foto')) {
            $clienteGeneral->foto = file_get_contents($request->file('foto'));
        }
        $clienteGeneral->save();

        return response()->json(['message' => 'Cliente general actualizado']);
    }



public function destroy($id) {
    $cliente = ClienteGeneral::find($id);

    if (!$cliente) {
        return response()->json(['error' => 'Cliente no encontrado'], 404);
    }

    // Verificar si el cliente tiene una imagen y eliminarla
    if ($cliente->foto && Storage::exists($cliente->foto)) {
        // Eliminar la imagen de la ruta especificada en la columna 'foto'
        Storage::delete($cliente->foto);
        // Opcional: Puedes hacer un log para confirmar que se eliminó la imagen
        Log::info("Imagen del cliente $id eliminada: " . $cliente->foto);
    }

    // Eliminar el cliente
    $cliente->delete();

    return response()->json(['message' => 'Cliente y su imagen eliminados con éxito'], 200);
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