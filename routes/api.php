<?php

use App\Http\Controllers\administracion\asociados\CastController;
use App\Http\Controllers\administracion\asociados\ClienteGeneralController;
use App\Http\Controllers\administracion\asociados\ClientesController;
use App\Http\Controllers\administracion\asociados\ProveedoresController;
use App\Http\Controllers\administracion\asociados\SubsidiarioController;
use App\Http\Controllers\administracion\asociados\TiendaController;
use App\Http\Controllers\administracion\compras\ComprasController;
use App\Http\Controllers\almacen\heramientas\HeramientasController;
use App\Http\Controllers\almacen\kits\KitsController;
use App\Http\Controllers\almacen\productos\ArticulosController;
use App\Http\Controllers\almacen\productos\CategoriaController;
use App\Http\Controllers\almacen\productos\MarcaController;
use App\Http\Controllers\almacen\productos\ModelosController;
use App\Http\Controllers\almacen\productos\ProductoController;
use App\Http\Controllers\almacen\repuestos\RepuestosController;
use App\Http\Controllers\almacen\subcategoria\SubcategoriaController;
use App\Http\Controllers\almacen\suministros\SuministrosController;
use App\Http\Controllers\almacen\ubicaciones\UbicacionesController;
use App\Http\Controllers\Apps\ActividadController;
use App\Http\Controllers\Apps\CalendarController;
use App\Http\Controllers\Apps\EtiquetaController;
use App\Http\Controllers\areacomercial\ClienteSeguimientoController;
use App\Http\Controllers\areacomercial\ContactoController;
use App\Http\Controllers\areacomercial\ContactoFormController;
use App\Http\Controllers\areacomercial\CronogramaController;
use App\Http\Controllers\areacomercial\EmpresaController;
use App\Http\Controllers\areacomercial\EmpresaFormController;
use App\Http\Controllers\areacomercial\ScrumboarddController;
use App\Http\Controllers\areacomercial\SeleccionSeguimientoController;
use App\Http\Controllers\tickets\OrdenesHelpdeskController;
use App\Http\Controllers\tickets\OrdenesTrabajoController;
use App\Http\Controllers\usuario\UsuarioController;
use App\Models\Articulo;
use App\Models\Cliente;
use App\Models\CuentasBancarias;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/clientes', [ClientesController::class, 'getAll']);
Route::get('/cast', [CastController::class, 'getAll']);
Route::get('/clientegeneral', [ClienteGeneralController::class, 'getAll']);
Route::get('/proveedores', [ProveedoresController::class, 'getAll']);
Route::get('/tiendas', [TiendaController::class, 'getAll']);
Route::get('/tiendas/paginado', [TiendaController::class, 'getAllPaginado']);
Route::get('/categoria', [CategoriaController::class, 'getAll']);
Route::get('/marca', [MarcaController::class, 'getAll']);
Route::get('/modelo', [ModelosController::class, 'getAll']);
Route::get('/articulos', [ArticulosController::class, 'getAll']);
Route::get('/ordenes', [OrdenesTrabajoController::class, 'getAll']);
Route::get('/ordenes/helpdesk', [OrdenesHelpdeskController::class, 'getAll']);
Route::get('/productos', [ProductoController::class, 'getAll']);
Route::get('/repuestos', [RepuestosController::class, 'getAll']);
Route::get('/heramientas', [HeramientasController::class, 'getAll']);
Route::get('/suministros', [SuministrosController::class, 'getAll']);
Route::get('/ubicaciones', [UbicacionesController::class, 'getAllUbicaciones']);
Route::get('/kits', [KitsController::class, 'getAll']);
Route::get('subcategoria', [SubcategoriaController::class, 'getAll']);


Route::post('/check-nombre-tienda', [TiendaController::class, 'checkNombreTienda']);
// Route::get('/subsidiarios', [SubsidiarioController::class, 'getAll']);
Route::post('/check-nombre', [ClienteGeneralController::class, 'checkNombre']);

Route::post('/categoria/check-nombre', [CategoriaController::class, 'checkNombre']);
Route::post('/marca/check-nombre', [MarcaController::class, 'checkNombre']);
Route::post('/modelo/check-nombre', [ModelosController::class, 'checkNombre']);
Route::post('/articulos/check-nombre', [ArticulosController::class, 'checkNombre']);
Route::post('/ordenes/check-ticket', [OrdenesTrabajoController::class, 'checkNumeroTicket']);

Route::delete('/clientegeneral/{id}', [ClienteGeneralController::class, 'destroy']);
Route::delete('/tiendas/{id}', [TiendaController::class, 'destroy']);
Route::delete('/clientes/{id}', [ClientesController::class, 'destroy']);
Route::delete('/proveedores/{id}', [ProveedoresController::class, 'destroy']);
Route::delete('/cast/{id}', [CastController::class, 'destroy']);
Route::get('marcas', [OrdenesTrabajoController::class, 'marcaapi']);
Route::get('/clientegenerales', [OrdenesTrabajoController::class, 'clienteGeneralApi']);
Route::patch('/actualizarVisita/{id}', [OrdenesTrabajoController::class, 'actualizarVisita']);
// api.php

Route::post('/guardarAnexoVisita', [OrdenesTrabajoController::class, 'guardarAnexoVisita']);
// Ruta para guardar la foto
Route::post('/subirFoto', [OrdenesTrabajoController::class, 'guardarFoto']);
Route::get('/verificarFoto/{idVisitas}', [OrdenesTrabajoController::class, 'verificarFotoExistente']);
Route::get('/verificarRegistroAnexo/{idVisitas}', [OrdenesTrabajoController::class, 'verificarRegistroAnexo']);
// Route::get('/ticket/{ticketId}/historial-modificaciones', [OrdenesTrabajoController::class, 'obtenerHistorialModificaciones']);

Route::get('/clientegeneralfiltros/{tipo}', [ClienteGeneralController::class, 'clientegeneralFiltros']);
// routes/api.php
Route::get('/marcasfiltros', function () {
    return \App\Models\Marca::select('idMarca', 'nombre')->get();
});

Route::get('/marcasporcliente/{idClienteGeneral}', [MarcaController::class, 'getMarcasPorCliente']);

Route::post('/guardarEstado', [OrdenesTrabajoController::class, 'guardarEstado']);
Route::get('/obtenerJustificacion', [OrdenesTrabajoController::class, 'obtenerJustificacion']);
Route::post('/guardarImagenes', [OrdenesTrabajoController::class, 'guardarImagenes']);
Route::post('/guardarImagen', [OrdenesTrabajoController::class, 'guardarImagen']);
// Route::get('/api/imagenes/{ticket_id}', [OrdenesTrabajoController::class, 'obtenerImagenes']);
// Definir una ruta para obtener las imágenes de un ticket y visita específicos
Route::get('/imagenes/{ticket_id}/{visita_id}', [OrdenesTrabajoController::class, 'obtenerImagenes']);

Route::delete('/eliminarImagen/{id}', [OrdenesTrabajoController::class, 'eliminarImagen']);
// Ruta para obtener las visitas de un ticket
Route::post('/actualizarFechaLlegada/{idVisitas}', [OrdenesTrabajoController::class, 'actualizarFechaLlegada']);


Route::get('/verificarFechaLlegada/{idVisitas}', [OrdenesTrabajoController::class, 'verificarFechaLlegada']);
// Ruta para verificar si ya existe una fecha de llegada
Route::get('/verificarFechaExistente/{idVisita}', [OrdenesTrabajoController::class, 'verificarFechaExistente']);


Route::post('/seleccionar-visita', [OrdenesTrabajoController::class, 'seleccionarVisita'])->name('seleccionarVisita');
Route::post('/seleccionar-visita-levantamiento', [OrdenesHelpdeskController::class, 'seleccionarVisitaLevantamiento'])->name('seleccionarVisita');

// Ruta para verificar si una visita está seleccionada
Route::get('/visita-seleccionada/{idVisita}', [OrdenesTrabajoController::class, 'verificarVisitaSeleccionada']);
Route::get('/visita-seleccionada-levantamiento/{idVisita}', [OrdenesTrabajoController::class, 'verificarVisitaSeleccionada']);
Route::get('/suministros/{idseleccionvisita}', [OrdenesHelpdeskController::class, 'getSuministros']);


// api.php
Route::get('/usuarios-datatable', [UsuarioController::class, 'getUsuarios']);


Route::get('/usuarios/tecnico', [UsuarioController::class, 'getUsuariostecnico']);
Route::get('/usuarios/tecnico/help', [UsuarioController::class, 'getUsuariostecnicohelp']);
Route::patch('/usuarios/{id}/estado', [UsuarioController::class, 'cambiarEstado']);


Route::get('/cuentas-bancarias/{idUsuario}', function ($idUsuario) {
    // Obtener las cuentas bancarias para el usuario especificado
    $cuentasBancarias = CuentasBancarias::where('idUsuario', $idUsuario)->get();

    // Retornar las cuentas bancarias en formato repuestos
    return response()->json($cuentasBancarias);
});

Route::post('/guardar-cuenta', [UsuarioController::class, 'guardarCuenta']);
Route::get('/solicitudentrega', [OrdenesTrabajoController::class, 'obtenerSolicitudes']);
Route::put('/solicitudentrega/denegar/{id}', [OrdenesTrabajoController::class, 'denegarSolicitud']);

// routes/api.php
Route::delete('/eliminarImagenesMasivo', [OrdenesTrabajoController::class, 'eliminarImagenesMasivo']);

// Ruta para obtener los datos de un cliente específico
Route::get('/cliente/{idCliente}', [ClientesController::class, 'obtenerCliente']);

// Ruta para obtener las tiendas asociadas a un cliente
Route::get('/cliente/{idCliente}/tiendas', [ClientesController::class, 'obtenerTiendas']);

Route::get('/obtenerJustificacionSoporte', [OrdenesHelpdeskController::class, 'obtenerJustificacionSoporte']);
Route::post('/guardarEstadoSoporte', [OrdenesHelpdeskController::class, 'guardarEstadoSoporte']);

Route::put('actualizar/visitas/{id}', [OrdenesTrabajoController::class, 'updatevisita']);
Route::get('/ticketapoyo/{idVisitas}/{idTicket}', [OrdenesTrabajoController::class, 'obtenerTecnicosDeApoyo']);
// Ruta para eliminar un técnico de apoyo
Route::delete('eliminar/tecnicoapoyo/{idTicketApoyo}', [OrdenesTrabajoController::class, 'eliminar']);

// routes/web.php o routes/api.php

Route::post('/agregar/tecnicoapoyo', [OrdenesTrabajoController::class, 'agregarTecnicoApoyo']);

Route::get('/datos-envio/{ticketId}', function ($ticketId) {
    $tipo = request('tipo', 2); // Por defecto tipo 2 como pediste

    $datos = DB::table('datos_envio')
        ->where('idTickets', $ticketId)
        ->where('tipo', $tipo)
        ->first();

    return response()->json($datos);
});

Route::get('/usuarios-tecnicos', function () {
    $usuarios = DB::table('usuarios')
        ->where('idTipoArea', 6) // Ajusta según tu necesidad
        ->select('idUsuario', 'Nombre')
        ->get();

    return response()->json($usuarios);
});

Route::get('/tipos-recojo', function () {
    $tipos = DB::table('tiporecojo')->get();
    return response()->json($tipos);
});

Route::get('/tipos-envio', function () {
    $tipos = DB::table('tipoenvio')->get();
    return response()->json($tipos);
});

Route::post('/guardar-datos-envio', function (Request $request) {
    try {
        $datos = $request->all();

        $existente = DB::table('datos_envio')
            ->where('idTickets', $datos['idTickets'])
            ->where('tipo', $datos['tipo'])
            ->first();

        if ($existente) {
            DB::table('datos_envio')
                ->where('idDatos_envio', $existente->idDatos_envio)
                ->update($datos);
        } else {
            DB::table('datos_envio')->insert($datos);
        }

        return response()->json([
            'success' => true,
            'message' => 'Datos guardados correctamente',
            'data' => $datos
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
});


Route::get('/tienda/{id}', function ($id) {
    $tienda = App\Models\Tienda::find($id);

    if (!$tienda) {
        return response()->json(['error' => 'Tienda no encontrada'], 404);
    }

    return response()->json($tienda);
});


Route::get('/constancias/por-ticket/{ticketId}', [OrdenesTrabajoController::class, 'porTicket']);
Route::delete('/constancias/fotos/{fotoId}', [OrdenesTrabajoController::class, 'eliminarFoto']);

Route::get('/validar-codigo_barras', function (Request $request) {
    $query = Articulo::where('codigo_barras', $request->valor);
    if ($request->has('id')) {
        $query->where('idArticulos', '!=', $request->id);
    }
    $exists = $query->exists();
    return response()->json(['exists' => $exists]);
});

Route::get('/validar-sku', function (Request $request) {
    $query = Articulo::where('sku', $request->valor);
    if ($request->has('id')) {
        $query->where('idArticulos', '!=', $request->id);
    }
    $exists = $query->exists();
    return response()->json(['exists' => $exists]);
});

Route::get('/validar-codigo_repuesto', function (Request $request) {
    $query = Articulo::where('codigo_repuesto', $request->valor);
    if ($request->has('id')) {
        $query->where('idArticulos', '!=', $request->id);
    }
    $exists = $query->exists();
    return response()->json(['exists' => $exists]);
});


Route::get('/validar-codigo_barras-kit', function (Request $request) {
    $exists = DB::table('kit')
        ->where('codigo', $request->valor)
        ->exists();
    
    return response()->json(['exists' => $exists]);
});

Route::get('/validar-sku-kit', function (Request $request) {
    $exists = DB::table('kit')
        ->where('sku', $request->valor)
        ->exists();
    
    return response()->json(['exists' => $exists]);
});

Route::get('/api/validar-nombre-subcategoria', [SubcategoriaController::class, 'validarNombre']);



// Route::middleware('auth:sanctum')->group(function () {
//     // Rutas para actividades
//     Route::get('actividades', [ActividadController::class, 'index']);
//     Route::post('actividades', [ActividadController::class, 'store']);
//     Route::put('actividades/{id}', [ActividadController::class, 'update']);
//     Route::delete('actividades/{id}', [ActividadController::class, 'destroy']);

//     // Rutas para etiquetas
//     Route::get('etiquetas', [EtiquetaController::class, 'index']);
//     Route::post('etiquetas', [EtiquetaController::class, 'store']);
//     Route::put('etiquetas/{id}', [EtiquetaController::class, 'update']);
//     Route::delete('etiquetas/{id}', [EtiquetaController::class, 'destroy']);
// });


Route::get('/usuarios', [CalendarController::class, 'usuariov1']);
Route::get('/catalogos', [ClienteSeguimientoController::class, 'catalogos']);


Route::get('/clientes/buscar', function(Request $request) {
    $documento = $request->query('documento');
    $tipoDoc = $request->query('tipo_documento');

    if (!$documento || !$tipoDoc) {
        return response()->json(['success' => false, 'message' => 'Datos incompletos'], 400);
    }

    $cliente = \App\Models\Cliente::where('documento', $documento)
        ->where('idTipoDocumento', $tipoDoc)
        ->first();

    if ($cliente) {
        return response()->json([
            'success' => true,
            'cliente' => [
                'nombre' => $cliente->nombre,
                'telefono' => $cliente->telefono,
                'email' => $cliente->email
            ]
        ]);
    } else {
        return response()->json(['success' => false, 'message' => 'Cliente no encontrado']);
    }
});


Route::get('/consulta-ruc', function(Request $request) {
    $ruc = $request->query('numero');
    if (!$ruc) {
        return response()->json(['success' => false, 'message' => 'RUC requerido'], 400);
    }

    $token = config('services.ruc_api.token');
    $apiUrl = config('services.ruc_api.url'); // ejemplo: https://api.decolecta.com/v1/sunat/ruc

    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Authorization' => "Bearer {$token}"
    ])->get($apiUrl, ['numero' => $ruc]);

    if ($response->ok() && isset($response['ruc'])) {
        return response()->json(['success' => true, 'empresa' => $response->json()]);
    }

    return response()->json(['success' => false, 'message' => 'No se pudo obtener datos'], 404);
});


Route::post('/buscar-ruc', [EmpresaController::class, 'buscarRuc']);
// En routes/api.php
Route::get('/tipos-documento', function() {
    return \App\Models\TipoDocumento::select('idTipoDocumento', 'nombre')->get();
});

Route::get('/fuentes-captacion', function() {
    return \App\Models\FuenteCaptacion::select('id', 'nombre')->get();
});

Route::get('/niveles-decision', function() {
    return \App\Models\NivelDecision::select('id', 'nombre')
           ->orderBy('nombre') // o cualquier otro campo
           ->get();
});



Route::prefix('v1')->group(function () {
    // Contactos
    Route::post('/contactosForm', [ContactoFormController::class, 'store']);
    Route::put('/contactosForm/{id}', [ContactoFormController::class, 'update']);
    Route::delete('/contactosForm/{id}', [ContactoFormController::class, 'destroy']);

    Route::get('/empresasForm/seguimiento/{idSeguimiento}', [EmpresaFormController::class, 'getBySeguimiento']);
    
    // Empresas
    Route::get('/contactosForm/seguimiento/{idSeguimiento}', [ContactoFormController::class, 'getBySeguimiento']);
    Route::post('/empresasForm', [EmpresaFormController::class, 'store']);
    Route::put('/empresasForm/{id}', [EmpresaFormController::class, 'update']);
    Route::delete('/empresasForm/{id}', [EmpresaFormController::class, 'destroy']);
});


Route::prefix('cronograma/{idSeguimiento}')->group(function() {
    Route::get('/data', [CronogramaController::class, 'getData']);
    Route::post('/task', [CronogramaController::class, 'saveTask']); // Para crear
    Route::put('/task/{taskId}', [CronogramaController::class, 'saveTask']); // Para actualizar
    Route::delete('/task/{taskId}', [CronogramaController::class, 'deleteTask']);
    Route::post('/link', [CronogramaController::class, 'saveLink']);
    Route::delete('/link/{linkId}', [CronogramaController::class, 'deleteLink']);
    Route::post('/config', [CronogramaController::class, 'saveConfig']);
});

Route::get('/seguimientos', [ClienteSeguimientoController::class, 'getSeguimientos']);



Route::get('/usuarios/comercial', function () {
    return \App\Models\Usuario::select('idUsuario', 'Nombre', 'apellidoPaterno', 'apellidoMaterno')
        ->where('estado', 1) // Solo activos si es necesario
        ->get()
        ->map(function ($user) {
            return [
                'id' => $user->idUsuario,
                'nombre_completo' => $user->Nombre . ' ' . $user->apellidoPaterno . ' ' . $user->apellidoMaterno,
            ];
        });
});

Route::get('/reunion/{reunionId}/participantes', [ScrumboarddController::class, 'getParticipantesReunion']);
// Ruta para obtener los documentos
Route::get('/documentos', function() {
    try {
        $documentos = DB::table('documento')
            ->select('idDocumento', 'nombre')
            ->orderBy('nombre')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $documentos
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al cargar documentos'
        ], 500);
    }
});

// En routes/web.php o routes/api.php
Route::get('/getall-proveedores', [ProveedoresController::class, 'getAllProveedores']);



// APIs para cargar datos
Route::get('/monedas', [ComprasController::class, 'getMonedas'])->name('api.monedas');
Route::get('//impuestos', [ComprasController::class, 'getImpuestos'])->name('api.impuestos');
Route::get('/sujetos', [ComprasController::class, 'getSujetos'])->name('api.sujetos');
Route::get('/condiciones-compra', [ComprasController::class, 'getCondicionesCompra'])->name('api.condiciones-compra');
Route::get('/tipos-pago', [ComprasController::class, 'getTiposPago'])->name('api.tipos-pago');
Route::get('/unidades', [ComprasController::class, 'getUnidades']);
Route::get('/modelos', [ComprasController::class, 'getModelos']);
Route::post('/guardar-nuevo-articulo', [ComprasController::class, 'guardarNuevoArticuloDesdeCompra']);
Route::get('/verificar-codigo-barras', [ComprasController::class, 'verificarCodigoBarras']);
// Ruta para guardar la compra

// Rutas que ya deberías tener
// Route::get('/documentos', [ComprasController::class, 'getDocumentos'])->name('api.documentos');
// Route::get('/getall-proveedores', [ComprasController::class, 'getProveedores'])->name('api.proveedores');