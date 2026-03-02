<x-layout.default>

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('administracion.cliente-general') }}" class="text-primary hover:underline">Clientes
                    Generales</a>
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
            <button type="button" class="btn btn-primary" onclick="window.history.back()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4 mr-2">
                    <path d="M4 12L20 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M8 17L4 12L8 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
                Volver
            </button>
        </div>

        <!-- Tabla de usuarios con 2 ejemplos -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">#
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre Completo</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Apellidos</th>
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
                    <!-- Usuario 1 -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-center">1</td>
                        <td class="px-4 py-3 text-sm text-center">Juan Carlos</td>
                        <td class="px-4 py-3 text-sm text-center">Pérez Rodríguez</td>
                        <td class="px-4 py-3 text-sm text-center">DNI: 12345678</td>
                        <td class="px-4 py-3 text-sm text-center">987654321</td>
                        <td class="px-4 py-3 text-sm text-center">juan.perez@email.com</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="badge bg-primary/10 text-primary">Administrador</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="badge badge-outline-success">Activo</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" class="p-2 hover:bg-yellow-100 rounded-full transition-colors group"
                                    title="Editar" onclick="openEditModal(1)">
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
                                <button type="button" class="p-2 hover:bg-red-100 rounded-full transition-colors group"
                                    title="Eliminar" onclick="openDeleteModal(1, 'Juan Carlos')">
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
                                        <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Usuario 2 -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-center">2</td>
                        <td class="px-4 py-3 text-sm text-center">María Isabel</td>
                        <td class="px-4 py-3 text-sm text-center">García López</td>
                        <td class="px-4 py-3 text-sm text-center">DNI: 87654321</td>
                        <td class="px-4 py-3 text-sm text-center">987654322</td>
                        <td class="px-4 py-3 text-sm text-center">maria.garcia@email.com</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="badge bg-warning/10 text-warning">Supervisor</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="badge badge-outline-success">Activo</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" class="p-2 hover:bg-yellow-100 rounded-full transition-colors group"
                                    title="Editar" onclick="openEditModal(2)">
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
                                <button type="button" class="p-2 hover:bg-red-100 rounded-full transition-colors group"
                                    title="Eliminar" onclick="openDeleteModal(2, 'María Isabel')">
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
                                        <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL DE EDICIÓN -->
    <div x-data="{ open: false, userId: null }" x-init="$watch('open', value => { if(!value) userId = null })"
         @open-edit-modal.window="open = true; userId = $event.detail.userId">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8">
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg" x-text="'Editar Usuario #' + (userId || '')"></h5>
                        <button type="button" class="text-white-dark hover:text-dark" @click="open = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="p-5">
                        <form @submit.prevent="alert('Usuario ' + userId + ' actualizado (simulación)'); open = false">
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Nombre</label>
                                <input type="text" class="form-input w-full" value="Juan Carlos" placeholder="Nombre">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Apellidos</label>
                                <input type="text" class="form-input w-full" value="Pérez Rodríguez" placeholder="Apellidos">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Documento</label>
                                    <input type="text" class="form-input w-full" value="12345678" placeholder="Documento">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Teléfono</label>
                                    <input type="text" class="form-input w-full" value="987654321" placeholder="Teléfono">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Correo</label>
                                <input type="email" class="form-input w-full" value="juan.perez@email.com" placeholder="Correo">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Rol</label>
                                <select class="form-select w-full">
                                    <option>Administrador</option>
                                    <option>Supervisor</option>
                                    <option>Técnico</option>
                                    <option>Usuario</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Estado</label>
                                <select class="form-select w-full">
                                    <option>Activo</option>
                                    <option>Inactivo</option>
                                </select>
                            </div>
                            <div class="flex justify-end items-center mt-6">
                                <button type="button" class="btn btn-outline-danger" @click="open = false">Cancelar</button>
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE ELIMINACIÓN -->
    <div x-data="{ open: false, userId: null, userName: '' }"
         @open-delete-modal.window="open = true; userId = $event.detail.userId; userName = $event.detail.userName">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-md my-8">
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Confirmar Eliminación</h5>
                        <button type="button" class="text-white-dark hover:text-dark" @click="open = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="p-5 text-center">
                        <div class="text-center mb-4">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mx-auto text-red-500">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" />
                                <path d="M12 7V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <circle cx="12" cy="16" r="1" fill="currentColor" />
                            </svg>
                        </div>
                        <p class="text-base font-medium mb-2">¿Estás seguro de eliminar este usuario?</p>
                        <p class="text-sm text-gray-500 mb-4" x-text="'Usuario: ' + userName"></p>
                        <p class="text-xs text-gray-400 mb-6">Esta acción no se puede deshacer.</p>
                        <div class="flex justify-center gap-4">
                            <button type="button" class="btn btn-outline-secondary" @click="open = false">Cancelar</button>
                            <button type="button" class="btn btn-danger" @click="alert('Usuario ' + userId + ' eliminado (simulación)'); open = false">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts para abrir modales -->
    <script>
        function openEditModal(userId) {
            window.dispatchEvent(new CustomEvent('open-edit-modal', { detail: { userId: userId } }));
        }

        function openDeleteModal(userId, userName) {
            window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: { userId: userId, userName: userName } }));
        }
    </script>

</x-layout.default>
