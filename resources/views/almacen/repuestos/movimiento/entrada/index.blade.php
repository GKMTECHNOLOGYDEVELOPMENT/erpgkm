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
        <div class="mb-8">
            <h1 class="text-2xl font-bold mb-2">REGISTRO DE REPUESTOS TCL - USO INTERNO</h1>
            <p class="text-gray-600">
                En este módulo podrá registrar repuestos TCL, ya sean nuevos o ya registrados en el sistema.
                También puede ver la lista de todos los repuestos registrados y buscar repuestos específicos.
            </p>

            <!-- Pestañas -->
            <div class="flex border-b border-gray-200 mt-6">
                <button class="px-4 py-2 font-medium text-blue-600 border-b-2 border-blue-600">NUEVO REGISTRO</button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sección izquierda - Búsqueda y repuestos -->
            <div class="lg:col-span-2">
                <div class="panel mt-6 p-5 max-w-7xl mx-auto">
                    <h2 class="text-lg font-semibold mb-4">Registro de Repuestos</h2>
                    <p class="text-gray-600 mb-6">
                        Ingrese el código de repuesto o código de barras y luego haga clic en "Verificar repuesto" para
                        cargar
                        los datos en caso el repuesto ya esté registrado, en caso contrario se cargará el formulario
                        para registrar un nuevo repuesto.
                    </p>

                    <div class="flex items-end gap-4 mb-8">
                        <!-- Input con ancho forzado -->
                        <div class="w-full">
                            <label class="block text-sm mb-1">Código de repuesto / Código de
                                barras</label>
                            <input type="text" class="clean-input w-full text-xl" placeholder="Código de repuesto"
                                x-model="codigoRepuesto" @keyup.enter="abrirModalVerificacion" />
                        </div>

                        <!-- Botón fijo -->
                        <button
                            class="shrink-0 bg-primary text-white px-4 py-2 rounded-md flex items-center gap-2 transition"
                            @click="abrirModalVerificacion">
                            <i class="fas fa-check-circle"></i>
                            VERIFICAR REPUESTO
                        </button>
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
                                        Sub Categoria</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Modelo</th> <!-- Nueva columna -->
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cantidad</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Precio</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subtotal</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Remover</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <template x-if="repuestos.length === 0">
                                    <tr>
                                        <td colspan="7"
                                            class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No hay repuestos agregados
                                        </td>
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
                <div class="panel mt-2 p-5 max-w-xl mx-auto sticky top-4">
                    <h2 class="text-lg font-semibold mb-4">DATOS DEL REGISTRO</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                            <input type="text" x-ref="fechaInput" x-model="fecha"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                                placeholder="Selecciona una fecha" />
                        </div>

                        <div x-data x-init="$('#proveedor-select').select2().on('change', function() {
                            $dispatch('input', this.value);
                        });">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Proveedor <span class="text-red-500">*</span>
                            </label>
                            <select id="proveedor-select" class="w-full" x-model="proveedorId">
                                <option value="">Seleccione una opción</option>
                                @foreach ($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Área <span
                                    class="text-red-500">*</span></label>
                            <select
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                                x-model="areaId">
                                <option value="">Seleccione una opción</option>
                                <template x-for="area in areas" :key="area.id">
                                    <option :value="area.id" x-text="area.nombre"></option>
                                </template>
                            </select>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-medium" x-text="formatCurrency(subtotal)"></span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">IGV (18%):</span>
                                <span class="font-medium" x-text="formatCurrency(itbis)"></span>
                            </div>
                            <div class="flex justify-between text-lg font-semibold">
                                <span class="text-gray-700">Total:</span>
                                <span class="text-blue-600" x-text="formatCurrency(total)"></span>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg"
                                :disabled="!puedeGuardar" :class="{ 'opacity-50 cursor-not-allowed': !puedeGuardar }"
                                @click="guardarRegistro">
                                GUARDAR REGISTRO
                            </button>
                        </div>

                        <p class="text-xs text-gray-500">
                            Los campos marcados con <span class="text-red-500">*</span> son obligatorios
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL -->
        <div x-show="modalAbierto" class="fixed inset-0 bg-black/60 z-[999] overflow-y-auto" x-transition
            style="display: none;">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="cerrarModal">
                <div x-show="open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-3xl">

                    <!-- Header -->
                    <div
                        class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-white/10">
                        <h5 class="font-semibold text-lg text-gray-800 dark:text-white">Agregar repuesto TCL</h5>
                        <button @click="cerrarModal" class="text-gray-500 hover:text-gray-800 dark:hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 text-sm text-gray-700 dark:text-white">
                        <template x-if="repuestoEncontrado">
                            <div class="space-y-8">
                                <!-- Código y nombre -->
                                <div>
                                    <h4 class="text-base font-semibold mb-4 flex items-center gap-2">
                                        <i class="fas fa-tags text-gray-600 dark:text-white"></i>
                                        Información del Repuesto
                                    </h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                                Código de Repuesto
                                            </label>
                                            <div class="input-with-icon">
                                                <i class="fas fa-barcode input-icon"></i>
                                                <input type="text" class="clean-input"
                                                    x-model="repuestoEncontrado.codigo_repuesto"
                                                    :value="codigoRepuesto" disabled>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                                Sub Categoria
                                            </label>
                                            <div class="input-with-icon">
                                                <i class="fas fa-cog input-icon"></i>
                                                <input type="text" class="clean-input"
                                                    x-model="repuestoEncontrado.subcategoria" disabled>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                                Modelo
                                            </label>
                                            <div class="input-with-icon">
                                                <i class="fas fa-project-diagram input-icon"></i>
                                                <input type="text" class="clean-input"
                                                    x-model="repuestoEncontrado.modelo" disabled>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                                Pulgadas
                                            </label>
                                            <div class="input-with-icon">
                                                <i class="fas fa-ruler input-icon"></i>
                                                <input type="text" class="clean-input"
                                                    x-model="repuestoEncontrado.pulgadas" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Información del repuesto -->
                                <div>
                                    <h4 class="text-base font-semibold mb-4 flex items-center gap-2">
                                        <i class="fas fa-box-open text-gray-600 dark:text-white"></i>
                                        Detalles del Repuesto
                                    </h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-gray-600 text-sm mb-1">Stock o existencias</label>
                                            <div class="input-with-icon">
                                                <i class="fas fa-boxes input-icon"></i>
                                                <input type="number" class="clean-input"
                                                    x-model="repuestoEncontrado.stock_total">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-gray-600 text-sm mb-1">Precio de compra (Con
                                                IGV)</label>
                                            <div class="input-with-icon">
                                                <i class="fas fa-money-bill-wave input-icon"></i>
                                                <input type="number" step="0.01" class="clean-input"
                                                    x-model="precioCompra" @change="actualizarPrecioCompra">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-gray-600 text-sm mb-1">Precio de venta (Con
                                                IGV)</label>
                                            <div class="input-with-icon">
                                                <i class="fas fa-tags input-icon"></i>
                                                <input type="number" step="0.01" class="clean-input"
                                                    x-model="repuestoEncontrado.precio_venta">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-gray-600 text-sm mb-1">Stock mínimo</label>
                                            <div class="input-with-icon">
                                                <i class="fas fa-exclamation-triangle input-icon"></i>
                                                <input type="number" class="clean-input"
                                                    x-model="repuestoEncontrado.stock_minimo">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-xs text-gray-500 mt-2">
                                    Los campos marcados con <span class="text-red-500 font-semibold">*</span> son
                                    obligatorios
                                </p>
                            </div>
                        </template>

                         <template x-if="!repuestoEncontrado && codigoRepuesto">
                    <!-- Contenido para nuevo repuesto -->
                    <div class="p-6 space-y-8">
                        <h4 class="text-base font-semibold mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Repuesto no encontrado: Registrar nuevo repuesto TCL
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Código de Repuesto -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <i class="fas fa-barcode"></i> Código de Repuesto TCL <span class="text-red-500">*</span>
                                </label>
                                <input type="text" class="clean-input" x-model="nuevoRepuesto.codigo_repuesto" :value="codigoRepuesto" required>
                            </div>

                            <!-- Código de Barras -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <i class="fas fa-barcode"></i> Código de Barras
                                </label>
                                <input type="text" class="clean-input" x-model="nuevoRepuesto.codigo_barras">
                            </div>

                            <!-- SKU -->
                            <div>
                                <label class="text-sm font-medium text-gray-700">SKU</label>
                                <input type="text" class="clean-input" x-model="nuevoRepuesto.sku">
                            </div>

                            <!-- Nombre -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <i class="fas fa-cog"></i> Nombre del Repuesto <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nombre" class="clean-input" x-model="nuevoRepuesto.nombre" required>
                            </div>

                            <!-- Modelos compatibles -->
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Modelos compatibles <span class="text-red-500">*</span></label>
                                <select id="modelosSelect" name="modelos[]" class="w-full" multiple="multiple">
                                    @foreach ($modelos as $modelo)
                                        <option value="{{ $modelo->idModelo }}">
                                            {{ $modelo->nombre }} - {{ $modelo->marca->nombre ?? 'Sin Marca' }} - {{ $modelo->categoria->nombre ?? 'Sin Categoria' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Pulgadas -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <i class="fas fa-ruler"></i> Pulgadas
                                </label>
                                <input type="text" class="clean-input" x-model="nuevoRepuesto.pulgadas">
                            </div>

                            <!-- Stock Total -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <i class="fas fa-boxes"></i> Stock Total <span class="text-red-500">*</span>
                                </label>
                                <input type="number" class="clean-input" x-model="nuevoRepuesto.stock_total" min="0" required>
                            </div>

                            <!-- Stock Mínimo -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <i class="fas fa-exclamation-triangle"></i> Stock Mínimo <span class="text-red-500">*</span>
                                </label>
                                <input type="number" class="clean-input" x-model="nuevoRepuesto.stock_minimo" min="0" required>
                            </div>

                            <!-- Precio Compra -->
                            <div>
                                <label class="text-sm font-medium text-gray-700">Precio de Compra <span class="text-red-500">*</span></label>
                                <div class="flex items-center gap-2">
                                    <span>S/</span>
                                    <input type="number" step="0.01" class="clean-input" x-model="nuevoRepuesto.precio_compra" min="0" required>
                                </div>
                            </div>

                            <!-- Precio Venta -->
                            <div>
                                <label class="text-sm font-medium text-gray-700">Precio de Venta <span class="text-red-500">*</span></label>
                                <div class="flex items-center gap-2">
                                    <span>S/</span>
                                    <input type="number" step="0.01" class="clean-input" x-model="nuevoRepuesto.precio_venta" min="0" required>
                                </div>
                            </div>

                         
                        </div>

                        <div class="pt-6 flex justify-end">
                            <button @click="guardarNuevoRepuesto" class="btn btn-primary">Guardar repuesto</button>
                        </div>
                    </div>
                </template>
                    </div>

                    <!-- Footer -->
                    <div
                        class="flex justify-end items-center gap-4 px-6 py-4 border-t border-gray-200 dark:border-white/10">
                        <button @click="cerrarModal" class="text-sm btn btn-outline-danger">CERRAR</button>
                        <button x-show="repuestoEncontrado" @click="agregarAlRegistro"
                            class="text-sm btn btn-primary flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            AGREGAR REPUESTO
                        </button>
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

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('repuestos', () => ({
                // Estado del componente
                codigoRepuesto: '',
                fecha: '',
                proveedorId: '',
                areaId: '',
                proveedores: [{
                        id: 1,
                        nombre: 'Proveedor A'
                    },
                    {
                        id: 2,
                        nombre: 'Proveedor B'
                    },
                    {
                        id: 3,
                        nombre: 'Proveedor C'
                    },
                ],
                areas: [{
                        id: 1,
                        nombre: 'Taller'
                    },
                    {
                        id: 2,
                        nombre: 'Almacén'
                    },
                    {
                        id: 3,
                        nombre: 'Soporte Técnico'
                    },
                ],
                modelos: [{
                        id: 1,
                        nombre: 'Modelo TCL 1'
                    },
                    {
                        id: 2,
                        nombre: 'Modelo TCL 2'
                    },
                    {
                        id: 3,
                        nombre: 'Modelo TCL 3'
                    },
                ],
                repuestos: [],
                modalAbierto: false,
                repuestoEncontrado: null,
                cantidadRepuesto: 1,
                precioCompra: 0,
                nuevoRepuesto: {
                    codigo_repuesto: '',
                    codigo_barras: '',
                    nombre: '',
                    stock_total: 0,
                    stock_minimo: 0,
                    precio_compra: 0,
                    precio_venta: 0,
                    idModelo: [], // <--- necesario para multiple
                    idsubcategoria: '',
                    sku: '',
                },

                modelos: @json($modelos),
                subcategorias: @json($subcategorias),
                areas: @json($areas),

                // Computadas
                get subtotal() {
                    return this.repuestos.reduce((sum, p) => sum + p.subtotal, 0);
                },

                get itbis() {
                    return this.subtotal * 0.18;
                },

                get total() {
                    return this.subtotal + this.itbis;
                },

                get puedeGuardar() {
                    return this.proveedorId && this.areaId && this.repuestos.length > 0;
                },

                // Métodos
                async abrirModalVerificacion() {
                    if (!this.codigoRepuesto) return;

                    try {
                        const response = await fetch(
                            `/buscar-repuesto?codigo=${this.codigoRepuesto}`);
                        const data = await response.json();

                        if (data.existe) {
                            this.repuestoEncontrado = {
                                id: data.articulo.idArticulos,
                                codigo_repuesto: data.articulo.codigo_repuesto,
                                codigo_barras: data.articulo.codigo_barras,
                                nombre: data
                                    .subcategoria, // Subcategoría mostrada como "nombre"
                                stock_total: data.articulo.stock_total,
                                stock_minimo: data.articulo.stock_minimo,
                                precio_compra: data.articulo.precio_compra,
                                precio_venta: data.articulo.precio_venta,
                                modelo: data.modelos.length ? data.modelos.join(' / ') :
                                '', // modelos separados por coma
                                pulgadas: data.articulo.pulgadas,
                                idModelo: data.articulo.idModelo,
                                subcategoria: data.subcategoria
                            };
                            this.precioCompra = data.articulo.precio_compra;
                        } else {
                            this.repuestoEncontrado = null;
                        }


                        this.modalAbierto = true;
                        this.cantidadRepuesto = 1;
                    } catch (error) {
                        console.error('Error al buscar repuesto:', error);
                        alert('Error al buscar el repuesto');
                    }
                },

                cerrarModal() {
                    this.modalAbierto = false;
                    this.repuestoEncontrado = null;
                },

                agregarAlRegistro() {
                    if (!this.repuestoEncontrado) return;

                    // Validación 1: Cantidad no puede ser menor o igual a cero
                    if (this.repuestoEncontrado.stock_total <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cantidad inválida',
                            text: 'La cantidad debe ser mayor que cero'
                        });
                        return;
                    }

                    // Validación 2: Precio de compra no puede ser mayor al precio de venta
                    if (parseFloat(this.precioCompra) > parseFloat(this.repuestoEncontrado
                            .precio_venta)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Precio inválido',
                            text: 'El precio de compra no puede ser mayor al precio de venta'
                        });
                        return;
                    }

                    // Validación 3: Precio de compra no puede ser negativo
                    if (this.precioCompra < 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Precio inválido',
                            text: 'El precio de compra no puede ser negativo'
                        });
                        return;
                    }

                    const nuevoRepuesto = {
                        id: this.repuestoEncontrado.id,
                        codigo_repuesto: this.repuestoEncontrado.codigo_repuesto,
                        codigo_barras: this.repuestoEncontrado.codigo_barras,
                        nombre: this.repuestoEncontrado.nombre,
                        cantidad: this.repuestoEncontrado.stock_total,
                        precio: this.precioCompra,
                        subtotal: this.repuestoEncontrado.stock_total * this.precioCompra,
                        stock_total: this.repuestoEncontrado.stock_total,
                        precio_venta: this.repuestoEncontrado.precio_venta,
                        modelo: this.repuestoEncontrado.modelo,
                        pulgadas: this.repuestoEncontrado.pulgadas
                    };

                    this.repuestos.push(nuevoRepuesto);

                    Swal.fire({
                        icon: 'success',
                        title: 'Repuesto agregado',
                        text: 'El repuesto fue agregado al registro correctamente',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    this.cerrarModal();
                    this.codigoRepuesto = '';
                },

                actualizarPrecioCompra() {
                    if (this.repuestoEncontrado) {
                        this.repuestoEncontrado.precio_compra = this.precioCompra;
                    }
                },

                actualizarSubtotal(repuesto) {
                    repuesto.subtotal = repuesto.cantidad * repuesto.precio;
                },

                removerRepuesto(index) {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: 'Este repuesto será eliminado del registro.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.repuestos.splice(index, 1);
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: 'El repuesto fue eliminado correctamente',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    });
                },


                formatCurrency(value) {
                    return 'S/ ' + value.toFixed(2);
                },

           async guardarNuevoRepuesto() {
            // Validación mejorada
            if (!this.nuevoRepuesto.codigo_repuesto) {
                Swal.fire({
                    icon: 'error',
                    title: 'Campo requerido',
                    text: 'El código de repuesto es obligatorio'
                });
                return;
            }

            if (!this.nuevoRepuesto.nombre) {
                Swal.fire({
                    icon: 'error',
                    title: 'Campo requerido',
                    text: 'El nombre del repuesto es obligatorio'
                });
                return;
            }

            // Obtener modelos seleccionados directamente de Select2
            const modelosSeleccionados = $('#modelosSelect').val();
            if (!modelosSeleccionados || modelosSeleccionados.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Campo requerido',
                    text: 'Debe seleccionar al menos un modelo compatible'
                });
                return;
            }

            // Validar precios
            if (parseFloat(this.nuevoRepuesto.precio_compra) > parseFloat(this.nuevoRepuesto.precio_venta)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Precios inválidos',
                    text: 'El precio de compra no puede ser mayor al precio de venta'
                });
                return;
            }

            try {
                const response = await fetch('/articulosmodal', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        ...this.nuevoRepuesto,
                        idModelo: modelosSeleccionados,
                        stock_total: parseInt(this.nuevoRepuesto.stock_total),
                        stock_minimo: parseInt(this.nuevoRepuesto.stock_minimo),
                        precio_compra: parseFloat(this.nuevoRepuesto.precio_compra),
                        precio_venta: parseFloat(this.nuevoRepuesto.precio_venta)
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Error al guardar el repuesto');
                }

                if (data.success) {
                    // Obtener nombres de los modelos seleccionados
                    const nombresModelos = this.modelos
                        .filter(m => modelosSeleccionados.includes(m.idModelo.toString()))
                        .map(m => m.nombre)
                        .join(', ');

                    // Agregar el nuevo repuesto a la lista
                    this.repuestos.push({
                        id: data.repuesto.idArticulos,
                        codigo_repuesto: data.repuesto.codigo_repuesto,
                        codigo_barras: data.repuesto.codigo_barras,
                        nombre: data.repuesto.nombre,
                        cantidad: data.repuesto.stock_total,
                        precio: data.repuesto.precio_compra,
                        subtotal: data.repuesto.stock_total * data.repuesto.precio_compra,
                        stock_total: data.repuesto.stock_total,
                        precio_venta: data.repuesto.precio_venta,
                        modelo: nombresModelos,
                        pulgadas: data.repuesto.pulgadas
                    });

                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Repuesto guardado correctamente',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Resetear el formulario
                    this.nuevoRepuesto = {
                        codigo_repuesto: '',
                        codigo_barras: '',
                        nombre: '',
                        stock_total: 0,
                        stock_minimo: 0,
                        precio_compra: 0,
                        precio_venta: 0,
                        idModelo: [],
                        pulgadas: '',
                        sku: '',
                        idsubcategoria: 1
                    };

                    // Resetear Select2
                    $('#modelosSelect').val(null).trigger('change');

                    this.cerrarModal();
                }
            } catch (error) {
                console.error('Error al guardar repuesto:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Ocurrió un error al guardar el repuesto'
                });
            }
        },
                guardarRegistro() {
                    if (!this.puedeGuardar) return;

                    const registroData = {
                        fecha: this.fecha,
                        proveedor_id: this.proveedorId,
                        repuestos: this.repuestos,
                        subtotal: this.subtotal,
                        itbis: this.itbis,
                        total: this.total
                    };

                    // Aquí iría la llamada AJAX para guardar en backend
                    console.log('Datos a enviar:', registroData);
                    alert('Registro de repuestos guardado exitosamente');

                    // Reset
                    this.repuestos = [];
                    this.proveedorId = '';
                },

                init() {
                    flatpickr(this.$refs.fechaInput, {
                        defaultDate: new Date(),
                        dateFormat: 'Y-m-d',
                        onChange: (selectedDates, dateStr) => {
                            this.fecha = dateStr;
                        },
                    });

                    // Set fecha inicial
                    this.fecha = new Date().toISOString().split('T')[0];
                }
            }));
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#modelosSelect').select2({
                placeholder: "Seleccione modelos compatibles",
                allowClear: true,
                templateResult: function(modelo) {
                    if (!modelo.id) return modelo.text;

                    var text = modelo.text;
                    // Extraemos las partes
                    var parts = text.split(' - ');
                    // Formateamos como en tu imagen
                    return $('<span>').text(parts[0] + ' - ' + parts[1] + ' - ' + parts[2]);
                },
                templateSelection: function(modelo) {
                    if (!modelo.id) return modelo.text;

                    // Solo mostrar el nombre del modelo en la selección
                    var text = modelo.text;
                    var parts = text.split(' - ');
                    return parts[0];
                },
                escapeMarkup: function(m) {
                    return m;
                }
            });
        });
    </script>
</x-layout.default>
