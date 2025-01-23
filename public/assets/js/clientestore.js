// Función para mostrar la alerta con SweetAlert
function showMessage(
    msg = 'Example notification text.',
    position = 'top-end',
    showCloseButton = true,
    closeButtonHtml = '',
    duration = 3000,
    type = 'success',
) {
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

// Script AJAX para el formulario de cliente
document.getElementById('clienteForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Evita el envío del formulario tradicional

    let formData = new FormData(this); // Obtiene todos los datos del formulario, incluidos archivos si los hay

    // Depuración: Mostrar los datos enviados en la consola
    console.log("Datos enviados en el formulario:");
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

    // Hacer la solicitud AJAX
    fetch('/cliente/store', {
        method: 'POST', // Método POST
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            Accept: 'application/json', // Asegura que Laravel devuelva errores en JSON
        },
        body: formData, // Envío de datos en formato multipart
    })
        .then((response) => {
            // Mostrar respuesta del servidor para depuración
            console.log("Respuesta del servidor:", response);

            if (!response.ok) {
                // Si el estado no es "ok", arrojar error
                return response.json().then((data) => {
                    throw data; // Arrojar los datos para capturarlos en el catch
                });
            }

            return response.json(); // Intentar convertir la respuesta en JSON
        })
        .then((data) => {
            // Mostrar datos devueltos por el servidor para depuración
            console.log("Datos recibidos del servidor:", data);

            if (data && data.success) { // Verificar si la respuesta indica éxito
                // Mostrar mensaje de éxito
                showMessage('Cliente agregado correctamente.', 'top-end');

                // Limpiar el formulario
                document.getElementById('clienteForm').reset();

                // Si estás usando Alpine.js para manejar tablas o vistas
                let alpineData = Alpine.store('multipleTable');
                if (alpineData && alpineData.updateTable) {
                    alpineData.updateTable(); // Actualizar la tabla
                }
            } else {
                // Mostrar mensaje de error general
                showMessage('Hubo un error al guardar el cliente.', 'top-end', true, '', 3000, 'error');
            }
        })
        .catch((error) => {
            // Si es un error de validación, mostrar los errores específicos
            if (error.errors) {
                console.error("Errores de validación recibidos del servidor:", error.errors);
                Object.keys(error.errors).forEach((key) => {
                    const mensajes = error.errors[key].join(', ');
                    showMessage(`${mensajes}`, 'top-end', true, '', 3000, 'error');
                });
            } else {
                // Mostrar mensaje de error general si no hay errores específicos
                console.error("Error en la solicitud AJAX:", error);
                showMessage('Ocurrió un error inesperado, por favor intenta de nuevo.', 'top-end', true, '', 3000, 'error');
            }
        });
});
