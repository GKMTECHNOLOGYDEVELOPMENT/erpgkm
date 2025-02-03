<?php

namespace App\Http\Controllers\tickets;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket; // Asegúrate de tener este modelo
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ClienteGeneral; // Reemplaza con el modelo correcto
use App\Models\TipoServicio; // Reemplaza con el modelo correcto
use App\Models\Usuario; // Reemplaza con el modelo correcto
use App\Models\Tipoticket; // Reemplaza con el modelo correcto
use App\Models\Cliente; // Reemplaza con el modelo correcto
use App\Models\Tienda; // Reemplaza con el modelo correcto
use App\Models\Marca; // Reemplaza con el modelo correcto
use App\Models\Modelo; // Reemplaza con el modelo correcto
use App\Models\TipoDocumento; // Reemplaza con el modelo correcto
use Illuminate\Support\Facades\File; // Asegúrate de usar esta clase
// use Barryvdh\DomPDF\Facade as PDF;

class OrdenesTrabajoController extends Controller
{
    // Mostrar la vista principal según el rol del usuario
    public function index()
    {
        // Obtener usuario autenticado y su rol
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? 'Sin Rol';

        // Obtener los datos necesarios
        $clientesGenerales = ClienteGeneral::all();
        $tiposServicio = TipoServicio::all();
        $usuarios = Usuario::where('idTipoUsuario', 4)->get();
        $tiposTickets = Tipoticket::all();
        $clientes = Cliente::all();
        $tiendas = Tienda::all();
        $marcas = Marca::all();
        $modelos = Modelo::all();

        // Determinar la carpeta de vistas según el rol
        $carpetaVista = match ($rol) {
            'COORDINACION SMART' => 'smart-tv',
            'ADMIN PRINCIPAL' => 'smart-tv', 'helpdesk',
            'COORDINACION HELP DESK' => 'helpdesk',
            default => '',
        };

        if ($carpetaVista) {
            return view("tickets.ordenes-trabajo.$carpetaVista.index", compact(
                'clientesGenerales',
                'tiposServicio',
                'usuarios',
                'tiposTickets',
                'clientes',
                'tiendas',
                'marcas',
                'modelos'
            ));
        } else {
            abort(403, 'No tienes permiso para acceder a esta vista.');
        }
    }

     // Mostrar la vista principal según el rol del usuario
     public function helpdesk()
     {
         // Obtener usuario autenticado y su rol
         $usuario = Auth::user();
         $rol = $usuario->rol->nombre ?? 'Sin Rol';
 
         // Obtener los datos necesarios
         $clientesGenerales = ClienteGeneral::all();
         $tiposServicio = TipoServicio::all();
         $usuarios = Usuario::where('idTipoUsuario', 4)->get();
         $tiposTickets = Tipoticket::all();
         $clientes = Cliente::all();
         $tiendas = Tienda::all();
         $marcas = Marca::all();
         $modelos = Modelo::all();
 
         // Determinar la carpeta de vistas según el rol
         $carpetaVista = match ($rol) {
             'COORDINACION SMART' => 'smart-tv',
             'COORDINACION HELP DESK' => 'helpdesk',
             default => '',
         };
 
         if ($carpetaVista) {
             return view("tickets.ordenes-trabajo.helpdesk.index", compact(
                 'clientesGenerales',
                 'tiposServicio',
                 'usuarios',
                 'tiposTickets',
                 'clientes',
                 'tiendas',
                 'marcas',
                 'modelos'
             ));
         } else {
             abort(403, 'No tienes permiso para acceder a esta vista.');
         }
     }


      // Mostrar la vista principal según el rol del usuario
    public function smart()
    {
        // Obtener usuario autenticado y su rol
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? 'Sin Rol';

        // Obtener los datos necesarios
        $clientesGenerales = ClienteGeneral::all();
        $tiposServicio = TipoServicio::all();
        $usuarios = Usuario::where('idTipoUsuario', 4)->get();
        $tiposTickets = Tipoticket::all();
        $clientes = Cliente::all();
        $tiendas = Tienda::all();
        $marcas = Marca::all();
        $modelos = Modelo::all();

        // Determinar la carpeta de vistas según el rol
        $carpetaVista = match ($rol) {
            'COORDINACION SMART' => 'smart-tv',
            'COORDINACION HELP DESK' => 'helpdesk',
            default => '',
        };

      
            return view("tickets.ordenes-trabajo.smart-tv.index", compact(
                'clientesGenerales',
                'tiposServicio',
                'usuarios',
                'tiposTickets',
                'clientes',
                'tiendas',
                'marcas',
                'modelos'
            ));
       
    }


    // Cargar la vista de creación según el rol del usuario
    public function createsmart()
    {
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? 'Sin Rol';

        $clientesGenerales = ClienteGeneral::where('estado', 1)->get();
        $clientes = Cliente::where('estado', 1)->get();
        $tiendas = Tienda::all();
        $usuarios = Usuario::where('idTipoUsuario', 1)->get();
        $tiposServicio = TipoServicio::all();
        $marcas = Marca::all();
        $modelos = Modelo::all();
        $tiposDocumento = TipoDocumento::all();

       

       
            return view("tickets.ordenes-trabajo.smart-tv.create", compact(
                'clientesGenerales',
                'clientes',
                'tiendas',
                'usuarios',
                'tiposServicio',
                'marcas',
                'modelos', 'tiposDocumento'
            ));
       
    }

     // Cargar la vista de creación según el rol del usuario
     public function createhelpdesk()
     {
         $usuario = Auth::user();
         $rol = $usuario->rol->nombre ?? 'Sin Rol';
 
         $clientesGenerales = ClienteGeneral::where('estado', 1)->get();
         $clientes = Cliente::where('estado', 1)->get();
         $tiendas = Tienda::all();
         $usuarios = Usuario::where('idTipoUsuario', 4)->get();
         $tiposServicio = TipoServicio::all();
         $marcas = Marca::all();
         $modelos = Modelo::all();
 
        
 
        
             return view("tickets.ordenes-trabajo.helpdesk.create", compact(
                 'clientesGenerales',
                 'clientes',
                 'tiendas',
                 'usuarios',
                 'tiposServicio',
                 'marcas',
                 'modelos'
             ));
        
     }

     public function storehelpdesk(Request $request)
     {
         try {
             // Log de depuración: mostrar los datos de la solicitud
             Log::debug('Datos recibidos en storehelpdesk:', $request->all());
     
             // Validar los datos
             $validatedData = $request->validate([
                 'numero_ticket' => 'required|string|max:255|unique:tickets,numero_ticket',
                 'idClienteGeneral' => 'required|integer|exists:clientegeneral,idClienteGeneral',
                 'idCliente' => 'required|integer|exists:cliente,idCliente',
                 'idTienda' => 'required|integer|exists:tienda,idTienda',
                 'idTecnico' => 'required|integer|exists:usuarios,idUsuario',
                 'tipoServicio' => 'required|integer|exists:tiposervicio,idTipoServicio',
                 'fallaReportada' => 'required|string|max:255',
             ]);
     
             // Log de depuración: mostrar los datos validados
             Log::debug('Datos validados:', $validatedData);
     
             // Crear la nueva orden de trabajo
             Ticket::create([
                 'numero_ticket' => $validatedData['numero_ticket'],
                 'idClienteGeneral' => $validatedData['idClienteGeneral'],
                 'idCliente' => $validatedData['idCliente'],
                 'idTienda' => $validatedData['idTienda'],
                 'idTecnico' => $validatedData['idTecnico'],
                 'tipoServicio' => $validatedData['tipoServicio'],
                 'idUsuario' => auth()->id(), // ID del usuario autenticado
                 'idEstadoots' => 17, // Estado inicial de la orden de trabajo
                 'fallaReportada'=> $validatedData['fallaReportada'],
                 'fecha_creacion' => now(), // Establece la fecha y hora actuales
             ]);
     
             // Log de depuración: confirmar que se creó la orden de trabajo
             Log::debug('Orden de trabajo creada correctamente.');
     
             // Redirigir con un mensaje de éxito
             return redirect()->route('ordenes.index')->with('success', 'Orden de trabajo creada correctamente.');
         } catch (\Illuminate\Validation\ValidationException $e) {
             // Log de depuración: mostrar los errores de validación
             Log::error('Errores de validación:', $e->errors());
     
             // Si la validación falla, redirigir con los errores
             return redirect()->back()->withErrors($e->errors())->withInput();
         } catch (\Exception $e) {
             // Log de depuración: mostrar el error general
             Log::error('Error al crear la orden de trabajo: ' . $e->getMessage());
     
             // En caso de cualquier otro error
             return redirect()->back()->with('error', 'Ocurrió un error al crear la orden de trabajo.');
         }
     }


public function storesmart(Request $request)
{
    try {
        Log::info('Inicio de la creación de orden de trabajo', ['data' => $request->all()]);

        // Validación de los datos
        $validatedData = $request->validate([
            'nroTicket' => 'required|string|max:255|unique:tickets,numero_ticket',
            'idClienteGeneral' => 'required|integer|exists:clientegeneral,idClienteGeneral',
            'idCliente' => 'required|integer|exists:cliente,idCliente',
            'idTienda' => 'required|integer|exists:tienda,idTienda',
            'direccion' => 'required|string|max:255',
            'idMarca' => 'required|integer|exists:marca,idMarca',
            'idModelo' => 'required|integer|exists:modelo,idModelo',
            'serie' => 'required|string|max:255',
            'tecnico' => 'required|integer|exists:usuarios,idUsuario',
            'fechaCompra' => 'required|date_format:Y-m-d', // Asegúrate de usar este formato de fecha
            'fallaReportada' => 'required|string|max:255',
            'lat' => 'nullable|string|max:255',
            'lng' => 'nullable|string|max:255',
        ]);

        Log::info('Datos validados correctamente', ['validatedData' => $validatedData]);

        // Crear la nueva orden de trabajo
        $ticket = Ticket::create([
            'numero_ticket' => $validatedData['nroTicket'],
            'idClienteGeneral' => $validatedData['idClienteGeneral'],
            'idCliente' => $validatedData['idCliente'],
            'idTienda' => $validatedData['idTienda'],
            'direccion' => $validatedData['direccion'],
            'idMarca' => $validatedData['idMarca'],
            'idModelo' => $validatedData['idModelo'],
            'serie' => $validatedData['serie'],
            'idTecnico' => $validatedData['tecnico'],
            'fechaCompra' => $validatedData['fechaCompra'],
            'fallaReportada' => $validatedData['fallaReportada'],
            'lat' => $validatedData['lat'],
            'lng' => $validatedData['lng'],
            'idEstadoots' => 1, // Estado inicial de la orden de trabajo
            'idUsuario' => auth()->id(), // ID del usuario autenticado
            'fecha_creacion' => now(), // Fecha de creación
        ]);

        Log::info('Orden de trabajo creada correctamente', ['ticket' => $ticket]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('ordenes.index')->with('success', 'Orden de trabajo creada correctamente.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        // En caso de error en la validación
        Log::error('Errores de validación', ['errors' => $e->errors()]);
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        // En caso de cualquier otro error
        Log::error('Error al crear la orden de trabajo', ['exception' => $e->getMessage()]);
        return redirect()->back()->with('error', 'Ocurrió un error al crear la orden de trabajo.');
    }
}

    // Editar orden de trabajo según el rol
    public function edit($id)
    {
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? 'Sin Rol';

        $orden = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda'])->findOrFail($id);

        
        $modelos = Modelo::all(); // Obtén todos los modelos disponibles

        $carpetaVista = match ($rol) {
            'COORDINACION SMART' => 'smart-tv',
            'COORDINACION HELP DESK' => 'helpdesk',
            default => '',
        };

        if ($carpetaVista) {
            return view("tickets.ordenes-trabajo.$carpetaVista.edit", compact('orden', 'modelos', 'usuario'));	
        } else {
            abort(403, 'No tienes permiso para acceder a esta vista.');
        }
    }

    // Actualizar una orden de trabajo
    public function update(Request $request, $id)
    {
        try {
            // Validar los datos
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'estado' => 'nullable|boolean',
            ]);

            // Encontrar y actualizar la orden de trabajo
            $orden = Ticket::findOrFail($id);
            $orden->update($validatedData);

            return redirect()->route('ordenes-trabajo.index')->with('success', 'Orden de trabajo actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar una orden de trabajo: ' . $e->getMessage());
            return redirect()->route('ordenes-trabajo.index')->with('error', 'Ocurrió un error al actualizar la orden de trabajo.');
        }
    }

    // Eliminar una orden de trabajo
    public function destroy($id)
    {
        try {
            $orden = Ticket::findOrFail($id);
            $orden->delete();

            return response()->json(['success' => true, 'message' => 'Orden de trabajo eliminada correctamente.']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar una orden de trabajo: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al eliminar la orden de trabajo.'], 500);
        }
    }

    // Exportar todas las órdenes de trabajo a PDF
    public function exportAllPDF()
    {
        $ordenes = Ticket::all();

        $pdf = Pdf::loadView('tickets.ordenes-trabajo.pdf.ordenes', compact('ordenes'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('reporte-ordenes-trabajo.pdf');
    }

    // Obtener todas las órdenes de trabajo en formato JSON
    public function getAll()
    {
        $ordenes = Ticket::with([
            'tecnico:idUsuario,Nombre', // Relación para obtener el nombre del técnico
            'usuario:idUsuario,Nombre', // Relación para obtener el nombre del usuario
            'cliente:idCliente,nombre', // Relación para obtener el nombre del cliente
            'clientegeneral:idClienteGeneral,descripcion', // Relación para el cliente general
            'tiposervicio:idTipoServicio,nombre', // Relación para obtener el nombre del tipo de servicio
            'estado_ot:idEstadoots,descripcion,color', // Relación para obtener la descripción del estado
        ])->get();

        // Formatear el resultado
        $ordenes = $ordenes->map(function ($orden) {
            return [
                'idTickets' => $orden->idTickets,
                'numero_ticket' => $orden->numero_ticket,
                'tecnico' => $orden->tecnico->Nombre ?? 'N/A', // Nombre del técnico
                'usuario' => $orden->usuario->Nombre ?? 'N/A', // Nombre del usuario
                'cliente' => $orden->cliente->nombre ?? 'N/A', // Nombre del cliente
                'cliente_general' => $orden->clientegeneral->descripcion ?? 'N/A', // Nombre del cliente general
                'tipoServicio' => $orden->tiposervicio->nombre ?? 'N/A', // Nombre del tipo de servicio
                'estado' => $orden->estado_ot->descripcion ?? 'N/A', // Descripción del estado
                'fecha_creacion' => $orden->fecha_creacion ? $orden->fecha_creacion->format('d/m/Y H:i') : 'N/A', // Formato de fecha
                'color' => $orden->estado_ot->color ?? '#000000', // Color del estado
            ];
        });

        return response()->json($ordenes);
    }

    public function generarInformePdf($idTickets)
    {
        // Obtener la información de la orden con el idTickets
        $orden = Ticket::where('idTickets', $idTickets)->firstOrFail();

        // Cargar una vista de Blade con los datos
        $pdf = PDF::loadView('tickets.ordenes-trabajo.pdf.informe', compact('orden'));

        // Mostrar el PDF en el navegador
        return $pdf->stream('informe_orden_' . $idTickets . '.pdf');
    }

    public function verInforme($idTickets)
    {
        // Obtener la información de la orden con el idTickets
        $orden = Ticket::where('idTickets', $idTickets)->firstOrFail();

        // Ruta donde se guardará el PDF
        $pdfDirectory = storage_path('app/public/pdfs');

        // Crear el directorio si no existe
        if (!File::exists($pdfDirectory)) {
            File::makeDirectory($pdfDirectory, 0777, true);
        }

        // Generar el PDF
        $pdf = PDF::loadView('tickets.ordenes-trabajo.pdf.informe', compact('orden'));
        // Ruta final donde se guardará el PDF
        $pdfPath = 'pdfs/informe_orden_' . $idTickets . '.pdf';

        // Guardar el PDF en el directorio especificado
        $pdf->save(storage_path('app/public/' . $pdfPath));

        // Retornar la URL del PDF
        return response()->json([
            'pdfUrl' => url('storage/' . $pdfPath)
        ]);
    }


    public function verHojaEntrega($idTickets)
    {
        // Obtener la información de la orden con el idTickets
        $orden = Ticket::where('idTickets', $idTickets)->firstOrFail();

        // Ruta donde se guardará el PDF
        $pdfDirectory = storage_path('app/public/pdfs');

        // Crear el directorio si no existe
        if (!File::exists($pdfDirectory)) {
            File::makeDirectory($pdfDirectory, 0777, true);
        }

        // Generar el PDF de la Hoja de Entrega
        $pdf = PDF::loadView('tickets.ordenes-trabajo.pdf.hoja_entrega', compact('orden'));

        // Ruta final donde se guardará el PDF
        $pdfPath = 'pdfs/hoja_entrega_orden_' . $idTickets . '.pdf';

        // Guardar el PDF en el directorio especificado
        $pdf->save(storage_path('app/public/' . $pdfPath));

        // Retornar la URL del PDF
        return response()->json([
            'pdfUrl' => url('storage/' . $pdfPath)
        ]);
    }


    // Validar si un nombre ya existe
    public function checkNumeroTicket(Request $request)
    {
        $numero_ticket = $request->input('numero_ticket');
        $exists = Ticket::where('numero_ticket', $numero_ticket)->exists();

        return response()->json(['unique' => !$exists]);
    }


    public function obtenerModelosPorMarca($idMarca){
        //Obtener los modelos relacionados con la marca
        $modelos = Modelo::where('idMarca', $idMarca)->get();

        //Retornamos los modelos en formato JSON
        return response()->json($modelos);

    }
}
