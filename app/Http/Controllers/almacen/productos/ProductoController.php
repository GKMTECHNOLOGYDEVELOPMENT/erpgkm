<?php

namespace App\Http\Controllers\almacen\productos;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\ArticuloModelo;
use App\Models\Categoria;
use App\Models\Kardex;
use App\Models\Marca;
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

class ProductoController extends Controller
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
        $marcas = Marca::all();
        $categorias = Categoria::all();
        $monedas = Moneda::all();

        

        // Retornar la vista con los datos necesarios
        return view('almacen.productos.articulos.create', compact('unidades', 'tiposArticulo', 'modelos', 'monedas', 'marcas', 'categorias'));
    }

     public function createproducto(Request $request)
    {
        // Obtener datos para los selects
            $modeloId = $request->query('modelo');

        $unidades = Unidad::all();
        $tiposArticulo = Tipoarticulo::all();
        $modelos = Modelo::with(['marca', 'categoria'])
            ->where('estado', 1)
            ->where('producto', 1)
            ->get();
        $monedas = Moneda::all();
        $marcas = Marca::all();
        $categorias = Categoria::all();


        

        // Retornar la vista con los datos necesarios
        return view('almacen.productos.create-producto', [
        'unidades' => $unidades,
        'tiposArticulo' => $tiposArticulo,
        'modelos' => $modelos,
        'monedas' => $monedas,
        'modeloSeleccionado' => $modeloId, // Pasamos el ID del modelo a la vista
        'marcas' => $marcas,
        'categorias' => $categorias
    ]);
    }




  public function store(Request $request)
{
    DB::beginTransaction(); // Iniciar transacción para operaciones atómicas

    try {
        // Validación de datos
        $validatedData = $request->validate([
            'codigo_barras' => 'required|string|max:255|unique:articulos,codigo_barras',
            'sku' => 'required|string|max:255|unique:articulos,sku',
            'nombre' => 'required|string|max:255|unique:articulos,nombre',
            'stock_total' => 'required|nullable|integer',
            'stock_minimo' => 'required|nullable|integer',
            'moneda_compra' => 'required|nullable|integer',
            'moneda_venta' => 'required|nullable|integer',
            'precio_compra' => 'required|nullable|numeric',
            'precio_venta' => 'required|nullable|numeric',
            'peso' => 'required|nullable|numeric',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'ficha_tecnica' => 'nullable|file|mimes:pdf|max:5120',
            'idUnidad' => 'required|nullable|integer',
            'idModelo' => 'integer|exists:modelo,idModelo', 
        ]);
        
        // Asignación de valores por defecto
        $dataArticulo = $validatedData;
        $dataArticulo['estado'] = $dataArticulo['estado'] ?? 1;
        $dataArticulo['idTipoArticulo'] = 1; // Tipo de artículo por defecto
        $dataArticulo['fecha_ingreso'] = now(); // Fecha de ingreso actual
        $dataArticulo['stock_total'] = $dataArticulo['stock_total'] ?? 0; // Asegurar valor por defecto
        $dataArticulo['precio_compra'] = $dataArticulo['precio_compra'] ?? 0; // Asegurar valor por defecto
        
        // Crear el artículo
        $articulo = Articulo::create($dataArticulo);

        // Registrar movimiento inicial en el Kardex (solo si hay stock)
        if ($dataArticulo['stock_total'] > 0) {
            Kardex::create([
                'fecha' => now(),
                'idArticulo' => $articulo->idArticulos,
                'unidades_entrada' => $dataArticulo['stock_total'],
                'costo_unitario_entrada' => $dataArticulo['stock_total'] * $dataArticulo['precio_compra'],
                'unidades_salida' => 0,
                'costo_unitario_salida' => 0,
                'inventario_inicial' => $dataArticulo['stock_total'], 
                'inventario_actual' => $dataArticulo['stock_total'],
                'costo_inventario' => $dataArticulo['stock_total'] * $dataArticulo['precio_compra']
            ]);
        }

        // Generar códigos de barras
        if (!empty($dataArticulo['codigo_barras'])) {
            $barcodeGenerator = new BarcodeGeneratorPNG();
            $barcode = $barcodeGenerator->getBarcode($dataArticulo['codigo_barras'], BarcodeGeneratorPNG::TYPE_CODE_128);
            $articulo->update(['foto_codigobarras' => $barcode]);
        }

        if (!empty($dataArticulo['sku'])) {
            $barcodeGenerator = new BarcodeGeneratorPNG();
            $barcode = $barcodeGenerator->getBarcode($dataArticulo['sku'], BarcodeGeneratorPNG::TYPE_CODE_128);
            $articulo->update(['fotosku' => $barcode]);
        }

        // Manejo de archivos
        if ($request->hasFile('foto')) {
            $photoPath = $request->file('foto')->getRealPath();
            $photoData = file_get_contents($photoPath);
            $articulo->update(['foto' => $photoData]);
        }

        if ($request->hasFile('ficha_tecnica')) {
            $pdf = $request->file('ficha_tecnica');
            $pdfPath = $pdf->store('fichas', 'public');
            $fileName = basename($pdfPath);
            $articulo->update(['ficha_tecnica' => $fileName]);
        }

        DB::commit(); // Confirmar todas las operaciones

        return response()->json([
            'success' => true,
            'message' => 'Artículo agregado correctamente',
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

    return view('almacen.productos.articulos.edit', compact('articulo', 'unidades', 'tiposArticulo', 'modelos', 'monedas', 'tiposAreas'));
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


public function update(Request $request, $id)
{
    try {
        // ✅ Validación igual que en store, sin UNIQUE
        $validatedData = $request->validate([
                'codigo_barras' => 'required|string|max:255',
                'sku' => 'required|string|max:255',
                'nombre' => 'required|string|max:255',
                'stock_total' => 'required|nullable|integer',
                'stock_minimo' => 'required|nullable|integer',
                'moneda_compra' => 'required|nullable|integer',
                'moneda_venta' => 'required|nullable|integer',
                'precio_compra' => 'required|nullable|numeric',
                'precio_venta' => 'required|nullable|numeric',
                'peso' => 'required|nullable|numeric',
                'estado' => 'required|boolean',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'ficha_tecnica' => 'nullable|file|mimes:pdf|max:5120', // <= validación del PDF
                'idUnidad' => 'required|nullable|integer',
                'idModelo' => 'integer|exists:modelo,idModelo', 
        ]);

        // ✅ Buscar el artículo
        $articulo = Articulo::findOrFail($id);

        // ✅ Actualizar datos principales
        $dataArticulo = $validatedData;
    
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
            ->where('idTipoArticulo', 1); // Solo repuestos
    
        $total = $query->count();
    
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                  ->orWhere('codigo_barras', 'like', "%$search%")
                  ->orWhere('sku', 'like', "%$search%")
                  ->orWhere('stock_total', 'like', "%$search%")
                  ->orWhereHas('unidad', fn($u) => $u->where('nombre', 'like', "%$search%"))
                  ->orWhereHas('modelo', function ($m) use ($search) {
                      $m->where('nombre', 'like', "%$search%")
                        ->orWhereHas('marca', fn($marca) => $marca->where('nombre', 'like', "%$search%"))
                        ->orWhereHas('categoria', fn($cat) => $cat->where('nombre', 'like', "%$search%"));
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
                'codigo_barras' => $articulo->codigo_barras,
                'sku' => $articulo->sku,
                'nombre' => $articulo->nombre,
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

}


