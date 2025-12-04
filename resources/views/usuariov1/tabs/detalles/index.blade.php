<template x-if="tab === 'danger-zone'">
    <div class="switch">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <!-- Limpiar caché -->
            <div class="panel space-y-5">
                <h5 class="font-semibold text-lg mb-4">Limpiar caché</h5>
                <p>Elimina la caché activa sin esperar el tiempo de expiración.</p>
                <button class="btn btn-secondary">Clear</button>
            </div>

            <!-- Desactivar cuenta -->
            <div class="panel space-y-5">
                <h5 class="font-semibold text-lg mb-4">Desactivar cuenta</h5>
                <p>No recibirás mensajes ni notificaciones durante 24 horas.</p>
                <label class="w-12 h-6 relative">
                    <input type="checkbox" class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer" />
                    <span class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                </label>
            </div>

            <!-- Eliminar cuenta -->
            <div class="panel space-y-5">
                <h5 class="font-semibold text-lg mb-4">Eliminar cuenta</h5>
                <p>Una vez eliminada, no podrás recuperar tu cuenta.</p>
                <button class="btn btn-danger btn-delete-account">Eliminar cuenta</button>
            </div>

            <!-- Reenviar enlace de contraseña -->
            <div class="panel space-y-5">
                <h5 class="font-semibold text-lg mb-4">Reenviar enlace de contraseña</h5>
                <p>Te enviaremos un correo para restablecer tu contraseña.</p>
                <button class="btn btn-warning">Enviar enlace</button>
            </div>

            <!-- Cambiar contraseña mejorado -->
            <div class="panel space-y-5">
                <h5 class="font-semibold text-lg mb-4 text-red-600 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 11c.657 0 1.26.267 1.707.707M17 16v1a3 3 0 01-3 3H10a3 3 0 01-3-3v-1m5-11a4 4 0 00-4 4v4h8v-4a4 4 0 00-4-4z" />
                    </svg>
                    Cambiar contraseña
                </h5>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Actualiza tu contraseña actual por una nueva más segura. Asegúrate de no compartirla con nadie.
                </p>
                <form @submit.prevent="handlePasswordChange" class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña actual</label>
                        <input type="password" placeholder="••••••••"
                            class="input input-bordered w-full focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            required>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nueva contraseña</label>
                        <input type="password" placeholder="••••••••"
                            class="input input-bordered w-full focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            required>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmar nueva contraseña</label>
                        <input type="password" placeholder="••••••••"
                            class="input input-bordered w-full focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            required>
                    </div>
                    <button type="submit"
                        class="btn btn-danger w-full mt-4 transition duration-200 hover:bg-red-600">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Actualizar contraseña
                    </button>
                </form>
            </div>

            <!-- Cerrar sesiones -->
            <div class="panel space-y-5">
                <h5 class="font-semibold text-lg mb-4">Cerrar todas las sesiones</h5>
                <p>Cierra tu sesión en todos los dispositivos conectados.</p>
                <button class="btn btn-warning">Cerrar sesiones</button>
            </div>

            <!-- Descargar datos -->
            <div class="panel space-y-5">
                <h5 class="font-semibold text-lg mb-4">Descargar mis datos</h5>
                <p>Solicita una copia de tus datos personales.</p>
                <button class="btn btn-primary">Solicitar descarga</button>
            </div>

        </div>
    </div>
</template>
