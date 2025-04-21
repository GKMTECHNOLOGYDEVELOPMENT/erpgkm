<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />

    <style>
        /* Agrupar buscador y paginación en la misma línea */
        div.dataTables_wrapper .dataTables_filter,
        div.dataTables_wrapper .dataTables_paginate {
            display: inline-block;
            vertical-align: middle;
        }

        div.dataTables_wrapper .dataTables_filter {
            float: right;
        }

        div.dataTables_wrapper .dataTables_paginate {
            float: right;
            margin-right: 10px;
        }

        .truncate {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    </style>


    <div class="p-4">
        <!-- Filtros -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div>
                <label for="startDateInput" class="text-sm font-medium text-gray-700">Fecha Inicio</label>
                <input type="text" id="startDate" class="form-input w-full" placeholder="Seleccionar Fecha">
            </div>

            <div>
                <label for="endDateInput" class="text-sm font-medium text-gray-700">Fecha Fin</label>
                <input type="text" id="endDate" class="form-input w-full" placeholder="Seleccionar Fecha">
            </div>
        </div>

        <!-- Tabla -->
        <div class="panel w-full">
            <div class="w-full overflow-x-auto">
                <table id="tablaAsistencias" class="display w-full text-sm text-center text-gray-700 table-auto">
                    <thead>
                        <tr>
                            <th class="font-bold">EMPLEADO</th>
                            <th class="font-bold">FECHA</th>
                            <th class="font-bold">ENTRADA</th>
                            <th class="font-bold">UBICACIÓN ENTRADA</th>
                            <th class="font-bold">INICIO BREAK</th>
                            <th class="font-bold">FIN BREAK</th>
                            <th class="font-bold">SALIDA</th>
                            <th class="font-bold">UBICACIÓN SALIDA</th>
                            <th class="font-bold">ASISTENCIA</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="{{ asset('assets/js/asistencias/asistencia.js') }}"></script>

</x-layout.default>
