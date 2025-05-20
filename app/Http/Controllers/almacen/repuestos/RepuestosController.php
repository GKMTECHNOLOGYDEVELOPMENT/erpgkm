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
        Log::error('Error actualizando artículo ID '.$id.': '.$e->getMessage()."\n".$e->getTraceAsString());
        return response()->json([
            'success' => false,
            'message' => 'Error: '.$e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
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

    public function getAll()
    {
        $articulos = Articulo::with(['unidad', 'tipoarticulo', 'modelo'])->get();
    
        $articulosData = $articulos->map(function ($articulo) {
            return [
                'idArticulos' => $articulo->idArticulos,
                'foto' => $articulo->foto ? 'data:image/jpeg;base64,' . base64_encode($articulo->foto) : null,
                'nombre' => $articulo->nombre,
                'unidad' => $articulo->unidad ? $articulo->unidad->nombre : 'Sin Unidad',
                'codigo_barras' => $articulo->codigo_barras,
                'stock_total' => $articulo->stock_total,
                'sku' => $articulo->sku,
                'tipo_articulo' => $articulo->tipoarticulo ? $articulo->tipoarticulo->nombre : 'Sin Tipo',
                'modelo' => $articulo->modelo ? $articulo->modelo->nombre : 'Sin Modelo',
                'estado' => $articulo->estado ? 'Activo' : 'Inactivo',
            ];
        });
    
        return response()->json($articulosData);
    }


    public function checkNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $exists = Articulo::where('nombre', $nombre)->exists();

        return response()->json(['unique' => !$exists]);
    }
}
