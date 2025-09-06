<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\Solicitud;
use App\Models\SolicitudArticulo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class SolicitudarticuloController extends Controller
{

    public function index()
    {
        $query = DB::table('solicitud')
            ->join('usuarios as encargado', 'solicitud.idEncargado', '=', 'encargado.idUsuario')
            ->join('usuarios as solicitante', 'solicitud.idUsuariosoli', '=', 'solicitante.idUsuario')
            ->select(
                'solicitud.*',
                DB::raw("CONCAT(encargado.Nombre, ' ', encargado.apellidoPaterno) as nombre_encargado"),
                DB::raw("CONCAT(solicitante.Nombre, ' ', solicitante.apellidoPaterno) as nombre_solicitante")
            )
            ->orderBy('solicitud.fecharequerida', 'desc');

        // Filtro por estado
        if (request('estado')) {
            $query->where('solicitud.estado', request('estado'));
        }

        // Filtro por urgencia
        if (request('urgencia')) {
            $query->where('solicitud.nivelUrgencia', request('urgencia'));
        }

        // Filtro por búsqueda
        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('solicitud.codigoSolicitud', 'like', "%$search%")
                    ->orWhere('solicitud.comentario', 'like', "%$search%")
                    ->orWhere('encargado.Nombre', 'like', "%$search%")
                    ->orWhere('encargado.apellidoPaterno', 'like', "%$search%")
                    ->orWhere('solicitante.Nombre', 'like', "%$search%")
                    ->orWhere('solicitante.apellidoPaterno', 'like', "%$search%");
            });
        }

        $solicitudes = $query->paginate(10);

        return view("solicitud.solicitudarticulo.index", compact('solicitudes'));
    }


    public function create()
    {
        $usuario = auth()->user()->load('tipoArea');
        $areas = \App\Models\TipoArea::all();

        $articulos = \App\Models\Articulo::with('tipoArticulo') // 👈 Aquí cargamos la relación
            ->where('estado', 1)
            ->get();

        return view("solicitud.solicitudarticulo.create", [
            'usuario' => $usuario,
            'areas' => $areas,
            'articulos' => $articulos
        ]);
    }


    public function store(Request $request)
    {
        try {
            // Validar los datos del formulario
            $validated = $request->validate([
                'codigoSolicitud' => 'required|string|max:255|unique:solicitud,codigoSolicitud',
                'nombre' => 'required|string|max:255',
                'departamento' => 'required|string|max:255',
                'articulo_id' => 'required|exists:articulos,idArticulos',
                'cantidad' => 'required|integer|min:1',
                'descripcion' => 'nullable|string',
                'urgencia' => 'required|in:baja,media,alta',
                'fecha_requerida' => 'required|date',
                'notas' => 'nullable|string',
                'articulos_adicionales' => 'nullable|string' // JSON string
            ]);

            // Convertir urgencia a nivel numérico
            $nivelUrgencia = 1; // baja por defecto
            if ($validated['urgencia'] === 'media') {
                $nivelUrgencia = 2;
            } elseif ($validated['urgencia'] === 'alta') {
                $nivelUrgencia = 3;
            }

            // Calcular días restantes
            $fechaRequerida = Carbon::parse($validated['fecha_requerida']);
            $diasRestantes = now()->diffInDays($fechaRequerida, false);

            // Crear la solicitud
            $solicitud = Solicitud::create([
                'codigoSolicitud' => $validated['codigoSolicitud'],
                'diasrestantes' => $diasRestantes > 0 ? $diasRestantes : 0,
                'estado' => 'pendiente',
                'idTipoSolicitud' => 1, // Asume que 1 es para solicitud de artículos
                'idEncargado' => 1, // Se asignará luego
                'comentario' => $validated['notas'],
                'fecharequerida' => $fechaRequerida,
                'idUsuariosoli' => Auth::id(),
                'nivelUrgencia' => $nivelUrgencia,
                'dias' => $diasRestantes > 0 ? $diasRestantes : 0,
                'idTenico' => null // Se asignará luego
            ]);

            // Guardar el artículo principal
            SolicitudArticulo::create([
                'idSolicitud' => $solicitud->idSolicitud,
                'codigoSolicitud' => $validated['codigoSolicitud'],
                'idArticulo' => $validated['articulo_id'],
                'cantidad' => $validated['cantidad'],
                'descripcion' => $validated['descripcion']
            ]);

            // Procesar artículos adicionales si existen
            if ($request->has('articulos_adicionales') && !empty($request->articulos_adicionales)) {
                $articulosAdicionales = json_decode($request->articulos_adicionales, true);

                foreach ($articulosAdicionales as $articulo) {
                    SolicitudArticulo::create([
                        'idSolicitud' => $solicitud->idSolicitud,
                        'codigoSolicitud' => $validated['codigoSolicitud'],
                        'idArticulo' => $articulo['articulo_id'],
                        'cantidad' => $articulo['cantidad'],
                        'descripcion' => $articulo['descripcion']
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Solicitud enviada correctamente. Código: ' . $validated['codigoSolicitud'],
                'codigo' => $validated['codigoSolicitud']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }



    public function show($id)
    {
        $solicitud = Solicitud::with([
            'encargado',
            'solicitante',
            'articulos'
        ])->findOrFail($id);


        return view('solicitud.solicitudarticulo.show', compact('solicitud'));
    }

    public function opciones($id)
    {
        $solicitud = Solicitud::with([
            'encargado',
            'solicitante',
            'articulos'
        ])->findOrFail($id);


        return view('solicitud.solicitudarticulo.opciones', compact('solicitud'));
    }


    public function edit($id)
    {

        $solicitud  = SolicitudArticulo::findOrFail($id);

        return view('solicitud.solicitudarticulo.edit', compact('solicitud'));
    }
}
