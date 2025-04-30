document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('modeloForm');
    const nombreInput = document.getElementById('nombre');
    const idMarcaInput = document.getElementById('idMarca');
    const idCategoriaInput = document.getElementById('idCategoria');

    // Validaciones
    const validateNombreUnico = async (nombre, idMarca, idCategoria) => {
        console.log('🔍 Validando nombre único...');
        console.log('📦 Datos enviados:', { nombre, idMarca, idCategoria });

        try {
            const response = await fetch('/api/modelo/check-nombre', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify({
                    nombre,
                    idMarca,
                    idCategoria,
                }),
            });

            const data = await response.json();
            console.log('✅ Respuesta del servidor:', data);

            return data.unique;
        } catch (error) {
            console.error('❌ Error en la petición:', error);
            return false;
        }
    };

    const validateNombre = (value) => {
        const regex = /^[a-zA-Z0-9\s]+$/; // Sin caracteres especiales
        return value.trim() !== '' && regex.test(value);
    };

    // Función reutilizable para validar nombre completo
    const validarNombreCompleto = async () => {
        const nombre = nombreInput.value;
        const idMarca = idMarcaInput.value;
        const idCategoria = idCategoriaInput.value;

        console.log('🛠 Ejecutando validación completa:', { nombre, idMarca, idCategoria });

        if (!validateNombre(nombre)) {
            console.log('❗ Nombre inválido (vacío o con caracteres especiales)');
            nombreInput.setCustomValidity(
                'El nombre no debe estar vacío ni tener caracteres especiales.',
            );
        } else if (!(await validateNombreUnico(nombre, idMarca, idCategoria))) {
            console.log('⚠️ El nombre ya está en uso con esa marca y categoría.');
            nombreInput.setCustomValidity('Ya existe un modelo con ese nombre, marca y categoría.');
        } else {
            console.log('✅ Nombre válido y único');
            nombreInput.setCustomValidity('');
        }

        nombreInput.reportValidity();
    };

    // Validar cuando se escribe el nombre
    nombreInput.addEventListener('input', validarNombreCompleto);

    // Validar cuando se cambia la marca o categoría
    idMarcaInput.addEventListener('change', validarNombreCompleto);
    idCategoriaInput.addEventListener('change', validarNombreCompleto);

    // Validación al enviar el formulario
    form.addEventListener('submit', async (event) => {
        const nombre = nombreInput.value;
        const idMarca = idMarcaInput.value;
        const idCategoria = idCategoriaInput.value;

        console.log('🚀 Enviando formulario con:', { nombre, idMarca, idCategoria });

        if (!validateNombre(nombre)) {
            console.log('❌ Envío cancelado: nombre inválido');
            event.preventDefault();
            return;
        }

        const isUnique = await validateNombreUnico(nombre, idMarca, idCategoria);

        if (!isUnique) {
            console.log('❌ Envío cancelado: nombre duplicado');
            event.preventDefault();
            return;
        }

        console.log('✅ Formulario válido, se enviará');
    });
});
