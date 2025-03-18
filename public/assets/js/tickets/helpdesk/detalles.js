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

        // **🔥 Agregar Skeleton Loading antes de inicializar**
        const skeleton = document.createElement("div");
        skeleton.className = "skeleton-loading w-full h-10 bg-gray-300 animate-pulse rounded-md";
        select.style.display = "none"; // Ocultar el select original
        select.parentNode.insertBefore(skeleton, select);

        // Aplicar Nice Select después de un pequeño delay
        setTimeout(() => {
            try {
                NiceSelect.bind(select, { searchable: true });

                // **🔥 Remover Skeleton Loading y mostrar el select**
                select.style.display = "none";
                select.classList.add("nice-select-applied");
                skeleton.remove();

                select.dataset.niceSelectInitialized = true;
                console.log("✅ Nice Select aplicado en:", select.id);
            } catch (error) {
                console.error("❌ Error inicializando Nice Select en:", select.id, error);
            }
        }, 600); // Tiempo del Skeleton antes de mostrar select real
    });
}

// ✅ Detectar cambios de tab y aplicar Nice Select 2 solo en "Tickets"
document.addEventListener("alpine:tab-changed", function(event) {
    if (event.detail.tab === "detalle") {
        console.log("🔄 Cambio a 'Tickets' detectado. Inicializando Nice Select 2...");
        setTimeout(initNiceSelect, 500);
    }
});
