<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Botón para abrir el modal de crear visita -->
<span class="text-sm sm:text-lg font-semibold mb-2 sm:mb-4 badge" style="background-color: {{ $colorEstado }};">
    Coordinación
</span>

<div class="flex gap-1 sm:gap-2 justify-center mt-2" id="botonCoordinacionContainer">
    <!-- Si no existe el flujo con idEstadflujo = 4, existe una condición para el idTickets y idVisitas, o no existe ninguna visita, mostrar el botón -->
    <button id="crearCordinacionBtn" class="px-2 py-1 sm:px-4 sm:py-2 btn btn-success text-white rounded-lg shadow-md flex items-center text-xs sm:text-base">
        Coordinación
    </button>
</div>

<script>
    // Pasamos el valor de 'ultimaVisitaConEstado1' desde Laravel a JavaScript
    var ultimaVisitaConEstado1 = @json($ultimaVisitaConEstado1);

    console.log("Valor de ultimaVisitaConEstado1:", ultimaVisitaConEstado1);

    // Verificar si 'ultimaVisitaConEstado1' es falso
    if (!ultimaVisitaConEstado1) {
        // Si es falso (es 0 o null), ocultamos el botón
        document.getElementById('botonCoordinacionContainer').style.display = 'none';
    }
    else {
        // Si la última visita tiene estado 1 o si no hay visitas, mostramos el botón
        document.getElementById('botonCoordinacionContainer').style.display = 'flex';
    }
</script>





<!-- Modal de Detalles con nuevo estilo y cierre al hacer clic fuera -->
<div id="modalDetallesVisita" class="modal hidden fixed inset-0 z-[999] flex items-start justify-center bg-[black]/60 overflow-y-auto"
    onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="modal-content panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-xl bg-white shadow-lg">

        <!-- Cabecera del Modal -->
        <div class="flex items-center justify-between px-5 py-3 border-b">
            <h2 id="detalleNombre" class="font-bold text-lg text-gray-800 dark:text-white"></h2>
            <button id="closeModalButton" class="text-gray-500 hover:text-gray-700 text-xl">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Cuerpo del Modal -->
        <div class="p-5 max-h-[70vh] overflow-y-auto space-y-4">
            <div>
                <h3 class="font-semibold text-sm text-gray-600 mb-1">Fecha Inicio:</h3>
                <input type="text" id="detalleFechaInicioHora" class="form-input w-full px-2 py-1 border rounded-lg text-gray-700">
            </div>
            <div>
                <h3 class="font-semibold text-sm text-gray-600 mb-1">Fecha Fin:</h3>
                <input type="text" id="detalleFechaFinalHora" class="form-input w-full px-2 py-1 border rounded-lg text-gray-700">
            </div>
            <div>
                <h3 class="font-semibold text-sm text-gray-600 mb-1">Técnico:</h3>
                <select id="detalleUsuario" class="form-input w-full px-2 py-1 border rounded-lg text-gray-700">
                    <!-- Opciones con JS -->
                </select>
            </div>

            <!-- Técnicos de apoyo -->
            <div>
                <h3 class="font-semibold text-sm text-gray-600 mb-1">Técnicos de Apoyo:</h3>
                <ul id="detalleTecnicosApoyo" class="list-none space-y-2">
                    <!-- Técnicos de apoyo se cargarán aquí -->
                </ul>

                <!-- Agregar técnico de apoyo -->
                <div class="mt-4">
                    <label for="detalleTecnicoApoyo" class="font-semibold text-sm text-gray-600 mb-1">Agregar Técnico de Apoyo:</label>
                    <select id="detalleTecnicoApoyo" class="form-input w-full px-2 py-1 border rounded-lg text-gray-700">
                        <!-- Opciones con JS -->
                    </select>
                    <button id="addTecnicoApoyoButton" class="btn btn-success mt-2 w-full">Agregar</button>
                </div>
            </div>
        </div>

        <!-- Pie del Modal -->
        <div class="flex justify-end items-center px-5 py-3 border-t ">
            <button id="closeModalButtonFooter" class="btn btn-outline-danger">Cerrar</button>
            <button id="actualizarButton" class="btn btn-primary ltr:ml-4 rtl:mr-4">Actualizar</button>
        </div>

    </div>
</div>




<div id="visitasContainer" class="mt-4">
    <div id="visitasList" class="space-y-4"></div>
</div>




<!-- Modal de Detalles de Visita -->
<div x-data="{ openDetallesVisita: false }" class="mb-5"
    @toggle-modal-detalles-visita.window="openDetallesVisita = !openDetallesVisita">
    <div class="fixed inset-0 bg-black/60 z-[999] hidden overflow-y-auto" :class="openDetallesVisita && '!block'">
        <div class="flex items-start justify-center min-h-screen px-4" @click.self="openDetallesVisita = false">
            <div x-show="openDetallesVisita" x-transition.duration.300
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8">
                <!-- Header -->
                <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                    <h5 class="font-bold text-lg">Detalles de la Visita</h5>
                    <button type="button" class="text-white-dark hover:text-dark" @click="openDetallesVisita = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="w-6 h-6">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>


                <div class="modal-scroll p-5 space-y-4">
                    <!-- Contenido del modal -->
                    <div>
                        <h4 class="text-lg font-semibold">Información de la Visita</h4>
                        <p><strong>Nombre:</strong> <span id="detalleNombre"></span></p>
                        <p><strong>Ticket: </strong><span id="detalleTicket"></span></p>
                        <p><strong>Fecha de Programación:</strong> <span id="detalleFechaProgramada"></span></p>
                        <p><strong>Fecha de Asignación:</strong> <span id="detalleFechaAsignada"></span></p>
                        <p><strong>Fecha de Desplazamiento:</strong> <span id="detalleFechaDesplazamiento"></span></p>
                        <p><strong>Fecha de Llegada:</strong> <span id="detalleFechaLlegada"></span></p>
                        <p><strong>Fecha de Inicio:</strong> <span id="detalleFechaInicio"></span></p>
                        <p><strong>Fecha de Finalización:</strong> <span id="detalleFechaFinal"></span></p>
                        <p><strong>Usuario:</strong> <span id="detalleUsuario"></span></p>
                        <p><strong>Estado:</strong> <span id="detalleEstado"></span></p>
                        <p><strong>Cliente: </strong><span id="detalleTicketCliente"></span></p>
                        <p><strong>Servicio: </strong><span id="detalleTicketServicio"></span></p>
                        <p><strong>Falla: </strong><span id="detalleTicketFalla"></span></p>
                        <p><strong>Direccion: </strong><span id="detalleTicketDireccion"></span></p>
                        <p><strong>Fecha de Compra: </strong><span id="detalleTicketFechaCompra"></span></p>
                        <p><strong>Lat: </strong><span id="detalleTicketLat"></span></p>
                        <p><strong>Long: </strong><span id="detalleTicketLng"></span></p>

                    </div>
                    <!-- Botones -->
                    <div class="flex justify-end items-center mt-4">
                        <button type="button" class="btn btn-outline-danger" @click="openDetallesVisita = false">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div x-data="{
    openCondiciones: false,
    visitaId: null, // Agrega esta propiedad
    latitud: null,  // Variable para almacenar la latitud
    longitud: null, // Variable para almacenar la longitud
    ubicacion: null, // Variable para almacenar la dirección

    condiciones: {
        esTitular: true,
        noAtiende: false,
        titularNoEsTitular: { nombre: '', dni: '', telefono: '' },
        motivoNoAtiende: '',
        imagen: null // Aquí se almacena la imagen
    },

    obtenerUbicacion() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                this.latitud = position.coords.latitude;
                this.longitud = position.coords.longitude;

                // Llamar a la API de Nominatim para obtener la dirección
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${this.latitud}&lon=${this.longitud}`)
                    .then(response => response.json())
                    .then(data => {
                        this.ubicacion = data.display_name; // Almacenar la dirección obtenida
                    })
                    .catch(error => {
                        console.error('Error al obtener la ubicación:', error);
                        toastr.error('No se pudo obtener la dirección.');
                    });
            }, (error) => {
                console.error('Error al obtener la ubicación:', error);
                toastr.error('No se pudo obtener la ubicación.');
            });
        } else {
            toastr.error('La geolocalización no está soportada por este navegador.');
        }
    },

    guardarCondiciones(condiciones, ticketId, visitaId) {
        console.log('El idVisitas es:', visitaId); // Agregar console.log aquí
        console.log('Latitud:', this.latitud); // Verificar latitud
        console.log('Longitud:', this.longitud); // Verificar longitud
        console.log('Ubicación:', this.ubicacion); // Verificar dirección

        if (!condiciones.esTitular) {
            if (!condiciones.titularNoEsTitular.nombre.trim()) {
                toastr.error('El campo Nombre no puede estar vacío.');
                return;
            }
            if (!condiciones.titularNoEsTitular.dni.trim()) {
                toastr.error('El campo DNI no puede estar vacío.');
                return;
            }
            if (!condiciones.titularNoEsTitular.telefono.trim()) {
                toastr.error('El campo Teléfono no puede estar vacío.');
                return;
            }
        }

        // Validar campo de motivo si no se atiende el servicio
        if (condiciones.noAtiende && !condiciones.motivoNoAtiende.trim()) {
            toastr.error('El campo Motivo no puede estar vacío.');
            return;
        }

        // Obtener la ubicación si aún no está disponible
        if (!this.latitud || !this.longitud) {
            this.obtenerUbicacion();
        }

        // Preparar los datos para enviar
        const datos = new FormData();
        datos.append('idTickets', ticketId);
        datos.append('idVisitas', visitaId);
        datos.append('titular', condiciones.noAtiende ? 2 : (condiciones.esTitular ? 1 : 0));
        datos.append('nombre', condiciones.esTitular ? null : condiciones.titularNoEsTitular.nombre);
        datos.append('dni', condiciones.esTitular ? null : condiciones.titularNoEsTitular.dni);
        datos.append('telefono', condiciones.esTitular ? null : condiciones.titularNoEsTitular.telefono);
        datos.append('servicio', condiciones.noAtiende ? 1 : 0);
        datos.append('motivo', condiciones.noAtiende ? condiciones.motivoNoAtiende : null);
        datos.append('fecha_condicion', new Date().toISOString().slice(0, 19).replace('T', ' '));

        // Agregar las coordenadas al FormData
        datos.append('lat', this.latitud);
        datos.append('lng', this.longitud);
        datos.append('ubicacion', this.ubicacion); // Agregar la dirección obtenida

        // Si la imagen está presente, agregarla al FormData
        if (condiciones.imagen) {
            datos.append('imagen', condiciones.imagen);
        }

        // Enviar los datos al servidor
        fetch('/api/guardarCondiciones', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Agrega el token CSRF
                },
                body: datos
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success('Condiciones guardadas correctamente.');
                    this.openCondiciones = false; // Cerrar el modal
                    location.reload();
                } else {
                    toastr.error('Error al guardar las condiciones.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Hubo un error al guardar las condiciones.');
            });
    }
}" class="mb-5"
    @set-visita-id.window="visitaId = $event.detail"
    @toggle-modal-condiciones.window="openCondiciones = !openCondiciones"
    x-init="obtenerUbicacion()">
    <div class="fixed inset-0 bg-black/60 z-[999] hidden overflow-y-auto" :class="openCondiciones && '!block'">
        <div class="flex items-start justify-center min-h-screen px-4" @click.self="openCondiciones = false">
            <div x-show="openCondiciones" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8">

                <!-- Header -->
                <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                    <h5 class="font-bold text-lg">Condiciones de Inicio de Servicio</h5>
                    <button type="button" class="text-white-dark hover:text-dark" @click="openCondiciones = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="w-6 h-6">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>


                </div>
                <div class="modal-scroll p-5 space-y-4">
                    <form>
                        <div style="display: none !important;">

                            <!-- Se muestra solo si "No se atiende" NO está activado -->
                            <template x-if="!condiciones.noAtiende">
                                <div>
                                    <!-- Switch "¿Es titular?" -->
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium">¿Es titular?</span>
                                        <label class="w-12 h-6 relative">
                                            <input type="checkbox" x-model="condiciones.esTitular"
                                                class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                                id="esTitularSwitch" />
                                            <span
                                                class="outline_checkbox bg-icon border-2 border-[#ebedf2] dark:border-white-dark block h-full rounded-full
                                             before:absolute before:left-1 before:bg-[#ebedf2] dark:before:bg-white-dark before:bottom-1 before:w-4 before:h-4
                                             before:rounded-full before:bg-[url(/assets/images/close.svg)] before:bg-no-repeat before:bg-center
                                             peer-checked:before:left-7 peer-checked:before:bg-[url(/assets/images/checked.svg)]
                                             peer-checked:border-primary peer-checked:before:bg-primary before:transition-all before:duration-300"></span>
                                        </label>
                                    </div>
                                    <!-- Si no es titular, mostrar campos para Nombre, DNI y Teléfono -->
                                    <div x-show="!condiciones.esTitular" class="space-y-3 mt-2">
                                        <div>
                                            <label class="block text-sm font-medium">Nombre</label>
                                            <input type="text" x-model="condiciones.titularNoEsTitular.nombre"
                                                class="form-input w-full">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium">DNI</label>
                                            <input type="text" x-model="condiciones.titularNoEsTitular.dni"
                                                class="form-input w-full">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium">Teléfono</label>
                                            <input type="text" x-model="condiciones.titularNoEsTitular.telefono"
                                                class="form-input w-full">
                                        </div>
                                    </div>
                                </div>
                            </template>


                        </div>


                        <!-- Switch "¿No se atiende el servicio?" -->
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-sm font-medium">¿No se atiende el servicio?</span>
                            <label class="w-12 h-6 relative">
                                <input type="checkbox" x-model="condiciones.noAtiende"
                                    class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                    id="noAtiendeSwitch" />
                                <span
                                    class="outline_checkbox bg-icon border-2 border-[#ebedf2] dark:border-white-dark block h-full rounded-full
                                    before:absolute before:left-1 before:bg-[#ebedf2] dark:before:bg-white-dark before:bottom-1 before:w-4 before:h-4
                                    before:rounded-full before:bg-[url(/assets/images/close.svg)] before:bg-no-repeat before:bg-center
                                    peer-checked:before:left-7 peer-checked:before:bg-[url(/assets/images/checked.svg)]
                                    peer-checked:border-primary peer-checked:before:bg-primary before:transition-all before:duration-300"></span>
                            </label>
                        </div>

                        <!-- Campo de motivo cuando "No se atiende" está activado -->
                        <div x-show="condiciones.noAtiende" class="space-y-3 mt-2">
                            <div>
                                <label class="block text-sm font-medium">Motivo</label>
                                <textarea x-model="condiciones.motivoNoAtiende" class="form-textarea w-full" rows="3"></textarea>
                            </div>

                            <!-- Campo para cargar la imagen -->
                            <!-- Campo para cargar la imagen -->
                            <div class="space-y-4 mt-4">
                                <label class="block text-lg font-semibold text-gray-700">Selecciona una Imagen</label>
                                <input type="file" x-ref="imagen"
                                    @change="condiciones.imagen = $refs.imagen.files[0]"
                                    class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full">
                            </div>

                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end items-center mt-4">
                            <button type="button" class="btn btn-outline-danger" @click="openCondiciones = false">
                                Cancelar
                            </button>
                            <button type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                @click="guardarCondiciones(condiciones, {{ $ticketId }},  visitaId)">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal para visualizar la imagen -->
<div id="imageModal"
    class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto flex items-center justify-center">
    <div class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg bg-white dark:bg-[#121c2c] shadow-lg">
        <!-- Encabezado -->
        <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
            <div class="font-bold text-lg">
                <span id="modalTitle"></span>
            </div>
            <button type="button" class="text-white-dark hover:text-dark" id="closeModal">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <!-- Imagen -->
        <div class="p-5 flex justify-center">
            <img id="modalImage" src="" alt="Imagen de la visita" class="max-w-full rounded-lg shadow-md" />
        </div>
    </div>
</div>





<!-- Contenedor donde se agregarán las visitas -->
<div id="cordinacionContainer" class="mt-5 flex flex-col space-y-4"></div>



<!-- Modal de Detalles -->
<div id="modalDetallesVisita" class="modal hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-80">
    <div class="modal-content bg-white rounded-lg shadow-lg w-full sm:w-3/4 md:w-1/3 p-4 overflow-auto">
        <!-- Cabecera del Modal -->
        <div class="modal-header flex justify-between items-center mb-4 border-b pb-2">
            <h2 id="detalleNombre" class="text-xl font-semibold text-gray-800"></h2>
            <button id="closeModalButton" class="text-gray-500 hover:text-gray-700 text-xl">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Cuerpo del Modal -->
        <div class="modal-body space-y-4">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <h3 class="font-semibold text-sm text-gray-700 mb-1">Fecha Inicio:</h3>
                    <input type="datetime-local" id="detalleFechaInicioHora" class="form-input w-full px-3 py-2 border rounded-lg text-gray-600">
                </div>
                <div>
                    <h3 class="font-semibold text-sm text-gray-700 mb-1">Fecha Fin:</h3>
                    <input type="datetime-local" id="detalleFechaFinalHora" class="form-input w-full px-3 py-2 border rounded-lg text-gray-600">
                </div>
                <div>
                    <h3 class="font-semibold text-sm text-gray-700 mb-1">Técnico:</h3>
                    <!-- Cambiar de un input a un select -->
                    <select id="detalleUsuario" class="form-input w-full px-3 py-2 border rounded-lg text-gray-600">
                        <!-- Las opciones se agregarán dinámicamente con JS -->
                    </select>
                </div>
            </div>
        </div>

        <!-- Pie del Modal -->
        <div class="modal-footer flex justify-end mt-4 border-t pt-4">
            <button id="closeModalButtonFooter" class="btn btn-primary bg-blue-600 text-white hover:bg-blue-700 py-2 px-4 rounded-lg focus:outline-none transition-all duration-200">Cerrar</button>
            <button id="actualizarButton" class="btn btn-success bg-green-600 text-white hover:bg-green-700 py-2 px-4 rounded-lg focus:outline-none transition-all duration-200">Actualizar</button>
        </div>
    </div>
</div>




<!-- MODAL PARA CREAR VISITA USANDO ALPINE.JS -->
<div x-data="{
    open: false, 
    encargadoTipo: '', 
    necesitaApoyo: false, 
    imagePreview: null,

    // Función para previsualizar la imagen antes de subirla
    previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = () => this.imagePreview = reader.result;
            reader.readAsDataURL(file);
        }
    }
}"
    class="mb-5" @toggle-modal.window="open = !open">

    <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
        <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
            <div x-show="open" x-transition.duration.300
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-3xl my-8 animate__animated animate__zoomInUp">
                <!-- Header del Modal -->
                <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                    <h5 class="font-bold text-lg"> Nueva Coordinación Soporte</h5>
                    <button type="button" class="text-white-dark hover:text-dark" @click="open = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="w-6 h-6">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="modal-scroll">
                    <!-- Formulario -->
                    <form class="p-5 space-y-4" enctype="multipart/form-data">
                        <!-- Nombre de la visita -->
                        <div class="flex space-x-2">
                            <div class="w-1/2">
                                <label class="block text-sm font-medium">Nombre de la Visita</label>
                                <input id="nombreVisitaInput" type="text" class="form-input w-full bg-gray-200"
                                    readonly>
                            </div>
                            <!-- Fecha -->
                            <div class="w-1/2">
                                <label class="block text-sm font-medium">Fecha</label>
                                <!-- Tipo text para que flatpickr lo maneje -->
                                <input id="fechaVisitaInput" type="text" class="form-input w-full"
                                    placeholder="Elige una fecha" required>
                            </div>
                        </div>
                        <!-- Rango de Hora -->
                        <div class="w-full">
                            <label class="block text-sm font-medium mb-1">Rango de atención</label>
                            <div class="flex space-x-2">
                                <input id="horaInicioInput" type="text" name="Hora Inicio" class="form-input w-1/2"
                                    placeholder="Elige la hora de Inicio" required>
                                <input id="horaFinInput" type="text" name="Hora Fin" class="form-input w-1/2"
                                    placeholder="Elige la hora de Fin" required>
                            </div>
                        </div>


                        <!-- ¿Es Recojo? -->
                        <div id="esRecojoContainer">
                            <label class="block text-sm font-medium mb-2">¿Es Recojo?</label>
                            <label class="w-12 h-6 relative inline-block">
                                <input type="checkbox" id="esRecojo" name="esRecojo" value="1"
                                    class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer" />
                                <span
                                    class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300">
                                </span>
                            </label>
                        </div>


                        <!-- Encargado -->
                        <div>
                            <label for="encargado" class="block text-sm font-medium">Encargado</label>
                            <select id="encargado" name="encargado" class="select2 w-full" style="display: none"
                                @change="encargadoTipo = $event.target.options[$event.target.selectedIndex].dataset.tipo">
                                <option value="" disabled selected>Seleccionar Encargado</option>
                                <!-- Aquí se itera sobre los usuarios -->
                                @foreach ($encargado as $encargados)
                                <option value="{{ $encargados->idUsuario }}"
                                    data-tipo="{{ $encargados->idTipoUsuario }}">
                                    {{ $encargados->Nombre }} -
                                    @if ($encargados->idTipoUsuario == 1)
                                    TÉCNICO

                                    @endif
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Mostrar checkbox "¿Necesita Apoyo?" solo si el encargado es Técnico -->
                        <div x-show="encargadoTipo == 1" class="mt-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="necesitaApoyo" name="necesita_apoyo"
                                    class="form-checkbox" x-model="necesitaApoyo">
                                <span class="ml-2 text-sm font-medium">¿Necesita Apoyo?</span>
                            </label>
                        </div>

                        <!-- Mostrar select de técnicos de apoyo solo si el checkbox está marcado -->
                        <div x-show="necesitaApoyo" class="mt-3">
                            <label for="idTecnicoApoyo" class="block text-sm font-medium">Seleccione Técnicos de
                                Apoyo</label>
                            <select id="idTecnicoApoyo" name="idTecnicoApoyo[]" multiple class="select2"
                                style="display: none;" placeholder="Seleccionar Técnicos de Apoyo">
                                <!-- Aquí iteramos sobre los técnicos -->
                                @foreach ($tecnicos_apoyo as $tecnico)
                                <option value="{{ $tecnico->idUsuario }}">{{ $tecnico->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Contenedor para mostrar los técnicos seleccionados -->
                        <div id="selected-items-container" class="mt-3 hidden">
                            <strong>Seleccionados:</strong>
                            <div id="selected-items-list" class="flex flex-wrap gap-2"></div>
                        </div>

                        <!-- Campo para cargar la imagen -->
                        <div class="space-y-4 mt-2">
                            <label class="block text-sm font-medium">Subir Imagen</label>
                            <input type="file" x-ref="imagen" id="imagenVisita" @change="previewImage" name="imagenVisita"
                                class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 
        file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full">

                            <!-- PREVISUALIZACIÓN DE LA IMAGEN -->
                            <div x-show="imagePreview" class="mt-4 flex justify-center">
                                <img :src="imagePreview" alt="Previsualización"
                                    class="max-w-full h-40 rounded-lg shadow-md">
                            </div>
                        </div>


                        <!-- Botones -->
                        <div class="flex justify-end items-center mt-4">
                            <button type="button" class="btn btn-outline-danger"
                                @click="open = false">Cancelar</button>
                            <button type="button" id="guardarBtn" class="btn btn-primary ltr:ml-4 rtl:mr-4">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const modalTitle = document.getElementById('modalTitle'); // ✅ Nuevo elemento para el título
        const closeModal = document.getElementById('closeModal');

        // ✅ Delegación de eventos para los botones "Ver Imagen"
        document.body.addEventListener('click', function(event) {
            const button = event.target.closest('button[id^="viewImageButton-"]');
            if (button) {
                const visitaId = button.dataset.id; // Extraer ID de la visita
                const imageType = button.dataset.imageType; // Tipo de imagen

                // ✅ Obtener la URL correcta según el tipo de imagen
                if (imageType === "inicioServicio") {
                    obtenerImagenInicioServicio(visitaId); // Función para "inicioServicio"
                } else if (imageType === "finalServicio") {
                    obtenerImagenFinalServicio(visitaId); // Función para "finalServicio"
                } else if (imageType === "desplazamiento") {
                    obtenerImagenDesplazamiento(visitaId); // Función para "desplazamiento"
                } else {
                    obtenerImagen(visitaId, imageType); // Función genérica para otras imágenes
                }

                // ✅ Cambiar el título del modal según el tipo de imagen
                modalTitle.textContent = obtenerTituloFase(imageType);

                // ✅ Mostrar el modal
                modal.classList.remove('hidden');
            }
        });

        // ❌ Cerrar modal al hacer clic en el botón de cerrar
        closeModal.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // ❌ Cerrar modal si se hace clic fuera de la imagen/modal
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });

        // Función para obtener la imagen de inicio de servicio desde el servidor
        function obtenerImagenInicioServicio(visitaId) {
            fetch(`/inicio-servicio-imagen/${visitaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.imagen) {
                        modalImage.src = `data:image/jpeg;base64,${data.imagen}`;
                    } else {
                        console.error('Imagen no encontrada.');
                        modalImage.src = ''; // Limpiar la imagen si no se encuentra
                    }
                })
                .catch(error => {
                    console.error('Error al obtener la imagen:', error);
                });
        }

        // Función para obtener la imagen de final de servicio desde el servidor
        function obtenerImagenFinalServicio(visitaId) {
            fetch(`/final-servicio-imagen/${visitaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.imagen) {
                        modalImage.src = `data:image/jpeg;base64,${data.imagen}`;
                    } else {
                        console.error('Imagen no encontrada.');
                        modalImage.src = ''; // Limpiar la imagen si no se encuentra
                    }
                })
                .catch(error => {
                    console.error('Error al obtener la imagen:', error);
                });
        }

        // Función para obtener la imagen de desplazamiento desde el servidor
        function obtenerImagenDesplazamiento(visitaId) {
            fetch(`/desplazamiento-imagen/${visitaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.imagen) {
                        modalImage.src = `data:image/jpeg;base64,${data.imagen}`;
                    } else {
                        console.error('Imagen no encontrada.');
                        modalImage.src = ''; // Limpiar la imagen si no se encuentra
                    }
                })
                .catch(error => {
                    console.error('Error al obtener la imagen:', error);
                });
        }

        // Función para obtener la imagen desde el servidor (caso genérico)
        function obtenerImagen(visitaId, imageType) {
            fetch(`/imagen-apoyo/${visitaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.imagen) {
                        modalImage.src = `data:image/jpeg;base64,${data.imagen}`;
                    } else {
                        console.error('Imagen no encontrada.');
                        modalImage.src = ''; // Limpiar la imagen si no se encuentra
                    }
                })
                .catch(error => {
                    console.error('Error al obtener la imagen:', error);
                });
        }

        // 🔄 Función para obtener el título del modal según la fase
        function obtenerTituloFase(imageType) {
            switch (imageType) {
                case "visita":
                    return "Imagen - Programación";
                case "inicioServicio":
                    return "Imagen - Llegada al Servicio";
                case "finalServicio":
                    return "Imagen - Final de Servicio"; // Título para la fase finalServicio
                case "desplazamiento":
                    return "Imagen - Desplazamiento"; // Título para la fase desplazamiento
                default:
                    return "Imagen de la Visita";
            }
        }
    });





    document.addEventListener("DOMContentLoaded", function() {
        // INICIALIZAR FLATPICKR PARA VISITA
        flatpickr("#fechaVisitaInput", {
            locale: "es",
            dateFormat: "Y-m-d"
        });
        flatpickr("#horaInicioInput", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            locale: "es"
        });
        flatpickr("#horaFinInput", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            locale: "es"
        });
        
        flatpickr("#detalleFechaInicioHora", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
        });

        flatpickr("#detalleFechaFinalHora", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
        });


        // ABRIR MODAL AL CREAR VISITA
        const crearCordinacionBtn = document.getElementById('crearCordinacionBtn');

        crearCordinacionBtn.addEventListener("click", function(event) {

            // Si la validación es correcta, proceder con la llamada AJAX
            const ticketId =
                '{{ $ticket->idTickets }}'; // El ID del ticket, que lo obtienes desde el backend

            console.log("ID del ticket:", ticketId);

            // Realizar consulta AJAX para obtener el número de visitas existentes para ese ticket
            $.ajax({
                url: `/obtener-numero-visitas/${ticketId}`, // Endpoint que te dará el número de visitas actuales para ese ticket
                type: 'GET',
                success: function(response) {
                    // Supongamos que la respuesta es el número de visitas asociadas al ticket
                    let numeroVisitas = response
                        .numeroVisitas; // Esto lo deberías ajustar según lo que devuelvas desde el backend

                    // El siguiente ID de visita sería el número de visitas + 1
                    let siguienteIdVisita = numeroVisitas + 1;

                    // Usamos el siguiente ID de visita para el nombre de la visita
                    nombreVisitaInput.value = `Visita ${siguienteIdVisita}`;

                    // Limpiar los campos de fecha y hora
                    fechaVisitaInput.value = "";
                    horaInicioInput.value = "";
                    horaFinInput.value = "";

                    // Abrir el modal
                    window.dispatchEvent(new Event('toggle-modal'));

                    console.log("Siguiente ID de visita:", siguienteIdVisita);
                },
                error: function(xhr, status, error) {
                    console.log("Error al obtener el número de visitas para el ticket:",
                        error);
                }
            });
        });


        guardarBtn.addEventListener("click", function(event) {
            // Obtener los valores del formulario
            const nombreVisita = document.getElementById('nombreVisitaInput').value;
            const fechaVisita = document.getElementById('fechaVisitaInput').value;
            const horaInicio = document.getElementById('horaInicioInput').value;
            const horaFin = document.getElementById('horaFinInput').value;
            const encargado = document.getElementById('encargado').value;

            // Enviar 1 si el checkbox está marcado, 0 si no
            const necesitaApoyo = document.getElementById('necesitaApoyo').checked ? 1 : 0;
            const recojo = document.getElementById('esRecojo').checked ? 1 : 0; // Obtener el valor de recojo


            const tecnicosApoyo = Array.from(document.getElementById('idTecnicoApoyo').selectedOptions)
                .map(option => option.value);
            const ticketId = '{{ $ticket->idTickets }}'; // El ID del ticket

            // Obtener la imagen seleccionada
            const imagenVisita = document.getElementById('imagenVisita').files[0]; // Obtener el primer archivo seleccionado

            // Verificar si los campos obligatorios están vacíos
            if (!nombreVisita || !fechaVisita || !horaInicio || !horaFin || !encargado) {
                toastr.error("Por favor, complete todos los campos obligatorios.");
                return; // Detener la ejecución si falta algún campo
            }

            // Validar si "Necesita Apoyo" está marcado y no se han seleccionado técnicos
            if (necesitaApoyo && tecnicosApoyo.length === 0) {
                toastr.error("Por favor, seleccione al menos un técnico de apoyo.");
                return; // Detener la ejecución si no se seleccionaron técnicos
            }

            // Convertir las horas de inicio y fin a formato Date
            const [horaInicioHoras, horaInicioMinutos] = horaInicio.split(':').map(Number);
            const [horaFinHoras, horaFinMinutos] = horaFin.split(':').map(Number);

            // Crear objetos Date para la hora de inicio y hora de fin
            const inicioDate = new Date();
            inicioDate.setHours(horaInicioHoras, horaInicioMinutos, 0);

            const finDate = new Date();
            finDate.setHours(horaFinHoras, horaFinMinutos, 0);

            // Validar si la hora de fin es menor o igual a la hora de inicio
            if (finDate <= inicioDate) {
                toastr.error("La hora de fin no puede ser menor o igual a la hora de inicio.");
                return; // Detener la ejecución si la hora de fin es menor o igual a la hora de inicio
            }

            // Crear un objeto FormData
            const formData = new FormData();

            // Añadir los datos al FormData
            formData.append('nombre', nombreVisita);
            formData.append('fecha_visita', fechaVisita);
            formData.append('hora_inicio', horaInicio);
            formData.append('hora_fin', horaFin);
            formData.append('encargado', encargado);
            formData.append('necesita_apoyo', necesitaApoyo);
            formData.append('tecnicos_apoyo', tecnicosApoyo);
            formData.append('recojo', recojo); // Agregar el valor de recojo


            // Si 'necesita_apoyo' está marcado, agregar técnicos de apoyo al FormData
            if (necesitaApoyo && tecnicosApoyo.length > 0) {
                tecnicosApoyo.forEach((tecnicoId) => {
                    formData.append('tecnicos_apoyo[]', tecnicoId); // Asegúrate de enviar como array
                });
            }
            formData.append('idTickets', ticketId);



            // Si hay una imagen, agregarla directamente al FormData
            if (imagenVisita) {
                formData.append('imagenVisita', imagenVisita); // Agregar el archivo de imagen directamente
            }

            // Realizar la solicitud AJAX
            $.ajax({
                url: '/guardar-visita-soporte',
                method: 'POST',
                data: formData,
                contentType: false, // No enviar un tipo de contenido
                processData: false, // No procesar los datos
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Añadir el token CSRF a los encabezados
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message); // Muestra un mensaje de éxito
                        window.dispatchEvent(new Event('toggle-modal')); // Cerrar el modal
                        location.reload(); // Recargar la página
                    } else {
                        toastr.error(response.message); // Mostrar mensaje de error
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error al guardar visita:", error);
                    toastr.error("Error al guardar la visita.");
                }
            });
        });


    });
</script>


<script>
    var ticketId = {
        {
            $ticketId
        }
    };
</script>
<script src="{{ asset('assets/js/tickets/helpdesk/help.js') }}"></script>