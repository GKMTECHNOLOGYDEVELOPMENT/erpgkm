<?php

namespace App\Http\Controllers\almacen\productos;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\ArticuloModelo;
use App\Models\Kardex;
use App\Models\Modelo;
use App\Models\Moneda;
use App\Models\Tipoarea;
use App\Models\Tipoarticulo;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ArticulosController extends Controller
{
    public function index()
    {
        $unidades = Unidad::all();
        $tiposArticulo = Tipoarticulo::all();
        $modelos = Modelo::with(['marca', 'categoria'])
            ->where('estado', 1)
            ->get();

        $monedas = Moneda::all();
        // Retorna la vista para artículos
        return view('almacen.productos.articulos.index', compact('unidades', 'tiposArticulo', 'modelos', 'monedas'));
    }



    public function storeModal(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validación de datos adaptada para el modal
            $validatedData = $request->validate([
                'codigo_barras' => 'nullable|string|max:255|unique:articulos,codigo_barras',
                'sku' => 'nullable|string|max:255|unique:articulos,sku',
                'codigo_repuesto' => 'required|string|max:255|unique:articulos,codigo_repuesto',
                'nombre' => 'required|string|max:255',
                'stock_total' => 'required|integer|min:0',
                'stock_minimo' => 'required|integer|min:0',
                'precio_compra' => 'required|numeric|min:0',
                'precio_venta' => 'required|numeric|min:0',
                'pulgadas' => 'nullable|string|max:11',
                'idModelo' => 'required|array',
                'idModelo.*' => 'integer|exists:modelo,idModelo',
                'idTipoArea' => 'required|integer|exists:tipo_area,id',
                'idsubcategoria' => 'required|integer|exists:subcategoria,idsubcategoria'
            ]);

            // Asignación de valores por defecto
            $dataArticulo = $validatedData;
            unset($dataArticulo['idModelo']); // Excluimos el array de modelos antes de crear el artículo

            $dataArticulo['estado'] = 1; // Activo por defecto
            $dataArticulo['idTipoArticulo'] = 2; // Tipo de artículo por defecto (repuesto)
            $dataArticulo['fecha_ingreso'] = now();
            $dataArticulo['moneda_compra'] = 1; // PEN por defecto
            $dataArticulo['moneda_venta'] = 1; // PEN por defecto

            // Crear el artículo
            $articulo = Articulo::create($dataArticulo);

            // Registrar movimiento inicial en el Kardex (solo si hay stock)
            if ($dataArticulo['stock_total'] > 0) {
                Kardex::create([
                    'fecha' => now(),
                    'idArticulo' => $articulo->idArticulos,
                    'unidades_entrada' => $dataArticulo['stock_total'],
                    'costo_unitario_entrada' => $dataArticulo['precio_compra'],
                    'unidades_salida' => 0,
                    'costo_unitario_salida' => 0,
                    'inventario_inicial' => $dataArticulo['stock_total'],
                    'inventario_actual' => $dataArticulo['stock_total'],
                    'costo_inventario' => $dataArticulo['stock_total'] * $dataArticulo['precio_compra']
                ]);
            }

            // Generar códigos de barras si existen
            $this->generarCodigosBarras($articulo, $dataArticulo);

            // Guardar modelos múltiples (relación muchos a muchos)
            foreach ($request->idModelo as $modeloId) {
                ArticuloModelo::create([
                    'articulo_id' => $articulo->idArticulos,
                    'modelo_id' => $modeloId,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Repuesto agregado correctamente',
                'repuesto' => $articulo,
                'modelos' => $request->idModelo
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el repuesto: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método auxiliar para generar códigos de barras
    private function generarCodigosBarras($articulo, $data)
    {
        $barcodeGenerator = new BarcodeGeneratorPNG();

        if (!empty($data['codigo_barras'])) {
            $barcode = $barcodeGenerator->getBarcode($data['codigo_barras'], BarcodeGeneratorPNG::TYPE_CODE_128);
            $articulo->update(['foto_codigobarras' => $barcode]);
        }

        if (!empty($data['sku'])) {
            $barcode = $barcodeGenerator->getBarcode($data['sku'], BarcodeGeneratorPNG::TYPE_CODE_128);
            $articulo->update(['fotosku' => $barcode]);
        }

        if (!empty($data['codigo_repuesto'])) {
            $barcode = $barcodeGenerator->getBarcode($data['codigo_repuesto'], BarcodeGeneratorPNG::TYPE_CODE_128);
            $articulo->update(['br-codigo-repuesto' => $barcode]);
        }
    }

    public function buscar(Request $request)
{
    $busqueda = $request->query('q');
    $tipoBusqueda = $request->query('tipo', 'texto');
    $page = (int) $request->query('page', 1);
    $perPage = (int) $request->query('perPage', 10);

    if ($tipoBusqueda === 'texto') {
        if (!$busqueda) {
            return response()->json([
                'existe' => false,
                'productos' => [],
                'total' => 0
            ]);
        }

        $query = DB::table('articulos')
            ->where('idTipoArticulo', 1)
            ->where('codigo_barras', $busqueda); // búsqueda exacta

        $total = $query->count();

        $productos = $query->select(
            'idArticulos',
            'codigo_barras',
            'codigo_repuesto',
            'nombre',
            'stock_total',
            'precio_compra',
            'precio_venta'
        )
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return response()->json([
            'existe' => $productos->count() > 0,
            'productos' => $productos,
            'total' => $total,
            'currentPage' => (int) $page,
            'perPage' => (int) $perPage,
        ]);
    }

    return response()->json(['existe' => false]);
}


    public function create()
    {
        // Obtener datos para los selects
        $unidades = Unidad::all();
        $tiposArticulo = Tipoarticulo::all();
        $modelos = Modelo::with(['marca', 'categoria'])
            ->where('estado', 1)
            ->where('producto', 1)
            ->get();
        $monedas = Moneda::all();

        // Retornar la vista con los datos necesarios
        return view('almacen.productos.articulos.create', compact('unidades', 'tiposArticulo', 'modelos', 'monedas'));
    }


    public function store(Request $request)
    {
        try {
            // Validación de datos
            $validatedData = $request->validate([
                'codigo_barras' => 'nullable|string|max:255',
                'nombre' => 'nullable|string|max:255',
                'stock_total' => 'nullable|integer',
                'stock_minimo' => 'nullable|integer',
                'moneda_compra' => 'nullable|integer',
                'moneda_venta' => 'nullable|integer',
                'precio_compra' => 'nullable|numeric',
                'precio_venta' => 'nullable|numeric',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'sku' => 'nullable|string|max:255',
                'peso' => 'nullable|numeric',
                'mostrarWeb' => 'nullable|string|max:255',
                'estado' => 'nullable|boolean',
                'idUnidad' => 'nullable|integer',
                'idTipoArticulo' => 'nullable|integer',
                'idModelo' => 'nullable|integer',
            ]);

            // Asignación de valores por defecto
            $dataArticulo = $validatedData;
            $dataArticulo['estado'] = $dataArticulo['estado'] ?? 1;
            $dataArticulo['stock_total'] = $dataArticulo['stock_total'] ?? 0;
            $dataArticulo['precio_compra'] = $dataArticulo['precio_compra'] ?? 0;

            // Crear el artículo
            $articulo = Articulo::create($dataArticulo);

            // Registrar movimiento inicial en el Kardex
            if ($dataArticulo['stock_total'] > 0) {
                Kardex::create([
                    'fecha' => now(),
                    'idArticulo' => $articulo->idArticulos,
                    'unidades_entrada' => $dataArticulo['stock_total'],
                    'costo_unitario_entrada' => $dataArticulo['precio_compra'],
                    'unidades_salida' => 0,
                    'costo_unitario_salida' => 0,
                    'inventario_inicial' => 0,
                    'inventario_actual' => $dataArticulo['stock_total'],
                    'costo_inventario' => $dataArticulo['stock_total'] * $dataArticulo['precio_compra']
                ]);
            }

            // Generar y guardar el código de barras para 'codigo_barras' como binario
            if (!empty($dataArticulo['codigo_barras'])) {
                $barcodeGenerator = new BarcodeGeneratorPNG();
                $barcode = $barcodeGenerator->getBarcode($dataArticulo['codigo_barras'], BarcodeGeneratorPNG::TYPE_CODE_128);
                $fotoCodigobarrasBinario = $barcode; // El código de barras ya es binario
                $articulo->update(['foto_codigobarras' => $fotoCodigobarrasBinario]);
            }

            // Generar y guardar el código de barras para 'sku' como binario
            if (!empty($dataArticulo['sku'])) {
                $barcodeGenerator = new BarcodeGeneratorPNG();
                $barcode = $barcodeGenerator->getBarcode($dataArticulo['sku'], BarcodeGeneratorPNG::TYPE_CODE_128);
                $fotoSkuBinario = $barcode; // El código de barras ya es binario
                $articulo->update(['fotosku' => $fotoSkuBinario]);
            }

            // Subir la foto del artículo y convertirla a binario
            if ($request->hasFile('foto')) {
                $photoPath = $request->file('foto')->getRealPath(); // Obtener la ruta del archivo
                $photoData = file_get_contents($photoPath); // Leer el archivo como binario
                $articulo->update(['foto' => $photoData]); // Guardar la foto como binario
            }

            // Respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Artículo agregado correctamente',
                'data' => [
                    'articulo' => $articulo,
                    'kardex_initial' => $dataArticulo['stock_total'] > 0 ? 'Registro de kardex creado' : 'Sin stock inicial, no se creó registro en kardex'
                ]
            ]);
        } catch (\Exception $e) {
            // Respuesta de error en caso de excepción
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar el artículo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $articulo = Articulo::findOrFail($id);
        $unidades = Unidad::all();
        $tiposArticulo = Tipoarticulo::all();
        $modelos = Modelo::all();
        $monedas = Moneda::all();
        $tiposAreas = Tipoarea::all();  // Asegúrate de tener un modelo llamado Tipoarea si es necesario

        // Convertimos las imágenes a base64 si están presentes
        $fotoCodigobarras = $articulo->foto_codigobarras ? base64_encode($articulo->foto_codigobarras) : null;
        $fotoSku = $articulo->fotosku ? base64_encode($articulo->fotosku) : null;

        return view('almacen.productos.articulos.edit', compact(
            'articulo',
            'unidades',
            'tiposArticulo',
            'modelos',
            'monedas',
            'tiposAreas',
            'fotoCodigobarras',
            'fotoSku'
        ));
    }


    public function detalle($id)
    {
        $articulo = Articulo::findOrFail($id);
        $unidades = Unidad::all();
        $tiposArticulo = Tipoarticulo::all();
        $modelos = Modelo::all();
        $monedas = Moneda::all();
        $tiposAreas = Tipoarea::all();  // Asegúrate de tener un modelo llamado Tipoarea si es necesario

        return view('almacen.productos.articulos.detalle', compact('articulo', 'unidades', 'tiposArticulo', 'modelos', 'monedas', 'tiposAreas'));
    }

    public function imagen($id)
    {
        $articulo = Articulo::findOrFail($id);
        $unidades = Unidad::all();
        $tiposArticulo = Tipoarticulo::all();
        $modelos = Modelo::all();
        $monedas = Moneda::all();
        $tiposAreas = Tipoarea::all();  // Asegúrate de tener un modelo llamado Tipoarea si es necesario

        return view('almacen.productos.articulos.imagen', compact('articulo', 'unidades', 'tiposArticulo', 'modelos', 'monedas', 'tiposAreas'));
    }



    public function updateFoto(Request $request, $id)
    {
        $articulo = Articulo::findOrFail($id);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');

            $request->validate([
                'foto' => 'image|mimes:jpeg,png|max:3072', // 3MB
            ]);

            $binary = file_get_contents($file->getRealPath());
            $articulo->foto = $binary;
            $articulo->save();

            return response()->json([
                'success' => true,
                'preview_url' => 'data:image/jpeg;base64,' . base64_encode($binary)
            ]);
        }

        return response()->json(['success' => false]);
    }

    public function deleteFoto($id)
    {
        $articulo = Articulo::findOrFail($id);
        $articulo->foto = null;
        $articulo->save();

        return response()->json([
            'success' => true,
            'preview_url' => asset('assets/images/articulo/producto-default.png')
        ]);
    }


    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Validar datos
            $validatedData = $request->validate([
                'codigo_barras' => 'nullable|string|max:255',
                'nombre' => 'required|string|max:255',
                'stock_total' => 'nullable|integer',
                'stock_minimo' => 'nullable|integer',
                'precio_compra' => 'nullable|numeric',
                'precio_venta' => 'nullable|numeric',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Aumenté a 5MB
                'sku' => 'nullable|string|max:255',
                'peso' => 'nullable|numeric',
                'mostrarWeb' => 'nullable|boolean',
                'estado' => 'nullable|boolean',
                'idUnidad' => 'required|integer',
                'estado' => 'required|boolean',
                'idTipoArticulo' => 'required|integer',
                'idModelo' => 'required|integer',
            ]);

            $articulo = Articulo::findOrFail($id);

            // Manejar imagen solo si es válida
            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                $foto = $request->file('foto');

                // Verificar tamaño mínimo (1KB)
                if ($foto->getSize() < 1024) {
                    throw new \Exception('La imagen es demasiado pequeña (mínimo 1KB requerido)');
                }

                $fotoBinario = file_get_contents($foto->getRealPath());
                if ($fotoBinario === false) {
                    throw new \Exception('No se pudo leer el archivo de imagen');
                }

                $validatedData['foto'] = $fotoBinario;
                $validatedData['foto_mime'] = $foto->getClientMimeType();
            } else {
                unset($validatedData['foto']);
            }

            // Actualizar artículo
            $articulo->update($validatedData);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Artículo actualizado exitosamente.',
                'data' => $articulo
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
            Log::error('Error actualizando artículo ID ' . $id . ': ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $articulo = Articulo::findOrFail($id);

            // Verificar si el artículo tiene estado = 1
            if ($articulo->estado == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este suministro no puede ser eliminado porque está activo.',
                ], 403); // 403 Forbidden
            }

            // Eliminar la foto si existe y es un path (en caso usas archivos, no blobs)
            if ($articulo->foto && !is_null($articulo->foto) && !is_resource($articulo->foto)) {
                $fotoPath = str_replace('storage/', '', $articulo->foto);
                Storage::disk('public')->delete($fotoPath);
            }

            $articulo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Suministro eliminado con éxito',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar el artículo: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar el artículo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function exportAllPDF()
    {
        $articulos = Articulo::all();
        $pdf = Pdf::loadView('almacen.productos.articulos.pdf.reporte-articulos', compact('articulos'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('reporte-articulos.pdf');
    }

    public function getAll(Request $request)
    {
        $query = Articulo::with(['unidad', 'tipoarticulo', 'modelo.marca', 'modelo.categoria'])
            ->where('idTipoArticulo', '!=', 2);

        $total = $query->count();

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                    ->orWhere('codigo_barras', 'like', "%$search%")
                    ->orWhere('sku', 'like', "%$search%")
                    ->orWhereHas('modelo', function ($m) use ($search) {
                        $m->where('nombre', 'like', "%$search%")
                            ->orWhereHas('marca', fn($q) => $q->where('nombre', 'like', "%$search%"))
                            ->orWhereHas('categoria', fn($q) => $q->where('nombre', 'like', "%$search%"));
                    });
            });
        }

        $filtered = $query->count();

        $articulos = $query
            ->skip($request->start)
            ->take($request->length)
            ->get();

        $data = $articulos->map(function ($articulo) {
            return [
                'idArticulos' => $articulo->idArticulos,
                'foto' => $articulo->foto ? 'data:image/jpeg;base64,' . base64_encode($articulo->foto) : null,
                'nombre' => $articulo->nombre,
                'codigo_barras' => $articulo->codigo_barras,
                'sku' => $articulo->sku,
                'unidad' => $articulo->unidad->nombre ?? 'Sin Unidad',
                'stock_total' => $articulo->stock_total,
                'tipo_articulo' => $articulo->tipoarticulo->nombre ?? 'Sin Tipo',
                'modelo' => $articulo->modelo->nombre ?? 'Sin Modelo',
                'marca' => $articulo->modelo->marca->nombre ?? 'Sin Marca',
                'categoria' => $articulo->modelo->categoria->nombre ?? 'Sin Categoría',
                'estado' => $articulo->estado ? 'Activo' : 'Inactivo',
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }




    public function checkNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $exists = Articulo::where('nombre', $nombre)->exists();

        return response()->json(['unique' => !$exists]);
    }
}
