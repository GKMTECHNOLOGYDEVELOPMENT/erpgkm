<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Head (CSS de Select2) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .clean-input {
            border: none;
            border-bottom: 1px solid #e0e6ed;
            border-radius: 0;
            padding-left: 35px;
            padding-bottom: 8px;
            padding-top: 8px;
            background-color: transparent;
            height: 40px;
            line-height: 1.25rem;
            font-size: 0.875rem;
        }

        .clean-input:focus {
            border-bottom: 2px solid #3b82f6;
            box-shadow: none;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 14px;
            pointer-events: none;
            z-index: 10;
        }

        .select2-container--default .select2-selection--single {
            background-color: transparent !important;
            border: none !important;
            border-bottom: 1px solid #e0e6ed !important;
            border-radius: 0 !important;
            height: 40px !important;
            padding-left: 35px !important;
            display: flex;
            align-items: center;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-bottom: 2px solid #3b82f6;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 6px !important;
            right: 10px !important;
        }

        .file-input-label {
            display: block;
            margin-top: 5px;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .input-with-icon {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-with-icon .clean-input {
            padding-left: 35px !important;
        }

        .input-with-icon .input-icon {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            z-index: 10;
            pointer-events: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: inherit !important;
        }

        .error-msg,
        .error-msg-duplicado {
            position: absolute;
            bottom: -1.25rem;
            left: 0;
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .border-red-500 {
            border-color: #ef4444 !important;
        }

        .clean-input::placeholder {
            font-size: 0.85rem;
        }

        /* 🔹 Elimina borde exterior del contenedor select2 */
        .select2-container {
            border: none !important;
            outline: none !important;
        }

        /* 🔹 Elimina bordes internos del contenedor activo */
        .select2-container--default .select2-selection--multiple {
            border: none !important;
            border-bottom: 1px solid #e0e6ed !important;
            box-shadow: none !important;
        }

        /* 🔹 En foco */
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-bottom: 2px solid #3b82f6 !important;
            box-shadow: none !important;
        }

        /* 🔹 Elimina borde al hacer clic */
        .select2-container--default .select2-selection--multiple:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        /* 🔹 Elimina scroll doble en selección */
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            scrollbar-width: none;
            /* Firefox */
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar {
            display: none;
            /* Chrome */
        }

        .select2-container--default .select2-selection--multiple {
            background-color: transparent;
            border: none;
            border-bottom: 1px solid #e0e6ed;
            border-radius: 0;
            padding-left: 35px;
            min-height: 40px;
            max-height: 90px;
            /* evita que se agrande */
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-bottom: 2px solid #3b82f6;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin: 0;
            padding: 4px 0;
            max-height: 80px;
            overflow-y: auto;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #3b82f6;
            border: none;
            /* elimina borde */
            color: white;
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 0.75rem;
            margin-top: 4px;
            box-shadow: none;
        }

        .select2-selection__choice__remove {
            border-right: none !important;
        }
    </style>

    <div class="container mx-auto px-4 py-6" x-data="repuestos">
        <!-- Encabezado -->
        <div class="panel max-w-7xl mx-auto border border-primary rounded-xl">
            <h1 class="text-3xl font-extrabold text-blue-700 flex items-center gap-3 mb-4">
                <i class="fas fa-box-open text-blue-600 text-2xl"></i>
                SALIDAS - USO INTERNO
            </h1>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-5 shadow-sm text-gray-700 leading-relaxed mb-6">
                <p class="mb-2">
                    Para agregar productos, digite el <strong class="text-blue-700">código de barras</strong> en el
                    campo
                    <span class="font-medium">"Código de producto"</span> y presione
                    <strong class="text-blue-600">Agregar producto</strong> o la tecla
                    <kbd class="px-1 py-0.5 bg-gray-200 rounded text-sm shadow-sm">Enter</kbd>. También puede usar la
                    opción
                    <strong class="text-blue-600">Buscar producto</strong>.
                </p>
                <p>
                    Si utiliza un lector de códigos de barras, conéctelo a su computadora, seleccione el campo
                    mencionado y
                    <span class="font-semibold text-blue-700">escanee el código directamente</span>.
                </p>
            </div>

            <!-- Pestañas -->
            <div class="flex border-b border-gray-200">
                <button class="px-6 py-2 text-sm font-semibold bg-primary text-white rounded-t-md transition">
                    NUEVO REGISTRO
                </button>
            </div>
        </div>



        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sección izquierda - Búsqueda y repuestos -->
            <div class="lg:col-span-2">
                <div class="panel mt-6 p-5 max-w-7xl mx-auto border border-primary rounded-xl">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-blue-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-tools text-blue-500"></i>
                            Registro de Repuestos
                        </h2>
                        <p
                            class="text-gray-600 leading-relaxed bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md shadow-sm">
                            Ingrese el <strong>código de repuesto</strong> o <strong>código de barras</strong> y luego
                            haga clic en
                            <span class="font-medium text-blue-600">"Verificar repuesto"</span> para cargar los datos si
                            ya está registrado.
                            Si no existe, se mostrará el formulario para registrar uno nuevo.
                        </p>
                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-3 items-end gap-4 mb-8">
                        <!-- Botones con separación -->
                        <div class="flex flex-col space-y-4 md:col-span-1">
                            <button type="button" @click="abrirModalBusqueda"
                                class="btn btn-sm btn-secondary w-full flex items-center justify-center gap-2">
                                <i class="fas fa-search"></i>
                                BUSCAR
                            </button>
                            <button type="button" @click="abrirModalVerificacion"
                                class="btn btn-sm btn-primary w-full flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                AGREGAR
                            </button>
                        </div>



                        <!-- Input ancho completo -->
                        <div class="md:col-span-2">
                            <label class="block text-sm mb-1">Código de producto</label>
                            <input type="text" class="clean-input w-full text-xl" placeholder="Código de producto"
                                x-model="codigoRepuesto" @keyup.enter="abrirModalVerificacion" />
                        </div>
                    </div>





                    <!-- Tabla de repuestos -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        #</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Código</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Producto</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cant</th> <!-- Nueva columna -->
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Precio</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subtotal</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center gap-1">
                                            Cantidad
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 6h16M4 12h16M4 18h16" />
                                            </svg>
                                        </div>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center gap-1">
                                            Precio
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3m-3-9v2m0 16v2m-4-4h8" />
                                            </svg>
                                        </div>
                                    </th>

                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Remover</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <template x-if="repuestos.length === 0">
                                    <tr>
                                        <td colspan="9" class="text-center font-semibold text-gray-600 py-4">No hay
                                            productos agregados</td>
                                    </tr>
                                </template>

                                <template x-for="(repuesto, index) in repuestos" :key="repuesto.id">
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="px-4 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100"
                                            x-text="index + 1"></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100"
                                            x-text="repuesto.codigo_repuesto || repuesto.codigo_barras"></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100"
                                            x-text="repuesto.nombre"></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100"
                                            x-text="repuesto.modelo"></td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <input type="number"
                                                class="w-20 border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-center bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                                                x-model="repuesto.cantidad" @change="actualizarSubtotal(repuesto)"
                                                min="1">
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <input type="number" step="0.01"
                                                class="w-24 border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-right bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                                                x-model="repuesto.precio" @change="actualizarSubtotal(repuesto)">
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right text-gray-800 dark:text-gray-100"
                                            x-text="formatCurrency(repuesto.subtotal)">
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <button
                                                class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                                @click="removerRepuesto(index)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sección derecha - Datos del registro -->
            <div class="lg:col-span-1">
                <div class="panel mt-2 p-5 max-w-xl mx-auto sticky top-4 border border-primary rounded-xl">
                    <h2 class="text-xl font-semibold mb-4 text-center border-b pb-2">DATOS DE LA VENTA</h2>

                    <div class="space-y-5 text-sm text-gray-700">
                        <!-- Fecha -->
                        <div>
                            <label class="block mb-1">Fecha</label>
                            <input type="text" x-ref="fechaInput" x-model="fecha" class="form-input" />
                        </div>

                        <!-- Caja -->
                        <div>
                            <label class="block mb-1">Caja</label>
                            <input type="text" value="Caja #1 - Caja principal" readonly class="form-input" />
                        </div>

                        <!-- Cliente-->
                        <div>
                            <label class="block mb-1 font-medium text-gray-800">Cliente</label>
                            <div class="flex">
                                <input type="text" x-model="clienteNombre" value="Público General" readonly class="form-input bg-gray-100" />


                                <button type="button"
                                    class="bg-gray-200 hover:bg-gray-300 flex items-center justify-center ltr:rounded-r-md rtl:rounded-l-md px-4 border border-l-0 border-gray-300 text-blue-600 hover:text-blue-800 transition">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Tipo de venta -->
                        <div>
                            <label class="block mb-1">Tipo de venta</label>
                            <div class="flex items-center gap-4">
                                <label class="inline-flex items-center gap-2">
                                    <input type="radio" x-model="tipoVenta" value="contado" />
                                    <span>Contado</span>
                                    <i class="fas fa-money-bill-wave text-blue-500"></i>
                                </label>
                                <label class="inline-flex items-center gap-2">
                                    <input type="radio" x-model="tipoVenta" value="credito" />
                                    <span>Crédito</span>
                                    <i class="fas fa-credit-card text-blue-500"></i>
                                </label>
                            </div>
                        </div>

                        <!-- Descuento -->
                        <div>
                            <label class="block mb-1 font-medium text-gray-800">Descuento de venta (%)</label>
                            <div class="flex">
                                <input type="number" x-model="descuento" class="form-input" />

                                <button type="button"
                                    class="bg-gray-200 hover:bg-gray-300 flex items-center justify-center ltr:rounded-r-md rtl:rounded-l-md px-4 border border-l-0 border-gray-300 text-blue-600 hover:text-blue-800 transition">
                                    <i class="fas fa-money-check-alt"></i>
                                </button>
                            </div>
                        </div>


                        <!-- Total pagado -->
                        <div>
                            <label class="block mb-1">Total pagado por cliente</label>
                            <input type="number" class="form-input" />
                        </div>

                        <!-- Cambio -->
                        <div>
                            <label class="block mb-1">Cambio devuelto al cliente</label>
                            <input type="number" readonly value="0.00" class="form-input bg-gray-100" />
                        </div>

                        <!-- Comentarios -->
                        <div>
                            <label class="block mb-1 text-gray-500">Comentarios</label>
                            <textarea class="form-input h-20"></textarea>
                        </div>

                        <!-- Totales -->
                        <div class="border-t pt-4 text-sm">
                            <div class="flex justify-between mb-1">
                                <span>Subtotal</span>
                                <span>+ $0.00 USD</span>
                            </div>
                            <div class="flex justify-between mb-1">
                                <span>ITBITS (18%)</span>
                                <span>+ $0.00 USD</span>
                            </div>
                            <div class="flex justify-between font-semibold">
                                <span>Descuento</span>
                                <span>- $0.00 USD</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer (JS de Select2 y jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/js/repuesto-movimiento/salida.js') }}"></script>
    @include('almacen.repuestos.movimiento.salida.modals-salida')



</x-layout.default>
