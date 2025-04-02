<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Botón para abrir el modal de crear visita -->






<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Botón para abrir el modal de crear visita -->

<!-- Técnico, Recojo, Envío en 2 columnas (2 arriba, 1 abajo) -->
<div id="tecnicoContainer" style="display: block;" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
    <form id="retorno" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST">
        <!-- Técnico -->
        <div>
            <label for="idTecnico" class="block text-sm font-medium">Técnico Envío</label>
            <select id="idTecnico" name="idTecnico" class="select2 w-full mb-2" style="display: none">
                <option value="" disabled selected>Seleccionar Técnico</option>
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->idUsuario }}">{{ $usuario->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tipo de Recojo -->
        <div>
            <label for="tipoRecojo" class="block text-sm font-medium">Tipo de Recojo</label>
            <select id="tipoRecojo" name="tipoRecojo" class="select2 w-full mb-2" style="display: none">
                <option value="" disabled selected>Seleccionar Tipo de Recojo</option>
                @foreach ($tiposRecojo as $tipo)
                    <option value="{{ $tipo->idtipoRecojo }}">{{ $tipo->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tipo de Envío (en una fila aparte ocupando 2 columnas) -->
        <div class="md:col-span-2">
            <label for="tipoEnvio" class="block text-sm font-medium">Tipo de Envío</label>
            <select id="tipoEnvio" name="tipoEnvio" class="select2 w-full mb-2" style="display: none">
                <option value="" disabled selected>Seleccionar Tipo de Envío</option>
                @foreach ($tiposEnvio as $tipoEnvio)
                    <option value="{{ $tipoEnvio->idtipoenvio }}">{{ $tipoEnvio->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-2 flex justify-end mt-4">
            <a href="{{ route('ordenes.index') }}" class="btn btn-outline-danger">Cancelar</a>
            <button id="guardarBtnfalla" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
        </div>
    </form>
</div>

<script>
    // Asegúrate que ticketId esté correctamente definido
    var ticketId = {{ $ticketId }};

    $(document).ready(function() {
        // Cambié el selector a #guardarBtnfalla como indicaste
        $('#guardarBtnfalla').on('click', function(e) {
            e.preventDefault(); // Prevenir que se recargue la página

            var formData = {
                idTecnico: $('#idTecnico').val(),
                tipoRecojo: $('#tipoRecojo').val(),
                tipoEnvio: $('#tipoEnvio').val(),
                ticketId: ticketId // Asegúrate que ticketId está definido en tu vista
            };

            // Verificar si algún campo obligatorio está vacío
            for (var key in formData) {
                if (formData[key] === '' || formData[key] === null) {
                    toastr.error('Por favor, complete todos los campos.');
                    return;
                }
            }

            // Obtener el token CSRF
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Enviar la solicitud AJAX
            $.ajax({
                url: '/guardar-datos-envio', // Asegúrate de que esta ruta exista
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Hubo un error al guardar los datos de envío.');
                }
            });
        });
    });
</script>






<script src="{{ asset('assets/js/tickets/helpdesk/help.js') }}"></script>
