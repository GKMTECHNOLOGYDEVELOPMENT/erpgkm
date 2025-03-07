<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Http\Requests\TiendasRequest;
use App\Models\Cliente;
use App\Models\Ticket;
use App\Models\Tienda;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// use PDF;

class TiendaController extends Controller
{

public function index()
{
        $clientes = Cliente::all();
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.tienda.index', compact('clientes')); 
}

public function create()
{


        $clientes = Cliente::where('estado', 1)->get();
        $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
        return view('administracion.asociados.tienda.create', compact('clientes', 'departamentos'));
}
    
  // Método para almacenar la tienda
public function store(TiendasRequest $request)
{
      // Verificamos los datos que estamos recibiendo en la solicitud
      Log::info('Datos del formulario:', $request->all());
  
      // Validamos que el cliente seleccionado exista
      $cliente = Cliente::find($request->idCliente);
  
      if (!$cliente) {
          Log::error('El cliente no fue encontrado.', ['idCliente' => $request->idCliente]);
          return redirect()->back()->with('error', 'El cliente seleccionado no existe.');
      }
  
      // Intentamos guardar la tienda y verificamos si el modelo se crea correctamente
      try {
          $tienda = Tienda::create([
              'ruc' => $request->ruc,
              'nombre' => $request->nombre,
              'celular' => $request->celular,
              'email' => $request->email,
              'direccion' => $request->direccion,
              'referencia' => $request->referencia,
              'lat' => $request->lat,
              'lng' => $request->lng,
              'idCliente' => $request->idCliente, // Relación con cliente
              'departamento' => $request->departamento,
              'provincia' => $request->provincia,
              'distrito' => $request->distrito,
          ]);
  
          // Verificamos si la tienda fue guardada
          Log::info('Tienda guardada exitosamente:', ['tienda' => $tienda]);
  
      } catch (\Exception $e) {
          // En caso de error, logueamos el error
          Log::error('Error al guardar la tienda:', [
              'error' => $e->getMessage(),
              'request_data' => $request->all()
          ]);
  
          // Enviamos un mensaje de error al usuario
          return redirect()->back()->with('error', 'Hubo un problema al guardar la tienda.');
      }
  
      // Redirigimos a la lista de tiendas o donde desees
      return redirect()->route('administracion.tienda')->with('success', 'Tienda guardada exitosamente');
  }


  public function edit($id)
{
    // Buscar la tienda que se quiere editar
    $tienda = Tienda::findOrFail($id);

    // Obtener todos los clientes para el select
    $clientes = Cliente::all();

    // Obtener los datos de los archivos JSON
    $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
    $provincias = json_decode(file_get_contents(public_path('ubigeos/provincias.json')), true);
    $distritos = json_decode(file_get_contents(public_path('ubigeos/distritos.json')), true);

    // Buscar el departamento correspondiente a la tienda
    $departamentoSeleccionado = array_filter($departamentos, function($departamento) use ($tienda) {
        return $departamento['id_ubigeo'] == $tienda->departamento;
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
        if (isset($provincia['id_ubigeo']) && $provincia['id_ubigeo'] == $tienda->provincia) {
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

    // Devolver la vista con los datos necesarios
    return view('administracion.asociados.tienda.edit', compact(
        'tienda', 
        'clientes', 
        'departamentos', 
        'provinciasDelDepartamento', 
        'provinciaSeleccionada', 
        'distritosDeLaProvincia', 
        'distritoSeleccionado' // Si realmente necesitas esta variable, asegúrate de pasarla correctamente
    ));
}

public function exportAllPDF()
{
    $tiendas = Tienda::with('cliente')->get(); // Ahora la relación funcionará

    try {
        $pdf = PDF::loadView('administracion.asociados.tienda.pdf.reporte-tiendas', compact('tiendas'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('reporte-tiendas.pdf');
    } catch (\Exception $e) {
        Log::error('Error al generar el PDF: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Hubo un problema al generar el PDF.');
    }
}
  
public function update(Request $request, $id)
{
    // Validación de los datos del formulario
    $request->validate([
        'ruc' => 'required|string|max:255',
        'nombre' => 'required|string|max:255',
        'direccion' => 'required|string|max:255',
        'idCliente' => 'required|exists:cliente,idCliente',
        'celular' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'referencia' => 'nullable|string|max:255',
        'lat' => 'required|numeric',
        'lng' => 'required|numeric',
        'departamento' => 'required|string|max:255', // Validar el campo 'departamento'
        'provincia' => 'required|string|max:255',    // Validar el campo 'provincia'
        'distrito' => 'required|string|max:255',     // Validar el campo 'distrito'
    ]);

    // Buscar la tienda que se quiere actualizar
    $tienda = Tienda::findOrFail($id);

    // Actualizar la tienda con los datos recibidos, incluyendo 'departamento', 'provincia' y 'distrito'
    $tienda->update([
        'ruc' => $request->ruc,
        'nombre' => $request->nombre,
        'direccion' => $request->direccion,
        'idCliente' => $request->idCliente,
        'celular' => $request->celular,
        'email' => $request->email,
        'referencia' => $request->referencia,
        'lat' => $request->lat,
        'lng' => $request->lng,
        'departamento' => $request->departamento, // Actualizar el departamento
        'provincia' => $request->provincia,       // Actualizar la provincia
        'distrito' => $request->distrito,         // Actualizar el distrito
    ]);

    // Redirigir al índice de tiendas con un mensaje de éxito
    return redirect()->route('administracion.tienda')->with('success', 'Tienda actualizada exitosamente');
}

// public function destroy($id)
// {
//     $tienda = Tienda::find($id);

//     if (!$tienda) {
//         return response()->json(['error' => 'Cliente no encontrado'], 404);
//     }
//     // Eliminar el cliente
//     $tienda->delete();

//     // Responder con el estado de la eliminación
//     return response()->json([
//         'message' => 'Tienda eliminada con éxito'
       
//     ], 200);
// }


public function destroy($id)
{
    // Registrar el inicio del proceso
    Log::info("Intentando eliminar la tienda con ID: $id");

    $tienda = Tienda::find($id);

    if (!$tienda) {
        Log::warning("No se encontró la tienda con ID: $id");
        return response()->json(['error' => 'Tienda no encontrada'], 404);
    }

    // Verificar si hay tickets asociados a esta tienda
    $ticketsAsociados = Ticket::where('idTienda', $id)->exists();
    if ($ticketsAsociados) {
        Log::warning("No se puede eliminar la tienda con ID: $id porque está asociada a un ticket.");
        return response()->json(['error' => 'No se puede eliminar la tienda porque está asociada a un ticket'], 400);
    }

    // Eliminar la tienda
    $tienda->delete();
    Log::info("Tienda con ID: $id eliminada con éxito.");

    // Responder con el estado de la eliminación
    return response()->json([
        'message' => 'Tienda eliminada con éxito'
    ], 200);
}
public function getAll()
{
    // Obtén todos los datos de la tabla tienda
    $tiendas = Tienda::all();

    // Procesa los datos para incluir todos los campos necesarios
    $tiendasData = $tiendas->map(function ($tienda) {
        return [
            'idTienda'   => $tienda->idTienda,
            'nombre'     => $tienda->nombre,
            'ruc'        => $tienda->ruc,
            'celular'    => $tienda->celular,
            'email'      => $tienda->email,
            'direccion'  => $tienda->direccion,
            'referencia' => $tienda->referencia,
        ];
    });

    // Retorna los datos en formato JSON
    return response()->json($tiendasData);
}
// Validar RUC
public function validarRuc(Request $request)
{

         $ruc = $request->ruc;
         $exists = Tienda::where('ruc', $ruc)->exists();
         return response()->json(['exists' => $exists]);
}
// Validar email
 public function validarEmail(Request $request)
{

     $email = $request->email;
         $exists = Tienda::where('email', $email)->exists();
         return response()->json(['exists' => $exists]);
}
 
// Validar celular
public function validarCelular(Request $request)
{
         $celular = $request->celular;
         $exists = Tienda::where('celular', $celular)->exists();
         return response()->json(['exists' => $exists]);
}
 
// Validar nombre
public function validarNombre(Request $request)
{
         $nombre = $request->nombre;
         $exists = Tienda::where('nombre', $nombre)->exists();
         return response()->json(['exists' => $exists]);
}


public function checkTiendas(Request $request)
{
    // Obtener todas las tiendas sin filtrar por 'estado'
    $tiendas = Tienda::all(); // Esto devuelve todas las tiendas

    return response()->json($tiendas); // Devolvemos las tiendas en formato JSON
}




}