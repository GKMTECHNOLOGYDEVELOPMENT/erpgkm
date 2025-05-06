<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/viewerjs/dist/viewer.min.css" />
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
                        <th class="font-bold text-center w-[200px] break-words whitespace-normal col-ubicacion">EMPLEADO
                        </th>
                        <th class="font-bold text-center">FECHA</th>
                        <th class="font-bold text-center">ENTRADA</th>
                        <th class="font-bold text-center w-[200px] break-words whitespace-normal col-ubicacion">
                            UBICACIÓN ENTRADA</th>
                        <th class="font-bold text-center">INICIO BREAK</th>
                        <th class="font-bold text-center">FIN BREAK</th>
                        <th class="font-bold text-center">SALIDA</th>
                        <th class="font-bold text-center w-[200px] break-words whitespace-normal col-ubicacion">
                            UBICACIÓN SALIDA</th>
                        <th class="font-bold text-center">ASISTENCIA</th>
                    </tr>
                </thead>

                <tbody></tbody>
            </table>


        </div>

        <div id="modalObservacion" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto">
            <div class="flex items-start justify-center min-h-screen px-4" onclick="cerrarModalObservacion()">
                <div class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg"
                    onclick="event.stopPropagation()">
                    <!-- Header -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Observación</h5>
                        <button type="button" class="text-white-dark hover:text-dark"
                            onclick="cerrarModalObservacion()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- Cuerpo -->
                    <div class="p-5 space-y-4">
                        <p id="observacionMensaje" class="text-sm text-gray-700 dark:text-white-dark/70">Mensaje</p>
                        <div id="observacionImagenes" class="flex flex-wrap gap-2"></div>

                        <!-- Botones de acción -->
                        <div id="observacionAcciones" class="flex justify-end gap-3 pt-4 border-t mt-4 hidden">
                            <button type="button" class="btn btn-outline-danger"
                                onclick="denegarObservacion()">Denegado</button>
                            <button type="button" class="btn btn-primary"
                                onclick="aprobarObservacion()">Aprobado</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>



        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/viewerjs/dist/viewer.min.js"></script>
        <script src="{{ asset('assets/js/asistencias/asistencia.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>
