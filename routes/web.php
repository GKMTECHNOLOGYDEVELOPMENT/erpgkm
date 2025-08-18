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
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LockscreenController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apps\ChatController;
use App\Http\Controllers\Politicas\PoliticasController;
use App\Http\Controllers\Apps\MailboxController;
use App\Http\Controllers\Apps\TodolistController;
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
use App\Http\Controllers\administracion\compras\ComprasController;
use App\Http\Controllers\administracion\movimiento\entrada\EntradaController;
use App\Http\Controllers\administracion\movimiento\salida\SalidaController;
use App\Http\Controllers\almacen\despacho\DespachoController;
use App\Http\Controllers\almacen\heramientas\HeramientasController;
use App\Http\Controllers\almacen\kardex\KardexController;
use App\Http\Controllers\almacen\productos\ProductoController;
use App\Http\Controllers\almacen\repuestos\RepuestosController;
use App\Http\Controllers\almacen\subcategoria\SubcategoriaController;
use App\Http\Controllers\almacen\suministros\SuministrosController;
use App\Http\Controllers\almacen\ubicaciones\UbicacionesController;
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
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\GuiaController;
use App\Http\Controllers\PasswordRecoveryController;
use App\Http\Controllers\solicitud\SolicitudarticuloController;
use App\Http\Controllers\UbigeoController;
use App\Http\Controllers\usuario\UsuarioController;
use App\Models\Cliente;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Subcategoria;
use App\Models\Suministro;
use App\Models\Tienda;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
Auth::routes();
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
// Ruta para la pantalla de restablecimiento de contraseña
Route::get('/auth/cover-password-reset', [PasswordResetController::class, 'show'])->name('auth.password-reset');
Route::get('/', [AdministracionController::class, 'index'])->name('index')->middleware('auth');
Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion')->middleware('auth');
Route::post('/configuracion', [ConfiguracionController::class, 'store'])->name('configuracion.store')->middleware('auth');
Route::post('/configuracion/delete', [ConfiguracionController::class, 'delete'])->name('configuracion.delete');
// Ruta para el dashboard de almacén
Route::get('/almacen', [AlmacenController::class, 'index'])->name('almacen')->middleware('auth');
// Ruta para el dashboard comercial
Route::get('/comercial', [ComercialController::class, 'index'])->name('commercial')->middleware('auth');
// Ruta para el dashboard de tickets
Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets')->middleware('auth');
// Ruta para Administración de Usuarios
Route::get('/administracion/usuarios', [UsuariosController::class, 'index'])->name('administracion.usuarios')->middleware('auth');
// Ruta para Administración de Compras
Route::get('/administracion/compras', [ComprasController::class, 'index'])->name('administracion.compra')->middleware('auth');
//Rutas para Clientes Generales
Route::get('/cliente-general', [ClienteGeneralController::class, 'index'])->name('administracion.cliente-general')->middleware('auth');
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
Route::get('/tienda', [TiendaController::class, 'index'])->name('administracion.tienda')->middleware('auth');
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
Route::get('/cast', [CastController::class, 'index'])->name('administracion.cast')->middleware('auth');
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
Route::get('/clientes', [ClientesController::class, 'index'])->name('administracion.clientes');
Route::post('/cliente/store', [ClientesController::class, 'store'])->name('cliente.store');
Route::get('/cliente/{idCliente}/edit', [ClientesController::class, 'edit'])->name('cliente.edit');
Route::put('/clientes/{idCliente}', [ClientesController::class, 'update'])->name('clientes.update');
Route::get('/exportar-clientes', function () {
    return Excel::download(new ClientesExport, 'clientes.xlsx');
})->name('clientes.exportExcel');
Route::get('/reporte-clientes', [ClientesController::class, 'exportAllPDF'])->name('reporte.clientes');
//Ruta para Administracion Asistencia
Route::get('/asistencias', [AsistenciaController::class, 'index'])->name('asistencias.index');
Route::post('/asistencias/actualizar-observacion', [AsistenciaController::class, 'actualizarEstadoObservacion']);
Route::get('/asistencias/observacion/{id}', [AsistenciaController::class, 'obtenerImagenesObservacion']);
Route::get('/asistencias/historial/{id}', [AsistenciaController::class, 'verHistorialUsuario'])->name('asistencias.historial.usuario');
Route::post('/asistencias/responder-observacion', [AsistenciaController::class, 'responderObservacion']);
Route::get('/asistencias/listado', [AsistenciaController::class, 'getAsistencias'])->name('asistencias.listado');
//Ruta para Administracion Proveedores
Route::get('/proveedores', [ProveedoresController::class, 'index'])->name('administracion.proveedores')->middleware('auth');
Route::post('/proveedores/store', [ProveedoresController::class, 'store'])->name('proveedor.store');
Route::get('/proveedores/{idProveedor}/edit', [ProveedoresController::class, 'edit'])->name('proveedor.edit');
Route::put('/proveedores/{idProveedor}', [ProveedoresController::class, 'update'])->name('proveedores.update');
Route::get('/exportar-proveedores', function () {
    return Excel::download(new ProveedoresExport, 'proveedores.xlsx');
})->name('proveedores.exportExcel');
Route::get('/reporte-proveedores', [ProveedoresController::class, 'exportAllPDF'])->name('proveedores.pdf');
//Ruta para administracion cotizaciones
Route::get('/cotizaciones/crear-cotizacion', [cotizacionController::class, 'index'])->name('cotizaciones.crear-cotizacion')->middleware('auth');
Route::get('/apps/chat', [ChatController::class, 'index'])->name('apps.chat');
Route::get('/apps/mailbox', [MailboxController::class, 'index'])->name('apps.mailbox');
Route::get('/apps/todolist', [TodolistController::class, 'index'])->name('apps.todolist');
Route::get('/apps/notes', [NotesController::class, 'index'])->name('apps.notes');
Route::get('/apps/scrumboard', [ScrumboardController::class, 'index'])->name('apps.scrumboard');
Route::get('/apps/contacts', [ContactsController::class, 'index'])->name('apps.contacts');
Route::get('/apps/calendar', [CalendarController::class, 'index'])->name('apps.calendar');
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
    Route::get('/', [CategoriaController::class, 'index'])->name('index'); // Mostrar la vista principal
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
// INICIO CATEGORIA ///
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
Route::delete('categorias/{id}', [CategoriaController::class, 'destroy'])->name('destroy');
/// FIN CATEGORIA ///
/// INICIO MARCA ///
Route::prefix('marcas')->name('marcas.')->group(function () {
    Route::get('/', [MarcaController::class, 'index'])->name('index'); // Mostrar la vista principal
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

/// FI
/// FIN MARCA ///
/// INICIO MODELO ///
Route::prefix('modelos')->name('modelos.')->group(function () {
    Route::get('/', [ModelosController::class, 'index'])->name('index'); // Mostrar la vista principal
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
    Route::get('/', [RepuestosController::class, 'index'])->name('index'); // Mostrar la vista principal
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
        return Excel::download(new ArticuloExport, 'despacho.xlsx');
    })->name('exportExcel');
});
/// INICIO VENTAS ///
Route::prefix('ventas')->name('ventas.')->group(function () {
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
        return Excel::download(new ArticuloExport, 'ventas.xlsx');
    })->name('exportExcel');
});
/// INICIO COMPRAS ///
Route::prefix('compras')->name('compras.')->group(function () {
    Route::get('/', [ComprasController::class, 'index'])->name('index'); // Mostrar la vista principal
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
        return Excel::download(new ArticuloExport, 'compras.xlsx');
    })->name('exportExcel');
});

Route::get('/buscar-repuesto', [ArticulosController::class, 'buscar']);
Route::post('/guardar-repuesto', [ArticulosController::class, 'store']);

Route::post('/articulosmodal', [ArticulosController::class, 'storeModal'])->name('articulos.store');
/// INICIO DEVOLUCIONES ///
Route::prefix('devoluciones')->name('devoluciones.')->group(function () {
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
        return Excel::download(new ArticuloExport, 'devoluciones.xlsx');
    })->name('exportExcel');
});
/// INICIO ARTICULOS ///
Route::prefix('heramientas')->name('heramientas.')->group(function () {
    Route::get('/', [HeramientasController::class, 'index'])->name('index'); // Mostrar la vista principal
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
/// INICIO ARTICULOS ///
Route::prefix('suministros')->name('suministros.')->group(function () {
    Route::get('/', [SuministrosController::class, 'index'])->name('index'); // Mostrar la vista principal
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
/// INICIO ARTICULOS ///
Route::prefix('producto')->name('producto.')->group(function () {
    Route::get('/', [ProductoController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::get('/create', [ProductoController::class, 'create'])->name('create'); // Formulario de creación
    Route::post('/store', [ProductoController::class, 'store'])->name('store'); // Guardar un nuevo artículo
    Route::get('/{id}/edit', [ProductoController::class, 'edit'])->name('edit'); // Editar un artículo
    Route::get('/{id}/kardex', [ProductoController::class, 'kardex'])->name('kardex'); // Editar un artículo
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
    Route::get('/', [SubcategoriaController::class, 'index'])->name('index'); // Mostrar la vista principal
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


// INICIO CATEGORIA ///
Route::prefix('solicitudarticulo')->name('solicitudarticulo.')->group(function () {
    Route::get('/', [SolicitudarticuloController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::get('/create', [SolicitudarticuloController::class, 'create'])->name('create'); // Guardar una nueva categoría
    Route::post('/store', [SolicitudarticuloController::class, 'store'])->name('store'); // Guardar una nueva categoría
    Route::get('/{id}/edit', [SolicitudarticuloController::class, 'edit'])->name('edit'); // Editar una categoría
        Route::get('/{id}/show', [SolicitudarticuloController::class, 'show'])->name('show'); // Editar una categoría
        Route::get('/{id}/opciones', [SolicitudarticuloController::class, 'opciones'])->name('opciones'); // Editar una categoría

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
    // Lógica del tipo de nombre
    $nombre = 'Visita';

    if ($idEstadflujo == 8) {
        $nombre = 'Recojo';
    } elseif ($idEstadflujo == 18) {
        $nombre = 'Entrega';
    } elseif ($idEstadflujo == 1) {
        if ($ticket->evaluaciontienda == 1) {
            $nombre = 'EvaluacionTienda';
        } else {
            $nombre = 'Visita';
        }
    }
    // Si no es Recojo, contar visitas
    $numeroVisitas = 0;
    if ($nombre !== 'Recojo') {
        $numeroVisitas = DB::table('visitas')
            ->where('idTickets', $ticketId)
            ->where('nombre', $nombre) // aquí filtras solo por el tipo actual
            ->count();
    }
    return response()->json([
        'numeroVisitas' => $numeroVisitas,
        'idEstadflujo' => $idEstadflujo,
        'tipoNombre' => $nombre // lo devolvemos para mayor claridad
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
    Route::get('/smart', [OrdenesTrabajoController::class, 'smarttable'])->name('smart');
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
    Route::get('/helpdesk', [OrdenesHelpdeskController::class, 'helpdesk'])->name('helpdesk');
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
    Route::get('/helpdesk/pdf/laboratorio/{idOt}', [OrdenesHelpdeskController::class, 'generateLabPdfVisita'])
        ->name('helpdesk.pdf.laboratorio');
    //EJECUCION
    Route::get('/helpdesk/ejecucion/{id}/edit', [OrdenesHelpdeskController::class, 'editejecucion'])
        ->name('helpdesk.ejecucion.edit');
    Route::get('/helpdesk/pdf/levantamiento/{idOt}', [OrdenesHelpdeskController::class, 'generateLevantamientoPdf'])
        ->name('helpdesk.pdf.levantamiento');
    Route::get('/helpdesk/pdf/soporte/{idOt}', [OrdenesHelpdeskController::class, 'generateSoportePdf'])->name('helpdesk.pdf.soporte');
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
Route::post('/guardar-modelo-smart', [ModelosController::class, 'store'])->name('guardar.modelo');
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
Route::get('/usuario', [UsuarioController::class, 'index'])->name('usuario');
Route::get('/create/usuario', [UsuarioController::class, 'create'])->name('usuario.create');
Route::get('/usuario/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuario.edit');
Route::post('/usuario/store', [UsuarioController::class, 'store'])->name('usuarios.store');
Route::put('config/{id}', [UsuarioController::class, 'config'])->name('usuario.config');
Route::put('/usuario/direccion/{id}', [UsuarioController::class, 'direccion'])->name('usuario.direccion');
// Route::put('/usuario/firma/{idUsuario}', [UsuarioController::class, 'actualizarFirma'])->name('usuario.firma');
// Route::get('/informe-pdf/{idTickets}', [OrdenesTrabajoController::class, 'generarInformePdf'])->name('informe.pdf');
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
Route::delete('/eliminar-suministro/{idSuministro}', [OrdenesHelpdeskController::class, 'eliminarSuministros']);
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
// Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

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
Route::get('/password/reset', function () {
    return view('auth.boxed-password-reset');
})->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Otras rutas necesarias para el reset
Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');
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
    Route::get('Seguimiento-Cliente/', [ClienteSeguimientoController::class, 'index'])->name('Seguimiento.index');
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
    'index', 'store', 'update', 'destroy'
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