
document.addEventListener("DOMContentLoaded", function() {
    // Inicializar NiceSelect2
    document.querySelectorAll('.select2').forEach(function(select) {
        NiceSelect.bind(select, {
            searchable: true
        });
    });
});

document.addEventListener("DOMContentLoaded", function() {
    let selectTecnicoApoyo = document.getElementById("idTecnicoApoyo");
    let checkboxApoyo = document.getElementById("necesitaApoyo");
    let selectContainer = document.getElementById("apoyoSelectContainer");
    let selectedItemsContainer = document.getElementById("selected-items-container");
    let selectedItemsList = document.getElementById("selected-items-list");

    // Inicializar NiceSelect2 en el select múltiple
    NiceSelect.bind(selectTecnicoApoyo, {
        searchable: true
    });

    // Mostrar/ocultar el select2 de técnicos de apoyo según el checkbox
    checkboxApoyo.addEventListener("change", function() {
        if (this.checked) {
            selectContainer.classList.remove("hidden");
            selectedItemsContainer.classList.remove("hidden");
        } else {
            selectContainer.classList.add("hidden");
            selectedItemsContainer.classList.add("hidden");
            selectedItemsList.innerHTML = ""; // Limpiar seleccionados si se desactiva
            selectTecnicoApoyo.value = ""; // Reiniciar el select
            NiceSelect.sync(selectTecnicoApoyo);
        }
    });

    // Actualizar la lista de seleccionados dinámicamente
    selectTecnicoApoyo.addEventListener("change", function() {
        selectedItemsList.innerHTML = ""; // Limpiar antes de actualizar

        let selectedOptions = Array.from(selectTecnicoApoyo.selectedOptions);
        selectedOptions.forEach(option => {
            let item = document.createElement("span");
            item.classList.add("badge", "bg-primary", "px-3", "py-1", "text-white",
                "rounded-lg", "text-sm", "font-medium");
            item.textContent = option.text;
            selectedItemsList.appendChild(item);
        });
    });
});


//ESTO ES DE VISITAS!
document.addEventListener("DOMContentLoaded", function() {
    let visitasContainer = document.getElementById("visitasContainer");
    let crearVisitaBtn = document.getElementById("crearVisitaBtn");
    let modalCrearVisita = document.getElementById("modalCrearVisita");
    let nombreVisitaInput = document.getElementById("nombreVisitaInput");
    let fechaVisitaInput = document.getElementById("fechaVisitaInput");
    let modalImagen = document.getElementById("modalImagen");
    let inputImagen = document.getElementById("inputImagen");
    let previewImagen = document.getElementById("previewImagen");
    let visitaActual = null;
    let visitaCount = 0;

    // Estados disponibles
    const estados = [{
            nombre: "Técnico en desplazamiento",
            requiereUbicacion: true
        },
        {
            nombre: "Llegada a servicio",
            requiereUbicacion: true,
            requiereImagen: true
        },
        {
            nombre: "Inicio de servicio",
            requiereUbicacion: true
        }
    ];

    const ubicaciones = [
        "Sucursal Lima Centro",
        "Sucursal San Isidro",
        "Sucursal Miraflores"
    ];

    function formatDate(fecha) {
        const año = fecha.getFullYear();
        const mes = (fecha.getMonth() + 1).toString().padStart(2, "0");
        const dia = fecha.getDate().toString().padStart(2, "0");
        let horas = fecha.getHours();
        const minutos = fecha.getMinutes().toString().padStart(2, "0");
        const ampm = horas >= 12 ? "PM" : "AM";
        horas = horas % 12 || 12;
        return `${año}-${mes}-${dia} ${horas}:${minutos} ${ampm}`;
    }

    // ABRIR MODAL AL CREAR VISITA
    crearVisitaBtn.addEventListener("click", function() {
        visitaCount++;
        nombreVisitaInput.value = `Visita ${visitaCount}`;
        fechaVisitaInput.value = "";
        modalCrearVisita.classList.remove("hidden");
    });

    // GUARDAR VISITA
    function guardarVisita() {
        if (!fechaVisitaInput.value) {
            alert("Por favor, selecciona una fecha y hora.");
            return;
        }

        let fechaSeleccionada = new Date(fechaVisitaInput.value);
        let fechaFormateada = formatDate(fechaSeleccionada);

        let visitaCard = document.createElement("div");
        visitaCard.classList.add("p-4", "bg-white", "shadow-lg", "rounded-lg", "border", "relative");

        visitaCard.innerHTML = `
    <h5 class="text-lg font-semibold mb-3 text-center">${nombreVisitaInput.value}</h5>
    <div class="space-y-2">
        <div class="flex items-center justify-between border p-3 rounded-lg bg-green-200 border-green-500">
            <span class="text-sm font-medium w-1/4">Fecha de Programación</span>
            <span class="hora text-sm text-gray-800 w-3/4 text-center">${fechaFormateada}</span>
        </div>
        ${estados.map((estado, index) => `
                    <div class="flex items-center justify-between border p-3 rounded-lg bg-gray-100">
                        <span class="text-sm font-medium w-1/4">${estado.nombre}</span>
                        ${estado.requiereImagen ? `
                    <button class="btn-modal bg-blue-500 text-white px-3 py-1 rounded-md">
                        📷
                    </button>
                ` : ""}
                        <button class="estado-btn bg-gray-300 text-white px-3 py-1 rounded-md">
                            ✔
                        </button>
                    </div>
                `).join("")}
    </div>
`;

        visitasContainer.appendChild(visitaCard);
        cerrarModalCrearVisita();
    }

    // CERRAR MODAL DE CREACIÓN DE VISITA
    function cerrarModalCrearVisita() {
        modalCrearVisita.classList.add("hidden");
    }

    // ABRIR MODAL DE IMAGEN
    document.addEventListener("click", function(event) {
        if (event.target.classList.contains("btn-modal")) {
            modalImagen.classList.remove("hidden");
            visitaActual = event.target.closest(".border");
        }
    });

    // GUARDAR IMAGEN
    function guardarImagen() {
        if (visitaActual && inputImagen.files.length > 0) {
            previewImagen.src = URL.createObjectURL(inputImagen.files[0]);
            previewImagen.classList.remove("hidden");
            visitaActual.classList.add("bg-green-200", "border-green-500");
            modalImagen.classList.add("hidden");
        }
    }

    function cerrarModalImagen() {
        modalImagen.classList.add("hidden");
    }

    // AVANZAR ESTADOS
    document.addEventListener("click", function(event) {
        if (event.target.classList.contains("estado-btn")) {
            let estadoDiv = event.target.closest(".border");
            let horaSpan = estadoDiv.querySelector(".hora");

            if (!horaSpan.textContent) {
                let fechaActual = new Date();
                let fechaFormateada = formatDate(fechaActual);
                horaSpan.textContent = fechaFormateada;
                horaSpan.classList.remove("hidden");
            }

            estadoDiv.classList.remove("bg-gray-100");
            estadoDiv.classList.add("bg-green-200", "border-green-500");
            event.target.disabled = true;
        }
    });

    // Conectar botones de modal a funciones
    window.guardarVisita = guardarVisita;
    window.guardarImagen = guardarImagen;
    window.cerrarModalCrearVisita = cerrarModalCrearVisita;
    window.cerrarModalImagen = cerrarModalImagen;
});

