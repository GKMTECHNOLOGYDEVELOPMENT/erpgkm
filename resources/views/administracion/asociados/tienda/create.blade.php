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
        <h2 class="text-xl font-bold mb-5">Agregar Tienda</h2>

        <!-- Mostrar alertas de éxito o error -->
    @if (session('success'))
        <div class="alert alert-success mb-4">
            <strong>Éxito!</strong> {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger mb-4">
            <strong>Error!</strong> {{ session('error') }}
        </div>
    @endif  
        <!-- Formulario -->
        <div class="p-5">
            <form id="tiendaForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST" action="{{ route('tiendas.store') }}">
            @csrf

                <!-- RUC -->
                <div>
                    <label for="ruc" class="block text-sm font-medium">RUC</label>
                    <input id="ruc" name="ruc" type="text" class="form-input w-full" placeholder="Ingrese el RUC">
                </div>
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                    <input id="nombre" type="text" class="form-input w-full" placeholder="Ingrese el nombre" name="nombre">
                </div>
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input id="email" type="email" class="form-input w-full" placeholder="Ingrese el email" name="email">
                </div>
                
             
            <div>
                <!-- <label for="idCliente" class="block text-sm font-medium">Cliente</label> -->
                <select id="idCliente" name="idCliente" class="select2 w-full">
                    <option value="" disabled selected>Seleccionar Cliente</option>
                    <!-- Llenar el select con clientes dinámicamente -->
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->idCliente }}" {{ old('idCliente') == $cliente->idCliente ? 'selected' : '' }}>
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
                    <input id="celular" type="text" class="form-input w-full" placeholder="Ingrese el celular" name="celular">
                </div>
               
                    
                  <!-- departamento -->
                  <div>
                    <label for="departamento" class="block text-sm font-medium">Departamento</label>
                    <select id="departamento" name="departamento" class="form-input w-full">
                        <option value="" disabled selected>Seleccionar Departamento</option>
                        @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento['id_ubigeo'] }}">{{ $departamento['nombre_ubigeo'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Provincia -->
                <div>
                    <label for="provincia" class="block text-sm font-medium">Provincia</label>
                    <select id="provincia" name="provincia" class="form-input w-full" disabled>
                        <option value="" disabled selected>Seleccionar Provincia</option>
                    </select>
                </div>

                <!-- Distrito -->
                <div>
                    <label for="distrito" class="block text-sm font-medium">Distrito</label>
                    <select id="distrito" name="distrito" class="form-input w-full" disabled>
                        <option value="" disabled selected>Seleccionar Distrito</option>
                    </select>
                </div>

                <div>
                    <label for="nombre_contacto" class="block text-sm font-medium">Dirección</label>
                    <input id="nombre_contacto" type="text" class="form-input w-full" placeholder="Ingrese el nombre del contacto" name="direccion">
                </div>
                <!-- Referencia -->
                <div >
                    <label for="referencia" class="block text-sm font-medium">Referencia</label>
                    <input id="referencia" type="text" class="form-input w-full" placeholder="Ingrese la referencia" name="referencia">
                </div>
              

                <!-- Latitud -->
                <div>
                    <label for="latitud" class="block text-sm font-medium">Latitud</label>
                    <input id="latitud" type="text" class="form-input w-full" placeholder="Latitud" name="lat" readonly>
                </div>
                <!-- Longitud -->
                <div>
                    <label for="longitud" class="block text-sm font-medium">Longitud</label>
                    <input id="longitud" type="text" name="lng" class="form-input w-full" placeholder="Longitud" readonly>
                </div>
                <!-- Mapa -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Mapa</label>
                    <div id="map" class="w-full h-64 rounded border"></div>
                </div>
                <!-- Botones -->
                <div class="md:col-span-2 flex justify-end mt-4">
                    <a href="{{ route('administracion.sub-sidiario') }}" class="btn btn-outline-danger">Cancelar</a>
                    <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
    $(document).ready(function() {
    // Cuando se selecciona un departamento, obtener las provincias relacionadas
    $('#departamento').change(function() {
        var departamentoId = $(this).val();

        if (departamentoId) {
            $.get('/ubigeo/provincias/' + departamentoId, function(data) {
                var provinciaSelect = $('#provincia');
                provinciaSelect.empty().prop('disabled', false);
                provinciaSelect.append('<option value="" disabled selected>Seleccionar Provincia</option>');

                data.forEach(function(provincia) {
                    provinciaSelect.append('<option value="' + provincia.id_ubigeo + '">' + provincia.nombre_ubigeo + '</option>');
                });
            });
        } else {
            $('#provincia').empty().prop('disabled', true);
            $('#distrito').empty().prop('disabled', true);
        }
    });

    // Cuando se selecciona una provincia, obtener los distritos relacionados
    $('#provincia').change(function() {
        var provinciaId = $(this).val();

        if (provinciaId) {
            $.get('/ubigeo/distritos/' + provinciaId, function(data) {
                var distritoSelect = $('#distrito');
                distritoSelect.empty().prop('disabled', false);
                distritoSelect.append('<option value="" disabled selected>Seleccionar Distrito</option>');

                data.forEach(function(distrito) {
                    distritoSelect.append('<option value="' + distrito.id_ubigeo + '">' + distrito.nombre_ubigeo + '</option>');
                });
            });
        } else {
            $('#distrito').empty().prop('disabled', true);
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
            const map = L.map('map').setView([-12.0464, -77.0428], 13); // Coordenadas iniciales (Lima, Perú)

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            let marker;

            map.on('click', function(e) {
                const { lat, lng } = e.latlng;

                // Actualizar los inputs de latitud y longitud
                document.getElementById('latitud').value = lat;
                document.getElementById('longitud').value = lng;

                // Agregar marcador al mapa
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }
            });
        });
    </script>
  
  
</x-layout.default>