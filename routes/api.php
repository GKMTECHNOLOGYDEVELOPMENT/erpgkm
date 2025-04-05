<?php

use App\Http\Controllers\administracion\asociados\CastController;
use App\Http\Controllers\administracion\asociados\ClienteGeneralController;
use App\Http\Controllers\administracion\asociados\ClientesController;
use App\Http\Controllers\administracion\asociados\ProveedoresController;
use App\Http\Controllers\administracion\asociados\SubsidiarioController;
use App\Http\Controllers\administracion\asociados\TiendaController;
use App\Http\Controllers\almacen\productos\ArticulosController;
use App\Http\Controllers\almacen\productos\CategoriaController;
use App\Http\Controllers\almacen\productos\MarcaController;
use App\Http\Controllers\almacen\productos\ModelosController;
use App\Http\Controllers\tickets\OrdenesHelpdeskController;
use App\Http\Controllers\tickets\OrdenesTrabajoController;
use App\Http\Controllers\usuario\UsuarioController;
use App\Models\CuentasBancarias;
use Illuminate\Http\Request;
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
Route::get('/categoria', [CategoriaController::class, 'getAll']);
Route::get('/marca', [MarcaController::class, 'getAll']);
Route::get('/modelo', [ModelosController::class, 'getAll']);
Route::get('/articulos', [ArticulosController::class, 'getAll']);
Route::get('/ordenes', [OrdenesTrabajoController::class, 'getAll']);
Route::get('/ordenes/helpdesk', [OrdenesHelpdeskController::class, 'getAll']);


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
Route::post('/guardarCondiciones', [OrdenesTrabajoController::class, 'guardar']);
Route::post('/guardarCondiciones/soporte', [OrdenesHelpdeskController::class, 'guardarSoporte']);
// Route::get('/ticket/{ticketId}/historial-modificaciones', [OrdenesTrabajoController::class, 'obtenerHistorialModificaciones']);

Route::get('/clientegeneralfiltros/{tipo}', [ClienteGeneralController::class, 'clientegeneralFiltros']);
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


Route::get('/usuarios', [UsuarioController::class, 'getUsuarios']);

Route::get('/usuarios/tecnico', [UsuarioController::class, 'getUsuariostecnico']);
Route::get('/usuarios/tecnico/help', [UsuarioController::class, 'getUsuariostecnicohelp']);
Route::patch('/usuarios/{id}/estado', [UsuarioController::class, 'cambiarEstado']);


Route::get('/cuentas-bancarias/{idUsuario}', function ($idUsuario) {
    // Obtener las cuentas bancarias para el usuario especificado
    $cuentasBancarias = CuentasBancarias::where('idUsuario', $idUsuario)->get();

    // Retornar las cuentas bancarias en formato JSON
    return response()->json($cuentasBancarias);
});

Route::post('/guardar-cuenta', [UsuarioController::class, 'guardarCuenta']);
Route::get('/solicitudentrega', [OrdenesTrabajoController::class, 'obtenerSolicitudes']);
// Ruta para aceptar la solicitud (estado = 1)
// Ruta para rechazar la solicitud (estado = 2)
Route::put('/solicitudentrega/denegar/{id}', [OrdenesTrabajoController::class, 'denegarSolicitud']);


// Ruta para obtener los datos de un cliente específico
Route::get('/cliente/{idCliente}', [ClientesController::class, 'obtenerCliente']);

// Ruta para obtener las tiendas asociadas a un cliente
Route::get('/cliente/{idCliente}/tiendas', [ClientesController::class, 'obtenerTiendas']);

Route::get('/obtenerJustificacionSoporte', [OrdenesHelpdeskController::class, 'obtenerJustificacionSoporte']);
Route::post('/guardarEstadoSoporte', [OrdenesHelpdeskController::class, 'guardarEstadoSoporte']);

Route::put('actualizar/visitas/{id}', [OrdenesTrabajoController::class, 'updatevisita']);
