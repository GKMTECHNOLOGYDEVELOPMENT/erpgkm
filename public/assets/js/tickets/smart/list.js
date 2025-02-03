document.addEventListener('alpine:init', () => {
    Alpine.data('multipleTable', () => ({
        datatable1: null,
        ordenesData: [],
        marcas: [],
        marcaFilter: '',
        startDate: '', // Para el filtro de fecha de inicio
        endDate: '', // Para el filtro de fecha de fin
        currentPage: 1,
        lastPage: 1,
        isLoading: false,

        init() {
            this.fetchMarcas();
            this.fetchDataAndInitTable();

             // Observar cambios en filtros y actualizar tabla
            this.$watch('marcaFilter', () => this.fetchDataAndInitTable());
            this.$watch('startDate', () => this.fetchDataAndInitTable());
            this.$watch('endDate', () => this.fetchDataAndInitTable());

            
        },
        
        // Observar cambios en la marcaFilter, startDate y endDate y volver a cargar los datos
        watch: {
            marcaFilter(newValue) {
                this.fetchDataAndInitTable(); // Re-cargar los datos cuando cambia la marca
            },
            startDate(newValue) {
                this.fetchDataAndInitTable(); // Re-cargar los datos cuando cambia la fecha de inicio
            },
            endDate(newValue) {
                this.fetchDataAndInitTable(); // Re-cargar los datos cuando cambia la fecha de fin
            }
        },
        
        fetchMarcas() {
            fetch('/api/marcas')
                .then(response => response.json())
                .then(data => {
                    this.marcas = data;
                })
                .catch(error => console.error('Error al cargar marcas:', error));
        },

        fetchDataAndInitTable(page = 1) {
            this.isLoading = true; // Mostrar preloader antes de cargar datos
        
            let url = `/api/ordenes?page=${page}`;
            if (this.marcaFilter) {
                url += `&marca=${this.marcaFilter}`; // Añadir el filtro de marca
            }
        
            // Verificar y registrar en consola el valor de las fechas
            if (this.startDate) {
                console.log("Filtro de fecha de inicio:", this.startDate); // Verificar valor de fecha de inicio
                url += `&start_date=${this.startDate}`; // Añadir el filtro de fecha de inicio
            }
        
            if (this.endDate) {
                console.log("Filtro de fecha de fin:", this.endDate); // Verificar valor de fecha de fin
                url += `&end_date=${this.endDate}`; // Añadir el filtro de fecha de fin
            }
        
            console.log("URL con parámetros de filtro:", url); // Verificar la URL que se está generando
        
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Error al obtener datos del servidor');
                    return response.json();
                })
                .then(data => {
                    console.log('Datos filtrados:', data); // Verifica los datos recibidos

                    this.ordenesData = data.data;
                    this.currentPage = data.current_page;
                    this.lastPage = data.last_page;
        
                    if (this.datatable1) {
                        this.datatable1.destroy();
                    }
        
                    this.datatable1 = new simpleDatatables.DataTable('#myTable1', {
                        data: {
                            headings: ['Acciones', 'Fecha Ticket', 'Ticket', 'Marca', 'Modelo', 'Serie', 'Técnico'],
                            data: this.formatDataForTable(this.ordenesData),
                        },
                        searchable: true,
                        perPage: 10,
                        labels: {
                            placeholder: 'Buscar...',
                            perPage: '{select} registros por página',
                            noRows: 'No se encontraron registros',
                            info: '',
                        },
                        layout: {
                            top: '{search}',
                            bottom: '{info}{select}{pager}',
                        },
                    });
        
                    this.updatePagination();
                })
                .catch(error => console.error('Error al inicializar la tabla:', error))
                .finally(() => {
                    this.isLoading = false; // Ocultar preloader después de cargar datos
                });
        },

        updatePagination() {
            let paginationDiv = document.getElementById('pagination');
            paginationDiv.innerHTML = ''; // Limpiar paginación anterior

            let maxPagesToShow = 5; // Máximo de páginas a mostrar antes de añadir puntos (...)
            let startPage = Math.max(1, this.currentPage - Math.floor(maxPagesToShow / 2));
            let endPage = Math.min(this.lastPage, startPage + maxPagesToShow - 1);

            let paginationHTML = `<ul class="inline-flex items-center space-x-1 rtl:space-x-reverse m-auto mb-4">`;

            // Mostrar "1" y "..." si la página actual está lejos del inicio
            if (startPage > 1) {
                paginationHTML += `
                    <li>
                        <button type="button" class="flex justify-center font-semibold p-1 rounded-full transition bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary" 
                        @click="fetchDataAndInitTable(1)">1</button>
                    </li>`;
                if (startPage > 2) paginationHTML += `<li><span class="px-3">...</span></li>`;
            }

            // Renderizar las páginas intermedias
            for (let i = startPage; i <= endPage; i++) {
                paginationHTML += `
                    <li>
                        <button type="button" class="flex justify-center font-semibold px-3 py-1.5 rounded-full transition ${this.currentPage === i ? 'bg-primary text-white dark:text-white-light dark:bg-primary' : 'bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary'}"
                        @click="fetchDataAndInitTable(${i})">${i}</button>
                    </li>`;
            }

            // Mostrar "..." y última página si la página actual está lejos del final
            if (endPage < this.lastPage) {
                if (endPage < this.lastPage - 1) paginationHTML += `<li><span class="px-3">...</span></li>`;
                paginationHTML += `
                    <li>
                        <button type="button" class="flex justify-center font-semibold p-1 rounded-full transition bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary" 
                        @click="fetchDataAndInitTable(${this.lastPage})">${this.lastPage}</button>
                    </li>`;
            }

            paginationHTML += `</ul>`;

            // Agregar botones de navegación
            paginationHTML = `
                <div class="flex justify-center">
                <button type="button" 
                        class="flex justify-center items-center font-semibold w-10 h-10 p-2 rounded-full transition bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary mx-2" 
                        ${this.currentPage === 1 ? 'disabled' : ''} 
                        @click="fetchDataAndInitTable(${this.currentPage - 1})">
                    <svg width="12" height="12" fill="currentColor" class="bi bi-chevron-left">
                        <path d="M11.75 13.75a.75.75 0 0 1-1.06 0L6 8.81 1.31 13.47a.75.75 0 1 1-1.06-1.06l5.95-5.95a.75.75 0 0 1 1.06 0l5.95 5.95a.75.75 0 0 1 0 1.06z"></path>
                    </svg>
                </button>

                ${paginationHTML}

                <button type="button" 
                        class="flex justify-center items-center font-semibold w-10 h-10 p-2 rounded-full transition bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary mx-2" 
                        ${this.currentPage === this.lastPage ? 'disabled' : ''} 
                        @click="fetchDataAndInitTable(${this.currentPage + 1})">
                    <svg width="12" height="12" fill="currentColor" class="bi bi-chevron-right">
                        <path d="M4.75 2.25a.75.75 0 0 1 1.06 0l5.95 5.95a.75.75 0 0 1 0 1.06L5.81 15.47a.75.75 0 1 1-1.06-1.06l5.95-5.95a.75.75 0 0 1 0-1.06L4.75 2.25z"></path>
                    </svg>
                </button>
            </div>
            `;

            paginationDiv.innerHTML = paginationHTML;
        },
        formatDataForTable(data) {
            return data.map(orden => [
                `<div style="text-align: center;" class="flex justify-center items-center">
                <a href="/ordenes/${orden.idTickets}/edit" class="ltr:mr-2 rtl:ml-2" x-tooltip="Editar">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
            </svg>   
                </a>
                <button type="button" x-tooltip="Eliminar" @click="deleteOrden(${orden.idTickets})">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                <path opacity="0.5" d="M9.17065 4C9.58249 2.83481 10.6937 2 11.9999 2C13.3062 2 14.4174 2.83481 14.8292 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
            </svg>
                </button>
            </div>`, // Acciones
                orden.fecha_creacion || 'N/A',
                orden.numero_ticket || 'N/A',
                orden.marca ? orden.marca.nombre : 'N/A',
                orden.modelo ? orden.modelo.nombre : 'N/A',
                orden.serie || 'N/A',
                orden.tecnico ? orden.tecnico.Nombre : 'N/A',
            ]);
        }
    }));
});
