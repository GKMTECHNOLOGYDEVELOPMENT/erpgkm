<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<x-layout.default>
    <!-- Breadcrumb -->
    <div class="mx-auto w-full px-4 py-6">
        <div class="mb-4">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="" class="text-primary hover:underline">Dashboard</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Almacén</span>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Asignación de Artículos</span>
                </li>
            </ul>
        </div>

        <!-- Header principal -->
        <div class="panel mb-6 p-5 rounded-xl shadow-lg border-0 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="w-14 h-14 bg-primary rounded-xl shadow-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor">
                                <path d="M3 7.5L12 3l9 4.5-9 4.5L3 7.5z" fill="currentColor" />
                                <path opacity="0.7" d="M3 7.5V16.5L12 21l9-4.5V7.5L12 12z" fill="currentColor" />
                                <path d="M9.2 13.6l1.6 1.6 3.8-3.8" stroke="#fff" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Asignación de Artículos</h1>
                        <p class="text-gray-600 mt-1">Gestión inteligente de inventario asignado</p>
                    </div>
                </div>
                <a href="{{ route('asignar-articulos.create') }}"
                    class="px-5 py-2.5 bg-primary text-white font-semibold rounded-lg hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary transition-all duration-200 shadow-md hover:shadow-lg flex items-center no-underline">
                    <i class="fas fa-plus mr-2"></i> Nueva Asignación
                </a>
            </div>
        </div>

        <!-- Filtros mejorados -->
        <div class="panel rounded-xl shadow-lg border-0 mb-6">
            <div class="p-5">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Filtrar Asignaciones</h3>
                        <p class="text-gray-500 text-sm mt-1">Encuentra rápidamente lo que necesitas</p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 items-end">
                    <!-- Usuario -->
                    <div class="flex-1 space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-user mr-2 text-primary"></i> Usuario
                        </label>
                        <div class="relative">
                            <select
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all duration-200 appearance-none">
                                <option value="">Todos los usuarios</option>
                                <option>Juan Pérez</option>
                                <option>María García</option>
                                <option>Carlos López</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Artículo -->
                    <div class="flex-1 space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-box mr-2 text-success"></i> Artículo
                        </label>
                        <div class="relative">
                            <select
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-success focus:ring-2 focus:ring-success/30 transition-all duration-200 appearance-none">
                                <option value="">Todos los artículos</option>
                                <option>Laptop HP EliteBook</option>
                                <option>Mouse Logitech</option>
                                <option>Monitor Dell 24"</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="flex-1 space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-tag mr-2 text-warning"></i> Estado
                        </label>
                        <div class="relative">
                            <select
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-warning focus:ring-2 focus:ring-warning/30 transition-all duration-200 appearance-none">
                                <option value="">Todos</option>
                                <option value="activo" class="text-success">● Activo</option>
                                <option value="devuelto" class="text-secondary">● Devuelto</option>
                                <option value="vencido" class="text-danger">● Vencido</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex flex-col sm:flex-row gap-3 flex-1">
                        <!-- Aplicar Filtros -->
                        <div class="flex-1 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-search mr-2 text-info"></i> Buscar
                            </label>
                            <button
                                class="w-full px-4 py-3 bg-primary text-white font-semibold rounded-xl hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center">
                                <i class="fas fa-search mr-2"></i> Aplicar Filtros
                            </button>
                        </div>

                        <!-- Limpiar Filtros -->
                        <div class="flex-1 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 opacity-0 md:opacity-100">
                                <i class="fas fa-redo mr-2 text-danger"></i> Limpiar
                            </label>
                            <button
                                class="w-full px-4 py-3 bg-danger/10 hover:bg-danger/20 text-danger font-medium rounded-lg border border-danger/20 transition-all duration-200 flex items-center justify-center">
                                <i class="fas fa-redo mr-2"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards de Usuarios - TODAS CON EL MISMO COLOR -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Card 2 - Usuario -->
            <div
                class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 overflow-hidden">
                <div class="bg-primary p-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <div
                                    class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center border-2 border-white/30">
                                    <span class="text-white text-xl font-bold">MG</span>
                                </div>
                                <div
                                    class="absolute -bottom-1 -right-1 w-5 h-5 bg-success rounded-full border-2 border-white">
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">María García</h3>
                                <p class="text-blue-100 text-sm">Marketing - Gerente</p>
                            </div>
                        </div>
                        <span
                            class="bg-white/20 text-white text-xs font-semibold px-3 py-1.5 rounded-full backdrop-blur-sm">
                            <i class="fas fa-box mr-1"></i> 2 artículos
                        </span>
                    </div>
                </div>

                <div class="p-5">
                    <div class="grid grid-cols-3 gap-3 mb-4">
                        <div class="bg-primary/10 p-3 rounded-lg text-center">
                            <p class="text-2xl font-bold text-primary">1</p>
                            <p class="text-xs text-primary">Activo</p>
                        </div>
                        <div class="bg-danger/10 p-3 rounded-lg text-center">
                            <p class="text-2xl font-bold text-danger">1</p>
                            <p class="text-xs text-danger">Dañado</p>
                        </div>
                        <div class="bg-warning/10 p-3 rounded-lg text-center">
                            <p class="text-2xl font-bold text-warning">S/6.200</p>
                            <p class="text-xs text-warning">Valor</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <!-- Artículo 1 con serie -->
                        <div
                            class="flex items-center justify-between p-3 bg-primary/5 rounded-xl border border-primary/10 hover:border-primary/20 transition-colors">
                            <div class="flex items-center space-x-3 flex-1">
                                <div
                                    class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-tablet-alt text-primary"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="min-w-0">
                                            <p class="font-medium text-gray-900 truncate">iPad Pro 12.9"</p>
                                            <div class="flex items-center space-x-3 mt-1">
                                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                                    <i class="fas fa-barcode mr-1 text-xs"></i>
                                                    SN: APP789123
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                    10/01/2024
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-3 flex-shrink-0">
                                <span
                                    class="bg-success/10 text-success text-xs font-semibold px-2 py-1 rounded-full whitespace-nowrap">
                                    Activo
                                </span>
                            </div>
                        </div>

                        <!-- Artículo 2 con serie -->
                        <div
                            class="flex items-center justify-between p-3 bg-primary/5 rounded-xl border border-primary/10 hover:border-primary/20 transition-colors">
                            <div class="flex items-center space-x-3 flex-1">
                                <div
                                    class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-mobile-alt text-primary"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="min-w-0">
                                            <p class="font-medium text-gray-900 truncate">iPhone 14 Pro</p>
                                            <div class="flex flex-col space-y-1 mt-1">
                                                <div class="flex items-center space-x-3">
                                                    <span
                                                        class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                                        <i class="fas fa-barcode mr-1 text-xs"></i>
                                                        SN: APP456789
                                                    </span>
                                                    <span class="text-xs text-gray-500">
                                                        <i class="fas fa-calendar-alt mr-1"></i>
                                                        05/01/2024
                                                    </span>
                                                </div>
                                                <div
                                                    class="text-xs text-red-500 bg-red-50 px-2 py-0.5 rounded inline-flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                                    Reportado: 05/01/2024
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-3 flex-shrink-0">
                                <span
                                    class="bg-danger/10 text-danger text-xs font-semibold px-2 py-1 rounded-full whitespace-nowrap">
                                    Dañado
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje especial -->
                    <div class="mt-4 p-3 bg-warning/5 rounded-xl border border-warning/10">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            <p class="text-sm text-warning">Requiere reemplazo del iPhone</p>
                        </div>
                    </div>
                </div>

                <div class="px-5 pb-5">
                    <div class="flex space-x-3">
                        <button
                            class="flex-1 px-4 py-2.5 bg-primary text-white font-semibold rounded-xl hover:bg-primary-dark transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center">
                            <i class="fas fa-plus mr-2"></i> Editar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layout.default>
