<!-- Botón para abrir el modal de crear visita -->
<span class="text-lg font-semibold mb-4 badge bg-success">Coordinación</span>
<div class="flex gap-2 justify-center">
    <button id="crearVisitaBtn" class="px-4 py-2 btn btn-success text-white rounded-lg shadow-md flex items-center">
        📅 Coordinación
    </button>
    <!-- <button id="crearRecojoBtn" class="px-4 py-2 btn btn-warning text-white rounded-lg shadow-md flex items-center">
        🚛 Recojo
    </button> -->
</div>



<!-- Contenedor donde se agregarán las visitas -->
<div id="visitasContainer" class="mt-5 flex flex-col space-y-4"></div>

<!-- MODAL PARA CREAR VISITA USANDO ALPINE.JS -->
<div x-data="{ open: false }" class="mb-5" @toggle-modal.window="open = !open">
    <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
        <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
            <div x-show="open" x-transition.duration.300
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-3xl my-8 animate__animated animate__zoomInUp">
                <!-- Header del Modal -->
                <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                    <h5 class="font-bold text-lg">Crear Nueva Visita</h5>
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
                    <form class="p-5 space-y-4">
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
                                <input id="horaInicioInput" type="text" class="form-input w-1/2"
                                    placeholder="Elige la hora de Inicio" required>
                                <input id="horaFinInput" type="text" class="form-input w-1/2"
                                    placeholder="Elige la hora de Fin" required>
                            </div>
                        </div>

                      <!-- Técnico -->
                    <div>
                        <label for="tecnico" class="block text-sm font-medium">Técnico</label>
                        <select id="tecnico" name="tecnico" class="select2 w-full" style="display: none">
                            <option value="" disabled selected>Seleccionar Técnico</option>
                            <!-- Aquí se itera sobre los usuarios -->
                            @foreach ($tecnico as $tecnicos)
                                <option value="{{ $tecnicos->idUsuario }}">{{ $tecnicos->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                        <!-- Checkbox Necesita Apoyo -->
                        <div class="mt-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="necesitaApoyo" name="necesita_apoyo" class="form-checkbox" value="false">
                                <span class="ml-2 text-sm font-medium">¿Necesita Apoyo?</span>
                            </label>
                        </div>
                       <!-- Select Múltiple para Técnicos de Apoyo (Inicialmente Oculto) -->
                        <div id="apoyoSelectContainer" class="mt-3 hidden">
                            <label for="idTecnicoApoyo" class="block text-sm font-medium">Seleccione Técnicos de Apoyo</label>
                            <select id="idTecnicoApoyo" name="idTecnicoApoyo[]" multiple placeholder="Seleccionar Técnicos de Apoyo" style="display:none">
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
                        <!-- Botones -->
                        <div class="flex justify-end items-center mt-4">
                            <button type="button" class="btn btn-outline-danger"
                                @click="open = false">Cancelar</button>
                            <button type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                onclick="guardarVisita()">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- MODAL PARA SUBIR IMAGEN USANDO ALPINE.JS -->
<div x-data="{
    openImagen: false,
    imagenUrl: '',
    imagenActual: '/assets/images/file-preview.svg',
    visitaId: null,
    imagenGuardada: null
}" class="mb-5"
    @toggle-modal-imagen.window="openImagen = !openImagen; 
                             visitaId = $event.detail.visitaId; 
                             imagenUrl = visitasData[visitaId]?.imagen || ''">
    <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="openImagen && '!block'">
        <div class="flex items-start justify-center min-h-screen px-4" @click.self="openImagen = false">
            <div x-show="openImagen" x-transition.duration.300
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 animate__animated animate__zoomInUp">
                <!-- Header del Modal -->
                <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                    <h5 class="font-bold text-lg">Subir Imagen</h5>
                    <button type="button" class="text-white-dark hover:text-dark" @click="openImagen = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="w-6 h-6">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="modal-scroll p-5 space-y-4">
                    <!-- Formulario -->
                    <form>
                        <!-- Input para subir imagen -->
                        <div>
                            <label class="block text-sm font-medium">Foto</label>
                            <input type="file" accept="image/*"
                                class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full"
                                @change="imagenUrl = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : imagenActual; 
                                     imagenGuardada = $event.target.files[0]">
                        </div>
                        <!-- Previsualización de la imagen -->
                        <div class="flex justify-center">
                            <template x-if="imagenUrl">
                                <img :src="imagenUrl" alt="Previsualización" class="w-40 h-40 object-cover">
                            </template>
                            <template x-if="!imagenUrl">
                                <img :src="imagenActual" alt="Imagen predeterminada"
                                    class="w-50 h-40 object-cover">
                            </template>
                        </div>
                        <!-- Botones -->
                        <div class="flex justify-end items-center mt-4">
                            <button type="button" class="btn btn-outline-danger"
                                @click="openImagen = false">Cancelar</button>
                            <button type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                @click="guardarImagen(visitaId, imagenGuardada)">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- MODAL PARA MOSTRAR DETALLES DE VISITA (usando Alpine.js) -->
<div x-data="{ openDetalle: false, detalles: '' }" class="mb-5"
    @toggle-modal-detalle.window="openDetalle = !openDetalle; detalles = $event.detail.detalles">
    <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="openDetalle && '!block'">
        <div class="flex items-start justify-center min-h-screen px-4" @click.self="openDetalle = false">
            <div x-show="openDetalle" x-transition.duration.300
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 bg-white dark:bg-gray-800 animate__animated animate__zoomInUp">
                <!-- Header del Modal -->
                <div class="flex bg-gray-100 dark:bg-gray-700 items-center justify-between px-5 py-3">
                    <h5 class="font-bold text-lg">Detalles de Visita</h5>
                    <button type="button" class="text-gray-700 dark:text-gray-300 hover:text-gray-900"
                        @click="openDetalle = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="w-6 h-6">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <div class="text-sm text-gray-700 dark:text-gray-300" x-html="detalles"></div>
                    <div class="flex justify-end mt-4">
                        <button type="button" class="btn btn-primary" @click="openDetalle = false">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Condiciones de Inicio de Servicio -->
<div x-data="{
    openCondiciones: false,
    condiciones: {
        esTitular: true,
        noAtiende: false,
        titularNoEsTitular: { nombre: '', dni: '', telefono: '' },
        motivoNoAtiende: ''
    }
}" class="mb-5" @toggle-modal-condiciones.window="openCondiciones = !openCondiciones">
    <div class="fixed inset-0 bg-black/60 z-[999] hidden overflow-y-auto" :class="openCondiciones && '!block'">
        <div class="flex items-start justify-center min-h-screen px-4" @click.self="openCondiciones = false">
            <div x-show="openCondiciones" x-transition.duration.300
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
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end items-center mt-4">
                            <button type="button" class="btn btn-outline-danger" @click="openCondiciones = false">
                                Cancelar
                            </button>
                            <button type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                @click="guardarCondiciones(visitaId)">
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

        // INICIALIZAR FLATPICKR PARA RECOJO
        flatpickr("#fechaRecojoInput", {
            locale: "es",
            dateFormat: "Y-m-d"
        });
        flatpickr("#horaInicioRecojoInput", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            locale: "es"
        });
        flatpickr("#horaFinRecojoInput", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            locale: "es"
        });

        let visitasContainer = document.getElementById("visitasContainer");
        let crearVisitaBtn = document.getElementById("crearVisitaBtn");
        let crearRecojoBtn = document.getElementById("crearRecojoBtn");
        let nombreVisitaInput = document.getElementById("nombreVisitaInput");
        let nombreRecojoInput = document.getElementById("nombreRecojoInput");
        let fechaVisitaInput = document.getElementById("fechaVisitaInput");
        let horaInicioInput = document.getElementById("horaInicioInput");
        let horaFinInput = document.getElementById("horaFinInput");
        let fechaRecojoInput = document.getElementById("fechaRecojoInput");
        let horaInicioRecojoInput = document.getElementById("horaInicioRecojoInput");
        let horaFinRecojoInput = document.getElementById("horaFinRecojoInput");
        let visitaCount = 0;
        let recojoCount = 0;

        // Almacenar datos de las visitas y recojo (incluyendo la imagen, si se sube)
        let visitasData = {};

        // Estados disponibles para visitas y recojo
        const estados = [{
                nombre: "Fecha de Programación",
                requiereUbicacion: true
            },
            {
                nombre: "Técnico en desplazamiento",
                requiereUbicacion: true
            },
            {
                nombre: "Llegada a servicio",
                requiereUbicacion: true,
                requiereImagen: true
            },
            {
                nombre: "Inicio de servicio",
                requiereUbicacion: true
            }
        ];

        function formatDate(fecha) {
            const año = fecha.getFullYear();
            const mes = (fecha.getMonth() + 1).toString().padStart(2, "0");
            const dia = fecha.getDate().toString().padStart(2, "0");
            let horas = fecha.getHours();
            const minutos = fecha.getMinutes().toString().padStart(2, "0");
            const ampm = horas >= 12 ? "PM" : "AM";
            horas = horas % 12 || 12;
            return `${año}-${mes}-${dia} ${horas}:${minutos} ${ampm}`;
        }

// Objeto para llevar el conteo de visitas por ticket
let visitasContador = {};

// ABRIR MODAL AL CREAR VISITA
crearVisitaBtn.addEventListener("click", function() {
    // Obtener el ID del ticket o el identificador único del ticket
    const ticketId = '{{ $ticket->idTickets }}';  // El ID del ticket, que lo obtienes desde el backend

    console.log("ID del ticket:", ticketId);

    // Primero, realiza una consulta AJAX para obtener el número de visitas existentes para ese ticket
    $.ajax({
        url: `/obtener-numero-visitas/${ticketId}`,  // Endpoint que te dará el número de visitas actuales para ese ticket
        type: 'GET',
        success: function(response) {
            // Supongamos que la respuesta es el número de visitas asociadas al ticket
            let numeroVisitas = response.numeroVisitas; // Esto lo deberías ajustar según lo que devuelvas desde el backend

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
            console.log("Error al obtener el número de visitas para el ticket:", error);
        }
    });
});



        // ABRIR MODAL AL CREAR RECOJO
        crearRecojoBtn.addEventListener("click", function() {
            recojoCount++;
            nombreRecojoInput.value = `Recojo ${recojoCount}`;
            // Limpiar los campos de fecha y hora
            fechaRecojoInput.value = "";
            horaInicioRecojoInput.value = "";
            horaFinRecojoInput.value = "";
            window.dispatchEvent(new Event('toggle-modal-recojo'));
        });

// GUARDAR VISITA
window.guardarVisita = function() {
    const fecha = fechaVisitaInput.value;
    const horaInicio = horaInicioInput.value;
    const horaFin = horaFinInput.value;

    // Validaciones de datos
    if (!fecha || !horaInicio || !horaFin) {
        alert("Por favor, selecciona la fecha y el rango de hora.");
        return;
    }

    const fechaInicio = new Date(fecha + 'T' + horaInicio);
    const fechaFin = new Date(fecha + 'T' + horaFin);

    if (fechaInicio >= fechaFin) {
        alert("La hora de inicio debe ser menor a la hora de fin.");
        return;
    }

    let fechaFormateada = `${formatDate(fechaInicio)} - ${formatDate(fechaFin)}`;
    let visitaId = `visita-${visitaCount}`;

    // Inicializar datos de la visita (la imagen se almacenará si se sube)
    visitasData[visitaId] = {
        imagen: null,
        estados: []
    };

    // Ahora enviamos los datos al backend usando AJAX
    const tecnicoSeleccionado = document.getElementById('tecnico').value;
    const necesitaApoyo = document.getElementById('necesitaApoyo').checked;
    const tecnicoApoyoSeleccionados = necesitaApoyo 
        ? Array.from(document.getElementById('idTecnicoApoyo').selectedOptions).map(option => option.value)
        : [];

    // Enviar solicitud AJAX para guardar los datos
    $.ajax({
        url: '/guardar-visita', // Ruta en Laravel
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',  // CSRF token
            nombre: nombreVisitaInput.value,
            fecha_programada: fechaVisitaInput.value,
            hora_inicio: horaInicio,
            hora_fin: horaFin,
            tecnico_id: tecnicoSeleccionado,
            necesitaApoyo: necesitaApoyo,
            tecnico_apoyo: tecnicoApoyoSeleccionados,
            ticket_id: '{{ $ticket->idTickets }}', // ID del ticket (o el que tengas disponible)
        },
        success: function(response) {
            alert('Visita guardada exitosamente');
            console.log('Respuesta del servidor:', response);

            // Después de guardar la visita, obtener todas las visitas nuevamente
            obtenerVisitas('{{ $ticket->idTickets }}');
        },
        error: function(xhr, status, error) {
            alert('Hubo un error al guardar la visita');
            console.log('Error al guardar visita:', error);
        }
    });
};
// Obtener las visitas cuando la página se carga
window.onload = function() {
    obtenerVisitas('{{ $ticket->idTickets }}');
};

// Función para obtener las visitas desde el backend
function obtenerVisitas(ticketId) {
    $.ajax({
        url: `/obtener-visitas/${ticketId}`,
        type: 'GET',
        success: function(response) {
            // Limpiar el contenedor de visitas antes de agregar las nuevas
            visitasContainer.innerHTML = '';

            response.forEach(visita => {
                let visitaCard = document.createElement("div");
                visitaCard.classList.add("p-4", "shadow-lg", "rounded-lg", "relative");
                visitaCard.id = `visita-${visita.idVisitas}`;
                const formatDate = (fecha) => {
  const date = new Date(fecha);
  
  // Obtener los componentes en UTC (sin conversión a zona horaria local)
  const year = date.getUTCFullYear();
  const month = String(date.getUTCMonth() + 1).padStart(2, '0'); // Los meses en JavaScript empiezan desde 0
  const day = String(date.getUTCDate()).padStart(2, '0');
  const hour = String(date.getUTCHours()).padStart(2, '0');
  const minute = String(date.getUTCMinutes()).padStart(2, '0');
  const second = String(date.getUTCSeconds()).padStart(2, '0');
  
  // Devolver la fecha en el formato 'DD/MM/YYYY HH:mm:ss'
  return `${day}/${month}/${year} ${hour}:${minute}:${second}`;
};

// Asegurarse de que las fechas estén en formato adecuado para el navegador
let fechaFormateada = `${formatDate(visita.fecha_inicio)} - ${formatDate(visita.fecha_final)}`;
console.log(fechaFormateada);




                visitaCard.innerHTML = `
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-lg font-semibold">${visita.nombre}</h5>
                        <button class="detalles-btn btn btn-info" data-visita="${visita.idVisitas}">Detalles de Visita</button>
                    </div>
                    <div id="estadoContainer-${visita.idVisitas}" class="flex flex-col space-y-2">
                        <div class="flex flex-row items-center p-3 rounded-lg estado-row" style="background-color: #eaf1ff;">
                            <span class="text-sm font-medium w-1/4 text-center">Fecha de Programación </span>
                            <span class="hora text-sm w-1/4 text-center">${fechaFormateada}</span>
                            <span class="ubicacion text-sm w-1/4 text-center hidden">Aqui se obtiene la ubicacion de la aplicacion</span>
                            <div class="flex flex-row items-center space-x-1 w-1/4">
                                <span class="estado-btn badge bg-success cursor-pointer" data-estado="0" data-visita="${visita.idVisitas}">
                                    ✔ 
                                </span>
                            </div>
                        </div>
                    </div>
                `;

                visitasContainer.appendChild(visitaCard); // Añadir la tarjeta de visita al contenedor
            });
        },
        error: function(xhr, status, error) {
            console.log('Error al obtener las visitas:', error);
        }
    });
}




        
        // GUARDAR RECOJO
        window.guardarRecojo = function() {
            const fecha = fechaRecojoInput.value;
            const horaInicio = horaInicioRecojoInput.value;
            const horaFin = horaFinRecojoInput.value;

            if (!fecha || !horaInicio || !horaFin) {
                alert("Por favor, selecciona la fecha y el rango de hora.");
                return;
            }

            const fechaInicio = new Date(fecha + 'T' + horaInicio);
            const fechaFin = new Date(fecha + 'T' + horaFin);

            if (fechaInicio >= fechaFin) {
                alert("La hora de inicio debe ser menor a la hora de fin.");
                return;
            }

            let fechaFormateada = `${formatDate(fechaInicio)} - ${formatDate(fechaFin)}`;
            let recojoId = `recojo-${recojoCount}`;

            // Inicializar datos del recojo
            visitasData[recojoId] = {
                imagen: null,
                estados: []
            };

            let recojoCard = document.createElement("div");
            recojoCard.classList.add("p-4", "shadow-lg", "rounded-lg", "relative");
            recojoCard.id = recojoId;

            // Aplicar el color de fondo correspondiente a "Fecha de Programación"
            let fechaProgramacionColor = "#eaf1ff"; // Color de fondo para "Fecha de Programación"

            // La tarjeta muestra una fila alineada con 4 columnas:
            recojoCard.innerHTML = `
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-lg font-semibold">${nombreRecojoInput.value}</h5>
                <button class="detalles-btn btn btn-info" data-visita="${recojoId}">Detalles de Recojo</button>
            </div>
            <div id="estadoContainer-${recojoId}" class="flex flex-col space-y-2">
                <!-- Primer estado: Fecha de Programación -->
                <div class="flex flex-row items-center p-3 rounded-lg estado-row" style="background-color: ${fechaProgramacionColor};">
                    <span class="text-sm font-medium w-1/4 text-center">Fecha de Programación</span>
                    <span class="hora text-sm w-1/4 text-center">${fechaFormateada}</span>
                    <span class="ubicacion text-sm w-1/4 text-center hidden">Sucursal Lima Centro</span>
                    <div class="flex flex-row items-center space-x-1 w-1/4">
                        <span class="estado-btn badge bg-success cursor-pointer" 
                            data-estado="0" data-visita="${recojoId}">
                            ✔
                        </span>
                    </div>
                </div>
            </div>
        `;

            visitasContainer.appendChild(recojoCard);
            window.dispatchEvent(new Event('toggle-modal-recojo'));
        };

        // Función para agregar un nuevo estado (cada estado en una sola fila)
        function agregarEstado(visitaId, estadoIndex) {
            if (estadoIndex >= estados.length) return;

            let estado = estados[estadoIndex];
            let estadoContainer = document.getElementById(`estadoContainer-${visitaId}`);
            const estadoColores = [
                '#eaf1ff', // Fecha de Programación (Primary-Light)
                "#fff9ed", // Técnico en Desplazamiento (Info-Light)
                "#ddf5f0", // Llegada a Servicio (Warning-Light)
                "#fbe5e6" // Inicio de Servicio (Success-Light)
            ];

            let estadoDiv = document.createElement("div");
            estadoDiv.classList.add("flex", "flex-row", "items-center", "p-3", "rounded-lg", 'estado-row');
            estadoDiv.style.backgroundColor = estadoColores[estadoIndex];

            let html = `
        <span class="text-sm font-medium w-1/4 text-center">${estado.nombre}</span>
        <span class="hora text-sm w-1/4 text-center hidden"></span>
        <span class="ubicacion text-sm w-1/4 text-center hidden">Tecnico en desplazamiento</span>
        <div class="flex flex-row items-center space-x-1 w-1/4">`;

            if (estado.requiereImagen) {
                html += `
            <span class="btn-modal badge bg-primary cursor-pointer" data-visita="${visitaId}" onclick="abrirModalImagen('${visitaId}')">
                📷
            </span>
            <span class="estado-btn badge bg-success cursor-pointer" data-estado="${estadoIndex}" data-visita="${visitaId}">
                ✔
            </span>`;
            } else if (estado.nombre === "Inicio de servicio") {
                html += `
            <span class="estado-btn badge bg-success cursor-pointer" data-estado="${estadoIndex}" data-visita="${visitaId}" onclick="abrirModalCondiciones('${visitaId}')">
                ✔
            </span>`;
            } else {
                html += `
            <span class="estado-btn badge bg-success cursor-pointer" data-estado="${estadoIndex}" data-visita="${visitaId}">
                ✔
            </span>`;
            }

            html += `</div>`;
            estadoDiv.innerHTML = html;
            estadoContainer.appendChild(estadoDiv);
        }

        // ABRIR MODAL DE IMAGEN
        window.abrirModalImagen = function(visitaId) {
            window.dispatchEvent(new CustomEvent('toggle-modal-imagen', {
                detail: {
                    visitaId
                }
            }));
        };

        // GUARDAR IMAGEN (la imagen se almacena en visitasData)
        window.guardarImagen = function(visitaId, imagen) {
            if (!visitaId || !imagen) {
                alert("Por favor, selecciona una imagen.");
                return;
            }
            visitasData[visitaId].imagen = URL.createObjectURL(imagen);
            window.dispatchEvent(new Event('toggle-modal-imagen'));
        };

        // INICIALIZAR CAMPO TÉCNICO Y SOPORTE
        let selectTecnicoApoyo = document.getElementById("idTecnicoApoyo");
        let checkboxApoyo = document.getElementById("necesitaApoyo");
        let selectContainer = document.getElementById("apoyoSelectContainer");
        let selectedItemsContainer = document.getElementById("selected-items-container");
        let selectedItemsList = document.getElementById("selected-items-list");

        // Inicializar NiceSelect2 en el select múltiple de técnicos de apoyo
        NiceSelect.bind(selectTecnicoApoyo, {
            searchable: true
        });

        checkboxApoyo.addEventListener("change", function() {
            if (this.checked) {
                selectContainer.classList.remove("hidden");
                selectedItemsContainer.classList.remove("hidden");
            } else {
                selectContainer.classList.add("hidden");
                selectedItemsContainer.classList.add("hidden");
                selectedItemsList.innerHTML = "";
                selectTecnicoApoyo.value = "";
                NiceSelect.sync(selectTecnicoApoyo);
            }
        });

        selectTecnicoApoyo.addEventListener("change", function() {
            selectedItemsList.innerHTML = "";
            let selectedOptions = Array.from(selectTecnicoApoyo.selectedOptions);
            selectedOptions.forEach(option => {
                let item = document.createElement("span");
                item.classList.add("badge", "bg-primary", "px-3", "py-1", "text-white",
                    "rounded-lg", "text-sm", "font-medium");
                item.textContent = option.text;
                selectedItemsList.appendChild(item);
            });
        });

        // AVANZAR ESTADOS UNO A UNO
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("estado-btn")) {
                let btn = event.target;
                let estadoIndex = parseInt(btn.dataset.estado);
                let visitaId = btn.dataset.visita;

                // Si el estado actual requiere imagen (por ejemplo, "Llegada a servicio")
                if (estados[estadoIndex].requiereImagen) {
                    if (!visitasData[visitaId].imagen) {
                        alert("Debe subir una imagen para 'Llegada a servicio'.");
                        window.abrirModalImagen(visitaId);
                        return;
                    }
                }
                let containerDiv = btn.closest(".estado-row");
                let horaSpan = containerDiv.querySelector(".hora");
                let ubicacionSpan = containerDiv.querySelector(".ubicacion");

                // Si NO es el estado 0, actualizamos la hora con la fecha actual;
                // en estado 0 se conserva el valor establecido al guardar la visita.
                if (estadoIndex !== 0) {
                    let fechaActual = new Date();
                    let fechaFormateada = formatDate(fechaActual);
                    horaSpan.textContent = fechaFormateada;
                }
                horaSpan.classList.remove("hidden");
                ubicacionSpan.classList.remove("hidden");

                containerDiv.classList.add("bg-green-200", "border-green-500");
                btn.disabled = true;

                // Agregar el siguiente estado
                agregarEstado(visitaId, estadoIndex + 1);
            }
        });

        // BOTÓN DETALLES DE VISITA: mostrar información de la visita
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("detalles-btn")) {
                let btn = event.target;
                let visitaId = btn.dataset.visita;
                let datos = visitasData[visitaId];
                // Construir los detalles
                let detalles = `
            <strong>Nombre:</strong> ${datos.nombre}<br>
            <strong>Fecha Programada:</strong> ${datos.rango}<br>
            <strong>Técnico:</strong> ${datos.tecnico ? datos.tecnico.nombre : "No seleccionado"}<br>
            <strong>Técnicos de Apoyo:</strong> ${datos.apoyo && datos.apoyo.length ? datos.apoyo.join(", ") : "Ninguno"}<br>
            <strong>Condiciones:</strong><br>
            - Mayor de edad: ${datos.condiciones?.mayorEdad ? 'Sí' : 'No'}<br>
            - Familiar atendió: ${datos.condiciones?.familiarAtendio ? 'Sí' : 'No'}<br>
            - Otra condición: ${datos.condiciones?.otraCondicion ? 'Sí' : 'No'}
        `;
                // Abrir modal de detalles
                window.dispatchEvent(new CustomEvent('toggle-modal-detalle', {
                    detail: {
                        detalles
                    }
                }));
            }
        });

        // ABRIR MODAL DE CONDICIONES
        window.abrirModalCondiciones = function(visitaId) {
            window.dispatchEvent(new CustomEvent('toggle-modal-condiciones', {
                detail: {
                    visitaId
                }
            }));
        };

        // GUARDAR CONDICIONES
        window.guardarCondiciones = function(visitaId) {
            let condiciones = Alpine.$data(document.querySelector('[x-data]')).condiciones;

            // Si "No se atiende" está activo, se valida solo el campo de motivo.
            if (condiciones.noAtiende) {
                if (!condiciones.motivoNoAtiende) {
                    alert("Por favor, ingrese el motivo por el cual no se atiende el servicio.");
                    return;
                }
            } else if (!condiciones.esTitular) { // Si no es titular, validar campos correspondientes.
                if (!condiciones.titularNoEsTitular.nombre || !condiciones.titularNoEsTitular.dni || !
                    condiciones.titularNoEsTitular.telefono) {
                    alert("Por favor, complete todos los campos para 'No es el titular'.");
                    return;
                }
            }

            // Guardar condiciones en visitasData (se asume que esta variable existe)
            visitasData[visitaId].condiciones = condiciones;
            window.dispatchEvent(new Event('toggle-modal-condiciones'));

            // Marcar estado como completado (lógica original)
            let estadoBtn = document.querySelector(
                `.estado-btn[data-visita="${visitaId}"][data-estado="3"]`);
            if (estadoBtn) {
                estadoBtn.disabled = true;
                let containerDiv = estadoBtn.closest(".estado-row");
                let horaSpan = containerDiv.querySelector(".hora");
                let fechaActual = new Date();
                let fechaFormateada = formatDate(fechaActual);
                horaSpan.textContent = fechaFormateada;
                horaSpan.classList.remove("hidden");
                containerDiv.classList.add("bg-green-200", "border-green-500");
            }
        };
    });
</script>
