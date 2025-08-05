document.getElementById('btnBuscarRuc').addEventListener('click', async () => {
    const btn = document.getElementById('btnBuscarRuc');
    const ruc = document.querySelector('input[name="ruc"]').value;

    if (!ruc) return alert("Ingresa un RUC");

    const originalText = btn.innerHTML;
    btn.innerHTML = '🔄 Buscando...';
    btn.disabled = true;

    try {
        const response = await fetch('/api/buscar-ruc', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ ruc })
        });

        const result = await response.json();

        if (result.success) {
            document.querySelector('input[name="razon_social"]').value = result.razon_social;
            document.querySelector('input[name="ubicacion"]').value = result.direccion;
            document.querySelector('input[name="rubro"]').value = result.rubro;
        } else {
            alert(result.message || 'No se encontraron datos');
        }
    } catch (error) {
        alert("Error al buscar RUC");
        console.error(error);
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});
