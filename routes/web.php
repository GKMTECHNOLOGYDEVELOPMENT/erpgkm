<?php

use App\Events\MensajeEnviado;
use App\Http\Controllers\administracion\asociados\CastController;
use App\Http\Controllers\administracion\asociados\ClienteGeneralController;
use App\Http\Controllers\administracion\asociados\ClientesController;
use App\Http\Controllers\administracion\asociados\ProveedoresController;
use App\Http\Controllers\administracion\asociados\SubsidiarioController;
use App\Http\Controllers\administracion\asociados\TiendaController;
use App\Http\Controllers\administracion\cotizaciones\cotizacionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\administracion\areas\AreasController;
use App\Http\Controllers\dashboard\AdministracionController;
use App\Http\Controllers\dashboard\AlmacenController;
use App\Http\Controllers\dashboard\ComercialController;
use App\Http\Controllers\dashboard\TicketsController;
use App\Http\Controllers\administracion\UsuariosController;
use App\Http\Controllers\administracion\CompraController;
use App\Http\Controllers\administracion\asistencias\AsistenciaController;
use App\Http\Controllers\almacen\productos\ArticulosController;
use App\Http\Controllers\almacen\productos\ModelosController;
use App\Http\Controllers\almacen\productos\TipoArticuloController;
use App\Http\Controllers\almacen\productos\MarcaController;
use App\Http\Controllers\almacen\productos\CategoriasController;
use App\Http\Controllers\configuracion\ConfiguracionController;
use App\Http\Controllers\permisos\PermisosController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LockscreenController;
use App\Http\Controllers\Auth\PasswordResetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apps\ChatController;
use App\Http\Controllers\Politicas\PoliticasController;
use App\Http\Controllers\Apps\MailboxController;
use App\Http\Controllers\Apps\TodolistController;
use App\Http\Controllers\almacen\asignarArticulo\AsignarArticuloController;
use App\Http\Controllers\Apps\NotesController;
use App\Http\Controllers\Apps\ScrumboardController;
use App\Http\Controllers\Apps\ContactsController;
use App\Http\Controllers\Apps\CalendarController;
use App\Models\Clientegeneral;
use App\Http\Controllers\tickets\OrdenesHelpdeskController;
use App\Models\Subsidiario;
use Illuminate\Http\Client\Request;
use App\Exports\ClientesGeneralExport;
use App\Exports\ClientesExport;
use App\Exports\TiendaExport;
use App\Exports\CastExport;
use App\Http\Controllers\almacen\productos\CategoriaController;
use App\Http\Controllers\tickets\OrdenesTrabajoController;
use App\Http\Controllers\almacen\kits\KitsController;
use App\Exports\ProveedoresExport;
use App\Exports\MarcasExport;
use App\Exports\CategoriaExport;
use App\Exports\ArticuloExport;
use App\Exports\ModeloExport;
use App\Http\Controllers\administracion\asociados\ContactoFinalController;
use App\Http\Controllers\administracion\compras\ComprasController;
use App\Http\Controllers\administracion\cotizacionesl\cotizacionlController as CotizacioneslCotizacionlController;
use App\Http\Controllers\administracion\cotizacionesls\cotizacionlController;
use App\Http\Controllers\administracion\movimiento\entrada\EntradaController;
use App\Http\Controllers\administracion\movimiento\salida\SalidaController;
use App\Http\Controllers\administracion\permisos\PermisosController as PermisosPermisosController;
use App\Http\Controllers\almacen\custodia\CustodiaController;
use App\Http\Controllers\almacen\despacho\DespachoController;
use App\Http\Controllers\almacen\devoluciones\DevolucionesController;
use App\Http\Controllers\almacen\heramientas\HeramientasController;
use App\Http\Controllers\almacen\kardex\KardexController;
use App\Http\Controllers\almacen\productos\ProductoController;
use App\Http\Controllers\almacen\repuestos\RepuestosController;

use App\Http\Controllers\almacen\subcategoria\SubcategoriaController;
use App\Http\Controllers\almacen\suministros\SuministrosController;
use App\Http\Controllers\almacen\ubicaciones\UbicacionesArticuloController;
use App\Http\Controllers\almacen\ubicaciones\UbicacionesController;
use App\Http\Controllers\almacen\ubicaciones\UbicacionesVistaController;
use App\Http\Controllers\Apps\ActividadController;
use App\Http\Controllers\Apps\EtiquetaController;
use App\Http\Controllers\areacomercial\ClienteSeguimientoController;
use App\Http\Controllers\areacomercial\ContactoController;
use App\Http\Controllers\areacomercial\ContactoFormController;
use App\Http\Controllers\areacomercial\CronogramaController;
use App\Http\Controllers\areacomercial\EmpresaController;
use App\Http\Controllers\areacomercial\EmpresaFormController;
use App\Http\Controllers\areacomercial\NoteController;
use App\Http\Controllers\areacomercial\ObservacionController;
use App\Http\Controllers\areacomercial\ScrumboarddController;
use App\Http\Controllers\areacomercial\SeleccionSeguimientoController;
use App\Http\Controllers\areacomercial\TagController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\entradasproveedores\EntradasproveedoresController;
use App\Http\Controllers\GuiaController;
use App\Http\Controllers\PasswordRecoveryController;
use App\Http\Controllers\solicitud\SolicitudalmacenController;
use App\Http\Controllers\solicitud\SolicitudarticuloController;
use App\Http\Controllers\solicitud\SolicitudcompraController;
use App\Http\Controllers\solicitud\SolicitudingresoController;
use App\Http\Controllers\solicitud\SolicitudrepuestoController;
use App\Http\Controllers\UbigeoController;
use App\Http\Controllers\usuario\UsuarioController;
use App\Models\Cliente;
use App\Models\Custodia;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Subcategoria;
use App\Models\Suministro;
use App\Models\Tienda;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\unity\UnityController;
use App\Http\Controllers\usuario\VentasController;
use App\Http\Controllers\ventas\VentasController as VentasVentasController;

Auth::routes();

Route::prefix('unity')->name('unity.')->group(function () {
    Route::get('/{id}/solicitud', [UnityController::class, 'solicitud'])
        ->whereNumber('id')->name('solicitud.byId');

    Route::get('/solicitud', [UnityController::class, 'solicitud'])
        ->name('solicitud.byQuery');

    Route::get('/{id}/solicitud/data', [UnityController::class, 'solicitudData'])
        ->whereNumber('id')->name('solicitud.data');

    Route::post('/{id}/solicitud/registrar', [UnityController::class, 'registrarAccion'])
        ->whereNumber('id')->name('solicitud.registrar');

    // ===== Nuevas rutas usando UnityController =====
    Route::get('/racks/modelo/create', [UnityController::class, 'racksModeloCreate'])
        ->name('racks.modelo.create');

    Route::get('/racks/asignar', [UnityController::class, 'racksAsignarIndex'])
        ->name('racks.asignar.index');

    Route::get('/cajas/create', [UnityController::class, 'cajasCreate'])
        ->name('cajas.create');

    Route::get('/almacen/vistageneral', [UnityController::class, 'vistaGeneral'])
        ->name('vistageneral.index');
});

// Rutas MÁS ESPECÍFICAS primero
Route::get('almacen/ubicaciones/qr/vista/{nombre}', [UbicacionesVistaController::class, 'vistaQR']);
Route::get('almacen/ubicaciones/qr/spark/{nombre}', [UbicacionesVistaController::class, 'vistaSparkQR']);

// Ruta MÁS GENÉRICA al final
Route::get('/almacen/ubicaciones/qr/{nombre}', [UbicacionesVistaController::class, 'generarQrPorNombre']);


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Ruta para mostrar el formulario de login
// Route::get('/login', function () { return view('auth.login'); })->name('login');
// Ruta para manejar el envío del formulario de login
Route::post('/login', [LoginController::class, 'login'])->name('login');
// Ruta para cerrar sesión
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// Ruta protegida con middleware 'auth'
Route::post('/check-email', [AuthController::class, 'checkEmail']);
// Ruta para la pantalla de bloqueo
Route::get('/auth/cover-lockscreen', [LockscreenController::class, 'show'])->name('auth.lockscreen');


// 1. RUTA para MOSTRAR el formulario de solicitud de reset (SIN token)
Route::get('/password/reset', [PasswordResetController::class, 'showPasswordResetForm'])
    ->name('password.request');

// 2. RUTA para ENVIAR el enlace de reset (POST)
Route::post('/password/email', [PasswordResetController::class, 'sendResetLink'])
    ->name('password.email');

// 3. RUTA para MOSTRAR formulario con token (CON token)
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])
    ->name('password.reset');

// 4. RUTA para ACTUALIZAR la contraseña (POST con token)
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword'])
    ->name('password.update');

// Opcional: Puedes mantener tu ruta personalizada si quieres
Route::get('/auth/cover-password-reset', [PasswordResetController::class, 'showPasswordResetForm'])
    ->name('auth.password-reset');



Route::middleware(['auth', 'permiso:VER DASHBOARD ADMINISTRACION'])->group(function () {
    Route::get('/', [AdministracionController::class, 'index'])->name('index');
});
Route::get('/configuracion', [ConfiguracionController::class, 'index'])
    ->name('configuracion')
    ->middleware(['auth', 'permiso:VER CONFIGURACION']);

Route::post('/configuracion', [ConfiguracionController::class, 'store'])->name('configuracion.store')->middleware('auth');
Route::post('/configuracion/delete', [ConfiguracionController::class, 'delete'])->name('configuracion.delete');
// Ruta para el dashboard de almacén
Route::middleware(['auth', 'permiso:VER DASHBOARD ALMACEN'])->group(function () {
    Route::get('/almacen', [AlmacenController::class, 'index'])->name('almacen');
}); // Ruta para el dashboard comercial
Route::middleware(['auth', 'permiso:VER DASHBOARD COMERCIAL'])->group(function () {
    Route::get('/comercial', [ComercialController::class, 'index'])->name('commercial');
});
// Ruta para el dashboard de tickets
Route::middleware(['auth', 'permiso:VER DASHBOARD TICKETS'])->group(function () {
    Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets');
});
// Ruta para Administración de Usuarios
Route::get('/administracion/usuarios', [UsuariosController::class, 'index'])->name('administracion.usuarios')->middleware('auth');
// Ruta para Administración de Compras
Route::get('/administracion/compras', [ComprasController::class, 'index'])->name('administracion.compra')->middleware('auth');
//Rutas para Clientes Generales
Route::get('/cliente-general', [ClienteGeneralController::class, 'index'])
    ->name('administracion.cliente-general')
    ->middleware(['auth', 'permiso:VER CLIENTE GENERAL']);

Route::get('/cliente-general/{id}/edit', [ClienteGeneralController::class, 'edit'])->name('cliente-general.edit');


Route::get('/exportar-clientes-general', function () {
    return Excel::download(new ClientesGeneralExport, 'clientes_general.xlsx');
})->name('clientes-general.exportExcel');
Route::get('/clientes-general/export-pdf', [ClienteGeneralController::class, 'exportAllPDF'])->name('clientes-general.exportPDF')->middleware('auth');
// Actualizar los datos del cliente general
Route::put('administracion/{id}', [ClienteGeneralController::class, 'update'])->name('cliente-general.update');
Route::post('/cliente-general/store', [ClienteGeneralController::class, 'store'])->name('cliente-general.store');
Route::get('/ubigeo/provincias/{departamento_id}', [UbigeoController::class, 'getProvinciasByDepartamento']);
Route::get('/ubigeo/distritos/{provincia_id}', [UbigeoController::class, 'getDistritosByProvincia']);
Route::get('/get-provincia/{departamentoId}', [UbigeoController::class, 'getProvincias']);
Route::get('/get-distrito/{provinciaId}', [UbigeoController::class, 'getDistritos']);
//Rutas para Tiendas
Route::get('/tienda', [TiendaController::class, 'index'])
    ->name('administracion.tienda')
    ->middleware(['auth', 'permiso:VER TIENDA']);
Route::post('/tiendas', [TiendaController::class, 'store'])->name('tiendas.store');
Route::get('/tienda/{idTienda}/edit', [TiendaController::class, 'edit'])->name('tienda.edit');
Route::put('/tienda/{idTienda}', [TiendaController::class, 'update'])->name('tiendas.update');
Route::get('/tienda/create', [TiendaController::class, 'create'])->name('tienda.create')->middleware('auth');
Route::delete('/tienda/{idTienda}', [TiendaController::class, 'destroy'])->name('tienda.destroy');
Route::get('/exportar-tiendas', function () {
    return Excel::download(new TiendaExport, 'reporte_tiendas.xlsx');
})->name('tiendas.exportExcel');
Route::get('/reporte-tiendas', [TiendaController::class, 'exportAllPDF'])->name('reporte.tiendas');
Route::get('/obtenerClientesGenerales', [ClienteGeneralController::class, 'obtenerClientesGenerales']);
Route::get('/check-marcas', [MarcaController::class, 'checkMarcas']);
Route::get('/check-tiendas', [TiendaController::class, 'checkTiendas']);
// // Ruta para actualizar una tienda
// Route::put('/api/tiendas/{id}', [TiendaController::class, 'update']);
Route::post('/check-nombre', function (Request $request) {
    $nombre = $request->input('nombre');
    $exists = Clientegeneral::where('descripcion', $nombre)->exists();
    return response()->json(['unique' => !$exists]);
});
// Ruta para Administracion de tiendas
// Ruta para Administracion Subsidiario
//Ruta para Administracion Cast
Route::get('/cast', [CastController::class, 'index'])
    ->name('administracion.cast')
    ->middleware(['auth', 'permiso:VER CAST']);

Route::post('/cast/store', [CastController::class, 'store'])->name('cast.store');
Route::get('/cast/{idCast}/edit', [CastController::class, 'edit'])->name('cast.edit');
Route::put('/cast/{idCast}', [CastController::class, 'update'])->name('casts.update');
Route::get('/exportar-cast', function () {
    return Excel::download(new CastExport, 'cast.xlsx');
})->name('cast.exportExcel');
Route::get('/reporte-cast', [CastController::class, 'exportAllPDF'])->name('reporte.cast');
// Route::get('/casts', [CastController::class, 'getAll']);
// Ruta para Administracion Subsidiario
// Route::get('/sub-sidiario/create', [SubsidiarioController::class, 'create'])->name('administracion.create')->middleware('auth');
//Ruta para Administracion Clientes
Route::get('/clientes', [ClientesController::class, 'index'])
    ->name('administracion.clientes')
    ->middleware(['auth', 'permiso:VER CLIENTES']);
Route::post('/cliente/store', [ClientesController::class, 'store'])->name('cliente.store');
Route::get('/cliente/{idCliente}/edit', [ClientesController::class, 'edit'])->name('cliente.edit');
Route::put('/clientes/{idCliente}', [ClientesController::class, 'update'])->name('clientes.update');
Route::get('/exportar-clientes', function () {
    return Excel::download(new ClientesExport, 'clientes.xlsx');
})->name('clientes.exportExcel');
Route::get('/reporte-clientes', [ClientesController::class, 'exportAllPDF'])->name('reporte.clientes');
//Ruta para Administracion Asistencia
Route::get('/usuario', [UsuarioController::class, 'index'])
    ->name('usuario')
    ->middleware(['auth', 'permiso:VER USUARIOS']);
Route::post('/asistencias/actualizar-observacion', [AsistenciaController::class, 'actualizarEstadoObservacion']);
Route::get('/asistencias/observacion/{id}', [AsistenciaController::class, 'obtenerImagenesObservacion']);
Route::get('/asistencias/historial/{id}', [AsistenciaController::class, 'verHistorialUsuario'])->name('asistencias.historial.usuario');
Route::post('/asistencias/responder-observacion', [AsistenciaController::class, 'responderObservacion']);
Route::get('/asistencias/observaciones-dia/{idUsuario}/{fecha}', [AsistenciaController::class, 'observacionesPorDia']);
Route::get('/asistencias/listado', [AsistenciaController::class, 'getAsistencias'])->name('asistencias.listado');
//Ruta para Administracion Proveedores
Route::get('/proveedores', [ProveedoresController::class, 'index'])
    ->name('administracion.proveedores')
    ->middleware(['auth', 'permiso:VER PROVEEDORES']);
Route::post('/proveedores/store', [ProveedoresController::class, 'store'])->name('proveedor.store');
Route::get('/proveedores/{idProveedor}/edit', [ProveedoresController::class, 'edit'])->name('proveedor.edit');
Route::put('/proveedores/{idProveedor}', [ProveedoresController::class, 'update'])->name('proveedores.update');
Route::get('/exportar-proveedores', function () {
    return Excel::download(new ProveedoresExport, 'proveedores.xlsx');
})->name('proveedores.exportExcel');
Route::get('/reporte-proveedores', [ProveedoresController::class, 'exportAllPDF'])->name('proveedores.pdf');
//Ruta para administracion cotizaciones
Route::get('/cotizaciones/crear-cotizacion', [cotizacionController::class, 'index'])->name('cotizaciones.crear-cotizacion')->middleware('auth');
Route::get('/apps/chat', [ChatController::class, 'index'])
    ->name('apps.chat')
    ->middleware(['auth', 'permiso:VER CHAT']);

Route::get('/apps/mailbox', [MailboxController::class, 'index'])
    ->name('apps.mailbox')
    ->middleware(['auth', 'permiso:VER CORREO']);

Route::get('/apps/todolist', [TodolistController::class, 'index'])
    ->name('apps.todolist')
    ->middleware(['auth', 'permiso:VER LISTA CORREO']);

Route::get('/apps/notes', [NotesController::class, 'index'])
    ->name('apps.notes')
    ->middleware(['auth', 'permiso:VER NOTAS']);

Route::get('/apps/scrumboard', [ScrumboardController::class, 'index'])
    ->name('apps.scrumboard')
    ->middleware(['auth', 'permiso:VER SCRUMBOARD']);

Route::get('/apps/contacts', [ContactsController::class, 'index'])
    ->name('apps.contacts')
    ->middleware(['auth', 'permiso:VER CONTACTOS']);

Route::get('/apps/calendar', [CalendarController::class, 'index'])
    ->name('apps.calendar')
    ->middleware(['auth', 'permiso:VER CALENDARIO']);

// Route::view('/apps/chat', 'apps.chat');
// Route::view('/apps/mailbox', 'apps.mailbox');
// Route::view('/apps/todolist', 'apps.todolist');
// Route::view('/apps/notes', 'apps.notes');
// Route::view('/apps/scrumboard', 'apps.scrumboard');
// Route::view('/apps/contacts', 'apps.contacts');
// Route::view('/apps/calendar', 'apps.calendar');
/// MODULO DE ALMACEN ///
// INICIO CATEGORIA ///
Route::prefix('categoria')->name('categorias.')->group(function () {
    Route::post('/store', [CategoriaController::class, 'store'])->name('store'); // Guardar una nueva categoría
    Route::get('/{id}/edit', [CategoriaController::class, 'edit'])->name('edit'); // Editar una categoría
    Route::put('/update/{id}', [CategoriaController::class, 'update'])->name('update'); // Actualizar una categoría
    // Route::delete('/{id}', [CategoriaController::class, 'destroy'])->name('destroy'); // Eliminar una categoría
    Route::get('/reporte-categorias', [CategoriaController::class, 'exportAllPDF'])->name('categorias.pdf'); // Exportar todas las categorías a PDF
    Route::get('/get-all', [CategoriaController::class, 'getAll'])->name('getAll'); // Obtener todas las categorías en formato JSON
    Route::post('/check-nombre', [CategoriaController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::get('/exportar-excel', function () {
        return Excel::download(new CategoriaExport, 'categorias.xlsx');
    })->name('exportExcel');
});

Route::prefix('categoria')->name('categorias.')->middleware('permiso:VER CATEGORIA')->group(function () {
    Route::get('/', [CategoriaController::class, 'index'])->name('index'); // Index
});






// INICIO UBICACIONES ///
Route::prefix('ubicaciones')->name('ubicaciones.')->group(function () {
    Route::get('/', [UbicacionesController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::get('/create', [UbicacionesController::class, 'create'])->name('create'); // Guardar una nueva categoría
    Route::post('/store', [UbicacionesController::class, 'store'])->name('store'); // Guardar una nueva categoría
    Route::get('/{id}/edit', [UbicacionesController::class, 'edit'])->name('edit'); // Editar una categoría
    Route::put('/update/{id}', [UbicacionesController::class, 'update'])->name('update'); // Actualizar una categoría
    // Route::delete('/{id}', [UbicacionesController::class, 'destroy'])->name('destroy'); // Eliminar una categoría
    Route::get('/reporte-ubicaciones', [UbicacionesController::class, 'exportAllPDF'])->name('ubicaciones.pdf'); // Exportar todas las categorías a PDF
    Route::get('/get-all', [UbicacionesController::class, 'getAll'])->name('getAll'); // Obtener todas las categorías en formato JSON
    Route::post('/check-nombre', [UbicacionesController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::delete('/{id}', [UbicacionesController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/exportar-excel', function () {
        return Excel::download(new CategoriaExport, 'ubicaciones.xlsx');
    })->name('exportExcel');
});


// INICIO CATEGORIA ///
Route::prefix('ubicacionesarticulo')->name('ubicacionesarticulo.')->group(function () {
    Route::get('/', [UbicacionesArticuloController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::get('/create', [UbicacionesController::class, 'create'])->name('create'); // Guardar una nueva categoría
    Route::post('/store', [UbicacionesController::class, 'store'])->name('store'); // Guardar una nueva categoría
    Route::get('/{id}/edit', [UbicacionesController::class, 'edit'])->name('edit'); // Editar una categoría
    Route::put('/update/{id}', [UbicacionesController::class, 'update'])->name('update'); // Actualizar una categoría
    // Route::delete('/{id}', [UbicacionesController::class, 'destroy'])->name('destroy'); // Eliminar una categoría
    Route::get('/reporte-ubicaciones', [UbicacionesController::class, 'exportAllPDF'])->name('ubicaciones.pdf'); // Exportar todas las categorías a PDF
    Route::get('/get-all', [UbicacionesController::class, 'getAll'])->name('getAll'); // Obtener todas las categorías en formato JSON
    Route::post('/check-nombre', [UbicacionesController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::delete('/{id}', [UbicacionesController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/exportar-excel', function () {
        return Excel::download(new CategoriaExport, 'ubicaciones.xlsx');
    })->name('exportExcel');
});








Route::delete('categorias/{id}', [CategoriaController::class, 'destroy'])->name('destroy');
/// FIN CATEGORIA ///
/// INICIO MARCA ///
Route::prefix('marcas')->name('marcas.')->group(function () {
    Route::post('/store', [MarcaController::class, 'store'])->name('store'); // Guardar una nueva marca
    Route::get('/{id}/edit', [MarcaController::class, 'edit'])->name('edit'); // Editar una marca
    Route::put('/update/{id}', [MarcaController::class, 'update'])->name('update'); // Actualizar una marca
    Route::delete('/{id}', [MarcaController::class, 'destroy'])->name('destroy'); // Eliminar una marca
    Route::get('/reporte-marcas', [MarcaController::class, 'exportAllPDF'])->name('marcas.pdf'); // Exportar todas las marcas a PDF
    Route::get('/get-all', [MarcaController::class, 'getAll'])->name('getAll'); // Obtener todas las marcas en formato JSON
    Route::post('/check-nombre', [MarcaController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::get('/exportar-excel', function () {
        return Excel::download(new MarcasExport, 'marcas.xlsx');
    })->name('exportExcel');
});

Route::prefix('marcas')->name('marcas.')->middleware('permiso:VER MARCAS')->group(function () {
    Route::get('/', [MarcaController::class, 'index'])->name('index'); // Index
});


/// FI
/// FIN MARCA ///
/// INICIO MODELO ///
Route::prefix('modelos')->name('modelos.')->group(function () {
    Route::post('/store', [ModelosController::class, 'store'])->name('store'); // Guardar un nuevo modelo
    Route::get('/{id}/edit', [ModelosController::class, 'edit'])->name('edit'); // Editar un modelo
    Route::put('/update/{id}', [ModelosController::class, 'update'])->name('update'); // Actualizar un modelo
    Route::delete('/{id}', [ModelosController::class, 'destroy'])->name('destroy'); // Eliminar un modelo
    Route::get('/export-pdf', [ModelosController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar todos los modelos a PDF
    Route::get('/get-all', [ModelosController::class, 'getAll'])->name('getAll'); // Obtener todos los modelos en formato JSON
    Route::post('/check-nombre', [ModelosController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::get('/exportar-excel', function () {
        return Excel::download(new ModeloExport, 'modelos.xlsx');
    })->name('exportExcel');
});

Route::prefix('modelos')->name('modelos.')->middleware('permiso:VER MODELOS')->group(function () {
    Route::get('/', [ModelosController::class, 'index'])->name('index'); // Index
});





/// FIN MODELO ///
/// INICIO ARTICULOS ///
Route::prefix('articulos')->name('articulos.')->group(function () {
    Route::get('/', [ArticulosController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::get('/create', [ArticulosController::class, 'create'])->name('create'); // Formulario de creación
    Route::post('/store', [ArticulosController::class, 'store'])->name('store'); // Guardar un nuevo artículo
    Route::get('/{id}/edit', [ArticulosController::class, 'edit'])->name('edit'); // Editar un artículo
    Route::get('/{id}/detalles', [ArticulosController::class, 'detalle'])->name('detalles'); // Editar un artículo
    Route::get('/{id}/imagen', [ArticulosController::class, 'imagen'])->name('imagen'); // Editar un artículo
    Route::put('/update/{id}', [ArticulosController::class, 'update'])->name('update'); // Actualizar un artículo
    Route::delete('/{id}', [ArticulosController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/export-pdf', [ArticulosController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar todos los artículos a PDF
    Route::get('/get-all', [ArticulosController::class, 'getAll'])->name('getAll'); // Obtener todos los artículos en formato JSON
    Route::post('/check-nombre', [ArticulosController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::post('/{id}/fotoupdate', [ArticulosController::class, 'updateFoto']);
    Route::delete('/{id}/fotodelete', [ArticulosController::class, 'deleteFoto']);
    Route::get('/exportar-excel', function () {
        return Excel::download(new ArticuloExport, 'articulos.xlsx');
    })->name('exportExcel');
});
/// INICIO ARTICULOS ///
Route::prefix('repuestos')->name('repuestos.')->group(function () {
    Route::get('/create', [RepuestosController::class, 'create'])->name('create'); // Formulario de creación
    Route::post('/store', [RepuestosController::class, 'store'])->name('store'); // Guardar un nuevo artículo
    Route::get('/entrada', [EntradaController::class, 'entrada'])->name('entrada');
    Route::get('/salida', [SalidaController::class, 'salida'])->name('salida');
    Route::get('/{id}/imagen', [RepuestosController::class, 'imagen'])->name('imagen'); // Editar un artículo
    Route::post('/{id}/fotoupdate', [RepuestosController::class, 'updateFoto']);
    Route::get('/{id}/kardex', [RepuestosController::class, 'kardex'])->name('kardex'); // Editar un artículo
    Route::get('/{id}/edit', [RepuestosController::class, 'edit'])->name('edit'); // Editar un artículo
    Route::get('/{id}/detalles', [RepuestosController::class, 'detalle'])->name('detalles'); // Editar un artículo
    Route::put('/update/{id}', [RepuestosController::class, 'update'])->name('update'); // Actualizar un artículo
    Route::delete('/{id}', [RepuestosController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/export-pdf', [RepuestosController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar todos los artículos a PDF
    Route::get('/get-all', [RepuestosController::class, 'getAll'])->name('getAll'); // Obtener todos los artículos en formato JSON
    Route::post('/check-nombre', [RepuestosController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::get('/exportar-excel', function () {
        return Excel::download(new ArticuloExport, 'repuestos.xlsx');
    })->name('exportExcel');
});

Route::prefix('repuestos')->name('repuestos.')->group(function () {
    Route::get('/', [RepuestosController::class, 'index'])
        ->name('index')
        ->middleware('permiso:VER REPUESTOS');
});
/// INICIO KARDEX ///
Route::prefix('kardex')->name('kardex.')->group(function () {
    Route::get('/', [KardexController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::get('/create', [RepuestosController::class, 'create'])->name('create'); // Formulario de creación
    Route::post('/store', [RepuestosController::class, 'store'])->name('store'); // Guardar un nuevo artículo
    Route::get('/{id}/imagen', [RepuestosController::class, 'imagen'])->name('imagen'); // Editar un artículo
    Route::post('/{id}/fotoupdate', [RepuestosController::class, 'updateFoto']);
    Route::get('/{id}/edit', [RepuestosController::class, 'edit'])->name('edit'); // Editar un artículo
    Route::get('/{id}/detalles', [RepuestosController::class, 'detalle'])->name('detalles'); // Editar un artículo
    Route::put('/update/{id}', [RepuestosController::class, 'update'])->name('update'); // Actualizar un artículo
    Route::delete('/{id}', [RepuestosController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/export-pdf', [RepuestosController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar todos los artículos a PDF
    Route::get('/get-all', [RepuestosController::class, 'getAll'])->name('getAll'); // Obtener todos los artículos en formato JSON
    Route::post('/check-nombre', [RepuestosController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::get('/exportar-excel', function () {
        return Excel::download(new ArticuloExport, 'kardex.xlsx');
    })->name('exportExcel');
});
/// INICIO DESPACHO ///
Route::prefix('despacho')->name('despacho.')->group(function () {
    Route::get('/', [DespachoController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::get('/create', [DespachoController::class, 'create'])->name('create'); // Formulario de creación
    Route::get('/show', [DespachoController::class, 'show'])->name('show'); // Formulario de creación
    Route::get('/pdf', [DespachoController::class, 'pdf'])->name('pdf'); // Formulario de creación

    Route::post('/store', [DespachoController::class, 'store'])->name('store'); // Guardar un nuevo artículo
    Route::get('/{id}/imagen', [RepuestosController::class, 'imagen'])->name('imagen'); // Editar un artículo
    Route::post('/{id}/fotoupdate', [RepuestosController::class, 'updateFoto']);
    Route::get('/{id}/edit', [DespachoController::class, 'edit'])->name('edit'); // Editar un artículo
    Route::get('/{id}/detalles', [RepuestosController::class, 'detalle'])->name('detalles'); // Editar un artículo
    Route::put('/update/{id}', [RepuestosController::class, 'update'])->name('update'); // Actualizar un artículo
    Route::delete('/{id}', [RepuestosController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/export-pdf', [RepuestosController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar todos los artículos a PDF
    Route::get('/get-all', [RepuestosController::class, 'getAll'])->name('getAll'); // Obtener todos los artículos en formato JSON
    Route::post('/check-nombre', [RepuestosController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::get('/exportar-excel', function () {
        return Excel::download(new ArticuloExport, 'despacho.xlsx');
    })->name('exportExcel');
});
/// INICIO VENTAS ///
Route::prefix('ventas')->name('ventas.')->group(function () {
    Route::get('/', [VentasVentasController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::get('/create', [VentasVentasController::class, 'create'])->name('create'); // Formulario de creación
    Route::post('/store', [VentasVentasController::class, 'store'])->name('store'); // Guardar un nuevo artículo
    Route::get('/{id}/edit', [VentasVentasController::class, 'edit'])->name('edit'); // Editar un artículo
    Route::get('/{id}/detalles', [VentasVentasController::class, 'detalle'])->name('detalles'); // Editar un artículo
    Route::put('/update/{id}', [VentasVentasController::class, 'update'])->name('update'); // Actualizar un artículo
    Route::delete('/{id}', [VentasVentasController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/export-pdf', [VentasVentasController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar todos los artículos a PDF
    Route::get('/get-all', [VentasVentasController::class, 'getAll'])->name('getAll'); // Obtener todos los artículos en formato JSON

});

/// INICIO VENTAS ///
Route::prefix('cotizaciones')->name('cotizaciones.')->group(function () {
    Route::get('/', [cotizacionController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::get('/create', [cotizacionController::class, 'create'])->name('create'); // Formulario de creación
    Route::post('/store', [cotizacionController::class, 'store'])->name('store'); // Guardar un nuevo artículo
    Route::get('/{id}/imagen', [cotizacionController::class, 'imagen'])->name('imagen'); // Editar un artículo
    Route::post('/{id}/fotoupdate', [cotizacionController::class, 'updateFoto']);
    Route::get('/{id}/edit', [cotizacionController::class, 'edit'])->name('edit'); // Editar un artículo
    Route::get('/{id}/detalles', [cotizacionController::class, 'detalle'])->name('detalles'); // Editar un artículo
    Route::put('/update/{id}', [cotizacionController::class, 'update'])->name('update'); // Actualizar un artículo
    Route::delete('/{id}', [cotizacionController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/export-pdf', [cotizacionController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar todos los artículos a PDF
    Route::get('/get-all', [cotizacionController::class, 'getAll'])->name('getAll'); // Obtener todos los artículos en formato JSON
    Route::post('/check-nombre', [cotizacionController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::get('/exportar-excel', function () {
        return Excel::download(new ArticuloExport, 'cotizaciones.xlsx');
    })->name('exportExcel');
});
/// INICIO COMPRAS ///
Route::prefix('compras')->name('compras.')->group(function () {
    Route::get('/', [ComprasController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::get('/data', [ComprasController::class, 'data'])->name('data'); // Mostrar la vista principal
    Route::put('/{idCompra}/estado', [ComprasController::class, 'updateEstado'])->name('compras.update-estado');
    Route::get('/create', [ComprasController::class, 'create'])->name('create'); // Formulario de creación
    Route::post('/store', [RepuestosController::class, 'store'])->name('store'); // Guardar un nuevo artículo
    Route::get('/{id}/imagen', [RepuestosController::class, 'imagen'])->name('imagen'); // Editar un artículo
    Route::post('/{id}/fotoupdate', [RepuestosController::class, 'updateFoto']);
    Route::get('/{id}/edit', [RepuestosController::class, 'edit'])->name('edit'); // Editar un artículo
    Route::get('/{id}/detalles', [RepuestosController::class, 'detalle'])->name('detalles'); // Editar un artículo
    Route::put('/update/{id}', [RepuestosController::class, 'update'])->name('update'); // Actualizar un artículo
    Route::delete('/{id}', [RepuestosController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/export-pdf', [RepuestosController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar todos los artículos a PDF
    Route::get('/get-all', [RepuestosController::class, 'getAll'])->name('getAll'); // Obtener todos los artículos en formato JSON
    Route::post('/check-nombre', [RepuestosController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe

    // Rutas para las acciones de compras
    // Rutas para las acciones de compras
    Route::get('/{id}/detalles', [ComprasController::class, 'detalles'])->name('compras.detalles');
    Route::get('/{id}/factura', [ComprasController::class, 'factura'])->name('compras.factura');
    Route::get('/{id}/ticket', [ComprasController::class, 'ticket'])->name('compras.ticket');


    Route::get('/exportar-excel', function () {
        return Excel::download(new ArticuloExport, 'compras.xlsx');
    })->name('exportExcel');
});

Route::get('/buscar-repuesto', [ArticulosController::class, 'buscar']);
Route::post('/guardar-repuesto', [ArticulosController::class, 'store']);

Route::post('/articulosmodal', [ArticulosController::class, 'storeModal'])->name('articulos.store');
/// INICIO DEVOLUCIONES ///
Route::prefix('devoluciones')->name('devoluciones.')->group(function () {
    Route::get('/', [DevolucionesController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::get('/create', [RepuestosController::class, 'create'])->name('create'); // Formulario de creación
    Route::post('/store', [RepuestosController::class, 'store'])->name('store'); // Guardar un nuevo artículo
    Route::get('/{id}/imagen', [RepuestosController::class, 'imagen'])->name('imagen'); // Editar un artículo
    Route::post('/{id}/fotoupdate', [RepuestosController::class, 'updateFoto']);
    Route::get('/{id}/edit', [RepuestosController::class, 'edit'])->name('edit'); // Editar un artículo
    Route::get('/{id}/detalles', [RepuestosController::class, 'detalle'])->name('detalles'); // Editar un artículo
    Route::put('/update/{id}', [RepuestosController::class, 'update'])->name('update'); // Actualizar un artículo
    Route::delete('/{id}', [RepuestosController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/export-pdf', [RepuestosController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar todos los artículos a PDF
    Route::get('/get-all', [RepuestosController::class, 'getAll'])->name('getAll'); // Obtener todos los artículos en formato JSON
    Route::post('/check-nombre', [RepuestosController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::get('/exportar-excel', function () {
        return Excel::download(new ArticuloExport, 'devoluciones.xlsx');
    })->name('exportExcel');
});
/// INICIO ARTICULOS ///
Route::prefix('heramientas')->name('heramientas.')->group(function () {
    Route::get('/create', [HeramientasController::class, 'create'])->name('create'); // Formulario de creación
    Route::post('/store', [HeramientasController::class, 'store'])->name('store'); // Guardar un nuevo artículo
    Route::get('/{id}/imagen', [RepuestosController::class, 'imagen'])->name('imagen'); // Editar un artículo
    Route::post('/{id}/fotoupdate', [RepuestosController::class, 'updateFoto']);
    Route::get('/{id}/edit', [HeramientasController::class, 'edit'])->name('edit'); // Editar un artículo
    Route::get('/{id}/detalles', [HeramientasController::class, 'detalle'])->name('detalles'); // Editar un artículo
    Route::put('/update/{id}', [HeramientasController::class, 'update'])->name('update'); // Actualizar un artículo
    Route::delete('/{id}', [HeramientasController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/export-pdf', [HeramientasController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar todos los artículos a PDF
    Route::get('/get-all', [HeramientasController::class, 'getAll'])->name('getAll'); // Obtener todos los artículos en formato JSON
    Route::post('/check-nombre', [HeramientasController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::get('/exportar-excel', function () {
        return Excel::download(new ArticuloExport, 'heramientas.xlsx');
    })->name('exportExcel');
});


Route::prefix('heramientas')->name('heramientas.')->group(function () {
    Route::get('/', [HeramientasController::class, 'index'])
        ->name('index')
        ->middleware('permiso:VER HERRAMIENTAS');
});
/// INICIO ARTICULOS ///
Route::prefix('suministros')->name('suministros.')->group(function () {
    Route::get('/create', [SuministrosController::class, 'create'])->name('create'); // Formulario de creación
    Route::post('/store/suministro', [SuministrosController::class, 'store'])->name('store'); // Guardar un nuevo artículo
    Route::get('/{id}/edit', [SuministrosController::class, 'edit'])->name('edit'); // Editar un artículo
    Route::get('/{id}/detalles', [SuministrosController::class, 'detalle'])->name('detalles'); // Editar un artículo
    Route::get('/{id}/imagen', [SuministrosController::class, 'imagen'])->name('imagen'); // Editar un artículo
    Route::put('/update/{id}', [SuministrosController::class, 'update'])->name('update'); // Actualizar un artículo
    Route::delete('/{id}', [SuministrosController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/export-pdf', [SuministrosController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar todos los artículos a PDF
    Route::get('/get-all', [SuministrosController::class, 'getAll'])->name('getAll'); // Obtener todos los artículos en formato JSON
    Route::post('/check-nombre', [SuministrosController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::put('/{id}/cambiar-estado', [SuministrosController::class, 'cambiarEstado']);
    Route::get('/exportar-excel', function () {
        return Excel::download(new ArticuloExport, 'suministros.xlsx');
    })->name('exportExcel');
});

Route::prefix('suministros')->name('suministros.')->group(function () {
    Route::get('/', [SuministrosController::class, 'index'])
        ->name('index')
        ->middleware('permiso:VER SUMINISTROS');
});
/// INICIO ARTICULOS ///
Route::prefix('producto')->name('producto.')->group(function () {
    Route::get('/create', [ProductoController::class, 'create'])->name('create'); // Formulario de creación
    Route::post('/store', [ProductoController::class, 'store'])->name('store'); // Guardar un nuevo artículo
    Route::get('/{id}/edit', [ProductoController::class, 'edit'])->name('edit'); // Editar un artículo
    Route::get('/{id}/imagen', [RepuestosController::class, 'imagen'])->name('imagen'); // Editar un artículo
    Route::post('/{id}/fotoupdate', [RepuestosController::class, 'updateFoto']);
    Route::get('/{id}/detalles', [ProductoController::class, 'detalle'])->name('detalles'); // Editar un artículo
    Route::put('/update/{id}', [ProductoController::class, 'update'])->name('update'); // Actualizar un artículo
    Route::delete('/{id}', [ProductoController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/export-pdf', [ProductoController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar todos los artículos a PDF
    Route::get('/get-all', [ProductoController::class, 'getAll'])->name('getAll'); // Obtener todos los artículos en formato JSON
    Route::post('/check-nombre', [ProductoController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::get('/create-producto', [ProductoController::class, 'createproducto'])->name('create.producto'); // Crear un nuevo kit
    Route::get('/exportar-excel', function () {
        return Excel::download(new ArticuloExport, 'producto.xlsx');
    })->name('exportExcel');
});

Route::prefix('producto')->name('producto.')->group(function () {
    Route::get('/', [ProductoController::class, 'index'])
        ->name('index')
        ->middleware('permiso:VER PRODUCTOS'); // Solo quien tenga permiso
});

Route::prefix('kardex')->name('kardex.')->group(function () {
    Route::get('/producto/{id}/kardex', [KardexController::class, 'kardexxproducto'])->name('kardexxproducto'); // Editar un artículo

    Route::get('/producto/{idArticulo}/detalles/{id}', [KardexController::class, 'detalles'])
        ->name('kardex.detalles');
});





Route::post('/unidades/store', [RepuestosController::class, 'storeunidad'])->name('unidades.store');
Route::post('/subcategoriarepuesto/store', [RepuestosController::class, 'storesubcategoria'])->name('subcategoria.store');
Route::post('/modelorepuesto/store', [RepuestosController::class, 'storerepuestomodelo'])->name('modelo.store');

/// FIN ARTICULO ///
/// KITS DE ARTICULOS ///
Route::prefix('kits')->name('almacen.kits.')->group(function () {
    Route::get('/', [KitsController::class, 'index'])->name('index'); // Mostrar todos los kits
    Route::get('/create', [KitsController::class, 'create'])->name('create'); // Crear un nuevo kit
    Route::post('/store', [KitsController::class, 'store'])->name('store'); // Guardar un nuevo kit
    Route::get('/{id}/edit', [KitsController::class, 'edit'])->name('edit'); // Editar un kit
    Route::put('/update/{id}', [KitsController::class, 'update'])->name('update'); // Actualizar un kit
    Route::delete('/{id}', [KitsController::class, 'destroy'])->name('destroy'); // Eliminar un kit
    Route::get('/export-pdf', [KitsController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar a PDF
    Route::get('/get-all', [KitsController::class, 'getAll'])->name('getAll'); // Obtener datos en JSON
});
// INICIO CATEGORIA ///
Route::prefix('subcategoria')->name('subcategoria.')->group(function () {
    Route::get('/create', [SubcategoriaController::class, 'create'])->name('create'); // Guardar una nueva categoría
    Route::post('/store', [SubcategoriaController::class, 'store'])->name('store'); // Guardar una nueva categoría
    Route::get('/{id}/edit', [SubcategoriaController::class, 'edit'])->name('edit'); // Editar una categoría
    Route::put('/update/{id}', [SubcategoriaController::class, 'update'])->name('update'); // Actualizar una categoría
    // Route::delete('/{id}', [UbicacionesController::class, 'destroy'])->name('destroy'); // Eliminar una categoría
    Route::get('/reporte-ubicaciones', [UbicacionesController::class, 'exportAllPDF'])->name('ubicaciones.pdf'); // Exportar todas las categorías a PDF
    Route::get('/get-all', [UbicacionesController::class, 'getAll'])->name('getAll'); // Obtener todas las categorías en formato JSON
    Route::post('/check-nombre', [UbicacionesController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::delete('destroy/{id}', [SubcategoriaController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/exportar-excel', function () {
        return Excel::download(new CategoriaExport, 'ubicaciones.xlsx');
    })->name('exportExcel');
});

Route::prefix('subcategoria')->name('subcategoria.')->middleware('permiso:VER SUB CATEGORIA')->group(function () {
    Route::get('/', [SubcategoriaController::class, 'index'])->name('index'); // Index
});


// INICIO CATEGORIA ///
Route::prefix('solicitudarticulo')->name('solicitudarticulo.')->group(function () {
    Route::get('/create', [SolicitudarticuloController::class, 'create'])->name('create'); // Guardar una nueva categoría
    Route::post('/store', [SolicitudarticuloController::class, 'store'])->name('store'); // Guardar una nueva categoría
    Route::get('/{id}/edit', [SolicitudarticuloController::class, 'edit'])->name('edit'); // Editar una categoría
    Route::get('/{id}/show', [SolicitudarticuloController::class, 'show'])->name('show'); // Editar una categoría
    Route::get('/{id}/opciones', [SolicitudarticuloController::class, 'opciones'])->name('opciones'); // Editar una categoría


    Route::get('/{id}/gestionar', [SolicitudarticuloController::class, 'gestionar'])->name('gestionar'); // Editar una categoría


    Route::put('/update/{id}', [SolicitudarticuloController::class, 'update'])->name('update'); // Actualizar una categoría
    // Route::delete('/{id}', [UbicacionesController::class, 'destroy'])->name('destroy'); // Eliminar una categoría
    Route::get('/reporte-ubicaciones', [UbicacionesController::class, 'exportAllPDF'])->name('ubicaciones.pdf'); // Exportar todas las categorías a PDF
    Route::get('/get-all', [UbicacionesController::class, 'getAll'])->name('getAll'); // Obtener todas las categorías en formato JSON
    Route::post('/check-nombre', [UbicacionesController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::delete('destroy/{id}', [SolicitudarticuloController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/exportar-excel', function () {
        return Excel::download(new CategoriaExport, 'ubicaciones.xlsx');
    })->name('exportExcel');


    Route::post('/{id}/aceptar', [SolicitudarticuloController::class, 'aceptar'])->name('solicitudrepuesto.aceptar');
    Route::post('/{id}/aceptar-individual', [SolicitudarticuloController::class, 'aceptarIndividual'])->name('solicitudrepuesto.aceptar.individual');

    Route::get('/{id}/conformidad-pdf', [SolicitudarticuloController::class, 'generarConformidad'])
        ->name('conformidad-pdf');


    Route::get('/{id}/gestionar', [SolicitudarticuloController::class, 'gestionar'])->name('gestionar');
    Route::post('/{id}/marcar-usado', [SolicitudarticuloController::class, 'marcarUsado'])->name('marcar-usado');
    Route::post('/{id}/marcar-no-usado', [SolicitudarticuloController::class, 'marcarNoUsado'])->name('marcar-no-usado');

    // En web.php (rutas de solicitudarticulo)
    Route::post('/{id}/enviar-almacen', [SolicitudarticuloController::class, 'enviarAlmacen'])->name('enviar-almacen');
    Route::get('/enviar-almacen/modal-data', [SolicitudarticuloController::class, 'getModalData'])->name('modal-data');
});

Route::get('solicitudarticulo/', [SolicitudarticuloController::class, 'index'])
    ->name('solicitudarticulo.index')
    ->middleware('permiso:VER SOLICITUD DE ARTICULO');

Route::prefix('solicitudrepuesto')->name('solicitudrepuesto.')->group(function () {
    Route::get('/', [SolicitudrepuestoController::class, 'index'])->name('index');
    Route::get('/create', [SolicitudrepuestoController::class, 'create'])->name('create');
    Route::get('/create/provincia', [SolicitudrepuestoController::class, 'createProvincia'])->name('create.provincia');
    Route::post('/store', [SolicitudrepuestoController::class, 'store'])->name('store');
    Route::get('/{id}', [SolicitudrepuestoController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [SolicitudrepuestoController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SolicitudrepuestoController::class, 'update'])->name('update');
    Route::delete('/{id}', [SolicitudrepuestoController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/opciones', [SolicitudrepuestoController::class, 'opciones'])->name('opciones');
    Route::get('/{id}/gestionar', [SolicitudrepuestoController::class, 'gestionar'])->name('gestionar'); // Editar una categoría
    Route::post('/{id}/aceptar', [SolicitudRepuestoController::class, 'aceptar'])->name('solicitudrepuesto.aceptar');
    Route::post('/{id}/aceptar-individual', [SolicitudRepuestoController::class, 'aceptarIndividual'])->name('solicitudrepuesto.aceptar.individual');

    Route::post('/store-provincia', [SolicitudRepuestoController::class, 'storeProvincia'])
        ->name('solicitudrepuesto.store-provincia')
        ->middleware('auth');

    Route::get('/{id}/conformidad-pdf', [SolicitudrepuestoController::class, 'generarConformidad'])
        ->name('conformidad-pdf');
});


Route::prefix('solicitudrepuestoprovincia')->name('solicitudrepuestoprovincia.')->group(function () {
    Route::get('/', [SolicitudrepuestoController::class, 'index'])->name('index');
    Route::get('/create', [SolicitudrepuestoController::class, 'create'])->name('create');
    Route::get('/create/provincia', [SolicitudrepuestoController::class, 'createProvincia'])->name('create.provincia');
    Route::post('/store', [SolicitudrepuestoController::class, 'store'])->name('store');
    Route::get('/{id}', [SolicitudrepuestoController::class, 'showprovincia'])->name('show');
    Route::get('/{id}/edit', [SolicitudrepuestoController::class, 'editProvincia'])->name('edit');
    Route::put('/{id}', [SolicitudrepuestoController::class, 'updateProvincia'])->name('update');
    Route::delete('/{id}', [SolicitudrepuestoController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/opciones', [SolicitudrepuestoController::class, 'opcionesProvincia'])->name('opciones');
    Route::get('/{id}/gestionar', [SolicitudrepuestoController::class, 'gestionar'])->name('gestionar'); // Editar una categoría
    Route::post('/{id}/aceptar-provincia', [SolicitudRepuestoController::class, 'aceptarProvincia'])->name('solicitudrepuesto.aceptar');
    Route::post('/{id}/aceptar-provincia-individual', [SolicitudRepuestoController::class, 'aceptarProvinciaIndividual'])->name('solicitudrepuesto.aceptar.individual');

    Route::post('/store-provincia', [SolicitudRepuestoController::class, 'storeProvincia'])
        ->name('solicitudrepuesto.store-provincia')
        ->middleware('auth');

    Route::get('/{id}/conformidad-pdf', [SolicitudrepuestoController::class, 'generarConformidadProvincia'])
        ->name('conformidad-pdf');
});


// Rutas para gestión de estados de repuestos
Route::post('/solicitudrepuesto/{id}/marcar-usado', [SolicitudRepuestoController::class, 'marcarUsado'])->name('solicitudrepuesto.marcar.usado');
Route::post('/solicitudrepuesto/{id}/marcar-no-usado', [SolicitudRepuestoController::class, 'marcarNoUsado'])->name('solicitudrepuesto.marcar.no-usado');

// INICIO CATEGORIA ///
Route::prefix('solicitudingreso')->name('solicitudingreso.')->group(function () {
    Route::get('/create', [SolicitudarticuloController::class, 'create'])->name('create'); // Guardar una nueva categoría
    Route::post('/store', [SolicitudarticuloController::class, 'store'])->name('store'); // Guardar una nueva categoría
    Route::get('/{id}/edit', [SolicitudarticuloController::class, 'edit'])->name('edit'); // Editar una categoría
    Route::get('/{id}/show', [SolicitudarticuloController::class, 'show'])->name('show'); // Editar una categoría
    Route::get('/{id}/opciones', [SolicitudarticuloController::class, 'opciones'])->name('opciones'); // Editar una categoría
    Route::post('/{id}/actualizar', [SolicitudIngresoController::class, 'actualizarSolicitud']);
    Route::put('/update/{id}', [SolicitudarticuloController::class, 'update'])->name('update'); // Actualizar una categoría
    // Route::delete('/{id}', [UbicacionesController::class, 'destroy'])->name('destroy'); // Eliminar una categoría
    Route::get('/reporte-ubicaciones', [UbicacionesController::class, 'exportAllPDF'])->name('ubicaciones.pdf'); // Exportar todas las categorías a PDF
    Route::get('/get-all', [UbicacionesController::class, 'getAll'])->name('getAll'); // Obtener todas las categorías en formato JSON
    Route::post('/check-nombre', [UbicacionesController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::delete('destroy/{id}', [SolicitudarticuloController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/exportar-excel', function () {
        return Excel::download(new CategoriaExport, 'ubicaciones.xlsx');
    })->name('exportExcel');
});

Route::get('solicitudingreso/', [SolicitudingresoController::class, 'index'])
    ->name('solicitudingreso.index')
    ->middleware('permiso:VER SOLICITUD DE INGRESO');

Route::get('/solicitudes-ingreso/por-compra/{compraId}', [SolicitudIngresoController::class, 'porCompra'])->name('solicitudes.por-compra');
Route::post('/solicitudes-ingreso/procesar', [SolicitudIngresoController::class, 'procesar'])->name('solicitudes.procesar');
Route::post('/solicitudes-ingreso/distribuir', [SolicitudIngresoController::class, 'distribuir'])->name('solicitudes.distribuir');


//ENTRADAS DE PROVEEDORES
Route::prefix('entradasproveedores')->name('entradasproveedores.')->group(function () {
    Route::get('/', [EntradasproveedoresController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::get('/create', [EntradasproveedoresController::class, 'create'])->name('create'); // Guardar una nueva categoría
    Route::post('/store', [EntradasproveedoresController::class, 'store'])->name('store'); // Guardar una nueva categoría
    Route::get('/{id}/edit', [EntradasproveedoresController::class, 'edit'])->name('edit'); // Editar una categoría
    Route::put('/update/{id}', [EntradasproveedoresController::class, 'update'])->name('update'); // Actualizar una categoría
    Route::delete('/{id}', [EntradasproveedoresController::class, 'destroy'])->name('destroy'); // Eliminar una categoría
    Route::get('/reporte-ubicaciones', [UbicacionesController::class, 'exportAllPDF'])->name('ubicaciones.pdf'); // Exportar todas las categorías a PDF
    Route::get('/get-all', [UbicacionesController::class, 'getAll'])->name('getAll'); // Obtener todas las categorías en formato JSON
    Route::post('/check-nombre', [UbicacionesController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::get('/exportar-excel', function () {
        return Excel::download(new CategoriaExport, 'ubicaciones.xlsx');
    })->name('exportExcel');
});

//ENTRADAS DE PROVEEDORES
Route::prefix('cotizacionesL')->name('cotizacionesL.')->group(function () {
    Route::get('/', [CotizacioneslCotizacionlController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::get('/create', [EntradasproveedoresController::class, 'create'])->name('create'); // Guardar una nueva categoría
    Route::post('/store', [EntradasproveedoresController::class, 'store'])->name('store'); // Guardar una nueva categoría
    Route::get('/{id}/edit', [EntradasproveedoresController::class, 'edit'])->name('edit'); // Editar una categoría
    Route::put('/update/{id}', [EntradasproveedoresController::class, 'update'])->name('update'); // Actualizar una categoría
    Route::delete('/{id}', [EntradasproveedoresController::class, 'destroy'])->name('destroy'); // Eliminar una categoría
    Route::get('/reporte-ubicaciones', [UbicacionesController::class, 'exportAllPDF'])->name('ubicaciones.pdf'); // Exportar todas las categorías a PDF
    Route::get('/get-all', [UbicacionesController::class, 'getAll'])->name('getAll'); // Obtener todas las categorías en formato JSON
    Route::post('/check-nombre', [UbicacionesController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::get('/exportar-excel', function () {
        return Excel::download(new CategoriaExport, 'ubicaciones.xlsx');
    })->name('exportExcel');
});



Route::prefix('solicitudcompra')->name('solicitudcompra.')->group(function () {
    Route::get('/', [SolicitudcompraController::class, 'index'])->name('index');
    Route::get('/create', [SolicitudcompraController::class, 'create'])->name('create');
    Route::get('/gestionadministracion', [SolicitudcompraController::class, 'gestionadministracion'])->name('gestionadministracion');

    Route::post('/store', [SolicitudcompraController::class, 'store'])->name('store');
    Route::get('/{id}', [SolicitudcompraController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [SolicitudcompraController::class, 'edit'])->name('edit');
    Route::get('/{id}/evaluacion', [SolicitudcompraController::class, 'evaluacion'])->name('evaluacion');
    Route::put('/{id}', [SolicitudcompraController::class, 'update'])->name('update');
    Route::delete('/{id}', [SolicitudcompraController::class, 'destroy'])->name('destroy');
    Route::get('/solicitud-almacen/{id}/detalles', [SolicitudcompraController::class, 'getSolicitudAlmacenDetalles'])->name('solicitud-almacen.detalles');


    Route::post('/{idSolicitud}/articulo/{idDetalle}/aprobar', [SolicitudcompraController::class, 'aprobarArticulo'])->name('articulo.aprobar');
    Route::post('/{idSolicitud}/articulo/{idDetalle}/rechazar', [SolicitudcompraController::class, 'rechazarArticulo'])->name('articulo.rechazar');
    Route::post('/{id}/cambiar-estado', [SolicitudcompraController::class, 'cambiarEstado'])->name('cambiar.estado');
    Route::post('/{id}/cancelar', [SolicitudcompraController::class, 'cancelarSolicitud'])->name('cancelar');
});

// En routes/web.php - REEMPLAZA el grupo de rutas completo:

Route::prefix('solicitudalmacen')->name('solicitudalmacen.')->group(function () {
    Route::get('/', [SolicitudalmacenController::class, 'index'])->name('index');
    Route::get('/create', [SolicitudalmacenController::class, 'create'])->name('create');
    Route::post('/', [SolicitudalmacenController::class, 'store'])->name('store');
    Route::get('/select-data', [SolicitudalmacenController::class, 'getSelectData'])->name('select-data');
    // SOLO UNA RUTA para búsqueda
    Route::get('/buscar-articulos', [SolicitudalmacenController::class, 'buscarArticulos'])->name('buscar-articulos');

    // Rutas para detalles
    Route::get('/{id}/detalles', [SolicitudalmacenController::class, 'show'])->name('show');
    Route::get('/{id}/detalles-data', [SolicitudalmacenController::class, 'getDetailData'])->name('detalles-data');

    // Rutas para edición
    Route::get('/{id}/edit', [SolicitudalmacenController::class, 'edit'])->name('edit');
    Route::get('/{id}/edit-data', [SolicitudalmacenController::class, 'getEditData'])->name('edit-data');
    Route::put('/{id}', [SolicitudalmacenController::class, 'update'])->name('update');

    // Rutas para gestión de estados - CORREGIDAS
    Route::post('/detalle/{id}/cambiar-estado', [SolicitudalmacenController::class, 'changeDetailStatus'])->name('detalle.cambiar-estado');
    Route::post('/{id}/cambiar-estado-final', [SolicitudalmacenController::class, 'changeFinalStatus'])->name('cambiar-estado-final');
});


// Ruta para obtener los clientes generales asociados a un cliente
Route::get('/clientes-generales/{idCliente}', [OrdenesTrabajoController::class, 'getClientesGeneraless']);
Route::get('/clientesdatos', [OrdenesTrabajoController::class, 'getClientes'])->name('clientes.get');
Route::post('/guardar-cliente', [OrdenesTrabajoController::class, 'guardarCliente'])->name('guardar.cliente');
Route::get('/clientesDatosCliente', [OrdenesTrabajoController::class, 'clientesDatosCliente'])->name('clientes.get');
Route::get('/clientesdatoscliente', [OrdenesTrabajoController::class, 'getClientesdatosclientes'])->name('clientes.get');
Route::post('/clientes/{idCliente}/agregar-clientes-generales', [ClientesController::class, 'agregarClientesGenerales']);
Route::delete('/clientes/{idCliente}/eliminar-cliente-general/{idClienteGeneral}', [ClientesController::class, 'eliminarClienteGeneral']);
Route::get('/tickets-por-serie/{serie}', [OrdenesTrabajoController::class, 'getTicketsPorSerie']);
Route::post('/guardar-visita', [OrdenesTrabajoController::class, 'guardarVisita']);
Route::get('/obtener-visitas/{ticketId}', [OrdenesTrabajoController::class, 'obtenerVisitas']);
Route::post('/guardar-visita-soporte', [OrdenesHelpdeskController::class, 'guardarVisitaSoporte']);
Route::get('/obtener-numero-visitas/{ticketId}', function ($ticketId) {
    if (!is_numeric($ticketId)) {
        return response()->json(['error' => 'ID de ticket no válido'], 400);
    }
    // Obtener ticket completo
    $ticket = DB::table('tickets')->where('idTickets', $ticketId)->first();

    if (!$ticket) {
        return response()->json(['error' => 'Ticket no encontrado'], 404);
    }
    // Obtener el flujo del ticket
    $idEstadflujo = DB::table('ticketflujo')
        ->where('idTicketFlujo', $ticket->idTicketFlujo)
        ->value('idEstadflujo');

    if ($idEstadflujo === null) {
        return response()->json(['error' => 'Flujo del ticket no encontrado'], 404);
    }

    // Determinar el tipo de nombre base
    $nombreBase = 'Visita';

    if ($idEstadflujo == 8) {
        $nombreBase = 'Recojo';
    } elseif ($idEstadflujo == 18) {
        $nombreBase = 'Entrega';
    } elseif ($idEstadflujo == 1) {
        $nombreBase = ($ticket->evaluaciontienda == 1) ? 'EvaluacionTienda' : 'Visita';
    }

    // Contar las visitas con LIKE (ej: "Visita%", "Recojo%", etc.)
    $numeroVisitas = DB::table('visitas')
        ->where('idTickets', $ticketId)
        ->where('nombre', 'LIKE', $nombreBase . '%')
        ->count();

    return response()->json([
        'numeroVisitas' => $numeroVisitas,
        'idEstadflujo'  => $idEstadflujo,
        'tipoNombre'    => $nombreBase
    ]);
});
Route::get('/ticket/{id}/historial-modificaciones', [OrdenesTrabajoController::class, 'obtenerHistorialModificaciones']);
Route::get('/imagen-apoyo/{idVisita}', [OrdenesTrabajoController::class, 'getImagen'])->name('imagen-apoyo.get');
Route::get('/inicio-servicio-imagen/{idVisita}', [OrdenesTrabajoController::class, 'getImagenInicioServicio'])->name('inicio-servicio-imagen.get');
Route::get('/final-servicio-imagen/{idVisita}', [OrdenesTrabajoController::class, 'getImagenFinalServicio'])->name('final-servicio-imagen.get');
Route::get('/desplazamiento-imagen/{idVisita}', [OrdenesTrabajoController::class, 'getImagenTipo2'])->name('desplazamiento-servicio-imagen.get');
Route::get('/api/obtenerVisitas/{ticketId}', [OrdenesTrabajoController::class, 'obtenerVisitas']);
Route::post('/ticket/{ticketId}/ticketflujo/{flujoId}/update', 'TicketFlujoController@update')->name('ticketflujo.update');
//INICIO TICKETS///


/// INICIO ÓRDENES DE TRABAJO ///
Route::prefix('ordenes')->name('ordenes.')->group(function () {
    // Vista principal de órdenes de trabajo
    Route::get('/', [OrdenesTrabajoController::class, 'index'])->name('index');
    // ***** RUTAS PARA SMART-TV *****
    Route::get('/create-smart/', [OrdenesTrabajoController::class, 'createsmart'])->name('createsmart')->middleware('auth');
    Route::post('/storesmart', [OrdenesTrabajoController::class, 'storesmart'])->name('storesmart')->middleware('auth');
    Route::get('smart/{id}/edit', [OrdenesTrabajoController::class, 'edit'])->name('edit')->middleware('auth');
    Route::get('/export-excel', [OrdenesTrabajoController::class, 'exportToExcel'])->name('export.excel');
    Route::get('/smart/informe/{idOt}/pdf', [OrdenesTrabajoController::class, 'generateInformePdf'])
        ->name('generateInformePdf');
    Route::get('smart/{id}/firmas/{idVisitas}/', [OrdenesTrabajoController::class, 'firmacliente'])->name('firmacliente');
    Route::get('smart/{id}/pdf/{idVisitas}/', [OrdenesTrabajoController::class, 'generateInformePdfVisita'])->name('pdfcliente');
    // Firma cliente para levantamiento
    // Vista previa del PDF en imagen (levantamiento)
    Route::get('helpdesk/levantamiento/{idOt}/vista-previa-imagen/{idVisita}', [OrdenesHelpdeskController::class, 'vistaPreviaImagen'])
        ->name('helpdesk.levantamiento.vista-previa.imagen');
    Route::get('helpdesk/soporte/{idOt}/vista-previa-imagen/{idVisita}', [OrdenesHelpdeskController::class, 'vistaPreviaImagen'])->name('helpdesk.soporte.vista-previa.imagen');
    Route::get('helpdesk/levantamiento/{id}/firmas/{idVisitas}', [OrdenesHelpdeskController::class, 'firmaclienteLeva'])->name('firmacliente.leva');
    Route::get('helpdesk/soporte/{id}/firmas/{idVisitas}/', [OrdenesHelpdeskController::class, 'firmaclienteSopo'])->name('firmacliente.sopo');
    Route::get('helpdesk/ejecucion/{id}/firmas/{idVisitas}/', [OrdenesHelpdeskController::class, 'firmaclienteEjecucion'])->name('firmacliente.ejecucion');
    Route::get('help/{id}/pdf/{idVisitas}/', [OrdenesTrabajoController::class, 'generateInformePdfVisita'])->name('pdfcliente');
    Route::get('helpdesk/levantamiento/{idOt}/pdf/{idVisita}', [OrdenesHelpdeskController::class, 'generateLevantamientoPdfVisita'])
        ->name('helpdesk.levantamiento.pdf');
    Route::get('helpdesk/soporte/{idOt}/pdf/{idVisita}', [OrdenesHelpdeskController::class, 'generateSoportePdfVisita'])
        ->name('helpdesk.soporte.pdf');
    Route::get('helpdesk/lab/{id}/firmas/{idVisitas}/', [OrdenesHelpdeskController::class, 'firmaclienteLab'])->name('firmacliente.Lab');
    Route::get('help/{id}/pdf/{idVisitas}/', [OrdenesHelpdeskController::class, 'generateLabPdfVisita'])->name('pdfcliente');
    Route::get('help/{id}/pdf_app/{idVisitas}/', [OrdenesHelpdeskController::class, 'generateLabPdfVisitaApp'])->name('pdfclienteapp');
    Route::get('/helpdesk/export-excel', [OrdenesHelpdeskController::class, 'exportHelpdeskToExcel'])->name('ordenes.export.helpdesk.excel');
    // ✅ Ruta corregida para verificar actualizaciones
    Route::get('/{idOt}/check-updates', [OrdenesTrabajoController::class, 'checkUpdates'])
        ->name('checkUpdates');
    // ***** RUTAS PARA HELPDESK *****
    Route::get('/create-helpdesk', [OrdenesHelpdeskController::class, 'createhelpdesk'])->name('createhelpdesk');
    Route::post('/storehelpdesk', [OrdenesHelpdeskController::class, 'storehelpdesk'])->name('storehelpdesk')->middleware('auth');
    Route::get('/helpdesk/levantamiento/{id}/edit', [OrdenesHelpdeskController::class, 'editHelpdesk'])->name('helpdesk.levantamiento.edit');
    Route::put('/helpdesk/update/{id}', [OrdenesHelpdeskController::class, 'updateHelpdesk'])->name('helpdesk.update');
    Route::get('/export-helpdesk-excel', [OrdenesHelpdeskController::class, 'exportHelpdeskToExcel'])->name('export.helpdesk.excel');
    Route::get('/helpdesk/get-all', [OrdenesHelpdeskController::class, 'getAll'])->name('helpdesk.getAll');
    Route::get('/helpdesk/soporte/{id}/edit', [OrdenesHelpdeskController::class, 'editSoporte'])
        ->name('helpdesk.soporte.edit');
    Route::get('/helpdesk/laboratorio/{id}/edit', [OrdenesHelpdeskController::class, 'ediLaboratorio'])
        ->name('helpdesk.laboratorio.edit');
    Route::get('/helpdesk/pdf/laboratorio/{idOt}', [OrdenesHelpdeskController::class, 'generateLaboratorioPdf'])
        ->name('helpdesk.pdf.laboratorio');
    Route::get('/helpdesk/pdf/conformidad/laboratorio/{idOt}', [OrdenesHelpdeskController::class, 'generateConformidadLaboratorioPdf'])
        ->name('helpdesk.pdf.conformidad.laboratorio');

    Route::get('/helpdesk/pdf/laboratorio/{idOt}', [OrdenesHelpdeskController::class, 'generateLabPdfVisita'])
        ->name('helpdesk.pdf.laboratorio');
    //EJECUCION
    Route::get('/helpdesk/ejecucion/{id}/edit', [OrdenesHelpdeskController::class, 'editejecucion'])
        ->name('helpdesk.ejecucion.edit');
    Route::get('/helpdesk/pdf/levantamiento/{idOt}', [OrdenesHelpdeskController::class, 'generateLevantamientoPdf'])
        ->name('helpdesk.pdf.levantamiento');
    Route::get('/helpdesk/pdf/soporte/{idOt}', [OrdenesHelpdeskController::class, 'generateSoportePdf'])->name('helpdesk.pdf.soporte');
    Route::get('/helpdesk/pdf/conformidad/{idOt}', [OrdenesHelpdeskController::class, 'generateConformidadPdf'])
        ->name('helpdesk.pdf.conformidad');
    Route::get('/helpdesk/pdf/conformidad/levantamiento/{idOt}', [OrdenesHelpdeskController::class, 'generateConformidadLevantamientoPdf'])
        ->name('helpdesk.pdf.conformidad.levantamiento');
    Route::get('/helpdesk/pdf/conformidad/ejecucion/{idOt}', [OrdenesHelpdeskController::class, 'generateConformidadEjecucionPdf'])
        ->name('helpdesk.pdf.conformidad.ejecucion');
    Route::get('/helpdesk/pdf/ejecucion/{idOt}', [OrdenesHelpdeskController::class, 'generateEjecucionPdf'])->name('helpdesk.pdf.ejecucion');
    // ***** RUTAS GENERALES *****
    Route::put('/update/{id}', [OrdenesTrabajoController::class, 'update'])->name('update');
    Route::delete('/{id}', [OrdenesTrabajoController::class, 'destroy'])->name('destroy');
    // ***** EXPORTACIÓN DE DATOS *****
    Route::get('/export-pdf', [OrdenesTrabajoController::class, 'exportAllPDF'])->name('export.pdf');
    // ***** OBTENCIÓN DE DATOS *****
    Route::get('/get-all', [OrdenesTrabajoController::class, 'getAll'])->name('getAll');
    // ***** VALIDACIONES *****
    Route::post('/check-nombre', [OrdenesTrabajoController::class, 'checkNombre'])->name('checkNombre');
});

Route::get('ordenes/smart', [OrdenesTrabajoController::class, 'smarttable'])
    ->name('ordenes.smart')
    ->middleware('permiso:VER SMART-TV');

Route::get('ordenes/helpdesk', [OrdenesHelpdeskController::class, 'helpdesk'])
    ->name('ordenes.helpdesk')
    ->middleware('permiso:VER HELPDESK');



Route::get('/informe/vista-previa-imagen/{idOt}/{idVisita}', [OrdenesTrabajoController::class, 'vistaPreviaImagen'])->name('informe.vista-previa.imagen');
Route::get('/ordenes/helpdesk/soporte/{idOt}/vista-previa/{idVisita}/{tipo?}', [OrdenesHelpdeskController::class, 'vistaPreviaImagen'])
    ->name('ordenes.helpdesk.soporte.vista-previa.imagen');
Route::get('/ordenes/helpdesk/ejecucion/{idOt}/vista-previa/{idVisita}/{tipo?}', [OrdenesHelpdeskController::class, 'vistaPreviaImagen'])
    ->name('ordenes.helpdesk.ejecucion.vista-previa.imagen');
Route::put('actualizar-orden-helpdesk/{id}', [OrdenesHelpdeskController::class, 'actualizarHelpdesk'])->name('formActualizarOrdenHelpdesk');
Route::put('actualizar-orden-soporte/{id}', [OrdenesHelpdeskController::class, 'actualizarSoporte'])->name('formActualizarOrdenHelpdesk');
Route::put('actualizar-orden-ejecucion/{id}', [OrdenesHelpdeskController::class, 'actualizarEjecucion'])->name('formActualizarOrdenHelpdesk');
Route::post('ordenes/helpdesk/levantamiento/{id}/guardar-firma/{idVisitas}', [OrdenesHelpdeskController::class, 'guardarFirmaCliente'])
    ->name('helpdesk.levantamiento.guardar-firma');
Route::post('ordenes/helpdesk/soporte/{id}/guardar-firma/{idVisitas}', [OrdenesHelpdeskController::class, 'guardarFirmaCliente']);
Route::post('ordenes/helpdesk/ejecucion/{id}/guardar-firma/{idVisitas}', [OrdenesHelpdeskController::class, 'guardarFirmaCliente']);
Route::post('ordenes/smart/{id}/guardar-firma/{idVisitas}', [OrdenesTrabajoController::class, 'guardarFirmaCliente'])
    ->name('guardar.firma.cliente');
Route::get('ordenes/smart/{id}/obtener-firma-cliente', [OrdenesTrabajoController::class, 'obtenerFirmaCliente'])
    ->name('obtener.firma.cliente')
    ->middleware('auth');
Route::get('ordenes/smart/{id}/obtener-firma-tecnico', [OrdenesTrabajoController::class, 'obtenerFirmaTecnico'])
    ->name('obtener.firma.tecnico')
    ->middleware('auth');
Route::get('ordenes/helpdesk/soporte/{id}/obtener-firma-tecnico', [OrdenesHelpdeskController::class, 'obtenerFirmaTecnico'])
    ->name('obtener.firma.tecnico')
    ->middleware('auth');
Route::get('ordenes/helpdesk/ejecucion/{id}/obtener-firma-tecnico', [OrdenesHelpdeskController::class, 'obtenerFirmaTecnico'])
    ->name('obtener.firma.tecnico')
    ->middleware('auth');
// Route::post('/guardar-datos-envio', [OrdenesHelpdeskController::class, 'guardardatosenviosoporte']);
// routes/web.php
Route::get('/get-marcas', [MarcaController::class, 'checkMarcas']);
Route::get('/marcas-por-cliente-general/{idClienteGeneral}', [MarcaController::class, 'getMarcasByClienteGeneral']);
Route::post('/constancias', [OrdenesTrabajoController::class, 'storeConstancia'])
    ->name('constancias.store');
Route::get('/constancia/pdf/{id}', [OrdenesTrabajoController::class, 'descargarPDF'])->name('constancia.pdf');
Route::get('/constancia/pdf/{id}/{idVisitas}', [OrdenesTrabajoController::class, 'descargarPDF_App'])->name('constancia.pdf');
// En routes/web.php
Route::get('/constancias/fotos/{id}', [OrdenesTrabajoController::class, 'mostrarFoto'])
    ->name('constancias.fotos.mostrar');
// Agrega esta ruta en tu archivo de rutas web.php
Route::get('validar-ticket/{nroTicket}', [OrdenesTrabajoController::class, 'validarTicket'])->name('validarTicket');
Route::post('/ticket/{ticketId}/ticketflujo/{flujoId}/update', [OrdenesTrabajoController::class, 'actualizarComentario'])->name('ticketflujo.update');
///CLIENTE GENERAL POR CREATE-SMART
Route::post('/guardar-cliente-general-smart', [ClienteGeneralController::class, 'guardarClienteSmart'])->name('guardar.cliente');
Route::post('/guardar-marca-smart', [MarcaController::class, 'store'])->name('guardar.cliente');
Route::post('/guardar-modelo-smart', [ModelosController::class, 'storeMODELOSMART'])->name('guardar.modelo');
///RUTAS
Route::get('/clientes/generales/asociados/{idCliente}', [ClientesController::class, 'clientesGeneralesAsociados']);
Route::post('/clientes/{idCliente}/agregar-cliente-general/{idClienteGeneral}', [ClientesController::class, 'agregarClienteGeneral']);
Route::get('/get-clientes-generales/{idCliente}', [OrdenesTrabajoController::class, 'getClientesGeneralesss']);
// Ruta para obtener modelos de una marca
Route::get('/get-modelos/{marcaId}', function ($marcaId) {
    // Obtener los modelos asociados a la marca seleccionada
    $modelos = Modelo::where('idMarca', $marcaId)->get();
    // Retornar los modelos como respuesta JSON
    return response()->json($modelos);
});
Route::put('actualizar-orden/{id}', [OrdenesTrabajoController::class, 'actualizarOrden'])->name('formActualizarOrden');
Route::post('/guardar-modificacion/{id}', [OrdenesTrabajoController::class, 'guardarModificacion']);
Route::get('/ultima-modificacion/{idTickets}', [OrdenesTrabajoController::class, 'obtenerUltimaModificacion']);
// Route::put('/usuario/actualizacion/{usuario}', [UsuarioController::class, 'update'])->name('updateusuario');
Route::post('/usuarios/{id}/update', [UsuarioController::class, 'update'])->name('usuarios.update');
// Rutas para obtener modelos por marca
Route::get('/modelos/{idMarca}', [OrdenesTrabajoController::class, 'obtenerModelosPorMarca']);
Route::get('/perfil', [UsuarioController::class, 'perfil'])->name('perfil');
Route::get('/asistencias', [AsistenciaController::class, 'index'])
    ->name('asistencias.index')
    ->middleware(['auth', 'permiso:VER ASISTENCIAS']);
Route::get('/create/usuario', [UsuarioController::class, 'create'])->name('usuario.create');
Route::get('/usuario/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuario.edit');
Route::post('/usuario/store', [UsuarioController::class, 'store'])->name('usuarios.store');
Route::put('config/{id}', [UsuarioController::class, 'config'])->name('usuario.config');
Route::put('/usuario/direccion/{id}', [UsuarioController::class, 'direccion'])->name('usuario.direccion');


Route::get('/usuario/{id}/documentos', [UsuarioController::class, 'getDocumentos'])->name('usuario.documentos');
Route::post('/usuario/{id}/documentos/upload', [UsuarioController::class, 'uploadDocumento'])->name('usuario.documentos.upload');
Route::get('/usuario/documentos/{id}/download', [UsuarioController::class, 'downloadDocumento'])->name('usuario.documentos.download');
Route::delete('/usuario/documentos/{id}', [UsuarioController::class, 'deleteDocumento'])->name('usuario.documentos.delete');


Route::post('/usuario/{id}/cambiar-password', [UsuarioController::class, 'cambiarPassword'])->name('usuario.cambiar.password');
Route::post('/usuario/{id}/desactivar-cuenta', [UsuarioController::class, 'desactivarCuenta'])->name('usuario.desactivar');
Route::post('/usuario/{id}/activar-cuenta', [UsuarioController::class, 'activarCuenta'])->name('usuario.activar');
Route::post('/usuario/{id}/enviar-recuperacion', [UsuarioController::class, 'enviarRecuperacion'])->name('usuario.enviar.recuperacion');
Route::get('/usuario/{id}/generar-pdf', [UsuarioController::class, 'generarPDF'])->name('usuario.generar.pdf');
Route::get('/usuario/{id}/descargar-documentos', [UsuarioController::class, 'descargarDocumentos'])->name('usuario.descargar.documentos');


Route::get('/usuario/{idUsuario}/articulos-activos', [UsuarioController::class, 'getArticulosAsignados']);

// Route::put('/usuario/firma/{idUsuario}', [UsuarioController::class, 'actualizarFirma'])->name('usuario.firma');
// Route::get('/informe-pdf/{idTickets}', [OrdenesTrabajoController::class, 'generarInformePdf'])->name('informe.pdf');


Route::prefix('almacen/asignar-articulo')->name('asignar-articulos.')->group(function () {
    Route::get('/', [AsignarArticuloController::class, 'index'])->name('index');
    Route::get('/create', [AsignarArticuloController::class, 'create'])->name('create');
    Route::post('/', [AsignarArticuloController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AsignarArticuloController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AsignarArticuloController::class, 'update'])->name('update'); // <-- Agregar esta línea
    Route::post('/{id}/devolver', [AsignarArticuloController::class, 'devolver'])->name('devolver');
    Route::post('/detalle/{id}/reportar-danado', [AsignarArticuloController::class, 'reportarDanado'])->name('reportar-danado');
});

Route::get('/ver-informe-pdf/{idTickets}', [OrdenesTrabajoController::class, 'verInforme']);
Route::get('/ver-hoja-entrega-pdf/{idTickets}', [OrdenesTrabajoController::class, 'verHojaEntrega']);
//Validaciones de tienda
Route::post('/validar/ructienda', [TiendaController::class, 'validarRuc'])->name('validar.ruc');
Route::post('/validar/emailtienda', [TiendaController::class, 'validarEmail'])->name('validar.email');
Route::post('/validar/celulartienda', [TiendaController::class, 'validarCelular'])->name('validar.celular');
Route::post('/validar/nombretienda', [TiendaController::class, 'validarNombre'])->name('validar.nombre');
//Validaciones de cast
Route::get('/validar/ruccast', [CastController::class, 'validarRucCast'])->name('validar.ruccast');
Route::get('/validar/emailcast', [CastController::class, 'validarEmailCast'])->name('validar.emailcast');
Route::get('/validar/celularcast', [CastController::class, 'validarTelefonoCast'])->name('validar.telefonocast');
Route::get('/validar/nombrecast', [CastController::class, 'validarNombreCast'])->name('validar.nombrecast');
//Validaciones de proveedores
Route::post('/validar/rucproveedores', [ProveedoresController::class, 'validarnumeroDocumentoProveedores'])->name('validar.numerodocumentoproveedores');
Route::post('/validar/emailproveedores', [ProveedoresController::class, 'validarEmailProveedores'])->name('validar.emailproveedores');
Route::post('/validar/celularproveedores', [ProveedoresController::class, 'validarTelefonoProveedores'])->name('validar.telefonoproveedores');
Route::post('/validar/nombreproveedores', [ProveedoresController::class, 'validarNombreProveedores'])->name('validar.nombreproveedores');
Route::post('/guardarCondiciones', [OrdenesTrabajoController::class, 'guardar'])->middleware('auth');
Route::post('/guardarCondiciones/soporte', [OrdenesHelpdeskController::class, 'guardarSoporte'])->middleware('auth');
Route::get('/ticket/{id}/estados', [OrdenesTrabajoController::class, 'loadEstados'])->name('ticket.loadEstados');
Route::get('/visita/{ticketId}', [OrdenesTrabajoController::class, 'mostrarDetalles'])->name('visita.detalles');
Route::post('/tickets/{idTicket}/actualizar-estado', [OrdenesTrabajoController::class, 'actualizarEstado']);

Route::post('/guardar-estado', [OrdenesTrabajoController::class, 'guardarEstadoflujo'])->name('guardarEstado');


Route::delete('/ticketflujo/{id}/eliminar', [OrdenesTrabajoController::class, 'eliminarflujo']);
Route::post('/ticket/{ticketId}/relacionarflujo', [OrdenesTrabajoController::class, 'relacionarFlujo']);
//politicas
Route::get('/politicas', [PoliticasController::class, 'index'])->name('politicas');
// web.php (Rutas)
Route::get('/usuario/firma/{idUsuario}', [UsuarioController::class, 'obtenerFirma']);
Route::put('/usuario/firma/{idUsuario}', [UsuarioController::class, 'guardarFirma']);
Route::get('/clientes/{id}', [OrdenesHelpdeskController::class, 'getClientes']);
Route::post('/solicitud-entrega', [OrdenesTrabajoController::class, 'guardarSolicitud'])->name('solicitud.entrega')->middleware('auth');
Route::put('/solicitudentrega/aceptar/{id}', [OrdenesTrabajoController::class, 'aceptarSolicitud'])
    ->middleware('auth'); // Middleware para autenticar al usuario
Route::post('/suministros/store', [OrdenesHelpdeskController::class, 'store']);
Route::post('/guardar-suministros', [OrdenesHelpdeskController::class, 'guardarSuministros'])->middleware('auth');
Route::get('/get-suministros/{ticketId}/{visitaId}', [OrdenesHelpdeskController::class, 'getSuministros']);
Route::delete('/eliminar-suministro/{id}', [OrdenesHelpdeskController::class, 'eliminarSuministros']);
Route::patch('/actualizar-suministro/{id}', [OrdenesHelpdeskController::class, 'actualizarCantidad']);
Route::get('/clientes/{idClienteGeneral}', [OrdenesHelpdeskController::class, 'obtenerClientes']);
Route::view('/apps/invoice/list', 'apps.invoice.list');
Route::get('/apps/invoice/preview/{id}', [OrdenesHelpdeskController::class, 'verEnvio']);
Route::view('/apps/invoice/add', 'apps.invoice.add');
Route::view('/apps/invoice/edit', 'apps.invoice.edit');
Route::get('/modelos/categoria/{idCategoria}', [OrdenesHelpdeskController::class, 'obtenerModelosPorCategoria']);
Route::get('/marcas/categoria/{idCategoria}', [OrdenesHelpdeskController::class, 'obtenerMarcasPorCategoria']);
Route::get('/modelos/marca/{idMarca}', [OrdenesHelpdeskController::class, 'obtenerModelosPorMarca']);
Route::get('/modelos/marca/{idMarca}/categoria/{idCategoria}', [OrdenesHelpdeskController::class, 'obtenerModelosPorMarcaYCategoria']);
Route::get(
    '/modelos/marca/{idMarca}/obtener/categoria/{idCategoria}',
    [OrdenesHelpdeskController::class, 'obtenerModelosPorMarcaYCategoriaobtener']
)
    ->name('modelos.porMarcaYCategoria');
Route::post('/guardar-equipo', [OrdenesHelpdeskController::class, 'guardarEquipo'])->name('guardarEquipo');
Route::get('/obtener-productos-instalados', [OrdenesHelpdeskController::class, 'obtenerProductosInstalados']);
Route::post('/guardar-equipo-retirar', [OrdenesHelpdeskController::class, 'guardarEquipoRetirar']);
Route::get('/obtener-productos-retirados', [OrdenesHelpdeskController::class, 'obtenerProductosRetirados']);
// Rutas en routes/web.php
Route::delete('/eliminar-producto/{id}', [OrdenesHelpdeskController::class, 'eliminarProducto']);
Route::get('/enviar-guia', [GuiaController::class, 'enviar']);
// Ruta para mostrar el formulario de recuperación de contraseña


// Route::post('/password/recover', [PasswordRecoveryController::class, 'sendRecoveryLink'])->name('password.recover');
// Obtener datos del cliente
Route::get('/get-cliente-data/{id}', function ($id) {
    $cliente = Cliente::find($id);

    if (!$cliente) {
        return response()->json(['error' => 'Cliente no encontrado'], 404);
    }

    return response()->json([
        'idTipoDocumento' => $cliente->idTipoDocumento,
        'esTienda' => $cliente->esTienda,
        'direccion' => $cliente->direccion
    ]);
});
Route::get('/get-all-tiendas', function () {
    return response()->json(
        Tienda::select('idTienda', 'nombre', 'direccion')
            ->get()
    );
});
Route::get('/get-tiendas-by-cliente/{clienteId}', function ($clienteId) {
    return response()->json(
        Tienda::where('idCliente', $clienteId)
            ->select('idTienda', 'nombre', 'direccion')
            ->get()
    );
});
Route::get('/get-tiendas/{clienteId?}', function ($clienteId = null) {
    try {
        // 1. Si no viene clienteId, devolver todas las tiendas
        if (!$clienteId) {
            return response()->json(
                Tienda::select('idTienda', 'nombre', 'direccion', 'idCliente')
                    ->orderBy('nombre')
                    ->get()
            );
        }

        // 2. Obtener datos del cliente para determinar el filtro
        $cliente = Cliente::find($clienteId);

        if (!$cliente) {
            return response()->json([], 404);
        }

        // 3. Aplicar filtros según tipo de documento y esTienda
        $query = Tienda::query();

        if ($cliente->idTipoDocumento == 8 && $cliente->esTienda == 0) {
            // Mostrar todas las tiendas
            $query->select('idTienda', 'nombre', 'direccion', 'idCliente')
                ->orderBy('nombre');
        } elseif ($cliente->idTipoDocumento == 9 && $cliente->esTienda == 1) {
            // Mostrar solo tiendas asociadas a este cliente
            $query->where('idCliente', $clienteId)
                ->select('idTienda', 'nombre', 'direccion')
                ->orderBy('nombre');
        } else {
            // Caso por defecto (no debería ocurrir según tu lógica)
            return response()->json([], 200);
        }

        return response()->json($query->get());
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error al obtener tiendas',
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/get-no-tiendas', function () {
    // Log para depuración
    Log::info('Solicitud de tiendas vacías recibida');

    return response()->json([]);
})->middleware('auth'); // Opcional: proteger la ruta


Route::get('/get-marcas-by-cliente-general/{clienteGeneralId}', function ($clienteGeneralId) {
    try {
        // Obtener marcas relacionadas con el cliente general
        $marcas = DB::table('marca_clientegeneral')
            ->join('marca', 'marca_clientegeneral.idMarca', '=', 'marca.idMarca')
            ->where('marca_clientegeneral.idClienteGeneral', $clienteGeneralId)
            ->where('marca.estado', 1) // Solo marcas activas
            ->select('marca.idMarca', 'marca.nombre')
            ->orderBy('marca.nombre')
            ->get();

        return response()->json($marcas);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error al obtener marcas',
            'message' => $e->getMessage()
        ], 500);
    }
});
// Obtener modelos por marca
Route::get('/get-modelos-by-marca/{marcaId}', function ($marcaId) {
    return response()->json(
        DB::table('modelo')
            ->where('idMarca', $marcaId)
            ->where('estado', 1) // Solo modelos activos
            ->select('idModelo', 'nombre')
            ->orderBy('nombre')
            ->get()
    );
});
// Obtener todas las marcas (actualizada para incluir solo activas)
Route::get('/get-all-marcas', function () {
    return response()->json(
        DB::table('marca')
            ->where('estado', 1)
            ->select('idMarca', 'nombre')
            ->orderBy('nombre')
            ->get()
    );
});


Route::get('/emitir', function () {
    broadcast(new MensajeEnviado('Hola bro, WebSocket activo 🔥'))->toOthers();
    return 'Evento enviado X5!';
});
Route::view('/components/tabs', 'ui-components.tabs');
Route::view('/components/accordions', 'ui-components.accordions');
Route::view('/components/modals', 'ui-components.modals');
Route::view('/components/cards', 'ui-components.cards');
Route::view('/components/carousel', 'ui-components.carousel');
Route::view('/components/countdown', 'ui-components.countdown');
Route::view('/components/counter', 'ui-components.counter');
Route::view('/components/sweetalert', 'ui-components.sweetalert');
Route::view('/components/timeline', 'ui-components.timeline');
Route::view('/components/notifications', 'ui-components.notifications');
Route::view('/components/media-object', 'ui-components.media-object');
Route::view('/components/list-group', 'ui-components.list-group');
Route::view('/components/pricing-table', 'ui-components.pricing-table');
Route::view('/components/lightbox', 'ui-components.lightbox');
Route::view('/elements/alerts', 'elements.alerts');
Route::view('/elements/avatar', 'elements.avatar');
Route::view('/elements/badges', 'elements.badges');
Route::view('/elements/breadcrumbs', 'elements.breadcrumbs');
Route::view('/elements/buttons', 'elements.buttons');
Route::view('/elements/buttons-group', 'elements.buttons-group');
Route::view('/elements/color-library', 'elements.color-library');
Route::view('/elements/dropdown', 'elements.dropdown');
Route::view('/elements/infobox', 'elements.infobox');
Route::view('/elements/jumbotron', 'elements.jumbotron');
Route::view('/elements/loader', 'elements.loader');
Route::view('/elements/pagination', 'elements.pagination');
Route::view('/elements/popovers', 'elements.popovers');
Route::view('/elements/progress-bar', 'elements.progress-bar');
Route::view('/elements/search', 'elements.search');
Route::view('/elements/tooltips', 'elements.tooltips');
Route::view('/elements/treeview', 'elements.treeview');
Route::view('/elements/typography', 'elements.typography');
Route::view('/charts', 'charts');
Route::view('/widgets', 'widgets');
Route::view('/font-icons', 'font-icons');
Route::view('/dragndrop', 'dragndrop');
Route::view('/tables', 'tables');
Route::view('/datatables/advanced', 'datatables.advanced');
Route::view('/datatables/alt-pagination', 'datatables.alt-pagination');
Route::view('/datatables/basic', 'datatables.basic');
Route::view('/datatables/checkbox', 'datatables.checkbox');
Route::view('/datatables/clone-header', 'datatables.clone-header');
Route::view('/datatables/column-chooser', 'datatables.column-chooser');
Route::view('/datatables/export', 'datatables.export');
Route::view('/datatables/multi-column', 'datatables.multi-column');
Route::view('/datatables/multiple-tables', 'datatables.multiple-tables');
Route::view('/datatables/order-sorting', 'datatables.order-sorting');
Route::view('/datatables/range-search', 'datatables.range-search');
Route::view('/datatables/skin', 'datatables.skin');
Route::view('/datatables/sticky-header', 'datatables.sticky-header');
Route::view('/forms/basic', 'forms.basic');
Route::view('/forms/input-group', 'forms.input-group');
Route::view('/forms/layouts', 'forms.layouts');
Route::view('/forms/validation', 'forms.validation');
Route::view('/forms/input-mask', 'forms.input-mask');
Route::view('/forms/select2', 'forms.select2');
Route::view('/forms/touchspin', 'forms.touchspin');
Route::view('/forms/checkbox-radio', 'forms.checkbox-radio');
Route::view('/forms/switches', 'forms.switches');
Route::view('/forms/wizards', 'forms.wizards');
Route::view('/forms/file-upload', 'forms.file-upload');
Route::view('/forms/quill-editor', 'forms.quill-editor');
Route::view('/forms/markdown-editor', 'forms.markdown-editor');
Route::view('/forms/date-picker', 'forms.date-picker');
Route::view('/forms/clipboard', 'forms.clipboard');
Route::view('/users/user-account-settings', 'users.user-account-settings');
Route::view('/pages/knowledge-base', 'pages.knowledge-base');
Route::view('/pages/contact-us-boxed', 'pages.contact-us-boxed');
Route::view('/pages/contact-us-cover', 'pages.contact-us-cover');
Route::view('/pages/faq', 'pages.faq');
Route::view('/pages/coming-soon-boxed', 'pages.coming-soon-boxed');
Route::view('/pages/coming-soon-cover', 'pages.coming-soon-cover');
Route::view('/pages/error404', 'pages.error404');
Route::view('/pages/error500', 'pages.error500');
Route::view('/pages/error503', 'pages.error503');
Route::view('/pages/maintenence', 'pages.maintenence');



Route::middleware('auth')->group(function () {
    // Rutas para actividades
    Route::get('actividades', [ActividadController::class, 'index'])->name('actividades.index');
    Route::post('actividades', [ActividadController::class, 'store'])->name('actividades.store');
    Route::put('actividades/{id}', [ActividadController::class, 'update'])->name('actividades.update');
    Route::delete('actividades/{id}', [ActividadController::class, 'destroy'])->name('actividades.destroy');

    // Rutas para etiquetas
    Route::get('etiquetas', [EtiquetaController::class, 'index'])->name('etiquetas.index');
    Route::post('etiquetas', [EtiquetaController::class, 'store'])->name('etiquetas.store');
    Route::put('etiquetas/{id}', [EtiquetaController::class, 'update'])->name('etiquetas.update');
    Route::delete('etiquetas/{id}', [EtiquetaController::class, 'destroy'])->name('etiquetas.destroy');
});


Route::middleware('auth')->group(function () {
    // Rutas para tickets
    // Solo ver lista
    Route::get('Seguimiento-Cliente/', [ClienteSeguimientoController::class, 'index'])
        ->name('Seguimiento.index')
        ->middleware('permiso:VER SEGUIMIENTO CLIENTE');
    Route::get('Seguimiento-Cliente/create', [ClienteSeguimientoController::class, 'tabsseguimiento'])->name('Seguimiento.create');
    Route::post('Seguimiento-Cliente/store', [ClienteSeguimientoController::class, 'store'])->name('Seguimiento.store');
    Route::get('Seguimiento-Cliente/{id}/edit', [ClienteSeguimientoController::class, 'edit'])->name('Seguimiento.edit');
    Route::get('Seguimiento-Cliente/{id}', [ClienteSeguimientoController::class, 'show'])->name('Seguimiento.show');
    Route::put('Seguimiento-Cliente/{id}/update', [ClienteSeguimientoController::class, 'update'])->name('Seguimiento.update');
    Route::delete('Seguimiento-Cliente/{id}', [ClienteSeguimientoController::class, 'destroy'])->name('Seguimiento.destroy');
    Route::get('Seguimiento-Cliente/{id}/historial', [ClienteSeguimientoController::class, 'historial'])->name('Seguimiento.historial');
});

Route::post('/contactosone', [ContactoController::class, 'store'])->name('contactos.store')->middleware('auth');
Route::post('/empresas', [EmpresaController::class, 'store'])->name('empresas.store')->middleware('auth');
Route::put('/contactos/{contacto}', [ContactoController::class, 'update'])->name('contactos.update');
Route::put('/empresas/{empresa}', [EmpresaController::class, 'update'])->name('empresas.update');

Route::get('/seguimiento/{id}/edit', [ClienteSeguimientoController::class, 'editSeguimiento'])->name('seguimiento.edit');
Route::get('/seguimiento/{id}/edit-tab', [ClienteSeguimientoController::class, 'editTab'])
    ->name('seguimiento.edit-tab');
Route::resource('observaciones', ObservacionController::class)->only([
    'index',
    'store',
    'update',
    'destroy'
]);

Route::get('observaciones/{task}/edit', [ObservacionController::class, 'edit'])
    ->name('observaciones.edit');


Route::prefix('scrumboard')->group(function () {
    // Obtener proyectos
    Route::get('/projects', [ScrumboarddController::class, 'index']);

    // Proyectos CRUD
    Route::post('/projects', [ScrumboarddController::class, 'store']);
    Route::put('/projects/{project}', [ScrumboarddController::class, 'update']);
    Route::delete('/projects/{project}', [ScrumboarddController::class, 'destroy']);
    Route::post('/projects/{project}/clear-tasks', [ScrumboarddController::class, 'clearTasks']);

    // Tareas CRUD
    Route::post('/tasks', [ScrumboarddController::class, 'storeTask']);
    Route::put('/tasks/{task}', [ScrumboarddController::class, 'updateTask']);
    Route::delete('/tasks/{task}', [ScrumboarddController::class, 'destroyTask']);
    Route::post('/tasks/move', [ScrumboarddController::class, 'moveTask']);
});




Route::middleware(['auth'])->group(function () {
    // Rutas para Notas
    Route::prefix('notes')->group(function () {
        Route::get('/', [NoteController::class, 'index'])->name('notes.index');
        Route::post('/', [NoteController::class, 'store'])->name('notes.store');
        Route::get('/create', [NoteController::class, 'create'])->name('notes.create');
        Route::get('/{note}', [NoteController::class, 'show'])->name('notes.show');
        Route::put('/{note}', [NoteController::class, 'update'])->name('notes.update');
        Route::delete('/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');
        Route::get('/{note}/edit', [NoteController::class, 'edit'])->name('notes.edit');
        Route::patch('/{note}/toggle-favorite', [NoteController::class, 'toggleFavorite'])->name('notes.toggle-favorite');
        Route::patch('/{note}/update-tag', [NoteController::class, 'updateTag'])->name('notes.update-tag');
    });

    // Rutas para Tags
    Route::prefix('tags')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('tags.index');
        Route::post('/', [TagController::class, 'store'])->name('tags.store');
        Route::get('/create', [TagController::class, 'create'])->name('tags.create');
        Route::get('/{tag}', [TagController::class, 'show'])->name('tags.show');
        Route::put('/{tag}', [TagController::class, 'update'])->name('tags.update');
        Route::delete('/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
        Route::get('/{tag}/edit', [TagController::class, 'edit'])->name('tags.edit');
    });
});


// Rutas para contactos
Route::get('/contactos/form', [ContactoController::class, 'formmul'])->name('contactos.form');
Route::get('/contactos/list', [ContactoController::class, 'listmul'])->name('contactos.list');
Route::post('/contactos', [ContactoController::class, 'storemul'])->name('contactos.store');
Route::put('/contactos/{contacto}', [ContactoController::class, 'updatemul'])->name('contactos.update');
Route::delete('/contactos/{contacto}', [ContactoController::class, 'destroymul'])->name('contactos.destroy');



Route::get('/formulario/empresa', function () {
    return view('areacomercial.forms.form-empresa');
});

Route::get('/formulario/contacto', function () {
    return view('areacomercial.forms.form-contacto');
});

Route::put('/empresasformularioprio/{empresa}', [EmpresaController::class, 'update'])->name('empresasformem.update');




Route::prefix('cronograma')->name('cronograma.')->group(function () {

    // Obtener datos del cronograma
    Route::get('/{idSeguimiento}/data', [CronogramaController::class, 'getData'])
        ->name('data');

    // Operaciones con tareas
    Route::post('/{idSeguimiento}/task', [CronogramaController::class, 'saveTask'])
        ->name('task.save');

    Route::put('/{idSeguimiento}/task/{taskId}', [CronogramaController::class, 'saveTask'])
        ->name('task.update');

    Route::delete('/{idSeguimiento}/task/{taskId}', [CronogramaController::class, 'deleteTask'])
        ->name('task.delete');

    // Operaciones con dependencias (links)
    Route::post('/{idSeguimiento}/link', [CronogramaController::class, 'saveLink'])
        ->name('link.save');

    Route::delete('/{idSeguimiento}/link/{linkId}', [CronogramaController::class, 'deleteLink'])
        ->name('link.delete');

    // Configuración
    Route::post('/{idSeguimiento}/config', [CronogramaController::class, 'saveConfig'])
        ->name('config.save');

    // Histórico
    Route::get('/{idSeguimiento}/historico', [CronogramaController::class, 'getHistorico'])
        ->name('historico');

    // Importar datos
    Route::post('/{idSeguimiento}/import', [CronogramaController::class, 'importData'])
        ->name('import');

    // Exportar datos
    Route::get('/{idSeguimiento}/export/{format}', [CronogramaController::class, 'exportData'])
        ->name('export')
        ->where('format', 'json|excel|csv');
});


Route::post('/seleccionar-seguimiento', [SeleccionSeguimientoController::class, 'store']);

Route::get('/obtener-seleccion/{idSeguimiento}', [SeleccionSeguimientoController::class, 'obtenerSeleccion']);
// Asegúrate de que estén dentro de las rutas web normales
Route::middleware('web')->group(function () {
    Route::get('/scrumboard/tasks/{taskId}/cotizaciones', [ScrumboarddController::class, 'getCotizaciones']);
    Route::post('/scrumboard/cotizaciones', [ScrumboarddController::class, 'storeCotizacion']);
    Route::put('/scrumboard/cotizaciones/{id}', [ScrumboarddController::class, 'updateCotizacion']);
    Route::delete('/scrumboard/cotizaciones/{id}', [ScrumboarddController::class, 'deleteCotizacion']);
    Route::post('/scrumboard/cotizaciones/handle', [ScrumboarddController::class, 'handleCotizacion']);


    // Rutas para reuniones
    Route::post('/scrumboard/reuniones/handle', [ScrumboarddController::class, 'handleReunion']);
    Route::get('/scrumboard/tasks/{taskId}/reuniones', [ScrumboarddController::class, 'getReuniones']);
    Route::delete('/scrumboard/reuniones/{id}', [ScrumboarddController::class, 'deleteReunion']);

    // Rutas para levantamientos
    Route::post('/scrumboard/levantamientos/handle', [ScrumboarddController::class, 'handleLevantamiento']);
    Route::get('/scrumboard/tasks/{taskId}/levantamientos', [ScrumboarddController::class, 'getLevantamientos']);
    Route::delete('/scrumboard/levantamientos/{id}', [ScrumboarddController::class, 'deleteLevantamiento']);
    // Rutas para proyectos ganados
    Route::post('/scrumboard/ganados/handle', [ScrumboarddController::class, 'handleGanado']);
    Route::get('/scrumboard/tasks/{taskId}/ganados', [ScrumboarddController::class, 'getGanados']);
    Route::delete('/scrumboard/ganados/{id}', [ScrumboarddController::class, 'deleteGanado']);


    // Rutas para proyectos observados
    Route::post('/scrumboard/observados/handle', [ScrumboarddController::class, 'handleObservado']);
    Route::get('/scrumboard/tasks/{taskId}/observados', [ScrumboarddController::class, 'getObservados']);
    Route::delete('/scrumboard/observados/{id}', [ScrumboarddController::class, 'deleteObservado']);
    // Rutas para proyectos rechazados
    Route::post('/scrumboard/rechazados/handle', [ScrumboarddController::class, 'handleRechazado']);
    Route::get('/scrumboard/tasks/{taskId}/rechazados', [ScrumboarddController::class, 'getRechazados']);
    Route::delete('/scrumboard/rechazados/{id}', [ScrumboarddController::class, 'deleteRechazado']);

    // routes/web.php o routes/api.php

});


Route::get('/tarea/{taskId}', [ScrumboarddController::class, 'mostrarTarea']);
Route::get('/scrumboard/reuniones/{id}/participantes', [ScrumboarddController::class, 'getParticipantesReunion']);

Route::get('/buscar-articulo', [ArticulosController::class, 'buscar'])->name('buscar.articulo');


Route::post('/guardar-compra', [ComprasController::class, 'guardarCompra'])->name('api.guardar-compra');

Route::get('/compras/create', [ComprasController::class, 'create'])->name('compras.create');

Route::get('/administracion/compras/{id}/ticket', [ComprasController::class, 'ticket'])
    ->name('compras.ticket');

Route::get('/administracion/compras/{id}/ticket-devolucion', [ComprasController::class, 'ticketDevolucion'])
    ->name('compras.ticket.devolucion');

Route::get('/administracion/compras/{id}/factura-pdf', [ComprasController::class, 'facturaPdf'])
    ->name('compras.factura.pdf');


Route::post('/compras/{id}/devolucion', [ComprasController::class, 'procesarDevolucion']);
Route::get('/compras/{id}/devoluciones', [ComprasController::class, 'obtenerDevoluciones']);


Route::post('/compras/devolucion', [ComprasController::class, 'procesarDevolucion'])->name('compras.devolucion');
if (app()->environment('local')) {
}
// Route::view('/__preview/custodia', 'solicitud.solicitudcustodia.index')->name('solicitudcustodia.index');}


Route::get('/preview/custodia', [CustodiaController::class, 'index'])->name('solicitudcustodia.index');

Route::get('/preview/custodia', [CustodiaController::class, 'index'])
    ->name('solicitudcustodia.index')
    ->middleware('permiso:VER SOLICITUD DE CUSTODIA');

Route::get('/custodia/create', [CustodiaController::class, 'create'])->name('solicitudcustodia.create');

// Guardar nueva custodia
Route::post('/custodia/store', [CustodiaController::class, 'store'])
    ->name('solicitudcustodia.store');

Route::post('/tickets/{id}/actualizar-custodia', [CustodiaController::class, 'actualizarCustodia'])->name('tickets.actualizar.custodia')->middleware('auth');

// routes/web.php


Route::post('/solicitud/custodia/{id}/retirar-repuesto', [CustodiaController::class, 'retirarRepuesto'])
    ->name('solicitudcustodia.retirar-repuesto')->middleware('auth');

Route::post('/solicitud/custodia/anular-retiro/{idRetiro}', [CustodiaController::class, 'anularRetiro'])
    ->name('solicitudcustodia.anular-retiro')->middleware('auth');

Route::get('/solicitud/custodia/repuestos-compatibles/{idModelo}', [CustodiaController::class, 'getRepuestosCompatibles'])
    ->name('solicitudcustodia.repuestos-compatibles');




Route::get('/tickets/{id}/custodia', [CustodiaController::class, 'verificarCustodia']);

Route::get('/solicitud/custodia/opciones/{id}', [CustodiaController::class, 'opciones'])
    ->name('solicitudcustodia.opciones');

Route::get('/solicitud/custodia/harvest/{id}', [CustodiaController::class, 'harvest'])
    ->name('solicitudcustodia.harvest');


Route::put('/solicitud/custodia/{id}', [CustodiaController::class, 'update'])
    ->name('solicitudcustodia.update');




//PRODUCTOS
// Ruta para mostrar la vista principal
Route::get('/entradas-proveedores', [EntradasproveedoresController::class, 'index'])->name('entradas-proveedores.index');

// Ruta para buscar productos (AJAX)
Route::get('/buscar-producto-entrada', [EntradasproveedoresController::class, 'buscarProductoEntrada'])->name('buscar-producto-entrada');

// Ruta para guardar la entrada (POST)
Route::post('/guardar-entrada-proveedor', [EntradasproveedoresController::class, 'guardarEntradaProveedor'])->name('guardar-entrada-proveedor');


Route::get('/kardex/producto/{articulo_id}/cliente/{cliente_id}', [KardexController::class, 'kardexProductoPorCliente']);



// Ruta para el detalle de movimientos de un registro específico del kardex
Route::get('/kardex/detalle-movimientos/{kardex_id}', [KardexController::class, 'detalleMovimientosKardex'])
    ->name('kardex.detalle-movimientos');


// Ruta para ver las series de un producto
Route::get('/producto/{id}/series', [ProductoController::class, 'verSeries'])->name('producto.series');
Route::post('/series/cambiar-estado', [ProductoController::class, 'cambiarEstadoSerie']);
Route::get('/solicitudes-ingreso/series/{compraId}/{articuloId}', [SolicitudingresoController::class, 'obtenerSeries']);

Route::get('/solicitudes-ingreso/series/{compraId}/{articuloId}', [SolicitudingresoController::class, 'obtenerSeries']);

// En web.php
Route::post('/solicitud-ingreso/cambiar-estado-grupo', [SolicitudIngresoController::class, 'cambiarEstadoGrupo'])->name('solicitud-ingreso.cambiar-estado-grupo');

Route::post('/solicitud-ingreso/{id}/cambiar-estado', [SolicitudIngresoController::class, 'cambiarEstado']);




Route::post('/solicitud-ingreso/guardar-ubicacion', [SolicitudIngresoController::class, 'guardarUbicacion']);







Route::post('/solicitud-ingreso/{id}/cambiar-estado', [SolicitudIngresoController::class, 'cambiarEstado'])->name('solicitud-ingreso.cambiar-estado');
Route::post('/solicitud-ingreso/{id}/actualizar', [SolicitudIngresoController::class, 'actualizarSolicitud']);

Route::get('/almacen/vista', [UbicacionesVistaController::class, 'vistaAlmacen'])
    ->name('almacen.vista')
    ->middleware('permiso:VER VISTA ALMACEN');

Route::get('/almacen/ubicaciones/detalle/{rack}', [UbicacionesVistaController::class, 'detalleRack'])->name('almacen.ubicaciones.detalle');

// Rutas para fotos de custodia (BLOB en base de datos)
Route::get('/custodia/{id}/fotos', [CustodiaController::class, 'obtenerFotos'])->name('custodia.fotos');
Route::post('/custodia/{id}/fotos', [CustodiaController::class, 'guardarFotos'])->name('custodia.fotos.subir');
Route::get('/custodia/fotos/{id}/imagen', [CustodiaController::class, 'obtenerImagen'])->name('custodia.fotos.imagen');
Route::get('/custodia/fotos/{id}/descargar', [CustodiaController::class, 'descargarFoto'])->name('custodia.fotos.descargar');
Route::delete('/custodia/fotos/{id}', [CustodiaController::class, 'eliminarFoto'])->name('custodia.fotos.eliminar');
Route::get('/custodia/fotos/{id}/verificar', [CustodiaController::class, 'verificarIntegridad'])->name('custodia.fotos.verificar');

Route::prefix('permisos')->group(function () {
    // Vista principal
    Route::get('/', [PermisosPermisosController::class, 'index'])->name('permisos.index');

    // APIs
    Route::get('/data', [PermisosPermisosController::class, 'getData'])->name('permisos.data');

    // Permisos
    Route::post('/permisos', [PermisosPermisosController::class, 'storePermiso'])->name('permisos.store-permiso');
    Route::put('/permisos/{id}', [PermisosPermisosController::class, 'updatePermiso'])->name('permisos.update-permiso');
    Route::delete('/permisos/{id}', [PermisosPermisosController::class, 'destroyPermiso'])->name('permisos.destroy-permiso');

    // Combinaciones
    Route::post('/combinaciones', [PermisosPermisosController::class, 'storeCombinacion'])->name('permisos.store-combinacion');
    Route::delete('/combinaciones/{id}', [PermisosPermisosController::class, 'destroyCombinacion'])->name('permisos.destroy-combinacion');

    // Permisos de combinaciones
    Route::get('/combinaciones/{idCombinacion}/permisos', [PermisosPermisosController::class, 'getPermisosCombinacion'])->name('permisos.get-permisos-combinacion');
    Route::post('/combinaciones/{idCombinacion}/permisos', [PermisosPermisosController::class, 'guardarPermisosCombinacion'])->name('permisos.guardar-permisos-combinacion');
});


// Ruta para el detalle de un rack específico
Route::get('/almacen/ubicaciones/detalle-rack/{rack}', [UbicacionesVistaController::class, 'detalleRack'])
    ->name('almacen.detalle-rack');

// Para racks panel
Route::get('/almacen/ubicaciones/detalle-rack-panel/{rack}', [UbicacionesVistaController::class, 'detalleRackPanel'])
    ->name('almacen.detalle-rack-panel');

// Rutas para reubicación
Route::post('/almacen/reubicacion/iniciar', [UbicacionesVistaController::class, 'iniciarReubicacion'])->name('almacen.reubicacion.iniciar');
Route::post('/almacen/reubicacion/confirmar', [UbicacionesVistaController::class, 'confirmarReubicacion'])->name('almacen.reubicacion.confirmar');
Route::post('/almacen/reubicacion/cancelar', [UbicacionesVistaController::class, 'cancelarReubicacion'])->name('almacen.reubicacion.cancelar');


// RUTAS PARA AGREGAR PRODUCTO
Route::get('/almacen/productos/listar', [UbicacionesVistaController::class, 'listarProductos']); // Cambiado a GET
Route::post('/almacen/ubicaciones/agregar-producto', [UbicacionesVistaController::class, 'agregarProducto']);
Route::post('/almacen/ubicaciones/vaciar', [UbicacionesVistaController::class, 'vaciarUbicacion']);
// Rutas para reubicación entre racks
Route::get('/almacen/racks/disponibles', [UbicacionesVistaController::class, 'listarRacksDisponibles']);
Route::get('/almacen/racks/{id}/ubicaciones-vacias', [UbicacionesVistaController::class, 'listarUbicacionesVacias']);
Route::post('/almacen/reubicacion/iniciar-multiple', [UbicacionesVistaController::class, 'iniciarReubicacionMultiple']);
Route::post('/almacen/reubicacion/confirmar-multiple', [UbicacionesVistaController::class, 'confirmarReubicacionMultiple']);

// Rutas para reubicación entre racks
Route::get('/almacen/racks/disponibles', [UbicacionesVistaController::class, 'obtenerRacksDisponibles']);
Route::get('/almacen/racks/{id}/ubicaciones-vacias', [UbicacionesVistaController::class, 'obtenerUbicacionesVacias']);
Route::post('/almacen/reubicacion/confirmar-entre-racks', [UbicacionesVistaController::class, 'confirmarReubicacionEntreRacks']);
Route::get('/almacen/ubicaciones/{id}/articulos', [UbicacionesVistaController::class, 'obtenerArticulosUbicacion']);

// Rutas para gestión de racks y ubicaciones
Route::post('/almacen/racks/crear', [UbicacionesVistaController::class, 'crearRack']);
Route::post('/almacen/racks/{rackId}/actualizar-dimensiones', [UbicacionesVistaController::class, 'actualizarDimensionesRack']);
Route::get('/almacen/racks/listar', [UbicacionesVistaController::class, 'listarRacks']);
Route::get('/almacen/racks/{id}/info', [UbicacionesVistaController::class, 'obtenerInfoRack']);
Route::post('/almacen/ubicaciones/crear', [UbicacionesVistaController::class, 'crearUbicacion']);
Route::post('/almacen/racks/sugerir-letra', [UbicacionesVistaController::class, 'sugerirSiguienteLetra']);
Route::get('/almacen/racks/{rack}/datos-actualizados', [UbicacionesVistaController::class, 'getDatosActualizados']);



Route::get('/solicitud-ingreso/sugerir-ubicaciones/{articuloId}/{cantidad}', [SolicitudingresoController::class, 'sugerirUbicacionesMejorado']);




Route::post('/almacen/ubicaciones/actualizar-producto', [UbicacionesVistaController::class, 'actualizarProducto'])->name('ubicaciones.actualizar-producto');
Route::post('/almacen/ubicaciones/eliminar-producto', [UbicacionesVistaController::class, 'eliminarProducto'])->name('ubicaciones.eliminar-producto');

Route::get('/custodia/{id}/sugerencias-ubicacion', [CustodiaController::class, 'sugerirUbicacionesCustodia']);
Route::get('/custodia/{id}/ubicacion-actual', [CustodiaController::class, 'obtenerUbicacionActual']);

// routes/web.php
Route::get('/almacen/clientes-generales/listar', [UbicacionesVistaController::class, 'listarClientesGenerales']);



Route::get('/solicitud/repuestos', [SolicitudrepuestoController::class, 'index'])->name('solicitud.repuesto.index');


// En routes/web.php o donde tengas tus rutas
Route::get('/almacen/racks/{id}/tiene-productos', [UbicacionesVistaController::class, 'verificarProductosEnRack']);




// RUTAS MOVER PANELES RACKS

// RUTAS PARA MOVIMIENTOS EN PANEL
// En web.php, cambia la ruta:
Route::post('/almacen/ubicaciones/disponibles-panel', [UbicacionesVistaController::class, 'getUbicacionesParaMovimientoPanel']);
Route::post('/almacen/mover-caja-panel', [UbicacionesVistaController::class, 'moverCajaPanel']);
Route::post('/almacen/mover-producto-panel', [UbicacionesVistaController::class, 'moverProductoPanel']);
// En web.php, AÑADE esta ruta:
Route::post('/almacen/mover-articulo-en-caja-panel', [UbicacionesVistaController::class, 'moverProductoPanel']);

Route::post('/almacen/mover-articulo-en-caja-panel', [UbicacionesVistaController::class, 'moverArticuloEnCajaPanel']);
// Rutas para suministros
// Route::post('/guardar-suministros', [OrdenesHelpdeskController::class, 'guardarSuministros'])->name('guardar.suministros');
// Route::get('/get-suministros/{ticketId}/{visitaId}', [OrdenesHelpdeskController::class, 'obtenerSuministros'])->name('get.suministros');
// Route::patch('/actualizar-suministro/{id}', [OrdenesHelpdeskController::class, 'actualizarSuministro'])->name('actualizar.suministro');
// Route::delete('/eliminar-suministro/{id}', [OrdenesHelpdeskController::class, 'eliminarSuministro'])->name('eliminar.suministro');

// Rutas para cotizaciones desde tickets
Route::prefix('cotizaciones-tickets')->group(function () {
    Route::get('/', [cotizacionController::class, 'index'])->name('cotizaciones-tickets.index');
    Route::get('/datos-cotizacion/{ticketId}', [cotizacionController::class, 'getDatosCotizacion'])->name('cotizaciones-tickets.datos');
    Route::post('/generar-individual', [cotizacionController::class, 'generarCotizacionIndividual'])->name('cotizaciones-tickets.generar-individual');
    Route::post('/generar-multiple', [cotizacionController::class, 'generarCotizacionMultiple'])->name('cotizaciones-tickets.generar-multiple');

    // Nuevas rutas para las funcionalidades
    Route::post('/vista-previa-temporal', [cotizacionController::class, 'vistaPreviaTemporal'])->name('cotizaciones.vista-previa-temporal');
    Route::post('/generar-pdf-temporal', [cotizacionController::class, 'generarPDFTemporal'])->name('cotizaciones.generar-pdf-temporal');
    Route::post('/enviar-email-temporal', [cotizacionController::class, 'enviarEmailTemporal'])->name('cotizaciones.enviar-email-temporal');
    // Rutas para cotizaciones guardadas
    Route::get('/{id}/vista-previa', [cotizacionController::class, 'vistaPrevia'])->name('cotizaciones.vista-previa');
    Route::get('/{id}/generar-pdf', [cotizacionController::class, 'generarPDF'])->name('cotizaciones.generar-pdf');
    Route::post('/{id}/enviar-email', [cotizacionController::class, 'enviarEmail'])->name('cotizaciones.enviar-email');
});


// Rutas principales de cotizaciones
// Route::prefix('administracion/cotizaciones')->group(function () {
//     Route::get('/', [cotizacionController::class, 'index'])->name('cotizaciones.index');
//     Route::get('/create', [cotizacionController::class, 'create'])->name('cotizaciones.create');
//     Route::post('/', [cotizacionController::class, 'store'])->name('cotizaciones.store');
//     Route::get('/{id}', [cotizacionController::class, 'show'])->name('cotizaciones.show');
//     Route::get('/{id}/edit', [cotizacionController::class, 'edit'])->name('cotizaciones.edit');
//     Route::put('/{id}', [cotizacionController::class, 'update'])->name('cotizaciones.update');
//     Route::delete('/{id}', [cotizacionController::class, 'destroy'])->name('cotizaciones.delete');
//     Route::get('/{id}/pdf', [cotizacionController::class, 'generarPDF'])->name('cotizaciones.pdf');
//     Route::post('/{id}/enviar-email', [cotizacionController::class, 'enviarEmail'])->name('cotizaciones.enviar-email');
//     // En tu grupo de rutas web de cotizaciones, agrega:
//     Route::get('/{id}/detalles', [cotizacionController::class, 'detalle'])->name('cotizaciones.detalles');
// });

// Rutas para Áreas
Route::prefix('areas')->group(function () {
    Route::get('/', [AreasController::class, 'index'])->name('areas.index');
    Route::get('/create', [AreasController::class, 'create'])->name('areas.create');
    Route::post('/', [AreasController::class, 'store'])->name('areas.store');
    Route::get('/{id}', [AreasController::class, 'show'])->name('areas.show');
    Route::get('/{id}/edit', [AreasController::class, 'edit'])->name('areas.edit');
    Route::put('/{id}', [AreasController::class, 'update'])->name('areas.update');
    Route::delete('/{id}', [AreasController::class, 'destroy'])->name('areas.destroy');

    // API Routes
    Route::get('/api/areas', [AreasController::class, 'getAreas'])->name('api.areas');
    Route::get('/api/areas-data', [AreasController::class, 'getAll'])->name('api.areas.data');
    Route::get('/api/clientes-generales', [AreasController::class, 'getClientesGenerales'])->name('api.areas.clientes'); // ← NUEVA RUTA
    Route::get('/{id}/clientes-modal', [AreasController::class, 'getClientesModal']);
});



// Rutas para ContactoFinal
Route::prefix('contactofinal')->group(function () {
    Route::get('/', [ContactoFinalController::class, 'index'])->name('contactofinal.index');
    Route::post('/store', [ContactoFinalController::class, 'store'])->name('contactofinal.store');
    Route::get('/{id}/edit', [ContactoFinalController::class, 'edit'])->name('contactofinal.edit');
    Route::put('/{id}', [ContactoFinalController::class, 'update'])->name('contactofinal.update');
    Route::delete('/{id}', [ContactoFinalController::class, 'destroy'])->name('contactofinal.destroy');
    Route::get('/all', [ContactoFinalController::class, 'getAll'])->name('contactofinal.getAll');
});




// En tu archivo de rutas
Route::get('/contactos/cliente-general/{id}', [OrdenesHelpdeskController::class, 'obtenerContactosPorClienteGeneral'])->name('contactos.por-cliente-general');
Route::get('/contactos/todos', [OrdenesHelpdeskController::class, 'obtenerTodosLosContactos'])->name('contactos.todos');
Route::get('/cliente-general/{id}/contactos', [OrdenesHelpdeskController::class, 'obtenerClienteGeneralConContactos'])->name('cliente-general.contactos');


// Route::get('/cotizacion-productos/{cotizacionId}', [SolicitudarticuloController::class, 'getCotizacionProductos']);



Route::post('/solicitudcompra/{id}/observar', [SolicitudcompraController::class, 'observarSolicitud'])->name('solicitudcompra.observar');