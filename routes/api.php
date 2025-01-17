<?php

use App\Http\Controllers\administracion\asociados\CastController;
use App\Http\Controllers\administracion\asociados\ClienteGeneralController;
use App\Http\Controllers\administracion\asociados\ClientesController;
use App\Http\Controllers\administracion\asociados\SubsidiarioController;
use App\Http\Controllers\administracion\asociados\TiendaController;
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

Route::get('/casts', [CastController::class, 'getAll']);

Route::get('/clientegeneral', [ClienteGeneralController::class, 'getAll']);


Route::get('/tiendas', [TiendaController::class, 'getAll']);

Route::post('/check-nombre-tienda', [TiendaController::class, 'checkNombreTienda']);


// Route::get('/subsidiarios', [SubsidiarioController::class, 'getAll']);

Route::get('/clientes', [ClientesController::class, 'getAll']);

Route::post('/check-nombre', [ClienteGeneralController::class, 'checkNombre']);

Route::delete('/clientegeneral/{id}', [ClienteGeneralController::class, 'destroy']);

Route::delete('/tiendas/{id}', [TiendaController::class, 'destroy']);

Route::delete('/clientes/{id}', [ClientesController::class, 'destroy']);


