<x-layout.default>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('administracion.cliente-general') }}" class="text-primary hover:underline">
                    Clientes Generales
                </a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Usuarios de {{ $clienteGeneral->descripcion ?? 'Cliente' }}</span>
            </li>
        </ul>
    </div>

    <div class="panel mt-6">
        <div class="flex items-center justify-between mb-5">
            <h5 class="font-semibold text-lg dark:text-white-light">
                Lista de Usuarios - {{ $clienteGeneral->descripcion ?? '' }}
            </h5>
            <div class="flex gap-2">
                <button type="button" class="btn btn-primary"
                    onclick="abrirModalCrearUsuario({{ $clienteGeneral->idClienteGeneral }})">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2">
                        <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.5" />
                        <path d="M5 18V17C5 14.2386 7.23858 12 10 12H14C16.7614 12 19 14.2386 19 17V18"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M20 12H22M2 12H4M12 2V4M12 20V22" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" opacity="0.5" />
                    </svg>
                    Nuevo Usuario
                </button>
                <button type="button" class="btn btn-outline-primary" onclick="window.history.back()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2">
                        <path d="M4 12L20 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M8 17L4 12L8 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    Volver
                </button>
            </div>
        </div>

        <!-- Mensajes con Toastr -->
        @if (session('success'))
            <script>
                $(document).ready(function() {
                    toastr.success('{{ session('success') }}');
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                $(document).ready(function() {
                    toastr.error('{{ session('error') }}');
                });
            </script>
        @endif

        <!-- Tabla de usuarios -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="tablaUsuarios">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">#
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre Completo</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Documento</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Teléfono</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Correo</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rol
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($usuarios as $index => $usuario)
                        <tr class="hover:bg-gray-50" id="usuario-{{ $usuario->idUsuario }}">
                            <td class="px-4 py-3 text-sm text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-sm text-center">
                                {{ $usuario->Nombre }} {{ $usuario->apellidoPaterno }} {{ $usuario->apellidoMaterno }}
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                {{ $usuario->tipoDocumento->nombre ?? 'N/A' }}: {{ $usuario->documento }}
                            </td>
                            <td class="px-4 py-3 text-sm text-center">{{ $usuario->telefono }}</td>
                            <td class="px-4 py-3 text-sm text-center">{{ $usuario->correo_personal }}</td>
                            <td class="px-4 py-3 text-sm text-center">
                                <span class="badge bg-primary/10 text-primary">
                                    {{ $usuario->rol->nombre ?? 'Sin rol' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                {!! $usuario->estado_badge !!}
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Botón Editar -->
                                    <button type="button"
                                        class="p-2 hover:bg-yellow-100 rounded-full transition-colors group"
                                        title="Editar" onclick="editarUsuario({{ $usuario->idUsuario }})">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="text-gray-600 group-hover:scale-110 transition-transform group-hover:text-yellow-600">
                                            <path
                                                d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z"
                                                stroke="currentColor" stroke-width="1.5" />
                                            <path opacity="0.5"
                                                d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015"
                                                stroke="currentColor" stroke-width="1.5" />
                                        </svg>
                                    </button>

                                    <!-- Botón Reenviar Credenciales - AHORA USA MODAL -->
                                    <button type="button"
                                        class="p-2 hover:bg-blue-100 rounded-full transition-colors group"
                                        title="Reenviar Credenciales"
                                        onclick="abrirModalReenviar({{ $usuario->idUsuario }})">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="text-gray-600 group-hover:scale-110 transition-transform group-hover:text-blue-600">
                                            <path
                                                d="M22 12C22 6.47715 17.5228 2 12 2C7.835 2 4.24507 4.27513 2.42676 7.5M2.42676 7.5H6.5M2.42676 7.5L2.5 7M22 12C22 17.5228 17.5228 22 12 22C7.835 22 4.24507 19.7249 2.42676 16.5M2.42676 16.5H6.5M2.42676 16.5L2.5 17"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                            <path d="M9 12H15M15 12L13 10M15 12L13 14" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>

                                    <!-- Botón Eliminar -->
                                    <button type="button"
                                        class="p-2 hover:bg-red-100 rounded-full transition-colors group"
                                        title="Eliminar"
                                        onclick="eliminarUsuario({{ $usuario->idUsuario }}, '{{ $usuario->Nombre }}')">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="text-gray-600 group-hover:scale-110 transition-transform group-hover:text-red-600">
                                            <path opacity="0.5"
                                                d="M9.17065 4C9.58249 2.83481 10.6937 2 11.9999 2C13.3062 2 14.4174 2.83481 14.8292 4"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                            <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                            <path
                                                d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                            <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" />
                                            <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                No hay usuarios registrados para este cliente
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL DE CREAR/EDITAR USUARIO -->
    <div x-data="usuarioModal()" x-init="init()" class="mb-5">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-2xl">

                    <!-- Header -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <div class="font-bold text-lg flex items-center gap-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary">
                                <circle cx="12" cy="8" r="4" stroke="currentColor"
                                    stroke-width="1.5" />
                                <path d="M5 18V17C5 14.2386 7.23858 12 10 12H14C16.7614 12 19 14.2386 19 17V18"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M20 12H22M2 12H4M12 2V4M12 20V22" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" opacity="0.5" />
                            </svg>
                            <span x-text="modo === 'crear' ? 'Crear Nuevo Usuario' : 'Editar Usuario'"></span>
                        </div>
                        <button type="button" class="text-white-dark hover:text-dark" @click="cerrarModal">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="1.5" />
                                <path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-5">
                        <form @submit.prevent="guardarUsuario">
                            @csrf
                            <input type="hidden" name="idClienteGeneral" x-model="form.idClienteGeneral">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nombre Completo -->
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nombre Completo <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="form.nombreCompleto" class="form-input w-full"
                                        placeholder="Ej: Juan Carlos Pérez" required>
                                </div>

                                <!-- Apellido Paterno -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Apellido Paterno <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="form.apellidoPaterno" class="form-input w-full"
                                        placeholder="Ej: Pérez" required>
                                </div>

                                <!-- Apellido Materno -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Apellido Materno
                                    </label>
                                    <input type="text" x-model="form.apellidoMaterno" class="form-input w-full"
                                        placeholder="Ej: García">
                                </div>

                                <!-- Tipo de Documento -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Tipo de Documento <span class="text-red-500">*</span>
                                    </label>
                                    <select x-model="form.tipoDocumento" class="form-select w-full" required>
                                        <option value="">Seleccione tipo</option>
                                        <template x-for="td in tiposDocumento" :key="td.idTipoDocumento">
                                            <option :value="td.idTipoDocumento" x-text="td.nombre"></option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Número de Documento -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Número de Documento <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="form.numeroDocumento" class="form-input w-full"
                                        placeholder="Ej: 12345678" required>
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Teléfono <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" x-model="form.telefono" class="form-input w-full"
                                        placeholder="Ej: 987654321" required>
                                </div>

                                <!-- Correo Personal -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Correo Personal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" x-model="form.correoPersonal" class="form-input w-full"
                                        placeholder="Ej: usuario@email.com" required>
                                </div>

                                <!-- Rol -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Rol <span class="text-red-500">*</span>
                                    </label>
                                    <select x-model="form.rol" class="form-select w-full" required>
                                        <option value="">Seleccione un rol</option>
                                        <template x-for="r in roles" :key="r.idRol">
                                            <option :value="r.idRol" x-text="r.nombre"></option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Campos adicionales -->
                                <div class="col-span-2">
                                    <div class="flex items-center gap-4">
                                        <template x-if="modo === 'crear'">
                                            <div class="flex items-center">
                                                <input type="checkbox" id="enviarCredenciales"
                                                    x-model="form.enviarCredenciales" class="form-checkbox">
                                                <label for="enviarCredenciales" class="ml-2 text-sm">
                                                    Enviar credenciales al correo
                                                </label>
                                            </div>
                                        </template>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="activo" x-model="form.activo"
                                                class="form-checkbox">
                                            <label for="activo" class="ml-2 text-sm">
                                                Usuario activo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="flex justify-end items-center mt-8 gap-2">
                                <button type="button" class="btn btn-outline-danger" @click="cerrarModal">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1">
                                        <circle opacity="0.5" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="1.5" />
                                        <path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" />
                                    </svg>
                                    Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary" :disabled="cargando">
                                    <svg v-if="!cargando" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1">
                                        <path d="M3 10H21" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M12 3V21" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" opacity="0.5" />
                                        <circle cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="1.5" opacity="0.5" />
                                    </svg>
                                    <span x-text="modo === 'crear' ? 'Crear Usuario' : 'Actualizar Usuario'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE ELIMINACIÓN -->
    <div x-data="eliminarModal()" x-init="initEliminar()">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-md my-8">
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Confirmar Eliminación</h5>
                        <button type="button" class="text-white-dark hover:text-dark" @click="cerrar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="p-5 text-center">
                        <div class="text-center mb-4">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="mx-auto text-red-500">
                                <circle cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="1.5" />
                                <path d="M12 7V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <circle cx="12" cy="16" r="1" fill="currentColor" />
                            </svg>
                        </div>
                        <p class="text-base font-medium mb-2">¿Estás seguro de eliminar este usuario?</p>
                        <p class="text-sm text-gray-500 mb-4" x-text="'Usuario: ' + userName"></p>
                        <p class="text-xs text-gray-400 mb-6">Esta acción no se puede deshacer.</p>
                        <div class="flex justify-center gap-4">
                            <button type="button" class="btn btn-outline-secondary"
                                @click="cerrar">Cancelar</button>
                            <button type="button" class="btn btn-danger" @click="confirmarEliminar"
                                :disabled="cargando">
                                <span x-show="!cargando">Eliminar</span>
                                <span x-show="cargando">Eliminando...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE REENVIAR CREDENCIALES - AHORA IGUAL AL MODAL DE ELIMINAR -->
    <div x-data="reenviarModal()" x-init="initReenviar()">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-md my-8">
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Reenviar Credenciales</h5>
                        <button type="button" class="text-white-dark hover:text-dark" @click="cerrar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="p-5 text-center">
                        <div class="text-center mb-4">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="mx-auto text-blue-500">
                                <circle cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="1.5" />
                                <path d="M12 6V12L15 15" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" />
                                <path d="M22 12C22 6.47715 17.5228 2 12 2C7.835 2 4.24507 4.27513 2.42676 7.5"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </div>
                        <p class="text-base font-medium mb-2">¿Reenviar credenciales?</p>
                        <p class="text-sm text-gray-500 mb-4">Se generará una nueva contraseña y se enviará al correo
                            del usuario.</p>
                        <p class="text-xs text-gray-400 mb-6">Esta acción no afecta los datos del usuario, solo su
                            contraseña.</p>
                        <div class="flex justify-center gap-4">
                            <button type="button" class="btn btn-outline-secondary"
                                @click="cerrar">Cancelar</button>
                            <button type="button" class="btn btn-primary" @click="confirmarReenviar"
                                :disabled="cargando">
                                <span x-show="!cargando">Reenviar</span>
                                <span x-show="cargando">Enviando...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (necesario para Toastr) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Configuración de Toastr -->
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>
    
    <script>
        // Datos iniciales desde PHP
        const clienteGeneralId = {{ $clienteGeneral->idClienteGeneral }};

        // Modal de Usuario
        function usuarioModal() {
            return {
                open: false,
                modo: 'crear',
                cargando: false,
                tiposDocumento: [],
                roles: [],
                form: {
                    idClienteGeneral: clienteGeneralId,
                    nombreCompleto: '',
                    apellidoPaterno: '',
                    apellidoMaterno: '',
                    tipoDocumento: '',
                    numeroDocumento: '',
                    telefono: '',
                    correoPersonal: '',
                    rol: '',
                    enviarCredenciales: true,
                    activo: true,
                },

                init() {
                    this.cargarSelectores();

                    // Escuchar eventos
                    document.addEventListener('abrir-modal-crear', (e) => {
                        this.abrirModalCrear(e.detail.clienteId);
                    });

                    document.addEventListener('abrir-modal-editar', (e) => {
                        this.abrirModalEditar(e.detail.usuarioId);
                    });
                },

                cargarSelectores() {
                    fetch('/usuarios-cliente-general/get-form-data')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.tiposDocumento = data.tiposDocumento;
                                this.roles = data.roles;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                },

                abrirModalCrear(clienteId) {
                    this.modo = 'crear';
                    this.form.idClienteGeneral = clienteId;
                    this.resetForm();
                    this.open = true;
                    console.log('Modal crear abierto para cliente:', clienteId);
                },

                abrirModalEditar(usuarioId) {
                    this.modo = 'editar';
                    this.cargando = true;

                    fetch(`/usuarios-cliente-general/${usuarioId}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const u = data.usuario;
                                this.form = {
                                    idUsuario: u.idUsuario,
                                    idClienteGeneral: clienteGeneralId,
                                    nombreCompleto: u.nombreCompleto,
                                    apellidoPaterno: u.apellidoPaterno,
                                    apellidoMaterno: u.apellidoMaterno || '',
                                    tipoDocumento: u.idTipoDocumento,
                                    numeroDocumento: u.documento,
                                    telefono: u.telefono,
                                    correoPersonal: u.correo_personal,
                                    rol: u.idRol,
                                    enviarCredenciales: false,
                                    activo: u.estado == 1
                                };
                                this.open = true;
                                console.log('Modal editar abierto para usuario:', usuarioId);
                            } else {
                                toastr.error('Error al cargar datos del usuario');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error('Error al cargar datos del usuario');
                        })
                        .finally(() => {
                            this.cargando = false;
                        });
                },

                cerrarModal() {
                    this.open = false;
                    this.resetForm();
                },

                resetForm() {
                    this.form = {
                        idClienteGeneral: clienteGeneralId,
                        nombreCompleto: '',
                        apellidoPaterno: '',
                        apellidoMaterno: '',
                        tipoDocumento: '',
                        numeroDocumento: '',
                        telefono: '',
                        correoPersonal: '',
                        rol: '',
                        enviarCredenciales: true,
                        activo: true,
                    };
                },

                guardarUsuario() {
                    // Validaciones básicas
                    if (!this.form.nombreCompleto || !this.form.apellidoPaterno || !this.form.tipoDocumento ||
                        !this.form.numeroDocumento || !this.form.telefono || !this.form.correoPersonal || !this.form.rol) {
                        toastr.warning('Por favor complete todos los campos requeridos');
                        return;
                    }

                    this.cargando = true;

                    const url = this.modo === 'crear' ?
                        '/usuarios-cliente-general' :
                        `/usuarios-cliente-general/${this.form.idUsuario}`;

                    const datos = this.modo === 'crear' ?
                        this.form :
                        {
                            ...this.form,
                            _method: 'PUT'
                        };

                    console.log('Enviando a:', url, 'Datos:', datos);

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(datos)
                        })
                        .then(response => {
                            console.log('Status:', response.status);
                            console.log('Content-Type:', response.headers.get('content-type'));

                            if (!response.ok) {
                                return response.text().then(text => {
                                    console.error('Respuesta no OK:', text.substring(0, 200));
                                    throw new Error(
                                        `Error ${response.status}: El servidor devolvió HTML en lugar de JSON`
                                        );
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Respuesta JSON:', data);
                            if (data.success) {
                                toastr.success(this.modo === 'crear' ? 'Usuario creado exitosamente' :
                                    'Usuario actualizado exitosamente');
                                this.cerrarModal();
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                if (data.errors) {
                                    let mensajes = '';
                                    for (let campo in data.errors) {
                                        mensajes += `• ${data.errors[campo].join('\n')}\n`;
                                    }
                                    toastr.error(mensajes, 'Errores encontrados');
                                } else {
                                    toastr.error(data.message || 'Error al guardar usuario');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error('Error al guardar usuario: ' + error.message);
                        })
                        .finally(() => {
                            this.cargando = false;
                        });
                }
            }
        }

        // Modal de Eliminación
        function eliminarModal() {
            return {
                open: false,
                usuarioId: null,
                userName: '',
                cargando: false,

                initEliminar() {
                    document.addEventListener('abrir-modal-eliminar', (e) => {
                        this.usuarioId = e.detail.usuarioId;
                        this.userName = e.detail.userName;
                        this.open = true;
                        console.log('Modal eliminar abierto para usuario:', this.usuarioId);
                    });
                },

                cerrar() {
                    this.open = false;
                    this.usuarioId = null;
                    this.userName = '';
                },

                confirmarEliminar() {
                    if (!this.usuarioId) return;

                    this.cargando = true;

                    fetch(`/usuarios-cliente-general/${this.usuarioId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                _method: 'DELETE'
                            })
                        })
                        .then(response => {
                            console.log('Status:', response.status);
                            if (!response.ok) {
                                return response.text().then(text => {
                                    throw new Error(`Error ${response.status}: ${text.substring(0, 100)}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                toastr.success('Usuario eliminado exitosamente');
                                this.cerrar();
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                toastr.error(data.message || 'Error al eliminar usuario');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error('Error al eliminar usuario: ' + error.message);
                        })
                        .finally(() => {
                            this.cargando = false;
                        });
                }
            }
        }

        // Modal de Reenviar Credenciales - AHORA IGUAL AL MODAL DE ELIMINAR
        function reenviarModal() {
            return {
                open: false,
                usuarioId: null,
                cargando: false,

                initReenviar() {
                    document.addEventListener('abrir-modal-reenviar', (e) => {
                        this.usuarioId = e.detail.usuarioId;
                        this.open = true;
                        console.log('Modal reenviar abierto para usuario:', this.usuarioId);
                    });
                },

                cerrar() {
                    this.open = false;
                    this.usuarioId = null;
                },

                confirmarReenviar() {
                    if (!this.usuarioId) return;

                    this.cargando = true;

                    fetch(`/usuarios-cliente-general/${this.usuarioId}/reenviar-credenciales`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            console.log('Status:', response.status);
                            if (!response.ok) {
                                return response.text().then(text => {
                                    throw new Error(`Error ${response.status}: ${text.substring(0, 100)}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                toastr.success('Credenciales reenviadas exitosamente');
                                this.cerrar();
                            } else {
                                toastr.error(data.message || 'Error al reenviar credenciales');
                                this.cerrar();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error('Error al reenviar credenciales: ' + error.message);
                            this.cerrar();
                        })
                        .finally(() => {
                            this.cargando = false;
                        });
                }
            }
        }

        // Funciones globales
        function abrirModalCrearUsuario(clienteId) {
            console.log('abrirModalCrearUsuario llamado con clienteId:', clienteId);
            const event = new CustomEvent('abrir-modal-crear', {
                detail: {
                    clienteId: clienteId
                }
            });
            document.dispatchEvent(event);
        }

        function editarUsuario(usuarioId) {
            console.log('editarUsuario llamado con usuarioId:', usuarioId);
            const event = new CustomEvent('abrir-modal-editar', {
                detail: {
                    usuarioId: usuarioId
                }
            });
            document.dispatchEvent(event);
        }

        function eliminarUsuario(usuarioId, userName) {
            console.log('eliminarUsuario llamado con usuarioId:', usuarioId, 'userName:', userName);
            const event = new CustomEvent('abrir-modal-eliminar', {
                detail: {
                    usuarioId: usuarioId,
                    userName: userName
                }
            });
            document.dispatchEvent(event);
        }

        // NUEVA FUNCIÓN PARA ABRIR MODAL DE REENVIAR (SIN TOASTR)
        function abrirModalReenviar(usuarioId) {
            console.log('abrirModalReenviar llamado con usuarioId:', usuarioId);
            const event = new CustomEvent('abrir-modal-reenviar', {
                detail: {
                    usuarioId: usuarioId
                }
            });
            document.dispatchEvent(event);
        }
    </script>

</x-layout.default>