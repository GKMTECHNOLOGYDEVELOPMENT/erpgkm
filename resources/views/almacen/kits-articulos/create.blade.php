<x-layout.default>
    <!-- (Mantén todos los estilos y scripts existentes) -->

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('producto.index') }}" class="text-primary hover:underline">
                    <i class="fas fa-arrow-left mr-1"></i> Productos
                </a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Crear Kit de Productos</span>
            </li>
        </ul>
    </div>

    <div class="panel mt-6 p-5 max-w-6x2 mx-auto">
        <h2 class="text-xl font-bold mb-5 flex items-center">
            <i class="fas fa-boxes text-primary mr-2"></i> Crear Nuevo Kit
        </h2>

        <form id="kitForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="es_kit" value="1">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Código del Kit -->
                <div class="relative">
                    <label for="codigo_barras" class="block text-sm font-medium text-gray-700">Código del Kit</label>
                    <div class="relative mt-1">
                        <i class="fas fa-barcode input-icon"></i>
                        <input id="codigo_barras" name="codigo_barras" type="text" class="clean-input w-full"
                            placeholder="Ingrese código único del kit" required>
                    </div>
                </div>

                <!-- Nombre del Kit -->
                <div class="relative">
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre del Kit</label>
                    <div class="relative mt-1">
                        <i class="fas fa-cogs input-icon"></i>
                        <input id="nombre" name="nombre" type="text" class="clean-input w-full" 
                               placeholder="Ej: Kit de Cámaras de Seguridad" required>
                    </div>
                </div>

                <!-- Precio del Kit -->
                <div>
                    <label for="precio_venta" class="block text-sm font-medium text-gray-700">Precio del Kit</label>
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

                <!-- Foto del Kit -->
                <div class="mb-5" x-data="{ 
                    fotoPreview: '/assets/images/articulo/kit-default.png', 
                    defaultImage: '/assets/images/articulo/kit-default.png' 
                }">
                    <label class="block text-sm font-medium text-gray-700">Imagen del Kit</label>
                    <label for="foto"
                        class="inline-block text-sm font-semibold px-3 py-1.5 rounded cursor-pointer hover:bg-blue-700 transition">
                        <i class="fas fa-upload mr-1"></i> Seleccionar imagen
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
                    <div class="flex justify-center mt-4">
                        <div class="w-full max-w-xs h-40 flex justify-center items-center bg-gray-50 rounded">
                            <img :src="fotoPreview" alt="Previsualización del kit"
                                class="w-full h-full object-contain">
                        </div>
                    </div>
                </div>

                <!-- Componentes del Kit -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold mb-3 flex items-center">
                        <i class="fas fa-box-open text-primary mr-2"></i> Productos incluidos en el Kit
                    </h3>
                    
                    <div id="componentes-container">
                        <!-- Cada componente será un row -->
                        <div class="componente-row grid grid-cols-12 gap-4 mb-4 items-end">
                            <div class="col-span-6">
                                <label class="block text-sm font-medium text-gray-700">Producto</label>
                                <select name="componentes[0][id_producto]" class="select2-producto w-full" required>
                                    <option value="" disabled selected>Seleccionar producto</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->idProducto }}">
                                            {{ $producto->nombre }} ({{ $producto->codigo_barras }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                                <input type="number" name="componentes[0][cantidad]" min="1" value="1" 
                                       class="clean-input w-full" required>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Descuento %</label>
                                <input type="number" name="componentes[0][descuento]" min="0" max="100" value="0" 
                                       class="clean-input w-full">
                            </div>
                            <div class="col-span-1 flex justify-end">
                                <button type="button" class="btn btn-danger quitar-componente" disabled>
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" id="agregar-componente" class="btn btn-outline-primary mt-2">
                        <i class="fas fa-plus mr-1"></i> Agregar Producto
                    </button>
                </div>

                <!-- Descripción del Kit -->
                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción del Kit</label>
                    <textarea id="descripcion" name="descripcion" rows="3" 
                              class="clean-input w-full mt-1" 
                              placeholder="Describa los beneficios y características de este kit"></textarea>
                </div>
            </div>

            <div class="flex justify-end mt-6 gap-4">
                <a href="{{ route('producto.index') }}" class="btn btn-outline-danger flex items-center">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>
                <button type="button" id="btnLimpiarKit" class="btn btn-outline-warning flex items-center">
                    <i class="fas fa-eraser mr-2"></i> Limpiar
                </button>
                <button type="submit" class="btn btn-primary flex items-center">
                    <i class="fas fa-save mr-2"></i> Guardar Kit
                </button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Inicializar Select2 para productos
        $('.select2-producto').select2({
            placeholder: "Buscar producto...",
            width: '100%'
        });

        // Contador para componentes
        let contadorComponentes = 1;
        
        // Agregar nuevo componente
        $('#agregar-componente').click(function() {
            const newRow = `
                <div class="componente-row grid grid-cols-12 gap-4 mb-4 items-end">
                    <div class="col-span-6">
                        <select name="componentes[${contadorComponentes}][id_producto]" class="select2-producto w-full" required>
                            <option value="" disabled selected>Seleccionar producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->idProducto }}">
                                    {{ $producto->nombre }} ({{ $producto->codigo_barras }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-3">
                        <input type="number" name="componentes[${contadorComponentes}][cantidad]" min="1" value="1" 
                               class="clean-input w-full" required>
                    </div>
                    <div class="col-span-2">
                        <input type="number" name="componentes[${contadorComponentes}][descuento]" min="0" max="100" value="0" 
                               class="clean-input w-full">
                    </div>
                    <div class="col-span-1 flex justify-end">
                        <button type="button" class="btn btn-danger quitar-componente">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            
            $('#componentes-container').append(newRow);
            $('.select2-producto').select2(); // Inicializar Select2 para el nuevo campo
            
            // Habilitar botones de quitar para todas las filas menos la primera
            $('.quitar-componente').prop('disabled', false);
            
            contadorComponentes++;
        });
        
        // Quitar componente
        $(document).on('click', '.quitar-componente', function() {
            if($('.componente-row').length > 1) {
                $(this).closest('.componente-row').remove();
                
                // Si solo queda una fila, deshabilitar su botón de quitar
                if($('.componente-row').length === 1) {
                    $('.quitar-componente').prop('disabled', true);
                }
            }
        });
        
        // Limpiar formulario
        $('#btnLimpiarKit').click(function() {
            $('#kitForm')[0].reset();
            $('.select2-producto').val(null).trigger('change');
            
            // Dejar solo un componente
            $('.componente-row').not(':first').remove();
            $('.quitar-componente').prop('disabled', true);
            
            // Resetear contador
            contadorComponentes = 1;
        });
        
        // Enviar formulario
        $('#kitForm').submit(function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch("/producto/store-kit", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    toastr.success("Kit creado correctamente");
                    window.location.href = "{{ route('producto.index') }}";
                } else {
                    toastr.error(data.message || "Error al crear el kit");
                }
            })
            .catch(error => {
                toastr.error("Error en la comunicación con el servidor");
                console.error(error);
            });
        });
    });
    </script>
</x-layout.default>