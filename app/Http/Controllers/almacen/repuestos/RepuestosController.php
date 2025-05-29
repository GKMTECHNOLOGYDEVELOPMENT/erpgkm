<?php

namespace App\Http\Controllers\almacen\repuestos;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
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

class RepuestosController extends Controller
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
        return view('almacen.repuestos.index', compact('unidades', 'tiposArticulo', 'modelos', 'monedas'));
    }

    public function create()
    {
        // Obtener datos para los selects
        $unidades = Unidad::all();
        $tiposArticulo = Tipoarticulo::all();
        $modelos = Modelo::with(['marca', 'categoria'])->where('estado', 1)->get();
        $monedas = Moneda::all();

        // Retornar la vista con los datos necesarios
        return view('almacen.repuestos.create', compact('unidades', 'tiposArticulo', 'modelos', 'monedas'));
    }


    public function store(Request $request)
    {
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
                'precio_compra' => 'required|nullable|numeric',
                'precio_venta' => 'required|nullable|numeric',
                'foto' => 'required|nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'pulgadas' => 'required|nullable|string|max:255',
                'idUnidad' => 'required|nullable|integer',
                'idModelo' => 'required|nullable|integer',
            ]);
            
            // Asignación de valores por defecto
            $dataArticulo = $validatedData;
            $dataArticulo['estado'] = $dataArticulo['estado'] ?? 1;
            $dataArticulo['idTipoArticulo'] = 2; // Tipo de artículo por defecto
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
    
            // Respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Artículo agregado correctamente',
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

    return view('almacen.repuestos.edit', compact('articulo', 'unidades', 'tiposArticulo', 'modelos', 'monedas', 'tiposAreas'));
}


public function detalle($id)
{
    $articulo = Articulo::findOrFail($id);
    $unidades = Unidad::all();
    $tiposArticulo = Tipoarticulo::all();
    $modelos = Modelo::all();
    $monedas = Moneda::all();
    $tiposAreas = Tipoarea::all();  // Asegúrate de tener un modelo llamado Tipoarea si es necesario

    return view('almacen.repuestos.detalle', compact('articulo', 'unidades', 'tiposArticulo', 'modelos', 'monedas', 'tiposAreas'));
}
public function update(Request $request, $id)
{
    DB::beginTransaction();

    Log::info("Inicio de actualización del artículo con ID: {$id}");

    try {
        // Log de entrada de datos
        Log::debug("Datos recibidos para actualización:", $request->except(['foto', 'foto_codigobarras', 'fotosku']));

        // Validar datos
        $validatedData = $request->validate([
            'codigo_barras' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'codigo_repuesto' => 'required|string|max:255',
            'stock_total' => 'required|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:1',
            'moneda_compra' => 'required|integer',
            'moneda_venta' => 'required|integer',
            'precio_compra' => 'required|numeric|min:0.01',
            'precio_venta' => 'required|numeric|min:0.01|gt:precio_compra',
            'pulgadas' => 'required|string|max:255',
            'idUnidad' => 'required|integer',
            'idModelo' => 'required|integer',
        ]);

        Log::info("Validación completada correctamente para artículo ID: {$id}");

        $articulo = Articulo::findOrFail($id);
        Log::debug("Artículo encontrado:", $articulo->makeHidden(['foto', 'fotosku', 'foto_codigobarras'])->toArray());

        // Campos que sí deben actualizarse
        $camposActualizar = collect($request->only([
            'codigo_barras',
            'sku',
            'codigo_repuesto',
            'stock_total',
            'stock_minimo',
            'moneda_compra',
            'moneda_venta',
            'precio_compra',
            'precio_venta',
            'pulgadas',
            'idUnidad',
            'idModelo',
        ]));

        $articulo->update($camposActualizar->toArray());

        DB::commit();

        Log::info("Artículo ID {$id} actualizado exitosamente.");
        Log::debug("Datos actualizados del artículo:", $articulo->makeHidden(['foto', 'fotosku', 'foto_codigobarras'])->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Artículo actualizado exitosamente.',
            'data' => $articulo->makeHidden(['foto', 'fotosku', 'foto_codigobarras'])
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        Log::warning("Error de validación al actualizar artículo ID {$id}: ", $e->errors());

        return response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Excepción al actualizar artículo ID {$id}: ".$e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error: '.$e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
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

            if ($articulo->foto) {
                $fotoPath = str_replace('storage/', '', $articulo->foto);
                Storage::disk('public')->delete($fotoPath);
            }

            $articulo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Artículo eliminado con éxito',
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
        $query = Articulo::with(['unidad', 'tipoarticulo', 'modelo'])
        ->where('idTipoArticulo', 2); // ✅ Filtrar solo tipo de artículo = 2
    
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
                'nombre' => $articulo->nombre,
                'codigo_repuesto' => $articulo->codigo_repuesto,
                'unidad' => $articulo->unidad->nombre ?? 'Sin Unidad',
                'codigo_barras' => $articulo->codigo_barras,
                'stock_total' => $articulo->stock_total,
                'sku' => $articulo->sku,
                'tipo_articulo' => $articulo->tipoarticulo->nombre ?? 'Sin Tipo',
                'modelo' => $articulo->modelo->nombre ?? 'Sin Modelo',
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
