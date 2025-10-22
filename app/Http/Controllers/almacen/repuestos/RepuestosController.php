<?php

namespace App\Http\Controllers\almacen\repuestos;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\ArticuloModelo;
use App\Models\Categoria;
use App\Models\Kardex;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Moneda;
use App\Models\Subcategoria;
use App\Models\Tipoarea;
use App\Models\Tipoarticulo;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Picqer\Barcode\BarcodeGeneratorPNG;

class RepuestosController extends Controller
{
    public function index()
    {
        $unidades = Unidad::all();
        $tiposArticulo = Tipoarticulo::all();
        // En tu controlador
        $modelos = Modelo::with(['marca', 'categoria'])
            ->where('estado', 1)
            ->where('repuesto', 1) // o como tengas tu lógica
            ->get(['idModelo', 'nombre', 'idMarca', 'idCategoria', 'pulgadas']);

        $monedas = Moneda::all();

        $marcas = Marca::all();

        // Retorna la vista para artículos
        return view('almacen.repuestos.index', compact('unidades', 'tiposArticulo', 'modelos', 'monedas', 'marcas'));
    }

    public function create()
    {
        // Obtener datos para los selects
        $unidades = Unidad::all();
        $tiposArticulo = Tipoarticulo::all();
        $modelos = Modelo::with(['marca', 'categoria'])
            ->where('estado', 1)
            ->where('repuesto', 1)
            ->get();       
        $monedas = Moneda::all();
        $subcategorias = Subcategoria::all();
        $marcas = Marca::all();
        $categorias = Categoria::all();



        // Retornar la vista con los datos necesarios
        return view('almacen.repuestos.create', compact('unidades', 'tiposArticulo', 'modelos', 'monedas', 'subcategorias', 'marcas','categorias'));
    }


    public function kardex ($id){


   $articulo = Articulo::findOrFail($id);
    
    // Obtener todos los movimientos del kardex para este artículo ordenados por fecha descendente
    $movimientos = Kardex::where('idArticulo', $id)
                        ->orderBy('fecha', 'desc')
                        ->paginate(10);

        return view('almacen.repuestos.kardex.index', compact('articulo', 'movimientos'));
    }



    // En UnidadController.php
public function storeunidad(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255|unique:unidad,nombre'
    ]);

    $unidad = Unidad::create([
        'nombre' => $request->nombre
    ]);

    return response()->json([
        'success' => true,
        'unidad' => $unidad
    ]);
}


// En tu controlador
public function storesubcategoria(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:100|unique:subcategorias,nombre',
        'descripcion' => 'nullable|string'
    ]);

    $subcategoria = SubCategoria::create([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion
    ]);

    return response()->json([
        'success' => true,
        'subcategoria' => [
            'id' => $subcategoria->id,
            'nombre' => $subcategoria->nombre
        ]
    ]);
}

 public function storerepuestomodelo(Request $request)
    {
        // Validación de datos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:modelo,nombre',
            'idMarca' => 'required|exists:marca,idMarca',
            'idCategoria' => 'required|exists:categoria,idCategoria',
            'repuesto' => 'nullable|boolean',
            'producto' => 'nullable|boolean',
            'heramientas' => 'nullable|boolean',
            'suministros' => 'nullable|boolean',
            'pulgadas' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            // Crear el modelo
            $modelo = Modelo::create([
                'nombre' => $request->nombre,
                'idMarca' => $request->idMarca,
                'idCategoria' => $request->idCategoria,
                'estado' => 1, // Activo por defecto
                'repuesto' => $request->repuesto ?? 0,
                'producto' => $request->producto ?? 0,
                'heramientas' => $request->heramientas ?? 0,
                'suministros' => $request->suministros ?? 0,
                'pulgadas' => $request->pulgadas
            ]);

            // Obtener datos relacionados para la respuesta
            $marca = Marca::find($request->idMarca);
            $categoria = Categoria::find($request->idCategoria);

            DB::commit();

            return response()->json([
                'success' => true,
                'modelo' => [
                    'idModelo' => $modelo->idModelo,
                    'nombre' => $modelo->nombre
                ],
                'marca' => [
                    'nombre' => $marca->nombre
                ],
                'categoria' => [
                    'nombre' => $categoria->nombre
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el modelo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
            DB::beginTransaction(); // Iniciar transacción para operaciones atómicas

        try {
            // Validación de datos
            $validatedData = $request->validate([
                'codigo_barras' => 'required|string|max:255|unique:articulos,codigo_barras',
                'sku' => 'required|string|max:255|unique:articulos,sku',
                'codigo_repuesto' => 'required|string|max:255|unique:articulos,codigo_repuesto',
                'stock_total' => 'required|nullable|integer',
                'stock_minimo' => 'required|nullable|integer',
                'moneda_compra' => 'required|nullable|integer',
                'moneda_venta' => 'required|nullable|integer',
                'precio_compra' => 'nullable|numeric',
                'precio_venta' => 'nullable|numeric',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'ficha_tecnica' => 'nullable|file|mimes:pdf|max:5120', // <= validación del PDF
                'pulgadas' => 'required|nullable|string|max:255',
                'idUnidad' => 'required|nullable|integer',
                'idModelo' => 'nullable|array', // Select multiple
                'idModelo.*' => 'integer|exists:modelo,idModelo',
                'idsubcategoria' => 'required|nullable|integer'
            ]);
            
            // Asignación de valores por defecto
            $dataArticulo = $validatedData;
            unset($dataArticulo['idModelo']); // 👈 EXCLUÍS el array antes de crear el artículo

            $dataArticulo['estado'] = $dataArticulo['estado'] ?? 1;
            $dataArticulo['idTipoArticulo'] = 2; // Tipo de artículo por defecto
            $dataArticulo['fecha_ingreso'] = now(); // Fecha de ingreso con valor actual
            
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

               // Generar y guardar el código de barras para 'codigo_repuesto' como binario
            if (!empty($dataArticulo['codigo_repuesto'])) {
                $barcodeGenerator = new BarcodeGeneratorPNG();
                $barcode = $barcodeGenerator->getBarcode($dataArticulo['codigo_repuesto'], BarcodeGeneratorPNG::TYPE_CODE_128);
                $fotoCrBinario = $barcode; // El código de barras ya es binario
                $articulo->update(['br-codigo-repuesto' => $fotoCrBinario]);
            }
    
            // Subir la foto del artículo y convertirla a binario
            if ($request->hasFile('foto')) {
                $photoPath = $request->file('foto')->getRealPath(); // Obtener la ruta del archivo
                $photoData = file_get_contents($photoPath); // Leer el archivo como binario
                $articulo->update(['foto' => $photoData]); // Guardar la foto como binario
            }

           if ($request->hasFile('ficha_tecnica')) {
                $pdf = $request->file('ficha_tecnica');
                $pdfPath = $pdf->store('fichas', 'public'); // guarda: fichas/nombreArchivo.pdf
                $fileName = basename($pdfPath); // extrae solo "nombreArchivo.pdf"
                $articulo->update(['ficha_tecnica' => $fileName]); // guarda solo el nombre en BD
            }

            // Guardar modelos múltiples (relación muchos a muchos)
        if ($request->has('idModelo') && is_array($request->idModelo)) {
            foreach ($request->idModelo as $modeloId) {
                ArticuloModelo::create([
                    'articulo_id' => $articulo->idArticulos,
                    'modelo_id' => $modeloId,
                ]);
            }
        }


                DB::commit(); // Confirmar todas las operaciones




    
                return response()->json([
            'success' => true,
            'message' => 'Repuesto agregado correctamente',
            'data' => [
                'articulo_id' => $articulo->idArticulos,
                'kardex_created' => $dataArticulo['stock_total'] > 0,
                'stock_inicial' => $dataArticulo['stock_total']
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack(); // Revertir en caso de error
        
        return response()->json([
            'success' => false,
            'message' => 'Ocurrió un error al guardar el artículo.',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString() // Solo para desarrollo, quitar en producción
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
        $subcategorias = Subcategoria::all();

    return view('almacen.repuestos.edit', compact('articulo', 'unidades', 'tiposArticulo', 'modelos', 'monedas', 'tiposAreas', 'subcategorias'));
}


public function detalle($id)
{
    $articulo = Articulo::findOrFail($id);
    $unidades = Unidad::all();
    $tiposArticulo = Tipoarticulo::all();
// Cargar los modelos relacionados al artículo (muchos a muchos)
$modelos = \App\Models\Modelo::whereIn('idModelo', function($query) use ($articulo) {
    $query->select('modelo_id')
          ->from('articulo_modelo')
          ->where('articulo_id', $articulo->idArticulos);
})->with('categoria')->get();
    $monedas = Moneda::all();
    $tiposAreas = Tipoarea::all();  // Asegúrate de tener un modelo llamado Tipoarea si es necesario
    $subcategorias = Subcategoria::all();

    return view('almacen.repuestos.detalle', compact('articulo', 'unidades', 'tiposArticulo', 'modelos', 'monedas', 'tiposAreas','subcategorias'));
}


public function update(Request $request, $id)
{
    try {
        // ✅ Validación igual que en store, sin UNIQUE
        $validatedData = $request->validate([
            'codigo_barras' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'codigo_repuesto' => 'required|string|max:255',
            'stock_total' => 'required|nullable|integer',
            'stock_minimo' => 'required|nullable|integer',
            'moneda_compra' => 'required|nullable|integer',
            'moneda_venta' => 'required|nullable|integer',
            'precio_compra' => 'required|nullable|numeric',
            'precio_venta' => 'required|nullable|numeric',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'ficha_tecnica' => 'nullable|file|mimes:pdf|max:5120',
            'pulgadas' => 'required|nullable|string|max:255',
            'idUnidad' => 'required|nullable|integer',
            'estado' => 'required|boolean',
            'idModelo' => 'nullable|array',
            'idModelo.*' => 'integer|exists:modelo,idModelo',
            'idsubcategoria' => 'required|nullable|integer'

        ]);

        // ✅ Buscar el artículo
        $articulo = Articulo::findOrFail($id);

        // ✅ Actualizar datos principales
        $dataArticulo = $validatedData;
        unset($dataArticulo['idModelo']); // 👈 Removés idModelo (relación aparte)

        $articulo->update($dataArticulo);

        // ✅ Código de barras para 'codigo_barras'
        if (!empty($dataArticulo['codigo_barras'])) {
            $barcodeGenerator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $barcode = $barcodeGenerator->getBarcode($dataArticulo['codigo_barras'], $barcodeGenerator::TYPE_CODE_128);
            $articulo->update(['foto_codigobarras' => $barcode]);
        }

        // ✅ Código de barras para 'sku'
        if (!empty($dataArticulo['sku'])) {
            $barcodeGenerator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $barcode = $barcodeGenerator->getBarcode($dataArticulo['sku'], $barcodeGenerator::TYPE_CODE_128);
            $articulo->update(['fotosku' => $barcode]);
        }


        
        // ✅ Código de barras para 'sku'
        if (!empty($dataArticulo['codigo_repuesto'])) {
            $barcodeGenerator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $barcode = $barcodeGenerator->getBarcode($dataArticulo['codigo_repuesto'], $barcodeGenerator::TYPE_CODE_128);
            $articulo->update(['br-codigo-repuesto' => $barcode]);
        }

        // ✅ Subir nueva imagen (si viene)
        if ($request->hasFile('foto')) {
            $photoPath = $request->file('foto')->getRealPath();
            $photoData = file_get_contents($photoPath);
            $articulo->update(['foto' => $photoData]);
        }

      // ✅ Reemplazar el PDF anterior si viene uno nuevo
        if ($request->hasFile('ficha_tecnica')) {
            // Eliminar el anterior si existe
            if ($articulo->ficha_tecnica) {
                $rutaAnterior = storage_path('app/public/fichas/' . $articulo->ficha_tecnica);
                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }
            }

            // Subir el nuevo
            $pdf = $request->file('ficha_tecnica');
            $pdfPath = $pdf->store('fichas', 'public');
            $fileName = basename($pdfPath);
            $articulo->update(['ficha_tecnica' => $fileName]);
        }


        // ✅ Actualizar modelos relacionados (relación muchos-a-muchos)
        if ($request->has('idModelo') && is_array($request->idModelo)) {
            // Primero borrar los antiguos
            \App\Models\ArticuloModelo::where('articulo_id', $articulo->idArticulos)->delete();

            // Insertar los nuevos
            foreach ($request->idModelo as $modeloId) {
                \App\Models\ArticuloModelo::create([
                    'articulo_id' => $articulo->idArticulos,
                    'modelo_id' => $modeloId,
                ]);
            }
        }

        // ✅ Respuesta de éxito
        return response()->json([
            'success' => true,
            'message' => 'Artículo actualizado correctamente',
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Ocurrió un error al actualizar el artículo.',
            'error' => $e->getMessage(),
        ], 500);
    }
}




public function imagen($id)
{
    $articulo = Articulo::findOrFail($id);
    $unidades = Unidad::all();
    $tiposArticulo = Tipoarticulo::all();
    $modelos = Modelo::all();
    $monedas = Moneda::all();
    $tiposAreas = Tipoarea::all();  // Asegúrate de tener un modelo llamado Tipoarea si es necesario

    return view('almacen.repuestos.imagen', compact('articulo', 'unidades', 'tiposArticulo', 'modelos', 'monedas', 'tiposAreas'));
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
    $query = Articulo::with(['unidad', 'tipoarticulo', 'subcategoria', 'modelos'])
        ->where('idTipoArticulo', 2); // Solo repuestos

    $total = $query->count();

    if ($search = $request->input('search.value')) {
        $query->where(function ($q) use ($search) {
            $q->where('codigo_repuesto', 'like', "%$search%")
              ->orWhere('stock_total', 'like', "%$search%")
              ->orWhere('estado', 'like', "%$search%")
              ->orWhereHas('modelos', fn($sub) => $sub->where('nombre', 'like', "%$search%"))
              ->orWhereHas('subcategoria', fn($sub) => $sub->where('nombre', 'like', "%$search%"));
        });
    }

    $filtered = $query->count();

    $articulos = $query
        ->skip($request->start)
        ->take($request->length)
        ->get();

    $data = $articulos->map(function ($articulo) {
        $modeloNombres = $articulo->modelos->pluck('nombre')->join(' / ');
        $subcategoriaNombre = $articulo->subcategoria->nombre ?? 'Sin Subcategoría';

        // 🔽 Agregamos la consulta para obtener los clientes generales con stock
        $clientes = DB::table('inventario_ingresos_clientes as iic')
            ->join('clientegeneral as cg', 'cg.idClienteGeneral', '=', 'iic.cliente_general_id')
            ->select(
                'cg.idClienteGeneral',
                'cg.descripcion',
                DB::raw('SUM(iic.cantidad) as total')
            )
            ->where('iic.articulo_id', $articulo->idArticulos)
            ->groupBy('cg.idClienteGeneral', 'cg.descripcion')
            ->get();

        // 🔽 Construimos el select HTML
$selectHtml = '<select class="select-cliente-general w-full text-sm rounded" data-articulo-id="' . $articulo->idArticulos . '">';
        $selectHtml .= '<option value="">Seleccionar cliente</option>'; // 👈 línea agregada

        foreach ($clientes as $cliente) {
            $selectHtml .= '<option value="' . $cliente->idClienteGeneral . '">' .
                $cliente->descripcion . ' - ' . $cliente->total . ' unidades' .
                '</option>';
        }

        $selectHtml .= '</select>';

        return [
            'idArticulos' => $articulo->idArticulos,
            'foto' => $articulo->foto ? 'data:image/jpeg;base64,' . base64_encode($articulo->foto) : null,
            'nombre' => $articulo->nombre,
            'codigo_repuesto' => $articulo->codigo_repuesto,
            'unidad' => $articulo->unidad->nombre ?? 'Sin Unidad',
            'codigo_barras' => $articulo->codigo_barras,
            'stock_total' => $articulo->stock_total,
            'sku' => $articulo->sku,
            'tipo_articulo' => $articulo->tipoarticulo->nombre ?? 'Sin Tipo',
            'modelo' => $modeloNombres ?: 'Sin Modelo',
            'subcategoria' => $subcategoriaNombre,
            'estado' => $articulo->estado ? 'Activo' : 'Inactivo',
            'cliente_general_select' => $selectHtml,
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





    public function entrada(Request $request)
    {
        return view('almacen.repuestos.entrada');
    }
}
