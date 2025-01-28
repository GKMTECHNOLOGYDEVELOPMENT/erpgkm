// Función para mostrar la alerta con SweetAlert
function showMessage(
    msg = 'Example notification text.',
    position = 'top-end',
    showCloseButton = true,
    closeButtonHtml = '',
    duration = 3000,
    type = 'success',
) {
    console.log(`Mostrar mensaje: ${msg}`); // Depuración: Ver qué mensaje se va a mostrar
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
document.getElementById('categoriaForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Evita el envío del formulario tradicional
    console.log("Formulario enviado"); // Depuración: Verificar si el formulario se está enviando

    let formData = new FormData(this); // Obtiene todos los datos del formulario
    console.log("Datos del formulario:", formData); // Depuración: Ver los datos del formulario

    fetch('/categoria/store', {
        method: 'POST', // Método POST
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            Accept: 'application/json', // Asegura que Laravel devuelva errores en JSON
        },
        body: formData, // Envío de datos en formato multipart
    })
    .then((response) => {
        console.log("Respuesta del servidor:", response); // Depuración: Ver la respuesta de la solicitud
        return response.json(); // Espera una respuesta JSON
    })
    .then((data) => {
        console.log("Datos recibidos del servidor:", data); // Depuración: Ver los datos recibidos
        if (data.success) {
            // Mostrar la alerta de éxito
            showMessage('Categoría agregada correctamente.', 'top-end');
            console.log("Categoría agregada con éxito"); // Depuración: Verificar que se ha agregado la categoría correctamente

            // Limpiar los campos del formulario
            document.getElementById('categoriaForm').reset();

            // Cerrar el modal (si es necesario)
            if (typeof open !== 'undefined') {
                open = false;
                console.log("Modal cerrado"); // Depuración: Verificar si el modal se cierra correctamente
            }

            // Actualizar la tabla (si usas Alpine.js)
            let alpineData = Alpine.store('multipleTable');
            if (alpineData && alpineData.updateTable) {
                alpineData.updateTable();
                console.log("Tabla actualizada"); // Depuración: Verificar si la tabla se actualiza
            }
        } else {
            // Mostrar alerta de error
            showMessage('Hubo un error al guardar la categoría.', 'top-end', true, '', 3000, 'error');
            console.log("Error al guardar la categoría"); // Depuración: Verificar que el error está siendo manejado correctamente
        }
    })
    .catch((error) => {
        console.error('Error en la solicitud:', error); // Depuración: Ver el error en la consola
        showMessage('Ocurrió un error, por favor intenta de nuevo.', 'top-end', true, '', 3000, 'error');
    });
});
