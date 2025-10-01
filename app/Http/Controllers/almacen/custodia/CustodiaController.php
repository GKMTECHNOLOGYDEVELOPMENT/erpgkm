<?php

namespace App\Http\Controllers\almacen\custodia;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Custodia;
use App\Models\CustodiaFoto;
use App\Models\CustodiaUbicacion;
use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use App\Models\Ubicacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class CustodiaController extends Controller
{

    public function index(Request $request)
    {
        $query = Custodia::with([
            'ticket',
            'ticket.cliente',
            'ticket.cliente.tipoDocumento',
            'ticket.marca',
            'ticket.modelo'
        ])
            ->where('estado', '!=', 'Rechazado');

        // Filtro de búsqueda
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('codigocustodias', 'like', "%{$search}%")
                    ->orWhereHas('ticket', function ($q2) use ($search) {
                        $q2->where('serie', 'like', "%{$search}%")
                            ->orWhere('numero_ticket', 'like', "%{$search}%")
                            ->orWhereHas('cliente', function ($q3) use ($search) {
                                $q3->where('nombre', 'like', "%{$search}%")
                                    ->orWhere('documento', 'like', "%{$search}%");
                            })
                            ->orWhereHas('marca', function ($q3) use ($search) {
                                $q3->where('nombre', 'like', "%{$search}%");
                            })
                            ->orWhereHas('modelo', function ($q3) use ($search) {
                                $q3->where('nombre', 'like', "%{$search}%");
                            });
                    });
            });
        }

        // Filtro por estado
        if ($request->has('estado') && $request->estado != 'Todos los estados') {
            $query->where('estado', $request->estado);
        }

        $custodias = $query->orderBy('created_at', 'desc')->paginate(9);

        // Estadísticas (siempre sin incluir rechazados)
        $totalSolicitudes = Custodia::where('estado', '!=', 'Rechazado')->count();
        $pendientes = Custodia::where('estado', 'Pendiente')->count();
        $enCustodia = Custodia::where('estado', 'Aprobado')->count();
        $devueltos = Custodia::where('estado', 'Devuelto')->count();

        // Mantener los filtros en la paginación
        if ($request->has('search')) {
            $custodias->appends(['search' => $request->search]);
        }
        if ($request->has('estado')) {
            $custodias->appends(['estado' => $request->estado]);
        }

        return view('solicitud.solicitudcustodia.index', compact(
            'custodias',
            'totalSolicitudes',
            'pendientes',
            'enCustodia',
            'devueltos'
        ));
    }


    public function harvest($id)
{
    $custodia = Custodia::findOrFail($id);

    // lógica de Harvest aquí...

    return view('solicitud.solicitudcustodia.harvest', compact('custodia'));
}



    public function actualizarCustodia(Request $request, $id)
    {
        $request->validate([
            'es_custodia' => 'required|boolean',
            'ubicacion_actual' => 'required_if:es_custodia,1|string|max:100',
            'erma' => 'nullable|string|max:50',
        ]);

        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Ticket no encontrado'], 404);
        }

        $nuevoEstado = (int) $request->input('es_custodia');
        $custodia = Custodia::where('id_ticket', $ticket->idTickets)->first();
        $erma = trim($request->input('erma'));

        // 🚨 Si no hay ERMA, automáticamente desactivar custodia
        if ($nuevoEstado === 1 && empty($erma)) {
            if ($custodia) {
                $custodia->estado = 'Rechazado';
                $custodia->save();
            }

            $ticket->es_custodia = 0;
            $ticket->save();

            return response()->json([
                'success' => false,
                'message' => '❌ No se puede activar custodia sin N. erma. Custodia desactivada automáticamente.',
                'es_custodia' => 0
            ], 400);
        }

        // ✅ PONER EN CUSTODIA
        if ($nuevoEstado === 1) {
            $ubicacion = trim($request->input('ubicacion_actual'));
            $fechaIngreso = $request->input('fecha_ingreso_custodia');

                    if (!$custodia) {
            $custodia = Custodia::create([
                'codigocustodias'        => strtoupper(Str::random(10)),
                'id_ticket'              => $ticket->idTickets,
                'idcliente'              => $ticket->idCliente,
                'numero_ticket'          => $ticket->numero_ticket,
                'idMarca'                => $ticket->idMarca,
                'idModelo'               => $ticket->idModelo,
                'serie'                  => $ticket->serie,
                'estado'                 => 'Pendiente',
                'fecha_ingreso_custodia' => $fechaIngreso ?? now()->toDateString(),
                'ubicacion_actual'       => $ubicacion,
                'responsable_entrega'    => null,
                'id_responsable_recepcion'  => auth()->id(),
                'observaciones'          => null,
                'fecha_devolucion'       => null,
            ]);
        } else {
                $custodia->ubicacion_actual = $ubicacion;
                $custodia->fecha_ingreso_custodia = $fechaIngreso ?? $custodia->fecha_ingreso_custodia;

                if ($custodia->estado === 'Rechazado') {
                    $custodia->estado = 'Pendiente';
                }

                $custodia->save();
            }

            $ticket->es_custodia = 1;
            $ticket->erma = $erma; // ✅ Guardar erma si existe
            $ticket->save();

            return response()->json([
                'success' => true,
                'message' => '✅ Equipo puesto en custodia correctamente.',
                'es_custodia' => 1,
                'ubicacion_actual' => $custodia->ubicacion_actual,
                'fecha_ingreso_custodia' => $custodia->fecha_ingreso_custodia,
                'erma' => $ticket->erma
            ]);
        }

        // ✅ QUITAR DE CUSTODIA
        if ($nuevoEstado === 0) {
            if ($custodia) {
                if ($custodia->estado === 'Pendiente') {
                    $custodia->estado = 'Rechazado';
                    $custodia->save();
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede desmarcar la custodia porque está en estado: ' . $custodia->estado,
                    ], 403);
                }
            }

            $ticket->es_custodia = 0;
            $ticket->save();

            return response()->json([
                'success' => true,
                'message' => '✅ Custodia desactivada correctamente.',
                'es_custodia' => 0
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Acción no válida.'], 400);
    }





    public function verificarCustodia($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Ticket no encontrado'], 404);
        }

        return response()->json(['success' => true, 'es_custodia' => $ticket->es_custodia]);
    }

    public function opciones($id)
    {
        $custodia = Custodia::with([
            'ticket',
            'ticket.cliente',
            'ticket.cliente.tipoDocumento',
            'ticket.marca',
            'ticket.modelo',
            'responsableRecepcion'
        ])->findOrFail($id);

        $ubicaciones = Ubicacion::with('sucursal')->get();


        return view('solicitud.solicitudcustodia.opciones', compact('custodia', 'ubicaciones'));
    }

    public function update(Request $request, $id)
    {
        $custodia = Custodia::findOrFail($id);

        // Validar los datos
        $validated = $request->validate([
            'estado' => 'required|in:Pendiente,En revisión,Aprobado,Rechazado,Devuelto',
            'ubicacion_actual' => 'required_if:estado,Aprobado|max:100',
            'observaciones' => 'nullable|string',
            'idubicacion' => 'required_if:estado,Aprobado|exists:ubicacion,idUbicacion',
            'observacion_almacen' => 'nullable|string',
            'cantidad' => 'sometimes|integer|min:1'
        ]);

        // Desactivar verificaciones de FK temporalmente (SOLO DESARROLLO)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::beginTransaction();

        try {
            // Actualizar la custodia
            $custodia->update($validated);

            // Si el estado es Aprobado, guardar en custodia_ubicacion
            if ($request->estado === 'Aprobado') {
                $ubicacion = Ubicacion::find($request->idubicacion);

                // Usar updateOrCreate con el formato correcto
                CustodiaUbicacion::updateOrCreate(
                    ['idCustodia' => $custodia->id],
                    [
                        'idUbicacion' => $request->idubicacion,
                        'observacion' => $request->observacion_almacen,
                        'cantidad' => $request->cantidad ?? 1,
                        'updated_at' => now(),
                        'created_at' => now() // Asegurar created_at
                    ]
                );
            } else if ($request->estado !== 'Aprobado') {
                CustodiaUbicacion::where('idCustodia', $custodia->id)->delete();
            }

            DB::commit();

            // Reactivar verificaciones de FK
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return response()->json([
                'success' => true,
                'message' => 'Custodia actualizada correctamente',
                'estado_actualizado' => $custodia->estado
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la custodia: ' . $e->getMessage()
            ], 500);
        }
    }



   public function create()
    {
        // Ya no necesitamos pasar los datos aquí, se cargarán via AJAX
        return view('solicitud.solicitudcustodia.create');
    }

    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'idcliente' => 'required|exists:cliente,idCliente',
            'numero_ticket' => 'nullable|string|max:100',
            'idMarca' => 'nullable|exists:marca,idMarca',
            'idModelo' => 'nullable|exists:modelo,idModelo',
            'serie' => 'nullable|string|max:100',
            'fecha_ingreso_custodia' => 'required|date',
            'ubicacion_actual' => 'required|string|max:100',
            'estado' => 'required|in:Pendiente,En revisión,Aprobado',
            'observaciones' => 'nullable|string',
        ]);

        try {
            // Generar código de custodia
            $codigoCustodia = 'CUST-' . date('YmdHis') . '-' . rand(100, 999);

            // Crear la custodia
            $custodia = Custodia::create([
                'codigocustodias' => $codigoCustodia,
                'id_ticket' => null,
                'idcliente' => $validated['idcliente'],
                'numero_ticket' => $validated['numero_ticket'],
                'idMarca' => $validated['idMarca'],
                'idModelo' => $validated['idModelo'],
                'serie' => $validated['serie'],
                'estado' => $validated['estado'],
                'fecha_ingreso_custodia' => $validated['fecha_ingreso_custodia'],
                'ubicacion_actual' => $validated['ubicacion_actual'],
                'id_responsable_recepcion' => auth()->id(),
                'observaciones' => $validated['observaciones'],
            ]);

            // Si es una petición AJAX, retornar JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Custodia creada exitosamente.',
                    'data' => $custodia
                ]);
            }

            return redirect()->route('solicitudcustodia.index')
                ->with('success', 'Custodia creada exitosamente.');

        } catch (\Exception $e) {
            // Si es una petición AJAX, retornar error en JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la custodia: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error al crear la custodia: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Métodos actualizados para soportar búsqueda con Select2
       public function getClientes(Request $request): JsonResponse
    {
        $search = $request->get('search');
        
        $clientes = Cliente::where('estado', 1)
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('documento', 'like', "%{$search}%");
                });
            })
            ->orderBy('nombre')
            ->paginate(30);

        // Transformar los datos para Select2
        $transformed = $clientes->getCollection()->map(function($cliente) {
            return [
                'id' => $cliente->idCliente,
                'nombre' => $cliente->nombre,
                'documento' => $cliente->documento,
                'text' => $cliente->nombre . ($cliente->documento ? ' - ' . $cliente->documento : '')
            ];
        });

        return response()->json([
            'results' => $transformed,
            'pagination' => [
                'more' => $clientes->hasMorePages()
            ]
        ]);
    }

        public function getMarcas(Request $request): JsonResponse
    {
        $search = $request->get('search');
        
        $marcas = Marca::where('estado', 1)
            ->when($search, function($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%");
            })
            ->orderBy('nombre')
            ->paginate(30);

        $transformed = $marcas->getCollection()->map(function($marca) {
            return [
                'id' => $marca->idMarca,
                'nombre' => $marca->nombre,
                'text' => $marca->nombre
            ];
        });

        return response()->json([
            'results' => $transformed,
            'pagination' => [
                'more' => $marcas->hasMorePages()
            ]
        ]);
    }
    public function getModelos(Request $request): JsonResponse
    {
        $search = $request->get('search');
        
        $modelos = Modelo::where('estado', 1)
            ->when($search, function($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%");
            })
            ->orderBy('nombre')
            ->paginate(30);

        $transformed = $modelos->getCollection()->map(function($modelo) {
            return [
                'id' => $modelo->idModelo,
                'nombre' => $modelo->nombre,
                'text' => $modelo->nombre
            ];
        });

        return response()->json([
            'results' => $transformed,
            'pagination' => [
                'more' => $modelos->hasMorePages()
            ]
        ]);
    }

   public function guardarFotos(Request $request, $idCustodia)
    {
        // Validar que la custodia exista
        $custodia = Custodia::find($idCustodia);
        
        if (!$custodia) {
            return response()->json([
                'success' => false,
                'message' => 'Custodia no encontrada'
            ], 404);
        }

        $request->validate([
            'fotos.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        // Verificar que hay fotos
        if (!$request->hasFile('fotos')) {
            return response()->json([
                'success' => false,
                'message' => 'No se han seleccionado fotos'
            ], 400);
        }

        $fotosSubidas = [];

        try {
            foreach ($request->file('fotos') as $foto) {
                // Validar cada archivo individualmente
                if (!$foto->isValid()) {
                    continue;
                }

                // Generar nombre único con hash
                $nombreOriginal = $foto->getClientOriginalName();
                $extension = $foto->getClientOriginalExtension();
                $tipoMime = $foto->getMimeType();
                $tamaño = $foto->getSize();
                
                // Leer el contenido del archivo
                $contenidoImagen = file_get_contents($foto->getRealPath());
                
                // Generar hash único para el nombre
                $nombreHash = $this->generarNombreHash($contenidoImagen, $custodia->id);
                
                // Generar hash para verificar integridad
                $hashArchivo = hash('sha256', $contenidoImagen);

                // Guardar en base de datos (se encripta automáticamente por el mutator)
                $fotoGuardada = CustodiaFoto::create([
                    'id_custodia' => $custodia->id,
                    'nombre_archivo' => $nombreOriginal,
                    'nombre_hash' => $nombreHash,
                    'tipo_archivo' => $tipoMime,
                    'tamaño_archivo' => $tamaño,
                    'datos_imagen' => $contenidoImagen, // Se encripta automáticamente
                    'hash_archivo' => $hashArchivo,
                    'uploaded_by' => auth()->id()
                ]);

                $fotosSubidas[] = $fotoGuardada;
            }

            return response()->json([
                'success' => true,
                'message' => count($fotosSubidas) . ' foto(s) subida(s) correctamente',
                'fotos_subidas' => count($fotosSubidas)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar las fotos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar nombre hash único para el archivo
     */
    private function generarNombreHash($contenidoImagen, $idCustodia)
    {
        $timestamp = microtime(true);
        $stringUnico = $contenidoImagen . $timestamp . $idCustodia . Str::random(10);
        
        return hash('sha256', $stringUnico);
    }

    /**
     * Obtener fotos de una custodia
     */
    public function obtenerFotos($idCustodia)
    {
        $fotos = CustodiaFoto::where('id_custodia', $idCustodia)
                            ->select([
                                'id',
                                'nombre_archivo',
                                'nombre_hash',
                                'tipo_archivo',
                                'tamaño_archivo',
                                'hash_archivo',
                                'descripcion',
                                'uploaded_by',
                                'created_at'
                            ])
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        return response()->json([
            'fotos' => $fotos
        ]);
    }

    /**
     * Obtener imagen individual (para mostrar en img src)
     */
    public function obtenerImagen($idFoto)
    {
        $foto = CustodiaFoto::find($idFoto);
        
        if (!$foto) {
            abort(404);
        }

        // Verificar permisos (opcional)
        // if (!auth()->check()) {
        //     abort(403);
        // }

        // Obtener la imagen desencriptada a través del accesor
        $imagenDesencriptada = $foto->imagen;
        
        if (!$imagenDesencriptada) {
            abort(404);
        }

        // Devolver como respuesta de imagen
        return response($imagenDesencriptada)
            ->header('Content-Type', $foto->tipo_archivo)
            ->header('Content-Length', strlen($imagenDesencriptada))
            ->header('Cache-Control', 'private, max-age=3600')
            ->header('Content-Disposition', 'inline; filename="' . $foto->nombre_archivo . '"');
    }

    /**
     * Descargar foto
     */
    public function descargarFoto($idFoto)
    {
        $foto = CustodiaFoto::find($idFoto);
        
        if (!$foto) {
            abort(404);
        }

        $imagenDesencriptada = $foto->imagen;
        
        if (!$imagenDesencriptada) {
            abort(404);
        }

        return response($imagenDesencriptada)
            ->header('Content-Type', $foto->tipo_archivo)
            ->header('Content-Length', strlen($imagenDesencriptada))
            ->header('Content-Disposition', 'attachment; filename="' . $foto->nombre_archivo . '"');
    }

    /**
     * Eliminar foto
     */
    public function eliminarFoto($idFoto)
    {
        $foto = CustodiaFoto::find($idFoto);
        
        if (!$foto) {
            return response()->json(['success' => false, 'message' => 'Foto no encontrada'], 404);
        }
        
        // Verificar permisos (opcional)
        if (auth()->id() !== $foto->uploaded_by && !auth()->user()->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'No tienes permisos para eliminar esta foto'], 403);
        }
        
        // Eliminar registro (la imagen se elimina automáticamente de la BD)
        $foto->delete();
        
        return response()->json(['success' => true, 'message' => 'Foto eliminada correctamente']);
    }

    /**
     * Verificar integridad de una foto
     */
    public function verificarIntegridad($idFoto)
    {
        $foto = CustodiaFoto::find($idFoto);
        
        if (!$foto) {
            return response()->json(['success' => false, 'message' => 'Foto no encontrada'], 404);
        }

        $imagenDesencriptada = $foto->imagen;
        $hashActual = hash('sha256', $imagenDesencriptada);
        $integro = ($hashActual === $foto->hash_archivo);

        return response()->json([
            'success' => true,
            'integro' => $integro,
            'hash_original' => $foto->hash_archivo,
            'hash_actual' => $hashActual
        ]);
    }
}
