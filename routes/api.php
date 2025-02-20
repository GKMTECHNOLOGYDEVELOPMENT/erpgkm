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
use App\Http\Controllers\tickets\OrdenesTrabajoController;
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
Route::patch('/api/actualizarVisita/{id}', [OrdenesTrabajoController::class, 'actualizarVisita']);


// Ruta para obtener las visitas de un ticket

