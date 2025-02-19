<!-- Botón para abrir el modal de crear visita -->
<span class="text-lg font-semibold mb-4 badge bg-success">Coordinación</span>
<div class="flex gap-2 justify-center">
    <button id="crearCordinacionBtn" class="px-4 py-2 btn btn-success text-white rounded-lg shadow-md flex items-center">
        📅 Coordinación
    </button>


</div>

<div id="visitasContainer" class="mt-4">
    <h2 class="text-xl font-semibold mb-4"></h2>
    <div id="visitasList" class="space-y-4"></div>
</div>

<script>
    function formatDate(dateString) {
        const date = new Date(dateString);
        const options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', hour12: true };
        return date.toLocaleString('en-US', options).replace(',', '');
    }

    var ticketId = {{ $ticketId }};

    fetch(`/api/obtenerVisitas/${ticketId}`)
        .then(response => response.json())
        .then(data => {
            const visitasList = document.getElementById('visitasList');
            visitasList.innerHTML = '';

            if (data && data.length > 0) {
                data.forEach(visita => {
                    const fechaInicio = formatDate(visita.fecha_inicio);
                    const fechaFinal = formatDate(visita.fecha_final);

                    const card = document.createElement('div');
                    card.className = 'bg-white border border-gray-200 rounded-lg shadow-sm p-5 max-w-md';
                    card.innerHTML = `
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">${visita.nombre}</h3>
                        <div class="text-gray-600 mb-2">
                            <span class="font-medium">Fecha de Programación</span><br>
                            ${fechaInicio} - ${fechaFinal}
                        </div>
                        <div class="text-gray-500">
                            <span class="font-medium">Detalles de Visita</span>
                        </div>
                    `;
                    visitasList.appendChild(card);
                });

                document.getElementById('visitasContainer').style.display = 'block';
            } else {
                alert("No hay visitas para este ticket.");
            }
        })
        .catch(error => {
            console.error('Error al obtener las visitas:', error);
            alert('Ocurrió un error al obtener las visitas.');
        });
</script>

<!-- Contenedor donde se agregarán las visitas -->
<div id="cordinacionContainer" class="mt-5 flex flex-col space-y-4"></div>

<!-- MODAL PARA CREAR VISITA USANDO ALPINE.JS -->
<div x-data="{ open: false, encargadoTipo: '', necesitaApoyo: false }" class="mb-5" @toggle-modal.window="open = !open">
    <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
        <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
            <div x-show="open" x-transition.duration.300
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-3xl my-8 animate__animated animate__zoomInUp">
                <!-- Header del Modal -->
                <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                    <h5 class="font-bold text-lg"> Nueva Cordinacion</h5>
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

                        <!-- Encargado -->
                        <div>
                            <label for="encargado" class="block text-sm font-medium">Encargado</label>
                            <select id="encargado" name="encargado" class="select2 w-full" style="display: none" @change="encargadoTipo = $event.target.options[$event.target.selectedIndex].dataset.tipo">
                                <option value="" disabled selected>Seleccionar Encargado</option>
                                <!-- Aquí se itera sobre los usuarios -->
                                @foreach ($encargado as $encargados)
                                    <option value="{{ $encargados->idUsuario }}" data-tipo="{{ $encargados->idTipoUsuario }}">
                                        {{ $encargados->Nombre }} - 
                                        @if($encargados->idTipoUsuario == 3)
                                            Técnico
                                        @elseif($encargados->idTipoUsuario == 5)
                                            Chofer
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Mostrar checkbox "¿Necesita Apoyo?" solo si el encargado es Técnico -->
                        <div x-show="encargadoTipo == 3" class="mt-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" id="necesitaApoyo" name="necesita_apoyo" class="form-checkbox" x-model="necesitaApoyo">
                            <span class="ml-2 text-sm font-medium">¿Necesita Apoyo?</span>
                        </label>

                        </div>

                      <!-- Mostrar select de técnicos de apoyo solo si el checkbox está marcado -->
                        <div x-show="necesitaApoyo" class="mt-3">
                            <label for="idTecnicoApoyo" class="block text-sm font-medium">Seleccione Técnicos de Apoyo</label>
                            <select id="idTecnicoApoyo" name="idTecnicoApoyo[]" multiple class="select2" style="display: none;" placeholder="Seleccionar Técnicos de Apoyo">
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
                                <button type="button" id="guardarBtn"  class="btn btn-primary ltr:ml-4 rtl:mr-4">
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


    // ABRIR MODAL AL CREAR VISITA
    const crearCordinacionBtn = document.getElementById('crearCordinacionBtn');

    crearCordinacionBtn.addEventListener("click", function(event) {

        // Si la validación es correcta, proceder con la llamada AJAX
        const ticketId = '{{ $ticket->idTickets }}';  // El ID del ticket, que lo obtienes desde el backend

        console.log("ID del ticket:", ticketId);

        // Realizar consulta AJAX para obtener el número de visitas existentes para ese ticket
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
    guardarBtn.addEventListener("click", function(event) {
    // Obtener los valores del formulario
    const nombreVisita = document.getElementById('nombreVisitaInput').value;
    const fechaVisita = document.getElementById('fechaVisitaInput').value;
    const horaInicio = document.getElementById('horaInicioInput').value;
    const horaFin = document.getElementById('horaFinInput').value;
    const encargado = document.getElementById('encargado').value;

    // Enviar 1 si el checkbox está marcado, 0 si no
    const necesitaApoyo = document.getElementById('necesitaApoyo').checked ? 1 : 0;

    const tecnicosApoyo = Array.from(document.getElementById('idTecnicoApoyo').selectedOptions).map(option => option.value);
    const ticketId = '{{ $ticket->idTickets }}';  // El ID del ticket

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

    // Si la validación es correcta, realizar la llamada AJAX para guardar la visita
    $.ajax({
        url: `/guardar-visita`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',  // Token CSRF
            nombre: nombreVisita,
            fecha_visita: fechaVisita,
            hora_inicio: horaInicio,
            hora_fin: horaFin,
            encargado: encargado,
            necesita_apoyo: necesitaApoyo,  // Enviar 1 o 0
            tecnicos_apoyo: tecnicosApoyo,
            idTickets: ticketId  // El id del ticket
        },
        success: function(response) {
            if (response.success) {
                toastr.success(response.message); // Muestra un mensaje de éxito
                window.dispatchEvent(new Event('toggle-modal')); // Cerrar el modal
            } else {
                // Si no fue exitoso, muestra el mensaje de error
                toastr.error(response.message);  // Muestra el mensaje de error, por ejemplo "El técnico ya tiene una visita asignada en este horario"
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al guardar visita:", error);
            toastr.error("Hubo un error al guardar la visita. Intenta nuevamente.");
        }
    });
});





});

</script>








<script>
document.addEventListener("DOMContentLoaded", function() {
    // Inicializar Select2 para el select de técnicos de apoyo
    $('.select2').select2({
        placeholder: "Seleccionar Técnicos de Apoyo", // Puedes personalizar el texto del placeholder
        allowClear: true // Permite limpiar la selección
    });

    // Mostrar/ocultar el contenedor y agregar/remover badges cuando los técnicos son seleccionados
    $('#idTecnicoApoyo').on('change', function() {
        const selectedTechnicians = $(this).val(); // Obtener los técnicos seleccionados
        const container = $('#selected-items-container');
        const listContainer = $('#selected-items-list');

        // Limpiar el contenedor antes de añadir nuevos badges
        listContainer.empty();

        // Si hay técnicos seleccionados, mostrar el contenedor
        if (selectedTechnicians && selectedTechnicians.length > 0) {
            container.removeClass('hidden'); // Mostrar el contenedor
            selectedTechnicians.forEach(function(technicianId) {
                // Aquí se asume que cada técnico tiene un nombre
                const technicianName = $('#idTecnicoApoyo option[value="' + technicianId + '"]').text(); // Obtener nombre del técnico
                const badge = `<span class="bg-blue-500 text-white px-3 py-1 rounded-full">${technicianName}</span>`;
                listContainer.append(badge);
            });
        } else {
            // Si no hay técnicos seleccionados, ocultar el contenedor
            container.addClass('hidden');
        }
    });
});


</script>
















