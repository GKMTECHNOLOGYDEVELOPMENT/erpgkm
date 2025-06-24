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
        
        /* Estilos para la tabla de productos del kit */
        .productos-kit-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .productos-kit-table th, 
        .productos-kit-table td {
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            text-align: left;
        }
        
        .productos-kit-table th {
            background-color: #f3f4f6;
            font-weight: 600;
        }
        
        .productos-kit-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .productos-kit-table tr:hover {
            background-color: #f0f0f0;
        }
        
        .btn-eliminar-producto {
            color: #ef4444;
            cursor: pointer;
        }
        
        .btn-agregar-producto {
            margin-top: 1rem;
        }
        
        /* Estilos para el contenedor de productos seleccionados */
        .productos-seleccionados {
            margin-top: 2rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
        }
        
        .productos-seleccionados h3 {
            margin-bottom: 1rem;
            font-size: 1.125rem;
            font-weight: 600;
        }

    </style>

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('almacen.kits.index') }}" class="text-primary hover:underline">
                    <i class="fas fa-arrow-left mr-1"></i> Kits de Productos
                </a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Crear Nuevo Kit</span>
            </li>
        </ul>
    </div>

    <div class="panel mt-6 p-5 max-w-6x2 mx-auto">
        <h2 class="text-xl font-bold mb-5 flex items-center">
            <i class="fas fa-boxes text-primary mr-2"></i> Agregar Nuevo Kit de Productos
        </h2>

        <form id="kitForm" method="POST" enctype="multipart/form-data">
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
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre del Kit</label>
                    <div class="relative mt-1">
                        <i class="fas fa-cogs input-icon"></i>
                        <input id="nombre" name="nombre" type="text" class="clean-input w-full" placeholder="Ingrese nombre del kit" required>
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

                <!-- Foto -->
                <div class="mb-5" x-data="{ 
                    fotoPreview: '/assets/images/articulo/producto-default.png', 
                    defaultImage: '/assets/images/articulo/producto-default.png' 
                }">
                    <label class="block text-sm font-medium text-gray-700">Foto del Kit</label>
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
            </div>

            <!-- Sección para agregar productos al kit -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-box-open mr-2 text-primary"></i> Productos que componen el kit
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <!-- Selección de producto -->
                    <div>
                        <label for="producto_id" class="block text-sm font-medium text-gray-700">Producto</label>
                        <select id="producto_id" class="select2-single w-full mt-1">
                            <option value="" disabled selected>Seleccionar producto</option>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->idArticulos }}" 
                                    data-precio="{{ $producto->precio_venta }}"
                                    data-unidad="{{ $producto->unidad->nombre ?? '' }}">
                                    {{ $producto->nombre }} ({{ $producto->codigo_barras }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cantidad -->
                    <div>
                        <label for="cantidad_producto" class="block text-sm font-medium text-gray-700">Cantidad</label>
                        <input type="number" id="cantidad_producto" min="1" value="1" 
                            class="clean-input w-full mt-1" placeholder="Cantidad">
                    </div>

                    <!-- Botón para agregar -->
                    <div class="flex items-end">
                        <button type="button" id="btnAgregarProducto" 
                            class="btn btn-primary h-10 flex items-center">
                            <i class="fas fa-plus mr-2"></i> Agregar Producto
                        </button>
                    </div>
                </div>

                <!-- Tabla de productos agregados al kit -->
                <div class="productos-seleccionados">
                    <h3 class="flex items-center">
                        <i class="fas fa-list-ul mr-2"></i> Productos seleccionados
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="productos-kit-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Código</th>
                                    <th>Unidad</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="productos-kit-body">
                                <!-- Aquí se agregarán dinámicamente los productos -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right font-semibold">Total estimado:</td>
                                    <td id="total-kit" class="font-semibold">S/ 0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Campo oculto para almacenar los productos del kit -->
                <input type="hidden" id="productos_kit" name="productos_kit" value="">
            </div>

            <div class="flex justify-end mt-6 gap-4">
                <!-- Cancelar -->
                <a href="{{ route('almacen.kits.index') }}" class="btn btn-outline-danger flex items-center">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>

                <!-- Limpiar -->
                <button type="button" id="btnLimpiar" class="btn btn-outline-warning flex items-center">
                    <i class="fas fa-eraser mr-2"></i> Limpiar
                </button>

                <!-- Guardar -->
                <button type="button" id="btnGuardar" class="btn btn-primary flex items-center">
                    <i class="fas fa-save mr-2"></i> Guardar Kit
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Inicializar Select2
        $('.select2-single').select2({
            placeholder: "Seleccione una opción",
            width: '100%',
            minimumResultsForSearch: 5
        });

        // Variables para manejar los productos del kit
        let productosKit = [];
        let totalKit = 0;

        // ---------------------------
        // 1. Manejo de monedas
        // ---------------------------
        const monedas = @json($monedas);
        let monedaVentaIndex = 0;
        const btnVenta = document.getElementById("toggleMonedaVenta");
        const symbolVenta = document.getElementById("precio_venta_symbol");
        const monedaInputVenta = document.getElementById("moneda_venta");

        if (monedas.length > 0) {
            symbolVenta.textContent = monedas[monedaVentaIndex].nombre;
            monedaInputVenta.value = monedas[monedaVentaIndex].idMonedas;

            btnVenta.addEventListener("click", function () {
                monedaVentaIndex = (monedaVentaIndex + 1) % monedas.length;
                symbolVenta.textContent = monedas[monedaVentaIndex].nombre;
                monedaInputVenta.value = monedas[monedaVentaIndex].idMonedas;
            });
        } else {
            btnVenta.disabled = true;
            symbolVenta.textContent = '';
        }

        // ---------------------------
        // 2. Agregar productos al kit
        // ---------------------------
        document.getElementById("btnAgregarProducto").addEventListener("click", function() {
            const productoSelect = document.getElementById("producto_id");
            const cantidadInput = document.getElementById("cantidad_producto");
            
            const productoId = productoSelect.value;
            const productoText = productoSelect.options[productoSelect.selectedIndex].text;
            const cantidad = parseInt(cantidadInput.value);
            const precioUnitario = parseFloat(productoSelect.options[productoSelect.selectedIndex].dataset.precio);
            const unidad = productoSelect.options[productoSelect.selectedIndex].dataset.unidad;
            
            if (!productoId || isNaN(cantidad) || cantidad <= 0) {
                toastr.warning("Seleccione un producto y una cantidad válida");
                return;
            }
            
            // Verificar si el producto ya está en el kit
            const productoExistente = productosKit.find(p => p.id === productoId);
            
            if (productoExistente) {
                // Actualizar cantidad si ya existe
                productoExistente.cantidad += cantidad;
                productoExistente.subtotal = productoExistente.cantidad * productoExistente.precio;
                
                // Actualizar fila en la tabla
                document.getElementById(`cantidad-${productoId}`).textContent = productoExistente.cantidad;
                document.getElementById(`subtotal-${productoId}`).textContent = `S/ ${productoExistente.subtotal.toFixed(2)}`;
            } else {
                // Agregar nuevo producto al kit
                const nuevoProducto = {
                    id: productoId,
                    nombre: productoText.split(' (')[0],
                    codigo: productoText.match(/\(([^)]+)\)/)[1],
                    unidad: unidad,
                    cantidad: cantidad,
                    precio: precioUnitario,
                    subtotal: cantidad * precioUnitario
                };
                
                productosKit.push(nuevoProducto);
                
                // Agregar fila a la tabla
                const tbody = document.getElementById("productos-kit-body");
                const row = document.createElement("tr");
                row.id = `row-${productoId}`;
                row.innerHTML = `
                    <td>${nuevoProducto.nombre}</td>
                    <td>${nuevoProducto.codigo}</td>
                    <td>${nuevoProducto.unidad}</td>
                    <td id="cantidad-${productoId}">${nuevoProducto.cantidad}</td>
                    <td>S/ ${nuevoProducto.precio.toFixed(2)}</td>
                    <td id="subtotal-${productoId}">S/ ${nuevoProducto.subtotal.toFixed(2)}</td>
                    <td class="text-center">
                        <button type="button" onclick="eliminarProductoKit('${productoId}')" class="btn-eliminar-producto">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            }
            
            // Actualizar total del kit
            actualizarTotalKit();
            
            // Limpiar selección
            productoSelect.value = "";
            cantidadInput.value = 1;
            $(productoSelect).trigger('change');
        });

        // ---------------------------
        // 3. Actualizar total del kit
        // ---------------------------
        function actualizarTotalKit() {
            totalKit = productosKit.reduce((sum, producto) => sum + producto.subtotal, 0);
            document.getElementById("total-kit").textContent = `S/ ${totalKit.toFixed(2)}`;
            
            // Actualizar campo oculto con los productos del kit
            document.getElementById("productos_kit").value = JSON.stringify(productosKit);
        }

        // ---------------------------
        // 4. Eliminar producto del kit
        // ---------------------------
        window.eliminarProductoKit = function(productoId) {
            productosKit = productosKit.filter(p => p.id !== productoId);
            document.getElementById(`row-${productoId}`).remove();
            actualizarTotalKit();
        };

        // ---------------------------
        // 5. Envío del formulario por AJAX
        // ---------------------------
        document.getElementById("btnGuardar").addEventListener("click", function () {
            const form = document.getElementById("kitForm");
            
            // Validar que haya al menos un producto en el kit
            if (productosKit.length === 0) {
                toastr.error("Debe agregar al menos un producto al kit");
                return;
            }

            const formData = new FormData(form);

            fetch("/kits/store", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success("Kit guardado correctamente");
                    
                    // Limpiar el formulario
                    form.reset();
                    productosKit = [];
                    document.getElementById("productos-kit-body").innerHTML = "";
                    document.getElementById("total-kit").textContent = "S/ 0.00";
                    
                    // Limpiar Select2
                    $('#producto_id').val(null).trigger('change');
                    
                    // Limpiar preview imagen
                    if (window.Alpine && Alpine.store) {
                        Alpine.store('fotoPreview', '');
                    } else {
                        document.querySelector('[x-data]').__x.$data.fotoPreview = '';
                    }
                } else {
                    toastr.error(data.message || "Ocurrió un error al guardar el kit.");
                    console.error(data);
                }
            })
            .catch(error => {
                toastr.error("Error en la comunicación con el servidor.");
                console.error(error);
            });
        });

        // ---------------------------
        // 6. Limpiar formulario
        // ---------------------------
        document.getElementById("btnLimpiar").addEventListener("click", function () {
            const form = document.getElementById("kitForm");
            form.reset();
            productosKit = [];
            document.getElementById("productos-kit-body").innerHTML = "";
            document.getElementById("total-kit").textContent = "S/ 0.00";
            $('#producto_id').val(null).trigger('change');
            
            // Limpiar preview imagen
            if (window.Alpine && Alpine.store) {
                Alpine.store('fotoPreview', '');
            } else {
                document.querySelector('[x-data]').__x.$data.fotoPreview = '';
            }
        });
    });
    </script>

        <script src="{{ asset('assets/js/kit/kitValidaciones.js') }}"></script>

</x-layout.default>