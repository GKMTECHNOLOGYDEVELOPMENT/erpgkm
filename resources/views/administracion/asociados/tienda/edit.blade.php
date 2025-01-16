<x-layout.default>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #map {
            height: 300px; /* Ajusta el tamaño del mapa según tus necesidades */
            width: 100%;
        }
    </style>
    <div class="panel mt-6 p-5 max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-5">Editar Tienda</h2>
    <!-- Formulario -->
    <div class="p-5">
        <form id="tiendaForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST" action="{{ route('tiendas.update', $tienda->idTienda) }}">
            @csrf
            @method('PUT') <!-- Usamos PUT para la actualización -->

            <!-- RUC -->
            <div>
                <label for="ruc" class="block text-sm font-medium">RUC</label>
                <input id="ruc" name="ruc" type="text" class="form-input w-full" placeholder="Ingrese el RUC" value="{{ old('ruc', $tienda->ruc) }}">
            </div>
            <!-- Nombre -->
            <div>
                <label for="nombre" class="block text-sm font-medium">Nombre</label>
                <input id="nombre" type="text" class="form-input w-full" placeholder="Ingrese el nombre" name="nombre" value="{{ old('nombre', $tienda->nombre) }}">
            </div>
            <!-- Dirección -->
            <div>
                <label for="direccion" class="block text-sm font-medium">Dirección</label>
                <input id="direccion" type="text" class="form-input w-full" placeholder="Ingrese la dirección" name="direccion" value="{{ old('direccion', $tienda->direccion) }}">
            </div>
            <!-- Cliente -->
            <div>
                <select id="idCliente" name="idCliente" class="select2 w-full">
                    <option value="" disabled selected>Seleccionar Cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->idCliente }}" {{ old('idCliente', $tienda->idCliente) == $cliente->idCliente ? 'selected' : '' }}>
                            {{ $cliente->nombre }} - {{ $cliente->documento }}
                        </option>
                    @endforeach
                </select>
                @error('idCliente')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <!-- Celular -->
            <div>
                <label for="celular" class="block text-sm font-medium">Celular</label>
                <input id="celular" type="text" class="form-input w-full" placeholder="Ingrese el celular" name="celular" value="{{ old('celular', $tienda->celular) }}">
            </div>
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input id="email" type="email" class="form-input w-full" placeholder="Ingrese el email" name="email" value="{{ old('email', $tienda->email) }}">
            </div>
            <!-- Referencia -->
            <div class="md:col-span-2">
                <label for="referencia" class="block text-sm font-medium">Referencia</label>
                <input id="referencia" type="text" class="form-input w-full" placeholder="Ingrese la referencia" name="referencia" value="{{ old('referencia', $tienda->referencia) }}">
            </div>
            <!-- Latitud -->
            <div>
                <label for="latitud" class="block text-sm font-medium">Latitud</label>
                <input id="latitud" type="text" class="form-input w-full" placeholder="Latitud" name="lat" value="{{ old('lat', $tienda->lat) }}" readonly>
            </div>
            <!-- Longitud -->
            <div>
                <label for="longitud" class="block text-sm font-medium">Longitud</label>
                <input id="longitud" type="text" name="lng" class="form-input w-full" placeholder="Longitud" value="{{ old('lng', $tienda->lng) }}" readonly>
            </div>

<!-- Departamento -->
<div>
    <label for="departamento" class="block text-sm font-medium">Departamento</label>
    <select id="departamento" name="departamento" class="form-input w-full">
        <option value="" disabled selected>Seleccionar Departamento</option>
        @foreach ($departamentos as $departamento)
            <option value="{{ $departamento['id_ubigeo'] }}" {{ old('departamento', $tienda->departamento) == $departamento['id_ubigeo'] ? 'selected' : '' }}>
                {{ $departamento['nombre_ubigeo'] }}
            </option>
        @endforeach
    </select>
</div>

<!-- Provincia -->
<div>
    <label for="provincia" class="block text-sm font-medium">Provincia</label>
    <select id="provincia" name="provincia" class="form-input w-full">
        <option value="" disabled selected>Seleccionar Provincia</option>
        @foreach ($provinciasDelDepartamento as $provincia)
            <option value="{{ $provincia['id_ubigeo'] }}" {{ old('provincia', $tienda->provincia) == $provincia['id_ubigeo'] ? 'selected' : '' }}>
                {{ $provincia['nombre_ubigeo'] }}
            </option>
        @endforeach
    </select>
</div>

<!-- Distrito -->
<div>
    <label for="distrito" class="block text-sm font-medium">Distrito</label>
    <select id="distrito" name="distrito" class="form-input w-full">
        <option value="" disabled selected>Seleccionar Distrito</option>
        @foreach ($distritosDeLaProvincia as $distrito)
            <option value="{{ $distrito['id_ubigeo'] }}" {{ old('distrito', $tienda->distrito) == $distrito['id_ubigeo'] ? 'selected' : '' }}>
                {{ $distrito['nombre_ubigeo'] }}
            </option>
        @endforeach
    </select>
</div>






            <!-- Mapa -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium">Mapa</label>
                <div id="map" class="w-full h-64 rounded border"></div>
            </div>

            <!-- Botones -->
            <div class="md:col-span-2 flex justify-end mt-4">
                <a href="{{ route('administracion.tienda') }}" class="btn btn-outline-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Rellenar provincias y distritos si ya están seleccionados
    var departamentoId = $('#departamento').val();
    if(departamentoId) {
        $.ajax({
            url: '/get-provincia/' + departamentoId,
            method: 'GET',
            success: function(response) {
                // Aquí ya no necesitas acceder a "provincias", ya que la respuesta es un arreglo directamente
                $.each(response, function(key, provincia) {
                    $('#provincia').append('<option value="'+ provincia.id_ubigeo +'">'+ provincia.nombre_ubigeo +'</option>');
                });
                $('#provincia').prop('disabled', false);
                $('#provincia').val('{{ $tienda->provincia }}'); // Pre-seleccionar provincia
            }
        });
    }

    var provinciaId = $('#provincia').val();
    if(provinciaId) {
        $.ajax({
            url: '/get-distrito/' + provinciaId,
            method: 'GET',
            success: function(response) {
                $.each(response, function(key, distrito) {
                    $('#distrito').append('<option value="'+ distrito.id_ubigeo +'">'+ distrito.nombre_ubigeo +'</option>');
                });
                $('#distrito').prop('disabled', false);
                $('#distrito').val('{{ $tienda->distrito }}'); // Pre-seleccionar distrito
            }
        });
    }

    // Cargar provincias cuando se seleccione un departamento
    $('#departamento').on('change', function() {
        var departamentoId = $(this).val();
        if(departamentoId) {
            $.ajax({
                url: '/get-provincia/' + departamentoId,
                method: 'GET',
                success: function(response) {
                    $('#provincia').html('<option value="" disabled selected>Seleccionar Provincia</option>');
                    // Aquí también ya no necesitas acceder a "provincias"
                    $.each(response, function(key, provincia) {
                        $('#provincia').append('<option value="'+ provincia.id_ubigeo +'">'+ provincia.nombre_ubigeo +'</option>');
                    });
                    $('#provincia').prop('disabled', false);
                }
            });
        }
    });

    // Cargar distritos cuando se seleccione una provincia
    $('#provincia').on('change', function() {
        var provinciaId = $(this).val();
        if(provinciaId) {
            $.ajax({
                url: '/get-distrito/' + provinciaId,
                method: 'GET',
                success: function(response) {
                    $('#distrito').html('<option value="" disabled selected>Seleccionar Distrito</option>');
                    $.each(response, function(key, distrito) {
                        $('#distrito').append('<option value="'+ distrito.id_ubigeo +'">'+ distrito.nombre_ubigeo +'</option>');
                    });
                    $('#distrito').prop('disabled', false);
                }
            });
        }
    });
});
</script>

<script>
    // Inicializar Select2
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.select2').forEach(function(select) {
            NiceSelect.bind(select, { searchable: true });
        });

        // Inicializar el mapa
        const map = L.map('map').setView([{{ $tienda->lat }}, {{ $tienda->lng }}], 13); // Coordenadas iniciales de la tienda

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker = L.marker([{{ $tienda->lat }}, {{ $tienda->lng }}]).addTo(map);  // Agregar el marcador en la posición actual

        map.on('click', function(e) {
            const { lat, lng } = e.latlng;

            // Actualizar los inputs de latitud y longitud
            document.getElementById('latitud').value = lat;
            document.getElementById('longitud').value = lng;

            // Agregar marcador al mapa
            marker.setLatLng([lat, lng]);
        });
    });
</script>


    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
</x-layout.default>