document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('clientGeneralForm');
    const descripcionInput = document.getElementById('descripcion');
    const fileInput = document.getElementById('ctnFile');

    // Base URL for API requests
    const BASE_URL = 'http://192.168.0.204:8000/'; // Ajusta según tu configuración

    // Validaciones
    const validateNombreUnico = async (nombre) => {
        try {
            const response = await fetch(`${BASE_URL}api/check-nombre`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    nombre
                })
            });
            const data = await response.json();
            return data.unique; // true si es único, false si ya existe
        } catch (error) {
            return false;
        }
    };

    const validateDescripcion = (value) => {
        const regex = /^[a-zA-Z0-9\s]+$/; // Sin caracteres especiales
        return value.trim() !== '' && regex.test(value);
    };

    const validateFile = (file) => {
        const allowedExtensions = ['image/png', 'image/jpeg', 'image/webp'];
        return file.size <= 5 * 1024 * 1024 && allowedExtensions.includes(file.type);
    };

    // Escucha de eventos para validaciones en tiempo real
    descripcionInput.addEventListener('input', async () => {
        const nombre = descripcionInput.value;
        if (!validateDescripcion(nombre)) {
            descripcionInput.setCustomValidity(
                'El nombre no debe estar vacío ni tener caracteres especiales.');
        } else if (!(await validateNombreUnico(nombre))) {
            descripcionInput.setCustomValidity('El nombre ya está en uso.');
        } else {
            descripcionInput.setCustomValidity('');
        }
        descripcionInput.reportValidity();
    });

    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (!file) {
            fileInput.setCustomValidity('Debes seleccionar un archivo.');
        } else if (!validateFile(file)) {
            fileInput.setCustomValidity(
                'El archivo debe ser PNG, JPG, WEBP y no superar los 5 MB.');
        } else {
            fileInput.setCustomValidity('');
        }
        fileInput.reportValidity();
    });

    // Validaciones al enviar el formulario
    form.addEventListener('submit', async (event) => {
        const nombre = descripcionInput.value;
        const file = fileInput.files[0];

        // Validar la descripción
        if (!validateDescripcion(nombre)) {
            event.preventDefault();
            return;
        }

        if (!(await validateNombreUnico(nombre))) {
            event.preventDefault();
            return;
        }

        // Validar el archivo de imagen (que sea obligatorio y válido)
        if (!file) {
            fileInput.setCustomValidity('La imagen es obligatoria.');
            event.preventDefault(); // Detener el envío
            return;
        }

        if (!validateFile(file)) {
            fileInput.setCustomValidity(
                'El archivo debe ser PNG, JPG, WEBP y no superar los 5 MB.');
            event.preventDefault();
            return;
        }

        // Si todo es válido, el formulario se enviará
    });
});
