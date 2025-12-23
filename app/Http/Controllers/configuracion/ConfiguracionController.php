<?php

namespace App\Http\Controllers\configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfiguracionController extends Controller
{
    public function index()
{
    $tipoOperacion = DB::table('tipo_operacion')->pluck('nombre');
    $tipoVenta = DB::table('tipo_venta')->pluck('nombre');
    $tipoVisita = DB::table('tipo_visita')->pluck('nombre');
    $tipoAlmacen = DB::table('tipoalmacen')->pluck('nombre');
    $tipoArea = DB::table('tipoarea')->pluck('nombre');
    $tipoArticulos = DB::table('tipoarticulos')->pluck('nombre');
    $tipoDocumentos = DB::table('tipodocumento')->pluck('nombre');
    $tipoIgv = DB::table('tipoigv')->pluck('nombre');
    $tipoMensaje = DB::table('tipomensaje')->pluck('nombre');
    $tipoMovimiento = DB::table('tipomovimiento')->pluck('nombre');
    $tipoPago = DB::table('tipopago')->pluck('nombre');
    $tipoPrioridad = DB::table('tipoprioridad')->pluck('nombre');
    $tipoServicio = DB::table('tiposervicio')->pluck('nombre');
    $tipoSolicitud = DB::table('tiposolicitud')->pluck('nombre');
    $tipoTickets = DB::table('tipotickets')->pluck('nombre');
    $tipoTrabajo = DB::table('tipotrabajo')->pluck('nombre');
    $tipoUsuario = DB::table('tipousuario')->pluck('nombre');
    $tipoUsuarioSoporte = DB::table('tipousuariosoporte')->pluck('nombre');
    $unidad = DB::table('unidad')->pluck('nombre');
    $moneda = DB::table('monedas')->pluck('nombre');
    $rol = DB::table('rol')->pluck('nombre');
    $rolSoftware = DB::table('rol_software')->pluck('nombre');
    $sexo = DB::table('sexo')->pluck('nombre');
    $importancia = DB::table('importancia')->pluck('nombre');
    // Agregar fuente de captación
    $fuenteCaptacion = DB::table('fuentes_captacion')->pluck('nombre');

    return view('configuracion.configuracion', compact(
        'tipoOperacion',
        'tipoVenta',
        'tipoVisita',
        'tipoAlmacen',
        'tipoArea',
        'tipoArticulos',
        'tipoDocumentos',
        'tipoIgv',
        'tipoMensaje',
        'tipoMovimiento',
        'tipoPago',
        'tipoPrioridad',
        'tipoServicio',
        'tipoSolicitud',
        'tipoTickets',
        'tipoTrabajo',
        'tipoUsuario',
        'tipoUsuarioSoporte',
        'unidad',
        'moneda',
        'rol',
        'rolSoftware',
        'sexo',
        'importancia',
        'fuenteCaptacion' // Agregar aquí
    ));
}
    

  public function store(Request $request)
{
    // Validación de los datos entrantes
    $validated = $request->validate([
        'table' => 'required|string|max:255', // Nombre de la tabla
        'column' => 'required|string|max:255', // Columna de la tabla
        'value' => 'required|string|max:255'  // Valor a insertar
    ]);

    // Asegurarse de que la tabla existe y es válida
    $allowedTables = [
        'tipo_operacion',
        'tipo_venta',
        'tipo_visita',
        'tipoalmacen',
        'tipoarea',
        'tipoarticulos',
        'tipodocumento',
        'tipoigv',
        'tipomensaje',
        'tipomovimiento',
        'tipopago',
        'tipoprioridad',
        'tiposervicio',
        'tiposolicitud',
        'tipotickets',
        'tipotrabajo',
        'tipousuario',
        'tipousuariosoporte',
        'unidad',
        'monedas',
        'rol',
        'rol_software',
        'sexo',
        'importancia',
        'fuentes_captacion' // Agregar aquí
    ];

    if (!in_array($validated['table'], $allowedTables)) {
        return response()->json(['error' => 'Tabla no permitida'], 400);
    }

    // Inserción en la base de datos
    DB::table($validated['table'])->insert([
        $validated['column'] => $validated['value']
    ]);

    return response()->json(['success' => 'Dato agregado correctamente']);
}

    public function delete(Request $request)
    {
        $data = $request->validate([
            'table' => 'required|string|max:255',
            'column' => 'required|string|max:255',
            'value' => 'required|string|max:255'
        ]);

        DB::table($data['table'])->where($data['column'], $data['value'])->delete();

        return response()->json(['success' => 'Dato eliminado correctamente']);
    }
    
}