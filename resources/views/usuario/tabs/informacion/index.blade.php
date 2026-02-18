<template x-if="tab === 'informacion'">
    <div x-init="$nextTick(() => initFlatpickr())" class="space-y-6">

        <!-- ============================================ -->
        <!-- SECCIÓN 1: DATOS LABORALES -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-5 bg-white dark:bg-[#0e1726]">
            <!-- Header -->
            <div class="flex items-center gap-2 mb-6">
                <div class="w-1 h-8 bg-blue-600 rounded-full"></div>
                <div>
                    <h5 class="text-lg font-bold text-gray-800 dark:text-white">Datos Laborales</h5>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Información contractual y jornada laboral
                    </p>
                </div>
            </div>

            <!-- SOLO UNA COLUMNA -->
            <div class="bg-white dark:bg-[#0e1726] border border-gray-200 dark:border-gray-700 rounded-xl p-5 shadow-sm">
                <!-- Título -->
                <div class="flex items-center gap-2 pb-3 mb-4 border-b border-gray-100 dark:border-gray-800">
                    <div class="w-7 h-7 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-contract text-blue-600 dark:text-blue-400 text-sm"></i>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">Información de Contrato y
                        Horario</span>
                </div>

                <!-- Campos en grid de 2 columnas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tipo Contrato -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            <i class="fas fa-tag text-blue-500 mr-1"></i>
                            Tipo de Contrato
                        </label>
                        <select name="idTipoContrato" id="idTipoContrato"
                            class="form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-blue-500">
                            <option value="">Seleccione tipo de contrato</option>
                            @foreach ($tiposContrato as $tipo)
                                <option value="{{ $tipo->idTipoContrato }}"
                                    {{ ($laboral->idTipoContrato ?? '') == $tipo->idTipoContrato ? 'selected' : '' }}>
                                    {{ $tipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Sueldo Mensual -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            <i class="fas fa-coins text-blue-500 mr-1"></i>
                            Sueldo Mensual
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">S/</span>
                            <input type="number" name="sueldoMensual" id="sueldoMensual" step="0.01"
                                value="{{ $usuario->sueldoMensual ?? '' }}" placeholder="0.00"
                                class="form-input w-full pl-9 bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-blue-500">
                        </div>
                    </div>
                    <!-- Fecha Inicio - Flatpickr -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            <i class="fas fa-calendar-plus text-blue-500 mr-1"></i>
                            Fecha Inicio
                        </label>
                        <div class="relative">
                            <input type="text" name="fechaInicio" id="fechaInicio"
                                class="flatpickr form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-blue-500"
                                placeholder="Seleccione fecha"
                                value="{{ $laboral->fechaInicio ? date('Y-m-d', strtotime($laboral->fechaInicio)) : '' }}">
                            <i
                                class="fas fa-calendar-alt absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Fecha Término - Flatpickr -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            <i class="fas fa-calendar-times text-blue-500 mr-1"></i>
                            Fecha Término
                        </label>
                        <div class="relative">
                            <input type="text" name="fechaTermino" id="fechaTermino"
                                class="flatpickr form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-blue-500"
                                placeholder="Seleccione fecha"
                                value="{{ $laboral->fechaTermino ? date('Y-m-d', strtotime($laboral->fechaTermino)) : '' }}">
                            <i
                                class="fas fa-calendar-alt absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Hora Inicio - Flatpickr time -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            <i class="fas fa-sun text-green-500 mr-1"></i>
                            Hora Inicio
                        </label>
                        <div class="relative">
                            <input type="text" name="horaInicioJornada" id="horaInicioJornada"
                                class="flatpickr-time form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-green-500"
                                placeholder="Seleccione hora"
                                value="{{ $laboral->horaInicioJornada ? \Carbon\Carbon::parse($laboral->horaInicioJornada)->format('H:i') : '' }}">
                            <i
                                class="fas fa-clock absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Hora Término - Flatpickr time -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            <i class="fas fa-moon text-green-500 mr-1"></i>
                            Hora Término
                        </label>
                        <div class="relative">
                            <input type="text" name="horaFinJornada" id="horaFinJornada"
                                class="flatpickr-time form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-green-500"
                                placeholder="Seleccione hora"
                                value="{{ $laboral->horaFinJornada ? \Carbon\Carbon::parse($laboral->horaFinJornada)->format('H:i') : '' }}">
                            <i
                                class="fas fa-clock absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>


                </div>

                <!-- Texto ayuda -->
                <p class="text-xs text-gray-400 mt-4">Complete la información contractual del empleado</p>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECCIÓN 2: CONFIGURACIÓN DE USUARIO -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-5 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-1 h-7 bg-secondary rounded-full"></div>
                <div>
                    <h6 class="text-lg font-bold text-gray-800 dark:text-white">Configuración de Usuario</h6>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Asignación de sucursal, área y permisos
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Sucursal -->
                <div>
                    <label for="idSucursal"
                        class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1 mb-1">
                        <i class="fas fa-building text-purple-500"></i>
                        Sucursal
                    </label>
                    <select name="idSucursal" id="idSucursal" class="form-input w-full">
                        <option value="" selected disabled>Selecciona una Sucursal</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->idSucursal }}"
                                {{ $usuario->idSucursal == $sucursal->idSucursal ? 'selected' : '' }}>
                                {{ $sucursal->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo de Área -->
                <div>
                    <label for="idTipoArea"
                        class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1 mb-1">
                        <i class="fas fa-layer-group text-purple-500"></i>
                        Tipo de Área
                    </label>
                    <select name="idTipoArea" id="idTipoArea" class="form-input w-full">
                        <option value="" selected disabled>Selecciona un Tipo de Área</option>
                        @foreach ($tiposArea as $tipoArea)
                            <option value="{{ $tipoArea->idTipoArea }}"
                                {{ $usuario->idTipoArea == $tipoArea->idTipoArea ? 'selected' : '' }}>
                                {{ $tipoArea->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo de Usuario -->
                <div>
                    <label for="idTipoUsuario"
                        class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1 mb-1">
                        <i class="fas fa-user-tag text-purple-500"></i>
                        Tipo de Usuario
                    </label>
                    <select name="idTipoUsuario" id="idTipoUsuario" class="form-input w-full">
                        <option value="" selected disabled>Selecciona un Tipo de Usuario</option>
                        @foreach ($tiposUsuario as $tipoUsuario)
                            <option value="{{ $tipoUsuario->idTipoUsuario }}"
                                {{ $usuario->idTipoUsuario == $tipoUsuario->idTipoUsuario ? 'selected' : '' }}>
                                {{ $tipoUsuario->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Rol -->
                <div>
                    <label for="idRol"
                        class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1 mb-1">
                        <i class="fas fa-shield-alt text-purple-500"></i>
                        Rol
                    </label>
                    <select name="idRol" id="idRol" class="form-input w-full">
                        <option value="" selected disabled>Selecciona un Rol</option>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->idRol }}"
                                {{ $usuario->idRol == $rol->idRol ? 'selected' : '' }}>
                                {{ $rol->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Botón de actualización -->
            <div class="flex justify-end mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="button" id="update-informacion-btn"
                    class="btn bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white border-none px-8 py-2.5 flex items-center gap-2 rounded-lg shadow-md hover:shadow-lg transition-all">
                    <i class="fas fa-save"></i>
                    Actualizar Información
                </button>
            </div>
        </div>
    </div>
</template>
