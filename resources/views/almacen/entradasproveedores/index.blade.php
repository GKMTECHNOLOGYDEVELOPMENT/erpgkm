 <x-layout.default title="Entrada Proveedores - ERP Solutions Force">
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

     <div class="container mx-auto px-4 py-6" x-data="entrada">
         <!-- Encabezado -->
         <div class="mb-8">
             <h1 class="mt-3 text-2xl font-bold text-gray-900">Entradas de proveedores</h1>
             <p class="mt-1 text-gray-600">
                 Este módulo te permite registrar el ingreso de <span class="font-medium">productos o repuestos</span>
                 provenientes de nuestros <span class="font-medium">proveedores autorizados</span>.
                 Podrás ingresar la <span class="font-medium">cantidad recibida</span> y asignar la
                 <span class="font-medium">ubicación de almacenamiento</span> correspondiente para mantener un control
                 preciso del inventario.
             </p>
         </div>




         <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
             <!-- Sección izquierda - Búsqueda y productos -->
             <div class="lg:col-span-2">
                 <div class="panel mt-6 p-5 max-w-7xl mx-auto">
                     <h2 class="text-lg font-semibold mb-4">Búsqueda y selección de producto</h2>
                     <p class="text-gray-600 mb-6">
                         Ingresa el <strong>codigo de repuesto</strong>, <strong>nombre</strong>,
                         <strong>modelo</strong> o <strong>marca</strong> del producto o repuesto que deseas buscar.
                         El sistema mostrará todos los productos que coincidan con los criterios ingresados, para que
                         puedas seleccionarlos fácilmente.
                     </p>



                     <div class="flex items-end gap-4 mb-8">
                         <!-- Input con ancho forzado -->
                         <div class="w-full">
                             <label class="block text-sm text-gray-500 mb-1">Codigo de repuesto, nombre, modelo o
                                 marca</label>
                             <div class="relative">
                                 <i
                                     class="fa-solid fa-barcode absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                 <input type="text" class="clean-input w-full text-xl pl-9"
                                     placeholder="Codigo de repuesto, nombre, modelo o marca" x-model="codigoBarras"
                                     @keyup.enter="abrirModalVerificacion" />
                             </div>
                         </div>

                         @if (\App\Helpers\PermisoHelper::tienePermiso('BUSCAR PRODUCTO ENTRADA PROVEEDOR'))
                             <!-- Botón fijo -->
                             <button
                                 class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition"
                                 @click="abrirModalVerificacion">
                                 <i class="fas fa-check-circle"></i>
                                 BUSCAR PRODUCTO
                             </button>
                         @endif
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
                                             x-text="producto.codigo_repuesto ? producto.codigo_repuesto : producto.nombre">
                                         </td>
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
                         <!-- Tipo de entrada -->
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">
                                 Tipo de entrada <span class="text-red-500">*</span>
                             </label>
                             <div class="relative">
                                 <select class="clean-input w-full pl-10 pr-8 appearance-none cursor-pointer bg-white"
                                     x-model="tipoEntrada" required>
                                     <option value="">Seleccione un tipo</option>
                                     <option value="consignacion">Consignación</option>
                                     <option value="prestamo">Préstamo</option>
                                     <option value="muestra">Muestra</option>
                                     <option value="traslado">Traslado interno</option>
                                     <option value="marca_asociada">Ingreso por marca asociada</option>
                                     <option value="otro">Otro</option>
                                 </select>
                                 <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                     <i class="fas fa-list-alt text-gray-400"></i>
                                 </div>
                             </div>
                         </div>

                         <!-- Fecha de ingreso -->
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">
                                 Fecha de ingreso <span class="text-red-500">*</span>
                             </label>
                             <div class="relative">
                                 <input type="text" x-ref="fechaIngresoInput" x-model="fechaIngreso"
                                     class="clean-input w-full pl-10 pr-4 cursor-pointer hover:border-blue-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                     placeholder="Seleccione una fecha" required>
                                 <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                     <i class="fas fa-calendar-alt text-gray-400"></i>
                                 </div>
                             </div>
                             <p class="text-xs text-gray-500 mt-1">Haga clic para seleccionar la fecha</p>
                         </div>

                         <!-- Cliente General -->
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">
                                 Cliente General<span class="text-red-500">*</span>
                             </label>
                             <div class="relative">
                                 <select
                                     class="clean-input w-full pl-10 pr-8 appearance-none cursor-pointer bg-white hover:border-blue-400 transition-colors"
                                     x-model="clienteGeneral">
                                     <option value="">Seleccione un cliente</option>
                                     @foreach ($clientesGenerales as $cliente)
                                         <option value="{{ $cliente->idClienteGeneral }}">{{ $cliente->descripcion }}
                                         </option>
                                     @endforeach
                                 </select>
                                 <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                     <i class="fas fa-handshake text-gray-400"></i>
                                 </div>

                             </div>
                         </div>
                         <!-- Observaciones -->
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">

                                 Observaciones
                             </label>
                             <div class="relative">
                                 <textarea class="clean-input w-full pl-10 pr-4 resize-none" x-model="observaciones" rows="3"
                                     placeholder="Ingrese observaciones adicionales..."></textarea>
                                 <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                     <i class="fas fa-comment-dots text-gray-400 mt-0.5"></i>
                                 </div>
                             </div>
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
                             <label class="block text-sm font-medium text-gray-700 mb-2">Adjuntar archivo
                                 (opcional)</label>
                             <label @dragover.prevent="over=true" @dragleave="over=false"
                                 @drop.prevent="
                over=false;
                $refs.fileInput.files = $event.dataTransfer.files;
                onFileChange({ target: $refs.fileInput })
            "
                                 class="w-full cursor-pointer rounded-xl border-2 border-dashed border-gray-300 p-6 text-center transition hover:border-blue-400"
                                 :class="over ? 'border-blue-500 bg-blue-50/60' : ''">
                                 <div class="flex flex-col items-center gap-2 text-gray-600">
                                     <i class="fas fa-cloud-upload-alt text-2xl"></i>
                                     <div class="text-sm">
                                         <span class="font-medium text-blue-600">Haz clic</span> o arrastra tu archivo
                                         aquí
                                     </div>
                                     <div class="text-xs text-gray-500">(pdf/doc/docx/jpg/jpeg/png) · máx 5MB</div>
                                 </div>
                                 <input x-ref="fileInput" type="file" class="sr-only" @change="onFileChange"
                                     accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
                             </label>
                             <template x-if="fileName">
                                 <div
                                     class="mt-3 flex items-center justify-between gap-3 rounded-lg border border-gray-200 p-3">
                                     <div class="flex items-center gap-3">
                                         <i class="fas fa-file-alt text-gray-500"></i>
                                         <div>
                                             <p class="text-sm font-medium text-gray-800" x-text="fileName"></p>
                                             <p class="text-xs text-gray-500"
                                                 x-text="(fileSize/1024).toFixed(1) + ' KB'"></p>
                                         </div>
                                     </div>
                                     <button type="button" @click="clear()"
                                         class="text-sm text-red-600 hover:underline">Quitar</button>
                                 </div>
                             </template>
                         </div>

                         <!-- Botón Guardar Entrada -->
                         <div class="pt-4">
                             @if (\App\Helpers\PermisoHelper::tienePermiso('GUARDAR ENTRADA PROVEEDOR'))
                                 <button
                                     class="w-full py-3 px-4 rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white"
                                     :disabled="guardandoEntrada" @click="guardarEntrada">
                                     <template x-if="guardandoEntrada">
                                         <svg class="animate-spin h-5 w-5 text-white"
                                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                             <circle class="opacity-25" cx="12" cy="12" r="10"
                                                 stroke="currentColor" stroke-width="4"></circle>
                                             <path class="opacity-75" fill="currentColor"
                                                 d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                             </path>
                                         </svg>
                                     </template>
                                     <template x-if="!guardandoEntrada">
                                         <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                 d="M5 13l4 4L19 7"></path>
                                         </svg>
                                     </template>
                                     <span x-text="guardandoEntrada ? 'GUARDANDO...' : 'GUARDAR ENTRADA'"></span>
                                 </button>
                             @endif
                         </div>
                     </div>
                 </div>
             </div>
             <!-- MODAL PARA SELECCIÓN DE PRODUCTOS -->
             <div x-show="modalAbierto" x-cloak class="fixed inset-0 bg-black/60 z-[999] overflow-y-auto" x-transition
                 @click="cerrarModal">
                 <div class="flex items-start justify-center min-h-screen px-4 py-8">
                     <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-auto" @click.stop>
                         <!-- Header del modal -->
                         <div class="flex items-center justify-between p-6 border-b border-gray-200">
                             <div>
                                 <h3 class="text-lg font-semibold text-gray-900">Seleccionar Producto</h3>
                                 <p class="text-sm text-gray-500 mt-1">Busca y selecciona el producto que deseas
                                     agregar</p>
                             </div>
                             <button @click="cerrarModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                         d="M6 18L18 6M6 6l12 12"></path>
                                 </svg>
                             </button>
                         </div>

                         <!-- Contenido del modal -->
                         <div class="p-6">
                             <!-- Loading state -->
                             <div x-show="modalCargando" class="text-center py-8">
                                 <div class="inline-flex items-center gap-3">
                                     <svg class="animate-spin h-6 w-6 text-blue-600" fill="none"
                                         viewBox="0 0 24 24">
                                         <circle class="opacity-25" cx="12" cy="12" r="10"
                                             stroke="currentColor" stroke-width="4"></circle>
                                         <path class="opacity-75" fill="currentColor"
                                             d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                         </path>
                                     </svg>
                                     <span class="text-gray-600">Buscando productos...</span>
                                 </div>
                             </div>

                             <!-- Lista de productos encontrados -->
                             <div x-show="!modalCargando">
                                 <template x-if="productosEncontrados.length === 0 && !modalCargando">
                                     <div class="text-center py-8">
                                         <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                                         <p class="text-gray-500">No se encontraron productos con esos criterios</p>
                                     </div>
                                 </template>

                                 <template x-if="productosEncontrados.length > 0">
                                     <div class="space-y-3">
                                         <p class="text-sm text-gray-600 mb-4">
                                             Se encontraron <span class="font-medium"
                                                 x-text="productosEncontrados.length"></span> producto(s)
                                         </p>

                                         <!-- Lista de productos -->
                                         <div class="max-h-96 overflow-y-auto space-y-2">
                                             <template x-for="(producto, index) in productosEncontrados"
                                                 :key="producto.id + '-' + producto.modelo">

                                                 <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors cursor-pointer"
                                                     :class="estaSeleccionado(producto) ? 'border-blue-500 bg-blue-50' : ''"
                                                     @click="seleccionarProducto(producto)">
                                                     <div class="flex items-start justify-between">
                                                         <div class="flex-1">
                                                             <div class="flex items-center gap-2 mb-2">
                                                                 <span
                                                                     class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                     <i class="fas fa-barcode mr-1"></i>
                                                                     <span
                                                                         x-text="producto.codigo_barras || 'Sin código'"></span>
                                                                 </span>
                                                                 <template x-if="producto.sku">
                                                                     <span
                                                                         class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                         SKU: <span x-text="producto.sku"
                                                                             class="ml-1"></span>
                                                                     </span>
                                                                 </template>
                                                             </div>

                                                             <h4 class="font-medium text-gray-900 mb-1"
                                                                 x-text="producto.codigo_repuesto ? producto.codigo_repuesto : producto.nombre">
                                                             </h4>
                                                             <div
                                                                 class="flex items-center gap-4 text-sm text-gray-600">
                                                                 <span>
                                                                     <i class="fas fa-tag mr-1"></i>
                                                                     Marca: <span class="font-medium"
                                                                         x-text="producto.marca"></span>
                                                                 </span>
                                                                 <span>
                                                                     <i class="fas fa-cogs mr-1"></i>
                                                                     Modelo: <span class="font-medium"
                                                                         x-text="producto.modelo"></span>
                                                                 </span>
                                                             </div>

                                                             <div class="flex items-center gap-4 mt-2 text-sm">
                                                                 <span class="text-gray-600">
                                                                     <i class="fas fa-boxes mr-1"></i>
                                                                     Stock: <span class="font-medium"
                                                                         x-text="producto.stock_total"></span>
                                                                 </span>
                                                                 <template x-if="producto.precio_compra">
                                                                     <span class="text-green-600">
                                                                         <i class="fas fa-dollar-sign mr-1"></i>
                                                                         Compra: <span class="font-medium">S/
                                                                         </span><span
                                                                             x-text="parseFloat(producto.precio_compra).toFixed(2)"></span>
                                                                     </span>
                                                                 </template>
                                                             </div>

                                                             <!-- Input de cantidad para productos seleccionados -->
                                                             <div x-show="estaSeleccionado(producto)" class="mt-3">
                                                                 <label
                                                                     class="block text-sm font-medium text-gray-700 mb-1">Cantidad:</label>
                                                                 <input type="number" min="1"
                                                                     :value="productosSeleccionados.find(p => p.id === producto
                                                                         .id)?.cantidadModal || 1"
                                                                     @click.stop
                                                                     @change="actualizarCantidadModal(producto, $event)"
                                                                     class="w-20 border border-gray-300 rounded px-2 py-1 text-center">
                                                             </div>
                                                         </div>

                                                         <!-- Checkbox para selección múltiple -->
                                                         <div class="ml-4">
                                                             <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-colors"
                                                                 :class="estaSeleccionado(producto) ?
                                                                     'border-blue-500 bg-blue-500' : 'border-gray-300'">
                                                                 <svg x-show="estaSeleccionado(producto)"
                                                                     class="w-3 h-3 text-white" fill="none"
                                                                     stroke="currentColor" viewBox="0 0 24 24">
                                                                     <path stroke-linecap="round"
                                                                         stroke-linejoin="round" stroke-width="3"
                                                                         d="M5 13l4 4L19 7"></path>
                                                                 </svg>
                                                             </div>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </template>
                                         </div>
                                     </div>
                                 </template>
                             </div>
                         </div>

                         <!-- Footer del modal con botones de acción -->
                         <div class="border-t border-gray-200 px-6 py-4"
                             x-show="!modalCargando && productosEncontrados.length > 0">
                             <div class="flex items-center justify-between">
                                 <div class="flex items-center gap-4">
                                     <span class="text-sm text-gray-600">
                                         Seleccionados: <span class="font-medium text-blue-600"
                                             x-text="productosSeleccionados.length"></span>
                                     </span>
                                     <template x-if="productosSeleccionados.length > 0">
                                         <button @click="limpiarSelecciones"
                                             class="text-sm text-gray-500 hover:text-red-600 underline">
                                             Limpiar selecciones
                                         </button>
                                     </template>
                                 </div>

                                 <div class="flex items-center gap-3">
                                     <button @click="cerrarModal"
                                         class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                         Cancelar
                                     </button>
                                     <button @click="agregarProductosSeleccionados"
                                         :disabled="productosSeleccionados.length === 0"
                                         class="px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white rounded-lg transition-colors flex items-center gap-2">
                                         <i class="fas fa-plus"></i>
                                         <span
                                             x-text="`Agregar ${productosSeleccionados.length} Producto${productosSeleccionados.length !== 1 ? 's' : ''}`"></span>
                                     </button>
                                 </div>
                             </div>
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
