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

// Script AJAX para el formulario de proveedor
document.getElementById('proveedorForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Evita el envío tradicional del formulario

    let formData = new FormData(this); // Obtiene los datos del formulario, incluidos archivos si los hay

    // Mostrar en consola los datos para depuración
    console.log('Formulario enviado:', this);
    console.log('Datos del formulario:', Array.from(formData.entries()));

    // Hacer la solicitud AJAX directamente a la ruta
    fetch('/proveedores/store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            Accept: 'application/json', // Asegura que Laravel devuelva errores en JSON
        },
        body: new FormData(document.getElementById('proveedorForm')),
    })
        .then((response) => {
            console.log('Respuesta del servidor:', response);

            if (!response.ok) {
                if (response.status === 422) {
                    return response.json().then((errors) => {
                        console.error('Errores de validación:', errors);
                        let messages = Object.values(errors.errors).flat().join('\n');
                        showMessage(messages, 'top-end');
                    });
                }
                throw new Error(`Error en la respuesta del servidor: ${response.statusText}`);
            }

            return response.json();
        })
        .then((data) => {
            console.log('Datos recibidos del servidor:', data);

            if (data && data.success) {
                showMessage('Proveedor agregado correctamente.', 'top-end');
                document.getElementById('proveedorForm').reset();

                let alpineData = Alpine.store('multipleTable');
                if (alpineData && alpineData.updateTable) {
                    alpineData.updateTable();
                }

                window.location.reload();
            } else {
                showMessage('Hubo un error al guardar el proveedor.', 'top-end');
            }
        })
        .catch((error) => {
            console.error('Error en la solicitud:', error);
            showMessage('Ocurrió un error, por favor intenta de nuevo.', 'top-end');
        });
});
