<!-- CDN Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

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

/* Hacer el select más ancho para mostrar toda la información */
.w-56 {
    width: 28rem !important; /* Más ancho para mostrar las etiquetas */
}

/* Asegurar que el dropdown también sea más ancho */
.select2-container {
    width: 100% !important;
    max-width: 28rem;
}

/* Mejorar la visualización de las opciones en el dropdown */
.select2-container--default .select2-results__option {
    white-space: normal !important;
    line-height: 1.4 !important;
    padding: 8px 12px !important;
    font-size: 13px !important;
}

/* Para modo oscuro */
.dark .select2-container--default .select2-results__option {
    color: #888ea8 !important;
}
/* Estilos para el botón de refrescar */
#refrescarTabla {
    transition: all 0.3s ease;
}

#refrescarTabla:hover {
    transform: translateY(-1px);
}

#refrescarTabla:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Animación de spin para el ícono */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>

<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md mt-6">
    <div class="flex justify-between items-center mb-4">
        <span class="text-sm sm:text-lg font-semibold badge" style="background-color: {{ $colorEstado }};">Articulos</span>
        <!-- Botón para refrescar la tabla -->
        <button id="refrescarTabla" class="btn btn-outline-primary btn-sm flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Refrescar
        </button>
    </div>

    <!-- Selector e Input -->
    <div class="mt-4 mb-6 flex items-center gap-4">
        <!-- Select modificado para mostrar con etiquetas claras -->
        <select id="articuloSelect" class="w-56 herramienta-select">
            <option selected disabled value="">Seleccione un artículo</option>
            @foreach ($articulos as $articulo)
            <option value="{{ $articulo->idArticulos }}" 
                    data-tipo="{{ $articulo->idTipoArticulo }}"
                    data-nombre="{{ $articulo->nombre }}"
                    data-marca="{{ $articulo->marca_nombre }}"
                    data-modelo="{{ $articulo->modelo_nombre }}">
                {{ $articulo->display_text }} - {{ $articulo->tipo_nombre }}
            </option>
            @endforeach
        </select>

        <!-- Input para la cantidad -->
        <input type="number" id="articuloCantidad" class="form-input w-16 text-center" value="1" min="1" />

        <!-- Botón para agregar el artículo -->
        <button id="agregarArticulo" class="btn btn-primary">Agregar</button>
    </div>

    <!-- Tabla de resumen -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-center">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="px-3 py-2 text-center">Artículo</th>
                    <th class="px-3 py-2 text-center">Cantidad</th>
                    <th class="px-3 py-2 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaResumenHerramientas"
                class="divide-y divide-gray-200 dark:divide-gray-600 text-center text-gray-800 dark:text-gray-100">
                <!-- Dinámico -->
            </tbody>
        </table>
    </div>

    <div class="flex justify-end mt-4">
        <button type="submit" class="btn btn-primary guardarHerramientas">Guardar</button>
    </div>
</div>

<input type="hidden" id="ticketId" value="{{ $id }}">

<input type="hidden" id="visitaId" value="{{ $idVisitaSeleccionada }}">


<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#articuloSelect').select2({
            placeholder: 'Seleccione un artículo',
            allowClear: true,
            width: '100%' // Ajuste responsivo
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const articuloSelect = document.getElementById("articuloSelect");
        const articuloCantidad = document.getElementById("articuloCantidad");
        const tablaBody = document.getElementById("tablaResumenHerramientas");
        let articulosSeleccionados = [];

        // Obtener ticketId y visitaId una sola vez
        const ticketId = document.getElementById("ticketId").value;
        const visitaId = document.getElementById("visitaId").value;

        function renderTabla() {
    console.log("Renderizando la tabla...");
    
    // Verificar si hay artículos
    if (articulosSeleccionados.length === 0) {
        tablaBody.innerHTML = `
            <tr>
                <td colspan="3" class="px-3 py-4 text-center text-gray-500 dark:text-gray-400">
                    No hay artículos agregados
                </td>
            </tr>
        `;
        return;
    }

    tablaBody.innerHTML = "";

    articulosSeleccionados.forEach((art, index) => {
        const tr = document.createElement("tr");
        tr.className = "hover:bg-gray-50 dark:hover:bg-gray-700";
        tr.innerHTML = `
            <td class="px-3 py-2 text-left">
                <div class="font-medium">${art.nombre}</div>
                <div class="text-xs text-gray-500">ID: ${art.id}</div>
            </td>
            <td class="px-3 py-2">
                <input type="number" min="1" value="${art.cantidad}" 
                    class="form-input w-16 text-center actualizarCantidad" data-index="${index}" />
            </td>
            <td class="px-3 py-2">
                <button class="btn btn-sm btn-danger eliminarArticulo" data-id="${art.idSuministros}">
                    Eliminar
                </button>
            </td>
        `;
        tablaBody.appendChild(tr);
    });

    console.log("Tabla renderizada con", articulosSeleccionados.length, "artículos");
}

       // Función para refrescar la tabla con animación
function refrescarTabla() {
    // Mostrar estado de carga
    const botonRefrescar = document.getElementById('refrescarTabla');
    const textoOriginal = botonRefrescar.innerHTML;
    
    // Agregar animación de carga
    botonRefrescar.innerHTML = `
        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Refrescando...
    `;
    botonRefrescar.disabled = true;

    // Obtener los suministros actualizados
    obtenerSuministros()
        .then(() => {
            // Restaurar el botón
            botonRefrescar.innerHTML = textoOriginal;
            botonRefrescar.disabled = false;
            
            // Mostrar mensaje de éxito
            toastr.success('Tabla actualizada correctamente');
        })
        .catch(error => {
            // Restaurar el botón en caso de error
            botonRefrescar.innerHTML = textoOriginal;
            botonRefrescar.disabled = false;
            
            toastr.error('Error al actualizar la tabla');
        });
}

// Modificar la función obtenerSuministros para prevenir duplicados
function obtenerSuministros() {
    return new Promise((resolve, reject) => {
        fetch(`/get-suministros/${ticketId}/${visitaId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Datos recibidos del servidor:', data);
                
                // Limpiar el array actual antes de agregar nuevos datos
                articulosSeleccionados = [];
                
                // Procesar los datos para mantener el mismo formato con etiquetas
                data.forEach(item => {
                    let nombreDisplay = "Nombre: " + item.nombre;
                    
                    if (item.marca_nombre && item.marca_nombre !== null) {
                        nombreDisplay += " | Marca: " + item.marca_nombre;
                    }
                    
                    if (item.modelo_nombre && item.modelo_nombre !== null) {
                        nombreDisplay += " | Modelo: " + item.modelo_nombre;
                    }
                    
                    // Verificar que no haya duplicados (por si acaso)
                    const existe = articulosSeleccionados.some(art => art.id == item.idArticulos);
                    
                    if (!existe) {
                        articulosSeleccionados.push({
                            idSuministros: item.idSuministros,
                            id: item.idArticulos,
                            nombre: nombreDisplay,
                            cantidad: item.cantidad
                        });
                    } else {
                        console.warn('Artículo duplicado detectado y omitido:', item.idArticulos);
                    }
                });
                
                renderTabla();
                resolve();
            })
            .catch(error => {
                console.error('Error al obtener los suministros:', error);
                reject(error);
            });
    });
}

// Agregar event listener para el botón de refrescar
document.getElementById('refrescarTabla').addEventListener('click', function() {
    refrescarTabla();
});

        // Llamada inicial para obtener los suministros
        obtenerSuministros();

       // Función para actualizar cantidad (mejorada)
function actualizarCantidadSuministro(idSuministro, nuevaCantidad) {
    if (!idSuministro) {
        console.log('No hay idSuministro, probablemente artículo nuevo');
        return Promise.resolve(true);
    }
    
    return fetch(`/actualizar-suministro/${idSuministro}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({
            cantidad: nuevaCantidad
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            toastr.success(data.message);
            return true;
        } else {
            throw new Error(data.error || 'Error al actualizar');
        }
    })
    .catch(error => {
        console.error('Error al actualizar cantidad:', error);
        toastr.error('Hubo un error al actualizar la cantidad.');
        return false;
    });
}


// Función para verificar duplicados antes de guardar
function verificarDuplicadosAntesDeGuardar() {
    const ids = articulosSeleccionados.map(art => art.id);
    const hasDuplicates = new Set(ids).size !== ids.length;
    
    if (hasDuplicates) {
        console.error('Se detectaron artículos duplicados:', ids);
        return false;
    }
    return true;
}

// Event listener para actualizar cantidades
tablaBody.addEventListener("input", function(e) {
    if (e.target.classList.contains("actualizarCantidad")) {
        const index = e.target.dataset.index;
        const nuevaCantidad = parseInt(e.target.value);

        if (nuevaCantidad > 0) {
            const cantidadAnterior = articulosSeleccionados[index].cantidad;
            articulosSeleccionados[index].cantidad = nuevaCantidad;
            
            const idSuministro = articulosSeleccionados[index].idSuministros;

            actualizarCantidadSuministro(idSuministro, nuevaCantidad)
                .then(success => {
                    if (!success) {
                        // Revertir el cambio en caso de error
                        articulosSeleccionados[index].cantidad = cantidadAnterior;
                        e.target.value = cantidadAnterior;
                    }
                });
        } else {
            // Si la cantidad es menor a 1, revertir al valor anterior
            e.target.value = articulosSeleccionados[index].cantidad;
            toastr.warning('La cantidad debe ser al menos 1');
        }
    }
});

// Función para obtener el texto completo del artículo con etiquetas
function obtenerTextoArticulo(optionElement) {
    const nombre = optionElement.dataset.nombre;
    const marca = optionElement.dataset.marca;
    const modelo = optionElement.dataset.modelo;
    
    let textoCompleto = "Nombre: " + nombre;
    
    // Agregar marca si existe
    if (marca && marca !== '') {
        textoCompleto += " | Marca: " + marca;
    }
    
    // Agregar modelo si existe
    if (modelo && modelo !== '') {
        textoCompleto += " | Modelo: " + modelo;
    }
    
    return textoCompleto;
}
// Función mejorada para validar artículos repetidos
function validarArticuloRepetido(idArticulo) {
    return articulosSeleccionados.some(art => art.id == idArticulo);
}
// Función mejorada para agregar artículo - SIN actualizar, solo validar
function agregarOActualizarArticulo() {
    const id = articuloSelect.value;
    const optionSeleccionada = articuloSelect.options[articuloSelect.selectedIndex];
    
    // Validaciones básicas
    if (!id || id === "" || !optionSeleccionada) {
        toastr.error("Por favor, selecciona un artículo válido.");
        return;
    }

    const cantidad = parseInt(articuloCantidad.value);
    if (cantidad < 1 || isNaN(cantidad)) {
        toastr.error("Por favor, ingresa una cantidad válida (mínimo 1).");
        return;
    }

    const nombreDisplay = obtenerTextoArticulo(optionSeleccionada);
    const tipoArticulo = optionSeleccionada.dataset.tipo;

    console.log('Validando artículo ID:', id);
    console.log('Artículos actuales:', articulosSeleccionados.map(a => a.id));

    // Validar si el artículo ya existe (BUSCAR POR ID)
    const articuloExistente = articulosSeleccionados.find(art => art.id == id);
    
    if (articuloExistente) {
        // Si el artículo ya existe, solo mostrar mensaje de error
        toastr.error(`El artículo "${nombreDisplay}" ya está en la lista.`);
        return; // No hacer nada más, solo salir de la función
    }

    // Si no existe, agregar el nuevo artículo
    articulosSeleccionados.push({
        id: id,
        nombre: nombreDisplay,
        tipo: tipoArticulo,
        cantidad: cantidad,
        idSuministros: null
    });

    toastr.success('Artículo agregado correctamente');
    renderTabla();
    
    // Reset del formulario
    articuloSelect.value = "";
    $(articuloSelect).val(null).trigger("change");
    articuloCantidad.value = 1;
}






        // Agregar artículo al hacer clic en el botón "Agregar"
        document.getElementById("agregarArticulo").addEventListener("click", function() {
            agregarOActualizarArticulo();
        });



        tablaBody.addEventListener("click", function(e) {
    if (e.target.classList.contains("eliminarArticulo")) {
        // Prevenir el comportamiento por defecto del botón
        e.preventDefault();
        
        console.log('Botón de eliminar clickeado');

        const idSuministro = e.target.dataset.id; // Obtener el idSuministro desde el botón

        console.log('idSuministro:', idSuministro);

        if (!idSuministro) {
            console.error('idSuministro no encontrado');
            return;
        }

        // Mostrar confirmación antes de eliminar
        if (!confirm('¿Estás seguro de que quieres eliminar este artículo?')) {
            return;
        }

        // Hacer la solicitud al servidor para eliminar el suministro
        fetch(`/eliminar-suministro/${idSuministro}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data);

                if (data.message === 'Artículo eliminado correctamente.') {
                    // Eliminar el artículo del array local
                    articulosSeleccionados = articulosSeleccionados.filter(art => art.idSuministros !== idSuministro);

                    console.log("Artículos después de la eliminación:", articulosSeleccionados);

                    // Mostrar mensaje de éxito
                    toastr.success('Artículo eliminado correctamente');

                    // Volver a renderizar la tabla SIN recargar la página
                    renderTabla();
                } else {
                    toastr.error(data.message || 'Error al eliminar el artículo');
                }
            })
            .catch(error => {
                console.error('Error al hacer la solicitud:', error);
                toastr.error('Hubo un error al eliminar el suministro');
            });
    }
});




        // Actualizar cantidad al modificar el input
        tablaBody.addEventListener("input", function(e) {
            if (e.target.classList.contains("actualizarCantidad")) {
                const index = e.target.dataset.index;
                const nuevaCantidad = parseInt(e.target.value);

                if (nuevaCantidad > 0) {
                    articulosSeleccionados[index].cantidad = nuevaCantidad;

                    // Ahora enviamos la actualización al servidor
                    const idSuministro = articulosSeleccionados[index]
                    .idSuministros; // ID del suministro
                    const url =
                    `/actualizar-suministro/${idSuministro}`; // URL de la ruta de actualización

                    fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Asegúrate de que el token CSRF esté presente
                            },
                            body: JSON.stringify({
                                cantidad: nuevaCantidad
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Respuesta del servidor:', data);
                            if (data.message) {
                                toastr.success(data.message); // Muestra el mensaje de éxito
                            }
                        })
                        .catch(error => {
                            console.error('Error al actualizar cantidad:', error);
                            toastr.error('Hubo un error al actualizar la cantidad.');
                        });
                }
            }
        });


    // Modificar la función de guardar para incluir validación de duplicados
document.querySelector(".guardarHerramientas").addEventListener("click", function(e) {
    e.preventDefault();

    // Verificar duplicados antes de guardar
    if (!verificarDuplicadosAntesDeGuardar()) {
        toastr.error('Se detectaron artículos duplicados. Por favor, refresca la tabla.');
        return;
    }

    // Recolectar los artículos seleccionados
    const articulosData = articulosSeleccionados.map(art => ({
        id: art.id,
        cantidad: art.cantidad
    }));

    // Verificar que todos los artículos tengan un id
    const articulosInvalidos = articulosData.filter(articulo => !articulo.id);
    if (articulosInvalidos.length > 0) {
        toastr.error('Por favor actualice la página.');
        return;
    }

    // Verificar que haya artículos para guardar
    if (articulosData.length === 0) {
        toastr.warning('No hay artículos para guardar.');
        return;
    }

    console.log("Enviando datos:", articulosData);

    fetch('/guardar-suministros', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                articulos: articulosData,
                ticketId: ticketId,
                visitaId: visitaId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            console.log("Respuesta del servidor:", data);

            if (data.message) {
                toastr.success(data.message);
                obtenerSuministros(); // Sincronizar con el servidor
            } else {
                toastr.error('Error en la respuesta del servidor');
            }
        })
        .catch(error => {
            console.error('Error en la solicitud AJAX:', error);
            toastr.error('Hubo un error al guardar los suministros.');
        });
});

    });
</script>