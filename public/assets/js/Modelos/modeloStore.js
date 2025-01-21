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

document.getElementById('modeloForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Evita el envío del formulario tradicional

    let formData = new FormData(this); // Obtiene todos los datos del formulario

    fetch('/modelos/store', {
        method: 'POST', // Método POST
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            Accept: 'application/json', // Asegura que Laravel devuelva errores en JSON
        },
        body: formData, // Envío de datos en formato multipart
    })
        .then((response) => response.json()) // Espera una respuesta JSON
        .then((data) => {
            if (data && data.success) {
                // Mostrar mensaje de éxito
                showMessage('Modelo agregado correctamente.', 'top-end');

                // Recargar la página después de guardar
                setTimeout(() => {
                    location.reload(); // Recarga la página
                }); 
            } else {
                // Mostrar mensaje de error
                showMessage('Hubo un error al guardar el modelo.', 'top-end', true, '', 3000, 'error');
            }
        })
        .catch((error) => {
            console.error('Error en la solicitud:', error);
            // Mostrar mensaje de error
            showMessage('Ocurrió un error, por favor intenta de nuevo.', 'top-end', true, '', 3000, 'error');
        });
});

