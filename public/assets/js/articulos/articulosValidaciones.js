document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('articuloForm');
    const nombreInput = document.getElementById('nombre');
    const codigoInput = document.getElementById('codigo');

    // Validaciones
    const validateNombreUnico = async (nombre) => {
        try {
            const response = await fetch('/api/articulos/check-nombre', {
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

    // const validateCodigoUnico = async (codigo) => {
    //     try {
    //         const response = await fetch('/api/articulos/check-codigo', {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //                 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
    //             },
    //             body: JSON.stringify({
    //                 codigo,
    //             }),
    //         });
    //         const data = await response.json();
    //         return data.unique; // true si es único, false si ya existe
    //     } catch (error) {
    //         return false;
    //     }
    // };

    const validateNombre = (value) => {
        const regex = /^[a-zA-Z0-9\s]+$/; // Sin caracteres especiales
        return value.trim() !== '' && regex.test(value);
    };

    const validateCodigo = (value) => {
        const regex = /^[a-zA-Z0-9-]+$/; // Sin caracteres especiales, permite guiones
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

    // codigoInput.addEventListener('input', async () => {
    //     const codigo = codigoInput.value;
    //     if (!validateCodigo(codigo)) {
    //         codigoInput.setCustomValidity(
    //             'El código no debe estar vacío ni tener caracteres especiales (excepto guiones).',
    //         );
    //     } else if (!(await validateCodigoUnico(codigo))) {
    //         codigoInput.setCustomValidity('El código ya está en uso.');
    //     } else {
    //         codigoInput.setCustomValidity('');
    //     }
    //     codigoInput.reportValidity();
    // });

    // Validaciones al enviar el formulario
    form.addEventListener('submit', async (event) => {
        const nombre = nombreInput.value;
        const codigo = codigoInput.value;

        // Validar el nombre
        if (!validateNombre(nombre) || !(await validateNombreUnico(nombre))) {
            event.preventDefault();
            return;
        }

        // Validar el código
        // if (!validateCodigo(codigo) || !(await validateCodigoUnico(codigo))) {
        //     event.preventDefault();
        //     return;
        // }

        // Si todo es válido, el formulario se enviará
    });
});
