<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div x-data="dangerZone({{ $usuario->idUsuario }}, {{ $usuario->estado }})">
    <div x-data="dangerZone()" class="switch">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">

            <!-- Cambiar contraseña -->
            <div
                class="panel space-y-6 border border-red-200 dark:border-red-900 bg-gradient-to-br from-white to-red-50 dark:from-gray-800 dark:to-gray-900">
                <div class="flex items-start justify-between">
                    <div>
                        <h5 class="font-bold text-xl mb-2 text-red-700 dark:text-red-400 flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            Cambiar Contraseña
                        </h5>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Protege tu cuenta con una nueva contraseña segura.
                        </p>
                    </div>
                </div>

                <form @submit.prevent="changePassword" class="space-y-4">
                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Contraseña Actual
                        </label>
                        <div class="relative">
                            <input x-model="passwordData.current" :type="showCurrent ? 'text' : 'password'"
                                placeholder="••••••••"
                                class="input input-bordered w-full pl-10 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                required>
                            <span class="absolute left-3 top-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </span>
                            <button type="button" @click="showCurrent = !showCurrent"
                                class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                <i :class="showCurrent ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Nueva Contraseña
                        </label>
                        <div class="relative">
                            <input x-model="passwordData.new" :type="showNew ? 'text' : 'password'"
                                placeholder="••••••••"
                                class="input input-bordered w-full pl-10 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                required>
                            <span class="absolute left-3 top-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </span>
                            <button type="button" @click="showNew = !showNew"
                                class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                <i :class="showNew ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Confirmar Nueva Contraseña
                        </label>
                        <div class="relative">
                            <input x-model="passwordData.confirm" :type="showConfirm ? 'text' : 'password'"
                                placeholder="••••••••"
                                class="input input-bordered w-full pl-10 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                required>
                            <span class="absolute left-3 top-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </span>
                            <!-- Añade este botón para el ojito -->
                            <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                <i :class="showConfirm ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" :disabled="!passwordsMatch || loading"
                        class="btn btn-danger w-full mt-6 py-3 font-semibold transition duration-200 hover:scale-[1.02]"
                        :class="{ 'opacity-50 cursor-not-allowed': !passwordsMatch || loading }">
                        <svg class="w-5 h-5 mr-2 animate-spin" x-show="loading" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span x-show="!loading">Actualizar Contraseña</span>
                        <span x-show="loading">Procesando...</span>
                    </button>
                </form>
            </div>

            <!-- Desactivar cuenta -->
            <div class="panel space-y-6 border border-purple-200 dark:border-purple-900">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="font-bold text-lg text-purple-700 dark:text-purple-400">
                                Desactivar Cuenta
                            </h5>
                            <p class="text-xs text-gray-500 mt-1">Estado actual:
                                <span x-text="accountStatus" :class="accountStatusClass"></span>
                            </p>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Desactiva tu cuenta temporalmente. No podrás acceder hasta que sea reactivada por un administrador.
                </p>

                <div class="flex gap-2">
                    <button @click="desactivarCuenta" x-show="!deactivateAccount" :disabled="loadingAccount"
                        class="btn btn-warning flex-1 flex items-center justify-center gap-2 py-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        <span x-show="!loadingAccount">Desactivar Cuenta</span>
                        <span x-show="loadingAccount">Procesando...</span>
                    </button>

                    <button @click="activarCuenta" x-show="deactivateAccount" :disabled="loadingAccount"
                        class="btn btn-success flex-1 flex items-center justify-center gap-2 py-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span x-show="!loadingAccount">Activar Cuenta</span>
                        <span x-show="loadingAccount">Procesando...</span>
                    </button>
                </div>
            </div>

            <!-- Descargar información en PDF -->
            <div class="panel space-y-6 border border-blue-200 dark:border-blue-900">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h5 class="font-bold text-lg text-blue-700 dark:text-blue-400">
                        Descargar Información
                    </h5>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Genera un PDF con toda tu información personal, datos laborales y documentos.
                </p>
                <button @click="descargarPDF" :disabled="loadingPDF"
                    class="btn btn-primary w-full flex items-center justify-center gap-2 py-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="!loadingPDF">Descargar PDF</span>
                    <span x-show="loadingPDF">Generando...</span>
                </button>
            </div>

            <!-- Descargar documentos en ZIP -->
            <div class="panel space-y-6 border border-green-200 dark:border-green-900">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                    </div>
                    <h5 class="font-bold text-lg text-green-700 dark:text-green-400">
                        Descargar Documentos
                    </h5>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Descarga todos tus documentos adjuntos (CV, DNI, etc.) en un archivo ZIP comprimido.
                </p>
                <button @click="descargarDocumentosZIP" :disabled="loadingZIP"
                    class="btn btn-success w-full flex items-center justify-center gap-2 py-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    <span x-show="!loadingZIP">Descargar ZIP</span>
                    <span x-show="loadingZIP">Preparando...</span>
                </button>
            </div>

            <!-- Enviar enlace de recuperación -->
            <div class="panel space-y-6 border border-orange-200 dark:border-orange-900 lg:col-span-2">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h5 class="font-bold text-lg text-orange-700 dark:text-orange-400">
                        Enlace de Recuperación
                    </h5>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Te enviaremos un enlace seguro para restablecer tu contraseña a tu correo electrónico registrado.
                </p>
                <button @click="enviarEnlaceRecuperacion" :disabled="loadingEnlace"
                    class="btn btn-warning w-full flex items-center justify-center gap-2 py-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span x-show="!loadingEnlace">Enviar Enlace</span>
                    <span x-show="loadingEnlace">Enviando...</span>
                </button>
            </div>

        </div>
    </div>
</div>
