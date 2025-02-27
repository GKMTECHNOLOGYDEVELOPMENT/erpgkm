<span class="text-lg font-semibold mb-4 badge bg-success">Firmas</span>

<!-- Contenedor general -->
<div class="flex flex-col md:flex-row md:justify-center md:space-x-4 space-y-6 md:space-y-0">
    <!-- Firma del Técnico -->
    <div class="w-full md:w-[500px] flex flex-col items-center">
        <p class="mb-2 text-lg font-medium text-center">Firma del Técnico</p>
        <div class="w-full h-[500px] border-2 border-gray-300 rounded-lg relative" style="height: 300px;">
            <img id="firmaTecnicoImg" class="w-full h-full object-contain" src="" alt="Firma del Técnico">
        </div>
        <div class="flex space-x-3 mt-4">
           
        </div>
    </div>

   <!-- Firma del Cliente -->
<div class="w-full md:w-[500px] flex flex-col items-center">
    <p class="mb-2 text-lg font-medium text-center">Firma del Cliente</p>
    <div class="w-full h-[500px] border-2 border-gray-300 rounded-lg relative" style="height: 300px;">
        <img id="firmaClienteImg" class="w-full h-full object-contain" src="" alt="Firma del Cliente">
    </div>
    <!-- Botón de refrescar -->
    <button type="button" class="btn btn-info mt-4" onclick="cargarFirmaCliente(); otraFuncion();">🔄 Refrescar Firma</button>
    </div>

</div>

<input type="hidden" id="ticketId" value="{{ $id }}">

<!-- Botones adicionales -->
<div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <button type="button" class="btn btn-primary w-full" onclick="finalizarServicio()">✅ Finalizar Servicio</button>
    <button type="button" class="btn btn-secondary w-full" onclick="coordinarRecojo()">📅 Coordinar Recojo</button>
    <button type="button" class="btn btn-warning w-full" onclick="fueraDeGarantia()">⚠️ Fuera de Garantía</button>
    <button type="button" class="btn btn-info w-full" onclick="pendienteRepuestos()">⏳ Pendiente por Coordinar Repuestos</button>
</div>

<script>
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
                if (data.firma) {
                    const base64Data = data.firma.replace(/^data:image\/\w+;base64,/, '');
                    console.log("Firma en base64 sin prefijo:", base64Data); // Verificar la cadena base64 sin el prefijo

                    // Verificar si la firma base64 es válida (longitud)
                    if (base64Data.length > 100) {
                        const imgElement = document.getElementById('firmaClienteImg');
                        imgElement.src = `data:image/png;base64,${base64Data}`;
                        console.log("Firma cargada en la imagen.");
                    } else {
                        console.log("La firma Base64 está vacía o es inválida.");
                    }
                } else {
                    console.log("No se encontró la firma.");
                }
            })
            .catch(error => {
                console.error('Error al cargar la firma:', error);
            });
    }

    // Funciones para los botones
    function finalizarServicio() {
        alert("Servicio finalizado correctamente.");
    }

    function coordinarRecojo() {
        alert("Recojo coordinado correctamente.");
    }

    function fueraDeGarantia() {
        alert("El servicio está fuera de garantía.");
    }

    function pendienteRepuestos() {
        alert("Pendiente por coordinar repuestos.");
    }

    // Inicializar las firmas cuando el DOM esté listo
    document.addEventListener("DOMContentLoaded", () => {
        cargarFirmaCliente(); // Cargar la firma del cliente después de inicializar
        // Si necesitas cargar una firma del técnico, puedes llamar a cargarFirmaTecnico(firmaBase64);
    });
</script>




