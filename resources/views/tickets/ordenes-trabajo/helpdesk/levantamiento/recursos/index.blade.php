<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">

    <!-- Sección de Herramientas -->
    <div class="p-5 rounded-lg shadow-md">
        <span class="text-lg font-semibold mb-4 badge bg-success">Herramientas</span>
        <div class="flex justify-end mt-2">
            <button type="button" id="addHerramienta" class="btn btn-primary">+</button>
        </div>
     <!-- Contenedor de Herramientas (idTipoArticulo = 1) -->
<div id="herramientasContainer" class="h-40 border overflow-y-auto p-3 rounded-lg mt-2">
    <div class="flex items-center gap-2 mt-2 herramienta-row">
        <select class="form-input w-full herramienta-select">
            @foreach($articulosTipo1 as $articulo)
                <option value="{{ $articulo->nombre }}">{{ $articulo->nombre }}</option>
            @endforeach
        </select>
        <input type="number" class="form-input w-20 cantidad-input" min="1" value="1">
        <button type="button" class="btn btn-danger removeHerramienta hidden">-</button>
    </div>
</div>



        <div class="flex justify-end mt-6">
            <button type="submit" class="btn btn-primary px-6 py-2">Guardar</button>
        </div>
    </div>

    <!-- Sección de Productos -->
    <div class="p-5 rounded-lg shadow-md">
        <span class="text-lg font-semibold mb-4 badge bg-success">Productos</span>
        <div class="flex justify-end mt-2">
            <button type="button" id="addProducto" class="btn btn-primary">+</button>
        </div>
        <div id="productosContainer" class="h-40 border overflow-y-auto p-3 rounded-lg mt-2">
        <div class="flex items-center gap-2 mt-2 producto-row">
            <select class="form-input w-full producto-select">
                @foreach($articulosTipo2 as $articulo)
                    <option value="{{ $articulo->nombre }}">{{ $articulo->nombre }}</option>
                @endforeach
            </select>
            <input type="number" class="form-input w-20 cantidad-input" min="1" value="1">
            <button type="button" class="btn btn-danger removeProducto hidden">-</button>
        </div>
    </div>
        <div class="flex justify-end mt-6">
            <button type="submit" class="btn btn-primary guardarProductos">Guardar</button>
        </div>
    </div>


    <!-- Sección de Repuestos -->
    <div class="p-5 rounded-lg shadow-md">
        <span class="text-lg font-semibold mb-4 badge bg-success">Repuestos</span>
        <div class="flex justify-end mt-2">
            <button type="button" id="addRepuesto" class="btn btn-primary">+</button>
        </div>
        <div id="repuestosContainer" class="h-40 border overflow-y-auto p-3 rounded-lg mt-2">
        <div class="flex items-center gap-2 mt-2 repuesto-row">
            <select class="form-input w-full repuesto-select">
                @foreach($articulosTipo3 as $articulo)
                    <option value="{{ $articulo->nombre }}">{{ $articulo->nombre }}</option>
                @endforeach
            </select>
            <input type="number" class="form-input w-20 cantidad-input" min="1" value="1">
            <button type="button" class="btn btn-danger removeRepuesto hidden">-</button>
        </div>
    </div>
        <div class="flex justify-end mt-6">
            <button type="submit" class="btn btn-primary guardarRepuestos">Guardar</button>
        </div>
    </div>

    <!-- Sección de Insumos -->
    <div class="p-5 rounded-lg shadow-md">
        <span class="text-lg font-semibold mb-4 badge bg-success">Insumos</span>
        <div class="flex justify-end mt-2">
            <button type="button" id="addInsumo" class="btn btn-primary">+</button>
        </div>
        <div id="insumosContainer" class="h-40 border overflow-y-auto p-3 rounded-lg mt-2">
        <div class="flex items-center gap-2 mt-2 insumo-row">
            <select class="form-input w-full insumo-select">
                @foreach($articulosTipo4 as $articulo)
                    <option value="{{ $articulo->nombre }}">{{ $articulo->nombre }}</option>
                @endforeach
            </select>
            <input type="number" class="form-input w-20 cantidad-input" min="1" value="1">
            <button type="button" class="btn btn-danger removeInsumo hidden">-</button>
        </div>
    </div>
        <div class="flex justify-end mt-6">
            <button type="submit" class="btn btn-primary guardarInsumos">Guardar</button>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>



<script>
    document.addEventListener("DOMContentLoaded", function() {

        // ================= HERRAMIENTAS =================
        const herramientasContainer = document.getElementById("herramientasContainer");
        const addHerramientaBtn = document.getElementById("addHerramienta");

        function inicializarHerramientas() {
            $(".herramienta-select").select2({
                width: "100%",
                placeholder: "Seleccione una herramienta",
                allowClear: true
            });
        }
        inicializarHerramientas();

        addHerramientaBtn.addEventListener("click", function() {
            const nuevaFila = document.createElement("div");
            nuevaFila.classList.add("flex", "items-center", "gap-2", "mt-2", "herramienta-row");

            nuevaFila.innerHTML = `
            <select class="form-input w-full herramienta-select">
                <option value="Destornillador">Destornillador</option>
                <option value="Martillo">Martillo</option>
                <option value="Llave inglesa">Llave inglesa</option>
            </select>
            <input type="number" class="form-input w-20 cantidad-input" min="1" value="1">
            <button type="button" class="btn btn-danger removeHerramienta">-</button>
        `;

            herramientasContainer.appendChild(nuevaFila);
            inicializarHerramientas();
            actualizarBotonesEliminar("herramientasContainer", "removeHerramienta");
        });

        herramientasContainer.addEventListener("click", function(event) {
            if (event.target.classList.contains("removeHerramienta")) {
                event.target.parentElement.remove();
                actualizarBotonesEliminar("herramientasContainer", "removeHerramienta");
            }
        });

        // ================= PRODUCTOS =================
        const productosContainer = document.getElementById("productosContainer");
        const addProductoBtn = document.getElementById("addProducto");

        function inicializarProductos() {
            $(".producto-select").select2({
                width: "100%",
                placeholder: "Seleccione un producto",
                allowClear: true
            });
        }
        inicializarProductos();

        addProductoBtn.addEventListener("click", function() {
            const nuevaFila = document.createElement("div");
            nuevaFila.classList.add("flex", "items-center", "gap-2", "mt-2", "producto-row");

            nuevaFila.innerHTML = `
            <select class="form-input w-full producto-select">
                <option value="Laptop HP">Laptop HP</option>
                <option value="Monitor 24 Pulgadas">Monitor 24 Pulgadas</option>
                <option value="Teclado Mecánico">Teclado Mecánico</option>
            </select>
            <input type="number" class="form-input w-20 cantidad-input" min="1" value="1">
            <button type="button" class="btn btn-danger removeProducto">-</button>
        `;

            productosContainer.appendChild(nuevaFila);
            inicializarProductos();
            actualizarBotonesEliminar("productosContainer", "removeProducto");
        });

        productosContainer.addEventListener("click", function(event) {
            if (event.target.classList.contains("removeProducto")) {
                event.target.parentElement.remove();
                actualizarBotonesEliminar("productosContainer", "removeProducto");
            }
        });

        // ================= REPUESTOS =================
        const repuestosContainer = document.getElementById("repuestosContainer");
        const addRepuestoBtn = document.getElementById("addRepuesto");

        function inicializarRepuestos() {
            $(".repuesto-select").select2({
                width: "100%",
                placeholder: "Seleccione un repuesto",
                allowClear: true
            });
        }
        inicializarRepuestos();

        addRepuestoBtn.addEventListener("click", function() {
            const nuevaFila = document.createElement("div");
            nuevaFila.classList.add("flex", "items-center", "gap-2", "mt-2", "repuesto-row");

            nuevaFila.innerHTML = `
            <select class="form-input w-full repuesto-select">
                <option value="Batería para Laptop">Batería para Laptop</option>
                <option value="Placa Base Asus">Placa Base Asus</option>
                <option value="Fuente de Poder">Fuente de Poder</option>
            </select>
            <input type="number" class="form-input w-20 cantidad-input" min="1" value="1">
            <button type="button" class="btn btn-danger removeRepuesto">-</button>
        `;

            repuestosContainer.appendChild(nuevaFila);
            inicializarRepuestos();
            actualizarBotonesEliminar("repuestosContainer", "removeRepuesto");
        });

        repuestosContainer.addEventListener("click", function(event) {
            if (event.target.classList.contains("removeRepuesto")) {
                event.target.parentElement.remove();
                actualizarBotonesEliminar("repuestosContainer", "removeRepuesto");
            }
        });

        // ================= INSUMOS =================
        const insumosContainer = document.getElementById("insumosContainer");
        const addInsumoBtn = document.getElementById("addInsumo");

        function inicializarInsumos() {
            $(".insumo-select").select2({
                width: "100%",
                placeholder: "Seleccione un insumo",
                allowClear: true
            });
        }
        inicializarInsumos();

        addInsumoBtn.addEventListener("click", function() {
            const nuevaFila = document.createElement("div");
            nuevaFila.classList.add("flex", "items-center", "gap-2", "mt-2", "insumo-row");

            nuevaFila.innerHTML = `
            <select class="form-input w-full insumo-select">
                <option value="Toner HP">Toner HP</option>
                <option value="Papel A4">Papel A4</option>
                <option value="Cinta Adhesiva">Cinta Adhesiva</option>
            </select>
            <input type="number" class="form-input w-20 cantidad-input" min="1" value="1">
            <button type="button" class="btn btn-danger removeInsumo">-</button>
        `;

            insumosContainer.appendChild(nuevaFila);
            inicializarInsumos();
            actualizarBotonesEliminar("insumosContainer", "removeInsumo");
        });

        insumosContainer.addEventListener("click", function(event) {
            if (event.target.classList.contains("removeInsumo")) {
                event.target.parentElement.remove();
                actualizarBotonesEliminar("insumosContainer", "removeInsumo");
            }
        });

        // ================= FUNCIONES GENERALES =================
        function actualizarBotonesEliminar(containerId, removeClass) {
            const rows = document.querySelectorAll(`#${containerId} .${removeClass}`);
            rows.forEach((btn, index) => {
                btn.classList.toggle("hidden", rows.length === 1);
            });
        }
    });
</script>
