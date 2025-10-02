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
            padding-left: 35px;
            padding-bottom: 8px;
            padding-top: 8px;
            background-color: transparent;
            height: 40px;
            line-height: 1.25rem;
            font-size: 0.875rem;
            /* <-- mismo tamaño que label */
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
                <span>Editar Producto</span>
            </li>
        </ul>
    </div>

    <div class="panel mt-6 p-5 max-w-6x2 mx-auto">
        <h2 class="text-xl font-bold mb-5 flex items-center">
            <i class="fas fa-edit text-primary mr-2"></i> Editar Producto
        </h2>


        <form id="productoForm" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" id="idArticulo" value="{{ $articulo->idArticulos }}">


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Código de Barras -->
                <div class="relative">
                    <label for="codigo_barras" class="block text-sm font-medium text-gray-700">Código de Barras</label>
                    <div class="relative mt-1">
                        <i class="fas fa-barcode input-icon"></i>
                        <input id="codigo_barras" name="codigo_barras" type="text" class="clean-input w-full"
                            placeholder="Ingrese código de barras" value="{{ $articulo->codigo_barras }}" required>
                    </div>
                </div>

                <!-- SKU -->
                <div class="relative">
                    <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                    <div class="relative mt-1">
                        <i class="fas fa-tag input-icon"></i>
                        <input id="sku" name="sku" type="text" class="clean-input w-full"
                            placeholder="Ingrese SKU" value="{{ $articulo->sku }}">
                    </div>
                </div>

                <!-- Nombre -->
                <div class="relative">
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <div class="relative mt-1">
                        <i class="fas fa-cogs input-icon"></i>
                        <input id="nombre" name="nombre" type="text" class="clean-input w-full"
                            placeholder="Ingrese nombre" required value="{{ $articulo->nombre }}">
                    </div>
                </div>

                <!-- Stock Total -->
                <div class="relative">
                    <label for="stock_total" class="block text-sm font-medium text-gray-700">Stock Total</label>
                    <div class="relative mt-1">
                        <i class="fas fa-boxes input-icon"></i>
                        <input id="stock_total" name="stock_total" type="number" min="0"
                            class="clean-input w-full bg-gray-100 cursor-not-allowed" placeholder="Ingrese stock total"
                            required value="{{ $articulo->stock_total }}" readonly>
                    </div>
                </div>


                <!-- Stock Mínimo -->
                <div class="relative">
                    <label for="stock_minimo" class="block text-sm font-medium text-gray-700">Stock Mínimo</label>
                    <div class="relative mt-1">
                        <i class="fas fa-boxes input-icon"></i>
                        <input id="stock_minimo" name="stock_minimo" type="number" min="0"
                            class="clean-input w-full" placeholder="Ingrese stock mínimo" required
                            value="{{ $articulo->stock_minimo }}">
                    </div>
                </div>

                <!-- ✅ Maneja Serie (NUEVO CAMPO) -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maneja Serie</label>
                    <div class="flex items-center mt-1">
                        <label class="inline-flex items-center cursor-pointer">
                            <input id="maneja_serie" name="maneja_serie" type="checkbox" value="1"
                                class="form-checkbox text-primary rounded"
                                {{ $articulo->maneja_serie ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600">¿Este producto maneja números de serie?</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        Si está marcado, el producto requerirá números de serie únicos para cada unidad
                    </p>
                </div>

                <!-- Unidad de Medida -->
                <div class="relative">
                    <label for="idUnidad" class="block text-sm font-medium text-gray-700">Unidad de Medida</label>
                    <div class="relative mt-1">
                        <i class="fas fa-balance-scale input-icon"></i>
                        <select id="idUnidad" name="idUnidad" class="select2-single clean-input w-full pl-10">
                            <option value="" disabled selected>Seleccionar Unidad</option>
                            @foreach ($unidades as $unidad)
                                <option value="{{ $unidad->idUnidad }}"
                                    {{ $unidad->idUnidad == $articulo->idUnidad ? 'selected' : '' }}>
                                    {{ $unidad->nombre }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>


                <!-- Modelo -->
                <div>
                    <label for="idModelo" class="block text-sm font-medium text-gray-700">Modelo</label>
                    <div class="relative mt-1">
                        <i class="fas fa-cubes input-icon"></i>
                        <select id="idModelo" name="idModelo" class="select2-single w-full" required>
                            <option value="" disabled {{ is_null($articulo->idModelo) ? 'selected' : '' }}>
                                Seleccionar modelo
                            </option>
                            @foreach ($modelos as $modelo)
                                <option value="{{ $modelo->idModelo }}"
                                    {{ $modelo->idModelo == $articulo->idModelo ? 'selected' : '' }}>
                                    {{ $modelo->nombre }} - {{ $modelo->marca->nombre ?? 'Sin Marca' }} -
                                    {{ $modelo->categoria->nombre ?? 'Sin Categoría' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <!-- Peso -->
                <div>
                    <label for="peso" class="block text-sm font-medium text-gray-700">Peso (kg)</label>
                    <div class="relative mt-1">
                        <i class="fas fa-weight input-icon"></i>
                        <input id="peso" name="peso" type="number" step="0.01" min="0"
                            class="clean-input w-full" placeholder="Ingrese peso" value="{{ $articulo->peso }}"
                            required>
                    </div>
                </div>

                <!-- Garantía de Fábrica -->
                <div class="relative">
                    <label for="garantia_fabrica" class="block text-sm font-medium text-gray-700">Garantía de
                        Fábrica</label>
                    <div class="relative mt-1">
                        <i class="fas fa-shield-alt input-icon"></i>
                        <input id="garantia_fabrica" name="garantia_fabrica" type="number" min="0"
                            class="clean-input w-full" placeholder="Tiempo de garantía"
                            value="{{ $articulo->garantia_fabrica ?? 0 }}">
                    </div>
                </div>

                <!-- Unidad de Tiempo de Garantía -->
                <div class="relative" id="unidad_tiempo_container"
                    style="{{ ($articulo->garantia_fabrica ?? 0) > 0 ? '' : 'display: none;' }}">
                    <label for="unidad_tiempo_garantia" class="block text-sm font-medium text-gray-700">Unidad de
                        Tiempo</label>
                    <div class="relative mt-1">
                        <i class="fas fa-clock input-icon"></i>
                        <select id="unidad_tiempo_garantia" name="unidad_tiempo_garantia"
                            class="select2-single clean-input w-full pl-10">
                            <option value="dias"
                                {{ ($articulo->unidad_tiempo_garantia ?? 'meses') == 'dias' ? 'selected' : '' }}>Días
                            </option>
                            <option value="semanas"
                                {{ ($articulo->unidad_tiempo_garantia ?? 'meses') == 'semanas' ? 'selected' : '' }}>
                                Semanas</option>
                            <option value="meses"
                                {{ ($articulo->unidad_tiempo_garantia ?? 'meses') == 'meses' ? 'selected' : '' }}>Meses
                            </option>
                            <option value="años"
                                {{ ($articulo->unidad_tiempo_garantia ?? 'meses') == 'años' ? 'selected' : '' }}>Años
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Proveedor -->
                <div class="relative input-with-icon">
                    <label for="idProveedor" class="block text-sm font-medium text-gray-700">Proveedor</label>
                    <div class="relative mt-1">
                        <i class="fas fa-truck input-icon"></i>
                        <select id="idProveedor" name="idProveedor" class="select2-single clean-input w-full pl-10">
                            <option value="" {{ is_null($articulo->idProveedor) ? 'selected' : '' }}>Seleccionar
                                proveedor (opcional)</option>
                            @foreach ($proveedores as $proveedor)
                                <option value="{{ $proveedor->idProveedor }}"
                                    {{ $proveedor->idProveedor == $articulo->idProveedor ? 'selected' : '' }}>
                                    {{ $proveedor->nombre }} - {{ $proveedor->numeroDocumento }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Precio de Compra -->
                <div>
                    <label for="precio_compra" class="block text-sm font-medium text-gray-700">Precio de
                        Compra</label>
                    <div class="flex items-center mt-1">
                        <button type="button" id="toggleMonedaCompra"
                            class="text-gray-500 px-2 h-10 border-b border-gray-300">
                            <span id="precio_compra_symbol" class="w-8 text-center">S/</span>
                        </button>
                        <div class="relative flex-1">
                            <input id="precio_compra" name="precio_compra" type="number" step="0.01"
                                min="0" class="clean-input w-full" placeholder="0.00" required
                                value="{{ $articulo->precio_compra }}">
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
                            <input id="precio_venta" name="precio_venta" type="number" step="0.01"
                                min="0" class="clean-input w-full" placeholder="0.00" required
                                value="{{ $articulo->precio_venta }}">
                        </div>
                        <input type="hidden" id="moneda_venta" name="moneda_venta" value="0">
                    </div>
                </div>
                <!-- Estado del Artículo -->
                <div>
                    <label for="estado" class="block text-sm font-medium">Estado</label>
                    <div class="ml-4 w-12 h-6 relative">
                        <input type="hidden" name="estado" value="0">
                        <input type="checkbox" id="estado" name="estado"
                            class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                            value="1"
                            {{ old('estado', isset($articulo) ? $articulo->estado : 1) ? 'checked' : '' }} />
                        <span
                            class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full 
                            before:absolute before:left-1 before:bg-white dark:before:bg-white-dark 
                            dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 
                            before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary 
                            before:transition-all before:duration-300">
                        </span>
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
                            <img :src="fotoPreview !== defaultImage ? fotoPreview :
                                '{{ $articulo->foto ? 'data:image/jpeg;base64,' . base64_encode($articulo->foto) : asset('assets/images/articulo/producto-default.png') }}'"
                                alt="Previsualización de la imagen" class="w-full h-full object-contain">

                        </div>
                    </div>
                </div>

                <!-- Ficha Técnica -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700">Ficha Técnica (PDF)</label>
                    <label for="ficha_tecnica"
                        class="inline-block text-sm font-semibold px-3 py-1.5 rounded cursor-pointer hover:bg-blue-700 transition">
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
                    <div class="relative w-full" style="padding-top: 50%;"> <!-- 50% = aspecto 2:1 -->
                        <iframe id="pdf_viewer" class="absolute top-0 left-0 w-full h-full border rounded"
                            type="application/pdf"></iframe>
                    </div>

                </div>


                @if ($articulo->ficha_tecnica)
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            document.getElementById('nombre_archivo').textContent = "{{ basename($articulo->ficha_tecnica) }}";
                            document.getElementById('pdf_viewer').src =
                                "{{ asset('storage/fichas/' . $articulo->ficha_tecnica) }}";
                        });
                    </script>
                @endif





            </div>
            <div class="flex justify-end mt-6 gap-4">
                <!-- Cancelar -->
                <a href="{{ route('producto.index') }}" class="btn btn-outline-danger flex items-center">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>

                <!-- Limpiar
            <button type="button" id="btnLimpiar" class="btn btn-outline-warning flex items-center">
                <i class="fas fa-eraser mr-2"></i> Limpiar
            </button> -->

                <!-- Guardar -->
                <button type="button" id="btnGuardar" class="btn btn-primary flex items-center">
                    <i class="fas fa-save mr-2"></i> Actualizar Producto
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @php
        $monedaCompraId = $articulo->moneda_compra;
        $monedaVentaId = $articulo->moneda_venta;
    @endphp

    <script>
        window.idArticulo = {{ $articulo->idArticulos ?? 'null' }};
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializar Select2
            $('.select2-single').select2({
                placeholder: "Seleccione un modelo",
                width: '100%',
                minimumResultsForSearch: 5
            });


            // ---------------------------
            // 2. Manejo de monedas
            // ---------------------------
            const monedas = @json($monedas);
            const monedaCompraId = {{ $monedaCompraId ?? 'null' }};
            const monedaVentaId = {{ $monedaVentaId ?? 'null' }};

            let monedaCompraIndex = monedas.findIndex(m => m.idMonedas == monedaCompraId);
            let monedaVentaIndex = monedas.findIndex(m => m.idMonedas == monedaVentaId);

            if (monedaCompraIndex === -1) monedaCompraIndex = 0;
            if (monedaVentaIndex === -1) monedaVentaIndex = 0;

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

                btnCompra.addEventListener("click", function() {
                    monedaCompraIndex = (monedaCompraIndex + 1) % monedas.length;
                    symbolCompra.textContent = monedas[monedaCompraIndex].nombre;
                    monedaInputCompra.value = monedas[monedaCompraIndex].idMonedas;
                });

                btnVenta.addEventListener("click", function() {
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
                inputFicha.addEventListener('change', function(e) {
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
            // 4. Actualizar del formulario por AJAX con preloader
            // ---------------------------
            document.getElementById("btnGuardar").addEventListener("click", function() {
                const form = document.getElementById("productoForm");
                const btnGuardar = this;

                // Ejecutar validaciones manuales
                form.dispatchEvent(new Event('submit', {
                    cancelable: true,
                    bubbles: true
                }));

                const errores = form.querySelectorAll(".error-msg, .error-msg-duplicado");
                if (errores.length > 0) {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                    return;
                }

                // Deshabilitar botón y mostrar loader
                btnGuardar.disabled = true;
                btnGuardar.innerHTML = `
        <i class="fas fa-spinner fa-spin mr-2"></i> Actualizando...
    `;
                btnGuardar.classList.add('opacity-75', 'cursor-not-allowed');

                const formData = new FormData(form);

                // ✅ Agregar _method = PUT para que Laravel lo acepte como actualización
                formData.append('_method', 'PUT');

                // ✅ Este ID debe venir de un campo oculto en el formulario
                const idArticulo = document.getElementById("idArticulo").value;

                const url = `/producto/update/${idArticulo}`;
                console.log("📤 Enviando datos para actualización a:", url);

                fetch(url, {
                        method: "POST", // Laravel interpretará como PUT gracias a _method
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
            <i class="fas fa-save mr-2"></i> Actualizar Producto
        `;
                        btnGuardar.classList.remove('opacity-75', 'cursor-not-allowed');

                        if (data.success) {
                            toastr.success("Producto actualizado correctamente");

                            // ✅ Redireccionar después de 2 segundos para que el usuario vea el mensaje
                            // setTimeout(() => {
                            //     window.location.href = "/producto"; // Cambia por tu ruta deseada
                            // }, 2000);

                        } else {
                            toastr.error("Error al actualizar el producto.");
                            console.error("❌ Error del servidor:", data);
                        }
                    })
                    .catch(error => {
                        // Restaurar botón a estado normal incluso en caso de error
                        btnGuardar.disabled = false;
                        btnGuardar.innerHTML = `
            <i class="fas fa-save mr-2"></i> Actualizar Producto
        `;
                        btnGuardar.classList.remove('opacity-75', 'cursor-not-allowed');

                        toastr.error("Error en la comunicación con el servidor.");
                        console.error("🚨 Error AJAX:", error);
                    });
            });

        });
    </script>
    <script>
        document.getElementById("btnLimpiar").addEventListener("click", function() {
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
    <script src="{{ asset('assets/js/almacen/productos/productosValidacionesUpdate.js') }}"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <script>
        // Función para mostrar/ocultar unidad de tiempo según garantía
        function toggleUnidadTiempo() {
            const garantiaInput = document.getElementById('garantia_fabrica');
            const unidadTiempoContainer = document.getElementById('unidad_tiempo_container');

            if (garantiaInput && unidadTiempoContainer) {
                if (garantiaInput.value > 0) {
                    unidadTiempoContainer.style.display = 'block';
                } else {
                    unidadTiempoContainer.style.display = 'none';
                }
            }
        }

        // Inicializar al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            toggleUnidadTiempo();

            // Event listener para cambios en garantía
            const garantiaInput = document.getElementById('garantia_fabrica');
            if (garantiaInput) {
                garantiaInput.addEventListener('input', toggleUnidadTiempo);
            }

            // Inicializar Select2 para el nuevo campo de proveedor
            $('#idProveedor').select2({
                placeholder: "Seleccione un proveedor",
                width: '100%',
                allowClear: true
            });
        });
    </script>
</x-layout.default>
