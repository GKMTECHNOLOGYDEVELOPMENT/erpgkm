<!-- CDN Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<style>
    /* Asegúrate de tener las reglas CSS como las has colocado antes */
    /* ... */
</style>

<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md mt-6">
    <span class="text-sm sm:text-lg font-semibold badge bg-success">Artículos</span>
<!-- Selector e Input -->
<div class="mt-4 mb-6 flex items-center gap-4">
    <!-- Select para elegir artículo -->
    <select id="articuloSelect" class="w-56 herramienta-select">
        <option selected disabled value="">Seleccione un artículo</option>
        @foreach ($articulos as $articulo)
            <option value="{{ $articulo->idArticulos }}">
                {{ strtoupper($articulo->nombre) }}
            </option>
        @endforeach
    </select>

    <!-- Input para la cantidad -->
    <input type="number" id="articuloCantidad" class="form-input w-16 text-center" value="1" min="1" />

    <!-- Botón para agregar el artículo -->
    <button id="agregarArticulo" class="btn btn-primary">Agregar</button>
</div>



    <!-- Tabla de resumen -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-center">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="px-3 py-2 text-center">Artículo</th>
                    <th class="px-3 py-2 text-center">Cantidad</th>
                    <th class="px-3 py-2 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaResumenHerramientas"
                class="divide-y divide-gray-200 dark:divide-gray-600 text-center text-gray-800 dark:text-gray-100">
                <!-- Dinámico -->
            </tbody>
        </table>
    </div>

    <div class="flex justify-end mt-4">
        <button type="submit" class="btn btn-primary guardarHerramientas">Guardar</button>
    </div>
</div>

<input type="hidden" id="ticketId" value="{{ $id }}">

<input type="hidden" id="visitaId" value="{{ $idVisitaSeleccionada }}">



<script>
 document.addEventListener("DOMContentLoaded", function() {
    const articuloSelect = document.getElementById("articuloSelect");
    const articuloCantidad = document.getElementById("articuloCantidad");
    const tablaBody = document.getElementById("tablaResumenHerramientas");
    let articulosSeleccionados = [];

    // Obtener ticketId y visitaId una sola vez
    const ticketId = document.getElementById("ticketId").value;
    const visitaId = document.getElementById("visitaId").value;

    // Función para renderizar la tabla de artículos
    function renderTabla() {
        tablaBody.innerHTML = "";

        articulosSeleccionados.forEach((art, index) => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td class="px-3 py-1">${art.nombre}</td>
                <td class="px-3 py-1 text-center">
                    <input type="number" min="1" value="${art.cantidad}" 
                        class="form-input w-16 text-center actualizarCantidad" data-index="${index}" />
                </td>
                <td class="px-3 py-1 text-center">
                <button class="btn btn-sm btn-danger eliminarArticulo" data-id="${art.idSuministros}">Eliminar</button>
          
                </td>
            `;
            tablaBody.appendChild(tr);
        });
    }

    function obtenerSuministros() {
    fetch(`/get-suministros/${ticketId}/${visitaId}`)
        .then(response => response.json())
        .then(data => {
            // Asignar los suministros obtenidos a la tabla, incluyendo el id
            articulosSeleccionados = data.map(item => ({
                idSuministros: item.idSuministros, // Añadimos el idSuministros aquí
                id: item.idArticulos,       // Asegúrate de incluir el id aquí
                nombre: item.nombre,
                cantidad: item.cantidad
            }));
            renderTabla();
        })
        .catch(error => {
            console.error('Error al obtener los suministros:', error);
        });
}


    // Llamada inicial para obtener los suministros
    obtenerSuministros();


  function agregarOActualizarArticulo() {
    const id = articuloSelect.value;
    const nombre = articuloSelect.options[articuloSelect.selectedIndex]?.text;
    const cantidad = parseInt(articuloCantidad.value);

    // Asegúrate de que el id sea válido
    if (!id || id === "" || cantidad < 1) {
        alert("Por favor, selecciona un artículo válido y una cantidad.");
        return;
    }

    // Verificar si el artículo que se está agregando ya existe en los artículos seleccionados
    const indexExistente = articulosSeleccionados.findIndex(a => a.id === id);

    // Si el artículo ya existe, actualizamos la cantidad
    if (indexExistente !== -1) {
        articulosSeleccionados[indexExistente].cantidad = cantidad;
    } else {
        // Verificar si el nuevo artículo que estamos agregando ya existe entre los artículos seleccionados
        // Esto solo verifica el nuevo artículo que estamos agregando, no los existentes
        const articuloRepetido = articulosSeleccionados.find(art => art.id === id);

        if (articuloRepetido) {
            alert('Este artículo ya ha sido agregado anteriormente.');
            return; // Si el artículo ya está en la lista, no lo agregamos
        }

        // Si el artículo no existe, lo agregamos con su id y el idSuministros vacío
        articulosSeleccionados.push({
            id: id,
            nombre: nombre,
            cantidad: cantidad,
            idSuministros: null  // Inicializamos como null, lo actualizaremos cuando se obtenga del servidor
        });
    }

    console.log("Artículos seleccionados:", articulosSeleccionados); // Verifica lo que se está agregando

    renderTabla();

    // Reset
    articuloSelect.value = "";
    $(articuloSelect).val(null).trigger("change");
    articuloCantidad.value = 1;
}






    // Agregar artículo al hacer clic en el botón "Agregar"
    document.getElementById("agregarArticulo").addEventListener("click", function() {
        agregarOActualizarArticulo();
    });


    

    tablaBody.addEventListener("click", function(e) {
    if (e.target.classList.contains("eliminarArticulo")) {
        // Agregar log para verificar si se encuentra el botón
        console.log('Botón de eliminar clickeado');
        
        const idSuministro = e.target.dataset.id; // Obtener el idSuministro desde el botón

        // Agregar log para verificar si el idSuministro es correcto
        console.log('idSuministro:', idSuministro);

        if (!idSuministro) {
            console.error('idSuministro no encontrado');
            return;
        }

        fetch(`/eliminar-suministro/${idSuministro}`, { // Usamos idSuministro en la URL
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Token de seguridad CSRF si estás usando Laravel
            }
        })
        .then(response => response.json())
        .then(data => {
            // Agregar log para ver la respuesta
            console.log('Respuesta del servidor:', data);

            if (data.message === 'Artículo eliminado correctamente.') {
    // Eliminar el artículo de la tabla de suministros en el frontend
    articulosSeleccionados = articulosSeleccionados.filter(art => art.idSuministros !== idSuministro);
    renderTabla();  // Volver a renderizar la tabla
} else {
    alert('Error al eliminar el suministro');
}
        })
        .catch(error => {
            console.error('Error al hacer la solicitud:', error);
            alert('Hubo un error al eliminar el suministro');
        });
    }
});




   // Actualizar cantidad al modificar el input
tablaBody.addEventListener("input", function(e) {
    if (e.target.classList.contains("actualizarCantidad")) {
        const index = e.target.dataset.index;
        const nuevaCantidad = parseInt(e.target.value);

        if (nuevaCantidad > 0) {
            articulosSeleccionados[index].cantidad = nuevaCantidad;

            // Ahora enviamos la actualización al servidor
            const idSuministro = articulosSeleccionados[index].idSuministros;  // ID del suministro
            const url = `/actualizar-suministro/${idSuministro}`;  // URL de la ruta de actualización

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',  // Asegúrate de que el token CSRF esté presente
                },
                body: JSON.stringify({ cantidad: nuevaCantidad })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data);
                if (data.message) {
                    alert(data.message);  // Muestra el mensaje de éxito
                }
            })
            .catch(error => {
                console.error('Error al actualizar cantidad:', error);
                alert('Hubo un error al actualizar la cantidad.');
            });
        }
    }
});


// Guardar suministros al hacer clic en "Guardar"
document.querySelector(".guardarHerramientas").addEventListener("click", function(e) {
    e.preventDefault();

    // Recolectar los artículos seleccionados
    const articulosData = articulosSeleccionados.map(art => ({
        id: art.id,
        cantidad: art.cantidad
    }));

     // Verificar que todos los artículos tengan un id
     const articulosInvalidos = articulosData.filter(articulo => !articulo.id);
    if (articulosInvalidos.length > 0) {
        alert('Algunos artículos no tienen un ID válido.');
        return;
    }


    console.log("Enviando datos:", articulosData); // Muestra los datos que estamos enviando

    fetch('/guardar-suministros', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            articulos: articulosData,
            ticketId: ticketId,  // Ya está declarada arriba
            visitaId: visitaId  // Ya está declarada arriba
        })
    })
    .then(response => {
        console.log("Respuesta recibida del servidor:", response); // Ver la respuesta antes de procesarla
        return response.text(); // Recibe la respuesta como texto primero
    })
    .then(data => {
        console.log("Contenido de la respuesta:", data); // Ver el contenido recibido

        // Si la respuesta es válida JSON, lo convertimos
        try {
            const jsonResponse = JSON.parse(data);
            console.log("Respuesta JSON procesada:", jsonResponse); // Ver la respuesta JSON procesada

            if (jsonResponse.message) {
                alert(jsonResponse.message); // Mostrar mensaje de éxito

                location.reload();

            }
        } catch (error) {
            console.error('Error al parsear JSON:', error);
            alert('Error en la respuesta del servidor.');
        }
    })
    .catch(error => {
        console.error('Error en la solicitud AJAX:', error);
        alert('Hubo un error al guardar los suministros.');
    });
});

});

</script>

