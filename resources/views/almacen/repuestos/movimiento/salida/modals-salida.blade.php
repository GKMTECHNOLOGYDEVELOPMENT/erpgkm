        
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
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
                                                    x-model="repuestoEncontrado.codigo_repuesto" :value="codigoRepuesto"
                                                    disabled>
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
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Repuesto no encontrado: Registrar nuevo repuesto TCL
                                </h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Código de Repuesto -->
                                    <div>
                                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                            <i class="fas fa-barcode"></i> Código de Repuesto TCL <span
                                                class="text-red-500">*</span>
                                        </label>
                                        <input type="text" class="clean-input"
                                            x-model="nuevoRepuesto.codigo_repuesto" :value="codigoRepuesto" required>
                                    </div>

                                    <!-- Código de Barras -->
                                    <div>
                                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                            <i class="fas fa-barcode"></i> Código de Barras
                                        </label>
                                        <input type="text" class="clean-input"
                                            x-model="nuevoRepuesto.codigo_barras">
                                    </div>

                                    <!-- SKU -->
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">SKU</label>
                                        <input type="text" class="clean-input" x-model="nuevoRepuesto.sku">
                                    </div>

                                    <!-- Nombre -->
                                    <div>
                                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                            <i class="fas fa-cog"></i> Nombre del Repuesto <span
                                                class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="nombre" class="clean-input"
                                            x-model="nuevoRepuesto.nombre" required>
                                    </div>

                                    <!-- Modelos compatibles -->
                                    <div class="col-span-1 md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Modelos compatibles
                                            <span class="text-red-500">*</span></label>
                                        <select id="modelosSelect" name="modelos[]" class="w-full"
                                            multiple="multiple">
                                            @foreach ($modelos as $modelo)
                                                <option value="{{ $modelo->idModelo }}">
                                                    {{ $modelo->nombre }} - {{ $modelo->marca->nombre ?? 'Sin Marca' }}
                                                    - {{ $modelo->categoria->nombre ?? 'Sin Categoria' }}
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
                                            <i class="fas fa-boxes"></i> Stock Total <span
                                                class="text-red-500">*</span>
                                        </label>
                                        <input type="number" class="clean-input" x-model="nuevoRepuesto.stock_total"
                                            min="0" required>
                                    </div>

                                    <!-- Stock Mínimo -->
                                    <div>
                                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                            <i class="fas fa-exclamation-triangle"></i> Stock Mínimo <span
                                                class="text-red-500">*</span>
                                        </label>
                                        <input type="number" class="clean-input"
                                            x-model="nuevoRepuesto.stock_minimo" min="0" required>
                                    </div>

                                    <!-- Precio Compra -->
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Precio de Compra <span
                                                class="text-red-500">*</span></label>
                                        <div class="flex items-center gap-2">
                                            <span>S/</span>
                                            <input type="number" step="0.01" class="clean-input"
                                                x-model="nuevoRepuesto.precio_compra" min="0" required>
                                        </div>
                                    </div>

                                    <!-- Precio Venta -->
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Precio de Venta <span
                                                class="text-red-500">*</span></label>
                                        <div class="flex items-center gap-2">
                                            <span>S/</span>
                                            <input type="number" step="0.01" class="clean-input"
                                                x-model="nuevoRepuesto.precio_venta" min="0" required>
                                        </div>
                                    </div>


                                </div>

                                <div class="pt-6 flex justify-end">
                                    <button @click="guardarNuevoRepuesto" class="btn btn-primary">Guardar
                                        repuesto</button>
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
