<span class="text-sm sm:text-lg font-semibold mb-2 sm:mb-4 badge bg-success"
    style="background-color: {{ $colorEstado }};">Firmas</span>

<!-- Contenedor general -->
<div class="flex flex-col md:flex-row md:justify-center md:space-x-4 space-y-6 md:space-y-0">
    <!-- Firma del Técnico -->
    <div class="w-full md:w-[500px] flex flex-col items-center">
        <p class="mb-2 text-lg font-bold text-center">
            @if ($tipoUsuario == 5)
            FIRMA DEL CHOFER
            @elseif ($tipoUsuario == 1)
            FIRMA DEL TÉCNICO
            @else
            FIRMA
            @endif
        </p>
        <div class="w-full h-[500px] border-2 border-gray-300 rounded-lg relative" style="height: 300px;">
            <img id="firmaTecnicoImg" class="w-full h-full object-contain" src="" alt="Firma del Técnico">
        </div>

        <!-- Mensaje si no hay firma -->
        <p id="noFirmaTecnico" class="text-red-500 mt-4 text-center hidden">No hay firma para esta visita.</p>
        <!-- Botón de refrescar -->
        <button type="button" class="btn btn-info mt-4" onclick="cargarFirmaTecnico();">🔄 Refrescar Firma</button>
    </div>

    <!-- Firma del Cliente -->
    <div class="w-full md:w-[500px] flex flex-col items-center">
        <p class="mb-2 text-lg font-bold text-center">FIRMA DEL CLIENTE</p>
        <div class="w-full h-[500px] border-2 border-gray-300 rounded-lg relative" style="height: 300px;">
            <img id="firmaClienteImg" class="w-full h-full object-contain" src="" alt="Firma del Cliente">
        </div>
        <!-- Mensaje si no hay firma -->
        <p id="noFirmaCliente" class="text-red-500 mt-4 text-center hidden">No hay firma para esta visita o cliente.</p>
        <!-- Botón de refrescar -->
        <button type="button" class="btn btn-info mt-4" onclick="cargarFirmaCliente();">🔄 Refrescar Firma</button>
    </div>

</div>

<input type="hidden" id="ticketId" value="{{ $id }}">

<input type="hidden" id="visitaId" value="{{ $idVisitaSeleccionada }}">

<input type="hidden" id="idvisita" value="{{ $idVisitaSeleccionada }}">



<!-- Verificar si la visita está activa (estadovisita no es 1) -->
@if ($estadovisita != 1)
<div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    @if ($idtipoServicio == 1)
    <!-- Mostrar estos botones solo si idtipoServicio es 1 -->
    <button type="button" class="w-full text-white px-4 py-2 rounded-lg transition-all duration-200"
        style="background-color: #BDB762; border: none; box-shadow: none;" value="7" onclick="finalizarServicio()">
        ✅ Visita finaliza correctamente
    </button>

    <button type="button" class="btn w-full text-black px-4 py-2 rounded-lg shadow-md transition-all duration-200"
        style="background-color: #B5FA37; border: none; box-shadow: none;" value="8" onclick="coordinarRecojo()">
        📅 Pendiente recojo
    </button>

    <button type="button" class="btn w-full text-black px-4 py-2 rounded-lg shadow-md transition-all duration-200"
        style="background-color: #ADADAD; border: none; box-shadow: none;" value="6" onclick="fueraDeGarantia()">
        ⚠️ Fuera de Garantía
    </button>

    <button type="button" class="btn w-full text-black px-4 py-2 rounded-lg shadow-md transition-all duration-200"
        style="background-color: #FFFF00; border: none; box-shadow: none;" value="5" onclick="pendienteRepuestos()">
        ⏳ Pendiente de solicitud de repuesto
    </button>

    @elseif ($idtipoServicio == 3)
    <!-- Mostrar solo este botón si idtipoServicio es 3 -->
    <button type="button" class="w-full text-white px-4 py-2 rounded-lg transition-all duration-200"
        style="background-color:rgb(98, 104, 189); border: none; box-shadow: none;" value="7"
        onclick="solicitudEntrega()">
        ✅ Solicitud de Entrega
    </button>
    @elseif ($idtipoServicio == 4)
        <!-- Mostrar solo este botón si idtipoServicio es 4 -->
    <button type="button" class="w-full text-white px-4 py-2 rounded-lg transition-all duration-200"
        style="background-color: #4CAF50; border: none; box-shadow: none;" value="7"
        onclick="finalizarServicio()">
        ✅ Visita finaliza correctamente
    </button>
    @elseif ($idtipoServicio == 7)
    <!-- Mostrar solo este botón si idtipoServicio es 7 -->
    <button type="button" class="w-full text-white px-4 py-2 rounded-lg transition-all duration-200"
        style="background-color: #FA4DF4; border: none; box-shadow: none;" value="9" onclick="coordinarEntrega()">
        📦 COORDINAR ENTREGA
    </button>
    @endif
</div>
@endif












<script>
    // Función para cargar la firma del técnico
    function cargarFirmaTecnico() {
        const ticketId = document.getElementById('ticketId').value; // Obtener el id del ticket
        console.log("Obteniendo firma del técnico para el ticket ID:", ticketId); // Log para verificar el ticketId

        // Obtener la firma del técnico desde el servidor
        fetch(`/ordenes/smart/${ticketId}/obtener-firma-tecnico`)
            .then(response => {
                console.log("Respuesta recibida:", response); // Log para verificar la respuesta
                return response.json();
            })
            .then(data => {
                console.log("Firma del técnico recibida:", data); // Verificar la respuesta completa
                const noFirmaTecnico = document.getElementById('noFirmaTecnico'); // Elemento para el mensaje

                const firmaTecnicoImg = document.getElementById('firmaTecnicoImg'); // Imagen de la firma

                // Verificar si la firma existe
                if (data.firma) {
                    const base64Data = data.firma.replace(/^data:image\/\w+;base64,/, ''); // Extraer base64

                    // Verificar si la firma base64 es válida
                    if (base64Data.length > 100) {
                        firmaTecnicoImg.src = `data:image/png;base64,${base64Data}`; // Mostrar firma
                        noFirmaTecnico.classList.add('hidden'); // Ocultar mensaje de "No hay firma"

                        console.log("Firma del técnico cargada en la imagen.");
                    } else {
                        console.log("La firma Base64 está vacía o es inválida.");
                        mostrarMensajeSinFirmaTecnico();
                    }
                } else {
                    console.log("No se encontró la firma del técnico.");
                    mostrarMensajeSinFirmaTecnico();
                }
            })
            .catch(error => {
                console.error('Error al cargar la firma del técnico:', error);
                mostrarMensajeSinFirmaTecnico();
            });
    }

    // Mostrar mensaje si no hay firma
    function mostrarMensajeSinFirmaTecnico() {
        const noFirmaTecnico = document.getElementById('noFirmaTecnico');
        const firmaTecnicoImg = document.getElementById('firmaTecnicoImg');
        firmaTecnicoImg.src = ''; // Limpiar imagen de firma
        noFirmaTecnico.classList.remove('hidden'); // Mostrar mensaje de "No hay firma"
    }


    // Función para cargar la firma del cliente
    function cargarFirmaCliente() {
        const ticketId = document.getElementById('ticketId').value; // Obtener el id del ticket
        console.log("Obteniendo firma para el ticket ID:", ticketId); // Log para verificar el ticketId

        // Obtener la firma del cliente desde el servidor
        fetch(`/ordenes/smart/${ticketId}/obtener-firma-cliente`)
            .then(response => {
                console.log("Respuesta recibida:", response); // Log para verificar la respuesta
                return response.json();
            })
            .then(data => {
                console.log("Firma recibida:", data); // Verificar la respuesta completa
                const noFirmaCliente = document.getElementById('noFirmaCliente'); // Elemento para el mensaje
                const firmaClienteImg = document.getElementById('firmaClienteImg'); // Imagen de la firma

                // Verificar si la firma existe
                if (data.firma) {
                    const base64Data = data.firma.replace(/^data:image\/\w+;base64,/, ''); // Extraer base64

                    // Verificar si la firma base64 es válida (longitud)
                    if (base64Data.length > 100) {
                        firmaClienteImg.src = `data:image/png;base64,${base64Data}`; // Mostrar firma
                        noFirmaCliente.classList.add('hidden'); // Ocultar mensaje de "No hay firma"
                        console.log("Firma cargada en la imagen.");
                    } else {
                        console.log("La firma Base64 está vacía o es inválida.");
                        mostrarMensajeSinFirma();
                    }
                } else {
                    console.log("No se encontró la firma.");
                    mostrarMensajeSinFirma();
                }
            })
            .catch(error => {
                console.error('Error al cargar la firma:', error);
                mostrarMensajeSinFirma();
            });
    }


    function solicitudEntrega() {
        const ticketId = document.getElementById('ticketId').value; // Obtener el id del ticket
        const visitaId = document.getElementById('visitaId')
            .value; // Obtener el id de la visita (puedes pasarlo como un input oculto o extraerlo de otra parte)

        console.log("Enviando solicitud de entrega para el ticket ID:", ticketId, "y visita ID:", visitaId);

        // Hacer la solicitud AJAX para guardar la solicitud de entrega
        fetch('/solicitud-entrega', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    idTickets: ticketId, // Enviar el id del ticket
                    idVisitas: visitaId // Enviar el id de la visita
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // console.log(data.message); // Mostrar el mensaje de éxito
                    toastr.success('Solicitud de entrega guardada correctamente.');
                    // Si necesitas hacer algo más (como recargar la página o actualizar la vista), puedes hacerlo aquí
                } else {
                    toastr.error(data.message || 'Error al guardar la solicitud de entrega.');
                }
            })
            .catch(error => {
                console.error('Error al enviar la solicitud de entrega:', error);
                toastr.error('Hubo un error al enviar la solicitud de entrega.');
            });
    }


    // Mostrar mensaje si no hay firma
    function mostrarMensajeSinFirma() {
        const noFirmaCliente = document.getElementById('noFirmaCliente');
        const firmaClienteImg = document.getElementById('firmaClienteImg');
        firmaClienteImg.src = ''; // Limpiar imagen de firma
        noFirmaCliente.classList.remove('hidden'); // Mostrar mensaje de "No hay firma"
    }

    // Función para actualizar el estado
    function actualizarEstado(estado) {
        const ticketId = document.getElementById('ticketId').value; // Obtener el ID del ticket
        console.log("Actualizando estado para el ticket ID:", ticketId, "Estado:", estado);
        const idVisita = document.getElementById('idvisita').value; // Obtener el valor del idvisita
        // console.log('visita:', IdVisita);

        fetch(`/tickets/${ticketId}/actualizar-estado`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    estado: estado,
                    idVisita: idVisita // Enviar el idVisita                    // Enviar el estado a actualizar
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success(data.message); // Mostrar mensaje de éxito
                    location.reload();
                } else {
                    toastr.error(data.message); // Mostrar mensaje de error
                }
            })
            .catch(error => {
                console.error('Error al actualizar estado:', error);
                toastr.error('Hubo un error al actualizar el estado.');
            });
    }

    // Funciones para los botones
    function finalizarServicio() {
        actualizarEstado(7); // Estado "10" para finalizar servicio
    }

    function coordinarRecojo() {
        actualizarEstado(8); // Estado "11" para coordinar recojo
    }

    function fueraDeGarantia() {
        actualizarEstado(6); // Estado "12" para fuera de garantía
    }

    function pendienteRepuestos() {
        actualizarEstado(5); // Estado "13" para pendiente de repuestos
    }

    function coordinarEntrega() {
        actualizarEstado(18)
    }

    // Inicializar las firmas cuando el DOM esté listo
    document.addEventListener("DOMContentLoaded", () => {
        cargarFirmaCliente(); // Cargar la firma del cliente después de inicializar
        cargarFirmaTecnico();
        // Si necesitas cargar una firma del técnico, puedes llamar a cargarFirmaTecnico(firmaBase64);
    });
</script>