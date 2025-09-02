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
                     <h2 class="text-lg font-semibold mb-4">Búsqueda y selección de producto</h2>
                    <p class="text-gray-600 mb-6">
                        Escribe o escanea el <strong>código de barras</strong> del producto y haz clic en <strong>Buscar producto</strong>.
                        Si el código es válido, se mostrará el producto correspondiente. 
                        Si no se encuentra, tendrás la opción de registrarlo manualmente.
                    </p>


                     <div class="flex items-end gap-4 mb-8">
                         <!-- Input con ancho forzado -->
                         <div class="w-full">
                             <label class="block text-sm text-gray-500 mb-1">Código de barras</label>
                             <div class="relative">
                                 <i
                                     class="fa-solid fa-barcode absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                 <input type="text" class="clean-input w-full text-xl pl-9"
                                     placeholder="Código de barras" x-model="codigoBarras"
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
                                         <!-- En la tabla de productos, reemplazar el campo oculto por uno visible -->
                                         <td class="px-4 py-4 whitespace-nowrap">
                                             <input type="number" step="0.01"
                                                 class="w-24 border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-right bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                                                 x-model="producto.precio" @change="actualizarSubtotal(producto)">
                                         </td>
                                         <td class="px-4 py-4 whitespace-nowrap" style="display: none;">
                                             <input type="number" step="0.01"
                                                 class="w-24 border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-right bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                                                 x-model="producto.precio_venta" placeholder="Precio venta">
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
                                             x-model="nuevoProducto.codigo_barras" readonly>

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
                                         <input type="number" class="clean-input" x-model="nuevoProducto.stock"
                                             @change="validarStock" min="0">
                                     </div>

                                     <!-- Stock Mínimo -->
                                     <div>
                                         <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                             <i class="fas fa-database"></i> Stock Mínimo
                                         </label>
                                         <input type="number" class="clean-input"
                                             x-model="nuevoProducto.stock_minimo" @change="validarStock"
                                             min="0">
                                     </div>

                                     <!-- Unidad de Medida -->
                                     <div x-init="$nextTick(() => {
                                         const select = $el.querySelector('select');
                                         $(select).select2({
                                             placeholder: 'Seleccione una opción',
                                             width: '100%',
                                             dropdownParent: select.closest('div') // para modales
                                         }).on('change', (e) => {
                                             nuevoProducto.unidad = e.target.value;
                                         });
                                     })">
                                         <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                             <i class="fas fa-balance-scale"></i> Unidad de Medida
                                         </label>
                                         <select class="clean-input w-full">
                                             <option value="">Seleccione una opción</option>
                                             <template x-for="unidad in unidades" :key="unidad.id">
                                                 <option :value="unidad.id" x-text="unidad.nombre"
                                                     :selected="unidad.id == nuevoProducto.unidad"></option>
                                             </template>
                                         </select>

                                         <template x-if="cargandoUnidades">
                                             <div class="text-xs text-gray-500 mt-1">Cargando unidades...</div>
                                         </template>
                                     </div>


                                     <!-- Modelo -->
                                     <div x-init="$nextTick(() => {
                                         const select = $el.querySelector('select');
                                         $(select).select2({
                                             placeholder: 'Seleccione una opción',
                                             width: '100%',
                                             dropdownParent: select.closest('div')
                                         }).on('change', (e) => {
                                             nuevoProducto.modelo = e.target.value;
                                         });
                                     })">
                                         <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                             <i class="fas fa-project-diagram"></i> Modelo
                                         </label>
                                         <select class="clean-input w-full">
                                             <option value="">Seleccione una opción</option>
                                             <template x-for="modelo in modelos" :key="modelo.id">
                                                 <option :value="modelo.id" x-text="modelo.nombre"
                                                     :selected="modelo.id == nuevoProducto.modelo"></option>
                                             </template>
                                         </select>

                                         <template x-if="cargandoModelos">
                                             <div class="text-xs text-gray-500 mt-1">Cargando modelos...</div>
                                         </template>
                                     </div>

                                     <!-- Garantía de Fábrica -->
                                     <div class="relative">
                                         <label for="garantia_fabrica"
                                             class="block text-sm font-medium text-gray-700">Garantía de
                                             Fábrica</label>
                                         <div class="relative mt-1">
                                             <i class="fas fa-shield-alt input-icon"></i>
                                             <input id="garantia_fabrica" name="garantia_fabrica" type="number"
                                                 min="0" class="clean-input w-full"
                                                 placeholder="Tiempo de garantía" value="0">
                                         </div>
                                     </div>

                                     <!-- Unidad de Tiempo de Garantía -->
                                     <div class="relative">
                                         <label for="unidad_tiempo_garantia"
                                             class="block text-sm font-medium text-gray-700">Unidad de Tiempo</label>
                                         <div class="relative mt-1">
                                             <i class="fas fa-clock input-icon"></i>
                                             <select id="unidad_tiempo_garantia" name="unidad_tiempo_garantia"
                                                 class="select2-single clean-input w-full pl-10">
                                                 <option value="dias">Días</option>
                                                 <option value="semanas">Semanas</option>
                                                 <option value="meses" selected>Meses</option>
                                                 <option value="años">Años</option>
                                             </select>
                                         </div>
                                     </div>

                                     <!-- <div x-init="$nextTick(() => {
                                         const select = $el.querySelector('select');
                                         $(select).select2({
                                             placeholder: 'Seleccione un proveedor',
                                             width: '100%',
                                             dropdownParent: select.closest('div')
                                         }).on('change', (e) => {
                                             nuevoProducto.proveedor = e.target.value;
                                         });
                                     })">
                                         <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                             <i class="fas fa-truck"></i> Proveedor
                                         </label>
                                         <select id="proveedorSelect"
                                 class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-white text-sm"
                                 x-model="proveedorId">
                                 <option value="">Seleccione una opción</option>
                                 <template x-for="proveedor in proveedores" :key="proveedor.id">
                                     <option :value="proveedor.id"
                                         x-text="proveedor.nombre + ' - ' + proveedor.numeroDocumento"></option>
                                 </template>
                             </select>

                                         <template x-if="cargandoProveedores">
                                             <div class="text-xs text-gray-500 mt-1">Cargando proveedores...</div>
                                         </template>
                                     </div> -->



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
                                <button type="button" 
                                    @click="toggleMonedaCompra()"
                                    class="text-gray-500 px-2 h-10 border-b border-gray-300">
                                <span x-text="monedaCompraActual?.simbolo || monedaCompraActual?.nombre || 'S/'" 
                                    class="w-8 text-center"></span>
                            </button>   
                                                                  <input type="number" step="0.01" class="clean-input"
                                                 x-model="nuevoProducto.precio_compra">

                                                <input type="hidden" id="moneda_compra" name="moneda_compra" value="0">

                                         </div>
                                     </div>

                                     <!-- En el modal, en la sección de información del producto -->
                                     <div>
                                         <label class="block text-gray-600 text-sm mb-1">Precio de venta<span
                                                 class="text-red-500 font-semibold">*</span></label>
                                         <div class="input-with-icon">
                                                 <button type="button" 
                                                        @click="toggleMonedaVenta()"
                                                        class="text-gray-500 px-2 h-10 border-b border-gray-300">
                                                    <span x-text="monedaVentaActual?.simbolo || monedaVentaActual?.nombre || 'S/'" 
                                                        class="w-8 text-center"></span>
                                                </button>                                             <input type="number" step="0.01" class="clean-input"
                                                 :value="nuevoProducto.precio_venta">
                                             <input type="hidden" x-model="nuevoProducto.precio_venta">

                                                                     <input type="hidden" id="moneda_venta" name="moneda_venta" value="0">


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


     </div>


     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

     <script src="{{ asset('assets/js/compras/compras.js') }}" defer></script>

 </x-layout.default>
