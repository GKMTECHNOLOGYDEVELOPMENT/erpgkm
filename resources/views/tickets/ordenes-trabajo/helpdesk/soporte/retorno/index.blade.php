<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Botón para abrir el modal de crear visita -->






<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Botón para abrir el modal de crear visita -->

<div id="tecnicoContainer" style="display: block;" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
    <form id="retorno" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST">
        <!-- Técnico -->
        <div>
            <label for="idTecnico" class="block text-sm font-medium">Técnico Envío</label>
            <select id="idTecnico" name="idTecnico" >
                <!-- <option value="" disabled selected>Cargando técnicos...</option> -->
            </select>
        </div>

        <!-- Tipo de Recojo -->
        <div>
            <label for="tipoRecojo" class="block text-sm font-medium">Tipo de Recojo</label>
            <select id="tipoRecojo" name="tipoRecojo" >
                <!-- <option value="" disabled selected>Cargando tipos de recojos...</option> -->
            </select>
        </div>

        <!-- Tipo de Envío -->
        <div class="md:col-span-2">
            <label for="tipoEnvio" class="block text-sm font-medium">Tipo de Envío</label>
            <select id="tipoEnvio" name="tipoEnvio" >
            </select>
        </div>

        <!-- Agencia -->
        <div class="md:col-span-2">
            <label for="agencia" class="block text-sm font-medium">Agencia</label>
            <input type="text" id="agencia" name="agencia" class="form-input w-full" readonly>
        </div>

        <div class="md:col-span-2 flex justify-end mt-4">
            <a href="{{ route('ordenes.index') }}" class="btn btn-outline-danger">Cancelar</a>
            <button id="guardarBtnfalla" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
        </div>
    </form>
</div>
<script>
$(document).ready(function() {
    const ticketId = {{ $ticketId }};
    let datosEnvio = null;

    // Función para cargar los datos de envío
    async function cargarDatosEnvio() {
        try {
            const response = await fetch(`/api/datos-envio/${ticketId}?tipo=2`);
            if (!response.ok) throw new Error('Error al cargar datos');
            
            datosEnvio = await response.json();
            
            if (datosEnvio) {
                console.log('Datos de envío cargados:', datosEnvio);
                // Actualizar campos con los datos recibidos
                if (datosEnvio.idUsuario) $('#idTecnico').val(datosEnvio.idUsuario);
                if (datosEnvio.tipoRecojo) $('#tipoRecojo').val(datosEnvio.tipoRecojo);
                if (datosEnvio.tipoEnvio) $('#tipoEnvio').val(datosEnvio.tipoEnvio);
                if (datosEnvio.agencia) $('#agencia').val(datosEnvio.agencia);
            }
        } catch (error) {
            console.error('Error al cargar datos de envío:', error);
        }
    }

    // Función para cargar opciones de select
    async function cargarOpcionesSelect(url, selectId, valueField, textField) {
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Error al cargar opciones');
            
            const data = await response.json();
            const select = $(`#${selectId}`);
            
            // Limpiar y agregar opción por defecto
            select.empty().append('<option value="" disabled selected>Seleccionar...</option>');
            
            // Agregar opciones
            data.forEach(item => {
                select.append(new Option(item[textField], item[valueField]));
            });
            
            console.log(`Opciones cargadas para ${selectId}:`, data);
        } catch (error) {
            console.error(`Error al cargar opciones para ${selectId}:`, error);
            $(`#${selectId}`).empty().append('<option value="" disabled selected>Error al cargar opciones</option>');
        }
    }

    // Cargar todos los datos al iniciar
    async function inicializar() {
        // Cargar opciones en paralelo
        await Promise.all([
            cargarOpcionesSelect('/api/usuarios-tecnicos', 'idTecnico', 'idUsuario', 'Nombre'),
            cargarOpcionesSelect('/api/tipos-recojo', 'tipoRecojo', 'idtipoRecojo', 'nombre'),
            cargarOpcionesSelect('/api/tipos-envio', 'tipoEnvio', 'idtipoenvio', 'nombre'),
            cargarDatosEnvio()
        ]);
        
        // Inicializar select2 después de cargar las opciones
        $('.select2').select2();
    }

    // Manejar el guardado de datos
    $('#guardarBtnfalla').on('click', async function(e) {
        e.preventDefault();
        
        const formData = {
            idTickets: ticketId,
            idUsuario: $('#idTecnico').val(),
            tipoRecojo: $('#tipoRecojo').val(),
            tipoEnvio: $('#tipoEnvio').val(),
            tipo: 2,
            agencia: $('#agencia').val() || 'Por definir'
        };

        // Validación
        if (!formData.idUsuario || !formData.tipoRecojo || !formData.tipoEnvio) {
            toastr.error('Por favor, complete todos los campos obligatorios.');
            return;
        }

        try {
            const response = await fetch('/api/guardar-datos-envio', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();
            
            if (response.ok && result.success) {
                toastr.success(result.message);
                // Actualizar datos locales
                datosEnvio = result.data || formData;
            } else {
                toastr.error(result.message || 'Error al guardar los datos');
            }
        } catch (error) {
            console.error('Error al guardar:', error);
            toastr.error('Error de conexión al guardar los datos');
        }
    });

    // Inicializar la página
    inicializar();
});
</script>






<!-- <script src="{{ asset('assets/js/tickets/helpdesk/help.js') }}"></script> -->
