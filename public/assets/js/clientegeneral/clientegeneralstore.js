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
        icon: type === 'success' ? 'success' : 'error',
        background: type === 'success' ? '#28a745' : '#dc3545',
        iconColor: 'white',
        customClass: {
            title: 'text-white',
        },
    });

    toast.fire({
        title: msg,
    });
}
document.getElementById('clientGeneralForm').addEventListener('submit', function (event) {
    event.preventDefault();

    let formData = new FormData(this);

    fetch('/cliente-general/store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            Accept: 'application/json',
        },
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            // Mostrar la alerta de éxito
            showMessage('Cliente agregado correctamente.', 'top-end', true, '', 3000, 'success');

            // Limpiar los campos del formulario
            document.getElementById('clientGeneralForm').reset();

            // Restablecer la previsualización de la imagen
            if (typeof Alpine !== 'undefined') {
                Alpine.store('imagenPreview', '/assets/images/file-preview.svg');
                Alpine.store('imagenActual', '/assets/images/file-preview.svg');
            }

            // Asegurar que la previsualización se actualice en la vista
            const previewImage = document.querySelector('#ctnFile').closest('div').querySelector('img');
            if (previewImage) {
                previewImage.src = '/assets/images/file-preview.svg';
            }

            // Cerrar el modal (si es necesario)
            if (typeof open !== 'undefined') {
                open = false;
            }

            // Redirigir después de un breve tiempo para que se vea el mensaje
            setTimeout(() => {
                window.location.href = `/cliente-general/${data.id}/edit`;
            }, 1500);
            
        } else {
            // Mostrar alerta de error si success es false
            showMessage(data.message || 'Hubo un error al guardar el cliente.', 'top-end', true, '', 3000, 'error');
        }
    })
    .catch((error) => {
        console.error('Error en la solicitud:', error);
        showMessage('Ocurrió un error, por favor intenta de nuevo.', 'top-end', true, '', 3000, 'error');
    });
});