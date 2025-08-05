document.getElementById('buscarClienteBtn').addEventListener('click', async () => {
    const btn = document.getElementById('buscarClienteBtn');
    const tipoDoc = document.getElementById('tipoDocumento').value;
    const numeroDoc = document.getElementById('numeroDocumento').value.trim();

    if (!tipoDoc || !numeroDoc) {
        alert('Por favor seleccione tipo de documento e ingrese el número.');
        return;
    }

    const originalText = btn.innerHTML;
    btn.innerHTML = '🔄 Buscando...';
    btn.disabled = true;

    try {
        const res = await fetch(`/api/clientes/buscar?tipo_documento=${encodeURIComponent(tipoDoc)}&documento=${encodeURIComponent(numeroDoc)}`);
        const data = await res.json();

        if (data.success && data.cliente) {
            const cliente = data.cliente;
            document.querySelector('input[name="nombre_completo"]').value = cliente.nombre || '';
            document.querySelector('input[name="telefono"]').value = cliente.telefono || '';
            document.querySelector('input[name="correo"]').value = cliente.email || '';
            document.querySelector('input[name="cargo"]').value = ''; // El cargo sigue vacío
        } else {
            alert('Cliente no encontrado');
        }
    } catch (error) {
        console.error('Error en la búsqueda:', error);
        alert('Error al buscar cliente');
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});
