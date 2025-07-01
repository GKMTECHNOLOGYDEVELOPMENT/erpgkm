<?php

namespace App\Http\Controllers\almacen\suministros;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\Categoria;
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

class SuministrosController extends Controller
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
        return view('almacen.suministros.index', compact('unidades', 'tiposArticulo', 'modelos', 'monedas'));
    }

    public function create()
    {
        // Obtener datos para los selects
        $unidades = Unidad::all();
        $tiposArticulo = Tipoarticulo::all();
        $modelos = Modelo::with(['marca', 'categoria'])
            ->where('estado', 1)
            ->where('suministros', 1)
            ->get();      
        $monedas = Moneda::all();
         $marcas = Marca::all();
        $categorias = Categoria::all();

        // Retornar la vista con los datos necesarios
        return view('almacen.suministros.create', compact('unidades', 'tiposArticulo', 'modelos', 'monedas','marcas', 'categorias' ));
    }


   
     public function store(Request $request)
    {
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
                'ficha_tecnica' => 'nullable|file|mimes:pdf|max:5120', // <= validación del PDF
                'idUnidad' => 'required|nullable|integer',
                'idModelo' => 'integer|exists:modelo,idModelo', 
            ]);
            
            // Asignación de valores por defecto
            $dataArticulo = $validatedData;

            $dataArticulo['estado'] = $dataArticulo['estado'] ?? 1;
            $dataArticulo['idTipoArticulo'] = 4; // Tipo de artículo por defecto
            $dataArticulo['fecha_ingreso'] = now(); // Fecha de ingreso con valor actual
            
            // Crear el artículo
            $articulo = Articulo::create($dataArticulo);
    
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

           if ($request->hasFile('ficha_tecnica')) {
                $pdf = $request->file('ficha_tecnica');
                $pdfPath = $pdf->store('fichas', 'public'); // guarda: fichas/nombreArchivo.pdf
                $fileName = basename($pdfPath); // extrae solo "nombreArchivo.pdf"
                $articulo->update(['ficha_tecnica' => $fileName]); // guarda solo el nombre en BD
            }

    
            // Respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Suministro agregado correctamente',
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

    return view('almacen.suministros.edit', compact('articulo', 'unidades', 'tiposArticulo', 'modelos', 'monedas', 'tiposAreas'));
}


public function detalle($id)
{
    $articulo = Articulo::findOrFail($id);
    $unidades = Unidad::all();
    $tiposArticulo = Tipoarticulo::all();
    $modelos = Modelo::all();
    $monedas = Moneda::all();
    $tiposAreas = Tipoarea::all();  // Asegúrate de tener un modelo llamado Tipoarea si es necesario

    return view('almacen.suministros.detalle', compact('articulo', 'unidades', 'tiposArticulo', 'modelos', 'monedas', 'tiposAreas'));
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
            'message' => 'Suministro actualizado correctamente',
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

    return view('almacen.suministros.imagen', compact('articulo', 'unidades', 'tiposArticulo', 'modelos', 'monedas', 'tiposAreas'));
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


public function cambiarEstado($id)
{
    try {
        $articulo = Articulo::findOrFail($id);
        $articulo->estado = $articulo->estado == 1 ? 0 : 1;
        $articulo->save();

        return response()->json([
            'success' => true,
            'message' => 'El estado del artículo ha sido actualizado correctamente.',
            'nuevoEstado' => $articulo->estado == 1 ? 'Activo' : 'Inactivo',
        ]);
    } catch (\Exception $e) {
        Log::error("Error al cambiar estado del artículo: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'No se pudo cambiar el estado del artículo.',
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
    $query = Articulo::with(['unidad', 'tipoarticulo',   'modelo.marca', 'modelo.categoria']) // <= Aquí
        ->where('idTipoArticulo', 4); // Solo repuestos

    $total = $query->count();

    if ($search = $request->input('search.value')) {
        $query->where(function ($q) use ($search) {
            $q->where('nombre', 'like', "%$search%")
              ->orWhere('codigo_barras', 'like', "%$search%")
              ->orWhere('sku', 'like', "%$search%");
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
            'sku' => $articulo->nombre,
            'nombre' => $articulo->nombre,
            'unidad' => $articulo->unidad->nombre ?? 'Sin Unidad',
            'codigo_barras' => $articulo->codigo_barras,
            'stock_total' => $articulo->stock_total,
            'sku' => $articulo->sku,
            'tipo_articulo' => $articulo->tipoarticulo->nombre ?? 'Sin Tipo',
            'modelo' => $articulo->modelo ? $articulo->modelo->nombre : 'Sin Modelo',
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
