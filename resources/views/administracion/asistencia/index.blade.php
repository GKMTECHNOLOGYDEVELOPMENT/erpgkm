<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .panel {
            overflow: visible !important;
        }

        #tablaAsistencias {
            min-width: 1000px;
        }

        .dataTables_wrapper {
            width: 100%;
        }

        .dataTables_scrollable {
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .dataTables_scrollable table {
            min-width: 1000px;
        }

        td.col-ubicacion {
            white-space: normal !important;
            word-break: break-word !important;
        }

        .dataTables_length select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            padding-right: 1.5rem;
            /* Ajusta espacio a la derecha para que el texto no se corte */
            background-image: none;
            /* Opcional, elimina cualquier ícono */
        }
    </style>

    <div x-data="usuariosTable">
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li><a href="javascript:;" class="text-primary hover:underline">Administración</a></li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1"><span>Asistencias</span></li>
            </ul>
        </div>
        <!-- Filtros -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
            <div>
                <label for="startDateInput" class="text-sm font-medium text-gray-700">Fecha Inicio</label>
                <input type="text" id="startDate" class="form-input w-full" placeholder="Seleccionar Fecha">
            </div>

            <div>
                <label for="endDateInput" class="text-sm font-medium text-gray-700">Fecha Fin</label>
                <input type="text" id="endDate" class="form-input w-full" placeholder="Seleccionar Fecha">
            </div>
        </div>

        <div class="panel mt-6">
            <table id="tablaAsistencias" class="table whitespace-nowrap">
                <thead>
                    <tr>
                        <th class="font-bold text-center w-[200px] break-words whitespace-normal col-ubicacion">EMPLEADO</th>
                        <th class="font-bold text-center">FECHA</th>
                        <th class="font-bold text-center">ENTRADA</th>
                        <th class="font-bold text-center w-[200px] break-words whitespace-normal col-ubicacion">UBICACIÓN ENTRADA</th>
                        <th class="font-bold text-center">INICIO BREAK</th>
                        <th class="font-bold text-center">FIN BREAK</th>
                        <th class="font-bold text-center">SALIDA</th>
                        <th class="font-bold text-center w-[200px] break-words whitespace-normal col-ubicacion">UBICACIÓN SALIDA</th>
                        <th class="font-bold text-center">ASISTENCIA</th>
                    </tr>
                </thead>

                <tbody></tbody>
            </table>


        </div>
    </div>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="{{ asset('assets/js/asistencias/asistencia.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>
