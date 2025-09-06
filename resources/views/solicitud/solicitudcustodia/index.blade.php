<x-layout.default>
    <div class="container mx-auto px-4 py-6">
        <!-- Header mejorado -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-indigo-700">Sistema de Custodia de Equipos</h1>
                <p class="text-gray-600 mt-2">Gestión y seguimiento de equipos en custodia</p>
            </div>
            <div class="mt-4 md:mt-0">
                <button
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nueva Solicitud
                </button>
            </div>
        </div>

        <!-- Filtros mejorados -->
        <div class="panel rounded-xl shadow-sm p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="md:col-span-2">
                    <div class="relative">
                        <input type="text" placeholder="Buscar por cliente, modelo o serie..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <select
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300">
                        <option selected>Todos los estados</option>
                        <option>Pendiente</option>
                        <option>En revisión</option>
                        <option>Aprobado</option>
                        <option>Rechazado</option>
                        <option>Devuelto</option>
                    </select>
                </div>
                <div>
                    <button
                        class="w-full bg-gray-800 hover:bg-black text-white px-4 py-2 rounded-lg transition-colors duration-300 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filtrar
                    </button>
                </div>
            </div>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="bg-primary from-blue-500 to-indigo-600 rounded-xl shadow-lg p-5 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-medium">Total en Custodia</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7l9-4 9 4-9 4-9-4zm0 0v10l9 4 9-4V7" />
                    </svg>

                </div>
                <p class="text-2xl font-bold mt-2">47</p>
                <p class="text-xs opacity-75 mt-1">Equipos en custodia</p>
            </div>

            <div class="bg-warning from-amber-500 to-orange-500 rounded-xl shadow-lg p-5 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-medium">Pendientes</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                    </svg>

                </div>
                <p class="text-2xl font-bold mt-2">12</p>
                <p class="text-xs opacity-75 mt-1">Por revisar</p>
            </div>

            <div class="bg-success from-green-500 to-emerald-600 rounded-xl shadow-lg p-5 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-medium">En Custodia</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9.75V6a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 6v3.75M3 9.75L12 15l9-5.25M3 9.75v8.25A2.25 2.25 0 005.25 20.25h13.5A2.25 2.25 0 0021 18V9.75" />
                    </svg>


                </div>
                <p class="text-2xl font-bold mt-2">28</p>
                <p class="text-xs opacity-75 mt-1">Equipos activos</p>
            </div>

            <div class="bg-danger from-red-500 to-pink-600 rounded-xl shadow-lg p-5 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-medium">Devueltos</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-75" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 14l-4-4 4-4m11 4H5" />
                    </svg>

                </div>
                <p class="text-2xl font-bold mt-2">7</p>
                <p class="text-xs opacity-75 mt-1">Equipos devueltos</p>
            </div>
        </div>

        <!-- Lista de solicitudes con diseño de tarjetas -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Solicitudes de Custodia</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Tarjeta de ejemplo 1 -->
                <div
                    class="panel rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300">
                    <div class="p-5 border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <span
                                    class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full flex items-center w-fit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 极速0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                    #SOL-0047
                                </span>
                                <h3 class="text-lg font-semibold text-gray-800 mt-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Samsung QLED 55"
                                </h3>
                            </div>
                            <span
                                class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pendiente
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                            Serie: SN-554789Q2023
                        </p>
                    </div>

                    <div class="p-5">
                        <div class="flex items-center mb-4 p-3 bg-gray-50 rounded-lg">
                            <div
                                class="h-10 w-10 flex-shrink-0 bg-indigo-100 rounded-full flex items-center justify-center">
                                <span class="text-indigo-800 font-medium">JG</span>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900">Juan González</h4>
                                <p class="text-xs text-gray-500">DNI: 40234567</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-gray-600">Ingreso:</span>
                                <span class="ml-auto text-gray-900">15/08/2023</span>
                            </div>

                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-amber-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-600">Tiempo:</span>
                                <span class="ml-auto font-medium text-amber-600">3 meses 12 días</span>
                            </div>

                            <div class="flex items-start text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500 mt-0.5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0极速z" />
                                </svg>
                                <div>
                                    <span class="text-gray-600">Dirección:</span>
                                    <p class="text-gray-900">Av. Principal #123</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-5 py-3 bg-gray-50 border-t border-gray-200 flex justify-end">
                        <div class="relative inline-block text-left">
                            <button class="btn btn-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin极速="round" stroke-width="2"
                                        d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 极速0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                </svg>
                                OPCIONES
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de ejemplo 2 -->
                <div
                    class="panel rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300">
                    <div class="p-5 border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <span
                                    class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full flex items-center w-fit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 极速0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                    #SOL-0032
                                </span>
                                <h3 class="text-lg font-semibold text-gray-800 mt-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    LG OLED 65"
                                </h3>
                            </div>
                            <span
                                class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pendiente
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                            Serie: SN-LG659874O2023
                        </p>
                    </div>

                    <div class="p-5">
                        <div class="flex items-center mb-4 p-3 bg-gray-50 rounded-lg">
                            <div
                                class="h-10 w-10 flex-shrink-0 bg-purple-100 rounded-full flex items-center justify-center">
                                <span class="text-purple-800 font-medium">MP</span>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900">María Pérez</h4>
                                <p class="text-xs text-gray-500">RUC: 20123456789</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-gray-600">Ingreso:</span>
                                <span class="ml-auto text-gray-900">22/10/2023</span>
                            </div>

                            <div class="flex items-center text-sm">
                                <svg xmlns="极速http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-amber-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-600">Tiempo:</span>
                                <span class="ml-auto font-medium text-amber-600">1 mes 5 días</span>
                            </div>

                            <div class="flex items-start text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500 mt-0.5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <span class="text-gray-600">Dirección:</span>
                                    <p class="text-gray-900">Calle Secundaria #456</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-5 py-3 bg-gray-50 border-t border-gray-200 flex justify-end">
                        <div class="relative inline-block text-left">
                            <button class="btn btn-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin极速="round" stroke-width="2"
                                        d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 极速0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                </svg>
                                OPCIONES
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de ejemplo 3 -->
                <div
                    class="panel rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300">
                    <div class="p-5 border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <span
                                    class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full flex items-center w-fit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 极速0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                    #SOL-0028
                                </span>
                                <h3 class="text-lg font-semibold text-gray-800 mt-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Sony Bravia 43"
                                </h3>
                            </div>
                            <span
                                class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pendiente
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                            Serie: SN-SONY438521X2023
                        </p>
                    </div>

                    <div class="p-5">
                        <div class="flex items-center mb-4 p-3 bg-gray-50 rounded-lg">
                            <div
                                class="h-10 w-10 flex-shrink-0 bg-amber-100 rounded-full flex items-center justify-center">
                                <span class="text-amber-800 font-medium">CR</span>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900">Carlos Rodríguez</h4>
                                <p class="text-xs text-gray-500">DNI: 35432198</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-gray-600">Ingreso:</span>
                                <span class="ml-auto text-gray-900">05/11/2023</span>
                            </div>

                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-amber-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-600">Tiempo:</span>
                                <span class="ml-auto font-medium text-amber-600">21 días</span>
                            </div>

                            <div class="flex items-start text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500 mt-0.5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0极速z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <span class="text-gray-600">Dirección:</span>
                                    <p class="text-gray-900">Jr. Los Olivos #789</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-5 py-3 bg-gray-50 border-t border-gray-200 flex justify-end">
                        <div class="relative inline-block text-left">
                            <button class="btn btn-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin极速="round" stroke-width="2"
                                        d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 极速0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                </svg>
                                OPCIONES
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón de ver más -->
        <div class="text-center mb-10">
            <button
                class="bg-white hover:bg-gray-100 text-indigo-600 font-medium py-2 px-6 border border-gray-300 rounded-lg shadow-sm transition-colors duration-300">
                Cargar más solicitudes
            </button>
        </div>
    </div>
</x-layout.default>
