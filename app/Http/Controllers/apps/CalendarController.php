<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Etiqueta;
use App\Models\Usuario;
use Google\Service\ServiceControl\Auth;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return view('apps.calendar'); // Vista para calendar
    }


    public function usuariov1()
    {
        try {
            $usuarios = Usuario::query()
                ->select([
                    'idUsuario as id',
                    'Nombre',
                    'apellidoPaterno',
                    'apellidoMaterno',
                    'correo as email',
                    'idRol'
                ])
                ->where('estado', 1)
                ->where('departamento', 3926) // Solo usuarios de Lima
                ->where('idTipoUsuario', '!=', 7) // Excluir tipo Invitado
                ->orderBy('apellidoPaterno')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $usuarios->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'Nombre' => $user->Nombre,
                        'apellidoPaterno' => $user->apellidoPaterno,
                        'email' => $user->email,
                        'idRol' => $user->idRol,
                        'text' => "{$user->Nombre} {$user->apellidoPaterno}" // Para TomSelect
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar usuarios',
                'data' => []
            ], 500);
        }
    }
}
