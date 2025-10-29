<!-- CDN Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<style>
    /* ======== Estilos Mejorados para el Componente ======== */
    .articulos-container {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .dark .articulos-container {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    }

    .articulos-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .articulos-badge {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
    }

    .articulos-controls {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        background: white;
        padding: 1.25rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .dark .articulos-controls {
        background: #1e293b;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .select-articulo {
        min-width: 300px;
        flex: 1;
    }

    .cantidad-input {
        width: 100px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem;
        font-weight: 600;
        text-align: center;
        transition: all 0.2s ease;
    }

    .cantidad-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .dark .cantidad-input {
        background: #0f172a;
        border-color: #334155;
        color: #f1f5f9;
    }

    .btn-agregar {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
    }

    .btn-agregar:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.4);
    }

    .btn-agregar:active {
        transform: translateY(0);
    }

    /* ======== Estilos para la Tabla ======== */
    .tabla-articulos {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .dark .tabla-articulos {
        background: #1e293b;
    }

    .tabla-articulos thead {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    }

    .tabla-articulos th {
        color: white;
        font-weight: 600;
        padding: 1rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .tabla-articulos tbody tr {
        transition: background-color 0.2s ease;
        border-bottom: 1px solid #e2e8f0;
    }

    .dark .tabla-articulos tbody tr {
        border-bottom-color: #334155;
    }

    .tabla-articulos tbody tr:hover {
        background-color: #f8fafc;
    }

    .dark .tabla-articulos tbody tr:hover {
        background-color: #334155;
    }

    .tabla-articulos td {
        padding: 1rem;
        vertical-align: middle;
    }

    .badge-tipo {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .badge-producto { background: #10b981; color: white; }
    .badge-repuesto { background: #f59e0b; color: white; }
    .badge-herramienta { background: #ef4444; color: white; }
    .badge-suministro { background: #8b5cf6; color: white; }

    .btn-eliminar {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-eliminar:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
    }

    .btn-guardar {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
    }

    .btn-guardar:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.4);
    }

    /* ======== Select2 Mejorado ======== */
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        height: 44px;
        transition: all 0.2s ease;
    }

    .select2-container--default .select2-selection--single:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1e293b;
        line-height: 40px;
        font-size: 14px;
        padding-left: 12px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #3b82f6;
    }

    /* Modo oscuro para Select2 */
    .dark .select2-container--default .select2-selection--single {
        background-color: #0f172a;
        border-color: #334155;
    }

    .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #f1f5f9;
    }

    .dark .select2-dropdown {
        background-color: #1e293b;
        border-color: #334155;
    }

    .dark .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #334155;
    }

    .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #3b82f6;
    }
</style>

<div class="articulos-container">
    <div class="articulos-header">
        <span class="articulos-badge">📦 Gestión de Artículos</span>
    </div>

    <!-- Controles para agregar artículos -->
    <div class="articulos-controls">
        <select id="articuloSelect" class="select-articulo herramienta-select">
            <option selected disabled value="">🔍 Seleccione un artículo</option>
            @foreach ($articulos as $articulo)
            @php
                // Determinar qué campo usar para el nombre según el tipo
                $nombreMostrar = $articulo->idTipoArticulo == 2 ? ($articulo->codigo_repuesto ?? 'Sin código') : ($articulo->nombre ?? 'Sin nombre');
            @endphp
            <option value="{{ $articulo->idArticulos }}" 
                    data-tipo="{{ $articulo->idTipoArticulo }}"
                    data-nombre="{{ $nombreMostrar }}">
                {{ strtoupper($nombreMostrar) }} - {{ strtoupper($articulo->tipo_nombre) }}
            </option>
            @endforeach
        </select>

        <div class="flex items-center gap-2">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad:</span>
            <input type="number" id="articuloCantidad" class="cantidad-input" value="1" min="1" />
        </div>

        <button id="agregarArticulo" class="btn-agregar">
            ➕ Agregar Artículo
        </button>
    </div>

    <!-- Tabla de artículos -->
    <div class="overflow-x-auto">
        <table class="min-w-full tabla-articulos">
            <thead>
                <tr>
                    <th class="text-left">Artículo</th>
                    <th class="text-center">Tipo</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaResumenHerramientas" class="divide-y divide-gray-200 dark:divide-gray-600">
                <!-- Contenido dinámico -->
            </tbody>
        </table>
    </div>

    <!-- Botón Guardar -->
    <div class="flex justify-end mt-6">
        <button type="submit" class="btn-guardar">
            💾 Guardar Artículos
        </button>
    </div>
</div>

<input type="hidden" id="ticketId" value="{{ $id }}">
<input type="hidden" id="visitaId" value="{{ $idVisitaSeleccionada }}">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar Select2
        $('#articuloSelect').select2({
            placeholder: '🔍 Buscar artículo...',
            allowClear: true,
            width: '100%',
            templateResult: function(articulo) {
                if (!articulo.id) {
                    return articulo.text;
                }
                
                // Obtener el tipo del artículo desde data-tipo
                const tipo = $(articulo.element).data('tipo');
                let badgeClass = '';
                let tipoText = '';
                
                // Asignar clases según el tipo
                switch(parseInt(tipo)) {
                    case 1:
                        badgeClass = 'badge-producto';
                        tipoText = 'PRODUCTO';
                        break;
                    case 2:
                        badgeClass = 'badge-repuesto';
                        tipoText = 'REPUESTO';
                        break;
                    case 3:
                        badgeClass = 'badge-herramienta';
                        tipoText = 'HERRAMIENTA';
                        break;
                    case 4:
                        badgeClass = 'badge-suministro';
                        tipoText = 'SUMINISTRO';
                        break;
                    default:
                        badgeClass = 'badge-suministro';
                        tipoText = 'SIN TIPO';
                }
                
                return $(
                    `<div class="flex justify-between items-center">
                        <span>${articulo.text.split(' - ')[0]}</span>
                        <span class="badge-tipo ${badgeClass}">${tipoText}</span>
                    </div>`
                );
            }
        });

        const articuloSelect = document.getElementById("articuloSelect");
        const articuloCantidad = document.getElementById("articuloCantidad");
        const tablaBody = document.getElementById("tablaResumenHerramientas");
        let articulosSeleccionados = [];

        const ticketId = document.getElementById("ticketId").value;
        const visitaId = document.getElementById("visitaId").value;

        // Función para obtener el nombre del tipo de artículo
        function obtenerTipoArticulo(idTipo) {
            switch(parseInt(idTipo)) {
                case 1: return { texto: 'PRODUCTO', clase: 'badge-producto' };
                case 2: return { texto: 'REPUESTO', clase: 'badge-repuesto' };
                case 3: return { texto: 'HERRAMIENTA', clase: 'badge-herramienta' };
                case 4: return { texto: 'SUMINISTRO', clase: 'badge-suministro' };
                default: return { texto: 'SIN TIPO', clase: 'badge-suministro' };
            }
        }

        // Función para renderizar la tabla
        function renderTabla() {
            tablaBody.innerHTML = "";

            if (articulosSeleccionados.length === 0) {
                tablaBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <span class="text-4xl mb-2">📦</span>
                                <p class="text-lg font-medium">No hay artículos agregados</p>
                                <p class="text-sm">Agrega artículos usando el formulario superior</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            articulosSeleccionados.forEach((art, index) => {
                const tipo = obtenerTipoArticulo(art.tipo);
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td class="font-medium text-gray-900 dark:text-white">
                        <div class="flex flex-col">
                            <span>${art.nombre}</span>
                            ${art.tipo == 2 ? '<span class="text-xs text-gray-500 dark:text-gray-400">Código Repuesto</span>' : ''}
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge-tipo ${tipo.clase}">${tipo.texto}</span>
                    </td>
                    <td class="text-center">
                        <input type="number" min="1" value="${art.cantidad}" 
                            class="cantidad-input actualizarCantidad" data-index="${index}" />
                    </td>
                    <td class="text-center">
                        <button class="btn-eliminar eliminarArticulo" data-id="${art.idSuministros}">
                            🗑️ Eliminar
                        </button>
                    </td>
                `;
                tablaBody.appendChild(tr);
            });
        }

        function obtenerSuministros() {
            fetch(`/get-suministros/${ticketId}/${visitaId}`)
                .then(response => response.json())
                .then(data => {
                    articulosSeleccionados = data.map(item => ({
                        idSuministros: item.idSuministros,
                        id: item.idArticulos,
                        nombre: item.nombre || item.codigo_repuesto || 'Sin nombre', // Fallback para ambos campos
                        cantidad: item.cantidad,
                        tipo: item.idTipoArticulo || 4
                    }));
                    renderTabla();
                })
                .catch(error => {
                    console.error('Error al obtener los suministros:', error);
                });
        }

        // Llamada inicial
        obtenerSuministros();

        function agregarOActualizarArticulo() {
            const selectedOption = articuloSelect.options[articuloSelect.selectedIndex];
            const id = articuloSelect.value;
            const nombre = selectedOption?.getAttribute('data-nombre');
            const tipo = selectedOption?.getAttribute('data-tipo');
            const cantidad = parseInt(articuloCantidad.value);

            if (!id || id === "" || cantidad < 1 || !nombre) {
                toastr.error("Por favor, selecciona un artículo válido y una cantidad.");
                return;
            }

            const indexExistente = articulosSeleccionados.findIndex(a => a.id === id);

            if (indexExistente !== -1) {
                articulosSeleccionados[indexExistente].cantidad = cantidad;
            } else {
                const articuloRepetido = articulosSeleccionados.find(art => art.id === id);
                if (articuloRepetido) {
                    toastr.error('Este artículo ya ha sido agregado anteriormente.');
                    return;
                }

                articulosSeleccionados.push({
                    id: id,
                    nombre: nombre,
                    cantidad: cantidad,
                    tipo: tipo,
                    idSuministros: null
                });
            }

            renderTabla();
            articuloSelect.value = "";
            $(articuloSelect).val(null).trigger("change");
            articuloCantidad.value = 1;
        }

        // Event listeners
        document.getElementById("agregarArticulo").addEventListener("click", agregarOActualizarArticulo);

        // Los demás event listeners y funciones se mantienen igual...
        tablaBody.addEventListener("click", function(e) {
            if (e.target.classList.contains("eliminarArticulo")) {
                const idSuministro = e.target.dataset.id;
                if (!idSuministro) {
                    console.error('idSuministro no encontrado');
                    return;
                }

                fetch(`/eliminar-suministro/${idSuministro}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message === 'Artículo eliminado correctamente.') {
                            articulosSeleccionados = articulosSeleccionados.filter(art => art.idSuministros !== idSuministro);
                            renderTabla();
                        } else {
                            toastr.error('Por favor actualice la página');
                        }
                    })
                    .catch(error => {
                        console.error('Error al hacer la solicitud:', error);
                        toastr.error('Hubo un error al eliminar el suministro');
                    });
            }
        });

        tablaBody.addEventListener("input", function(e) {
            if (e.target.classList.contains("actualizarCantidad")) {
                const index = e.target.dataset.index;
                const nuevaCantidad = parseInt(e.target.value);

                if (nuevaCantidad > 0) {
                    articulosSeleccionados[index].cantidad = nuevaCantidad;
                    const idSuministro = articulosSeleccionados[index].idSuministros;
                    const url = `/actualizar-suministro/${idSuministro}`;

                    fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({ cantidad: nuevaCantidad })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                toastr.success(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error al actualizar cantidad:', error);
                            toastr.error('Hubo un error al actualizar la cantidad.');
                        });
                }
            }
        });

        document.querySelector(".btn-guardar").addEventListener("click", function(e) {
            e.preventDefault();
            const articulosData = articulosSeleccionados.map(art => ({
                id: art.id,
                cantidad: art.cantidad
            }));

            const articulosInvalidos = articulosData.filter(articulo => !articulo.id);
            if (articulosInvalidos.length > 0) {
                toastr.error('Por favor actualice la página.');
                return;
            }

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
                .then(response => response.text())
                .then(data => {
                    try {
                        const jsonResponse = JSON.parse(data);
                        if (jsonResponse.message) {
                            toastr.success(jsonResponse.message);
                        }
                    } catch (error) {
                        console.error('Error al parsear JSON:', error);
                        toastr.error('Error en la respuesta del servidor.');
                    }
                })
                .catch(error => {
                    console.error('Error en la solicitud AJAX:', error);
                    toastr.error('Hubo un error al guardar los suministros.');
                });
        });
    });
</script>