<?php

namespace App\Http\Controllers\almacen\despacho;

use App\Http\Controllers\Controller;
use App\Models\Kit;
use App\Models\Articulo;
use App\Models\KitArticulo;
use App\Models\Modelo;
use App\Models\Moneda;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class DespachoController extends Controller
{
    public function index()
    {
        // Obtener todos los kits
        $kits = Kit::with('articulos')->get();

        $unidades = Unidad::all();
        // Cargar la vista index
        return view('almacen.despacho.index', compact('kits', 'unidades'));
    }

    public function create()
    {
        // Obtener los artículos activos para el select
        $articulos = Articulo::where('estado', 1)->get();
        $unidades = Unidad::all();
        $modelos = Modelo::all();
    $monedas = Moneda::all();
    $productos = Articulo::all();
        // Cargar la vista de creación
        return view('almacen.kits-articulos.create', compact('articulos', 'unidades', 'modelos', 'monedas', 'productos'));
    }

     public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'codigo_barras' => 'required|string|max:255|unique:kit,codigo',
            'sku' => 'required|string|max:255|unique:kit,sku',
            'nombre' => 'required|string|max:255',
            'stock_total' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'moneda_venta' => 'required|integer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'productos_kit' => 'required|json'
        ]);

        // Iniciar transacción para asegurar la integridad de los datos
        DB::beginTransaction();

        try {
            // Crear el kit
            $kit = new Kit();
            $kit->codigo = $request->codigo_barras;
            $kit->sku = $request->sku;
            $kit->nombre = $request->nombre;
            $kit->stock_total = $request->stock_total;
            $kit->stock_minimo = $request->stock_minimo;
            $kit->descripcion = ''; // Puedes agregar un campo en el formulario si lo necesitas
            $kit->precio_venta = $request->precio_venta;
            $kit->monedaVenta = $request->moneda_venta;
            $kit->fecha = now();
            
            // Manejar la foto si se subió (guardar en binario)
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoBinario = file_get_contents($foto->getRealPath());
                $kit->foto = $fotoBinario;
            } else {
                // Si no se subió foto, usar la imagen por defecto
                $defaultImagePath = public_path('/assets/images/articulo/producto-default.png');
                $kit->foto = file_get_contents($defaultImagePath);
            }

            $kit->save();

            // Guardar los productos del kit
            $productosKit = json_decode($request->productos_kit, true);

            foreach ($productosKit as $producto) {
                $kitArticulo = new KitArticulo();
                $kitArticulo->idKit = $kit->idKit;
                $kitArticulo->idArticulos = $producto['id'];
                $kitArticulo->cantidad = $producto['cantidad'];
                $kitArticulo->estado = 1; // 1 = activo
                $kitArticulo->save();
            }

            // Confirmar la transacción
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kit creado correctamente',
                'kit_id' => $kit->idKit
            ]);

        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            
            Log::error('Error al crear kit: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el kit: ' . $e->getMessage()
            ], 500);
        }
    }

public function edit($id)
{
    // Buscar el kit por ID con sus artículos
    $kit = Kit::with(['articulos' => function($query) {
        $query->select('articulos.*', 'kit_articulo.cantidad');
    }])->findOrFail($id);

    // Obtener los artículos activos para el select
    $productos = Articulo::where('estado', 1)->get();
    $unidades = Unidad::all();
    $modelos = Modelo::all();
    $monedas = Moneda::all();

    // Preparar los productos del kit para JavaScript
    $productosKit = $kit->articulos->map(function($articulo) {
        return [
            'id' => $articulo->idArticulos,
            'nombre' => $articulo->nombre,
            'codigo' => $articulo->codigo_barras,
            'unidad' => $articulo->unidad->nombre ?? '',
            'cantidad' => $articulo->pivot->cantidad,
            'precio' => $articulo->precio_venta,
            'subtotal' => $articulo->pivot->cantidad * $articulo->precio_venta
        ];
    });

    // Cargar la vista de edición
    return view('almacen.kits-articulos.edit', compact('kit', 'productos', 'unidades', 'modelos', 'monedas', 'productosKit'));
}




  public function update(Request $request, $id)
{
    // Validar los datos del formulario
    $request->validate([
        'codigo_barras' => 'required|string|max:255|unique:kit,codigo,'.$id.',idKit',
        'sku' => 'required|string|max:255|unique:kit,sku,'.$id.',idkit',
        'nombre' => 'required|string|max:255',
        'stock_total' => 'required|integer|min:0',
        'stock_minimo' => 'required|integer|min:0',
        'precio_venta' => 'required|numeric|min:0',
        'moneda_venta' => 'required|integer',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'productos_kit' => 'required|json'
    ]);

    // Iniciar transacción
    DB::beginTransaction();

    try {
        // Buscar el kit a actualizar
        $kit = Kit::findOrFail($id);
        $kit->codigo = $request->codigo_barras;
        $kit->sku = $request->sku;
        $kit->nombre = $request->nombre;
        $kit->precio_venta = $request->precio_venta;
        $kit->monedaVenta = $request->moneda_venta;
        $kit->stock_total = $request->stock_total;
        $kit->stock_minimo = $request->stock_minimo;

        // Manejar la foto si se subió
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $kit->foto = file_get_contents($foto->getRealPath());
        }

        $kit->save();

        // Eliminar relaciones anteriores
        KitArticulo::where('idKit', $kit->idKit)->delete();

        // Guardar los nuevos productos del kit
        $productosKit = json_decode($request->productos_kit, true);

        foreach ($productosKit as $producto) {
            KitArticulo::create([
                'idKit' => $kit->idKit,
                'idArticulos' => $producto['id'],
                'cantidad' => $producto['cantidad'],
                'estado' => 1
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Kit actualizado correctamente',
            'kit_id' => $kit->idKit
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al actualizar kit: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el kit: ' . $e->getMessage()
        ], 500);
    }
}




    public function destroy($id)
    {
        try {
            $kit = Kit::findOrFail($id);
            $kit->delete();

            return response()->json(['message' => 'Kit eliminado con éxito'], 200);
        } catch (\Exception $e) {
            Log::error('Error al eliminar el kit: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un problema al eliminar el kit.'], 500);
        }
    }

    public function exportAllPDF()
    {
        try {
            $kits = Kit::with('articulos')->get();

            $pdf = PDF::loadView('almacen.kits-articulos.pdf.kits', compact('kits'))
                ->setPaper('a4', 'landscape');

            return $pdf->download('reporte-kits.pdf');
        } catch (\Exception $e) {
            Log::error('Error al generar el PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al generar el PDF.');
        }
    }
public function getAll(Request $request)
{
    $query = Kit::query();

    $total = $query->count();

    if ($search = $request->input('search.value')) {
        $query->where(function ($q) use ($search) {
            $q->where('nombre', 'like', "%$search%")
              ->orWhere('codigo', 'like', "%$search%")
              ->orWhere('descripcion', 'like', "%$search%");
        });
    }

    $filtered = $query->count();

    $kits = $query
        ->skip($request->start)
        ->take($request->length)
        ->get();

    $data = $kits->map(function ($kit) {
        return [
            'idKit' => $kit->idKit,
            'foto' => $kit->foto ? 'data:image/jpeg;base64,' . base64_encode($kit->foto) : null,
            'codigo' => $kit->codigo,
            'nombre' => $kit->nombre,
            'descripcion' => $kit->descripcion,
            'precio_venta' => $kit->precio_venta,
            'precio' => $kit->precio,
            'estado' => 'Activo', // Puedes cambiar esto según tu lógica
        ];
    });

    return response()->json([
        'draw' => intval($request->draw),
        'recordsTotal' => $total,
        'recordsFiltered' => $filtered,
        'data' => $data,
    ]);
}

}
