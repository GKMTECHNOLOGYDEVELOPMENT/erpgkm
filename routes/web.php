<?php

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
use App\Http\Controllers\administracion\asistencias\OficinaController;
use App\Http\Controllers\administracion\asistencias\TecnicoController;
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
use App\Http\Controllers\Apps\MailboxController;
use App\Http\Controllers\Apps\TodolistController;
use App\Http\Controllers\Apps\NotesController;
use App\Http\Controllers\Apps\ScrumboardController;
use App\Http\Controllers\Apps\ContactsController;
use App\Http\Controllers\Apps\CalendarController;
use App\Models\Clientegeneral;
use App\Models\Subsidiario;
use Illuminate\Http\Client\Request;
use App\Exports\ClientesGeneralExport;
use App\Exports\ClientesExport;
use App\Exports\TiendaExport;
use App\Exports\CastExport;
use App\Http\Controllers\almacen\productos\CategoriaController;
use App\Http\Controllers\UbigeoController;
use Maatwebsite\Excel\Facades\Excel;
Auth::routes();

// Ruta para mostrar el formulario de login
Route::get('/', function () { return view('auth.cover-login'); })->name('login');
// Ruta para manejar el envío del formulario de login
Route::post('/login', [AuthController::class, 'login'])->name('login');
// Ruta para cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Ruta protegida con middleware 'auth'
Route::post('/check-email', [AuthController::class, 'checkEmail']);
// Ruta para la pantalla de bloqueo
Route::get('/auth/cover-lockscreen', [LockscreenController::class, 'show'])->name('auth.lockscreen');
// Ruta para la pantalla de restablecimiento de contraseña
Route::get('/auth/cover-password-reset', [PasswordResetController::class, 'show'])->name('auth.password-reset');


Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion')->middleware('auth');
Route::post('/configuracion', [ConfiguracionController::class, 'store'])->name('configuracion.store')->middleware('auth');
Route::post('/configuracion/delete', [ConfiguracionController::class, 'delete'])->name('configuracion.delete');
// Route::view('/auth/boxed-lockscreen', 'auth.boxed-lockscreen');
// Route::view('/auth/boxed-signin', 'auth.boxed-signin');
// Route::view('/auth/boxed-signup', 'auth.boxed-signup');
// Route::view('/auth/boxed-password-reset', 'auth.boxed-password-reset');
//Route::view('/auth/cover-login', 'auth.cover-login');
//Route::view('/auth/cover-register', 'auth.cover-register')
// Route::view('/auth/cover-lockscreen', 'auth.cover-lockscreen');
//Route::view('/auth/cover-password-reset', 'auth.cover-password-reset');

// Route::view('/analytics', 'analytics');
// Route::view('/finance', 'finance');
// Route::view('/crypto', 'crypto');
// Ruta para el dashboard de administración
Route::get('/index', [AdministracionController::class, 'index'])->name('index')->middleware('auth');

// Ruta para el dashboard de almacén
Route::get('/almacen', [AlmacenController::class, 'index'])->name('almacen')->middleware('auth');

// Ruta para el dashboard comercial
Route::get('/comercial', [ComercialController::class, 'index'])->name('commercial')->middleware('auth');

// Ruta para el dashboard de tickets
Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets')->middleware('auth');

// Ruta para Administración de Usuarios
Route::get('/administracion/usuarios', [UsuariosController::class, 'index'])->name('administracion.usuarios')->middleware('auth');
// Ruta para Administración de Compras
Route::get('/administracion/compras', [CompraController::class, 'index'])->name('administracion.compra')->middleware('auth');
//Rutas para Clientes Generales
Route::get('/cliente-general', [ClienteGeneralController::class, 'index'])->name('administracion.cliente-general')->middleware('auth');
Route::get('/cliente-general/{id}/edit', [ClienteGeneralController::class, 'edit'])->name('cliente-general.edit');
Route::get('/exportar-clientes-general', function () { return Excel::download(new ClientesGeneralExport, 'clientes_general.xlsx'); })->name('clientes-general.exportExcel');
Route::get('/clientes-general/export-pdf', [ClienteGeneralController::class, 'exportAllPDF']) ->name('clientes-general.exportPDF')->middleware('auth');

// Actualizar los datos del cliente general
Route::put('administracion/{id}', [ClienteGeneralController::class, 'update'])->name('cliente-general.update');
Route::post('cliente-general/store', [ClienteGeneralController::class, 'store'])->name('cliente-general.store');
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
Route::get('/exportar-tiendas', function () { return Excel::download(new TiendaExport, 'reporte_tiendas.xlsx'); })->name('tiendas.exportExcel');
Route::get('/reporte-tiendas', [TiendaController::class, 'exportAllPDF'])->name('reporte.tiendas');





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
Route::get('/exportar-cast', function () { return Excel::download(new CastExport, 'cast.xlsx'); })->name('cast.exportExcel');
Route::get('/reporte-cast', [CastController::class, 'exportAllPDF'])->name('reporte.cast');

// Route::get('/casts', [CastController::class, 'getAll']);
// Ruta para Administracion Subsidiario
// Route::get('/sub-sidiario/create', [SubsidiarioController::class, 'create'])->name('administracion.create')->middleware('auth');
//Ruta para Administracion Clientes
Route::get('/clientes', [ClientesController::class, 'index'])->name('administracion.clientes');
Route::post('/cliente/store', [ClientesController::class, 'store'])->name('cliente.store');
Route::get('/cliente/{idCliente}/edit', [ClientesController::class, 'edit'])->name('cliente.edit');
Route::put('/clientes/{idCliente}', [ClientesController::class, 'update'])->name('clientes.update');
Route::get('/exportar-clientes', function () { return Excel::download(new ClientesExport, 'clientes.xlsx'); })->name('clientes.exportExcel');
Route::get('/reporte-clientes', [ClientesController::class, 'exportAllPDF']) ->name('reporte.clientes');


//Ruta para Administracion Proveedores
Route::get('/proveedores', [ProveedoresController::class, 'index'])->name('administracion.proveedores')->middleware('auth');
Route::post('/proveedores/store', [ProveedoresController::class, 'store'])->name('proveedor.store');
Route::get('/proveedores/{idProveedor}/edit', [ProveedoresController::class, 'edit'])->name('proveedor.edit');
Route::put('/proveedores/{idProveedor}', [ProveedoresController::class, 'update'])->name('proveedores.update');
Route::get('/exportar-proveedores', function () { return Excel::download(new ProveedoresExport, 'proveedores.xlsx'); })->name('proveedores.exportExcel');
Route::get('/reporte-proveedores', [ProveedoresController::class, 'exportAllPDF'])->name('proveedores.pdf');


//Ruta para administracion cotizaciones
Route::get('/cotizaciones/crear-cotizacion', [cotizacionController::class, 'index'])->name('cotizaciones.crear-cotizacion')->middleware('auth');

//Ruta para Almacen Articulos
Route::get('/almacen/articulos', [ArticulosController::class, 'index'])->name('almacen.articulos')->middleware('auth');
//Ruta para Almacen Modelos
Route::get('/almacen/modelos', [ModelosController::class, 'index'])->name('almacen.modelos')->middleware('auth');
//Ruta para Almacen Marca
Route::get('/almacen/marca', [MarcaController::class, 'index'])->name('almacen.marcas')->middleware('auth');
//Ruta para Almacen TipoArticulos
Route::get('/almacen/tipo-articulos', [TipoArticuloController::class, 'index'])->name('almacen.tipo-articulos')->middleware('auth');
//Ruta para Almacen Categoria

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
// INICIO CATEGORIA /// XDDDDDDDDDDDDD
Route::prefix('categoria')->name('categorias.')->group(function () {
    Route::get('/', [CategoriaController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::post('/store', [CategoriaController::class, 'store'])->name('store'); // Guardar una nueva categoría
    Route::get('/{id}/edit', [CategoriaController::class, 'edit'])->name('edit');// Editar una categoría
    Route::put('/update/{id}', [CategoriaController::class, 'update'])->name('update'); // Actualizar una categoría
    Route::delete('/{id}', [CategoriaController::class, 'destroy'])->name('destroy'); // Eliminar una categoría
    Route::get('/export-pdf', [CategoriaController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar todas las categorías a PDF
    Route::get('/get-all', [CategoriaController::class, 'getAll'])->name('getAll'); // Obtener todas las categorías en formato JSON
    Route::post('/check-nombre', [CategoriaController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
});

/// FIN CATEGORIA ///

Route::prefix('marcas')->name('marcas.')->group(function () {
    Route::get('/', [MarcaController::class, 'index'])->name('index'); // Mostrar lista
    Route::get('/edit/{id}', [MarcaController::class, 'edit'])->name('edit'); // Mostrar formulario de edición
    Route::put('/update/{id}', [MarcaController::class, 'update'])->name('update'); // Actualizar marca
});


Route::view('/apps/invoice/list', 'apps.invoice.list');
Route::view('/apps/invoice/preview', 'apps.invoice.preview');
Route::view('/apps/invoice/add', 'apps.invoice.add');
Route::view('/apps/invoice/edit', 'apps.invoice.edit');



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

Route::view('/users/profile', 'users.profile');
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


