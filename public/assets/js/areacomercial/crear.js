        // Elementos del DOM
        const tabContactoBtn = document.getElementById('tabContactoBtn');
        const tabEmpresaBtn = document.getElementById('tabEmpresaBtn');
        const tabContacto = document.getElementById('tabContacto');
        const tabEmpresa = document.getElementById('tabEmpresa');
        const formContacto = document.getElementById('formContacto');
        const formEmpresa = document.getElementById('formEmpresa');

        // Event Listeners
        tabEmpresaBtn.addEventListener('click', () => showTab('empresa'));
        tabContactoBtn.addEventListener('click', () => showTab('contacto'));
        
    formEmpresa.addEventListener('submit', async function (e) {
    e.preventDefault();

    const btnGuardar = this.querySelector('button[type="submit"]');
    const textoOriginal = btnGuardar.textContent;

    // Cambiar texto a "Guardando..." y deshabilitar botón
    btnGuardar.textContent = 'Guardando...';
    btnGuardar.disabled = true;

    const data = {
        razon_social: this.razon_social.value,
        ruc: this.ruc.value,
        rubro: this.rubro.value,
        ubicacion: this.ubicacion.value,
        fuente_captacion_id: this.elements['fuente_captacion_id'].value
    };

    try {
        const response = await fetch('/empresas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json', // <--- Esta línea es clave
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            alert('✅ Empresa registrada correctamente');
                window.location.href = '/seguimiento/' + result.idSeguimiento + '/edit';

            this.reset();
        } else {
            alert('❌ Error al registrar empresa');
        }
    } catch (error) {
        alert('❌ Error en la conexión');
        console.error(error);
    } finally {
        // Restaurar texto y habilitar botón
        btnGuardar.textContent = textoOriginal;
        btnGuardar.disabled = false;
    }
});


        
      formContacto.addEventListener('submit', async function (e) {
    e.preventDefault();

    const btnGuardar = this.querySelector('button[type="submit"]');
    const textoOriginal = btnGuardar.textContent;

    // Cambiar texto a "Guardando..." y deshabilitar botón
    btnGuardar.textContent = 'Guardando...';
    btnGuardar.disabled = true;

    const data = {
        tipo_documento: this.tipo_documento.value,
        numero_documento: this.numero_documento.value,
        nombre_completo: this.nombre_completo.value,
        cargo: this.cargo.value,
        correo: this.correo.value,
        telefono: this.telefono.value,
        nivel_decision: this.nivel_decision.value // Asegúrate que coincida con el name del select
    };

    try {
        const response = await fetch('/contactosone', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        // Verifica si la respuesta está vacía
        const responseText = await response.text();
        if (!responseText) {
            throw new Error('La respuesta del servidor está vacía');
        }

        const result = JSON.parse(responseText);

        if (result.success) {
            alert('✅ Contacto registrado correctamente');
            window.location.href = '/seguimiento/' + result.idSeguimiento + '/edit';
            this.reset();
        } else {
            alert('❌ Error al registrar contacto: ' + (result.message || ''));
        }
    } catch (error) {
        alert('❌ Error en la conexión: ' + error.message);
        console.error('Error:', error);
    } finally {
        // Restaurar texto y habilitar botón
        btnGuardar.textContent = textoOriginal;
        btnGuardar.disabled = false;
    }
});

        // Funciones
        function showTab(tab) {
            tabEmpresa.classList.add('hidden');
            tabContacto.classList.add('hidden');
            tabEmpresaBtn.classList.remove('active-tab');
            tabContactoBtn.classList.remove('active-tab');

            if (tab === 'empresa') {
                tabEmpresa.classList.remove('hidden');
                tabEmpresaBtn.classList.add('active-tab');
            } else {
                tabContacto.classList.remove('hidden');
                tabContactoBtn.classList.add('active-tab');
            }
        }

        // Inicialización
        showTab('empresa');


        
