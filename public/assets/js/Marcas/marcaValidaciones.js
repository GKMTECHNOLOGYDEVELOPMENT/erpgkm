document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('marcaForm');
    const nombreInput = document.getElementById('nombre');

    // Validaciones
    const validateNombreUnico = async (nombre) => {
        try {
            const response = await fetch('/api/marca/check-nombre', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify({
                    nombre,
                }),
            });
            const data = await response.json();
            return data.unique; // true si es único, false si ya existe
        } catch (error) {
            return false;
        }
    };

    const validateNombre = (value) => {
        const regex = /^[a-zA-Z0-9\s]+$/; // Sin caracteres especiales
        return value.trim() !== '' && regex.test(value);
    };

    // Escucha de eventos para validaciones en tiempo real
    nombreInput.addEventListener('input', async () => {
        const nombre = nombreInput.value;
        if (!validateNombre(nombre)) {
            nombreInput.setCustomValidity(
                'El nombre no debe estar vacío ni tener caracteres especiales.',
            );
        } else if (!(await validateNombreUnico(nombre))) {
            nombreInput.setCustomValidity('El nombre ya está en uso.');
        } else {
            nombreInput.setCustomValidity('');
        }
        nombreInput.reportValidity();
    });

    // Validaciones al enviar el formulario
    form.addEventListener('submit', async (event) => {
        const nombre = nombreInput.value;

        // Validar el nombre
        if (!validateNombre(nombre)) {
            event.preventDefault();
            return;
        }

        if (!(await validateNombreUnico(nombre))) {
            event.preventDefault();
            return;
        }

        // Si todo es válido, el formulario se enviará
    });
});
