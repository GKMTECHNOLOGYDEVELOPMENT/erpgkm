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
            /* asegúrate de dejar espacio al ícono */
            padding-bottom: 8px;
            padding-top: 8px;
            background-color: transparent;
            height: 40px;
            /* controla la altura si es necesario */
            line-height: 1.25rem;
            font-size: 0.875rem;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 12px;
            pointer-events: none;
            z-index: 10;
        }

        .select2-container--default .select2-selection--multiple {
            background-color: transparent !important;
            border: none !important;
            border-bottom: 1px solid #e0e6ed !important;
            border-radius: 0;
            padding-left: 5px;
            padding-bottom: 5px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-bottom: 2px solid #3b82f6;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: #000 !important;
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

        /* Ajustes específicos para Select2 */
        .select2-container--default .select2-selection--multiple {
            padding-left: 35px !important;
            min-height: 40px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding-left: 0;
        }

        /* Quita el overflow del contenedor externo */
        .select2-container--default .select2-selection--multiple {
            max-height: none;
            overflow: visible;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
        }

        /* Mantén el scroll solo aquí */
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            max-height: 80px;
            overflow-y: auto;
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
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 6px !important;
            right: 10px !important;
        }
        .clean-input::placeholder {
            font-size: 0.85rem;
            /* o 0.75rem si lo quieres aún más pequeño */

        }
    </style>

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('repuestos.index') }}" class="text-primary hover:underline">
                    <i class="fas fa-arrow-left mr-1"></i> Repuestos
                </a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Editar Repuesto </span>
            </li>
        </ul>
    </div>

    <div class="panel mt-6 p-5 max-w-6x2 mx-auto">
        <h2 class="text-xl font-bold mb-5 flex items-center">
            <i class="fas fa-edit text-primary mr-2"></i> Editar Repuesto
        </h2>


        <form id="repuestosForm" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" id="idArticulo" value="{{ $articulo->idArticulos }}">


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Código de Barras -->
                <div class="relative">
                    <label for="codigo_barras" class="block text-sm font-medium text-gray-700">Código de Barras</label>
                    <div class="relative mt-1">
                        <i class="fas fa-barcode input-icon"></i>
                        <input id="codigo_barras" name="codigo_barras" type="text" class="clean-input w-full"
                            value="{{ old('codigo_barras', $articulo->codigo_barras) }}"
                            placeholder="Ingrese código de barras">

                    </div>
                </div>

                <!-- SKU -->
                <div class="relative">
                    <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                    <div class="relative mt-1">
                        <i class="fas fa-tag input-icon"></i>
                        <input id="sku" name="sku" type="text" class="clean-input w-full"
                            value="{{ old('sku', $articulo->sku) }}"
                            placeholder="Ingrese SKU">
                    </div>
                </div>

                <!-- Código Repuesto -->
                <div class="relative">
                    <label for="codigo_repuesto" class="block text-sm font-medium text-gray-700">Código Repuesto</label>
                    <div class="relative mt-1">
                        <i class="fas fa-code input-icon"></i>
                        <input id="codigo_repuesto" name="codigo_repuesto" type="text" class="clean-input w-full"
                            value="{{ old('codigo_repuesto', $articulo->codigo_repuesto) }}"
                            placeholder="Ingrese código de repuesto">

                    </div>
                </div>

                @php
                $modelosSeleccionados = $articulo->modelos->pluck('idModelo')->toArray();
                @endphp


                <!-- Modelo (Multiple Select) -->
                <div>
                    <label for="idModelo" class="block text-sm font-medium text-gray-700">Modelos</label>
                    <div class="relative mt-1">
                        <i class="fas fa-cubes input-icon"></i>
                        <!-- <i class="fas fa-car input-icon"></i> -->
                        <select id="idModelo" name="idModelo[]" class="select2-multiple w-full" multiple="multiple">
                            @foreach ($modelos as $modelo)
                            <option value="{{ $modelo->idModelo }}"
                                data-marca="{{ $modelo->idMarca }}"
                                data-categoria="{{ $modelo->idCategoria }}"
                                {{ in_array($modelo->idModelo, $modelosSeleccionados) ? 'selected' : '' }}>
                                {{ $modelo->nombre }} -
                                {{ $modelo->marca->nombre ?? 'Sin Marca' }} -
                                {{ $modelo->categoria->nombre ?? 'Sin Categoría' }}
                            </option>
                            @endforeach

                        </select>
                    </div>
                </div>

                <!-- Subcategoría -->
                <div class="relative input-with-icon">
                    <label for="idsubcategoria" class="block text-sm font-medium text-gray-700">Sub Categoría</label>
                    <div class="relative mt-1">
                        <i class="fas fa-folder input-icon"></i>
                        <select id="idsubcategoria" name="idsubcategoria" class="select2-single clean-input w-full pl-10"
                            style="appearance: none;">
                            <option value="" disabled>Seleccionar Subcategoría</option>
                            @foreach ($subcategorias as $subcategoria)
                                <option value="{{ $subcategoria->id }}"
                                    {{ $subcategoria->id == $articulo->idsubcategoria ? 'selected' : '' }}>
                                    {{ $subcategoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>



                   

                <!-- Precio Compra -->
                <div>
                    <label for="precio_compra" class="block text-sm font-medium text-gray-700">Precio de Compra</label>
                    <div class="flex items-center mt-1">
                        <button type="button" id="toggleMonedaCompra"
                            class="text-gray-500 px-2 h-10 border-b border-gray-300">
                            <span id="precio_compra_symbol" class="w-8 text-center">S/</span>
                        </button>
                        <div class="relative flex-1">
                            <!-- <i class="fas fa-dollar-sign input-icon"></i> -->
                            <input id="precio_compra" name="precio_compra" type="number" step="0.01"
                                class="clean-input w-full"
                                value="{{ old('precio_compra', $articulo->precio_compra) }}"
                                placeholder="0.00">

                        </div>
                        <input type="hidden" id="moneda_compra" name="moneda_compra" value="0">
                    </div>
                </div>

                <!-- Precio Venta -->
                <div>
                    <label for="precio_venta" class="block text-sm font-medium text-gray-700">Precio de Venta</label>
                    <div class="flex items-center mt-1">
                        <button type="button" id="toggleMonedaVenta"
                            class="text-gray-500 px-2 h-10 border-b border-gray-300">
                            <span id="precio_venta_symbol" class="w-8 text-center">S/</span>
                        </button>
                        <div class="relative flex-1">
                            <input id="precio_venta" name="precio_venta" type="number" step="0.01"
                                class="clean-input w-full"
                                value="{{ old('precio_venta', $articulo->precio_venta) }}"
                                placeholder="0.00">

                        </div>
                        <input type="hidden" id="moneda_venta" name="moneda_venta" value="0">
                    </div>
                </div>

                <!-- Stock Total -->
                <div class="relative">
                    <label for="stock_total" class="block text-sm font-medium text-gray-700">Stock Total</label>
                    <div class="relative mt-1">
                        <i class="fas fa-boxes input-icon"></i>
                        <input id="stock_total" name="stock_total" type="number" class="clean-input w-full"
                            value="{{ old('stock_total', $articulo->stock_total) }}"
                            placeholder="Ingrese stock total">

                    </div>
                </div>

                <!-- Stock Mínimo -->
                <div class="relative">
                    <label for="stock_minimo" class="block text-sm font-medium text-gray-700">Stock Mínimo</label>
                    <div class="relative mt-1">
                        <i class="fas fa-exclamation-triangle input-icon"></i>
                        <input id="stock_minimo" name="stock_minimo" type="number" class="clean-input w-full"
                            value="{{ old('stock_minimo', $articulo->stock_minimo) }}"
                            placeholder="Ingrese stock mínimo">

                    </div>
                </div>

                <!-- Unidad de Medida -->
                <div class="relative">
                    <label for="idUnidad" class="block text-sm font-medium text-gray-700">Unidad de Medida</label>
                    <div class="relative mt-1">
                        <i class="fas fa-balance-scale input-icon"></i>
                        <select id="idUnidad" name="idUnidad" class="clean-input w-full pl-8" style="appearance: none;">
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

                <!-- Pulgadas -->
                <div class="relative">
                    <label for="pulgadas" class="block text-sm font-medium text-gray-700">Pulgadas</label>
                    <div class="relative mt-1">
                        <i class="fas fa-ruler input-icon"></i>
                        <input id="pulgadas" name="pulgadas" type="text" class="clean-input w-full"
                            value="{{ old('pulgadas', $articulo->pulgadas) }}"
                            placeholder="Ej: 14'', 15'', etc.">

                    </div>
                </div>
                   <!-- Estado del Artículo -->
                <div>
                    <label for="estado" class="block text-sm font-medium">Estado</label>
                    <div class="ml-4 w-12 h-6 relative">
                        <input type="hidden" name="estado" value="0">
                        <input type="checkbox" id="estado" name="estado"
                            class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer" value="1"
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
                    <label for="foto" class="inline-block text-sm font-semibold px-3 py-1.5 rounded cursor-pointer hover:bg-blue-700 transition">
                        <i class="fas fa-upload mr-1"></i> Seleccionar archivo</label>
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
                           <img 
                                :src="fotoPreview !== defaultImage ? fotoPreview : '{{ $articulo->foto ? 'data:image/jpeg;base64,' . base64_encode($articulo->foto) : asset('assets/images/articulo/producto-default.png') }}'" 
                                alt="Previsualización de la imagen" 
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
                    <div class="relative w-full" style="padding-top: 50%;"> <!-- 50% = aspecto 2:1 -->
                        <iframe id="pdf_viewer" class="absolute top-0 left-0 w-full h-full border rounded"
                            type="application/pdf"></iframe>
                    </div>
                </div>


                @if ($articulo->ficha_tecnica)
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        document.getElementById('nombre_archivo').textContent = "{{ basename($articulo->ficha_tecnica) }}";
                        document.getElementById('pdf_viewer').src = "{{ asset('storage/fichas/' . $articulo->ficha_tecnica) }}";
                    });
                </script>
                @endif



            </div>
            <div class="flex justify-end mt-6 gap-4">
                <!-- Cancelar -->
                <a href="{{ route('articulos.index') }}" class="btn btn-outline-danger flex items-center">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>

             

                <!-- Guardar -->
                <button type="button" id="btnGuardar" class="btn btn-primary flex items-center">
                    <i class="fas fa-save mr-2"></i> Actualizar Repuesto
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
            // ---------------------------
            // 1. Inicializar Select2
            // ---------------------------
            $('.select2-multiple').select2({
                placeholder: "Seleccione uno o más modelos",
                allowClear: true,
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

 document.getElementById("btnGuardar").addEventListener("click", function () {
    const form = document.getElementById("repuestosForm");

    // Ejecutar validaciones manuales
    form.dispatchEvent(new Event('submit', {
        cancelable: true,
        bubbles: true
    }));

    const errores = form.querySelectorAll(".error-msg, .error-msg-duplicado");
    if (errores.length > 0) {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return;
    }

    const formData = new FormData(form);

    // ✅ Agregar _method = PUT para que Laravel lo acepte como actualización
    formData.append('_method', 'PUT');

    // ✅ Este ID debe venir de un campo oculto en el formulario
    const idArticulo = document.getElementById("idArticulo").value;

    const url = `/repuestos/update/${idArticulo}`;
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
        if (data.success) {
            toastr.success("Repuesto actualizado correctamente");

            // ✅ Redireccionar o lo que quieras hacer después
            // window.location.href = "/repuestos";

        } else {
            toastr.error("Error al actualizar el repuesto.");
            console.error("❌ Error del servidor:", data);
        }
    })
    .catch(error => {
        toastr.error("Error en la comunicación con el servidor.");
        console.error("🚨 Error AJAX:", error);
    });
});



        });
    </script>

    <script>
        document.getElementById("btnLimpiar").addEventListener("click", function() {
            const form = document.getElementById("repuestosForm");
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







    <script src="{{ asset('assets/js/almacen/repuesto/repuestoValidacionesupdate.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</x-layout.default>