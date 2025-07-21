<x-layout.default>

    <link href="{{ Vite::asset('resources/css/fullcalendar.min.css') }}" rel='stylesheet' />
    <!-- TomSelect CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <!-- Agrega estos estilos -->
    <style>
        .fc {
            text-transform: uppercase;
            font-weight: 600;
        }
    </style>

    <script src='/assets/js/fullcalendar.min.js'></script>
    <div x-data="calendar">
        <div class="panel">
            <div class="mb-5">
                <div class="mb-4 flex items-center sm:flex-row flex-col sm:justify-between justify-center">
                    <div class="sm:mb-0 mb-4">
                        <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right text-center">Calendario
                        </div>
                        <div class="flex items-center mt-2 flex-wrap sm:justify-start justify-center">
                            <template x-for="etiqueta in etiquetas" :key="etiqueta.id">
                                <div class="flex items-center ltr:mr-4 rtl:ml-4 group">
                                    <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2"
                                        :class="`bg-${etiqueta.color}`"></div>
                                    <div x-text="etiqueta.nombre" class="mr-1"></div>
                                    <!-- Botones de acción para cada etiqueta -->
                                    <button @click="editEtiqueta(etiqueta)"
                                        class="text-gray-400 hover:text-blue-500 invisible group-hover:visible">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="deleteEtiqueta(etiqueta.id)"
                                        class="text-gray-400 hover:text-red-500 invisible group-hover:visible">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" class="btn btn-primary" @click="editEvent()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Crear Actividad
                        </button>
                        <button type="button" class="btn btn-success" @click="showEtiquetaModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ltr:mr-2 rtl:ml-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Nueva Etiqueta
                        </button>
                    </div>
                </div>

                <!-- Modal para etiquetas -->
                <div class="fixed inset-0 bg-[black]/60 z-[999] overflow-y-auto hidden"
                    :class="isEtiquetaModal && '!block'">
                    <div class="flex items-center justify-center min-h-screen px-4"
                        @click.self="isEtiquetaModal = false">
                        <div x-show="isEtiquetaModal" x-transition x-transition.duration.300
                            class="panel border-0 p-0 rounded-lg overflow-hidden md:w-full max-w-lg w-[90%] my-8">
                            <button type="button"
                                class="absolute top-4 ltr:right-4 rtl:left-4 text-white-dark hover:text-dark"
                                @click="isEtiquetaModal = false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                            <h3 class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]"
                                x-text="etiquetaParams.id ? 'Editar Etiqueta' : 'Nueva Etiqueta'"></h3>
                            <div class="p-5">
                                <form @submit.prevent="saveEtiqueta">
                                    <div class="mb-5">
                                        <label for="etiquetaNombre">Nombre:</label>
                                        <input id="etiquetaNombre" type="text" class="form-input"
                                            placeholder="Nombre de la etiqueta" x-model="etiquetaParams.nombre"
                                            required />
                                    </div>

                                    <div class="mb-5">
                                        <label for="etiquetaColor">Color:</label>
                                        <select id="etiquetaColor" class="form-select" x-model="etiquetaParams.color"
                                            required>
                                            <option value="">Selecciona un color</option>
                                            <option value="primary">Azul (Primary)</option>
                                            <option value="success">Verde (Success)</option>
                                            <option value="danger">Rojo (Danger)</option>
                                            <option value="warning">Amarillo (Warning)</option>
                                            <option value="info">Cian (Info)</option>
                                            <option value="secondary">Gris (Secondary)</option>
                                            <option value="dark">Negro (Dark)</option>
                                            <option value="indigo">Indigo</option>
                                            <option value="purple">Morado</option>
                                            <option value="pink">Rosa</option>
                                        </select>
                                    </div>

                                    <div class="mb-5">
                                        <label for="etiquetaIcono">Icono (opcional):</label>
                                        <input id="etiquetaIcono" type="text" class="form-input"
                                            placeholder="Ej: fa-calendar" x-model="etiquetaParams.icono" />
                                    </div>

                                    <div class="flex justify-end items-center mt-8">
                                        <button type="button" x-show="etiquetaParams.id"
                                            @click="deleteEtiqueta(etiquetaParams.id)"
                                            class="btn btn-outline-danger ltr:mr-2 rtl:ml-2">
                                            Eliminar
                                        </button>
                                        <button type="button" class="btn btn-outline-danger"
                                            @click="isEtiquetaModal = false">Cancelar</button>
                                        <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                            x-text="etiquetaParams.id ? 'Actualizar' : 'Crear'"></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de evento -->
                <div class="fixed inset-0 bg-[black]/60 z-[999] overflow-y-auto hidden"
                    :class="isAddEventModal && '!block'">
                    <div class="flex items-center justify-center min-h-screen px-4"
                        @click.self="isAddEventModal = false">
                        <div x-show="isAddEventModal" x-transition x-transition.duration.300
                            class="panel border-0 p-0 rounded-lg overflow-hidden md:w-full max-w-lg w-[90%] my-8">
                            <button type="button"
                                class="absolute top-4 ltr:right-4 rtl:left-4 text-white-dark hover:text-dark"
                                @click="isAddEventModal = false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                            <h3 class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]"
                                x-text="params.id ? 'Editar Actividad' : 'Actividad'"></h3>
                            <div class="p-5">
                                <form @submit.prevent="saveEvent">
                                    <div class="mb-5">
                                        <label for="title">Titulo de Actividad :</label>
                                        <input id="title" type="text" name="title" id="title"
                                            class="form-input" placeholder="Reunión" x-model="params.title"
                                            required />
                                        <div class="text-danger mt-2" id="titleErr"></div>
                                    </div>

                                    <div class="mb-5">
                                        <label for="enlaceevento">Link de Actividad (Opcional) :</label>
                                        <input id="enlaceevento" type="url" name="enlaceevento"
                                            class="form-input" placeholder="https://erp.beyritech.com/actividad"
                                            x-model="params.enlaceevento" />
                                    </div>

                                    <div class="mb-5">
                                        <label for="ubicacion">Ubicacion (Opcional) :</label>
                                        <input id="ubicacion" type="text" name="ubicacion" class="form-input"
                                            placeholder="Av Sta Elvira E Mz B Lt 8, Los Olivos 15306"
                                            x-model="params.ubicacion" />
                                    </div>

                                    <div class="mb-5">
                                        <label for="etiqueta">Etiqueta:</label>
                                        <select id="etiqueta" class="form-select" x-model="params.etiqueta"
                                            required>
                                            <option value="">Select a Etiqueta</option>
                                            <template x-for="etiqueta in etiquetas" :key="etiqueta.id">
                                                <option :value="etiqueta.nombre" x-text="etiqueta.nombre"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <div class="mb-5">
                                        <label for="dateStart">Inicio :</label>
                                        <input id="dateStart" type="datetime-local" name="start" id="start"
                                            class="form-input" placeholder="Event Start Date" x-model="params.start"
                                            :min="minStartDate" @change="startDateChange($event)" required />
                                        <div class="text-danger mt-2" id="startDateErr"></div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="dateEnd">Fin :</label>
                                        <input id="dateEnd" type="datetime-local" name="end" id="end"
                                            class="form-input" placeholder="Event End Date" x-model="params.end"
                                            :min="minEndDate" required />
                                        <div class="text-danger mt-2" id="endDateErr"></div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="description">Descripción de Actividad (Opcional) :</label>
                                        <textarea id="description" name="description" id="description" class="form-textarea min-h-[130px]"
                                            placeholder="Ingrese Descripción" x-model="params.description"></textarea>
                                    </div>
                                    <div class="mb-5">
                                        <label for="invitados">Seleccione Invitados (Opcional) :</label>
                                        <select id="invitados" name="invitados[]" multiple
                                            class="tom-select"></select>

                                    </div>

                                    <div class="flex justify-end items-center mt-8">
                                        <button type="button" x-show="params.id" @click="deleteEvent()"
                                            class="btn btn-outline-danger ltr:mr-2 rtl:ml-2" :disabled="isDeleting">
                                            <span x-show="isDeleting" class="animate-spin -ml-1 mr-2 h-4 w-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </span>
                                            <span x-text="isDeleting ? 'Eliminando...' : 'Eliminar'"></span>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger"
                                            @click="isAddEventModal = false">Cancelar</button>
                                        <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                            x-text="params.id ? (isSaving ? 'Procesando...' : 'Actualizar Actividad') : (isSaving ? 'Procesando...' : 'Crear Actividad')"
                                            :disabled="isSaving">
                                            <span x-show="isSaving" class="animate-spin -ml-1 mr-2 h-4 w-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="calendar-wrapper" id='calendar'></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="{{ asset('assets/js/calendario/calendario.js') }}"></script>
    <!-- JS de NiceSelect -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>
</x-layout.default>
