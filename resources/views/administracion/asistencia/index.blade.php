<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Toastify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
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
            <div class="flex items-end h-full">
                <button type="button" class="badge bg-secondary btn-sm px-3 py-2 shadow-md focus:outline-none"
                    onclick="
                    document.getElementById('startDate')._flatpickr.clear();
                    document.getElementById('endDate')._flatpickr.clear();
                    Alpine.store('usuariosTable').reloadTable();
                "
                    title="Reiniciar Filtros">
                    <svg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4 block mx-auto' fill='none'
                        viewBox='0 0 24 24' stroke='currentColor'>
                        <polyline points='23 4 23 10 17 10' stroke-linecap='round' />
                        <polyline points='1 20 1 14 7 14' stroke-linecap='round' />
                        <path d='M3.51 9a9 9 0 0114.36-3.36L23 10' stroke-linecap='round' />
                        <path d='M20.49 15a9 9 0 01-14.36 3.36L1 14' stroke-linecap='round' />
                    </svg>
                </button>


            </div>


        </div>

        <div class="panel mt-6">
            <table id="tablaAsistencias" class="table whitespace-nowrap">
                <thead>
                    <tr>
                        <th class="font-bold text-center">DETALLE</th>
                        <th class="font-bold text-center w-[200px] break-words whitespace-normal col-ubicacion">EMPLEADO
                        </th>
                        <th class="font-bold text-center">ASISTENCIA</th>
                        <th class="font-bold text-center">FECHA</th>
                        <th class="font-bold text-center">ENTRADA</th>
                        <th class="font-bold text-center w-[200px] break-words whitespace-normal col-ubicacion">
                            UBICACIÓN ENTRADA</th>
                        <th class="font-bold text-center">INICIO BREAK</th>
                        <th class="font-bold text-center">FIN BREAK</th>
                        <th class="font-bold text-center">SALIDA</th>
                        <th class="font-bold text-center w-[200px] break-words whitespace-normal col-ubicacion">
                            UBICACIÓN SALIDA</th>

                    </tr>
                </thead>
            </table>
        </div>

        <div id="modalObservacion" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto">
            <div class="flex items-start justify-center min-h-screen px-4" onclick="cerrarModalObservacion()">
                <div class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-5xl my-8"
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

                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Columna izquierda: info -->
                        <div class="space-y-4 md:pr-6 border-r border-gray-300 dark:border-white/20">
                            <!-- Fecha y ubicación -->
                            <p class="text-sm text-gray-600 dark:text-white-dark/60">
                                <span class="font-bold">Fecha y hora:</span> <span id="observacionFechaHora"></span>
                            </p>
                            <p class="text-sm text-gray-600 dark:text-white-dark/60">
                                <span class="font-bold">Ubicación:</span> <span id="observacionUbicacion"></span>
                            </p>

                            <!-- Mensaje -->
                            <div>
                                <label class="text-sm font-semibold text-gray-700 dark:text-white">Mensaje:</label>
                                <div
                                    class="border border-gray-400 dark:border-white/20 bg-gray-50 dark:bg-white/5 rounded-md p-3 mt-1">
                                    <p id="observacionMensaje" class="text-sm text-gray-800 dark:text-white-dark/80">
                                    </p>
                                </div>
                            </div>

                            <!-- Campo de respuesta -->
                            <div id="respuestaContenedor">
                                <label for="respuestaTexto"
                                    class="text-sm font-semibold text-gray-700 dark:text-white">Respuesta:</label>
                                <div class="relative mt-1">
                                    <textarea id="respuestaTexto" rows="3"
                                        class="form-input w-full pr-12 resize-none border-gray-300 dark:border-white/20 dark:bg-[#121c2c] dark:text-white-dark"
                                        placeholder="Escribe tu respuesta aquí..."></textarea>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div id="observacionAcciones" class="flex justify-start gap-4 pt-4 border-t mt-4">
                                <button type="button" class="btn btn-outline-danger"
                                    onclick="denegarObservacion()">Denegado</button>
                                <button type="button" class="btn btn-primary"
                                    onclick="aprobarObservacion()">Aprobado</button>
                            </div>
                        </div>


                        <!-- Columna derecha: imágenes -->
                        <div class="flex flex-col gap-2 md:pl-6 justify-start items-start">
                            <h6 class="text-sm font-semibold text-gray-700 dark:text-white">Imágenes:</h6>
                            <div id="observacionImagenes" class="flex flex-wrap gap-2 overflow-auto max-h-[500px]">
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center px-5 pb-4 mt-2 border-t pt-3">
                        <button id="btnAnteriorObs" class="btn btn-outline-primary"
                            onclick="navegarObservacion(-1)">← Anterior</button>
                        <span id="paginadorObs" class="text-sm font-semibold text-gray-700 dark:text-white"></span>
                        <button id="btnSiguienteObs" class="btn btn-outline-primary"
                            onclick="navegarObservacion(1)">Siguiente →</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/viewerjs/dist/viewer.min.js"></script>
        <!-- Toastify JS -->
        <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        <script src="{{ asset('assets/js/asistencias/asistencia.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>
