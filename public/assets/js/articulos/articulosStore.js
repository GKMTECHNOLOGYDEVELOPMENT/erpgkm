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
document.addEventListener("DOMContentLoaded", function () {
    const articuloForm = document.getElementById("articuloForm");

    articuloForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Evita el envío tradicional del formulario

        // Crea un objeto FormData con todos los datos del formulario
        const formData = new FormData(articuloForm);

        // Realiza la solicitud para enviar los datos al servidor
        fetch('/articulos/store', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                Accept: 'application/json', // Asegura que Laravel devuelva errores en JSON
            },
            body: formData, // Incluye todos los datos del formulario
        })
            .then((response) => response.json()) // Espera una respuesta en JSON
            .then((data) => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    showMessage('Artículo agregado correctamente.', 'top-end');

                    // Limpia el formulario
                    articuloForm.reset();

                    // Restablecer la previsualización de la imagen
                    const previewImage = document.querySelector('#foto').closest('div').querySelector('img');
                    if (previewImage) {
                        previewImage.src = '/assets/images/file-preview.svg'; // Ruta de la imagen predeterminada
                    }

                    // Si usas Alpine.js para la previsualización
                    if (typeof Alpine !== 'undefined') {
                        Alpine.store('fotoPreview', '/assets/images/file-preview.svg');
                    }

                    // Opcional: Cerrar el modal
                    if (typeof open !== 'undefined') {
                        open = false;
                    }

                    // Actualizar la tabla (si usas Alpine.js)
                    const alpineData = Alpine.store('multipleTable');
                    if (alpineData && alpineData.updateTable) {
                        alpineData.updateTable();
                    }
                    window.location.reload();
                } else {
                    // Mostrar mensaje de error
                    showMessage('Hubo un error al guardar el artículo.', 'top-end', true, '', 3000, 'error');
                }
            })
            .catch((error) => {
                console.error('Error en la solicitud:', error);
                // Mostrar mensaje de error
                showMessage('Ocurrió un error, por favor intenta de nuevo.', 'top-end', true, '', 3000, 'error');
            });
    });
});
