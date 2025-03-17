// ✅ SOLO EJECUTAR NICE SELECT 2 EN EL TAB "TICKETS"
function initNiceSelect() {
    console.log("🔄 Verificando si estamos en 'Tickets' para inicializar Nice Select 2...");

    // Revisamos si Alpine ya inicializó los tabs
    const activeTab = document.querySelector("[class*='bg-success'][class*='text-white']");
    if (!activeTab) {
        console.log("⏸️ No se encontró un tab activo aún. Reintentando en 300ms...");
        setTimeout(initNiceSelect, 300);
        return;
    }

    if (!activeTab.textContent.includes("Ticket")) {
        console.log("⏸️ No estamos en 'Tickets', no se ejecutará Nice Select 2.");
        return;
    }

    console.log("✅ Aplicando Nice Select 2 en 'Tickets'...");

    document.querySelectorAll(".select2").forEach(select => {
        // Si ya está inicializado, eliminar la instancia anterior
        if (select.dataset.niceSelectInitialized) {
            const existingWrapper = select.nextElementSibling;
            if (existingWrapper && existingWrapper.classList.contains("nice-select")) {
                existingWrapper.remove();
            }
            select.classList.remove("nice-select-applied");
        }

        // Aplicar Nice Select
        try {
            NiceSelect.bind(select, {
                searchable: true
            });
            select.style.display = "none";
            select.classList.add("nice-select-applied");
            select.dataset.niceSelectInitialized = true;
        } catch (error) {
            console.error("❌ Error inicializando Nice Select en:", select.id, error);
        }
    });
}

// ✅ Detectar cambios de tab y aplicar Nice Select 2 solo en "Tickets"
document.addEventListener("alpine:tab-changed", function(event) {
    if (event.detail.tab === "detalle") {
        console.log("🔄 Cambio a 'Tickets' detectado. Inicializando Nice Select 2...");
        setTimeout(initNiceSelect, 500);
    }
});