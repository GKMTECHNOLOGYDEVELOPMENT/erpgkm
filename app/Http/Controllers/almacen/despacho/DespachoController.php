<?php

namespace App\Http\Controllers\almacen\despacho;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\Cliente;
use App\Models\Despacho;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DespachoController extends Controller
{

    public function index()
    {
        $despachos = Despacho::with('cliente')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($despacho) {
                return [
                    'id' => $despacho->id,
                    'numero' => $despacho->numero,
                    'tipo_guia' => $despacho->tipo_guia,
                    'documento' => $despacho->documento,
                    'cliente_nombre' => $despacho->cliente ? $despacho->cliente->nombre : 'Cliente no encontrado',
                    'fecha_entrega' => $despacho->fecha_entrega,
                    'total' => $despacho->total,
                    'estado' => $despacho->estado,
                    'created_at' => $despacho->created_at,
                    'updated_at' => $despacho->updated_at,
                ];
            });

        return view('almacen.despacho.index', compact('despachos'));
    }
    public function create()
    {
        return view('almacen.despacho.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validación más estricta
            $validated = $request->validate([
                'tipo_guia' => 'required|string|max:50',
                'numero' => 'required|string|max:20|unique:despachos,numero',
                'documento' => 'required|in:guia,factura',
                'fecha_entrega' => 'required|date|after_or_equal:today',
                'fecha_traslado' => 'required|date|after_or_equal:today',

                // Dirección partida
                'direccion_partida' => 'required|string|max:255',
                'dpto_partida' => 'required|string|max:50',
                'provincia_partida' => 'required|string|max:50',
                'distrito_partida' => 'required|string|max:50',

                // Dirección llegada
                'direccion_llegada' => 'required|string|max:255',
                'dpto_llegada' => 'required|string|max:50',
                'provincia_llegada' => 'required|string|max:50',
                'distrito_llegada' => 'required|string|max:50',

                // Cliente y transporte
                'cliente_id' => 'required|exists:cliente,idCliente',
                'modo_traslado' => 'required|in:publico,privado',
                'vendedor_id' => 'required|exists:usuarios,idUsuario',
                'conductor_id' => 'required|exists:usuarios,idUsuario',
                'trasbordo' => 'required|in:si,no',
                'condiciones' => 'required|in:contado,contrato',
                'tipo_traslado' => 'required|string|max:100',

                // Totales
                'subtotal_hidden' => 'required|numeric|min:0',
                'igv_hidden' => 'required|numeric|min:0',
                'total_hidden' => 'required|numeric|min:0',
                'articulos' => 'required|string',
            ]);

            // Validar que tipo_guia no sea duplicado (opcional, si quieres esta validación)
            $guiaExistente = Despacho::where('tipo_guia', $request->tipo_guia)
                ->where('numero', $request->numero)
                ->first();

            if ($guiaExistente) {
                return response()->json([
                    'success' => false,
                    'message' => 'La combinación de Tipo Guía y Número ya existe'
                ], 422);
            }

            // Decodificar artículos
            $articulosData = json_decode($request->articulos, true);

            if (!is_array($articulosData) || count($articulosData) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe agregar al menos un artículo'
                ], 422);
            }

            // Crear despacho
            $despacho = Despacho::create([
                'tipo_guia' => $request->tipo_guia,
                'numero' => $request->numero,
                'documento' => $request->documento,
                'fecha_entrega' => $request->fecha_entrega,
                'fecha_traslado' => $request->fecha_traslado,

                'direccion_partida' => $request->direccion_partida,
                'departamento_partida' => $request->dpto_partida,
                'provincia_partida' => $request->provincia_partida,
                'distrito_partida' => $request->distrito_partida,

                'direccion_llegada' => $request->direccion_llegada,
                'departamento_llegada' => $request->dpto_llegada,
                'provincia_llegada' => $request->provincia_llegada,
                'distrito_llegada' => $request->distrito_llegada,

                'cliente_id' => $request->cliente_id,
                'modo_traslado' => $request->modo_traslado,
                'vendedor_id' => $request->vendedor_id,
                'conductor_id' => $request->conductor_id,
                'trasbordo' => $request->trasbordo,
                'condiciones' => $request->condiciones,
                'tipo_traslado' => $request->tipo_traslado,

                'subtotal' => $request->subtotal_hidden,
                'igv' => $request->igv_hidden,
                'total' => $request->total_hidden,
                'estado' => 1,
            ]);

            // Crear artículos del despacho
            foreach ($articulosData as $articuloData) {
                $despacho->articulos()->create([
                    'articulo_id' => $this->getArticuloIdByCodigo($articuloData['codigo']),
                    'codigo' => $articuloData['codigo'],
                    'descripcion' => $articuloData['descripcion'],
                    'stock' => $articuloData['stock'],
                    'unidad' => $articuloData['unidad'],
                    'precio' => $articuloData['precio'],
                    'cantidad' => $articuloData['cantidad'],
                    'subtotal' => $articuloData['precio'] * $articuloData['cantidad'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Despacho creado exitosamente',
                'despacho_id' => $despacho->id
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear despacho: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el despacho: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getArticuloIdByCodigo($codigo)
    {
        $articulo = Articulo::where('codigo_barras', $codigo)
            ->orWhere('sku', $codigo)
            ->orWhere('nombre', $codigo)
            ->first();

        return $articulo ? $articulo->idArticulos : null;
    }

    // API Methods
    public function getClientes()
    {
        try {
            $clientes = Cliente::where('estado', 1)
                ->select('idCliente as id', 'nombre', 'documento')
                ->get()
                ->map(function ($cliente) {
                    return [
                        'id' => $cliente->id,
                        'text' => $cliente->nombre . ' - ' . $cliente->documento,
                        'nombre' => $cliente->nombre,
                        'documento' => $cliente->documento
                    ];
                });

            return response()->json($clientes);
        } catch (\Exception $e) {
            Log::error('Error cargando clientes: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    public function getUsuarios()
    {
        try {
            $usuarios = Usuario::where('estado', 1)
                ->select(
                    'idUsuario as id',
                    DB::raw("CONCAT(Nombre, ' ', apellidoPaterno, ' ', apellidoMaterno) as text")
                )
                ->get();

            return response()->json($usuarios);
        } catch (\Exception $e) {
            Log::error('Error cargando usuarios: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    public function getArticulos()
    {
        try {
            $articulos = Articulo::where('estado', 1)
                ->select(
                    'idArticulos as id',
                    'nombre as text',
                    'codigo_barras as codigo',
                    'precio_venta as precio',
                    'stock_total as stock'
                )
                ->get()
                ->map(function ($articulo) {
                    return [
                        'id' => $articulo->id,
                        'text' => $articulo->codigo . ' - ' . $articulo->text . ' (Stock: ' . $articulo->stock . ')',
                        'codigo' => $articulo->codigo,
                        'precio' => $articulo->precio,
                        'stock' => $articulo->stock,
                        'descripcion' => $articulo->text
                    ];
                });

            return response()->json($articulos);
        } catch (\Exception $e) {
            Log::error('Error cargando artículos: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    public function getDepartamentos()
    {
        $departamentos = [
            'Amazonas',
            'Ancash',
            'Apurímac',
            'Arequipa',
            'Ayacucho',
            'Cajamarca',
            'Callao',
            'Cusco',
            'Huancavelica',
            'Huánuco',
            'Ica',
            'Junín',
            'La Libertad',
            'Lambayeque',
            'Lima',
            'Loreto',
            'Madre de Dios',
            'Moquegua',
            'Pasco',
            'Piura',
            'Puno',
            'San Martín',
            'Tacna',
            'Tumbes',
            'Ucayali'
        ];

        return response()->json($departamentos);
    }


    public function show($id)
    {
        $despacho = Despacho::with(['cliente', 'vendedor', 'conductor', 'articulos'])
            ->findOrFail($id);

        return view('almacen.despacho.show', compact('despacho'));
    }

    public function edit($id)
    {
        $despacho = Despacho::with(['cliente', 'vendedor', 'conductor', 'articulos'])
            ->findOrFail($id);

        $clientes = Cliente::where('estado', 1)->get();
        $usuarios = Usuario::where('estado', 1)->get();
        $articulos = Articulo::where('estado', 1)->get();

        return view('almacen.despacho.edit', compact('despacho', 'clientes', 'usuarios', 'articulos'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $despacho = Despacho::findOrFail($id);

            // Validación actualizada para coincidir con store
            $validated = $request->validate([
                'tipo_guia' => 'required|string|max:50',
                'numero' => 'required|string|max:20|unique:despachos,numero,' . $despacho->id,
                'documento' => 'required|in:guia,factura',
                'fecha_entrega' => 'required|date|after_or_equal:today',
                'fecha_traslado' => 'required|date|after_or_equal:today',

                // Dirección partida
                'direccion_partida' => 'required|string|max:255',
                'dpto_partida' => 'required|string|max:50',
                'provincia_partida' => 'required|string|max:50',
                'distrito_partida' => 'required|string|max:50',

                // Dirección llegada
                'direccion_llegada' => 'required|string|max:255',
                'dpto_llegada' => 'required|string|max:50',
                'provincia_llegada' => 'required|string|max:50',
                'distrito_llegada' => 'required|string|max:50',

                // Cliente y transporte
                'cliente_id' => 'required|exists:cliente,idCliente',
                'modo_traslado' => 'required|in:publico,privado',
                'vendedor_id' => 'required|exists:usuarios,idUsuario',
                'conductor_id' => 'required|exists:usuarios,idUsuario',
                'trasbordo' => 'required|in:si,no',
                'condiciones' => 'required|in:contado,contrato',
                'tipo_traslado' => 'required|string|max:100',

                // Totales
                'subtotal_hidden' => 'required|numeric|min:0',
                'igv_hidden' => 'required|numeric|min:0',
                'total_hidden' => 'required|numeric|min:0',
                'articulos' => 'required|string',
            ]);

            // Validar que tipo_guia no sea duplicado (excluyendo el actual)
            $guiaExistente = Despacho::where('tipo_guia', $request->tipo_guia)
                ->where('numero', $request->numero)
                ->where('id', '!=', $despacho->id)
                ->first();

            if ($guiaExistente) {
                return response()->json([
                    'success' => false,
                    'message' => 'La combinación de Tipo Guía y Número ya existe'
                ], 422);
            }

            // Decodificar artículos
            $articulosData = json_decode($request->articulos, true);

            if (!is_array($articulosData) || count($articulosData) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe agregar al menos un artículo'
                ], 422);
            }

            // Actualizar despacho
            $despacho->update([
                'tipo_guia' => $request->tipo_guia,
                'numero' => $request->numero,
                'documento' => $request->documento,
                'fecha_entrega' => $request->fecha_entrega,
                'fecha_traslado' => $request->fecha_traslado,

                'direccion_partida' => $request->direccion_partida,
                'departamento_partida' => $request->dpto_partida,
                'provincia_partida' => $request->provincia_partida,
                'distrito_partida' => $request->distrito_partida,

                'direccion_llegada' => $request->direccion_llegada,
                'departamento_llegada' => $request->dpto_llegada,
                'provincia_llegada' => $request->provincia_llegada,
                'distrito_llegada' => $request->distrito_llegada,

                'cliente_id' => $request->cliente_id,
                'modo_traslado' => $request->modo_traslado,
                'vendedor_id' => $request->vendedor_id,
                'conductor_id' => $request->conductor_id,
                'trasbordo' => $request->trasbordo,
                'condiciones' => $request->condiciones,
                'tipo_traslado' => $request->tipo_traslado,

                'subtotal' => $request->subtotal_hidden,
                'igv' => $request->igv_hidden,
                'total' => $request->total_hidden,
            ]);

            // Eliminar artículos existentes
            $despacho->articulos()->delete();

            // Crear nuevos artículos
            foreach ($articulosData as $articuloData) {
                $despacho->articulos()->create([
                    'articulo_id' => $this->getArticuloIdByCodigo($articuloData['codigo']),
                    'codigo' => $articuloData['codigo'],
                    'descripcion' => $articuloData['descripcion'],
                    'stock' => $articuloData['stock'],
                    'unidad' => $articuloData['unidad'],
                    'precio' => $articuloData['precio'],
                    'cantidad' => $articuloData['cantidad'],
                    'subtotal' => $articuloData['precio'] * $articuloData['cantidad'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Despacho actualizado exitosamente',
                'despacho_id' => $despacho->id
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar despacho: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el despacho: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $despacho = Despacho::findOrFail($id);

            // Eliminar artículos primero
            $despacho->articulos()->delete();

            // Eliminar despacho
            $despacho->delete();

            DB::commit();

            return redirect()->route('despacho.index')
                ->with('success', 'Despacho eliminado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('despacho.index')
                ->with('error', 'Error al eliminar el despacho: ' . $e->getMessage());
        }
    }

    public function cambiarEstado(Request $request, $id)
    {
        try {
            $despacho = Despacho::findOrFail($id);

            $request->validate([
                'estado' => 'required|in:pendiente,en_proceso,completado,cancelado'
            ]);

            $despacho->update([
                'estado' => $request->estado
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getSeriesDisponibles($articuloId)
{
    try {
        $series = DB::table('articulo_series')
            ->where('articulo_id', $articuloId)
            ->where('estado', 'activo')
            ->whereNotIn('idArticuloSerie', function($query) {
                $query->select('articulo_serie_id')
                      ->from('despacho_detalles_series');
            })
            ->select('idArticuloSerie', 'numero_serie', 'estado')
            ->get();

        return response()->json($series);
    } catch (\Exception $e) {
        return response()->json([], 500);
    }
}
}
