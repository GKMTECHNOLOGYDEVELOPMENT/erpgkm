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
</style>

<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md mt-6">
    <span class="text-sm sm:text-lg font-semibold badge bg-success">Articulos</span>


    <!-- Selector e Input -->
    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4 items-center mb-6">
        <select id="articuloSelect" class="w-full herramienta-select">
            <option selected disabled value="">Seleccione un artículo</option>
            @foreach ($articulosTipo1 as $articulo)
                <option value="{{ $articulo->idArticulos }}">{{ $articulo->nombre }}</option>
            @endforeach
        </select>

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
                <!-- dinámico -->
            </tbody>
        </table>
    </div>

    <div class="flex justify-end mt-4">
        <button type="submit" class="btn btn-primary guardarHerramientas">Guardar</button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const articuloSelect = document.getElementById("articuloSelect");
        const articuloCantidad = document.getElementById("articuloCantidad");
        const tablaBody = document.getElementById("tablaResumenHerramientas");
        let articulosSeleccionados = [];

        // Inicializar select2
        $(articuloSelect).select2({
            width: "100%",
            placeholder: "Seleccione un artículo",
            allowClear: true
        });

        function renderTabla() {
            tablaBody.innerHTML = "";

            articulosSeleccionados.forEach((art, index) => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                <td class="px-3 py-1">${art.nombre}</td>
                <td class="px-3 py-1 text-center">
                    <input type="number" min="1" value="${art.cantidad}" 
                        class="form-input w-16 text-center actualizarCantidad" data-index="${index}" />
                </td>
                <td class="px-3 py-1 text-center">
                    <button class="btn btn-sm btn-danger eliminarArticulo" data-index="${index}">Eliminar</button>
                </td>
            `;
                tablaBody.appendChild(tr);
            });
        }

        function agregarOActualizarArticulo() {
            const id = articuloSelect.value;
            const nombre = articuloSelect.options[articuloSelect.selectedIndex]?.text;
            const cantidad = parseInt(articuloCantidad.value);

            if (!id || cantidad < 1) return;

            const indexExistente = articulosSeleccionados.findIndex(a => a.id === id);

            if (indexExistente !== -1) {
                articulosSeleccionados[indexExistente].cantidad = cantidad;
            } else {
                articulosSeleccionados.push({
                    id,
                    nombre,
                    cantidad
                });
            }

            renderTabla();

            // Reset
            articuloSelect.value = "";
            $(articuloSelect).val(null).trigger("change");
            articuloCantidad.value = 1;
        }

        articuloSelect.addEventListener("change", agregarOActualizarArticulo);
        articuloCantidad.addEventListener("input", () => {
            if (articuloSelect.value) agregarOActualizarArticulo();
        });

        tablaBody.addEventListener("click", function(e) {
            if (e.target.classList.contains("eliminarArticulo")) {
                const index = e.target.dataset.index;
                articulosSeleccionados.splice(index, 1);
                renderTabla();
            }
        });

        tablaBody.addEventListener("input", function(e) {
            if (e.target.classList.contains("actualizarCantidad")) {
                const index = e.target.dataset.index;
                const nuevaCantidad = parseInt(e.target.value);
                if (nuevaCantidad > 0) {
                    articulosSeleccionados[index].cantidad = nuevaCantidad;
                }
            }
        });

        document.querySelector(".guardarHerramientas").addEventListener("click", function(e) {
            e.preventDefault();
            console.log("Artículos a guardar:", articulosSeleccionados);
            // Enviar por fetch/AJAX si deseas
        });
    });
</script>
