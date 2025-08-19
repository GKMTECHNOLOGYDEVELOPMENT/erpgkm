<?php

namespace App\Http\Controllers\areacomercial;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Contacto;
use App\Models\Contactos;
use App\Models\Empresa;
use App\Models\FuenteCaptacion;
use App\Models\NivelDecision;
use App\Models\Project;
use App\Models\Seguimiento;
use App\Models\SeleccionarSeguimiento;
use App\Models\Servicio;
use App\Models\Status;
use App\Models\Task;
use App\Models\Tipoarea;
use App\Models\Tipodocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClienteSeguimientoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $clientes = Cliente::with(['servicio', 'tipoDocumento'])
                ->orderBy('idCliente', 'desc');

            return datatables()->eloquent($clientes)
                ->addColumn('tipo_prospecto', fn($row) => $row->servicio->nombre ?? '-')
                ->addColumn('nombre_prospecto', fn($row) => $row->nombre) // ajusta si el campo se llama diferente
                ->addColumn('fechaIngreso', fn($row) => $row->created_at->format('Y-m-d'))
                ->toJson();
        }

        return view('areacomercial.index');
    }

    public function tabsseguimiento()
    {

        return view('areacomercial.tabsseguimiento');
    }

    public function catalogos()
    {
        return response()->json([
            'fuentes' => FuenteCaptacion::select('id', 'nombre')->get(),
            'niveles' => NivelDecision::select('id', 'nombre')->get(),
            'documentos' => TipoDocumento::select('idTipoDocumento as id', 'nombre')->get(),
        ]);
    }



    public function show(string $id)
    {
        $cliente = Cliente::with(['servicio', 'tipoDocumento'])->find($id);

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $cliente
        ]);
    }


    public function create()
    {
        $servicios = Servicio::all();
        // Asume que tienes un modelo TipoDocumento
        $tipoDocumentos = Tipodocumento::all();
        // Aquí puedes implementar la lógica para crear un nuevo seguimiento de cliente
        return view('areacomercial.create', compact('servicios', 'tipoDocumentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'required|string|max:255|unique:cliente,documento',
            'telefono' => 'required|string|max:255|unique:cliente,telefono',
            'email' => 'required|email|max:255|unique:cliente,email',
            'idTipoDocumento' => 'required|exists:tipodocumento,idTipoDocumento',
            'idservicio' => 'required|exists:servicios,idServicios',

        ]);

        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            'documento' => $request->documento,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'fecha_registro' => now(),
            'idTipoDocumento' => $request->idTipoDocumento,
            'idservicio' => $request->idservicio,
            'estado' => 1, // Asumiendo que 1 es activo
            'idtipoarea' => 11,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cliente registrado correctamente',
            'cliente' => $cliente
        ]);
    }


    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        $servicios = Servicio::all();
        $tipoDocumentos = Tipodocumento::all();

        return view('areacomercial.edit', compact('cliente', 'servicios', 'tipoDocumentos'));
    }




    public function editSeguimiento($id)
{
    Log::info('Intentando editar seguimiento ID: '.$id);
    
    try {
        $seguimiento = Seguimiento::findOrFail($id);
        Log::info('Seguimiento encontrado:', $seguimiento->toArray());

        // Obtener idpersona desde la tabla seleccionarseguimiento
        $seleccion = SeleccionarSeguimiento::where('idseguimiento', $seguimiento->idSeguimiento)->first();
        $idPersona = $seleccion?->idpersona ?? null; // usar null si no existe

        if ($seguimiento->tipoRegistro == 1) {
            $empresa = Empresa::findOrFail($seguimiento->idEmpresa);
            $fuentes = FuenteCaptacion::all();

            return view('areacomercial.seguimiento', [
                'seguimiento' => $seguimiento,
                'empresa' => $empresa,
                'fuentes' => $fuentes,
                'documentos' => TipoDocumento::all(),
                'niveles' => NivelDecision::all(),
                'idPersona' => $idPersona, // 👈 Pasar a la vista
            ]);
        } else {
            $contacto = Contactos::findOrFail($seguimiento->idContacto);

            return view('areacomercial.seguimiento', [
                'seguimiento' => $seguimiento,
                'contacto' => $contacto,
                'fuentes' => FuenteCaptacion::all(),
                'documentos' => TipoDocumento::all(),
                'niveles' => NivelDecision::all(),
                'idPersona' => $idPersona, // 👈 Pasar a la vista
            ]);
        }
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error('Error al encontrar modelo: '.$e->getMessage());
        abort(404, 'El recurso solicitado no fue encontrado');
    } catch (\Exception $e) {
        Log::error('Error inesperado: '.$e->getMessage());
        abort(500, 'Ocurrió un error inesperado');
    }
}

    public function editTab($id, Request $request)
    {
        try {
            $seguimiento = Seguimiento::findOrFail($id);
            $tab = $request->query('tab');

            switch ($tab) {
                case 'empresa':
                    return $this->handleEmpresaTab($seguimiento);

                case 'contacto':
                    return $this->handleContactoTab($seguimiento);

                case 'proyectos':
                    return $this->handleProyectosTab($seguimiento);

                case 'cronograma':
                    return $this->handleCronogramaTab($seguimiento);

                case 'observaciones':
                    return $this->handleObservacionesTab($seguimiento);

                default:
                    throw new \Exception("Tab no válido");
            }
        } catch (\Exception $e) {
            Log::error("Error en editTab: " . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'html' => view('areacomercial.partials.error-tab', [
                    'message' => $e->getMessage()
                ])->render()
            ], 500);
        }
    }

    // Métodos para manejar cada tab
    private function handleEmpresaTab($seguimiento)
    {
        try {
            if (!$seguimiento->idEmpresa) {
                return response()->json([
                    'html' => $this->renderNoDataView('empresa'),
                    'error' => 'No existe empresa asociada'
                ]);
            }

            $empresa = Empresa::find($seguimiento->idEmpresa);
            if (!$empresa) {
                return response()->json([
                    'html' => $this->renderNoDataView('empresa'),
                    'error' => 'La empresa asociada no existe'
                ]);
            }

            $html = view('areacomercial.partials.empresa-tab', [
                'seguimiento' => $seguimiento,
                'empresa' => $empresa,
                'fuentes' => FuenteCaptacion::all()
            ])->render();

            return response()->json([
                'html' => $html
            ]);
        } catch (\Exception $e) {
            Log::error("Error en handleEmpresaTab: " . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'html' => $this->renderErrorView($e->getMessage())
            ], 500);
        }
    }

    private function handleContactoTab($seguimiento)
    {
        try {
            if (!$seguimiento->idContacto) {
                return response()->json([
                    'html' => $this->renderNoDataView('contacto'),
                    'error' => 'No existe contacto asociado a este seguimiento'
                ]);
            }

            $contacto = Contactos::find($seguimiento->idContacto);

            if (!$contacto) {
                return response()->json([
                    'html' => $this->renderNoDataView('contacto'),
                    'error' => 'El contacto asociado no existe'
                ]);
            }

            $html = view('areacomercial.partials.contacto-tab', [
                'seguimiento' => $seguimiento,
                'contacto' => $contacto,
                'documentos' => TipoDocumento::all(),
                'niveles' => NivelDecision::all()
            ])->render();

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            Log::error("Error en handleContactoTab: " . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'html' => $this->renderErrorView($e->getMessage())
            ], 500);
        }
    }

    private function handleProyectosTab($seguimiento)
    {
        $projects = Project::with('tasks')->get();

        return response()->json([
            'html' => view('areacomercial.partials.proyectos-tab', [
                'seguimiento' => $seguimiento,
                'projects' => $projects
            ])->render()
        ]);
    }

    private function handleCronogramaTab($seguimiento)
    {
        $actividades = $seguimiento->actividades ?? []; // Asumiendo que hay una relación

        return response()->json([
            'html' => view('areacomercial.partials.cronograma-tab', [
                'seguimiento' => $seguimiento,
                'actividades' => $actividades
            ])->render()
        ]);
    }
    private function handleObservacionesTab($seguimiento)
    {
        try {
            $user = auth()->user();

            // Asegúrate de cargar las relaciones
            $notes = $user->notes()->with(['tag', 'user'])->latest()->get();
            $tags = $user->tags;

            return response()->json([
                'html' => view('areacomercial.partials.observaciones-tab', [
                    'seguimiento' => $seguimiento,
                    'notes' => $notes,
                    'tags' => $tags
                ])->render(),
                'notes' => $notes->map(function ($note) {
                    return [
                        'id' => $note->id,
                        'title' => $note->title,
                        'description' => $note->description,
                        'is_favorite' => $note->is_favorite,
                        'tag' => $note->tag ? $note->tag->name : null,
                        'tag_color' => $note->tag ? $note->tag->color : null,
                        'date' => $note->created_at->format('M d, Y'),
                        'user' => $note->user->name,
                    ];
                }),
                'tags' => $tags
            ]);
        } catch (\Exception $e) {
            Log::error("Error en handleObservacionesTab: " . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'html' => view('areacomercial.partials.error-tab', [
                    'message' => 'Error al cargar las observaciones'
                ])->render()
            ], 500);
        }
    }
    // Métodos auxiliares (renderNoDataView, renderErrorView) se mantienen igual
    // Método para renderizar vista cuando no hay datos
    private function renderNoDataView($type)
    {
        $title = $type === 'empresa' ? 'Empresa' : 'Contacto';
        $icon = $type === 'empresa' ?
            '<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-yellow-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>' :
            '<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-yellow-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>';

        return view('areacomercial.partials.no-data-tab', [
            'icon' => $icon,
            'title' => $title,
            'message' => "No hay $title asociado a este seguimiento",
            'showCreateButton' => true,
            'type' => $type
        ])->render();
    }

    // Método para renderizar vista de error
    private function renderErrorView($message)
    {
        return view('areacomercial.partials.error-tab', [
            'message' => $message
        ])->render();
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cliente', 'documento')->ignore($cliente->idCliente, 'idCliente')
            ],
            'telefono' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cliente', 'telefono')->ignore($cliente->idCliente, 'idCliente')
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('cliente', 'email')->ignore($cliente->idCliente, 'idCliente')
            ],
            'idTipoDocumento' => 'required|exists:tipodocumento,idTipoDocumento',
            'idservicio' => 'required|exists:servicios,idServicios',
        ]);

        $cliente->update([
            'nombre' => $request->nombre,
            'documento' => $request->documento,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'idTipoDocumento' => $request->idTipoDocumento,
            'idservicio' => $request->idservicio,
            // Puedes actualizar otros campos según sea necesario
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cliente actualizado correctamente',
            'cliente' => $cliente
        ]);
    }
    public function destroy(string $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        $cliente->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cliente eliminado correctamente'
        ]);
    }
    public function getSeguimientos(Request $request)
    {
        $seguimientos = Seguimiento::with(['empresa', 'contacto']);

        return datatables()->eloquent($seguimientos)
            ->addColumn('tipo_prospecto', function ($s) {
                if (!is_null($s->idEmpresa) && $s->empresa) return 'Empresa';
                if (!is_null($s->idContacto) && $s->contacto) return 'Contacto';
                return '-';
            })
            ->addColumn('nombre_prospecto', function ($s) {
                if (!is_null($s->idEmpresa) && $s->empresa) return $s->empresa->nombre_razon_social ?? '-';
                if (!is_null($s->idContacto) && $s->contacto) return $s->contacto->nombre_completo ?? '-';
                return '-';
            })
            ->addColumn('documento', function ($s) {
                if (!is_null($s->idEmpresa) && $s->empresa) return $s->empresa->ruc ?? '-';
                if (!is_null($s->idContacto) && $s->contacto) return $s->contacto->numero_documento ?? '-';
                return '-';
            })
            ->addColumn('usuario', function ($s) {
                return $s->usuario
                    ? $s->usuario->Nombre . ' ' . $s->usuario->apellidoPaterno
                    : '-';
            })
            ->addColumn('fechaIngreso', fn($s) => $s->fechaIngreso ? date('Y-m-d', strtotime($s->fechaIngreso)) : '-')
            ->addColumn('acciones', function ($s) {
                $url = route('seguimiento.edit', ['id' => $s->idSeguimiento]);

                return '
        <a href="' . $url . '" 
            class="btn-seguir flex items-center justify-center text-blue-600 hover:text-blue-800 transition-colors" 
            title="Dar Seguimiento">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M12 2.25a.75.75 0 01.75.75v1.51a7.5 7.5 0 016.74 6.74h1.51a.75.75 0 010 1.5h-1.51a7.5 7.5 0 01-6.74 6.74v1.51a.75.75 0 01-1.5 0v-1.51a7.5 7.5 0 01-6.74-6.74H2.25a.75.75 0 010-1.5h1.51a7.5 7.5 0 016.74-6.74V3a.75.75 0 01.75-.75zm0 4.5a6 6 0 100 12 6 6 0 000-12zm0 3.75a2.25 2.25 0 110 4.5 2.25 2.25 0 010-4.5z" clip-rule="evenodd" />
            </svg>
        </a>';
            })
            ->rawColumns(['acciones']) // permite que el botón se renderice con HTML
            ->toJson();
    }
}
