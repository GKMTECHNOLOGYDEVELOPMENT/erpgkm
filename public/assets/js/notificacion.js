document.addEventListener("DOMContentLoaded", function () {
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

    // Mostrar mensaje de éxito o error si hay algún mensaje en la sesión
    if (window.sessionMessages.success) {
        showMessage(window.sessionMessages.success, 'top-end', true, '', 3000, 'success');
    } else if (window.sessionMessages.error) {
        showMessage(window.sessionMessages.error, 'top-end', true, '', 3000, 'error');
    }
});
