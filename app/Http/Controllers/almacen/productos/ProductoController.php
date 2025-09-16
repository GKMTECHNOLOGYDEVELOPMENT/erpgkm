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
use App\Models\Proveedore;
use App\Models\Tipoarea;
use App\Models\Tipoarticulo;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Str;


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
    
    // Obtener proveedores activos
    $proveedores = Proveedore::where('estado', 1)->get();

    // Retornar la vista con los datos necesarios
    return view('almacen.productos.articulos.create', compact(
        'unidades', 
        'tiposArticulo', 
        'modelos', 
        'monedas', 
        'marcas', 
        'categorias',
        'proveedores'  // Agregamos los proveedores
    ));
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
    DB::beginTransaction();

    try {
        // Validar datos
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
            'garantia_fabrica' => 'nullable|integer|min:0',
            'unidad_tiempo_garantia' => 'nullable|in:dias,semanas,meses,años',
            'idProveedor' => 'nullable|exists:proveedores,idProveedor',
        ]);

        // Asignar valores por defecto
        $dataArticulo = $validatedData;
        $dataArticulo['estado'] = $dataArticulo['estado'] ?? 1;
        $dataArticulo['idTipoArticulo'] = 1;
        $dataArticulo['fecha_ingreso'] = now();
        $dataArticulo['stock_total'] = $dataArticulo['stock_total'] ?? 0;
        $dataArticulo['precio_compra'] = $dataArticulo['precio_compra'] ?? 0;
        $dataArticulo['garantia_fabrica'] = $dataArticulo['garantia_fabrica'] ?? 0;
        $dataArticulo['unidad_tiempo_garantia'] = $dataArticulo['unidad_tiempo_garantia'] ?? 'meses';

        // Crear artículo
        $articulo = Articulo::create($dataArticulo);

        // Crear Kardex inicial
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

        // Código de barras y SKU como imagen
        $barcodeGenerator = new BarcodeGeneratorPNG();

        if (!empty($dataArticulo['codigo_barras'])) {
            $barcode = $barcodeGenerator->getBarcode($dataArticulo['codigo_barras'], BarcodeGeneratorPNG::TYPE_CODE_128);
            $articulo->update(['foto_codigobarras' => $barcode]);
        }

        if (!empty($dataArticulo['sku'])) {
            $barcode = $barcodeGenerator->getBarcode($dataArticulo['sku'], BarcodeGeneratorPNG::TYPE_CODE_128);
            $articulo->update(['fotosku' => $barcode]);
        }

        // Imagen del artículo
        if ($request->hasFile('foto')) {
            $photoData = file_get_contents($request->file('foto')->getRealPath());
            $articulo->update(['foto' => $photoData]);
        }

        // Ficha técnica PDF
        if ($request->hasFile('ficha_tecnica')) {
            $pdfPath = $request->file('ficha_tecnica')->store('fichas', 'public');
            $articulo->update(['ficha_tecnica' => basename($pdfPath)]);
        }

        // Crear COMPRA automática
        $usuario = Auth::user();
        if (!$usuario) {
            throw new \Exception("Usuario no autenticado");
        }

        // Generar código compra único
        do {
            $codigoCompra = strtoupper(Str::random(10));
        } while (DB::table('compra')->where('codigocompra', $codigoCompra)->exists());

        $totalCompra = $dataArticulo['stock_total'] * $dataArticulo['precio_compra'];

        // Insertar en tabla compra
        $compraId = DB::table('compra')->insertGetId([
            'codigocompra' => $codigoCompra,
            'serie' => 'MR',
            'nro' => '0000',
            'descripcion' => 'Compra inicial por creación de artículo ' . $articulo->nombre,
            'fechaEmision' => now(),
            'fechaVencimiento' => now(),
            'idDocumento' => null,
            'imagen' => null,
            'sujetoporcentaje' => null,
            'cantidad' => $dataArticulo['stock_total'],
            'gravada' => null,
            'igv' => 0,
            'total' => $totalCompra,
            'idMonedas' => $dataArticulo['moneda_compra'],
            'idDocumento' => null,
            'idImpuesto' => null,
            'idSujeto' => null,
            'idUsuario' => $usuario->idUsuario,
            'proveedor_id' => $dataArticulo['idProveedor'],
            'idCondicionCompra' => null,
            'idTipoPago' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insertar detalle de compra
        DB::table('detalle_compra')->insert([
            'idCompra' => $compraId,
            'idProducto' => $articulo->idArticulos,
            'cantidad' => $dataArticulo['stock_total'],
            'precio' => $dataArticulo['precio_compra'],
            'precio_venta' => $dataArticulo['precio_venta'],
            'subtotal' => $totalCompra,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Artículo y compra registrada correctamente',
            'data' => [
                'articulo_id' => $articulo->idArticulos,
                'compra_id' => $compraId,
                'stock_inicial' => $dataArticulo['stock_total'],
                'garantia' => $dataArticulo['garantia_fabrica'] . ' ' . $dataArticulo['unidad_tiempo_garantia'],
                'proveedor_id' => $dataArticulo['idProveedor'] ?? null,
            ]
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
        return response()->json([
            'success' => false,
            'message' => 'Ocurrió un error al guardar el artículo y compra',
            'error' => $e->getMessage()
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
    $proveedores = Proveedore::where('estado', 1)->get(); // Obtener proveedores activos
    return view('almacen.productos.articulos.edit', compact('articulo', 'unidades', 'tiposArticulo', 'modelos', 'monedas', 'tiposAreas', 'proveedores'));
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
            'ficha_tecnica' => 'nullable|file|mimes:pdf|max:5120',
            'idUnidad' => 'required|nullable|integer',
            'idModelo' => 'integer|exists:modelo,idModelo',
            // Nuevos campos agregados
            'garantia_fabrica' => 'nullable|integer|min:0',
            'unidad_tiempo_garantia' => 'nullable|in:dias,semanas,meses,años',
            'idProveedor' => 'nullable|exists:proveedores,idProveedor',
        ]);

        // ✅ Buscar el artículo
        $articulo = Articulo::findOrFail($id);

        // ✅ Actualizar datos principales
        $dataArticulo = $validatedData;
        
        // Valores por defecto para los nuevos campos si no están presentes
        $dataArticulo['garantia_fabrica'] = $dataArticulo['garantia_fabrica'] ?? 0;
        $dataArticulo['unidad_tiempo_garantia'] = $dataArticulo['unidad_tiempo_garantia'] ?? 'meses';
        
        // Si idProveedor está vacío, establecerlo como null
        if (empty($dataArticulo['idProveedor'])) {
            $dataArticulo['idProveedor'] = null;
        }
    
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
    // Obtener los clientes generales que tienen stock de este artículo
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

    // Construir el select HTML
    $selectHtml = '<select class="select-cliente-general w-full text-sm rounded">';
    if ($clientes->isEmpty()) {
        $selectHtml .= '<option value="">Sin cliente</option>';
    } else {
        foreach ($clientes as $cliente) {
            $selectHtml .= '<option value="' . $cliente->idClienteGeneral . '">' .
                $cliente->descripcion . ' - ' . $cliente->total . ' unidades' .
                '</option>';
        }
    }
    $selectHtml .= '</select>';

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


