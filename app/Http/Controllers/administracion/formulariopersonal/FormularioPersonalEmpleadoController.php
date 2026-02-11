<?php

namespace App\Http\Controllers\administracion\formulariopersonal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TipoDocumento;
use App\Models\Sexo;

class FormularioPersonalEmpleadoController extends Controller
{
    public function create()
    {
        // Cargar datos para los selects
        $tiposDocumento = TipoDocumento::all();
        $sexos = Sexo::all();
        
        // Cargar datos de ubigeo desde el JSON (igual que en clientes)
        $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
        
        return view('administracion.formulariopersonal.create', compact(
            'tiposDocumento',
            'sexos',
            'departamentos'
        ));
    }
    
  
}