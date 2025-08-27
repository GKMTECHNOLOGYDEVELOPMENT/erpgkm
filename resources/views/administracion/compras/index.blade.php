<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .clean-input {
            border: none;
            border-bottom: 1px solid #e0e6ed;
            border-radius: 0;
            padding-left: 35px;
            /* asegúrate de dejar espacio al ícono */
            padding-bottom: 8px;
            padding-top: 8px;
            background-color: transparent;
            height: 40px;
            /* controla la altura si es necesario */
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

        /* Estilos para inputs con íconos */
        .input-with-icon {
            position: relative;
            margin-bottom: 1.5rem;
            /* Espacio para mensajes de error */
        }

        .input-with-icon .clean-input {
            padding-left: 35px !important;
            /* Forzar espacio para el ícono */
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

        /* Estilos para mensajes de error */
        .error-msg,
        .error-msg-duplicado {
            position: absolute;
            bottom: -1.25rem;
            left: 0;
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        /* Estilos para campos inválidos */
        .border-red-500 {
            border-color: #ef4444 !important;
        }

        .clean-input::placeholder {
            font-size: 0.85rem;
            /* o 0.75rem si lo quieres aún más pequeño */
        }
    </style>

    <div class="container mx-auto px-4 py-6" x-data="compra">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">NUEVA COMPRA</h1>
            <p class="text-gray-600">
                En el módulo COMPRAS usted podrá registrar compras de productos ya sea nuevos o ya registrados en
                sistema.
                También puede ver la lista de todas las compras realizadas, buscar compras y ver información más
                detallada de cada compra.
            </p>

            <!-- Pestañas -->
            <div class="flex border-b border-gray-200 mt-6">
                <button class="px-4 py-2 font-medium text-blue-600 border-b-2 border-blue-600">NUEVA COMPRA</button>
                <button class="px-4 py-2 font-medium text-gray-500 hover:text-blue-500">COMPRAS REALIZADAS</button>
                <button class="px-4 py-2 font-medium text-gray-500 hover:text-blue-500">BUSCAR COMPRA</button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sección izquierda - Búsqueda y productos -->
            <div class="lg:col-span-2">
                <div class="panel mt-6 p-5 max-w-7xl mx-auto">
                    <h2 class="text-lg font-semibold mb-4">Registro de Productos</h2>
                    <p class="text-gray-600 mb-6">
                        Ingrese el código de barras del producto y luego haga clic en "Verificar producto" para cargar
                        los datos en caso el producto ya esté registrado,
                        en caso contrario se cargará el formulario para registrar un nuevo producto.
                    </p>

                    <div class="flex items-end gap-4 mb-8">
                        <!-- Input con ancho forzado -->
                        <div class="w-full">
                            <label class="block text-sm text-gray-500 mb-1">Código de barras</label>
                            <input type="text" class="clean-input w-full text-xl" placeholder="Código de barras"
                                x-model="codigoBarras" @keyup.enter="abrirModalVerificacion" />
                        </div>

                        <!-- Botón fijo -->
                        <button
                            class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition"
                            @click="abrirModalVerificacion">
                            <i class="fas fa-check-circle"></i>
                            VERIFICAR PRODUCTO
                        </button>
                    </div>


                    <!-- Tabla de productos -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        #</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Código de barras</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Producto</th>
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
                                <template x-if="productos.length === 0">
                                    <tr>
                                        <td colspan="7"
                                            class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No hay productos agregados
                                        </td>
                                    </tr>
                                </template>

                                <template x-for="(producto, index) in productos" :key="producto.id">
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="px-4 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100"
                                            x-text="index + 1"></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100"
                                            x-text="producto.codigo_barras"></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100"
                                            x-text="producto.nombre"></td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <input type="number"
                                                class="w-20 border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-center bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                                                x-model="producto.cantidad" @change="actualizarSubtotal(producto)"
                                                min="1">
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <input type="number" step="0.01"
                                                class="w-24 border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-right bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                                                x-model="producto.precio" @change="actualizarSubtotal(producto)">
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right text-gray-800 dark:text-gray-100"
                                            x-text="formatCurrency(producto.subtotal)">
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <button
                                                class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                                @click="removerProducto(index)">
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

            <!-- Sección derecha - Datos de la compra -->
            <div class="lg:col-span-1">
                <div class="panel mt-2 p-5 max-w-xl mx-auto sticky top-4">
                    <h2 class="text-lg font-semibold mb-4">DATOS DE LA COMPRA</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                            <input type="text" x-ref="fechaInput" x-model="fecha"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                                placeholder="Selecciona una fecha" />

                        </div>


                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor <span
                                    class="text-red-500">*</span></label>
                            <select
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                                x-model="proveedorId">
                                <option value="">Seleccione una opción</option>
                                <template x-for="proveedor in proveedores" :key="proveedor.id">
                                    <option :value="proveedor.id" x-text="proveedor.nombre"></option>
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
                                @click="guardarCompra">
                                GUARDAR COMPRA
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
                        <h5 class="font-semibold text-lg text-gray-800 dark:text-white">Agregar producto a compra</h5>
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
                        <template x-if="productoEncontrado">
                            <div class="space-y-8">

                                <!-- Código y nombre -->
                                <div>
                                    <h4 class="text-base font-semibold mb-4 flex items-center gap-2">
                                        <i class="fas fa-tags text-gray-600 dark:text-white"></i>

                                        Código y Nombre
                                    </h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                                Código de Barras
                                            </label>
                                            <div class="input-with-icon">
                                                <i class="fas fa-barcode input-icon"></i>
                                                <input type="text" class="clean-input"
                                                    x-model="nuevoProducto.codigo_barras" :value="codigoBarras">
                                            </div>
                                        </div>
                                        <!-- Nombre -->
                                        <div>
                                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                                Nombre
                                            </label>
                                            <div class="input-with-icon">
                                                <i class="fas fa-cog input-icon"></i>
                                                <input type="text" class="clean-input"
                                                    x-model="nuevoProducto.nombre">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Información del producto -->
                                <div>
                                    <h4 class="text-base font-semibold mb-4 flex items-center gap-2">
                                        <i class="fas fa-box-open text-gray-600 dark:text-white"></i>
                                        Información del producto
                                    </h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-gray-600 text-sm mb-1">Stock o existencias
                                                compradas</label>
                                            <div class="input-with-icon">
                                                <i class="fas fa-boxes input-icon"></i>
                                                <input type="number" class="clean-input"
                                                    x-model="nuevoProducto.stock">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-gray-600 text-sm mb-1">Precio de compra (Con
                                                impuesto incluido)</label>
                                            <div class="input-with-icon">
                                                <i class="fas fa-money-bill-wave input-icon"></i>
                                                <input type="number" step="0.01" class="clean-input"
                                                    x-model="precioCompra">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-gray-600 text-sm mb-1">Precio de venta (Con
                                                impuesto incluido)</label>
                                            <div class="input-with-icon">
                                                <i class="fas fa-tags input-icon"></i>
                                                <input type="number" step="0.01" class="clean-input"
                                                    x-model="productoEncontrado.precio_venta">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-gray-600 text-sm mb-1">Precio de venta por mayoreo
                                                (Con impuesto incluido)</label>
                                            <div class="input-with-icon">
                                                <i class="fas fa-hand-holding-usd input-icon"></i>
                                                <input type="number" step="0.01" class="clean-input"
                                                    value="0.00">
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

                        <template x-if="!productoEncontrado && codigoBarras">
                            <div class="p-6 space-y-8">
                                <h4 class="text-base font-semibold mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Producto no encontrado: Registrar nuevo producto
                                </h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Código de Barras -->
                                    <div>
                                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                            <i class="fas fa-barcode"></i> Código de Barras
                                        </label>
                                        <input type="text" class="clean-input"
                                            x-model="nuevoProducto.codigo_barras" :value="codigoBarras">
                                    </div>

                                    <!-- SKU -->
                                    <div>
                                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                            <i class="fas fa-tag"></i> SKU
                                        </label>
                                        <input type="text" class="clean-input" x-model="nuevoProducto.sku">
                                    </div>

                                    <!-- Nombre -->
                                    <div>
                                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                            <i class="fas fa-cog"></i> Nombre
                                        </label>
                                        <input type="text" class="clean-input" x-model="nuevoProducto.nombre">
                                    </div>

                                    <!-- Stock Total -->
                                    <div>
                                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                            <i class="fas fa-boxes"></i> Stock Total
                                        </label>
                                        <input type="number" class="clean-input" x-model="nuevoProducto.stock">
                                    </div>

                                    <!-- Stock Mínimo -->
                                    <div>
                                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                            <i class="fas fa-database"></i> Stock Mínimo
                                        </label>
                                        <input type="number" class="clean-input"
                                            x-model="nuevoProducto.stock_minimo">
                                    </div>

                                    <!-- Unidad de Medida -->
                                    <div>
                                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                            <i class="fas fa-balance-scale"></i> Unidad de Medida
                                        </label>
                                        <select class="clean-input" x-model="nuevoProducto.unidad">
                                            <option value="">Seleccione una opción</option>
                                            <template x-for="unidad in unidades" :key="unidad.id">
                                                <option :value="unidad.id" x-text="unidad.nombre"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- Modelo -->
                                    <div>
                                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                            <i class="fas fa-project-diagram"></i> Modelo
                                        </label>
                                        <select class="clean-input" x-model="nuevoProducto.modelo">
                                            <option value="">Seleccione una opción</option>
                                            <template x-for="modelo in modelos" :key="modelo.id">
                                                <option :value="modelo.id" x-text="modelo.nombre"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- Peso -->
                                    <div>
                                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                            <i class="fas fa-weight"></i> Peso (kg)
                                        </label>
                                        <input type="number" step="0.01" class="clean-input"
                                            x-model="nuevoProducto.peso">
                                    </div>

                                    <!-- Precio Compra -->
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Precio de Compra</label>
                                        <div class="flex items-center gap-2">
                                            <span>S/</span>
                                            <input type="number" step="0.01" class="clean-input"
                                                x-model="nuevoProducto.precio_compra">
                                        </div>
                                    </div>

                                    <!-- Precio Venta -->
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Precio de Venta</label>
                                        <div class="flex items-center gap-2">
                                            <span>S/</span>
                                            <input type="number" step="0.01" class="clean-input"
                                                x-model="nuevoProducto.precio_venta">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-6 flex justify-end">
                                    <button @click="guardarNuevoProducto" class="btn btn-primary">Guardar
                                        producto</button>
                                </div>
                            </div>
                        </template>

                    </div>

                    <!-- Footer -->
                    <div
                        class="flex justify-end items-center gap-4 px-6 py-4 border-t border-gray-200 dark:border-white/10">
                        <button @click="cerrarModal" class="text-sm btn btn-outline-danger">CERRAR</button>
                        <button x-show="productoEncontrado" @click="agregarAlCarrito"
                            class="text-sm btn btn-primary flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            AGREGAR PRODUCTO
                        </button>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <script src="{{ asset('assets/js/compras/compras.js') }}" defer></script>

</x-layout.default>
