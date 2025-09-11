 <x-layout.default>
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
        <h1 class="mt-3 text-2xl font-bold text-gray-900">Entradas de proveedores</h1>
        <p class="mt-1 text-gray-600">
            Este módulo te permite registrar el ingreso de <span class="font-medium">productos o repuestos</span> 
            provenientes de nuestros <span class="font-medium">proveedores autorizados</span>. 
            Podrás ingresar la <span class="font-medium">cantidad recibida</span> y asignar la 
            <span class="font-medium">ubicación de almacenamiento</span> correspondiente para mantener un control preciso del inventario.
        </p>
    </div>




         <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
             <!-- Sección izquierda - Búsqueda y productos -->
             <div class="lg:col-span-2">
                 <div class="panel mt-6 p-5 max-w-7xl mx-auto">
                    <h2 class="text-lg font-semibold mb-4">Búsqueda y selección de producto</h2>
                    <p class="text-gray-600 mb-6">
                        Ingresa el <strong>nombre</strong>, <strong>modelo</strong> o <strong>marca</strong> del producto o repuesto que deseas buscar.
                        El sistema mostrará todos los productos que coincidan con los criterios ingresados, para que puedas seleccionarlos fácilmente.
                    </p>



                     <div class="flex items-end gap-4 mb-8">
                         <!-- Input con ancho forzado -->
                         <div class="w-full">
                             <label class="block text-sm text-gray-500 mb-1">Nombre, modelo o marca</label>
                             <div class="relative">
                                 <i
                                     class="fa-solid fa-barcode absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                 <input type="text" class="clean-input w-full text-xl pl-9"
                                     placeholder="Nombre, modelo o marca" x-model="codigoBarras"
                                     @keyup.enter="abrirModalVerificacion" />
                             </div>
                         </div>


                         <!-- Botón fijo -->
                         <button
                             class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition"
                             @click="abrirModalVerificacion">
                             <i class="fas fa-check-circle"></i>
                             BUSCAR PRODUCTO
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
    <h2 class="text-lg font-semibold mb-4">Datos de la entrada</h2>

    <div class="space-y-4">

        <!-- Tipo de entrada -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de entrada <span class="text-red-500">*</span></label>
            <select class="clean-input w-full" x-model="tipoEntrada" required>
                <option value="">Seleccione un tipo</option>
                <option value="consignacion">Consignación</option>
                <option value="prestamo">Préstamo</option>
                <option value="muestra">Muestra</option>
                <option value="traslado">Traslado interno</option>
                <option value="marca_asociada">Ingreso por marca asociada</option>
                <option value="otro">Otro</option>
            </select>
        </div>

        <!-- Fecha de ingreso -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de ingreso <span class="text-red-500">*</span></label>
            <input type="date" class="clean-input w-full" x-model="fechaIngreso" required>
        </div>

        <!-- Marca (si aplica) -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Marca (opcional)</label>
            <input type="text" class="clean-input w-full" x-model="marca" placeholder="Ej. Xiaomi, TCL, etc.">
        </div>

        <!-- Responsable del ingreso -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Responsable del ingreso</label>
            <input type="text" class="clean-input w-full" x-model="responsable" placeholder="Nombre del encargado o usuario que registró">
        </div>

        <!-- Observaciones -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
            <textarea class="clean-input w-full" rows="3" x-model="observaciones" placeholder="Ej. Repuestos en consignación, sin factura."></textarea>
        </div>

        <!-- Adjuntar archivo (guía de remisión, acta, etc.) -->
        <div x-data="{
            file: null,
            fileName: '',
            fileSize: 0,
            over: false,
            onFileChange(e) {
                const f = e.target.files[0];
                if (!f) return this.clear();
                const max = 5 * 1024 * 1024;
                if (f.size > max) {
                    if (window.toastr) toastr.error('El archivo supera 5MB');
                    this.clear();
                    return;
                }
                this.file = f;
                this.fileName = f.name;
                this.fileSize = f.size;
            },
            clear() {
                this.file = null;
                this.fileName = '';
                this.fileSize = 0;
                $refs.fileInput.value = '';
            }
        }">
            <label class="block text-sm font-medium text-gray-700 mb-2">Adjuntar archivo (opcional)</label>
            <label @dragover.prevent="over=true" @dragleave="over=false" @drop.prevent="
                over=false;
                $refs.fileInput.files = $event.dataTransfer.files;
                onFileChange({ target: $refs.fileInput })
            " class="w-full cursor-pointer rounded-xl border-2 border-dashed border-gray-300 p-6 text-center transition hover:border-blue-400"
                :class="over ? 'border-blue-500 bg-blue-50/60' : ''">
                <div class="flex flex-col items-center gap-2 text-gray-600">
                    <i class="fas fa-cloud-upload-alt text-2xl"></i>
                    <div class="text-sm">
                        <span class="font-medium text-blue-600">Haz clic</span> o arrastra tu archivo aquí
                    </div>
                    <div class="text-xs text-gray-500">(pdf/doc/docx/jpg/jpeg/png) · máx 5MB</div>
                </div>
                <input x-ref="fileInput" type="file" class="sr-only" @change="onFileChange"
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
            </label>
            <template x-if="fileName">
                <div class="mt-3 flex items-center justify-between gap-3 rounded-lg border border-gray-200 p-3">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-alt text-gray-500"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-800" x-text="fileName"></p>
                            <p class="text-xs text-gray-500" x-text="(fileSize/1024).toFixed(1) + ' KB'"></p>
                        </div>
                    </div>
                    <button type="button" @click="clear()" class="text-sm text-red-600 hover:underline">Quitar</button>
                </div>
            </template>
        </div>

        <!-- Botón Guardar Entrada -->
        <div class="pt-4">
            <button class="w-full py-3 px-4 rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white"
                :disabled="guardandoEntrada"
                @click="guardarEntrada">
                <template x-if="guardandoEntrada">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </template>
                <template x-if="!guardandoEntrada">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7"></path>
                    </svg>
                </template>
                <span x-text="guardandoEntrada ? 'GUARDANDO...' : 'GUARDAR ENTRADA'"></span>
            </button>
        </div>
    </div>
</div>

         </div>

         <!-- MODAL -->
         <div x-show="modalAbierto" x-cloak class="fixed inset-0 bg-black/60 z-[999] overflow-y-auto" x-transition>
             <div class="flex items-start justify-center min-h-screen px-4" @click.self="cerrarModal">
                 <div x-show="modalType !== null" x-transition x-transition.duration.300
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
                         <!-- MODAL - Sección cuando producto existe -->
                         <!-- Loader flotante mientras se carga el modal -->
                         <div x-show="modalCargando" x-transition.opacity x-cloak
                             class="fixed inset-0 z-[1000] bg-black/60 flex items-center justify-center">
                             <div
                                 class="bg-white dark:bg-gray-900 px-6 py-4 rounded-lg shadow-md flex items-center gap-2">
                                 <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                     fill="none" viewBox="0 0 24 24">
                                     <circle class="opacity-25" cx="12" cy="12" r="10"
                                         stroke="currentColor" stroke-width="4"></circle>
                                     <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                 </svg>
                                 <span class="text-sm text-gray-700 dark:text-white">Cargando información del
                                     producto...</span>
                             </div>
                         </div>

                         <template x-if="modalType === 'existente' && !modalCargando">
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
                                                 <!-- CAMBIO: Usar productoEncontrado en lugar de nuevoProducto -->
                                                 <input type="text" class="clean-input"
                                                     :value="productoEncontrado.codigo_barras" readonly>
                                             </div>
                                         </div>
                                         <!-- Nombre -->
                                         <div>
                                             <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                                 Nombre
                                             </label>
                                             <div class="input-with-icon">
                                                 <i class="fas fa-cog input-icon"></i>
                                                 <!-- CAMBIO: Usar productoEncontrado en lugar de nuevoProducto -->
                                                 <input type="text" class="clean-input"
                                                     :value="productoEncontrado.nombre" readonly>
                                             </div>
                                         </div>
                                     </div>
                                 </div>

                                 <!-- En el modal, dentro de la sección "Información del producto" -->
                                 <div>
                                     <h4 class="text-base font-semibold mb-4 flex items-center gap-2">
                                         <i class="fas fa-box-open text-gray-600 dark:text-white"></i>
                                         Información del producto
                                     </h4>

                                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                         <div>
                                             <label class="block text-gray-600 text-sm mb-1">Cantidad a comprar<span
                                                     class="text-red-500 font-semibold">*</span></label>
                                             <div class="input-with-icon">
                                                 <i class="fas fa-cart-plus input-icon"></i>
                                                 <input type="number" class="clean-input" x-model="cantidadProducto"
                                                     min="1">
                                             </div>
                                         </div>

                                         <div>
                                             <label class="block text-gray-600 text-sm mb-1">Precio de compra<span
                                                     class="text-red-500 font-semibold">*</span></label>
                                             <div class="input-with-icon">
                                                 <i class="fas fa-money-bill-wave input-icon"></i>
                                                 <input type="number" step="0.01" class="clean-input"
                                                     x-model="precioCompra"
                                                     :class="{ 'border-red-500': mostrarErrorPrecio }">
                                             </div>
                                         </div>

                                         <!-- Campo de precio de venta con validación visual -->
                                         <div>
                                             <label class="block text-gray-600 text-sm mb-1">Precio de venta<span
                                                     class="text-red-500 font-semibold">*</span></label>
                                             <div class="input-with-icon">
                                                 <i class="fas fa-tags input-icon"></i>
                                                 <input type="number" step="0.01" class="clean-input"
                                                     x-model="productoEncontrado.precio_venta"
                                                     :class="{ 'border-red-500': mostrarErrorPrecio }"
                                                     @input="validarPrecios()">
                                             </div>
                                             <!-- Mostrar mensaje de error -->
                                             <div x-show="mostrarErrorPrecio" x-transition
                                                 class="error-msg text-red-500 text-xs mt-1">
                                                 <span x-text="errorPrecio"></span>
                                             </div>
                                         </div>


                                         <div style="display: none;">
                                             <label class="block text-gray-600 text-sm mb-1">Precio de venta por
                                                 mayoreo <span class="text-red-500 font-semibold">*</span></label>
                                             <div class="input-with-icon">
                                                 <i class="fas fa-hand-holding-usd input-icon"></i>
                                                 <input type="number" step="0.01" class="clean-input"
                                                     value="0.00">
                                             </div>
                                         </div>
                                     </div>

                                     <!-- Mensaje de error general (opcional, para errores más grandes) -->
                                     <div x-show="mostrarErrorPrecio" x-transition
                                         class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                                         <div class="flex">
                                             <div class="flex-shrink-0">
                                                 <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 20 20" fill="currentColor">
                                                     <path fill-rule="evenodd"
                                                         d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                         clip-rule="evenodd" />
                                                 </svg>
                                             </div>
                                             <div class="ml-3">
                                                 <h3 class="text-sm font-medium text-red-800">Error de validación</h3>
                                                 <div class="mt-2 text-sm text-red-700">
                                                     <span x-text="errorPrecio"></span>
                                                 </div>
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

                         <template x-if="modalType === 'nuevo' && !modalCargando">
                             <div class="p-6 space-y-8 mb-4">
                                 <!-- Encabezado mejorado -->
                                 <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-2">
                                     <div class="flex items-center gap-3">
                                         <div class="bg-red-100 p-2 rounded-full">
                                             <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round"
                                                     stroke-width="2"
                                                     d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                             </svg>
                                         </div>
                                         <div>
                                             <h4 class="text-lg font-semibold text-gray-800">Producto no encontrado
                                             </h4>
                                             <p class="text-sm text-red-600">Registrar nuevo producto en el sistema</p>
                                         </div>
                                     </div>
                                 </div>

                                 <!-- 1) CÓDIGO Y SKU -->
                                 <section>
                                     <header class="flex items-center gap-2 mb-4">
                                         <i class="fas fa-barcode text-gray-700"></i>
                                         <h3 class="text-lg font-semibold text-gray-800">Código y SKU</h3>
                                     </header>

                                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                         <!-- Código de Barras -->
                                         <div>
                                             <input type="text" class="clean-input"
                                                 x-model="nuevoProducto.codigo_barras" placeholder="Código de barras"
                                                 readonly>
                                         </div>

                                         <!-- SKU -->
                                         <div>
                                             <input type="text" class="clean-input" x-model="nuevoProducto.sku"
                                                 placeholder="SKU">
                                         </div>
                                     </div>
                                 </section>

                                 <!-- 2) INFORMACIÓN DEL PRODUCTO -->
                                 <section>
                                     <header class="flex items-center gap-2 mb-4">
                                         <i class="fas fa-box-open text-gray-700"></i>
                                         <h3 class="text-lg font-semibold text-gray-800">Información del producto</h3>
                                     </header>

                                     <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                         <!-- Nombre (full) -->
                                         <div>
                                             <label class="block text-sm font-medium text-gray-700 mb-2">
                                                 <i class="fas fa-cog mr-2"></i> Nombre
                                             </label>
                                             <input type="text" class="clean-input"
                                                 x-model="nuevoProducto.nombre">
                                         </div>

                                         <!-- Stock -->
                                         <div>
                                             <label class="block text-sm font-medium text-gray-700 mb-2">
                                                 <i class="fas fa-warehouse mr-2"></i> Stock o existencias
                                             </label>
                                             <input type="number" min="0" class="clean-input"
                                                 x-model="nuevoProducto.stock" @change="validarStock">
                                         </div>

                                         <!-- Stock mínimo -->
                                         <div>
                                             <label class="block text-sm font-medium text-gray-700 mb-2">
                                                 <i class="fas fa-layer-group mr-2"></i> Stock mínimo
                                             </label>
                                             <input type="number" min="0" class="clean-input"
                                                 x-model="nuevoProducto.stock_minimo" @change="validarStock">
                                         </div>
                                     </div>

                                     <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                                         <!-- Unidad de medida -->
                                         <div x-init="$nextTick(() => {
                                             const s = $el.querySelector('select');
                                             $(s).select2({ placeholder: 'Seleccione una opción', width: '100%', dropdownParent: s.closest('div') })
                                                 .on('change', e => nuevoProducto.unidad = e.target.value);
                                         })">
                                             <label class="block text-sm font-medium text-gray-700 mb-2">
                                                 <i class="fas fa-balance-scale mr-2"></i> Unidad de medida
                                             </label>
                                             <select class="clean-input w-full">
                                                 <option value="">Seleccione una opción</option>
                                                 <template x-for="u in unidades" :key="u.id">
                                                     <option :value="u.id" x-text="u.nombre"
                                                         :selected="u.id == nuevoProducto.unidad"></option>
                                                 </template>
                                             </select>
                                             <template x-if="cargandoUnidades">
                                                 <div class="text-xs text-gray-500 mt-2">Cargando unidades...</div>
                                             </template>
                                         </div>

                                         <!-- Modelo -->
                                         <div x-init="$nextTick(() => {
                                             const s = $el.querySelector('select');
                                             $(s).select2({ placeholder: 'Seleccione una opción', width: '100%', dropdownParent: s.closest('div') })
                                                 .on('change', e => nuevoProducto.modelo = e.target.value);
                                         })">
                                             <label class="block text-sm font-medium text-gray-700 mb-2">
                                                 <i class="fas fa-project-diagram mr-2"></i> Modelo
                                             </label>
                                             <select class="clean-input w-full">
                                                 <option value="">Seleccione una opción</option>
                                                 <template x-for="m in modelos" :key="m.id">
                                                     <option :value="m.id" x-text="m.nombre"
                                                         :selected="m.id == nuevoProducto.modelo"></option>
                                                 </template>
                                             </select>
                                             <template x-if="cargandoModelos">
                                                 <div class="text-xs text-gray-500 mt-2">Cargando modelos...</div>
                                             </template>
                                         </div>

                                         <!-- Garantía -->
                                         <div>
                                             <label class="block text-sm font-medium text-gray-700 mb-2">
                                                 <i class="fas fa-shield-alt mr-2"></i> Garantía de fábrica
                                             </label>
                                             <input type="number" min="0" class="clean-input"
                                                 x-model="nuevoProducto.garantia" placeholder="Tiempo de garantía">
                                         </div>

                                         <!-- Unidad de tiempo -->
                                         <div>
                                             <label class="block text-sm font-medium text-gray-700 mb-2">
                                                 <i class="fas fa-clock mr-2"></i> Unidad de tiempo
                                             </label>
                                             <select class="clean-input w-full"
                                                 x-model="nuevoProducto.unidad_tiempo_garantia">
                                                 <option value="">Seleccionar Unidad de tiempo</option>
                                                 <option value="dia">Dia</option>
                                                 <option value="dias">Días</option>
                                                 <option value="semanas">Semanas</option>
                                                <option value="semana">Semana</option>
                                                <option value="mes">Mes</option>
                                                <option value="meses">Meses</option>
                                                <option value="año">Año</option>
                                                <option value="años">Años</option>
                                             </select>
                                         </div>

                                         <!-- Peso -->
                                         <div>
                                             <label class="block text-sm font-medium text-gray-700 mb-2">
                                                 <i class="fas fa-weight mr-2"></i> Peso (kg)
                                             </label>
                                             <input type="number" step="0.01" class="clean-input"
                                                 x-model="nuevoProducto.peso">
                                         </div>
                                     </div>
                                 </section>

                                 <!-- 4) PRECIOS -->
                                 <section>
                                     <header class="flex items-center gap-2 mb-4">
                                         <i class="fas fa-money-bill-wave text-gray-700"></i>
                                         <h3 class="text-lg font-semibold text-gray-800">Precios</h3>
                                     </header>

                                   <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-10">
                                    <!-- Compra -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                            <i class="fas fa-money-bill-wave"></i> Precio de compra
                                        </label>
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="toggleMonedaCompra()"
                                                class="text-gray-500 px-2 h-10 border-b border-gray-300">
                                                <span x-text="monedaCompraActual?.simbolo || 'S/'"
                                                    class="w-8 text-center"></span>
                                            </button>
                                            <input type="number" step="0.01" class="clean-input"
                                                x-model="nuevoProducto.precio_compra">
                                            <input type="hidden" id="moneda_compra" name="moneda_compra"
                                                x-model="nuevoProducto.moneda_compra">
                                        </div>
                                    </div>

                                    <!-- Venta -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                            <i class="fas fa-tags"></i> Precio de venta
                                        </label>
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="toggleMonedaVenta()"
                                                class="text-gray-500 px-2 h-10 border-b border-gray-300">
                                                <span x-text="monedaVentaActual?.simbolo || 'S/'"
                                      
                             class="w-8 text-center"></span>
                                            </button>
                                            <input type="number" step="0.01" class="clean-input"
                                                x-model="nuevoProducto.precio_venta">
                                            <input type="hidden" id="moneda_venta" name="moneda_venta"
                                                x-model="nuevoProducto.moneda_venta">
                                        </div>
                                    </div>
                                </div>

                                 </section>
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


     </div>


     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

     <script src="{{ asset('assets/js/entradasproveedores/entradas.js') }}" defer></script>

 </x-layout.default>
