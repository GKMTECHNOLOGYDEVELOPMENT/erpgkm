<template x-if="tab === 'payment-details'">
    <div class="space-y-6">
        <!-- ============================================ -->
        <!-- SECCIÓN 1: MIS CUENTAS BANCARIAS -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-5 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-7 bg-green-500 rounded-full"></div>
                    <div>
                        <h5 class="text-lg font-bold text-gray-800 dark:text-white">Mis Cuentas Bancarias</h5>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Cuentas registradas para recibir pagos</p>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de cuenta bancaria -->
            @if($fichaGeneral && $fichaGeneral->numeroCuenta)
            <div id="cuenta-container" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-5 border border-green-200 dark:border-green-800/30">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-university"></i>
                            </div>
                            <div>
                                <h6 class="font-semibold text-gray-800 dark:text-white">{{ $bancos[$fichaGeneral->entidadBancaria] ?? 'Banco no especificado' }}</h6>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $monedas[$fichaGeneral->moneda] ?? $fichaGeneral->moneda }} - {{ $tiposCuenta[$fichaGeneral->tipoCuenta] ?? $fichaGeneral->tipoCuenta }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-medium rounded-full">
                            Principal
                        </span>
                    </div>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Número de Cuenta</p>
                            <p class="font-mono text-sm font-medium text-gray-800 dark:text-white">{{ $fichaGeneral->numeroCuenta }}</p>
                        </div>
                        @if($fichaGeneral->numeroCCI)
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Número de CCI</p>
                            <p class="font-mono text-sm font-medium text-gray-800 dark:text-white">{{ $fichaGeneral->numeroCCI }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-green-200 dark:border-green-800/30">
                        <button type="button" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1 edit-cuenta-btn">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                    </div>
                </div>
            </div>
            @else
            <div id="cuenta-container" class="text-center py-8 text-gray-500 dark:text-gray-400">
                <i class="fas fa-university text-4xl text-green-300 mb-3"></i>
                <p>No hay cuentas bancarias registradas</p>
                <p class="text-xs mt-1">Complete el formulario para agregar su primera cuenta</p>
            </div>
            @endif
        </div>

        <!-- ============================================ -->
        <!-- SECCIÓN 2: AGREGAR NUEVA CUENTA BANCARIA -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-5 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-1 h-7 bg-secondary rounded-full"></div>
                <div>
                    <h5 id="form-title" class="text-lg font-bold text-gray-800 dark:text-white">
                        {{ $fichaGeneral && $fichaGeneral->numeroCuenta ? 'Editar' : 'Agregar Nueva' }} Cuenta Bancaria
                    </h5>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Complete todos los campos para registrar una nueva cuenta</p>
                </div>
            </div>

            <form id="cuenta-bancaria-form" class="space-y-5">
                @csrf
                <input type="hidden" name="idUsuario" value="{{ $usuario->idUsuario }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Banco -->
                    <div>
                        <label for="banco" class="text-xs font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1.5 mb-1.5">
                            <i class="fas fa-university text-purple-500"></i>
                            Banco <span class="text-red-500">*</span>
                        </label>
                        <select id="banco" name="entidadBancaria" class="form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-purple-500" required>
                            <option value="">Seleccione una Opción</option>
                            @foreach($bancos as $key => $banco)
                                <option value="{{ $key }}" {{ ($fichaGeneral->entidadBancaria ?? '') == $key ? 'selected' : '' }}>
                                    {{ $banco }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Moneda -->
                    <div>
                        <label for="moneda" class="text-xs font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1.5 mb-1.5">
                            <i class="fas fa-coins text-purple-500"></i>
                            Moneda <span class="text-red-500">*</span>
                        </label>
                        <select id="moneda" name="moneda" class="form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-purple-500" required>
                            <option value="">Seleccione Moneda</option>
                            @foreach($monedas as $key => $moneda)
                                <option value="{{ $key }}" {{ ($fichaGeneral->moneda ?? '') == $key ? 'selected' : '' }}>
                                    {{ $moneda }} ({{ $key }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tipo de cuenta -->
                    <div>
                        <label for="tipoCuenta" class="text-xs font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1.5 mb-1.5">
                            <i class="fas fa-wallet text-purple-500"></i>
                            Tipo de Cuenta <span class="text-red-500">*</span>
                        </label>
                        <select id="tipoCuenta" name="tipoCuenta" class="form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-purple-500" required>
                            <option value="">Seleccione una Opción</option>
                            @foreach($tiposCuenta as $key => $tipo)
                                <option value="{{ $key }}" {{ ($fichaGeneral->tipoCuenta ?? '') == $key ? 'selected' : '' }}>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Número de cuenta -->
                    <div>
                        <label for="numeroCuenta" class="text-xs font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1.5 mb-1.5">
                            <i class="fas fa-hashtag text-purple-500"></i>
                            Número de Cuenta <span class="text-red-500">*</span>
                        </label>
                        <input id="numeroCuenta" name="numeroCuenta" type="text" 
                               value="{{ $fichaGeneral->numeroCuenta ?? '' }}"
                               placeholder="Ej: 191-12345678-0-12"
                               class="form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-purple-500" 
                               required>
                    </div>

                    <!-- Número de CCI -->
                    <div>
                        <label for="numeroCCI" class="text-xs font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1.5 mb-1.5">
                            <i class="fas fa-qrcode text-purple-500"></i>
                            Número de CCI <span class="text-red-500">*</span>
                        </label>
                        <input id="numeroCCI" name="numeroCCI" type="text" 
                               value="{{ $fichaGeneral->numeroCCI ?? '' }}"
                               placeholder="Ej: 00219112345678901234"
                               class="form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-purple-500" 
                               required>
                    </div>

                    <!-- Cuenta principal (por defecto será la única) -->
                    <div>
                        <label class="text-xs font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1.5 mb-1.5">
                            <i class="fas fa-star text-purple-500"></i>
                            Cuenta Principal
                        </label>
                        <div class="h-10 flex items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Esta será tu cuenta principal</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" id="saveBtn" class="btn bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white border-none px-8 py-2.5 flex items-center gap-2 rounded-lg shadow-md hover:shadow-lg transition-all">
                        <i class="fas fa-save"></i>
                        {{ $fichaGeneral && $fichaGeneral->numeroCuenta ? 'Actualizar' : 'Guardar' }} Cuenta Bancaria
                    </button>
                </div>
            </form>
        </div>

        <!-- ============================================ -->
        <!-- SECCIÓN 3: HISTORIAL DE PAGOS - QUINCENAS -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-5 bg-white dark:bg-[#0e1726]">
            <!-- Header simple -->
            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-7 bg-amber-500 rounded-full"></div>
                    <h5 class="text-lg font-bold text-gray-800 dark:text-white">Historial de Pagos</h5>
                </div>

                <!-- Filtro por período - SIMPLE -->
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-gray-400"></i>
                    <select class="form-input text-sm py-1.5 w-40">
                        <option>Seleccionar mes</option>
                        <option>Enero 2025</option>
                        <option>Febrero 2025</option>
                        <option>Marzo 2025</option>
                        <option selected>Abril 2025</option>
                        <option>Mayo 2025</option>
                    </select>
                </div>
            </div>

            <!-- Tabla de pagos - SOLO DATOS DEL USUARIO -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-[#1a1f2e]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Concepto</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Boleta</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <!-- PAGADO -->
                        <tr>
                            <td class="px-4 py-3 font-medium">Quincena 1</td>
                            <td class="px-4 py-3 text-gray-600">15/04/2025</td>
                            <td class="px-4 py-3 text-gray-600">Abril - Primera quincena</td>
                            <td class="px-4 py-3 font-medium text-gray-900">S/ 1,200.00</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Pagado
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="#" class="text-blue-600 hover:text-blue-800" title="Descargar PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                        <!-- PENDIENTE -->
                        <tr class="bg-gray-50/50 dark:bg-[#1a1f2e]/50">
                            <td class="px-4 py-3 font-medium">Quincena 2</td>
                            <td class="px-4 py-3 text-gray-600">30/04/2025</td>
                            <td class="px-4 py-3 text-gray-600">Abril - Segunda quincena</td>
                            <td class="px-4 py-3 font-medium text-gray-900">S/ 1,200.00</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pendiente
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-gray-400 text-xs">—</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Resumen simple y navegación -->
            <div class="flex flex-wrap items-center justify-between gap-3 mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                <div class="text-xs text-gray-500">
                    Mostrando <span class="font-medium">2</span> de <span class="font-medium">2</span> pagos
                </div>
                <div class="flex items-center gap-2">
                    <button class="btn btn-sm btn-outline-secondary px-3 py-1" disabled>
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <span class="text-sm px-2">Página 1 de 1</span>
                    <button class="btn btn-sm btn-outline-secondary px-3 py-1" disabled>
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
