<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <div class="panel mt-6">
        <div class="mb-4 flex flex-wrap justify-between items-center gap-3">
            <!-- Botón agregar -->
            <a href="{{ route('Seguimiento.create') }}"
                class="btn btn-sm bg-success text-white hover:bg-green-600 px-4 py-2 rounded shadow-sm flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Nuevo Seguimiento</span>
            </a>

            <!-- Grupo búsqueda -->
            <div class="flex items-center gap-3">
                <div class="relative w-64">
                    <input type="text" id="searchInput" placeholder="Buscar cliente..."
                        class="pr-10 pl-4 py-2 text-sm w-full border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
                    <button type="button" id="clearInput"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 hidden">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
                <button id="btnSearch"
                    class="btn btn-sm bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded shadow-sm">
                    Buscar
                </button>
            </div>
        </div>

        <!-- Tabla -->
<table id="tablaClientes" class="w-full min-w-[700px] table whitespace-nowrap">
    <thead>
        <tr>
            <th class="text-center">Tipo Prospecto</th>
            <th class="text-center">Nombre del Prospecto</th>
            <th class="text-center">Documento</th>
            <th class="text-center">Usuario</th>
            <th class="text-center">Fecha de Ingreso</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
</table>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="{{ asset('assets/js/areacomercial/list.js') }}"></script>

</x-layout.default>
