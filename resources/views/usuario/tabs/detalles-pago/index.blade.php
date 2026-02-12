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
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Cuentas registradas para recibir pagos
                        </p>
                    </div>
                </div>

            </div>

            <div x-data x-init="$nextTick(() => initPaymentDetails({{ $usuario->idUsuario }}))">
                <div id="cuentas-bancarias" class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECCIÓN 2: AGREGAR NUEVA CUENTA BANCARIA -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-5 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-1 h-7 bg-secondary rounded-full"></div>
                <div>
                    <h5 class="text-lg font-bold text-gray-800 dark:text-white">Agregar Nueva Cuenta Bancaria</h5>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Complete todos los campos para registrar
                        una nueva cuenta</p>
                </div>
            </div>

            <form class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Banco -->
                    <div>
                        <label for="banco"
                            class="text-xs font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1.5 mb-1.5">
                            <i class="fas fa-university text-purple-500"></i>
                            Banco <span class="text-red-500">*</span>
                        </label>
                        <select id="banco"
                            class="form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-purple-500">
                            <option>Seleccione una Opción</option>
                            <option value="1">Banco de Crédito del Perú</option>
                            <option value="2">BBVA Perú</option>
                            <option value="3">Scotiabank Perú</option>
                            <option value="4">Interbank</option>
                            <option value="5">Banco de la Nación</option>
                            <option value="6">Banco de Comercio</option>
                            <option value="7">BanBif</option>
                            <option value="8">Banco Pichincha</option>
                            <option value="9">Citibank Perú</option>
                            <option value="10">MiBanco</option>
                            <option value="11">Banco GNB Perú</option>
                            <option value="12">Banco Falabella</option>
                            <option value="13">Banco Ripley</option>
                            <option value="14">Banco Santander Perú</option>
                            <option value="15">Alfin Banco</option>
                            <option value="16">Bank of China</option>
                            <option value="17">Bci Perú</option>
                            <option value="18">ICBC Perú Bank</option>
                        </select>
                    </div>

                    <!-- Moneda -->
                    <div>
                        <label for="moneda"
                            class="text-xs font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1.5 mb-1.5">
                            <i class="fas fa-coins text-purple-500"></i>
                            Moneda <span class="text-red-500">*</span>
                        </label>
                        <select id="moneda"
                            class="form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-purple-500">
                            <option>Seleccione Moneda</option>
                            <option value="PEN">Soles (PEN)</option>
                            <option value="USD">Dólares (USD)</option>
                            <option value="EUR">Euros (EUR)</option>
                        </select>
                    </div>

                    <!-- Tipo de cuenta -->
                    <div>
                        <label for="payBrand"
                            class="text-xs font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1.5 mb-1.5">
                            <i class="fas fa-wallet text-purple-500"></i>
                            Tipo de Cuenta <span class="text-red-500">*</span>
                        </label>
                        <select id="payBrand"
                            class="form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-purple-500">
                            <option>Seleccione una Opción</option>
                            <option value="1">Cuenta de Ahorros</option>
                            <option value="2">Cuenta Corriente</option>
                            <option value="3">Cuenta a Plazo Fijo</option>
                        </select>
                    </div>

                    <!-- Número de cuenta -->
                    <div>
                        <label for="payNumber"
                            class="text-xs font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1.5 mb-1.5">
                            <i class="fas fa-hashtag text-purple-500"></i>
                            Número de Cuenta <span class="text-red-500">*</span>
                        </label>
                        <input id="payNumber" type="text" placeholder="Ej: 191-12345678-0-12"
                            class="form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-purple-500">
                    </div>

                    <!-- Número de CCI -->
                    <div>
                        <label for="cci"
                            class="text-xs font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1.5 mb-1.5">
                            <i class="fas fa-qrcode text-purple-500"></i>
                            Número de CCI <span class="text-red-500">*</span>
                        </label>
                        <input id="cci" type="text" placeholder="Ej: 00219112345678901234"
                            class="form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-purple-500">
                    </div>

                    <!-- Cuenta principal -->
                    <div>
                        <label for="principal"
                            class="text-xs font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1.5 mb-1.5">
                            <i class="fas fa-star text-purple-500"></i>
                            Cuenta Principal
                        </label>
                        <select id="principal"
                            class="form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-purple-500">
                            <option value="0">No</option>
                            <option value="1">Sí</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" id="saveBtn"
                        class="btn bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white border-none px-8 py-2.5 flex items-center gap-2 rounded-lg shadow-md hover:shadow-lg transition-all">
                        <i class="fas fa-save"></i>
                        Guardar Cuenta Bancaria
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
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Período</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Concepto</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Monto</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Boleta</th>
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
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
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
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pendiente
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-gray-400 text-xs">—</span>
                            </td>
                        </tr>
                        <!-- PAGADO - MES ANTERIOR -->
                        <tr>
                            <td class="px-4 py-3 font-medium">Quincena 2</td>
                            <td class="px-4 py-3 text-gray-600">30/03/2025</td>
                            <td class="px-4 py-3 text-gray-600">Marzo - Segunda quincena</td>
                            <td class="px-4 py-3 font-medium text-gray-900">S/ 1,200.00</td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Pagado
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="#" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                        <!-- PAGADO - MES ANTERIOR -->
                        <tr class="bg-gray-50/50 dark:bg-[#1a1f2e]/50">
                            <td class="px-4 py-3 font-medium">Quincena 1</td>
                            <td class="px-4 py-3 text-gray-600">15/03/2025</td>
                            <td class="px-4 py-3 text-gray-600">Marzo - Primera quincena</td>
                            <td class="px-4 py-3 font-medium text-gray-900">S/ 1,200.00</td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Pagado
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="#" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Resumen simple y navegación -->
            <div
                class="flex flex-wrap items-center justify-between gap-3 mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                <div class="text-xs text-gray-500">
                    Mostrando <span class="font-medium">4</span> de <span class="font-medium">12</span> pagos
                </div>
                <div class="flex items-center gap-2">
                    <button class="btn btn-sm btn-outline-secondary px-3 py-1" disabled>
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <span class="text-sm px-2">Página 1 de 3</span>
                    <button class="btn btn-sm btn-outline-secondary px-3 py-1">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
