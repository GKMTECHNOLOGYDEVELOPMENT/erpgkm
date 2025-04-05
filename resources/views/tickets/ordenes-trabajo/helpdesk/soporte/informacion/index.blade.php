<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
<style>
    /* ======== Modo Claro (Default) ======== */
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border-color: #e8e8e8 !important;
        height: 36px;
        /* Tamaño más pequeño */
        line-height: 34px;
        /* Alineación */
        font-size: 14px;
        /* Texto más pequeño */
        padding: 0 10px;
        /* Espaciado más compacto */
    }

    /* Ajustar el texto dentro del select */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--tw-text-opacity) !important;
        line-height: 34px;
        /* Alineación */
        font-size: 14px;
        /* Tamaño de texto más pequeño */
        padding-left: 6px;
        /* Espacio interno */
    }

    /* Flecha de selección */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 34px;
        /* Alineación */
    }

    /* Opciones seleccionadas */
    .select2-container--default .select2-results__option--selected {
        font-weight: 700;
    }

    /* Dropdown */
    .select2-container--default .select2-dropdown {
        border-radius: 5px;
        box-shadow: 0 0 0 1px #4444441c;
        font-size: 14px;
        /* Ajuste del texto */
    }

    /* Input de búsqueda */
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #e8e8e8;
        font-size: 13px;
        /* Texto más pequeño */
        padding: 6px;
        /* Más compacto */
    }

    /* ======== Modo Oscuro ======== */
    .dark .select2-container--default .select2-selection--single {
        background-color: #1b2e4b !important;
        border-color: #253b5c !important;
        color: #888ea8 !important;
        height: 36px;
        /* Tamaño más pequeño */
        line-height: 34px;
        /* Alineación */
        font-size: 14px;
        /* Texto más pequeño */
        padding: 0 10px;
    }

    .dark .select2-container--default .select2-dropdown {
        background-color: #1b2e4b !important;
    }

    .dark .select2-container--default .select2-results__option--highlighted,
    .dark .select2-container--default .select2-results__option--selected {
        background-color: #132136 !important;
        border-color: #253b5c !important;
    }

    /* Input de búsqueda en modo oscuro */
    .dark .select2-container--default .select2-search--dropdown .select2-search__field {
        background-color: #132136 !important;
        border-color: #253b5c !important;
        color: #fff !important;
        font-size: 13px;
        padding: 6px;
    }

    .panel {
        overflow: visible !important;
        /* Asegura que el modal no restrinja contenido */
    }
</style>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
    <!-- Sección de Detalles de los Estados -->
    <div class="p-5 rounded-lg shadow-md">
        <span class="text-sm sm:text-lg font-semibold mb-2 sm:mb-4 badge" style="background-color: {{ $colorEstado }};">Detalles de los Estados</span>

        <div class="grid grid-cols-1 gap-4 mt-4">
            <!-- Select de Estado con Nice Select -->
            <div>
                 <label for="estado" class="block text-sm font-medium">Estado</label>
          
            <select id="estado" name="estado"  style="display: none">
            <option value="" disabled selected>Selecciona una opción</option>

                @foreach ($estadosOTS as $index => $estado)
                    <option value="{{ $estado->idEstadoots }}" data-color="{{ $estado->color }}" {{ $index == 0 ? 'selected' : '' }}>
                        {{ $estado->descripcion }}
                    </option>
                @endforeach
            </select>
            </div>

            <!-- Textarea de Justificación -->
            <div>
                <label for="justificacion" class="block text-sm font-medium">Justificación</label>
                <textarea id="justificacion" name="justificacion" rows="3" class="form-input w-full mt-2"></textarea>
            </div>

            <!-- Botón Guardar -->
            <div class="flex justify-end">
                <button id="guardarEstado" class="btn btn-primary px-6 py-2">Guardar</button>
            </div>
        </div>

    </div>

    <!-- Sección de Herramientas -->
    <div class="p-5 rounded-lg shadow-md">
        <span class="text-lg font-semibold mb-4 badge bg-success">Inventario</span>
        <div class="flex justify-end mt-2">
            <button type="button" id="addHerramienta" class="btn btn-primary">+</button>
        </div>
        <!-- Contenedor con altura definida y scroll -->
        <div id="herramientasContainer" class="h-40 border overflow-y-auto p-3 rounded-lg mt-2">
            <div class="flex items-center gap-2 mt-2 herramienta-row">
                <select class="form-input w-full herramienta-select">
                    <option value="DISCO DURO DE 2 TB">DISCO DURO DE 2 TB</option>
                    <option value="MEMORIA RAM 16GB">MEMORIA RAM 16GB</option>
                    <option value="PROCESADOR INTEL I7">PROCESADOR INTEL I7</option>
                </select>
                <input type="number" class="form-input w-20 cantidad-input" min="1" value="1">
                <button type="button" class="btn btn-danger removeHerramienta hidden">-</button>
            </div>
        </div>



        <div class="flex justify-end mt-6">
            <button type="submit" class="btn btn-primary px-6 py-2">Guardar</button>
        </div>
    </div>
</div>

<!-- Contenedor Principal -->
<div id="cardInstalarRetirar" class="mt-4">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sección Instalar (estrecha en grandes, ocupa todo en medianas y chicas) -->
        <div class="lg:col-span-1 p-5 rounded-lg shadow-md">
            <span class="text-lg font-semibold mb-4 badge bg-success mb-4">Instalar</span>
            <div class="grid grid-cols-1 gap-4">

            <form method="POST" id="formInstalar">
            @csrf    <!-- Categoría (Tipo Producto) -->
    <div>
    <label class="block text-sm font-medium">Tipo Producto</label>
    <select id="tipoProductoInstalar" name="tipoProducto" >
        <option value="" disabled selected>Seleccionar Tipo de Producto</option>
        @foreach ($categorias as $categoria)
            <option value="{{ $categoria->idCategoria }}">{{ $categoria->nombre }}</option>
        @endforeach
    </select>
</div>

<!-- Marca -->
<div>
    <label class="block text-sm font-medium">Marca</label>
    <select id="marcaInstalar" name="marca">
        <option value="" disabled selected>Seleccionar Marca</option>
        @foreach ($marcas as $marca)
            <option value="{{ $marca->idMarca }}">{{ $marca->nombre }}</option>
        @endforeach
    </select>
</div>

<!-- Modelo -->
<div>
    <label class="block text-sm font-medium">Modelo</label>
    <select id="modeloInstalar" name="modelo" style="display: none">
        <option value="" disabled selected>Seleccionar Modelo</option>
        <!-- Los modelos se llenarán dinámicamente con JavaScript -->
    </select>
</div>


                <!-- Número de Serie -->
                <div>
                    <label class="block text-sm font-medium">Nro. de Serie</label>
                    <input type="text" id="serieInstalar" class="form-input w-full mt-2"
                        placeholder="Ingrese Nro. de Serie">
                </div>

                <!-- Botón Guardar -->
                <div class="flex justify-end">
                    <button id="guardarInstalar" class="btn btn-primary px-6 py-2">Guardar</button>
                </div>

                </form>

            </div>
        </div>

        </form>


        <!-- Tabla de Productos - Instalar (más ancha en pantallas grandes) -->
        <div class="lg:col-span-2 p-5 rounded-lg shadow-md">
            <span class="text-lg font-semibold mb-4">Productos Instalados</span>
            <div class="overflow-x-auto mt-2">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-center">Producto</th>
                            <th class="px-4 py-2 text-center">Marca</th>
                            <th class="px-4 py-2 text-center">Modelo</th>
                            <th class="px-4 py-2 text-center">Nro. Serie</th>
                            <th class="px-4 py-2 text-center">Accion</th>

                        </tr>
                    </thead>
                    <tbody id="tablaInstalar"></tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Sección Retirar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- Sección Retirar (estrecha en grandes, ocupa todo en medianas y chicas) -->
        <div class="lg:col-span-1 p-5 rounded-lg shadow-md">
            <span class="text-lg font-semibold mb-4 badge bg-success mb-4">Retirar</span>
            <div class="grid grid-cols-1 gap-4">
                <!-- Tipo Producto -->
               <form method="POST" id="formRetirar">
            @csrf    <!-- Categoría (Tipo Producto) -->
    <div>
    <label class="block text-sm font-medium">Tipo Producto</label>
    <select id="tipoProductoRetirar" name="tipoProducto">
        <option value="" disabled selected>Seleccionar Tipo de Producto</option>
        @foreach ($categorias as $categoria)
            <option value="{{ $categoria->idCategoria }}">{{ $categoria->nombre }}</option>
        @endforeach
    </select>
</div>

<!-- Marca -->
<div>
    <label class="block text-sm font-medium">Marca</label>
    <select id="marcaRetirar" name="marca">
        <option value="" disabled selected>Seleccionar Marca</option>
        @foreach ($marcas as $marca)
            <option value="{{ $marca->idMarca }}">{{ $marca->nombre }}</option>
        @endforeach
    </select>
</div>

<!-- Modelo -->
<div>
    <label class="block text-sm font-medium">Modelo</label>
    <select id="modeloRetirar" name="modelo" style="display: none">
        <option value="" disabled selected>Seleccionar Modelo</option>
        <!-- Los modelos se llenarán dinámicamente con JavaScript -->
    </select>
</div>


                <!-- Número de Serie -->
                <div>
                    <label class="block text-sm font-medium">Nro. de Serie</label>
                    <input type="text" id="serieRetirar" class="form-input w-full mt-2"
                        placeholder="Ingrese Nro. de Serie">
                </div>

                <!-- Botón Guardar -->
                <div class="flex justify-end">
                    <button id="guardarRetirar" class="btn btn-primary px-6 py-2">Guardar</button>
                </div>

                

            </div>
        </div>

        </form>

      

        <!-- Tabla de Productos - Retirar (más ancha en pantallas grandes) -->
        <div class="lg:col-span-2 p-5 rounded-lg shadow-md">
            <span class="text-lg font-semibold mb-4">Productos Retirados</span>
            <div class="overflow-x-auto mt-2">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-center">Producto</th>
                            <th class="px-4 py-2 text-center">Marca</th>
                            <th class="px-4 py-2 text-center">Modelo</th>
                            <th class="px-4 py-2 text-center">Nro. Serie</th>
                            <th class="px-4 py-2 text-center">Accion</th>
                        </tr>
                    </thead>
                    <tbody id="tablaRetirar"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    // Al hacer clic en el botón "Guardar" de la sección Instalar
    $('#guardarInstalar').click(function(e) {
        e.preventDefault(); // Evita que el formulario se envíe de forma predeterminada

        // Obtener los valores de los campos
        var tipoProducto = $('#tipoProductoInstalar').val();
        var marca = $('#marcaInstalar').val();
        var modelo = $('#modeloInstalar').val();
        var serie = $('#serieInstalar').val();
        var ticketId = "{{ $ticketId }}"; // Usar el ticketId que ya está disponible en el contexto
        var idVisitaSeleccionada = "{{ $idVisitaSeleccionada }}"; // Usar la variable idVisitaSeleccionada

        // Verificar que todos los campos estén completos
        if (!tipoProducto || !marca || !modelo || !serie) {
            alert("Por favor, complete todos los campos.");
            return;
        }

        // Crear un objeto FormData para enviar todos los datos del formulario
        var formData = new FormData();
        formData.append('tipoProducto', tipoProducto);
        formData.append('marca', marca);
        formData.append('modelo', modelo);
        formData.append('numeroSerie', serie);
        formData.append('idTicket', ticketId); // Asegúrate de enviar el ticketId
        formData.append('idVisita', idVisitaSeleccionada); // Asegúrate de enviar el idVisita
        formData.append('_token', '{{ csrf_token() }}'); // Token CSRF para seguridad

        // Hacer la solicitud AJAX para guardar los datos
        $.ajax({
            url: '/guardar-equipo', // Ruta del controlador
            method: 'POST',
            data: formData,
            processData: false, // Para enviar datos binarios (imágenes, etc.)
            contentType: false, // Para evitar que jQuery cambie el tipo de contenido
            success: function(response) {
                // Si el equipo se guarda correctamente, agregarlo a la tabla
                if (response.success) {
                    var nuevoProducto = `
                        <tr>
                            <td class="text-center">${response.producto.tipoProducto}</td>
                            <td class="text-center">${response.producto.marca}</td>
                            <td class="text-center">${response.producto.modelo}</td>
                            <td class="text-center">${response.producto.nserie}</td>
                        </tr>
                    `;
                    $('#tablaInstalar').append(nuevoProducto);


                    alert("Producto instalado correctamente.");
                    location.reload();
                } else {
                    alert("Hubo un error al guardar el equipo.");
                }
            },
            error: function(xhr) {
                alert("Hubo un error al procesar la solicitud.");
            }
        });
    });
</script>


<script>
    // Al cargar la página, hacer una solicitud AJAX para obtener los productos instalados
    $(document).ready(function() {
        var ticketId = "{{ $ticketId }}"; // Asegúrate de que $ticketId esté disponible en el contexto
        var idVisitaSeleccionada = "{{ $idVisitaSeleccionada }}"; // Asegúrate de que $idVisitaSeleccionada esté disponible

        // Solicitar los productos instalados
        $.ajax({
            url: '/obtener-productos-instalados', // Ruta del controlador para obtener los productos instalados
            method: 'GET',
            data: {
                idTicket: ticketId,
                idVisita: idVisitaSeleccionada,
            },
            success: function(response) {
                // Limpiar la tabla
                $('#tablaInstalar').empty();

                // Verificar si hay productos
                if (response.length > 0) {
                    // Recorrer los productos y agregarlos a la tabla
                    response.forEach(function(producto) {
                        var nuevoProducto = `
                        <tr data-id="${producto.idEquipos}">
                            <td class="text-center">${producto.categoria_nombre}</td> <!-- Mostrar el nombre de la categoría -->
                            <td class="text-center">${producto.marca_nombre}</td> <!-- Mostrar el nombre de la marca -->
                            <td class="text-center">${producto.modelo_nombre}</td> <!-- Mostrar el nombre del modelo -->
                            <td class="text-center">${producto.nserie}</td> <!-- Mostrar el número de serie -->
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm eliminarProducto">Eliminar</button>
                            </td>
                        </tr>
                    `;
                        $('#tablaInstalar').append(nuevoProducto);
                    });
                } else {
                    // Si no hay productos, mostrar un mensaje
                    var noProductos = `
                    <tr>
                        <td colspan="5" class="text-center">No hay productos instalados para este ticket y visita.</td>
                    </tr>
                `;
                    $('#tablaInstalar').append(noProductos);
                }
            },
            error: function(xhr) {
                alert("Hubo un error al obtener los productos instalados.");
            }
        });
    });
</script>


<script>
    // Manejador para el botón de eliminar producto
    $('#tablaInstalar').on('click', '.eliminarProducto', function() {
        var fila = $(this).closest('tr');
        var idEquipo = fila.data('id'); // Obtener el id del producto a eliminar

        // Confirmar la eliminación
        if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
            // Hacer la solicitud AJAX para eliminar el producto
            $.ajax({
                url: '/eliminar-producto/' + idEquipo, // Ruta al controlador para eliminar el producto
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}', // Incluir el token CSRF
                },
                success: function(response) {
                    if (response.success) {
                        fila.remove(); // Eliminar la fila de la tabla
                        alert('Producto eliminado correctamente.');
                    } else {
                        alert('Hubo un error al eliminar el producto.');
                    }
                },
                error: function(xhr) {
                    alert('Hubo un error al procesar la solicitud de eliminación.');
                }
            });
        }
    });
</script>


<script>
$(document).ready(function() {
    // Inicializar Select2 para los selectores
    $('#tipoProductoInstalar').select2({
        placeholder: 'Seleccionar Tipo de Producto',
        allowClear: true
    });

    $('#marcaInstalar').select2({
        placeholder: 'Seleccionar Marca',
        allowClear: true
    });

    $('#modeloInstalar').select2({
        placeholder: 'Seleccionar Modelo',
        allowClear: true
    });

    // Cuando se selecciona una categoría
    $('#tipoProductoInstalar').change(function() {
        var idCategoria = $(this).val();

        // Si se seleccionó una categoría
        if (idCategoria) {
            // Hacer la solicitud AJAX para obtener las marcas por categoría
            $.ajax({
                url: '/marcas/categoria/' + idCategoria, // Ruta definida en el controlador
                method: 'GET',
                success: function(response) {
                    // Limpiar el select de marcas
                    $('#marcaInstalar').empty();
                    $('#marcaInstalar').append('<option value="" disabled selected>Seleccionar Marca</option>');

                    // Llenar el select de marcas con los datos recibidos
                    response.forEach(function(marca) {
                        $('#marcaInstalar').append('<option value="' + marca.idMarca + '">' + marca.nombre + '</option>');
                    });

                    // Mostrar el select de marcas
                    $('#marcaInstalar').show();
                    $('#marcaInstalar').trigger('change'); // Actualizar Select2 después de agregar opciones
                },
                error: function(xhr) {
                    // Si ocurre un error, mostrar un mensaje
                    alert('Hubo un error al cargar las marcas');
                }
            });
        } else {
            // Si no se seleccionó una categoría, esconder el select de marcas y modelos
            $('#marcaInstalar').hide();
            $('#modeloInstalar').hide();
        }
    });

    // Cuando se selecciona una marca
    $('#marcaInstalar').change(function() {
        var idMarca = $(this).val();

        // Si se seleccionó una marca
        if (idMarca) {
            // Hacer la solicitud AJAX para obtener los modelos por marca
            $.ajax({
                url: '/modelos/marca/' + idMarca, // Ruta definida en el controlador
                method: 'GET',
                success: function(response) {
                    // Limpiar el select de modelos
                    $('#modeloInstalar').empty();
                    $('#modeloInstalar').append('<option value="" disabled selected>Seleccionar Modelo</option>');

                    // Llenar el select de modelos con los datos recibidos
                    response.forEach(function(modelo) {
                        $('#modeloInstalar').append('<option value="' + modelo.idModelo + '">' + modelo.nombre + '</option>');
                    });

                    // Mostrar el select de modelos
                    $('#modeloInstalar').show();
                    $('#modeloInstalar').trigger('change'); // Actualizar Select2 después de agregar opciones
                },
                error: function(xhr) {
                    // Si ocurre un error, mostrar un mensaje
                    alert('Hubo un error al cargar los modelos');
                }
            });
        } else {
            // Si no se seleccionó una marca, esconder el select de modelos
            $('#modeloInstalar').hide();
        }
    });
});


</script>



<script>
    // Al hacer clic en el botón "Guardar" de la sección Retirar
    $('#guardarRetirar').click(function(e) {
        e.preventDefault(); // Evita que el formulario se envíe de forma predeterminada

        // Obtener los valores de los campos
        var tipoProducto = $('#tipoProductoRetirar').val();
        var marca = $('#marcaRetirar').val();
        var modelo = $('#modeloRetirar').val();
        var serie = $('#serieRetirar').val();
        var ticketId = "{{ $ticketId }}"; // Usar el ticketId que ya está disponible en el contexto
        var idVisitaSeleccionada = "{{ $idVisitaSeleccionada }}"; // Usar la variable idVisitaSeleccionada

        // Verificar que todos los campos estén completos
        if (!tipoProducto || !marca || !modelo || !serie) {
            alert("Por favor, complete todos los campos.");
            return;
        }

        // Crear un objeto FormData para enviar todos los datos del formulario
        var formData = new FormData();
        formData.append('tipoProducto', tipoProducto);
        formData.append('marca', marca);
        formData.append('modelo', modelo);
        formData.append('numeroSerie', serie);
        formData.append('idTicket', ticketId); // Asegúrate de enviar el ticketId
        formData.append('idVisita', idVisitaSeleccionada); // Asegúrate de enviar el idVisita
        formData.append('_token', '{{ csrf_token() }}'); // Token CSRF para seguridad

        // Hacer la solicitud AJAX para guardar los datos
        $.ajax({
            url: '/guardar-equipo-retirar', // Ruta del controlador para guardar el producto retirado
            method: 'POST',
            data: formData,
            processData: false, // Para enviar datos binarios (imágenes, etc.)
            contentType: false, // Para evitar que jQuery cambie el tipo de contenido
            success: function(response) {
                // Si el equipo se guarda correctamente, agregarlo a la tabla
                if (response.success) {
                    var nuevoProducto = `
                    <tr>
                        <td class="text-center">${response.producto.tipoProducto}</td>
                        <td class="text-center">${response.producto.marca}</td>
                        <td class="text-center">${response.producto.modelo}</td>
                        <td class="text-center">${response.producto.nserie}</td>
                    </tr>
                `;
                    $('#tablaRetirar').append(nuevoProducto);
                    location.reload();
                    alert("Producto retirado correctamente.");
                } else {
                    alert("Hubo un error al guardar el equipo.");
                }
            },
            error: function(xhr) {
                alert("Hubo un error al procesar la solicitud.");
            }
        });
    });
</script>

<script>
    // Al cargar la página, hacer una solicitud AJAX para obtener los productos retirados
    $(document).ready(function() {
        var ticketId = "{{ $ticketId }}"; // Asegúrate de que $ticketId esté disponible en el contexto
        var idVisitaSeleccionada = "{{ $idVisitaSeleccionada }}"; // Asegúrate de que $idVisitaSeleccionada esté disponible

        // Solicitar los productos retirados
        $.ajax({
            url: '/obtener-productos-retirados', // Ruta del controlador para obtener los productos retirados
            method: 'GET',
            data: {
                idTicket: ticketId,
                idVisita: idVisitaSeleccionada,
            },
            success: function(response) {
                // Limpiar la tabla
                $('#tablaRetirar').empty();

                // Verificar si hay productos
                if (response.length > 0) {
                    // Recorrer los productos y agregarlos a la tabla
                    response.forEach(function(producto) {
                        var nuevoProducto = `
                        <tr data-id="${producto.idEquipos}">
                            <td class="text-center">${producto.categoria_nombre}</td> <!-- Mostrar el nombre de la categoría -->
                            <td class="text-center">${producto.marca_nombre}</td> <!-- Mostrar el nombre de la marca -->
                            <td class="text-center">${producto.modelo_nombre}</td> <!-- Mostrar el nombre del modelo -->
                            <td class="text-center">${producto.nserie}</td> <!-- Mostrar el número de serie -->
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm eliminarProducto">Eliminar</button>
                            </td>
                        </tr>
                    `;
                        $('#tablaRetirar').append(nuevoProducto);
                    });
                } else {
                    // Si no hay productos, mostrar un mensaje
                    var noProductos = `
                    <tr>
                        <td colspan="5" class="text-center">No hay productos retirados para este ticket y visita.</td>
                    </tr>
                `;
                    $('#tablaRetirar').append(noProductos);
                }
            },
            error: function(xhr) {
                alert("Hubo un error al obtener los productos retirados.");
            }
        });
    });
</script>

<script>
    // Manejador para el botón de eliminar producto
    $('#tablaRetirar').on('click', '.eliminarProducto', function() {
        var fila = $(this).closest('tr');
        var idEquipo = fila.data('id'); // Obtener el id del producto a eliminar

        // Confirmar la eliminación
        if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
            // Hacer la solicitud AJAX para eliminar el producto
            $.ajax({
                url: '/eliminar-producto/' + idEquipo, // Ruta al controlador para eliminar el producto
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}', // Incluir el token CSRF
                },
                success: function(response) {
                    if (response.success) {
                        fila.remove(); // Eliminar la fila de la tabla
                        alert('Producto eliminado correctamente.');
                    } else {
                        alert('Hubo un error al eliminar el producto.');
                    }
                },
                error: function(xhr) {
                    alert('Hubo un error al procesar la solicitud de eliminación.');
                }
            });
        }
    });
</script>

<script>
$(document).ready(function() {
    // Inicializar Select2 para los selectores de Retirar
    $('#tipoProductoRetirar').select2({
        placeholder: 'Seleccionar Tipo de Producto',
        allowClear: true
    });

    $('#marcaRetirar').select2({
        placeholder: 'Seleccionar Marca',
        allowClear: true
    });

    $('#modeloRetirar').select2({
        placeholder: 'Seleccionar Modelo',
        allowClear: true
    });

    // Cuando se selecciona una categoría en Retirar
    $('#tipoProductoRetirar').change(function() {
        var idCategoria = $(this).val();

        // Si se seleccionó una categoría
        if (idCategoria) {
            // Hacer la solicitud AJAX para obtener las marcas por categoría
            $.ajax({
                url: '/marcas/categoria/' + idCategoria, // Ruta definida en el controlador
                method: 'GET',
                success: function(response) {
                    // Limpiar el select de marcas
                    $('#marcaRetirar').empty();
                    $('#marcaRetirar').append('<option value="" disabled selected>Seleccionar Marca</option>');

                    // Llenar el select de marcas con los datos recibidos
                    response.forEach(function(marca) {
                        $('#marcaRetirar').append('<option value="' + marca.idMarca + '">' + marca.nombre + '</option>');
                    });

                    // Mostrar el select de marcas
                    $('#marcaRetirar').show();
                    $('#marcaRetirar').trigger('change'); // Actualizar Select2 después de agregar opciones
                },
                error: function(xhr) {
                    // Si ocurre un error, mostrar un mensaje
                    alert('Hubo un error al cargar las marcas');
                }
            });
        } else {
            // Si no se seleccionó una categoría, esconder el select de marcas y modelos
            $('#marcaRetirar').hide();
            $('#modeloRetirar').hide();
        }
    });

    // Cuando se selecciona una marca en Retirar
    $('#marcaRetirar').change(function() {
        var idMarca = $(this).val();

        // Si se seleccionó una marca
        if (idMarca) {
            // Hacer la solicitud AJAX para obtener los modelos por marca
            $.ajax({
                url: '/modelos/marca/' + idMarca, // Ruta definida en el controlador
                method: 'GET',
                success: function(response) {
                    // Limpiar el select de modelos
                    $('#modeloRetirar').empty();
                    $('#modeloRetirar').append('<option value="" disabled selected>Seleccionar Modelo</option>');

                    // Llenar el select de modelos con los datos recibidos
                    response.forEach(function(modelo) {
                        $('#modeloRetirar').append('<option value="' + modelo.idModelo + '">' + modelo.nombre + '</option>');
                    });

                    // Mostrar el select de modelos
                    $('#modeloRetirar').show();
                    $('#modeloRetirar').trigger('change'); // Actualizar Select2 después de agregar opciones
                },
                error: function(xhr) {
                    // Si ocurre un error, mostrar un mensaje
                    alert('Hubo un error al cargar los modelos');
                }
            });
        } else {
            // Si no se seleccionó una marca, esconder el select de modelos
            $('#modeloRetirar').hide();
        }
    });
});


</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        NiceSelect.bind(document.getElementById("estado"));
        const estadoSelect = document.getElementById("estado");
    });

    document.addEventListener("DOMContentLoaded", function() {
        const herramientasContainer = document.getElementById("herramientasContainer");
        const addHerramientaBtn = document.getElementById("addHerramienta");

        // Inicializar Select2 en los selects existentes
        function inicializarSelects() {
            $(".herramienta-select").select2({
                width: "100%", // Asegura que el select ocupa todo el espacio disponible
                placeholder: "Seleccione una herramienta",
                allowClear: true
            });
        }

        inicializarSelects(); // Llamar a la función para inicializar el primer select

        // Agregar una nueva fila al presionar el botón "+"
        addHerramientaBtn.addEventListener("click", function() {
            const nuevaFila = document.createElement("div");
            nuevaFila.classList.add("flex", "items-center", "gap-2", "mt-2", "herramienta-row");

            nuevaFila.innerHTML = `
            <select class="form-input w-full herramienta-select">
                <option value="DISCO DURO DE 2 TB">DISCO DURO DE 2 TB</option>
                <option value="MEMORIA RAM 16GB">MEMORIA RAM 16GB</option>
                <option value="PROCESADOR INTEL I7">PROCESADOR INTEL I7</option>
            </select>
            <input type="number" class="form-input w-20 cantidad-input" min="1" value="1">
            <button type="button" class="btn btn-danger removeHerramienta">-</button>
        `;

            herramientasContainer.appendChild(nuevaFila);
            herramientasContainer.scrollTop = herramientasContainer
                .scrollHeight; // Hace scroll automáticamente al final

            // Inicializar Select2 en el nuevo select
            $(nuevaFila).find(".herramienta-select").select2({
                width: "100%",
                placeholder: "Seleccione una herramienta",
                allowClear: true
            });

            // Mostrar el botón de eliminar en todas las filas menos la primera
            actualizarBotonesEliminar();
        });

        // Delegación de eventos para eliminar herramientas dinámicamente
        herramientasContainer.addEventListener("click", function(event) {
            if (event.target.classList.contains("removeHerramienta")) {
                event.target.parentElement.remove();
                actualizarBotonesEliminar();
            }
        });

        // Función para actualizar la visibilidad del botón "-"
        function actualizarBotonesEliminar() {
            const filas = document.querySelectorAll(".herramienta-row");
            const botonesEliminar = document.querySelectorAll(".removeHerramienta");

            botonesEliminar.forEach((btn, index) => {
                btn.classList.toggle("hidden", filas.length === 1); // Ocultar si solo hay una fila
            });
        }

        // Inicializar visibilidad de los botones eliminar
        actualizarBotonesEliminar();
    });
    document.addEventListener("DOMContentLoaded", function() {
        // Obtener el contenedor principal
        const cardInstalarRetirar = document.getElementById("cardInstalarRetirar");

        if (cardInstalarRetirar) {
            // Seleccionar solo los selects dentro de cardInstalarRetirar
            const selectsNice = cardInstalarRetirar.querySelectorAll(".nice-select");

            // Inicializar NiceSelect2 en estos selects con búsqueda habilitada
            selectsNice.forEach(select => {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });
        }
    });
</script>






<script>
    document.getElementById("guardarEstado").addEventListener("click", function() {
        const estadoSelect = document.getElementById("estado");
        const estadoId = estadoSelect.value;
        const justificacion = document.getElementById("justificacion").value;

        // Validar que se haya seleccionado un estado y se haya ingresado una justificación
        if (!estadoId || !justificacion.trim()) {
            toastr.error("Debe seleccionar un estado y escribir una justificación.");
            return;
        }

        // Enviar los datos al servidor
        fetch('/api/guardarEstadoSoporte', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    idEstadoots: estadoId,
                    justificacion: justificacion.trim(),
                    idTickets: {{ $ticket->idTickets }} // Solo se pasa el ID del ticket
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success("Estado guardado correctamente.");
                } else {
                    toastr.error("Error al guardar el estado.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                toastr.error("Hubo un error al guardar el estado.");
            });
    });
</script>





<script>
  document.getElementById("estado").addEventListener("change", function() {
    const estadoId = this.value;
    const ticketId = {{ $ticket->idTickets }};
    const visitaId = {{ $visitaId ?? 'null' }};

    // Obtener la justificación del estado seleccionado
    fetch(`/api/obtenerJustificacionSoporte?ticketId=${ticketId}&visitaId=${visitaId}&estadoId=${estadoId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar la justificación en el textarea
                document.getElementById("justificacion").value = data.justificacion || "";
            } else {
                toastr.error(data.message || "Error al obtener la justificación");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            toastr.error("Error al obtener la justificación.");
        });

    // Verificar si el estado seleccionado es igual a 3 (puedes cambiar esto según tu lógica)
    if (estadoId == 5) {
        const cardFotos = document.getElementById("cardFotos");
        if (cardFotos) {
            cardFotos.style.display = "block"; // Mostrar el elemento

            renderizarPrevisualizacion();
        }
    } else {
        const cardFotos = document.getElementById("cardFotos");
        if (cardFotos) {
            cardFotos.style.display = "none"; // Ocultar el elemento
        }
    }
});

    document.addEventListener("DOMContentLoaded", function() {
        // Inicializar todos los select con la clase .selectize
        document.querySelectorAll(".selectize").forEach(function(select) {
            NiceSelect.bind(select);
        });
    });
</script>



<script>
    document.getElementById('guardarInstalar').addEventListener('click', function() {
        // Obtener los valores de los campos
        const tipoProducto = document.getElementById('tipoProductoInstalar').value;
        const marca = document.getElementById('marcaInstalar').value;
        const modelo = document.getElementById('modeloInstalar').value;
        const nroSerie = document.getElementById('serieInstalar').value;

        // Validar que los campos no estén vacíos
        if (tipoProducto && marca && modelo && nroSerie) {
            // Crear una nueva fila para la tabla
            const fila = document.createElement('tr');

            // Crear celdas para cada columna
            const celdaProducto = document.createElement('td');
            celdaProducto.textContent = tipoProducto;
            celdaProducto.classList.add('px-4', 'py-2', 'text-center');

            const celdaMarca = document.createElement('td');
            celdaMarca.textContent = marca;
            celdaMarca.classList.add('px-4', 'py-2', 'text-center');

            const celdaModelo = document.createElement('td');
            celdaModelo.textContent = modelo;
            celdaModelo.classList.add('px-4', 'py-2', 'text-center');

            const celdaSerie = document.createElement('td');
            celdaSerie.textContent = nroSerie;
            celdaSerie.classList.add('px-4', 'py-2', 'text-center');

            // Agregar las celdas a la fila
            fila.appendChild(celdaProducto);
            fila.appendChild(celdaMarca);
            fila.appendChild(celdaModelo);
            fila.appendChild(celdaSerie);

            // Agregar la fila a la tabla
            document.getElementById('tablaInstalar').appendChild(fila);

            // Limpiar los campos del formulario después de guardar
            document.getElementById('tipoProductoInstalar').value = '';
            document.getElementById('marcaInstalar').value = '';
            document.getElementById('modeloInstalar').value = '';
            document.getElementById('serieInstalar').value = '';
        } else {
            alert('Por favor complete todos los campos.');
        }
    });
</script>


<script>
    document.getElementById('guardarRetirar').addEventListener('click', function() {
        // Obtener los valores de los campos
        const tipoProducto = document.getElementById('tipoProductoRetirar').value;
        const marca = document.getElementById('marcaRetirar').value;
        const modelo = document.getElementById('modeloRetirar').value;
        const nroSerie = document.getElementById('serieRetirar').value;

        // Validar que los campos no estén vacíos
        if (tipoProducto && marca && modelo && nroSerie) {
            // Crear una nueva fila para la tabla
            const fila = document.createElement('tr');

            // Crear celdas para cada columna
            const celdaProducto = document.createElement('td');
            celdaProducto.textContent = tipoProducto;
            celdaProducto.classList.add('px-4', 'py-2', 'text-center');

            const celdaMarca = document.createElement('td');
            celdaMarca.textContent = marca;
            celdaMarca.classList.add('px-4', 'py-2', 'text-center');

            const celdaModelo = document.createElement('td');
            celdaModelo.textContent = modelo;
            celdaModelo.classList.add('px-4', 'py-2', 'text-center');

            const celdaSerie = document.createElement('td');
            celdaSerie.textContent = nroSerie;
            celdaSerie.classList.add('px-4', 'py-2', 'text-center');

            // Agregar las celdas a la fila
            fila.appendChild(celdaProducto);
            fila.appendChild(celdaMarca);
            fila.appendChild(celdaModelo);
            fila.appendChild(celdaSerie);

            // Agregar la fila a la tabla
            document.getElementById('tablaRetirar').appendChild(fila);

            // Limpiar los campos del formulario después de guardar
            document.getElementById('tipoProductoRetirar').value = '';
            document.getElementById('marcaRetirar').value = '';
            document.getElementById('modeloRetirar').value = '';
            document.getElementById('serieRetirar').value = '';
        } else {
            alert('Por favor complete todos los campos.');
        }
    });
</script>
