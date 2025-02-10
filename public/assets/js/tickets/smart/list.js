document.addEventListener('alpine:init', () => {
    Alpine.data('multipleTable', () => ({
      datatable1: null,
      ordenesData: [],
      marcas: [],
      marcaFilter: '',
      startDate: '',
      endDate: '',
      currentPage: 1,
      lastPage: 1,
      isLoading: false,
  
      init() {
        this.fetchMarcas();
        this.fetchDataAndInitTable();
        this.$watch('marcaFilter', () => this.fetchDataAndInitTable());
        this.$watch('startDate', () => this.fetchDataAndInitTable());
        this.$watch('endDate', () => this.fetchDataAndInitTable());
      },
  
      fetchMarcas() {
        fetch('/api/marcas')
          .then(response => response.json())
          .then(data => { this.marcas = data; })
          .catch(error => console.error('Error al cargar marcas:', error));
      },
  
      fetchDataAndInitTable(page = 1) {
        this.isLoading = true;
        let url = `/api/ordenes?page=${page}`;
        if (this.marcaFilter) url += `&marca=${this.marcaFilter}`;
        if (this.clienteGeneralFilter) url += `&clienteGeneral=${this.clienteGeneralFilter}`;
        if (this.startDate) url += `&start_date=${this.startDate}`;
        if (this.endDate) url += `&end_date=${this.endDate}`;
  
        fetch(url)
          .then(response => {
            if (!response.ok) throw new Error('Error al obtener datos del servidor');
            return response.json();
          })
          .then(data => {
            this.ordenesData = data.data;
            this.currentPage = data.current_page;
            this.lastPage = data.last_page;
  
            if (this.datatable1) this.datatable1.destroy();
  
            this.datatable1 = new simpleDatatables.DataTable('#myTable1', {
              data: {
                headings: [
                  'EDITAR',
                  'N. TICKET',
                  'F. TICKET',
                  'F. VISITA',
                  'CATEGORIA',
                  // 'MARCA',
                  'GENERAL',
                  'MODELO',
                  'SERIE',
                  'CLIENTE',
                  'DIRECCIÓN',
                  'MÁS'
                ],
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
  
            document.querySelectorAll('#myTable1 thead th').forEach(cell => {
              cell.style.textAlign = 'center';
            });
            document.querySelectorAll('#myTable1 tbody td').forEach(cell => {
              cell.style.wordWrap = 'break-word';
              cell.style.whiteSpace = 'normal';
            });
  
            this.updatePagination();
          })
          .catch(error => console.error('Error al inicializar la tabla:', error))
          .finally(() => { this.isLoading = false; });
      },
  
      updatePagination() {
        let paginationDiv = document.getElementById('pagination');
        paginationDiv.innerHTML = '';
  
        let maxPagesToShow = 5;
        let startPage = Math.max(1, this.currentPage - Math.floor(maxPagesToShow / 2));
        let endPage = Math.min(this.lastPage, startPage + maxPagesToShow - 1);
  
        let paginationHTML = `<ul class="inline-flex items-center space-x-1 rtl:space-x-reverse m-auto mb-4">`;
  
        if (startPage > 1) {
          paginationHTML += `
            <li>
              <button type="button" class="flex justify-center font-semibold p-1 rounded-full transition bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary" 
              @click="fetchDataAndInitTable(1)">1</button>
            </li>`;
          if (startPage > 2) paginationHTML += `<li><span class="px-3">...</span></li>`;
        }
  
        for (let i = startPage; i <= endPage; i++) {
          paginationHTML += `
            <li>
              <button type="button" class="flex justify-center font-semibold px-3 py-1.5 rounded-full transition ${this.currentPage === i ? 'bg-primary text-white dark:text-white-light dark:bg-primary' : 'bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary'}"
              @click="fetchDataAndInitTable(${i})">${i}</button>
            </li>`;
        }
  
        if (endPage < this.lastPage) {
          if (endPage < this.lastPage - 1) paginationHTML += `<li><span class="px-3">...</span></li>`;
          paginationHTML += `
            <li>
              <button type="button" class="flex justify-center font-semibold p-1 rounded-full transition bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary" 
              @click="fetchDataAndInitTable(${this.lastPage})">${this.lastPage}</button>
            </li>`;
        }
  
        paginationHTML += `</ul>`;
  
        paginationHTML = `
          <div class="flex justify-center">
            <button type="button" 
              class="flex justify-center items-center font-semibold w-10 h-10 p-2 rounded-full transition bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary mx-2" 
              ${this.currentPage === 1 ? 'disabled' : ''} 
              @click="fetchDataAndInitTable(${this.currentPage - 1})">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 block mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
  
            ${paginationHTML}
  
            <button type="button" 
              class="flex justify-center items-center font-semibold w-10 h-10 p-2 rounded-full transition bg-white-light text-dark hover:text-white hover:bg-primary dark:text-white-light dark:bg-[#191e3a] dark:hover:bg-primary mx-2" 
              ${this.currentPage === this.lastPage ? 'disabled' : ''} 
              @click="fetchDataAndInitTable(${this.currentPage + 1})">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 block mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>
        `;
  
        paginationDiv.innerHTML = paginationHTML;
      },
  
      formatDataForTable(data) {
        return data.map(orden => {
          const fechaTicket = orden.fecha_creacion
            ? new Date(orden.fecha_creacion).toLocaleDateString('es-ES', {
                day: '2-digit', month: '2-digit', year: 'numeric'
              })
            : 'N/A';
  
          const fechaVisita = orden.fecha_visita
            ? new Date(orden.fecha_visita).toLocaleDateString('es-ES', {
                day: '2-digit', month: '2-digit', year: 'numeric'
              })
            : 'N/A';
  
          const wrap = text =>
            `<div style="word-wrap: break-word; white-space: normal; text-align: center;">${text}</div>`;
  
          return [
            `<div style="text-align: center;" class="flex justify-center items-center">
               <a href="/ordenes/smart/${orden.idTickets}/edit" class="ltr:mr-2 rtl:ml-2" x-tooltip="Editar">
                 <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 block mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" />
                   <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" />
                 </svg>
               </a>
             </div>`,
            wrap(orden.numero_ticket || 'N/A'),
            wrap(fechaTicket),
            wrap(fechaVisita),
            wrap(orden.modelo && orden.modelo.categorium  ? orden.modelo.categorium .nombre : 'N/A'), // Aquí se agrega el nombre de la categoría
            // wrap(orden.marca ? orden.marca.nombre : 'N/A'),
            wrap(orden.clientegeneral ? orden.clientegeneral.descripcion: 'N/A' ),
            wrap(orden.modelo ? orden.modelo.nombre : 'N/A'),
            wrap(orden.serie ? orden.serie : 'N/A'),
            wrap(orden.cliente ? orden.cliente.nombre : 'N/A'),
            wrap(orden.direccion || 'N/A'),
            `<div class="flex justify-center items-center">
               <button type="button" class="p-1" @click="toggleRowDetails($event, ${orden.idTickets})">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 block mx-auto" fill="currentColor" viewBox="0 0 24 24">
                   <circle cx="5" cy="12" r="2"/>
                   <circle cx="12" cy="12" r="2"/>
                   <circle cx="19" cy="12" r="2"/>
                 </svg>
               </button>
             </div>`
          ];
        });
      },
  
      toggleRowDetails(event, id) {
        let button = event.currentTarget;
        let currentRow = button.closest('tr');
        if (currentRow.nextElementSibling && currentRow.nextElementSibling.classList.contains('expanded-row')) {
          currentRow.parentNode.removeChild(currentRow.nextElementSibling);
        } else {
          let record = this.ordenesData.find(r => r.idTickets == id);
          if (record) {
            let newRow = document.createElement('tr');
            newRow.classList.add('expanded-row');
            let cell = document.createElement('td');
            // Ahora la tabla tiene 11 columnas
            cell.setAttribute('colspan', 11);
            cell.innerHTML = `
              <div class="p-2 text-sm">
                <ul>
                  <li><strong>SOLUCIÓN:</strong> ${record.solucion || 'N/A'}</li>
                  <li><strong>ESTADO FLUJO:</strong> ${record.estado_fljo || 'N/A'}</li>
                  <li><strong>TÉCNICO:</strong> ${record.tecnico ? record.tecnico.Nombre : 'N/A'}</li>
                </ul>
              </div>
            `;
            newRow.appendChild(cell);
            currentRow.parentNode.insertBefore(newRow, currentRow.nextSibling);
          }
        }
      }
    }));
  });
  