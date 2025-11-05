<x-layout.default title="Permisos - ERP Solutions Force">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse mb-4">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Permisos</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Gestión de Permisos</span>
            </li>
        </ul>
    </div>
    <div x-data="sistemaPermisos()" x-init="init()" class="min-h-screen bg-gray-50">
        <!-- Header Principal Mejorado -->
        <div class="bg-white shadow-lg border-b border-gray-200">
            <div class="container mx-auto px-6 py-5">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <!-- Título -->
                    <div class="flex items-center space-x-4">
                        <div
                            class="bg-primary p-3 rounded-xl shadow-lg transform hover:scale-105 transition-transform duration-200">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h1
                                class="text-2xl font-bold text-gray-900 bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                Sistema de Permisos
                            </h1>
                            <p class="text-sm text-gray-600 mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Gestión centralizada de accesos y privilegios del sistema
                            </p>
                        </div>
                    </div>

                    <!-- Estadísticas centradas -->
                    <div class="flex justify-center flex-wrap gap-10 mt-6 lg:mt-0 lg:gap-16 w-full lg:w-auto">
                        <div
                            class="text-center group cursor-pointer transform hover:scale-105 transition-transform duration-200">
                            <div class="text-2xl font-bold text-blue-600 bg-blue-50 rounded-full w-14 h-14 flex items-center justify-center mx-auto group-hover:bg-blue-100 transition-colors"
                                x-text="permisos.length">
                            </div>
                            <div class="text-sm text-gray-500 mt-2 font-medium tracking-wide">Permisos</div>
                        </div>

                        <div
                            class="text-center group cursor-pointer transform hover:scale-105 transition-transform duration-200">
                            <div class="text-2xl font-bold text-green-600 bg-green-50 rounded-full w-14 h-14 flex items-center justify-center mx-auto group-hover:bg-green-100 transition-colors"
                                x-text="combinaciones.length">
                            </div>
                            <div class="text-sm text-gray-500 mt-2 font-medium tracking-wide">Combinaciones</div>
                        </div>

                        <div
                            class="text-center group cursor-pointer transform hover:scale-105 transition-transform duration-200">
                            <div class="text-2xl font-bold text-purple-600 bg-purple-50 rounded-full w-14 h-14 flex items-center justify-center mx-auto group-hover:bg-purple-100 transition-colors"
                                x-text="roles.length">
                            </div>
                            <div class="text-sm text-gray-500 mt-2 font-medium tracking-wide">Roles</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navegación Mejorada -->
        <div class="bg-white border-b border-gray-200 shadow-sm ">
            <div class="container mx-auto px-6">
                <div class="flex space-x-1 overflow-x-auto">
                    <button @click="activeTab = 'permisos'"
                        :class="activeTab === 'permisos' ?
                            'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border-blue-300 shadow-inner' :
                            'text-gray-600 hover:text-gray-900 hover:bg-gray-50 border-transparent'"
                        class="flex items-center px-6 py-4 border-b-2 font-medium text-sm transition-all duration-200 group relative min-w-0 flex-1 lg:flex-none">
                        <div class="flex items-center space-x-3">
                            <div :class="activeTab === 'permisos' ?
                                'bg-blue-600 text-white' :
                                'bg-gray-200 text-gray-600 group-hover:bg-gray-300'"
                                class="p-2 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                            </div>
                            <span class="whitespace-nowrap">Gestión de Permisos</span>
                        </div>
                        <div x-show="activeTab === 'permisos'"
                            class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600 rounded-t"></div>
                    </button>

                    <button @click="activeTab = 'combinaciones'"
                        :class="activeTab === 'combinaciones' ?
                            'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border-blue-300 shadow-inner' :
                            'text-gray-600 hover:text-gray-900 hover:bg-gray-50 border-transparent'"
                        class="flex items-center px-6 py-4 border-b-2 font-medium text-sm transition-all duration-200 group relative min-w-0 flex-1 lg:flex-none">
                        <div class="flex items-center space-x-3">
                            <div :class="activeTab === 'combinaciones' ?
                                'bg-blue-600 text-white' :
                                'bg-gray-200 text-gray-600 group-hover:bg-gray-300'"
                                class="p-2 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <span class="whitespace-nowrap">Combinaciones</span>
                        </div>
                        <div x-show="activeTab === 'combinaciones'"
                            class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600 rounded-t"></div>
                    </button>

                    <button @click="activeTab = 'asignar'"
                        :class="activeTab === 'asignar' ?
                            'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border-blue-300 shadow-inner' :
                            'text-gray-600 hover:text-gray-900 hover:bg-gray-50 border-transparent'"
                        class="flex items-center px-6 py-4 border-b-2 font-medium text-sm transition-all duration-200 group relative min-w-0 flex-1 lg:flex-none">
                        <div class="flex items-center space-x-3">
                            <div :class="activeTab === 'asignar' ?
                                'bg-blue-600 text-white' :
                                'bg-gray-200 text-gray-600 group-hover:bg-gray-300'"
                                class="p-2 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                    </path>
                                </svg>
                            </div>
                            <span class="whitespace-nowrap">Asignar Permisos</span>
                        </div>
                        <div x-show="activeTab === 'asignar'"
                            class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600 rounded-t"></div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="container mx-auto px-6 py-8">
            <!-- Alertas mejoradas -->
            <div x-show="alert.show" x-transition x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                class="mb-8 rounded-xl border-l-4 shadow-lg transform transition-all duration-300 hover:shadow-xl"
                :class="{
                    'bg-gradient-to-r from-green-50 to-green-100 border-green-400 text-green-800': alert
                        .type === 'success',
                    'bg-gradient-to-r from-red-50 to-red-100 border-red-400 text-red-800': alert.type === 'error',
                    'bg-gradient-to-r from-blue-50 to-blue-100 border-blue-400 text-blue-800': alert
                        .type === 'info'
                }">
                <div class="p-4 flex items-center">
                    <div class="flex-shrink-0">
                        <svg x-show="alert.type === 'success'" class="w-6 h-6" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <svg x-show="alert.type === 'error'" class="w-6 h-6" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <svg x-show="alert.type === 'info'" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-semibold" x-text="alert.message"></p>
                    </div>
                    <button @click="alert.show = false"
                        class="ml-auto text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-full hover:bg-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Tab: Gestión de Permisos -->
            <div x-show="activeTab === 'permisos'" x-transition class="space-y-6">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <!-- Header Mejorado -->
                    <div :class="editingPermiso ? 'bg-warning' : 'bg-primary'"
                        class="px-8 py-6 border-b border-blue-100 transition-colors duration-300">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <h2 class="text-xl font-bold text-white"
                                        x-text="editingPermiso ? 'Editar Permiso' : 'Crear Nuevo Permiso'"></h2>
                                    <p class="text-sm text-white mt-1"
                                        x-text="editingPermiso ? 'Modifique los datos del permiso existente' : 'Complete los campos para crear un nuevo permiso'">
                                    </p>
                                </div>
                            </div>
                            <div x-show="editingPermiso" class="flex items-center space-x-2">
                                <span
                                    class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full border border-yellow-200">
                                    Modo Edición
                                </span>
                            </div>
                        </div>
                    </div>


                    <!-- Formulario Mejorado -->
                    <div class="p-8">
                        <form @submit.prevent="editingPermiso ? updatePermiso() : createPermiso()" class="space-y-8">
                            <!-- Campos del Formulario -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <!-- Campo Nombre -->
                                <div class="space-y-3">
                                    <label
                                        class="block text-sm font-semibold text-gray-700 flex items-center space-x-1">
                                        <span>Nombre del Permiso</span>
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <input type="text" x-model="permisoForm.nombre" required
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:border-gray-400 placeholder-gray-400"
                                            placeholder="Ver Dashboard">
                                    </div>

                                    <p class="text-xs text-gray-500">Nombre único identificador del permiso</p>
                                </div>


                                <!-- Campo Módulo -->
                                <div class="space-y-3">
                                    <label
                                        class="block text-sm font-semibold text-gray-700 flex items-center space-x-1">
                                        <span>Módulo</span>
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                        <input type="text" x-model="permisoForm.modulo" required
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:border-gray-400"
                                            placeholder="Dashboard">
                                    </div>
                                    <p class="text-xs text-gray-500">Módulo o área del sistema</p>
                                </div>

                                <!-- Campo Descripción -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-gray-700">Descripción</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <input type="text" x-model="permisoForm.descripcion"
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:border-gray-400"
                                            placeholder="Descripción opcional">
                                    </div>
                                    <p class="text-xs text-gray-500">Breve descripción de la funcionalidad</p>
                                </div>

                            </div>

                            <!-- Botones Mejorados - Posición y Diseño -->
                            <div class="flex justify-between items-center pt-8 border-t border-gray-200">
                                <!-- Información de Estado -->
                                <div x-show="editingPermiso"
                                    class="flex items-center space-x-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Editando permiso existente</span>
                                </div>
                                <div x-show="!editingPermiso"
                                    class="flex items-center space-x-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span>Creando nuevo permiso</span>
                                </div>

                                <!-- Grupo de Botones -->
                                <div class="flex space-x-4">
                                    <!-- Botón Cancelar - Solo visible en modo edición -->
                                    <button type="button" @click="cancelEditPermiso()" x-show="editingPermiso"
                                        class="btn btn-danger flex items-center justify-center px-6 py-3 text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm min-w-[120px] space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <span>Cancelar</span>
                                    </button>


                                    <button type="submit"
                                        class="px-8 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 focus:ring-2 focus:ring-blue-500 transition-all duration-200 shadow-lg flex items-center space-x-2 min-w-[140px] justify-center group">
                                        <svg class="w-4 h-4 transition-transform duration-200 group-hover:scale-110"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span x-text="editingPermiso ? 'Actualizar' : 'Crear Permiso'"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Lista de Permisos - Vista de Cards -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <!-- Header Mejorado -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-8 py-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="bg-blue-600 p-3 rounded-xl shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Permisos del Sistema</h2>
                                    <p class="text-sm text-gray-600 mt-1"
                                        x-text="`${permisos.length} permisos configurados`"></p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div
                                    class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full border border-gray-200">
                                    <span class="font-semibold text-blue-600" x-text="permisos.length"></span>
                                    elementos
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenedor de Cards con Scroll -->
                    <div class="p-8">
                        <!-- Grid de 4 columnas con scroll vertical -->
                        <div x-show="permisos.length > 0"
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 max-h-[600px] overflow-y-auto pr-4 custom-scrollbar">

                            <template x-for="permiso in permisos" :key="permiso.idPermiso">
                                <!-- Card de Permiso -->
                                <div
                                    class="bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 hover:border-blue-200 group relative">

                                    <!-- Indicador de Estado -->
                                    <div class="absolute top-4 right-4">
                                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                    </div>

                                    <!-- Header de la Card -->
                                    <div class="flex items-start space-x-3 mb-4">
                                        <div class="bg-blue-100 p-2 rounded-lg flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-bold text-gray-900 text-lg truncate group-hover:text-blue-600 transition-colors"
                                                x-text="permiso.nombre" :title="permiso.nombre"></h3>
                                        </div>
                                    </div>

                                    <!-- Módulo -->
                                    <div class="mb-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200"
                                            x-text="permiso.modulo"></span>
                                    </div>

                                    <!-- Descripción -->
                                    <div class="mb-6">
                                        <p class="text-sm text-gray-600 leading-relaxed line-clamp-3"
                                            x-text="permiso.descripcion || 'Sin descripción disponible'"
                                            :class="{ 'text-gray-400 italic': !permiso.descripcion }"></p>
                                    </div>

                                    <!-- Información Adicional -->
                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                        <span class="text-green-600 font-semibold flex items-center space-x-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span>Activo</span>
                                        </span>
                                    </div>

                                    <!-- Acciones -->
                                    <div class="flex space-x-2 pt-4 border-t border-gray-100">
                                        <button @click="editPermiso(permiso)"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 hover:text-blue-800 border border-blue-200 transition-all duration-200 group/btn"
                                            title="Editar permiso">
                                            <svg class="w-4 h-4 mr-1 transition-transform group-hover/btn:scale-110"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            Editar
                                        </button>

                                        <button @click="deletePermiso(permiso.idPermiso)"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 hover:text-red-800 border border-red-200 transition-all duration-200 group/btn"
                                            title="Eliminar permiso">
                                            <svg class="w-4 h-4 mr-1 transition-transform group-hover/btn:scale-110"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Estado Vacío -->
                        <div x-show="permisos.length === 0" class="text-center py-16">
                            <div class="bg-gray-50 rounded-2xl p-12 max-w-md mx-auto border border-gray-200">
                                <div
                                    class="bg-blue-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">No hay permisos creados</h3>
                                <p class="text-gray-600 mb-6">Comience creando el primer permiso del sistema</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Combinaciones -->
            <div x-show="activeTab === 'combinaciones'" x-transition class="space-y-6">
                <!-- Formulario Crear Combinación mejorado -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <!-- Header Mejorado -->
                    <div class="bg-primary px-8 py-6 border-b border-blue-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <h2 class="text-xl font-bold text-white">Crear Nueva Combinación</h2>
                                    <p class="text-sm text-white mt-1">Defina una nueva combinación de rol, tipo de
                                        usuario y área</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span
                                    class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full border border-green-200">
                                    Nueva Combinación
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario Mejorado -->
                    <div class="p-8">
                        <form @submit.prevent="createCombinacion()" class="space-y-8">
                            <!-- Campos del Formulario -->
                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                                <!-- Campo Rol -->
                                <div class="space-y-3">
                                    <label
                                        class="block text-sm font-semibold text-gray-700 flex items-center space-x-1">
                                        <span>Rol</span>
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <select x-model="combinacionForm.idRol" required
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:border-gray-400 appearance-none cursor-pointer">
                                            <option value="">Seleccionar Rol</option>
                                            <template x-for="rol in roles" :key="rol.idRol">
                                                <option :value="rol.idRol" x-text="rol.nombre"></option>
                                            </template>
                                        </select>
                                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Seleccione el rol del sistema</p>
                                </div>

                                <!-- Campo Tipo Usuario -->
                                <div class="space-y-3">
                                    <label
                                        class="block text-sm font-semibold text-gray-700 flex items-center space-x-1">
                                        <span>Tipo Usuario</span>
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                            </svg>
                                        </div>
                                        <select x-model="combinacionForm.idTipoUsuario" required
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:border-gray-400 appearance-none cursor-pointer">
                                            <option value="">Seleccionar Tipo</option>
                                            <template x-for="tipo in tiposUsuario" :key="tipo.idTipoUsuario">
                                                <option :value="tipo.idTipoUsuario" x-text="tipo.nombre"></option>
                                            </template>
                                        </select>
                                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Tipo de usuario del sistema</p>
                                </div>

                                <!-- Campo Tipo Área -->
                                <div class="space-y-3">
                                    <label
                                        class="block text-sm font-semibold text-gray-700 flex items-center space-x-1">
                                        <span>Tipo Área</span>
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
                                            </svg>
                                        </div>
                                        <select x-model="combinacionForm.idTipoArea" required
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:border-gray-400 appearance-none cursor-pointer">
                                            <option value="">Seleccionar Área</option>
                                            <template x-for="area in tiposArea" :key="area.idTipoArea">
                                                <option :value="area.idTipoArea" x-text="area.nombre"></option>
                                            </template>
                                        </select>
                                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Área o departamento del sistema</p>
                                </div>

                                <!-- Campo Nombre Personalizado -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-gray-700">Nombre
                                        Personalizado</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </div>
                                        <input type="text" x-model="combinacionForm.nombre_combinacion"
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:border-gray-400 placeholder-gray-400"
                                            placeholder="Nombre personalizado (opcional)">
                                    </div>
                                    <p class="text-xs text-gray-500">Nombre personalizado para la combinación</p>
                                </div>
                            </div>

                            <!-- Botones Mejorados -->
                            <div class="flex justify-between items-center pt-8 border-t border-gray-200">
                                <!-- Información de Estado -->
                                <div class="flex items-center space-x-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>Creando nueva combinación de permisos</span>
                                </div>

                                <!-- Grupo de Botones -->
                                <div class="flex space-x-4">
                                    <button type="submit"
                                        class="px-8 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 focus:ring-2 focus:ring-blue-500 transition-all duration-200 shadow-lg flex items-center space-x-2 min-w-[160px] justify-center group">
                                        <svg class="w-4 h-4 transition-transform duration-200 group-hover:scale-110"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        <span>Crear Combinación</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Lista de Combinaciones - Vista de Lista Mejorada -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <!-- Header Mejorado -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-8 py-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="bg-blue-600 p-3 rounded-xl shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Combinaciones Existentes</h2>
                                    <p class="text-sm text-gray-600 mt-1"
                                        x-text="`${combinaciones.length} combinaciones configuradas`"></p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div
                                    class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full border border-gray-200">
                                    <span class="font-semibold text-blue-600" x-text="combinaciones.length"></span>
                                    elementos
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenedor de Lista Mejorada -->
                    <div class="p-8">
                        <!-- Lista Vertical Mejorada -->
                        <div x-show="combinaciones.length > 0"
                            class="space-y-4 max-h-[600px] overflow-y-auto pr-4 custom-scrollbar">

                            <template x-for="combinacion in combinaciones" :key="combinacion.idCombinacion">
                                <!-- Item de Lista Mejorado -->
                                <div
                                    class="bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 hover:border-blue-200 group">
                                    <div class="flex items-center justify-between">
                                        <!-- Información Principal -->
                                        <div class="flex items-start space-x-4 flex-1 min-w-0">
                                            <!-- Icono -->
                                            <div class="bg-blue-100 p-3 rounded-xl flex-shrink-0">
                                                <svg class="w-5 h-5 text-blue-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>

                                            <!-- Contenido Mejorado -->
                                            <div class="flex-1 min-w-0">
                                                <!-- Header con Badge Integrado -->
                                                <div class="flex items-start justify-between mb-4">
                                                    <div class="flex-1 min-w-0">
                                                        <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors truncate pr-4"
                                                            x-text="combinacion.nombre_completo"
                                                            :title="combinacion.nombre_completo"></h3>

                                                        <!-- Badge de Permisos Integrado en el Header -->
                                                        <div class="flex items-center space-x-3">
                                                            <span
                                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-blue-50 text-blue-700 border border-blue-200 shadow-sm"
                                                                x-text="`${combinacion.permisos_count} permisos asignados`"></span>

                                                            <!-- Estado de Actividad -->
                                                            <span
                                                                class="inline-flex items-center text-xs text-green-600 font-medium">
                                                                <span
                                                                    class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5 animate-pulse"></span>
                                                                Activa
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Grid de Información Mejorado -->
                                                <div
                                                    class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                                    <!-- Rol -->
                                                    <div
                                                        class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                                        <div class="bg-blue-100 p-2 rounded-lg flex-shrink-0">
                                                            <svg class="w-4 h-4 text-blue-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <div
                                                                class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">
                                                                Rol</div>
                                                            <div class="text-sm font-semibold text-gray-900 truncate"
                                                                x-text="combinacion.rol"></div>
                                                        </div>
                                                    </div>

                                                    <!-- Tipo Usuario -->
                                                    <div
                                                        class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                                        <div class="bg-green-100 p-2 rounded-lg flex-shrink-0">
                                                            <svg class="w-4 h-4 text-green-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                                            </svg>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <div
                                                                class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">
                                                                Tipo Usuario</div>
                                                            <div class="text-sm font-semibold text-gray-900 truncate"
                                                                x-text="combinacion.tipo_usuario"></div>
                                                        </div>
                                                    </div>

                                                    <!-- Tipo Área -->
                                                    <div
                                                        class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                                        <div class="bg-purple-100 p-2 rounded-lg flex-shrink-0">
                                                            <svg class="w-4 h-4 text-purple-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
                                                            </svg>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <div
                                                                class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">
                                                                Tipo Área</div>
                                                            <div class="text-sm font-semibold text-gray-900 truncate"
                                                                x-text="combinacion.tipo_area"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Información Adicional Compacta -->
                                                <div
                                                    class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                        <div class="flex items-center space-x-1">
                                                            <svg class="w-3 h-3 text-gray-400" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <span>Actualizado: <span
                                                                    class="font-medium text-gray-700">Hoy</span></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Acciones -->
                                        <div class="flex items-center space-x-3 ml-6 flex-shrink-0">
                                            <button @click="selectCombinacion(combinacion)"
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-xl hover:bg-blue-100 hover:text-blue-800 border border-blue-200 transition-all duration-200 group/btn shadow-sm"
                                                title="Asignar permisos">
                                                <svg class="w-4 h-4 mr-2 transition-transform group-hover/btn:scale-110"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                    </path>
                                                </svg>
                                                Gestionar Permisos
                                            </button>

                                            <button @click="deleteCombinacion(combinacion.idCombinacion)"
                                                class="inline-flex items-center p-2 text-red-600 bg-red-50 rounded-xl hover:bg-red-100 hover:text-red-800 border border-red-200 transition-all duration-200 group/btn shadow-sm"
                                                title="Eliminar combinación">
                                                <svg class="w-4 h-4 transition-transform group-hover/btn:scale-110"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Estado Vacío -->
                        <div x-show="combinaciones.length === 0" class="text-center py-16">
                            <div class="bg-gray-50 rounded-2xl p-12 max-w-md mx-auto border border-gray-200">
                                <div
                                    class="bg-blue-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">No hay combinaciones creadas</h3>
                                <p class="text-gray-600 mb-6">Comience creando la primera combinación del sistema</p>
                                <button onclick="document.querySelector('form').scrollIntoView({ behavior: 'smooth' })"
                                    class="px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-colors shadow-lg">
                                    Crear Primera Combinación
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Asignar Permisos -->
            <div x-show="activeTab === 'asignar'" x-transition class="space-y-6">
                <!-- Seleccionar Combinación mejorada -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden"
                    x-show="!selectedCombinacion">
                    <!-- Header Mejorado -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-8 py-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="bg-blue-600 p-3 rounded-xl shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Seleccionar Combinación</h2>
                                    <p class="text-sm text-gray-600 mt-1">Elija una combinación para gestionar sus
                                        permisos</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div
                                    class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full border border-gray-200">
                                    <span class="font-semibold text-blue-600" x-text="combinaciones.length"></span>
                                    disponibles
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grid de Combinaciones Mejorado -->
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            <template x-for="combinacion in combinaciones" :key="combinacion.idCombinacion">
                                <!-- Tarjeta de Combinación Mejorada -->
                                <button @click="selectCombinacion(combinacion)"
                                    class="bg-gradient-to-br from-white to-gray-50 border-2 border-gray-200 rounded-2xl p-6 text-left hover:border-blue-300 hover:shadow-xl transition-all duration-300 group relative overflow-hidden">

                                    <!-- Efecto de borde superior sutil -->
                                    <div
                                        class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>

                                    <!-- Header de la Tarjeta -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-blue-600 transition-colors truncate"
                                                x-text="combinacion.nombre_completo"
                                                :title="combinacion.nombre_completo"></h3>

                                            <!-- Badge de Permisos -->
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-blue-50 text-blue-700 border border-blue-200 shadow-sm"
                                                x-text="`${combinacion.permisos_count} permisos asignados`"></span>
                                        </div>

                                        <!-- Icono de Flecha -->
                                        <div
                                            class="transform group-hover:translate-x-1 transition-transform duration-300 ml-4 flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Información de la Combinación -->
                                    <div class="space-y-3 mb-4">
                                        <!-- Rol -->
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-500 font-medium">Rol:</span>
                                            <span class="text-gray-900 font-semibold truncate ml-2"
                                                x-text="combinacion.rol"></span>
                                        </div>

                                        <!-- Tipo Usuario -->
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-500 font-medium">Tipo Usuario:</span>
                                            <span class="text-gray-900 font-semibold truncate ml-2"
                                                x-text="combinacion.tipo_usuario"></span>
                                        </div>

                                        <!-- Tipo Área -->
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-500 font-medium">Tipo Área:</span>
                                            <span class="text-gray-900 font-semibold truncate ml-2"
                                                x-text="combinacion.tipo_area"></span>
                                        </div>
                                    </div>

                                    <!-- Estado y Acción -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                            <span class="font-medium text-green-600">Activa</span>
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            Haga clic para gestionar
                                        </div>
                                    </div>

                                    <!-- Efecto de Hover -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-blue-500 to-blue-600 opacity-0 group-hover:opacity-5 transition-opacity duration-300 rounded-2xl">
                                    </div>
                                </button>
                            </template>
                        </div>

                        <!-- Estado Vacío Mejorado -->
                        <div x-show="combinaciones.length === 0" class="text-center py-16">
                            <div class="bg-gray-50 rounded-2xl p-12 max-w-md mx-auto border border-gray-200">
                                <div
                                    class="bg-blue-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">No hay combinaciones disponibles
                                </h3>
                                <p class="text-gray-600 mb-6">Cree una combinación primero para gestionar permisos</p>
                                <button @click="activeTab = 'combinaciones'"
                                    class="px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-colors shadow-lg">
                                    Crear Combinación
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- === Asignar Permisos a Combinación === -->
                <div x-show="selectedCombinacion"
                    class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden flex flex-col">

                    <!-- Header Mejorado -->
                    <div class="bg-primary px-8 py-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <button @click="closeCombinacion()"
                                    class="p-2 text-white hover:bg-white hover:bg-opacity-20 rounded-xl transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                </button>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Gestionar Permisos</h2>
                                    <p class="text-blue-100 text-sm mt-1" x-text="selectedCombinacionNombre"></p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-blue-100 text-sm">
                                    <span class="font-semibold" x-text="selectedPermisos.length"></span> de
                                    <span x-text="permisos.length"></span> permisos seleccionados
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- === CONTENIDO PRINCIPAL === -->
                    <main class="flex-1 p-8 space-y-8 overflow-y-auto">

                        <!-- === BARRA DE HERRAMIENTAS === -->
                        <section
                            class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 lg:gap-6 border-b border-gray-100 pb-6">
                            <!-- Buscador -->
                            <div class="flex-1 max-w-md">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" x-model="searchTerm" placeholder="Buscar permisos..."
                                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm hover:border-gray-400 transition-all duration-200" />
                                </div>
                            </div>
                            <!-- Filtros y Acciones -->
                            <div class="flex flex-wrap items-center gap-6 mt-2">
                                <select x-model="filterModule"
                                    class="min-w-[300px] px-6 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm hover:shadow-md font-medium transition-all duration-200">
                                    <option value="">Todos los módulos</option>
                                    <template x-for="module in [...new Set(permisos.map(p => p.modulo))]"
                                        :key="module">
                                        <option x-text="module"></option>
                                    </template>
                                </select>


                                <button @click="selectAllPermisos()"
                                    class="px-7 py-3.5 text-base font-semibold text-blue-700 bg-blue-50 rounded-xl hover:bg-blue-100 border-2 border-blue-200 transition-all duration-300 hover:scale-105 shadow-sm flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Seleccionar Todos</span>
                                </button>
                            </div>

                        </section>

                        <!-- === INDICADOR DE FILTROS === -->
                        <section x-show="searchTerm || filterModule"
                            class="p-4 bg-blue-50 rounded-xl border border-blue-200 flex items-center justify-between">
                            <div class="text-blue-700 font-medium">
                                <span x-text="filteredPermisos.length"></span> permisos encontrados
                                <span x-show="searchTerm">para "<span x-text="searchTerm"></span>"</span>
                                <span x-show="filterModule">en <span x-text="filterModule"></span></span>
                            </div>
                            <button @click="searchTerm = ''; filterModule = ''"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span>Limpiar filtros</span>
                            </button>
                        </section>

                        <!-- === GRID DE PERMISOS === -->
                        <section>
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                <template x-for="permiso in filteredPermisos" :key="permiso.idPermiso">
                                    <label
                                        :class="selectedPermisos.includes(permiso.idPermiso) ?
                                            'border-blue-400 bg-gradient-to-br from-blue-50 to-indigo-50 shadow-lg transform scale-[1.02]' :
                                            'border-gray-200 hover:border-blue-300 hover:bg-gray-50 hover:shadow-md'"
                                        class="border-2 rounded-2xl p-6 cursor-pointer transition-all duration-300 group relative overflow-hidden">

                                        <div class="flex items-start space-x-4">
                                            <!-- Checkbox -->
                                            <input type="checkbox" :value="permiso.idPermiso"
                                                x-model="selectedPermisos"
                                                class="w-6 h-6 text-blue-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">

                                            <!-- Información -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between mb-4">
                                                    <h3 class="font-bold text-gray-900 text-lg leading-tight group-hover:text-blue-700 transition-colors"
                                                        x-text="permiso.nombre"></h3>
                                                    <span
                                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-sm"
                                                        x-text="permiso.modulo"></span>
                                                </div>
                                                <p class="text-sm text-gray-600 mb-4 leading-relaxed"
                                                    x-text="permiso.descripcion || 'Sin descripción disponible'"
                                                    :class="{ 'text-gray-400 italic': !permiso.descripcion }"></p>

                                                <div class="flex items-center justify-between text-xs font-medium">
                                                    <span x-show="selectedPermisos.includes(permiso.idPermiso)"
                                                        class="text-green-600 font-semibold flex items-center bg-green-50 px-3 py-1.5 rounded-lg border border-green-200">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        Activado
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </template>
                            </div>

                            <!-- Vacío -->
                            <div x-show="filteredPermisos.length === 0" class="text-center py-12">
                                <div class="w-24 h-24 mx-auto mb-4 text-gray-300">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-500 mb-2">No se encontraron permisos</h3>
                                <p class="text-gray-400">Intenta ajustar los filtros de búsqueda</p>
                            </div>
                        </section>
                    </main>

                    <!-- === PIE DE ACCIONES === -->
                    <footer
                        class="bg-gray-50 border-t border-gray-200 px-8 py-6 flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

                        <!-- Contador de permisos -->
                        <div class="text-center md:text-left text-gray-700">
                            <span class="font-bold text-blue-600 text-lg" x-text="selectedPermisos.length"></span>
                            <span> permisos seleccionados de </span>
                            <span class="font-semibold" x-text="permisos.length"></span>
                        </div>

                        <!-- Botones -->
                        <div class="flex flex-col sm:flex-row justify-center md:justify-end gap-4 w-full md:w-auto">
                            <!-- Botón Cancelar -->
                            <button @click="closeCombinacion()"
                                class="btn btn-danger w-full sm:w-auto px-8 py-3 text-base font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span>Cancelar</span>
                            </button>


                            <button @click="guardarPermisos()"
                                class="w-full sm:w-auto px-8 py-3 text-base font-semibold text-white bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl hover:from-green-600 hover:to-emerald-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Guardar Permisos</span>
                            </button>
                        </div>
                    </footer>

                </div>

            </div>
        </div>
    </div>

    <script>
        function sistemaPermisos() {
            return {
                activeTab: 'permisos',
                permisos: [],
                combinaciones: [],
                roles: [],
                tiposUsuario: [],
                tiposArea: [],
                selectedCombinacion: null,
                selectedPermisos: [],
                editingPermiso: null,
                searchTerm: '',
                filterModule: '',

                permisoForm: {
                    nombre: '',
                    modulo: '',
                    descripcion: ''
                },

                combinacionForm: {
                    idRol: '',
                    idTipoUsuario: '',
                    idTipoArea: '',
                    nombre_combinacion: ''
                },

                alert: {
                    show: false,
                    type: 'success',
                    message: ''
                },

                // Propiedad computada para permisos filtrados
                get filteredPermisos() {
                    let filtered = this.permisos;

                    if (this.searchTerm) {
                        const term = this.searchTerm.toLowerCase();
                        filtered = filtered.filter(permiso =>
                            permiso.nombre.toLowerCase().includes(term) ||
                            permiso.descripcion?.toLowerCase().includes(term) ||
                            permiso.modulo.toLowerCase().includes(term)
                        );
                    }

                    if (this.filterModule) {
                        filtered = filtered.filter(permiso => permiso.modulo === this.filterModule);
                    }

                    return filtered;
                },

                // Propiedad computada segura para el nombre de la combinación
                get selectedCombinacionNombre() {
                    return this.selectedCombinacion ? this.selectedCombinacion.nombre_completo : '';
                },

                async init() {
                    await this.loadData();
                },

                async loadData() {
                    try {
                        const response = await fetch('/permisos/data');
                        const data = await response.json();

                        this.permisos = data.permisos;
                        this.combinaciones = data.combinaciones;
                        this.roles = data.roles;
                        this.tiposUsuario = data.tiposUsuario;
                        this.tiposArea = data.tiposArea;
                    } catch (error) {
                        this.showAlert('Error al cargar datos', 'error');
                    }
                },

                async createPermiso() {
                    try {
                        const response = await fetch('/permisos/permisos', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.permisoForm)
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.permisos.push(result.permiso);
                            this.permisoForm = {
                                nombre: '',
                                modulo: '',
                                descripcion: ''
                            };
                            this.showAlert('Permiso creado exitosamente', 'success');
                        } else {
                            throw new Error(result.error);
                        }
                    } catch (error) {
                        this.showAlert('Error al crear permiso: ' + error.message, 'error');
                    }
                },

                editPermiso(permiso) {
                    this.editingPermiso = permiso;
                    this.permisoForm = {
                        ...permiso
                    };
                },

                async updatePermiso() {
                    try {
                        const response = await fetch(`/permisos/permisos/${this.editingPermiso.idPermiso}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.permisoForm)
                        });

                        const result = await response.json();

                        if (result.success) {
                            const index = this.permisos.findIndex(p => p.idPermiso === this.editingPermiso.idPermiso);
                            this.permisos[index] = result.permiso;
                            this.cancelEditPermiso();
                            this.showAlert('Permiso actualizado exitosamente', 'success');
                        } else {
                            throw new Error(result.error);
                        }
                    } catch (error) {
                        this.showAlert('Error al actualizar permiso: ' + error.message, 'error');
                    }
                },

                cancelEditPermiso() {
                    this.editingPermiso = null;
                    this.permisoForm = {
                        nombre: '',
                        modulo: '',
                        descripcion: ''
                    };
                },

                async deletePermiso(id) {
                    if (!confirm('¿Está seguro de eliminar este permiso?')) return;

                    try {
                        const response = await fetch(`/permisos/permisos/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.permisos = this.permisos.filter(p => p.idPermiso !== id);
                            this.showAlert('Permiso eliminado exitosamente', 'success');
                        } else {
                            throw new Error(result.error);
                        }
                    } catch (error) {
                        this.showAlert('Error al eliminar permiso: ' + error.message, 'error');
                    }
                },

                async createCombinacion() {
                    try {
                        if (!this.combinacionForm.idRol || !this.combinacionForm.idTipoUsuario || !this.combinacionForm
                            .idTipoArea) {
                            this.showAlert('Debe seleccionar Rol, Tipo de Usuario y Tipo de Área', 'error');
                            return;
                        }

                        const response = await fetch('/permisos/combinaciones', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.combinacionForm)
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.combinaciones.push(result.combinacion);
                            this.combinacionForm = {
                                idRol: '',
                                idTipoUsuario: '',
                                idTipoArea: '',
                                nombre_combinacion: ''
                            };
                            this.showAlert('Combinación creada exitosamente', 'success');
                        } else {
                            throw new Error(result.error);
                        }
                    } catch (error) {
                        this.showAlert('Error al crear combinación: ' + error.message, 'error');
                    }
                },

                async deleteCombinacion(id) {
                    if (!confirm('¿Está seguro de eliminar esta combinación?')) return;

                    try {
                        const response = await fetch(`/permisos/combinaciones/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.combinaciones = this.combinaciones.filter(c => c.idCombinacion !== id);
                            this.showAlert('Combinación eliminada exitosamente', 'success');
                        } else {
                            throw new Error(result.error);
                        }
                    } catch (error) {
                        this.showAlert('Error al eliminar combinación: ' + error.message, 'error');
                    }
                },

                async selectCombinacion(combinacion) {
                    this.selectedCombinacion = combinacion;
                    this.activeTab = 'asignar';

                    try {
                        const response = await fetch(`/permisos/combinaciones/${combinacion.idCombinacion}/permisos`);
                        const result = await response.json();

                        if (result.success) {
                            this.selectedPermisos = result.permisos;
                        } else {
                            throw new Error(result.error);
                        }
                    } catch (error) {
                        this.showAlert('Error al cargar permisos de la combinación', 'error');
                    }
                },

                // Método seguro para cerrar la combinación seleccionada
                closeCombinacion() {
                    this.selectedCombinacion = null;
                    this.selectedPermisos = [];
                    this.activeTab = 'combinaciones';
                },

                async guardarPermisos() {
                    try {
                        // Validar que hay una combinación seleccionada
                        if (!this.selectedCombinacion) {
                            this.showAlert('No hay ninguna combinación seleccionada', 'error');
                            return;
                        }

                        const response = await fetch(
                            `/permisos/combinaciones/${this.selectedCombinacion.idCombinacion}/permisos`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    permisos: this.selectedPermisos
                                })
                            });

                        const result = await response.json();

                        if (result.success) {
                            const index = this.combinaciones.findIndex(c => c.idCombinacion === this.selectedCombinacion
                                .idCombinacion);
                            this.combinaciones[index].permisos_count = this.selectedPermisos.length;
                            this.combinaciones[index].permisos = this.selectedPermisos;

                            this.showAlert('Permisos guardados exitosamente', 'success');
                        } else {
                            throw new Error(result.error);
                        }
                    } catch (error) {
                        this.showAlert('Error al guardar permisos: ' + error.message, 'error');
                    }
                },

                // Nuevo método para seleccionar/deseleccionar todos los permisos
                selectAllPermisos() {
                    if (this.selectedPermisos.length === this.filteredPermisos.length) {
                        // Deseleccionar todos los permisos filtrados
                        this.selectedPermisos = this.selectedPermisos.filter(id =>
                            !this.filteredPermisos.some(p => p.idPermiso === id)
                        );
                    } else {
                        // Seleccionar todos los permisos filtrados
                        const filteredIds = this.filteredPermisos.map(p => p.idPermiso);
                        const newSelection = [...new Set([...this.selectedPermisos, ...filteredIds])];
                        this.selectedPermisos = newSelection;
                    }
                },

                showAlert(message, type = 'success') {
                    this.alert = {
                        show: true,
                        message,
                        type
                    };
                    setTimeout(() => {
                        this.alert.show = false;
                    }, 5000);
                }
            }
        }
    </script>
</x-layout.default>
