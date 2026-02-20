<template x-if="tab === 'danger-zone'">
    <div class="space-y-6">
        <!-- ============================================ -->
        <!-- SECCIÓN 1: CONTROL DE ACCESO POR PLATAFORMA -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-5 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-1 h-7 bg-blue-500 rounded-full"></div>
                <div>
                    <h5 class="text-lg font-bold text-gray-800 dark:text-white">Control de Acceso por Plataforma</h5>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Active o desactive el acceso independiente
                        para cada plataforma</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Web -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-5 border border-blue-200 dark:border-blue-800/30">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div>
                                <h6 class="font-semibold text-gray-800 dark:text-white">Plataforma Web</h6>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Acceso desde navegador</p>
                            </div>
                        </div>
                        <div>
                            <label class="w-12 h-6 relative block">
                                <input type="checkbox" id="estadoWeb" name="estadoWeb"
                                    class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                    {{ ($usuario->estadoWeb ?? 1) == 1 ? 'checked' : '' }} />
                                <span
                                    class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Estado actual:</span>
                        <span id="estadoWebText"
                            class="{{ ($usuario->estadoWeb ?? 1) == 1 ? 'text-green-600' : 'text-red-600' }} dark:{{ ($usuario->estadoWeb ?? 1) == 1 ? 'text-green-400' : 'text-red-400' }} font-semibold">
                            {{ ($usuario->estadoWeb ?? 1) == 1 ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>

                <!-- App -->
                <div
                    class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl p-5 border border-purple-200 dark:border-purple-800/30">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div>
                                <h6 class="font-semibold text-gray-800 dark:text-white">Aplicación Móvil</h6>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Acceso desde app móvil</p>
                            </div>
                        </div>
                        <div>
                            <label class="w-12 h-6 relative block">
                                <input type="checkbox" id="estadoApp" name="estadoApp"
                                    class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                    {{ ($usuario->estadoApp ?? 1) == 1 ? 'checked' : '' }} />
                                <span
                                    class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Estado actual:</span>
                        <span id="estadoAppText"
                            class="{{ ($usuario->estadoApp ?? 1) == 1 ? 'text-green-600' : 'text-red-600' }} dark:{{ ($usuario->estadoApp ?? 1) == 1 ? 'text-green-400' : 'text-red-400' }} font-semibold">
                            {{ ($usuario->estadoApp ?? 1) == 1 ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECCIÓN 2: CONFIGURACIÓN DE CORREO PARA ACCESO WEB -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-5 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-1 h-7 bg-purple-500 rounded-full"></div>
                <div>
                    <h5 class="text-lg font-bold text-gray-800 dark:text-white">Configuración de Correo para Acceso Web
                    </h5>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Seleccione con qué correo el usuario
                        podrá iniciar sesión en la web</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="tipoCorreoWeb" id="correoCorporativo" value="corporativo"
                            class="w-4 h-4 text-blue-600"
                            {{ ($usuario->correo_configurado_web ?? 'corporativo') == 'corporativo' ? 'checked' : '' }}>
                        <span class="text-gray-700 dark:text-gray-300">Correo Corporativo</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="tipoCorreoWeb" id="correoPersonal" value="personal"
                            class="w-4 h-4 text-blue-600"
                            {{ ($usuario->correo_configurado_web ?? 'corporativo') == 'personal' ? 'checked' : '' }}>
                        <span class="text-gray-700 dark:text-gray-300">Correo Personal</span>
                    </label>
                </div>

                <div class="bg-gray-50 dark:bg-[#1a1f2e] p-4 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            Correo configurado:
                            <span id="correoConfiguradoMostrar" class="font-medium text-gray-900 dark:text-white">
                                {{ ($usuario->correo_configurado_web ?? 'corporativo') == 'corporativo' ? $usuario->correo ?? 'No configurado' : $usuario->correo_personal ?? 'No configurado' }}
                            </span>
                        </span>
                    </div>
                </div>

                <!-- Botón para guardar configuración de correo -->
                <div class="flex justify-end">
                    <button id="btnGuardarCorreoConfig"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Configuración de Correo
                    </button>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECCIÓN 3: ENVÍO DE ACCESOS Y CREDENCIALES -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-5 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-1 h-7 bg-green-500 rounded-full"></div>
                <div>
                    <h5 class="text-lg font-bold text-gray-800 dark:text-white">Envío de Credenciales</h5>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Seleccione las credenciales a enviar y el
                        destinatario</p>
                </div>
            </div>

            <!-- 3.1 Selección de Credenciales -->
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Tipo de credenciales a enviar:
                </h3>
                <div class="flex flex-wrap gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="enviarWeb" class="w-4 h-4 text-blue-600 rounded" checked>
                        <span class="text-gray-700 dark:text-gray-300">Plataforma Web</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="enviarApp" class="w-4 h-4 text-blue-600 rounded" checked>
                        <span class="text-gray-700 dark:text-gray-300">Aplicación Móvil</span>
                    </label>
                </div>
            </div>

            <!-- 3.2 y 3.3 Contenido y Destinatarios -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Credenciales Web -->
                <div id="credencialesWeb" class="border rounded-lg p-4 dark:border-gray-700">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fas fa-globe text-blue-500"></i>
                        <h4 class="font-medium text-gray-900 dark:text-white">Credenciales Web</h4>
                    </div>
                    <div class="space-y-2 text-sm">
                        <p class="text-gray-600 dark:text-gray-400">Usuario: <span
                                class="font-mono text-gray-900 dark:text-white"
                                id="usuarioWeb">{{ $usuario->correo ?? 'No configurado' }}</span></p>
                        <p class="text-gray-600 dark:text-gray-400">Contraseña: <span
                                class="font-mono text-gray-900 dark:text-white">••••••••</span></p>
                    </div>
                </div>

                <!-- Credenciales App -->
                <div id="credencialesApp" class="border rounded-lg p-4 dark:border-gray-700">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fas fa-mobile-alt text-green-500"></i>
                        <h4 class="font-medium text-gray-900 dark:text-white">Credenciales App</h4>
                    </div>
                    <div class="space-y-2 text-sm">
                        <p class="text-gray-600 dark:text-gray-400">Usuario: <span
                                class="font-mono text-gray-900 dark:text-white"
                                id="usuarioApp">{{ $usuario->usuario ?? '' }}_app</span></p>
                        <p class="text-gray-600 dark:text-gray-400">Contraseña: <span
                                class="font-mono text-gray-900 dark:text-white">••••••••</span></p>
                    </div>
                </div>
            </div>

            <!-- Destinatarios -->
            <div class="bg-gray-50 dark:bg-[#1a1f2e] p-4 rounded-lg mb-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Enviar a:</h4>
                <div class="flex flex-wrap gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="destinatario" id="destCorporativo" value="corporativo"
                            class="w-4 h-4 text-blue-600" checked>
                        <span class="text-gray-700 dark:text-gray-300">Correo Corporativo
                            ({{ $usuario->correo ?? 'No configurado' }})</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="destinatario" id="destPersonal" value="personal"
                            class="w-4 h-4 text-blue-600">
                        <span class="text-gray-700 dark:text-gray-300">Correo Personal
                            ({{ $usuario->correo_personal ?? 'No configurado' }})</span>
                    </label>
                </div>
            </div>

            <!-- 3.4 Notificación a Gerencia -->
            <div class="border-l-4 border-yellow-400 bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded mb-4">
                <div class="flex items-start gap-3">
                    <i class="fas fa-bell text-yellow-600 dark:text-yellow-400 mt-1"></i>
                    <div>
                        <h4 class="font-medium text-yellow-800 dark:text-yellow-300">Notificación a Gerencia</h4>
                        <p id="notificacionGerencia" class="text-sm text-yellow-700 dark:text-yellow-400 mt-1">
                            Se notificará a gerencia: Usuario: {{ $usuario->Nombre ?? '' }}
                            {{ $usuario->apellidoPaterno ?? '' }} | Acceso: Web + App | Fecha:
                            {{ now()->format('d/m/Y H:i') }} | Admin:
                            {{ auth()->user()->name ?? (auth()->user()->usuario ?? 'Sistema') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Alertas -->
            <div id="alertaContainer" class="mb-4 hidden">
                <div id="alerta" class="p-4 rounded-lg"></div>
            </div>

            <button id="btnEnviarCredenciales"
                class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                <i class="fas fa-paper-plane mr-2"></i>
                Enviar Credenciales
            </button>
        </div>

        <!-- ============================================ -->
        <!-- SECCIÓN 4: GESTIÓN DE CONTRASEÑAS Y GENERADOR -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-5 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-1 h-7 bg-red-500 rounded-full"></div>
                <div>
                    <h5 class="text-lg font-bold text-gray-800 dark:text-white">Gestión de Contraseñas</h5>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Configure contraseñas independientes
                        para cada plataforma</p>
                </div>
            </div>

            <!-- Contraseñas Independientes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Contraseña Web
                    </label>
                    <div class="relative">
                        <input type="password" id="passwordWeb" value="" placeholder="Nueva contraseña"
                            class="w-full pr-10 pl-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <button onclick="togglePassword('passwordWeb', 'iconWeb')" type="button"
                            class="absolute right-2 top-2 text-gray-400 hover:text-gray-600">
                            <i id="iconWeb" class="far fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Dejar vacío para no cambiar</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Contraseña App
                    </label>
                    <div class="relative">
                        <input type="password" id="passwordApp" value="" placeholder="Nueva contraseña"
                            class="w-full pr-10 pl-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <button onclick="togglePassword('passwordApp', 'iconApp')" type="button"
                            class="absolute right-2 top-2 text-gray-400 hover:text-gray-600">
                            <i id="iconApp" class="far fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Dejar vacío para no cambiar</p>
                </div>
            </div>

            <!-- Botón guardar contraseñas -->
            <div class="flex justify-end mb-6">
                <button id="btnGuardarContrasenas"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Contraseñas
                </button>
            </div>

            <!-- Generador de Contraseñas Seguras -->
            <div class="border-t pt-4 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Generador de Contraseñas Seguras
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <label class="flex items-center gap-2">
                        <input type="number" id="configLongitud" value="12" min="8" max="32"
                            class="w-20 px-2 py-1 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Longitud</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" id="configMayusculas" checked class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Mayúsculas</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" id="configNumeros" checked class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Números</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" id="configEspeciales" checked class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Caracteres especiales</span>
                    </label>
                </div>

                <div class="flex gap-2">
                    <input type="text" id="passwordGenerada" readonly
                        class="flex-1 px-3 py-2 bg-gray-50 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white font-mono">
                    <button onclick="generarPassword()" type="button"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Generar
                    </button>
                    <button onclick="copiarPassword()" type="button"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
