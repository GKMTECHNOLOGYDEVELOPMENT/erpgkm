<x-layout.auth>
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <!-- Fondo decorativo -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="px-6 py-16 text-center font-semibold relative z-10">
            <div class="max-w-md mx-auto">
                <!-- Icono 403 animado -->
                <div class="relative mb-8">
                    <div class="relative w-48 h-48 mx-auto">
                        <!-- Círculo de fondo animado -->
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-purple-600/20 rounded-full opacity-60 animate-pulse"></div>
                        
                        <!-- Anillo exterior con animación -->
                        <div class="absolute inset-4 border-4 border-red-500 rounded-full flex items-center justify-center animate-bounce" style="animation-duration: 2s;">
                            <!-- Icono de prohibido mejorado -->
                            <div class="relative w-28 h-28 transform transition-transform duration-300 hover:scale-110">
                                <!-- Círculo interior con sombra -->
                                <div class="absolute inset-0 border-4 border-red-500 rounded-full shadow-lg shadow-red-500/30"></div>
                                <!-- Barra diagonal con gradiente -->
                                <div class="absolute top-1/2 left-0 right-0 h-3 bg-gradient-to-r from-red-500 to-red-600 transform -rotate-45 -translate-y-1/2 rounded-full shadow-md"></div>
                            </div>
                        </div>
                        
                        <!-- Texto 403 con efecto -->
                        <div class="absolute -bottom-10 left-0 right-0">
                            <span class="text-3xl font-bold bg-gradient-to-r from-red-500 to-red-600 bg-clip-text text-transparent drop-shadow-sm">
                                403
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contenido textual mejorado -->
                <div class="space-y-4 mb-8">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white tracking-tight">
                        Acceso Denegado
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed max-w-sm mx-auto">
                        No tienes los permisos necesarios para acceder a esta página.
                    </p>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="/" 
                       class="btn btn-gradient px-8 py-3 rounded-lg text-white font-semibold uppercase tracking-wide transition-all duration-300 transform hover:scale-105 hover:shadow-lg shadow-md border-0 min-w-[140px] text-center">
                        <i class="fas fa-home mr-2"></i>
                        Inicio
                    </a>
                    
                    <button onclick="history.back()" 
                            class="px-8 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold uppercase tracking-wide transition-all duration-300 transform hover:scale-105 hover:shadow-lg min-w-[140px]">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </button>
                </div>

                <!-- Información adicional -->
                <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <p class="text-sm text-blue-700 dark:text-blue-300 flex items-center justify-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        Si crees que esto es un error, contacta al administrador
                    </p>
                </div>
            </div>
        </div>

        <!-- Efectos decorativos adicionales -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 opacity-30">
            <div class="flex space-x-2">
                <div class="w-2 h-2 bg-red-500 rounded-full animate-bounce"></div>
                <div class="w-2 h-2 bg-red-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                <div class="w-2 h-2 bg-red-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            </div>
        </div>
    </div>

    <style>
        .btn-gradient {
            background: linear-gradient(135deg, #4361EE 0%, #3A0CA3 100%);
            position: relative;
            overflow: hidden;
        }
        
        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-gradient:hover::before {
            left: 100%;
        }
    </style>
</x-layout.auth>