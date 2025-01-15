<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #map {
            height: 300px; /* Ajusta el tamaño del mapa según tus necesidades */
            width: 100%;
        }
    </style>
    
    <div class="panel mt-6 p-5 max-w-2xl mx-auto">
        <h2 class="text-xl font-bold mb-5">Agregar Subsidiario</h2>
        <!-- Formulario -->
        <div class="p-5">
            <form id="subsidiariosForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- RUC -->
                <div>
                    <label for="ruc" class="block text-sm font-medium">RUC</label>
                    <input id="ruc" type="text" class="form-input w-full" placeholder="Ingrese el RUC">
                </div>
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                    <input id="nombre" type="text" class="form-input w-full" placeholder="Ingrese el nombre">
                </div>
                <!-- Nombre del Contacto -->
                <div>
                    <label for="nombre_contacto" class="block text-sm font-medium">Nombre del Contacto</label>
                    <input id="nombre_contacto" type="text" class="form-input w-full" placeholder="Ingrese el nombre del contacto">
                </div>
                <!-- Tienda -->
                <div>
                    <select id="idTienda" class="select2 w-full">
                        <option value="" disabled selected>Seleccionar Tienda</option>
                        <option value="1">Tienda 1</option>
                        <option value="2">Tienda 2</option>
                        <option value="3">Tienda 3</option>
                        <option value="4">Tienda 4</option>
                        <option value="5">Tienda 5</option>
                    </select>
                </div>
                <!-- Celular -->
                <div>
                    <label for="celular" class="block text-sm font-medium">Celular</label>
                    <input id="celular" type="text" class="form-input w-full" placeholder="Ingrese el celular">
                </div>
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input id="email" type="email" class="form-input w-full" placeholder="Ingrese el email">
                </div>
                <!-- Dirección -->
                <div class="md:col-span-2">
                    <label for="direccion" class="block text-sm font-medium">Dirección</label>
                    <textarea id="direccion" class="form-input w-full" rows="2" placeholder="Ingrese la dirección"></textarea>
                </div>
                <!-- Referencia -->
                <div class="md:col-span-2">
                    <label for="referencia" class="block text-sm font-medium">Referencia</label>
                    <input id="referencia" type="text" class="form-input w-full" placeholder="Ingrese la referencia">
                </div>
                <!-- Latitud -->
                <div>
                    <label for="latitud" class="block text-sm font-medium">Latitud</label>
                    <input id="latitud" type="text" class="form-input w-full" placeholder="Latitud" readonly>
                </div>
                <!-- Longitud -->
                <div>
                    <label for="longitud" class="block text-sm font-medium">Longitud</label>
                    <input id="longitud" type="text" class="form-input w-full" placeholder="Longitud" readonly>
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

    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
</x-layout.default>