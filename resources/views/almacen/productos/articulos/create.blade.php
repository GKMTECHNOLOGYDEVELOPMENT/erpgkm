<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
        <style>
       .clean-input {
            border: none;
            border-bottom: 1px solid #e0e6ed;
            border-radius: 0;
            padding-left: 35px; /* asegúrate de dejar espacio al ícono */
            padding-bottom: 8px;
            padding-top: 8px;
            background-color: transparent;
            height: 40px; /* controla la altura si es necesario */
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
            margin-bottom: 1.5rem; /* Espacio para mensajes de error */
        }

        .input-with-icon .clean-input {
            padding-left: 35px !important; /* Forzar espacio para el ícono */
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
        .error-msg, .error-msg-duplicado {
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

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('producto.index') }}" class="text-primary hover:underline">
                    <i class="fas fa-arrow-left mr-1"></i> Producto
                </a>
                
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Crear Producto nuevo</span>
            </li>
        </ul>
    </div>

    <div class="panel mt-6 p-5 max-w-6x2 mx-auto">
        <h2 class="text-xl font-bold mb-5 flex items-center">
            <i class="fas fa-box text-primary mr-2"></i> Agregar Nuevo Producto
        </h2>


       <form id="productoForm" method="POST"  enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Código de Barras -->
                <div class="relative">
                    <label for="codigo_barras" class="block text-sm font-medium text-gray-700">Código de Barras</label>
                    <div class="relative mt-1">
                        <i class="fas fa-barcode input-icon"></i>
                        <input id="codigo_barras" name="codigo_barras" type="text" class="clean-input w-full"
                            placeholder="Ingrese código de barras" required>
                    </div>
                </div>

                <!-- SKU -->
                <div class="relative">
                    <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                    <div class="relative mt-1">
                        <i class="fas fa-tag input-icon"></i>
                        <input id="sku" name="sku" type="text" class="clean-input w-full" placeholder="Ingrese SKU">
                    </div>
                </div>

                <!-- Nombre -->
                <div class="relative">
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <div class="relative mt-1">
                        <i class="fas fa-cogs input-icon"></i>
                        <input id="nombre" name="nombre" type="text" class="clean-input w-full" placeholder="Ingrese nombre" required>
                    </div>
                </div>

                <!-- Stock Total -->
                <div class="relative">
                    <label for="stock_total" class="block text-sm font-medium text-gray-700">Stock Total</label>
                    <div class="relative mt-1">
                        <i class="fas fa-boxes input-icon"></i>
                        <input id="stock_total" name="stock_total" type="number" min="0" class="clean-input w-full"
                            placeholder="Ingrese stock total" required>
                    </div>
                </div>

                <!-- Stock Mínimo -->
                <div class="relative">
                    <label for="stock_minimo" class="block text-sm font-medium text-gray-700">Stock Mínimo</label>
                    <div class="relative mt-1">
                        <i class="fas fa-boxes input-icon"></i>
                        <input id="stock_minimo" name="stock_minimo" type="number" min="0" class="clean-input w-full"
                            placeholder="Ingrese stock mínimo" required>
                    </div>
                </div>

          <!-- Unidad de Medida -->
        <div class="relative input-with-icon">
            <div class="flex justify-between items-center">
                <label for="idUnidad" class="block text-sm font-medium text-gray-700">Unidad de Medida</label>
                <button type="button" class="btn btn-primary btn-sm flex items-center gap-2" @click="$dispatch('toggle-unidad-modal')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                </button>
            </div>
            <div class="relative mt-1">
                <i class="fas fa-balance-scale input-icon"></i>
                <select id="idUnidad" name="idUnidad" class="select2-single clean-input w-full pl-10">
                    <option value="" disabled selected>Seleccionar Unidad</option>
                    @foreach ($unidades as $unidad)
                        <option value="{{ $unidad->idUnidad }}">{{ $unidad->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

                <!-- Modelo -->
                <div class="relative input-with-icon">

                  <div class="flex justify-between items-center">
                    <label for="idModelo" class="block text-sm font-medium text-gray-700">Modelo</label>
                        <button type="button" class="btn btn-primary btn-sm flex items-center gap-2" @click="$dispatch('toggle-modal')">
                          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                        </button>
                    </div>

                                        <div class="relative mt-1">

                    <div class="relative mt-1">
                        <i class="fas fa-cubes input-icon"></i>
                        <!-- <i class="fas fa-toolbox input-icon"></i> -->
                        <select id="idModelo" name="idModelo" class="select2-single clean-input w-full pl-10" required>
                            <option value="" disabled selected>Seleccionar modelo</option>
                            @foreach ($modelos as $modelo)
                                <option value="{{ $modelo->idModelo }}">
                                    {{ $modelo->nombre }} - {{ $modelo->marca->nombre ?? 'Sin Marca' }} - {{ $modelo->categoria->nombre ?? 'Sin Categoría' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                </div>

                <!-- Peso -->
                <div>
                    <label for="peso" class="block text-sm font-medium text-gray-700">Peso (kg)</label>
                    <div class="relative mt-1">
                        <i class="fas fa-weight input-icon"></i>
                        <input id="peso" name="peso" type="number" step="0.01" min="0" class="clean-input w-full" placeholder="Ingrese peso" required>
                    </div>
                </div>

                <!-- Precio de Compra -->
                <div>
                    <label for="precio_compra" class="block text-sm font-medium text-gray-700">Precio de Compra</label>
                    <div class="flex items-center mt-1">
                        <button type="button" id="toggleMonedaCompra"
                            class="text-gray-500 px-2 h-10 border-b border-gray-300">
                            <span id="precio_compra_symbol" class="w-8 text-center">S/</span>
                        </button>
                        <div class="relative flex-1">
                            <input id="precio_compra" name="precio_compra" type="number" step="0.01" min="0"
                                class="clean-input w-full" placeholder="0.00" required>
                        </div>
                        <input type="hidden" id="moneda_compra" name="moneda_compra" value="0">
                    </div>
                </div>

                <!-- Precio de Venta -->
                <div>
                    <label for="precio_venta" class="block text-sm font-medium text-gray-700">Precio de Venta</label>
                    <div class="flex items-center mt-1">
                        <button type="button" id="toggleMonedaVenta"
                            class="text-gray-500 px-2 h-10 border-b border-gray-300">
                            <span id="precio_venta_symbol" class="w-8 text-center">S/</span>
                        </button>
                        <div class="relative flex-1">
                            <input id="precio_venta" name="precio_venta" type="number" step="0.01" min="0"
                                class="clean-input w-full" placeholder="0.00" required>
                        </div>
                        <input type="hidden" id="moneda_venta" name="moneda_venta" value="0">
                    </div>
                </div>

                <!-- Garantía de Fábrica -->
                <div class="relative">
                    <label for="garantia_fabrica" class="block text-sm font-medium text-gray-700">Garantía de Fábrica</label>
                    <div class="relative mt-1">
                        <i class="fas fa-shield-alt input-icon"></i>
                        <input id="garantia_fabrica" name="garantia_fabrica" type="number" min="0" class="clean-input w-full"
                            placeholder="Tiempo de garantía" value="0">
                    </div>
                </div>

                <!-- Unidad de Tiempo de Garantía -->
                <div class="relative">
                    <label for="unidad_tiempo_garantia" class="block text-sm font-medium text-gray-700">Unidad de Tiempo</label>
                    <div class="relative mt-1">
                        <i class="fas fa-clock input-icon"></i>
                        <select id="unidad_tiempo_garantia" name="unidad_tiempo_garantia" class="select2-single clean-input w-full pl-10">
                            <option value="dias">Días</option>
                            <option value="semanas">Semanas</option>
                            <option value="meses" selected>Meses</option>
                            <option value="años">Años</option>
                        </select>
                    </div>
                </div>

                <!-- Proveedor -->
                <div class="relative input-with-icon">
                    <label for="idProveedor" class="block text-sm font-medium text-gray-700">Proveedor</label>
                    <div class="relative mt-1">
                        <i class="fas fa-truck input-icon"></i>
                        <select id="idProveedor" name="idProveedor" class="select2-single clean-input w-full pl-10">
                            <option value="" selected>Seleccionar proveedor (opcional)</option>
                            @foreach ($proveedores as $proveedor)
                                <option value="{{ $proveedor->idProveedor }}">
                                    {{ $proveedor->nombre }} - {{ $proveedor->numeroDocumento }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                 <!-- Foto -->
                <div class="mb-5" x-data="{ 
                    fotoPreview: '/assets/images/articulo/producto-default.png', 
                    defaultImage: '/assets/images/articulo/producto-default.png' 
                }">
                    <label class="block text-sm font-medium text-gray-700">Foto</label>
                    <label for="foto"
                        class="inline-block text-sm font-semibold px-3 py-1.5 rounded cursor-pointer hover:bg-blue-700 transition">
                        <i class="fas fa-upload mr-1"></i> Seleccionar archivo
                    </label>
                    <div class="relative mt-1">
                        <input id="foto" name="foto" type="file" accept="image/*"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                            @change="fotoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : defaultImage">
                        <div class="border-b border-gray-300 pb-2 flex justify-between items-center">
                            <span x-text="fotoPreview !== defaultImage ? 'Archivo seleccionado' : 'Imagen por defecto'" 
                                class="text-gray-500 text-sm"></span>
                            <i class="fas fa-camera text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Previsualización de imagen -->
                    <div class="flex justify-center mt-4">
                        <div class="w-full max-w-xs h-40 flex justify-center items-center bg-gray-50 rounded">
                            <img :src="fotoPreview" alt="Previsualización de la imagen"
                                class="w-full h-full object-contain">
                        </div>
                    </div>
                </div>


                      <!-- Ficha Técnica -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700">Ficha Técnica (PDF)</label>
                    <label for="ficha_tecnica" class="inline-block text-sm font-semibold px-3 py-1.5 rounded cursor-pointer hover:bg-blue-700 transition">
                        <i class="fas fa-upload mr-1"></i> Seleccionar archivo</label>
                    <div class="relative mt-1">
                        <input id="ficha_tecnica" name="ficha_tecnica" type="file" accept=".pdf"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="border-b border-gray-300 pb-2 flex justify-between items-center">
                            <span id="nombre_archivo" class="text-gray-500 text-sm">Ningún archivo seleccionado</span>
                            <i class="fas fa-file-pdf text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Vista previa del PDF -->
                <div id="preview_pdf" class="mt-4 max-w-full">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vista previa:</label>
                <iframe id="pdf_viewer" class="w-full h-[600px] border rounded" type="application/pdf"></iframe>
                    </div>
                </div>


            </div>
   <div class="flex justify-end mt-6 gap-4">
            <!-- Cancelar -->
            <a href="{{ route('producto.index') }}" class="btn btn-outline-danger flex items-center">
                <i class="fas fa-times mr-2"></i> Cancelar
            </a>

            <!-- Limpiar -->
            <button type="button" id="btnLimpiar" class="btn btn-outline-warning flex items-center">
                <i class="fas fa-eraser mr-2"></i> Limpiar
            </button>

            <!-- Guardar -->
            <button type="button" id="btnGuardar" class="btn btn-primary flex items-center">
                <i class="fas fa-save mr-2"></i> Guardar Producto
            </button>
            </div>
        </form>
    </div>


    
    <!-- Modal para agregar modelo -->
<div x-data="{ open: false }" class="mb-5" @toggle-modal.window="open = !open">
    <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
        <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
            <div x-show="open" x-transition.duration.300
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 animate__animated animate__zoomInUp">
                <!-- Header del Modal -->
                <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                    <h5 class="font-bold text-lg">Agregar Modelo</h5>
                    <button type="button" class="text-white-dark hover:text-dark" @click="open = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="w-6 h-6">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- Formulario -->
                <div class="modal-scroll">
                    <form class="p-5 space-y-4" id="modeloForm" enctype="multipart/form-data" method="post">
                        @csrf
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium">Nombre</label>
                            <input id="nombre" name="nombre" class="clean-input w-full"
                                placeholder="Ingrese el nombre del modelo" required>
                        </div>
                        <!-- Marca -->
                        <div>
                            <label for="idMarca" class="block text-sm font-medium">Marca</label>
                            <select id="idMarca" name="idMarca" class="select2-single" required>
                                <option value="" disabled selected>Seleccione la Marca</option>
                                @foreach ($marcas as $marca)
                                    <option value="{{ $marca->idMarca }}">{{ $marca->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Categoría -->
                        <div>
                            <label for="idCategoria" class="block text-sm font-medium">Categoria</label>
                            <select id="idCategoria" name="idCategoria" class="select2-single" required>
                                <option value="" disabled selected>Seleccione la Categoría</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->idCategoria }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                   <!-- Tipo de Modelo (Checkboxes) -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Tipo de Modelo</label>
                        <div class="space-y-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="producto" value="1" class="form-checkbox text-primary" checked>
                                <span class="ml-2">Producto</span>
                            </label>
                            <!-- Ocultamos los otros checkboxes pero los mantenemos en el formulario -->
                            <input type="hidden" name="repuesto" value="0">
                            <input type="hidden" name="heramientas" value="0">
                            <input type="hidden" name="suministros" value="0">
                        </div>
                    </div>

                        <!-- Botones -->
                        <div class="flex justify-end items-center mt-4">
                            <button type="button" class="btn btn-outline-danger" @click="open = false">Cancelar</button>
                            <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Modal para agregar unidad -->
<div x-data="{ unidadOpen: false }" class="mb-5" @toggle-unidad-modal.window="unidadOpen = !unidadOpen">
    <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="unidadOpen && '!block'">
        <div class="flex items-start justify-center min-h-screen px-4" @click.self="unidadOpen = false">
            <div x-show="unidadOpen" x-transition.duration.300
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 animate__animated animate__zoomInUp">
                <!-- Header del Modal -->
                <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                    <h5 class="font-bold text-lg">Agregar Unidad de Medida</h5>
                    <button type="button" class="text-white-dark hover:text-dark" @click="unidadOpen = false; $dispatch('modal-unidad-closed')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="w-6 h-6">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- Formulario -->
                <div class="modal-scroll">
                    <form class="p-5 space-y-4" id="unidadForm" enctype="multipart/form-data" method="post">
                        @csrf
                        <!-- Nombre -->
                        <div class="input-with-icon">
                            <label for="nombre_unidad" class="block text-sm font-medium">Nombre</label>
                            <div class="relative mt-1">
                                <i class="fas fa-balance-scale input-icon"></i>
                                <input id="nombre_unidad" name="nombre" class="clean-input w-full pl-10"
                                    placeholder="Ej: Kilogramos, Litros, Unidades" required>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end items-center mt-4">
                            <button type="button" class="btn btn-outline-danger" @click="unidadOpen = false; $dispatch('modal-unidad-closed')">Cancelar</button>
                            <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Inicializar Select2
        $('.select2-single').select2({
            placeholder: "Seleccione una opción",
            width: '100%',
            minimumResultsForSearch: 5
        });

      
    // ---------------------------
    // 2. Manejo de monedas
    // ---------------------------
    const monedas = @json($monedas);
    let monedaCompraIndex = 0;
    let monedaVentaIndex = 0;
    const btnCompra = document.getElementById("toggleMonedaCompra");
    const btnVenta = document.getElementById("toggleMonedaVenta");
    const symbolCompra = document.getElementById("precio_compra_symbol");
    const symbolVenta = document.getElementById("precio_venta_symbol");
    const monedaInputCompra = document.getElementById("moneda_compra");
    const monedaInputVenta = document.getElementById("moneda_venta");

    if (monedas.length > 0) {
        symbolCompra.textContent = monedas[monedaCompraIndex].nombre;
        monedaInputCompra.value = monedas[monedaCompraIndex].idMonedas;
        symbolVenta.textContent = monedas[monedaVentaIndex].nombre;
        monedaInputVenta.value = monedas[monedaVentaIndex].idMonedas;

        btnCompra.addEventListener("click", function () {
            monedaCompraIndex = (monedaCompraIndex + 1) % monedas.length;
            symbolCompra.textContent = monedas[monedaCompraIndex].nombre;
            monedaInputCompra.value = monedas[monedaCompraIndex].idMonedas;
        });

        btnVenta.addEventListener("click", function () {
            monedaVentaIndex = (monedaVentaIndex + 1) % monedas.length;
            symbolVenta.textContent = monedas[monedaVentaIndex].nombre;
            monedaInputVenta.value = monedas[monedaVentaIndex].idMonedas;
        });
    } else {
        btnCompra.disabled = true;
        btnVenta.disabled = true;
        symbolCompra.textContent = '';
        symbolVenta.textContent = '';
    }

    // ---------------------------
    // 3. Vista previa de PDF
    // ---------------------------
    const inputFicha = document.getElementById('ficha_tecnica');
    if (inputFicha) {
        inputFicha.addEventListener('change', function (e) {
            const file = e.target.files[0];
            const fileName = document.getElementById('nombre_archivo');
            const previewContainer = document.getElementById('preview_pdf');
            const pdfViewer = document.getElementById('pdf_viewer');

            if (file && file.type === 'application/pdf') {
                fileName.textContent = file.name;
                const fileURL = URL.createObjectURL(file);
                pdfViewer.src = fileURL;
                previewContainer.classList.remove('hidden');
            } else {
                fileName.textContent = 'Archivo no válido';
                previewContainer.classList.add('hidden');
            }
        });
    }

    // ---------------------------
// 4. Envío del formulario por AJAX
// ---------------------------
document.getElementById("btnGuardar").addEventListener("click", function () {
    const form = document.getElementById("productoForm");
    const btnGuardar = this;

    // Ejecutar validaciones manuales
    form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));

    // Verificar errores
    const errores = form.querySelectorAll(".error-msg, .error-msg-duplicado");
    if (errores.length > 0) {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return;
    }

    // Deshabilitar botón y mostrar loader
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = `
        <i class="fas fa-spinner fa-spin mr-2"></i> Guardando...
    `;
    btnGuardar.classList.add('opacity-75', 'cursor-not-allowed');

    const formData = new FormData(form);

    fetch("/producto/store", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Restaurar botón a estado normal
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = `
            <i class="fas fa-save mr-2"></i> Guardar Producto
        `;
        btnGuardar.classList.remove('opacity-75', 'cursor-not-allowed');

        if (data.success) {
            toastr.success("Producto guardado correctamente");

            // ✅ Limpiar el formulario
            form.reset();

            // ✅ Limpiar Select2
            $('#idModelo').val(null).trigger('change');

            // ✅ Limpiar preview imagen (usando Alpine.js)
            if (window.Alpine && Alpine.store) {
                // Si estás usando Alpine v3 con store, podrías resetearlo así (opcional)
                Alpine.store('fotoPreview', '');
            } else {
                // Alternativamente, puedes resetear manualmente el contenedor de preview
                document.querySelector('[x-data]').__x.$data.fotoPreview = '';
            }

            // ✅ Limpiar vista previa PDF
            document.getElementById('pdf_viewer').src = '';
            document.getElementById('nombre_archivo').textContent = 'Ningún archivo seleccionado';
            document.getElementById('preview_pdf').classList.add('hidden');

        } else {
            toastr.error("Ocurrió un error al guardar el producto.");
            console.error(data);
        }
    })
    .catch(error => {
        // Restaurar botón a estado normal incluso en caso de error
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = `
            <i class="fas fa-save mr-2"></i> Guardar Producto
        `;
        btnGuardar.classList.remove('opacity-75', 'cursor-not-allowed');
        
        toastr.error("Error en la comunicación con el servidor.");
        console.error(error);
    });
});

    });
    </script>

    <script>
document.getElementById("btnLimpiar").addEventListener("click", function () {
    const form = document.getElementById("productoForm");
    // Limpiar todos los campos
    form.reset();
    // Limpiar Select2
    $('#idModelo').val(null).trigger('change');
    // Limpiar PDF
    document.getElementById('pdf_viewer').src = '';
    document.getElementById('nombre_archivo').textContent = 'Ningún archivo seleccionado';
    document.getElementById('preview_pdf').classList.add('hidden');
});
</script>
    <script src="{{ asset('assets/js/almacen/productos/productosValidaciones.js') }}"></script>
    <script src="{{ asset('assets/js/almacen/productos/modal.js') }}"></script>
   <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</x-layout.default>
