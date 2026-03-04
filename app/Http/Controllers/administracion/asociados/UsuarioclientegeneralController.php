<?php
// app/Http/Controllers/administracion/asociados/UsuarioclientegeneralController.php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\ClienteGeneral;
use App\Models\Tipodocumento;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UsuarioclientegeneralController extends Controller
{
   /**
 * Mostrar lista de usuarios por cliente general
 */
/**
 * Mostrar lista de usuarios por cliente general
 */
public function index($idClienteGeneral)
{
    Log::info('=== INICIO: Listar usuarios por cliente general ===');
    Log::info('ID Cliente General recibido: ' . $idClienteGeneral);
    
    try {
        // Buscar el cliente
        $clienteGeneral = ClienteGeneral::find($idClienteGeneral);
        
        if (!$clienteGeneral) {
            Log::error('Cliente no encontrado con ID: ' . $idClienteGeneral);
            return redirect()->route('administracion.cliente-general')
                ->with('error', 'Cliente no encontrado');
        }
        
        Log::info('Cliente encontrado: ' . $clienteGeneral->descripcion . ' (ID: ' . $clienteGeneral->idClienteGeneral . ')');
        
        // Obtener usuarios - SIEMPRE será una colección, incluso si está vacía
        $usuarios = Usuario::with(['tipodocumento', 'rol'])
            ->where('idClienteGeneral', $idClienteGeneral)
            ->orderBy('idUsuario', 'desc')
            ->get();
        
        Log::info('Total usuarios encontrados: ' . $usuarios->count());
        Log::info('Tipo de variable $usuarios: ' . get_class($usuarios));
        
        // Verificar que la vista existe
        $vista = 'administracion.asociados.clienteGeneral.usuariosXclientes.index';
        
        if (!view()->exists($vista)) {
            Log::error('LA VISTA NO EXISTE: ' . $vista);
            
            // Intentar con la ruta alternativa
            $vistaAlternativa = 'administracion.asociados.usuarios-por-cliente';
            
            if (view()->exists($vistaAlternativa)) {
                Log::info('Usando vista alternativa: ' . $vistaAlternativa);
                return view($vistaAlternativa, compact('clienteGeneral', 'usuarios'));
            }
            
            throw new \Exception('La vista no existe: ' . $vista);
        }
        
        return view($vista, compact('clienteGeneral', 'usuarios'));
        
    } catch (\Exception $e) {
        Log::error('ERROR al cargar usuarios: ' . $e->getMessage());
        Log::error('Archivo: ' . $e->getFile() . ':' . $e->getLine());
        Log::error('Trace: ' . $e->getTraceAsString());
        
        return redirect()->route('administracion.cliente-general')
            ->with('error', 'Error al cargar los usuarios: ' . $e->getMessage());
    }
}
    /**
     * Obtener datos para el formulario (tipos de documento y roles)
     */
    public function getFormData()
    {
        Log::info('=== INICIO: Obtener datos para formulario ===');
        
        try {
            $tiposDocumento = Tipodocumento::orderBy('nombre')->get();
            Log::info('Tipos de documento encontrados: ' . $tiposDocumento->count());
            
            $roles = Rol::orderBy('nombre')->get();
            Log::info('Roles encontrados: ' . $roles->count());
            
            $response = [
                'success' => true,
                'tiposDocumento' => $tiposDocumento,
                'roles' => $roles
            ];
            
            Log::info('Datos enviados correctamente');
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            Log::error('ERROR al obtener datos del formulario: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            // Datos de respaldo en caso de error
            $response = [
                'success' => true,
                'tiposDocumento' => [
                    ['idTipoDocumento' => 1, 'nombre' => 'DNI'],
                    ['idTipoDocumento' => 2, 'nombre' => 'RUC'],
                    ['idTipoDocumento' => 3, 'nombre' => 'Carnet de Extranjería'],
                    ['idTipoDocumento' => 4, 'nombre' => 'Pasaporte']
                ],
                'roles' => [
                    ['idRol' => 1, 'nombre' => 'Administrador'],
                    ['idRol' => 2, 'nombre' => 'Supervisor'],
                    ['idRol' => 3, 'nombre' => 'Técnico'],
                    ['idRol' => 4, 'nombre' => 'Usuario'],
                    ['idRol' => 5, 'nombre' => 'Invitado']
                ]
            ];
            
            Log::warning('Usando datos de respaldo');
            
            return response()->json($response);
        }
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        Log::info('=== INICIO: Crear nuevo usuario ===');
        Log::info('Datos recibidos:', $request->all());
        
        try {
            DB::beginTransaction();
            Log::info('Transacción iniciada');

            // Validar datos con reglas de unicidad
            Log::info('Validando datos...');
            
            $messages = [
                'numeroDocumento.unique' => 'El número de documento ya está registrado en el sistema.',
                'telefono.unique' => 'El número de teléfono ya está registrado en el sistema.',
                'correoPersonal.unique' => 'El correo personal ya está registrado en el sistema.',
                'numeroDocumento.required' => 'El número de documento es obligatorio.',
                'telefono.required' => 'El teléfono es obligatorio.',
                'correoPersonal.required' => 'El correo personal es obligatorio.',
                'correoPersonal.email' => 'El correo personal debe ser una dirección de email válida.',
            ];
            
            $validated = $request->validate([
                'nombreCompleto' => 'required|string|max:255',
                'apellidoPaterno' => 'required|string|max:255',
                'apellidoMaterno' => 'nullable|string|max:255',
                'tipoDocumento' => 'required|exists:tipodocumento,idTipoDocumento',
                'numeroDocumento' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('usuarios', 'documento')->where(function ($query) {
                        return $query->whereNotNull('documento');
                    }),
                ],
                'telefono' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('usuarios', 'telefono')->where(function ($query) {
                        return $query->whereNotNull('telefono');
                    }),
                ],
                'correoPersonal' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('usuarios', 'correo_personal')->where(function ($query) {
                        return $query->whereNotNull('correo_personal');
                    }),
                ],
                'rol' => 'required|exists:rol,idRol',
                'enviarCredenciales' => 'sometimes|boolean',
                'activo' => 'sometimes|boolean',
                'idClienteGeneral' => 'required|exists:clientegeneral,idClienteGeneral'
            ], $messages);
            
            Log::info('Validación exitosa');

            // Verificar que el cliente existe
            $cliente = ClienteGeneral::find($request->idClienteGeneral);
            Log::info('Cliente encontrado: ' . ($cliente ? $cliente->descripcion : 'NO ENCONTRADO'));

            // Generar usuario y contraseña
            Log::info('Generando username...');
            $username = $this->generarUsername(
                $request->nombreCompleto, 
                $request->apellidoPaterno
            );
            Log::info('Username generado: ' . $username);
            
            Log::info('Generando password...');
            $password = $this->generarPassword();
            Log::info('Password generado (sin encriptar)');
            
            // Crear usuario
            Log::info('Creando objeto Usuario...');
            $usuario = new Usuario();
            $usuario->Nombre = $request->nombreCompleto;
            $usuario->apellidoPaterno = $request->apellidoPaterno;
            $usuario->apellidoMaterno = $request->apellidoMaterno ?? '';
            $usuario->documento = $request->numeroDocumento;
            $usuario->telefono = $request->telefono;
            $usuario->correo_personal = $request->correoPersonal;
            $usuario->idTipoDocumento = $request->tipoDocumento;
            $usuario->idRol = $request->rol;
            $usuario->idClienteGeneral = $request->idClienteGeneral;
            $usuario->estado = $request->has('activo') ? 1 : 0;
            
            // Datos de acceso
            $usuario->usuario = $username;
            $usuario->clave = $password; // Se encripta automáticamente por el mutator
            
            // Verificar campos requeridos que podrían dar error
            Log::info('Verificando campos requeridos...');
            $camposRequeridos = [
             
            ];
            
            foreach ($camposRequeridos as $campo => $valor) {
                if (!isset($usuario->$campo)) {
                    Log::warning("Campo {$campo} no está definido, asignando valor por defecto: {$valor}");
                    $usuario->$campo = $valor;
                }
            }
            
            // Guardar
            Log::info('Intentando guardar usuario en BD...');
            $usuario->save();
            Log::info('Usuario guardado con ID: ' . $usuario->idUsuario);

            // Enviar credenciales por correo si se solicita
            if ($request->has('enviarCredenciales') && $request->enviarCredenciales) {
                Log::info('Enviando credenciales por correo a: ' . $usuario->correo_personal);
                $this->enviarCredenciales($usuario, $password);
            } else {
                Log::info('No se solicitaron envío de credenciales');
            }

            DB::commit();
            Log::info('Transacción COMMIT exitosa');

            // Respuesta según el tipo de solicitud
            if ($request->ajax() || $request->wantsJson()) {
                Log::info('Enviando respuesta JSON exitosa');
                
                $usuarioConRelaciones = $usuario->load(['tipodocumento', 'rol']);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario creado exitosamente',
                    'usuario' => $usuarioConRelaciones
                ]);
            }

            Log::info('Redirigiendo a lista de usuarios');
            return redirect()->route('usuarios-por-cliente', $request->idClienteGeneral)
                ->with('success', 'Usuario creado exitosamente');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('ERROR DE VALIDACIÓN:');
            Log::error('Errores:', $e->errors());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error('ERROR DE BASE DE DATOS:');
            Log::error('Código: ' . $e->getCode());
            Log::error('Mensaje: ' . $e->getMessage());
            Log::error('SQL: ' . $e->getSql());
            Log::error('Bindings: ', $e->getBindings());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error en la base de datos: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error en la base de datos: ' . $e->getMessage())
                ->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ERROR GENERAL:');
            Log::error('Tipo: ' . get_class($e));
            Log::error('Mensaje: ' . $e->getMessage());
            Log::error('Archivo: ' . $e->getFile() . ':' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el usuario: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error al crear el usuario: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Obtener datos de un usuario para editar
     */
    public function edit($id)
    {
        Log::info('=== INICIO: Obtener usuario para editar ===');
        Log::info('ID Usuario: ' . $id);
        
        try {
            $usuario = Usuario::with(['tipodocumento', 'rol'])
                ->findOrFail($id);
            
            Log::info('Usuario encontrado: ' . $usuario->Nombre . ' ' . $usuario->apellidoPaterno);
            
            return response()->json([
                'success' => true,
                'usuario' => [
                    'idUsuario' => $usuario->idUsuario,
                    'nombreCompleto' => $usuario->Nombre,
                    'apellidoPaterno' => $usuario->apellidoPaterno,
                    'apellidoMaterno' => $usuario->apellidoMaterno,
                    'idTipoDocumento' => $usuario->idTipoDocumento,
                    'documento' => $usuario->documento,
                    'telefono' => $usuario->telefono,
                    'correo_personal' => $usuario->correo_personal,
                    'idRol' => $usuario->idRol,
                    'estado' => $usuario->estado
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('ERROR al obtener usuario: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar datos del usuario'
            ], 500);
        }
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {
        Log::info('=== INICIO: Actualizar usuario ===');
        Log::info('ID Usuario: ' . $id);
        Log::info('Datos recibidos:', $request->all());
        
        try {
            DB::beginTransaction();
            Log::info('Transacción iniciada');

            $usuario = Usuario::findOrFail($id);
            Log::info('Usuario encontrado: ' . $usuario->Nombre);

            // Validar datos con reglas de unicidad excluyendo el usuario actual
            Log::info('Validando datos...');
            
            $messages = [
                'numeroDocumento.unique' => 'El número de documento ya está registrado en el sistema.',
                'telefono.unique' => 'El número de teléfono ya está registrado en el sistema.',
                'correoPersonal.unique' => 'El correo personal ya está registrado en el sistema.',
            ];
            
            $validated = $request->validate([
                'nombreCompleto' => 'required|string|max:255',
                'apellidoPaterno' => 'required|string|max:255',
                'apellidoMaterno' => 'nullable|string|max:255',
                'tipoDocumento' => 'required|exists:tipodocumento,idTipoDocumento',
                'numeroDocumento' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('usuarios', 'documento')->ignore($id, 'idUsuario')->where(function ($query) {
                        return $query->whereNotNull('documento');
                    }),
                ],
                'telefono' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('usuarios', 'telefono')->ignore($id, 'idUsuario')->where(function ($query) {
                        return $query->whereNotNull('telefono');
                    }),
                ],
                'correoPersonal' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('usuarios', 'correo_personal')->ignore($id, 'idUsuario')->where(function ($query) {
                        return $query->whereNotNull('correo_personal');
                    }),
                ],
                'rol' => 'required|exists:rol,idRol',
                'activo' => 'sometimes|boolean'
            ], $messages);
            
            Log::info('Validación exitosa');

            // Actualizar datos
            $usuario->Nombre = $request->nombreCompleto;
            $usuario->apellidoPaterno = $request->apellidoPaterno;
            $usuario->apellidoMaterno = $request->apellidoMaterno ?? '';
            $usuario->documento = $request->numeroDocumento;
            $usuario->telefono = $request->telefono;
            $usuario->correo_personal = $request->correoPersonal;
            $usuario->idTipoDocumento = $request->tipoDocumento;
            $usuario->idRol = $request->rol;
            $usuario->estado = $request->has('activo') ? 1 : 0;
            
            Log::info('Guardando cambios...');
            $usuario->save();
            Log::info('Usuario actualizado correctamente');

            DB::commit();
            Log::info('Transacción COMMIT exitosa');

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario actualizado exitosamente',
                    'usuario' => $usuario->load(['tipodocumento', 'rol'])
                ]);
            }

            return redirect()->route('usuarios-por-cliente', $usuario->idClienteGeneral)
                ->with('success', 'Usuario actualizado exitosamente');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('ERROR DE VALIDACIÓN en actualización:');
            Log::error('Errores:', $e->errors());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ERROR GENERAL en actualización:');
            Log::error('Mensaje: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el usuario: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar usuario
     */
    public function destroy($id)
    {
        Log::info('=== INICIO: Eliminar usuario ===');
        Log::info('ID Usuario: ' . $id);
        
        try {
            DB::beginTransaction();
            Log::info('Transacción iniciada');

            $usuario = Usuario::findOrFail($id);
            Log::info('Usuario encontrado: ' . $usuario->Nombre);
            
            $idClienteGeneral = $usuario->idClienteGeneral;
            
            Log::info('Eliminando usuario...');
            $usuario->delete();
            Log::info('Usuario eliminado correctamente');

            DB::commit();
            Log::info('Transacción COMMIT exitosa');

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario eliminado exitosamente'
                ]);
            }

            return redirect()->route('usuarios-por-cliente', $idClienteGeneral)
                ->with('success', 'Usuario eliminado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ERROR al eliminar usuario: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Generar nombre de usuario único
     */
    private function generarUsername($nombre, $apellido)
    {
        Log::info('Generando username para: ' . $nombre . ' ' . $apellido);
        
        // Tomar primera letra del nombre + apellido
        $base = strtolower(substr($nombre, 0, 1) . $apellido);
        // Eliminar tildes y caracteres especiales
        $base = preg_replace('/[^a-z0-9]/', '', $base);
        $username = $base;
        $contador = 1;
        
        Log::info('Username base: ' . $base);
        
        while (Usuario::where('usuario', $username)->exists()) {
            $username = $base . $contador;
            $contador++;
            Log::info('Username ya existe, probando: ' . $username);
        }
        
        Log::info('Username final seleccionado: ' . $username);
        
        return $username;
    }

    /**
     * Generar contraseña aleatoria
     */
    private function generarPassword($longitud = 8)
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = substr(str_shuffle($caracteres), 0, $longitud);
        
        Log::info('Password generado: [OCULTO]');
        
        return $password;
    }

    /**
 * Enviar credenciales por correo
 */
private function enviarCredenciales($usuario, $password)
{
    try {
        $cliente = ClienteGeneral::find($usuario->idClienteGeneral);
        
        $data = [
            'nombre' => $usuario->Nombre . ' ' . $usuario->apellidoPaterno,
            'documento' => $usuario->documento, // Enviar el número de documento como usuario
            'password' => $password,
            'cliente' => $cliente ? $cliente->descripcion : 'Sistema',
            'url' => url('/login')
        ];
        
        if (view()->exists('emails.credenciales')) {
            Mail::send('emails.credenciales', $data, function($message) use ($usuario) {
                $message->to($usuario->correo_personal, $usuario->Nombre)
                        ->subject('Tus credenciales de acceso al sistema');
            });
        } else {
            $mensaje = "Hola {$data['nombre']},\n\n";
            $mensaje .= "Se ha creado una cuenta para usted en el sistema de {$data['cliente']}.\n\n";
            $mensaje .= "USUARIO: {$data['documento']} (su número de documento)\n"; // Cambiado aquí
            $mensaje .= "CONTRASEÑA: {$data['password']}\n\n";
            $mensaje .= "Puede acceder en: {$data['url']}\n\n";
            $mensaje .= "Por seguridad, cambie su contraseña en el primer inicio de sesión.";
            
            Mail::raw($mensaje, function($message) use ($usuario) {
                $message->to($usuario->correo_personal, $usuario->Nombre)
                        ->subject('Tus credenciales de acceso al sistema');
            });
        }
        
        Log::info('Correo enviado a: ' . $usuario->correo_personal);
        
    } catch (\Exception $e) {
        Log::error('Error al enviar correo de credenciales: ' . $e->getMessage());
    }
}


 public function usuarios($id)
    {
        Log::info('=== INICIO: Listar usuarios por cliente general ===');
        Log::info('ID Cliente General recibido: ' . $id);
        
        try {
            // Buscar el cliente
            $clienteGeneral = ClienteGeneral::findOrFail($id);
            Log::info('Cliente encontrado: ' . $clienteGeneral->descripcion . ' (ID: ' . $clienteGeneral->idClienteGeneral . ')');
            
            // Obtener usuarios del cliente - ¡ESTO ES LO QUE FALTABA!
            $usuarios = Usuario::with(['tipodocumento', 'rol'])
                ->where('idClienteGeneral', $id)
                ->orderBy('idUsuario', 'desc')
                ->get();
            
            Log::info('Total usuarios encontrados: ' . $usuarios->count());
            
            // Pasar AMBAS variables a la vista
            return view('administracion.asociados.clienteGeneral.usuariosXclientes.index', 
                compact('clienteGeneral', 'usuarios'));
            
        } catch (\Exception $e) {
            Log::error('ERROR al cargar usuarios: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return redirect()->route('administracion.cliente-general')
                ->with('error', 'Error al cargar los usuarios');
        }
    }
}