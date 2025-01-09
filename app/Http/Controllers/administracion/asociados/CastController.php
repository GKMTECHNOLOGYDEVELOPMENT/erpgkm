<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralRequests;
use App\Models\Cast;
use App\Models\Clientegeneral;
use Illuminate\Http\Request;

class CastController extends Controller
{
    public function index()
    {
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.cast'); 
    }

   

      public function getAll()
    {
        // Obtén todos los datos de la tabla cast
        $casts = Cast::all();

        // Retorna los datos en formato JSON
        return response()->json($casts);
    }
}