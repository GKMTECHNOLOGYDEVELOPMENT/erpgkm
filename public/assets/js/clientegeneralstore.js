// Función para mostrar la alerta con SweetAlert
function showMessage(msg = 'Example notification text.', position = 'top-end', showCloseButton = true,
    closeButtonHtml = '', duration = 3000, type = 'success') {
    const toast = window.Swal.mixin({
        toast: true,
        position: position || 'top-end',
        showConfirmButton: false,
        timer: duration,
        showCloseButton: showCloseButton,
        icon: type === 'success' ? 'success' : 'error', // Cambia el icono según el tipo
        background: type === 'success' ? '#28a745' : '#dc3545', // Verde para éxito, Rojo para error
        iconColor: 'white', // Color del icono
        customClass: {
            title: 'text-white', // Asegura que el texto sea blanco
        },
    });

    toast.fire({
        title: msg,
    });
}

// Aquí va tu código para el formulario, incluyendo el fetch y demás
document.getElementById('clientGeneralForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío del formulario tradicional

    let formData = new FormData(this); // Obtiene todos los datos del formulario, incluida la foto

    fetch(Laravel.routeClientStore, {
            method: "POST", // Asegúrate de usar el método POST
            headers: {
                'X-CSRF-TOKEN': Laravel.csrfToken, // Usa la variable global para el token CSRF
            },
            body: formData, // Envío de datos en formato multipart
        })
        .then(response => response.json()) // Espera una respuesta JSON
        .then(data => {
            if (data.success) {
                // Mostrar la alerta de éxito
                showMessage('Cliente agregado correctamente.', 'top-end');

                // Limpiar los campos del formulario
                document.getElementById('clientGeneralForm').reset();

                // Limpiar la previsualización de la imagen y volver a la imagen por defecto
                if (typeof Alpine !== 'undefined') {
                    // Limpiar el estado de imagenPreview en Alpine.js
                    Alpine.store('imagenPreview', '/assets/images/file-preview.svg'); // Restablecer a la imagen predeterminada
                    Alpine.store('imagenActual', '/assets/images/file-preview.svg'); // Actualizar la imagen actual
                }

                // Cerrar el modal (asumiendo que 'open' está vinculado al estado del modal)
                open = false; // Esto asume que `open` es el controlador del modal en Alpine.js

                // Llamar al método para actualizar la tabla (si usas Alpine.js)
                let alpineData = Alpine.store('multipleTable');
                if (alpineData && alpineData.updateTable) {
                    alpineData.updateTable(); // Llama a `updateTable` de Alpine
                }
            } else {
                // Mostrar alerta de error
                showMessage('Hubo un error al guardar el cliente.', 'top-end');
            }
        })
        .catch(error => {
            // Mostrar alerta de error
            showMessage('Ocurrió un error, por favor intenta de nuevo.', 'top-end');
        });
});
