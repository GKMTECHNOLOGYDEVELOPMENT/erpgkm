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

// Aquí va tu código para el formulario, incluyendo el fetch y demás
document.getElementById('marcaForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Evita el envío del formulario tradicional

    let formData = new FormData(this); // Obtiene todos los datos del formulario

    fetch('/marcas/store', {
        method: 'POST', // Método POST
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            Accept: 'application/json', // Asegura que Laravel devuelva errores en JSON
        },
        body: formData, // Envío de datos en formato multipart
    })
        .then((response) => response.json()) // Espera una respuesta JSON
        .then((data) => {
            if (data.success) {
                // Mostrar la alerta de éxito
                showMessage('Marca agregada correctamente.', 'top-end');

                // Limpiar los campos del formulario
                document.getElementById('marcaForm').reset();

                // Cerrar el modal (si es necesario)
                if (typeof open !== 'undefined') {
                    open = false;
                }

                // Actualizar la tabla (si usas Alpine.js)
                let alpineData = Alpine.store('multipleTable');
                if (alpineData && alpineData.updateTable) {
                    alpineData.updateTable();
                }
            } else {
                // Mostrar alerta de error
                showMessage('Hubo un error al guardar la marca.', 'top-end', true, '', 3000, 'error');
            }
        })
        .catch((error) => {
            console.error('Error en la solicitud:', error);
            // Mostrar alerta de error
            showMessage('Ocurrió un error, por favor intenta de nuevo.', 'top-end', true, '', 3000, 'error');
        });
});
    