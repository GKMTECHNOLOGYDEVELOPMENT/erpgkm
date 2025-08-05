
document.addEventListener('DOMContentLoaded', async () => {
    const fuenteSelect = document.getElementById('fuenteCaptacion');
    const nivelSelect = document.getElementById('nivelDecision');
    const tipoDocSelect = document.getElementById('tipoDocumento');

    try {
        const res = await fetch('/api/catalogos');
        const data = await res.json();

        // Fuente captación
        data.fuentes.forEach(fuente => {
            let opt = document.createElement('option');
            opt.value = fuente.id;
            opt.textContent = fuente.nombre;
            fuenteSelect.appendChild(opt);
        });

        // Nivel decisión
        data.niveles.forEach(nivel => {
            let opt = document.createElement('option');
            opt.value = nivel.id;
            opt.textContent = nivel.nombre;
            nivelSelect.appendChild(opt);
        });

        // Tipo documento
        data.documentos.forEach(doc => {
            let opt = document.createElement('option');
            opt.value = doc.id;
            opt.textContent = doc.nombre;
            tipoDocSelect.appendChild(opt);
        });

    } catch (err) {
        console.error('Error cargando los catálogos:', err);
    }
});
