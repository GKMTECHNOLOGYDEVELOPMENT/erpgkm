 <x-layout.default>
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
             <h1 class="mt-3 text-2xl font-bold text-gray-900">Nueva compra</h1>
             <p class="mt-1 text-gray-600">
                 Registra compras de forma simple: busca productos por <span class="font-medium">código de barras</span>
                 o
                 <span class="font-medium">nombre</span>, selecciónalos del listado, ajusta cantidades y guarda.
                 Si el producto no existe, podrás crearlo al instante.
             </p>
         </div>


         <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
             <!-- Sección izquierda - Búsqueda y productos -->
             <div class="lg:col-span-2">
                 <div class="panel mt-6 p-5 max-w-7xl mx-auto">
                     <h2 class="text-lg font-semibold mb-4">Búsqueda y selección de productos</h2>
                     <p class="text-gray-600 mb-6">
                         Escribe el código de barras o el nombre y haz clic en <strong>Buscar producto</strong>.
                         Te mostraremos las coincidencias en una ventana donde podrás elegir uno o varios productos.
                         Si no aparece lo que buscas, tendrás la opción de registrarlo.
                     </p>

                     <div class="flex items-end gap-4 mb-8">
                         <!-- Input con ancho forzado -->
                         <div class="w-full">
                             <label class="block text-sm text-gray-500 mb-1">Código de barras</label>
                             <div class="relative">
                                 <i
                                     class="fa-solid fa-barcode absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                 <input type="text" class="clean-input w-full text-xl pl-9"
                                     placeholder="Código de barras o nombre" x-model="codigoBarras"
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
                         <!-- Primera fila: Documento y Serie + Nro -->
                         <div class="grid grid-cols-2 gap-4">
                             <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-1">Documento <span
                                         class="text-red-500">*</span></label>
                                 <select
                                     class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-white text-sm"
                                     x-model="documentoId" required>
                                     <option value="">Seleccione un documento</option>
                                     <template x-for="documento in documentos" :key="documento.idDocumento">
                                         <option :value="documento.idDocumento" x-text="documento.nombre"></option>
                                     </template>
                                 </select>

                                 <!-- Loading state -->
                                 <template x-if="documentos.length === 0">
                                     <div class="text-sm text-gray-500 mt-1">Cargando documentos...</div>
                                 </template>
                             </div>

                             <!-- Fila: Serie - Número con guion en el centro -->
                             <div class="flex gap-2 items-end">
                                 <!-- Campo Serie -->
                                 <div class="w-1/2">
                                     <label class="block text-sm font-medium text-gray-700 mb-1">Serie <span
                                             class="text-red-500">*</span></label>
                                     <input type="text" x-model="serie"
                                         class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                   focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 
                   text-gray-800 dark:text-white text-sm" />
                                 </div>

                                 <!-- Separador visual "-" -->
                                 <div class="pb-3 text-lg text-gray-600 dark:text-gray-300">-</div>

                                 <!-- Campo Número -->
                                 <div class="w-1/2">
                                     <label class="block text-sm font-medium text-gray-700 mb-1">Número <span
                                             class="text-red-500">*</span></label>
                                     <input type="text" x-model="nro"
                                         class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                   focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 
                   text-gray-800 dark:text-white text-sm" />
                                 </div>
                             </div>


                         </div>

                         <!-- Segunda fila: Fechas -->
                         <div class="grid grid-cols-2 gap-4">
                             <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Emisión <span
                                         class="text-red-500">*</span></label>
                                 <input type="text" x-ref="fechaInput" x-model="fecha"
                                     class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-white text-sm"
                                     placeholder="Selecciona fecha" />
                             </div>

                             <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Vencimiento <span
                                         class="text-red-500">*</span></label>
                                 <input type="text" value="2025-08-27" x-ref="fechaVencimientoInput"
                                     x-model="fechaVencimiento"
                                     class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-white text-sm" />
                             </div>
                         </div>

                         <!-- Tercera fila: Moneda y Tipo de Cambio -->
                         <div class="grid grid-cols-2 gap-4">
                             <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-1">Moneda <span
                                         class="text-red-500">*</span></label>
                                 <select
                                     class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-white text-sm"
                                     x-model="monedaId" @change="cambiarMoneda" required>
                                     <option value="">Seleccione una moneda</option>
                                     <template x-for="moneda in monedas" :key="moneda.id">
                                         <option :value="moneda.id"
                                             x-text="moneda.nombre + ' (' + moneda.simbolo + ')'"></option>
                                     </template>
                                 </select>

                                 <!-- Loading state -->
                                 <template x-if="monedas.length === 0">
                                     <div class="text-sm text-gray-500 mt-1">Cargando monedas...</div>
                                 </template>
                             </div>

                             <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-1">Tipo Cambio</label>
                                 <input type="number" step="0.001" x-model="tipoCambio"
                                     class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-white text-sm"
                                     @change="cambiarMoneda" />
                             </div>
                         </div>
                         <!-- Cuarta fila: Impuesto y DUA -->
                         <div class="grid grid-cols-2 gap-4 items-end">
                             <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-1">Impuesto <span
                                         class="text-red-500">*</span></label>
                                 <select
                                     class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-white text-sm"
                                     x-model="impuestoId" required>
                                     <option value="">Seleccione un impuesto</option>
                                     <template x-for="impuesto in impuestos" :key="impuesto.id">
                                         <option :value="impuesto.id"
                                             x-text="impuesto.nombre + ' (' + impuesto.monto + '%)'"></option>
                                     </template>
                                 </select>

                                 <!-- Loading state -->
                                 <template x-if="impuestos.length === 0">
                                     <div class="text-sm text-gray-500 mt-1">Cargando impuestos...</div>
                                 </template>
                             </div>

                             <div class="flex items-center h-10">
                                 <input type="checkbox" id="dua" checked
                                     class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                 <label for="dua" class="ms-2 text-sm font-medium text-gray-700">DUA</label>
                             </div>
                         </div>

                         <!-- Quinta fila: Proveedor -->
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor <span
                                     class="text-red-500">*</span></label>
                             <select id="proveedorSelect"
                                 class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-white text-sm"
                                 x-model="proveedorId">
                                 <option value="">Seleccione una opción</option>
                                 <template x-for="proveedor in proveedores" :key="proveedor.id">
                                     <option :value="proveedor.id"
                                         x-text="proveedor.nombre + ' - ' + proveedor.numeroDocumento"></option>
                                 </template>
                             </select>

                             <!-- Loading state -->
                             <template x-if="proveedores.length === 0">
                                 <div class="text-sm text-gray-500 mt-1">Cargando proveedores...</div>
                             </template>
                         </div>

                         <!-- Sexta fila: Condición de Compra y Sujeto a -->
                         <div class="grid grid-cols-2 gap-4">
                             <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-1">Condición Compra <span
                                         class="text-red-500">*</span></label>
                                 <select
                                     class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-white text-sm"
                                     x-model="condicionCompraId" required>
                                     <option value="">Seleccione una condición</option>
                                     <template x-for="condicion in condicionesCompra" :key="condicion.id">
                                         <option :value="condicion.id" x-text="condicion.nombre"></option>
                                     </template>
                                 </select>

                                 <!-- Loading state -->
                                 <template x-if="condicionesCompra.length === 0">
                                     <div class="text-sm text-gray-500 mt-1">Cargando condiciones...</div>
                                 </template>
                             </div>

                             <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-1">Sujeto a <span
                                         class="text-red-500">*</span></label>
                                 <select
                                     class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-white text-sm"
                                     x-model="sujetoId" required>
                                     <option value="">Seleccione una opción</option>
                                     <template x-for="sujeto in sujetos" :key="sujeto.id">
                                         <option :value="sujeto.id" x-text="sujeto.nombre"></option>
                                     </template>
                                 </select>

                                 <!-- Loading state -->
                                 <template x-if="sujetos.length === 0">
                                     <div class="text-sm text-gray-500 mt-1">Cargando sujetos...</div>
                                 </template>
                             </div>
                         </div>

                         <!-- Nueva fila: Tipo de Pago -->
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Pago</label>
                             <select
                                 class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-white text-sm"
                                 x-model="tipoPagoId">
                                 <option value="">Seleccione un tipo de pago</option>
                                 <template x-for="tipoPago in tiposPago" :key="tipoPago.id">
                                     <option :value="tipoPago.id" x-text="tipoPago.nombre"></option>
                                 </template>
                             </select>

                             <!-- Loading state -->
                             <template x-if="tiposPago.length === 0">
                                 <div class="text-sm text-gray-500 mt-1">Cargando tipos de pago...</div>
                             </template>
                         </div>



                         <!-- Séptima fila: Adjuntar Archivo -->
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Adjuntar Archivo</label>
                             <div class="flex items-center gap-2">
                                 <input type="file"
                                     class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-white text-sm"
                                     accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.qif" />
                                 <span class="text-xs text-gray-500">(pdf/doc/jpg/png/qif)</span>
                             </div>
                         </div>

                         <!-- Separador -->
                         <div class="border-t border-gray-200 pt-4"></div>

                         <!-- Totales con moneda dinámica -->
                         <div class="space-y-2">
                             <div class="flex justify-between">
                                 <span class="text-gray-600 text-sm">Subtotal:</span>
                                 <span class="font-medium text-sm" x-text="formatCurrency(subtotal)"></span>
                             </div>
                             <div class="flex justify-between">
                                 <span class="text-gray-600 text-sm">
                                     <span
                                         x-text="impuestos.find(i => i.id == impuestoId)?.nombre || 'Impuesto'"></span>
                                     (<span x-text="impuestos.find(i => i.id == impuestoId)?.monto || 18"></span>%):
                                 </span>
                                 <span class="font-medium text-sm" x-text="formatCurrency(itbis)"></span>
                             </div>
                             <div class="flex justify-between text-lg font-semibold pt-2 border-t border-gray-200">
                                 <span class="text-gray-700">Total:</span>
                                 <span class="text-blue-600" x-text="formatCurrency(total)"></span>
                             </div>
                         </div>

                         <!-- Botón Guardar con Loading -->
                         <div class="pt-4">
                             <button
                                 class="w-full py-3 px-4 rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-2"
                                 :disabled="!puedeGuardar || guardandoCompra"
                                 :class="{
                                     'bg-blue-600 hover:bg-blue-700 text-white': puedeGuardar && !guardandoCompra,
                                     'bg-gray-400 text-gray-600 cursor-not-allowed': !puedeGuardar || guardandoCompra
                                 }"
                                 @click="guardarCompra">

                                 <!-- Spinner de loading -->
                                 <template x-if="guardandoCompra">
                                     <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                         fill="none" viewBox="0 0 24 24">
                                         <circle class="opacity-25" cx="12" cy="12" r="10"
                                             stroke="currentColor" stroke-width="4"></circle>
                                         <path class="opacity-75" fill="currentColor"
                                             d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                         </path>
                                     </svg>
                                 </template>

                                 <!-- Ícono normal cuando no está cargando -->
                                 <template x-if="!guardandoCompra">
                                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                             d="M5 13l4 4L19 7"></path>
                                     </svg>
                                 </template>

                                 <!-- Texto del botón -->
                                 <span x-text="guardandoCompra ? 'GUARDANDO...' : 'GUARDAR COMPRA'"></span>
                             </button>
                         </div>

                         <p class="text-xs text-gray-500 text-center">
                             Los campos marcados con <span class="text-red-500">*</span> son obligatorios
                         </p>
                     </div>
                 </div>
             </div>
         </div>

         <!-- MODAL -->
         <div x-show="modalAbierto" class="fixed inset-0 bg-black/60 z-[999] overflow-y-auto" x-transition
             style="display:none;">
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

                     <!-- Body (cards) -->
                     <!-- Body -->
                     <div class="p-6 text-sm text-gray-700 dark:text-white">
                         <template x-if="productosEncontrados.length > 0">
                             <div class="space-y-4">
                                 <div class="flex items-center gap-2">
                                     <h4 class="text-base font-semibold">Resultados</h4>
                                     <span class="text-xs text-gray-500">(<span x-text="totalResultados"></span>
                                         total)</span>
                                 </div>

                                 <div
                                     class="rounded-2xl border border-gray-200/70 bg-white/70 dark:bg-gray-900/50 shadow-sm overflow-hidden">
                                     <div class="max-h-[420px] overflow-auto">
                                         <table class="min-w-full text-xs md:text-sm">
                                             <!-- THEAD -->
                                             <thead class="sticky top-0 z-10">
                                                 <tr
                                                     class="bg-gradient-to-b from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 text-gray-600">
                                                     <th class="px-4 py-3 text-left w-28 font-semibold">Código</th>
                                                     <th class="px-4 py-3 text-left font-semibold">Nombre</th>
                                                     <th class="px-3 py-3 text-right w-20 font-semibold">Stock</th>
                                                     <th class="px-3 py-3 text-right w-28 font-semibold">P. Compra</th>
                                                     <th class="px-3 py-3 text-right w-28 font-semibold">P. Venta</th>
                                                     <th class="px-4 py-3 text-center w-36 font-semibold">Acción</th>
                                                 </tr>
                                             </thead>

                                             <!-- TBODY -->
                                             <tbody>
                                                 <template x-for="(producto, i) in productosEncontrados"
                                                     :key="producto.idArticulos">
                                                     <tr
                                                         :class="[
                                                             isSelected(producto) ? 'bg-green-50' : (i % 2 === 0 ?
                                                                 'bg-white' : 'bg-gray-50/60'),
                                                             'hover:bg-blue-50/60 transition-colors'
                                                         ]">
                                                         <td class="px-4 py-2 font-mono text-[13px] whitespace-nowrap"
                                                             x-text="producto.codigo_barras"></td>
                                                         <td class="px-4 py-2">
                                                             <div class="max-w-[360px] truncate"
                                                                 :title="producto.nombre" x-text="producto.nombre">
                                                             </div>
                                                         </td>
                                                         <td class="px-3 py-2 text-right tabular-nums"
                                                             x-text="Number(producto.stock_total)"></td>
                                                         <td class="px-3 py-2 text-right tabular-nums"
                                                             x-text="Number(producto.precio_compra).toFixed(2)"></td>
                                                         <td class="px-3 py-2 text-right tabular-nums"
                                                             x-text="Number(producto.precio_venta).toFixed(2)"></td>
                                                         <td class="px-4 py-2 text-center">
                                                             <button type="button"
                                                                 @click.prevent="toggleSeleccion(producto)"
                                                                 :class="isSelected(producto) ?
                                                                     'px-2 py-1 rounded-full border border-green-400 bg-green-100 text-green-700 text-[11px] font-semibold' :
                                                                     'px-2 py-1 rounded-full text-[11px] font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100'">
                                                                 <span
                                                                     x-show="!isSelected(producto)">Seleccionar</span>
                                                                 <span
                                                                     x-show="isSelected(producto)">Seleccionado</span>
                                                             </button>
                                                         </td>
                                                     </tr>
                                                 </template>
                                             </tbody>
                                         </table>
                                     </div>

                                     <!-- Paginación -->
                                     <div
                                         class="flex items-center justify-center gap-2 p-3 border-t border-gray-200 bg-gray-50">
                                         <button
                                             class="px-3 py-1 text-sm rounded-full border hover:bg-white disabled:opacity-50"
                                             @click="abrirModalVerificacion(paginaActual - 1)"
                                             :disabled="paginaActual === 1">Anterior</button>

                                         <span class="text-sm">
                                             Página <span class="font-semibold" x-text="paginaActual"></span>
                                             de <span class="font-semibold"
                                                 x-text="Math.ceil(totalResultados / resultadosPorPagina)"></span>
                                         </span>

                                         <button
                                             class="px-3 py-1 text-sm rounded-full border hover:bg-white disabled:opacity-50"
                                             @click="abrirModalVerificacion(paginaActual + 1)"
                                             :disabled="paginaActual >= Math.ceil(totalResultados / resultadosPorPagina)">Siguiente</button>
                                     </div>
                                 </div>
                             </div>
                         </template>

                         <!-- Sin resultados -->
                         <template x-if="productosEncontrados.length === 0 && codigoBarras && !productoEncontrado">
                             <div class="text-center text-gray-500 mt-8">No se encontraron productos con el término
                                 ingresado.</div>
                         </template>
                     </div>

                     <!-- Footer -->
                     <div
                         class="flex justify-end items-center gap-4 px-6 py-4 border-t border-gray-200 dark:border-white/10">
                         <div class="mr-auto text-sm text-gray-600" x-show="seleccionadosCount > 0">
                             Seleccionados: <span class="font-semibold" x-text="seleccionadosCount"></span>
                         </div>

                         <button @click="cerrarModal" class="text-sm btn btn-outline-danger">CERRAR</button>

                         <!-- Botón único dinámico -->
                         <button @click="seleccionadosCount > 0 ? agregarSeleccionados() : agregarAlCarrito()"
                             :class="seleccionadosCount > 0 ?
                                 'text-sm px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700' :
                                 'text-sm btn btn-primary flex items-center gap-2'">
                             <svg x-show="seleccionadosCount === 0" class="w-4 h-4" fill="none"
                                 stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                             </svg>
                             <span
                                 x-text="seleccionadosCount > 0 ? 'AGREGAR SELECCIONADOS' : 'AGREGAR PRODUCTO'"></span>
                         </button>
                     </div>

                 </div>
             </div>
         </div>

     </div>


     </div>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

     <script src="{{ asset('assets/js/compras/compras.js') }}" defer></script>

 </x-layout.default>
