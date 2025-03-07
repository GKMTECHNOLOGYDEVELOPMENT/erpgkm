document.addEventListener("alpine:init", () => {
    Alpine.data("usuariosTable", () => ({
        datatable: null,
        usuariosData: [],

        init() {
            console.log("🚀 Cargando usuarios desde API...");
            this.fetchUsuarios();
        },

        fetchUsuarios() {
            fetch("/api/usuarios")
                .then(response => response.json())
                .then(data => {
                    console.log("✅ Usuarios obtenidos:", data);
                    this.usuariosData = data;
                    this.initTable();
                })
                .catch(error => console.error("❌ Error al obtener usuarios:", error));
        },

        initTable() {
            const tableElement = document.querySelector("#myTable1");

            if (!tableElement) {
                console.error("❌ No se encontró la tabla #myTable1 en el DOM.");
                return;
            }

            // Destruir la tabla si ya existe
            if (tableElement.dataset.datatable) {
                console.log("🔄 Reinicializando Simple-DataTables...");
                tableElement.dataTable.destroy();
            }

            console.log("📊 Inicializando Simple-DataTables...");
            this.datatable = new simpleDatatables.DataTable(tableElement, {
                data: {
                    headings: ["Nombre", "Apellido Paterno", "Tipo Documento", "Documento", "Teléfono", "Tipo Usuario", "Rol", "Área", "Acción"],
                    data: this.formatDataForTable(this.usuariosData),
                },
                searchable: true,
                paging: true,
                labels: {
                    placeholder: "Buscar...",
                    perPage: "{select}",
                    noRows: "No se encontraron registros",
                    info: "Mostrando {start} a {end} de {rows} registros",
                },
            });

            // Centrando los encabezados y los datos
            document.querySelectorAll("#myTable1 thead th, #myTable1 tbody td").forEach(cell => {
                cell.style.textAlign = "center";
            });

            console.log("✅ Simple-DataTables inicializado correctamente.");
        },

        formatDataForTable(data) {
            return data.map(usuario => [
                `<div style="text-align: center;">${usuario.Nombre || "N/A"}</div>`,
                `<div style="text-align: center;">${usuario.apellidoPaterno || "N/A"}</div>`,
                `<div style="text-align: center;">${usuario.tipoDocumento ? usuario.tipoDocumento.nombre : "N/A"}</div>`,
                `<div style="text-align: center;">${usuario.documento || "N/A"}</div>`,
                `<div style="text-align: center;">${usuario.telefono || "N/A"}</div>`,
                `<div style="text-align: center;">${usuario.tipoUsuario ? usuario.tipoUsuario.nombre : "N/A"}</div>`,
                `<div style="text-align: center;">${usuario.rol ? usuario.rol.nombre : "N/A"}</div>`,
                `<div style="text-align: center;">${usuario.tipoArea ? usuario.tipoArea.nombre : "N/A"}</div>`,
                `<div style="text-align: center;" class="flex justify-center items-center gap-2">
                    <a href="/usuarios/${usuario.idUsuario}/edit" class="text-blue-600 hover:text-blue-800" x-tooltip="Editar">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                            <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                            <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                    </a>
                    <button type="button" class="text-red-600 hover:text-red-800" x-tooltip="Eliminar" onclick="deleteUsuario(${usuario.idUsuario})">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path opacity="0.5" d="M9.17065 4C9.58249 2.83481 10.6937 2 11.9999 2C13.3062 2 14.4174 2.83481 14.8292 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>`
            ]);
        }
    }));
});

// Función para eliminar usuario
function deleteUsuario(idUsuario) {
    Swal.fire({
        icon: "warning",
        title: "¿Estás seguro?",
        text: "¡No podrás revertir esta acción!",
        showCancelButton: true,
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
    }).then(result => {
        if (result.isConfirmed) {
            fetch(`/api/usuarios/${idUsuario}`, { method: "DELETE" })
                .then(response => {
                    if (!response.ok) throw new Error("Error al eliminar usuario");
                    return response.json();
                })
                .then(() => {
                    Swal.fire("Eliminado", "El usuario ha sido eliminado con éxito.", "success")
                        .then(() => location.reload());
                })
                .catch(error => {
                    Swal.fire("Error", error.message || "Ocurrió un error al eliminar el usuario.", "error");
                });
        }
    });
}
