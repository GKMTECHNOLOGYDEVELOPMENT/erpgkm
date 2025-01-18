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

// Script AJAX para el formulario de proveedor
document.getElementById('proveedorForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío del formulario tradicional

    let formData = new FormData(this); // Obtiene todos los datos del formulario, incluidos archivos si los hay

    // Mostrar en consola los datos antes de enviarlos (esto es solo para depuración)
    console.log("Formulario enviado:", this);
    console.log("Datos del formulario:", Array.from(formData.entries()));

    // Hacer la solicitud AJAX
    fetch(Laravel.routeProveedorStore, {  // Usar la variable global para la ruta
        method: "POST", // Asegúrate de usar el método POST
        headers: {
            'X-CSRF-TOKEN': Laravel.csrfToken, // Usando la variable global para el token CSRF
        },
        body: formData, // Enviar los datos del formulario (incluso archivos si los hay)
    })
    .then(response => {
        console.log("Respuesta del servidor:", response);
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json(); // Intentar convertir la respuesta en JSON
    })
    .then(data => {
        console.log("Datos recibidos del servidor:", data);

        if (data && data.success) { // Asegurarte de que `data` y `data.success` existen
            showMessage('Proveedor agregado correctamente.', 'top-end');
            document.getElementById('proveedorForm').reset(); // Resetea el formulario

            // Actualizar la tabla de proveedores (si se usa Alpine.js)
            let alpineData = Alpine.store('multipleTable');
            if (alpineData && alpineData.updateTable) {
                alpineData.updateTable(); // Actualizar la tabla en Alpine.js
            }
        } else {
            showMessage('Hubo un error al guardar el proveedor.', 'top-end');
        }
    })
    .catch(error => {
        console.error("Error en la solicitud:", error);
        showMessage('Ocurrió un error, por favor intenta de nuevo.', 'top-end');
    });
});
