<x-layout.default>

    <style>
        .tab-btn {
            font-weight: 600;
            color: #6B7280;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            cursor: pointer;
        }

        .active-tab {
            color: #3B82F6;
            border-color: #3B82F6;
        }

        .tab-content {
            transition: all 0.3s;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        input:focus,
        select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            border-color: #3B82F6;
        }

        .btn-hover:hover {
            transform: translateY(-1px);
        }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Estilo para tabs responsivos */
        .tabs-container {
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        /* Estilo para tarjetas de observaciones */
        .observacion-card {
            transition: all 0.3s ease;
        }

        .observacion-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Estilo para la línea de tiempo del cronograma */
        .timeline-item {
            position: relative;
            padding-left: 2rem;
        }

        .timeline-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 2px;
            height: 100%;
            background-color: #e2e8f0;
        }

        .timeline-dot {
            position: absolute;
            left: -5px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #3b82f6;
        }
    </style>
    <div class="max-w-6x2 mx-auto p-8 bg-white rounded-lg shadow-lg">
        {{-- <h2 class="text-4x2 font-bold mb-8 text-gray-800">
            @if ($seguimiento->tipoRegistro == 1)
                Editar Empresa
            @else
                Editar Contacto
            @endif
        </h2> --}}

        {{-- Tabs --}}
        <div class="flex space-x-4 border-b border-gray-200 mb-6 overflow-x-auto">
            <button id="tabEmpresaBtn"
                class="tab-btn px-6 py-3 flex items-center whitespace-nowrap {{ $seguimiento->tipoRegistro == 1 ? 'active-tab' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z"
                        clip-rule="evenodd" />
                </svg>
                Empresa
            </button>
            <button id="tabContactoBtn"
                class="tab-btn px-6 py-3 flex items-center whitespace-nowrap {{ $seguimiento->tipoRegistro == 2 ? 'active-tab' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clip-rule="evenodd" />
                </svg>
                Contacto
            </button>
            <button id="tabProyectosBtn" class="tab-btn px-6 py-3 flex items-center whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                Proyectos/Servicios
            </button>
            <button id="tabCronogramaBtn" class="tab-btn px-6 py-3 flex items-center whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                        clip-rule="evenodd" />
                </svg>
                Cronograma
            </button>
            <button id="tabObservacionesBtn" class="tab-btn px-6 py-3 flex items-center whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                Observaciones
            </button>
        </div>

        {{-- Contenedores de los tabs --}}
        <div class="panel">
            <div id="tabEmpresa" class="tab-content {{ $seguimiento->tipoRegistro == 1 ? '' : 'hidden' }}"></div>
            <div id="tabContacto" class="tab-content {{ $seguimiento->tipoRegistro == 2 ? '' : 'hidden' }}"></div>
            <div id="tabProyectos" class="tab-content hidden"></div>
            <div id="tabCronograma" class="tab-content hidden"></div>
            <div id="tabObservaciones" class="tab-content hidden"></div>
        </div>  
    </div>



        <script src="/assets/js/Sortable.min.js"></script>
        <link href="{{ Vite::asset('resources/css/fullcalendar.min.css') }}" rel='stylesheet' />
        <script src='/assets/js/fullcalendar.min.js'></script>


        


        

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const tabButtons = {
                empresa: document.getElementById('tabEmpresaBtn'),
                contacto: document.getElementById('tabContactoBtn'),
                proyectos: document.getElementById('tabProyectosBtn'),
                cronograma: document.getElementById('tabCronogramaBtn'),
                observaciones: document.getElementById('tabObservacionesBtn')
            };

            const tabContents = {
                empresa: document.getElementById('tabEmpresa'),
                contacto: document.getElementById('tabContacto'),
                proyectos: document.getElementById('tabProyectos'),
                cronograma: document.getElementById('tabCronograma'),
                observaciones: document.getElementById('tabObservaciones')
            };

            const seguimientoId = {{ $seguimiento->idSeguimiento }};
            const initialTab = @json($seguimiento->tipoRegistro == 1 ? 'empresa' : 'contacto');

            // Event Listeners para todos los tabs
            Object.entries(tabButtons).forEach(([tabName, button]) => {
                button.addEventListener('click', () => loadTab(tabName));
            });

            // Cargar el tab inicial
            loadTab(initialTab, true);

            // Función para cargar un tab
            async function loadTab(tab, isInitialLoad = false) {
                try {
                    // Mostrar estado de carga
                    showLoadingState(tab);

                    // Hacer la petición al servidor
                    const response = await fetch(`/seguimiento/${seguimientoId}/edit-tab?tab=${tab}`);

                    // Verificar primero si la respuesta es JSON
                    let data;
                    try {
                        data = await response.json();
                    } catch (jsonError) {
                        // Si falla el parseo JSON, obtener como texto para diagnóstico
                        const textResponse = await response.text();
                        console.error('Respuesta no JSON:', textResponse.substring(0, 300));
                        throw new Error(
                            `El servidor devolvió un formato inesperado. Estado: ${response.status}`);
                    }

                    // Si hay error en la respuesta (pero es JSON válido)
                    if (!response.ok) {
                        const errorMsg = data.error || `Error ${response.status}: ${response.statusText}`;
                        throw new Error(errorMsg);
                    }

                    // Si no hay contenido HTML
                    if (!data.html) {
                        throw new Error('No se recibió contenido para mostrar');
                    }

                    // Actualizar el contenido del tab
                    updateTabContent(tab, data.html, isInitialLoad);

                } catch (error) {
                    console.error(`Error al cargar el tab ${tab}:`, error);

                    // Mostrar mensaje de error apropiado
                    let errorMessage = error.message;

                    // Mensajes más amigables para diferentes tipos de error
                    if (error.message.includes('formato inesperado')) {
                        errorMessage = 'Error al comunicarse con el servidor';
                    } else if (error.message.includes('No se recibió contenido')) {
                        errorMessage = 'No hay datos disponibles para mostrar';
                    }

                    showErrorState(tab, errorMessage);
                }
            }

            function updateTabContent(tab, html, isInitialLoad) {
                // Primero ocultamos todos los tabs de "Proyectos", "Cronograma" y "Observaciones"
                tabProyectos.classList.add('hidden');
                tabCronograma.classList.add('hidden');
                tabObservaciones.classList.add('hidden');

                // Removemos la clase active-tab de todos los botones de estos tabs
                tabProyectosBtn.classList.remove('active-tab');
                tabCronogramaBtn.classList.remove('active-tab');
                tabObservacionesBtn.classList.remove('active-tab');

                // Mantenemos tu lógica original para Empresa/Contacto
                if (tab === 'empresa') {
                    tabEmpresa.innerHTML = html;
                    if (!isInitialLoad) {
                        tabEmpresa.classList.remove('hidden');
                        tabContacto.classList.add('hidden');
                        tabEmpresaBtn.classList.add('active-tab');
                        tabContactoBtn.classList.remove('active-tab');
                    }
                } else if (tab === 'contacto') {
                    tabContacto.innerHTML = html;
                    if (!isInitialLoad) {
                        tabContacto.classList.remove('hidden');
                        tabEmpresa.classList.add('hidden');
                        tabContactoBtn.classList.add('active-tab');
                        tabEmpresaBtn.classList.remove('active-tab');
                    }
                } else {
                    // Manejo para los demás tabs
                    const tabElement = tabContents[tab];
                    const tabButton = tabButtons[tab];

                    tabElement.innerHTML = html;
                    tabElement.classList.remove('hidden');
                    tabButton.classList.add('active-tab');

                    // Asegurarnos de ocultar los tabs de empresa/contacto si estamos en otro tab
                    if (!isInitialLoad) {
                        tabEmpresa.classList.add('hidden');
                        tabContacto.classList.add('hidden');
                        tabEmpresaBtn.classList.remove('active-tab');
                        tabContactoBtn.classList.remove('active-tab');
                    }
                }
            }

            // Función para crear nuevo registro
            function createNew(type) {
                if (type === 'empresa') {
                    // Lógica para crear nueva empresa
                    window.location.href = `/empresas/create?seguimiento_id=${seguimientoId}`;
                } else {
                    // Lógica para crear nuevo contacto
                    window.location.href = `/contactos/create?seguimiento_id=${seguimientoId}`;
                }
            }

            // Mostrar estado de carga
            function showLoadingState(tab) {
                const loaderHtml = `
                    <div class="flex justify-center items-center h-40">
                        <div class="loader"></div>
                        <p class="ml-3 text-gray-600">Cargando...</p>
                    </div>
                `;

                if (tab === 'empresa') {
                    tabEmpresa.innerHTML = loaderHtml;
                } else {
                    tabContacto.innerHTML = loaderHtml;
                }
            }

            // Mostrar estado de error
            function showErrorState(tab, message) {
                const errorHtml = `
                    <div class="flex justify-center items-center h-40">
                        <div class="text-red-500 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-2">${message}</p>
                            <button onclick="loadTab('${tab}')" class="mt-3 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Reintentar
                            </button>
                        </div>
                    </div>
                `;

                if (tab === 'empresa') {
                    tabEmpresa.innerHTML = errorHtml;
                } else {
                    tabContacto.innerHTML = errorHtml;
                }
            }

            // Inicializar scripts específicos del tab
            function initTabScripts(tab) {
                if (tab === 'empresa') {
                    // Inicializar scripts para el tab de empresa
                    initEmpresaTabScripts();
                } else {
                    // Inicializar scripts para el tab de contacto
                    initContactoTabScripts();
                }
            }

            // Scripts específicos para el tab de empresa
            function initEmpresaTabScripts() {
                // Aquí puedes inicializar cualquier script necesario para el tab de empresa
                const btnBuscarRuc = document.getElementById('btnBuscarRuc');
                if (btnBuscarRuc) {
                    btnBuscarRuc.addEventListener('click', function() {
                        // Tu lógica para buscar RUC
                    });
                }
            }

            // Scripts específicos para el tab de contacto
            function initContactoTabScripts() {
                // Aquí puedes inicializar cualquier script necesario para el tab de contacto
                const buscarClienteBtn = document.getElementById('buscarClienteBtn');
                if (buscarClienteBtn) {
                    buscarClienteBtn.addEventListener('click', function() {
                        // Tu lógica para buscar cliente
                    });
                }
            }

            // Hacer la función loadTab accesible globalmente para el botón de reintentar
            window.loadTab = loadTab;
        });
    </script>





<script>
document.addEventListener("alpine:init", () => {
    Alpine.data("scrumboard", () => ({
        // Estado inicial
        params: {
            id: null,
            title: ''
        },
        paramsTask: {
            projectId: null,
            id: null,
            title: '',
            description: '',
            tags: '',
            image: null
        },
        selectedTask: null,
        selectedProject: null,
        isAddProjectModal: false,
        isAddTaskModal: false,
        isDeleteModal: false,
        isDeleteProject: false,
        projectList: [],

        // Inicialización
        init() {
            this.loadProjects();
            this.initializeSortable();
        },

        // Cargar proyectos desde el servidor
        loadProjects() {
            fetch('/scrumboard/projects')
                .then(response => response.json())
                .then(data => {
                    this.projectList = data.map(project => ({
                        ...project,
                        tasks: project.tasks || []
                    }));
                    this.initializeSortable();
                });
        },

        // Inicializar SortableJS para drag and drop
      initializeSortable() {
    setTimeout(() => {
        const sortableLists = document.querySelectorAll('.sortable-list');
        
        sortableLists.forEach(list => {
            Sortable.create(list, {
                group: 'tasks',
                animation: 150,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onEnd: (evt) => {
                    const projectId = parseInt(evt.to.getAttribute('data-id'));
                    const taskId = parseInt(evt.item.getAttribute('data-task-id')); // Cambiar a data-task-id
                    
                    if (evt.from !== evt.to) {
                        this.moveTask(taskId, projectId);
                    }
                }
            });
        });
    });
},

moveTask(taskId, newProjectId) {
    // Encontrar el proyecto actual de la tarea
    const currentProject = this.projectList.find(project => 
        project.tasks.some(task => task.id === taskId)
    );
    
    if (!currentProject) {
        console.error('No se encontró el proyecto actual de la tarea');
        this.showMessage('Error: No se pudo encontrar la tarea actual', 'error');
        this.loadProjects(); // Recargar para sincronizar
        return;
    }

    // Verificar que el movimiento sea a un proyecto diferente
    if (currentProject.id === newProjectId) {
        console.log('La tarea ya está en este proyecto');
        return;
    }

    // Mostrar mensaje de carga
    this.showMessage('Moviendo tarea...', 'info');

    fetch('/scrumboard/tasks/move', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            task_id: taskId,
            new_project_id: newProjectId
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            this.showMessage('Tarea movida exitosamente!');
            this.loadProjects(); // Recargar los proyectos
        } else {
            this.showMessage(data.message || 'Error al mover la tarea', 'error');
            this.loadProjects(); // Recargar para sincronizar estado
        }
    })
    .catch(error => {
        console.error('Error:', error);
        this.showMessage('Error al mover la tarea: ' + (error.message || 'Error desconocido'), 'error');
        this.loadProjects(); // Recargar para sincronizar estado
    });
},

        // Abrir modal para agregar/editar proyecto
        addEditProject(project = null) {
            this.params = {
                id: null,
                title: ''
            };
            
            if (project) {
                this.params = {
                    id: project.id,
                    title: project.title
                };
            }
            
            this.isAddProjectModal = true;
        },

        // Guardar proyecto (crear o actualizar)
        saveProject() {
            if (!this.params.title) {
                this.showMessage('Title is required.', 'error');
                return false;
            }

            const url = this.params.id 
                ? `/scrumboard/projects/${this.params.id}` 
                : '/scrumboard/projects';
                
            const method = this.params.id ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(this.params)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showMessage('Project has been saved successfully.');
                    this.isAddProjectModal = false;
                    this.loadProjects(); // Recargar la lista de proyectos
                } else {
                    this.showMessage(data.message || 'Error saving project', 'error');
                }
            })
            .catch(error => {
                this.showMessage('Error saving project', 'error');
                console.error('Error:', error);
            });
        },

        // Confirmar eliminación de proyecto
        deleteConfirmProject(project) {
            this.selectedProject = project;
            this.isDeleteProject = true;
            this.isDeleteModal = true;
        },

        // Eliminar proyecto
        deleteProject() {
            if (!this.selectedProject) return;
            
            fetch(`/scrumboard/projects/${this.selectedProject.id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showMessage('Project has been deleted successfully.');
                    this.isDeleteModal = false;
                    this.loadProjects(); // Recargar la lista de proyectos
                } else {
                    this.showMessage(data.message || 'Error deleting project', 'error');
                }
            })
            .catch(error => {
                this.showMessage('Error deleting project', 'error');
                console.error('Error:', error);
            });
        },

        // Limpiar todas las tareas de un proyecto
        clearProjects(project) {
            if (!confirm('Are you sure you want to clear all tasks from this project?')) {
                return;
            }
            
            fetch(`/scrumboard/projects/${project.id}/clear-tasks`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showMessage('All tasks have been cleared from the project.');
                    this.loadProjects(); // Recargar la lista de proyectos
                } else {
                    this.showMessage(data.message || 'Error clearing tasks', 'error');
                }
            })
            .catch(error => {
                this.showMessage('Error clearing tasks', 'error');
                console.error('Error:', error);
            });
        },

        // Abrir modal para agregar/editar tarea
        addEditTask(projectId, task = null) {
            this.paramsTask = {
                projectId: projectId,
                id: null,
                title: '',
                description: '',
                tags: '',
                image: null
            };
            
            if (task) {
                this.paramsTask = {
                    projectId: projectId,
                    id: task.id,
                    title: task.title,
                    description: task.description,
                    tags: task.tags ? task.tags.join(', ') : '',
                    image: task.image || null
                };
            }
            
            this.isAddTaskModal = true;
        },

        // Guardar tarea (crear o actualizar)
     saveTask() {
    if (!this.paramsTask.title) {
        this.showMessage('Title is required.', 'error');
        return false;
    }

    const url = this.paramsTask.id 
        ? `/scrumboard/tasks/${this.paramsTask.id}` 
        : '/scrumboard/tasks';
    const method = this.paramsTask.id ? 'PUT' : 'POST';

    // 1. Crear objeto con los datos
    const data = {
        title: this.paramsTask.title,
        description: this.paramsTask.description || '',
        tags: this.paramsTask.tags || ''
    };

    // 2. Solo agregar project_id para nuevas tareas
    if (!this.paramsTask.id) {
        data.project_id = this.paramsTask.projectId;
    }

    // 3. Configurar headers
    const headers = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
    };

    // 4. Opciones para fetch
    const options = {
        method: method,
        headers: headers,
        body: JSON.stringify(data)
    };

    // 5. Realizar la petición
    fetch(url, options)
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            this.showMessage('Task saved successfully!');
            this.isAddTaskModal = false;
            this.loadProjects();
            
            // Resetear el formulario solo para nuevas tareas
            if (!this.paramsTask.id) {
                this.paramsTask = {
                    projectId: this.paramsTask.projectId,
                    id: null,
                    title: '',
                    description: '',
                    tags: '',
                    image: null
                };
            }
        })
        .catch(error => {
            console.error('Error saving task:', error);
            this.showMessage(error.message || 'Error saving task', 'error');
        });
},

        // Confirmar eliminación de tarea
        deleteConfirmModal(projectId, task) {
            this.selectedTask = task;
            this.isDeleteProject = false;
            this.isDeleteModal = true;
        },

        // Eliminar tarea
        deleteTask() {
            if (!this.selectedTask) return;
            
            fetch(`/scrumboard/tasks/${this.selectedTask.id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showMessage('Task has been deleted successfully.');
                    this.isDeleteModal = false;
                    this.loadProjects(); // Recargar la lista de proyectos
                } else {
                    this.showMessage(data.message || 'Error deleting task', 'error');
                }
            })
            .catch(error => {
                this.showMessage('Error deleting task', 'error');
                console.error('Error:', error);
            });
        },

        // Mostrar mensajes de notificación
        showMessage(msg = '', type = 'success') {
            const toast = window.Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 3000,
            });
            toast.fire({
                icon: type,
                title: msg,
                padding: '10px 20px',
            });
        }
    }));
});
</script>

  <script>
document.addEventListener("alpine:init", () => {
    Alpine.data("notes", () => ({
        defaultParams: {
            id: null,
            title: '',
            description: '',
            tag_id: null,
            is_favorite: false
        },
        isAddNoteModal: false,
        isDeleteNoteModal: false,
        isViewNoteModal: false,
        params: {
            id: null,
            title: '',
            description: '',
            tag_id: null,
            is_favorite: false
        },
        isShowNoteMenu: false,
        notesList: [],
        tagsList: [],
        filterdNotesList: [],
        selectedTab: 'all',
        deletedNote: null,
        selectedNote: {
            id: null,
            title: '',
            description: '',
            tag: '',
            tag_color: '',
            user: '',
            date: '',
            isFav: false
        },
        loading: false,

        init() {
            this.loadTags();
            this.loadNotes();
        },

        async loadTags() {
            try {
                const response = await fetch('/tags', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.tagsList = data.tags;
                }
            } catch (error) {
                console.error('Error loading tags:', error);
                this.showMessage('Error loading tags', 'error');
            }
        },

        async loadNotes(filter = 'all') {
            this.loading = true;
            try {
                const url = filter && filter !== 'all' ? `/notes?filter=${filter}` : '/notes';
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.notesList = data.notes.map(note => ({
                        id: note.id,
                        title: note.title,
                        description: note.description,
                        isFav: note.is_favorite,
                        tag: note.tag,
                        tag_color: note.tag_color,
                        date: note.date,
                        user: note.user
                    }));
                    this.searchNotes();
                } else {
                    throw new Error('Failed to load notes');
                }
            } catch (error) {
                console.error('Error loading notes:', error);
                this.showMessage('Error loading notes', 'error');
            } finally {
                this.loading = false;
            }
        },

        searchNotes() {
            if (this.selectedTab === 'fav') {
                this.filterdNotesList = this.notesList.filter((d) => d.isFav);
            } else if (this.selectedTab === 'all') {
                this.filterdNotesList = this.notesList;
            } else {
                this.filterdNotesList = this.notesList.filter((d) => d.tag === this.selectedTab);
            }
        },

        async saveNote() {
            if (!this.params.title.trim()) {
                this.showMessage('Title is required.', 'error');
                return false;
            }

            this.loading = true;
            try {
                const url = this.params.id ? `/notes/${this.params.id}` : '/notes';
                const method = this.params.id ? 'PUT' : 'POST';
                
                const formData = new FormData();
                formData.append('title', this.params.title);
                formData.append('description', this.params.description || '');
                formData.append('tag_id', this.params.tag_id || '');
                formData.append('is_favorite', this.params.is_favorite ? '1' : '0');
                
                if (this.params.id) {
                    formData.append('_method', 'PUT');
                }

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                if (response.ok) {
                    const data = await response.json();
                    this.showMessage(data.message || 'Note saved successfully.');
                    this.isAddNoteModal = false;
                    this.resetParams();
                    await this.loadNotes(this.selectedTab);
                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to save note');
                }
            } catch (error) {
                console.error('Error saving note:', error);
                this.showMessage('Error saving note: ' + error.message, 'error');
            } finally {
                this.loading = false;
            }
        },

        async tabChanged(type) {
            this.selectedTab = type;
            await this.loadNotes(type);
            this.isShowNoteMenu = false;
        },

        async setFav(note) {
            try {
                const response = await fetch(`/notes/${note.id}/toggle-favorite`, {
                    method: 'PATCH',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    // Update in local list
                    let item = this.filterdNotesList.find((d) => d.id === note.id);
                    if (item) {
                        item.isFav = data.is_favorite;
                    }
                    // Also update in main list
                    let mainItem = this.notesList.find((d) => d.id === note.id);
                    if (mainItem) {
                        mainItem.isFav = data.is_favorite;
                    }
                    this.searchNotes();
                    this.showMessage(data.message);
                } else {
                    throw new Error('Failed to update favorite status');
                }
            } catch (error) {
                console.error('Error updating favorite:', error);
                this.showMessage('Error updating favorite status', 'error');
            }
        },

        async setTag(note, tagName) {
            try {
                const tag = this.tagsList.find(t => t.name === tagName);
                const tag_id = tag ? tag.id : null;

                const response = await fetch(`/notes/${note.id}/update-tag`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ tag_id: tag_id })
                });

                if (response.ok) {
                    const data = await response.json();
                    // Update in local lists
                    [this.notesList, this.filterdNotesList].forEach(list => {
                        let item = list.find((d) => d.id === note.id);
                        if (item) {
                            item.tag = data.tag;
                            item.tag_color = data.tag_color;
                        }
                    });
                    this.searchNotes();
                    this.showMessage(data.message);
                } else {
                    throw new Error('Failed to update tag');
                }
            } catch (error) {
                console.error('Error updating tag:', error);
                this.showMessage('Error updating tag', 'error');
            }
        },

        deleteNoteConfirm(note) {
            this.deletedNote = note;
            this.isDeleteNoteModal = true;
        },

        viewNote(note) {
            this.selectedNote = {
                id: note.id,
                title: note.title,
                description: note.description,
                tag: note.tag,
                tag_color: note.tag_color,
                user: note.user,
                date: note.date,
                isFav: note.isFav
            };
            this.isViewNoteModal = true;
        },

        editNote(note = null) {
            this.isShowNoteMenu = false;
            this.resetParams();
            
            if (note) {
                this.params = {
                    id: note.id,
                    title: note.title,
                    description: note.description,
                    tag_id: this.tagsList.find(t => t.name === note.tag)?.id || null,
                    is_favorite: note.isFav || false
                };
            }
            this.isAddNoteModal = true;
        },

        async deleteNote() {
            if (!this.deletedNote) return;

            this.loading = true;
            try {
                const response = await fetch(`/notes/${this.deletedNote.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    this.showMessage(data.message || 'Note deleted successfully.');
                    this.isDeleteNoteModal = false;
                    this.deletedNote = null;
                    await this.loadNotes(this.selectedTab);
                } else {
                    throw new Error('Failed to delete note');
                }
            } catch (error) {
                console.error('Error deleting note:', error);
                this.showMessage('Error deleting note', 'error');
            } finally {
                this.loading = false;
            }
        },

        resetParams() {
            this.params = JSON.parse(JSON.stringify(this.defaultParams));
        },

        getTagNameById(tagId) {
            if (!tagId) return '';
            const tag = this.tagsList.find(t => t.id === tagId);
            return tag ? tag.name : '';
        },

        getTagColorById(tagId) {
            if (!tagId) return '';
            const tag = this.tagsList.find(t => t.id === tagId);
            return tag ? tag.color : '';
        },

        showMessage(msg = '', type = 'success') {
            if (window.Swal) {
                const toast = window.Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                });
                toast.fire({
                    icon: type,
                    title: msg,
                    padding: '10px 20px',
                });
            } else {
                // Fallback if no SweetAlert
                alert(msg);
            }
        }
    }));
});
</script>
        <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("calendar", () => ({
                defaultParams: ({
                    id: null,
                    title: '',
                    start: '',
                    end: '',
                    description: '',
                    type: 'primary'
                }),
                params: {
                    id: null,
                    title: '',
                    start: '',
                    end: '',
                    description: '',
                    type: 'primary'
                },
                isAddEventModal: false,
                minStartDate: '',
                minEndDate: '',
                calendar: null,
                now: new Date(),
                events: [],
                init() {
                    this.events = [{
                            id: 1,
                            title: 'All Day Event',
                            start: this.now.getFullYear() + '-' + this.getMonth(this.now) +
                                '-01T14:30:00',
                            end: this.now.getFullYear() + '-' + this.getMonth(this.now) +
                                '-02T14:30:00',
                            className: 'danger',
                            description: 'Aenean fermentum quam vel sapien rutrum cursus. Vestibulum imperdiet finibus odio, nec tincidunt felis facilisis eu.',
                        },
                        {
                            id: 2,
                            title: 'Site Visit',
                            start: this.now.getFullYear() + '-' + this.getMonth(this.now) +
                                '-07T19:30:00',
                            end: this.now.getFullYear() + '-' + this.getMonth(this.now) +
                                '-08T14:30:00',
                            className: 'primary',
                            description: 'Etiam a odio eget enim aliquet laoreet. Vivamus auctor nunc ultrices varius lobortis.',
                        },
                        {
                            id: 3,
                            title: 'Product Lunching Event',
                            start: this.now.getFullYear() + '-' + this.getMonth(this.now) +
                                '-17T14:30:00',
                            end: this.now.getFullYear() + '-' + this.getMonth(this.now) +
                                '-18T14:30:00',
                            className: 'info',
                            description: 'Proin et consectetur nibh. Mauris et mollis purus. Ut nec tincidunt lacus. Nam at rutrum justo, vitae egestas dolor.',
                        },
                       
                        {
                            id: 13,
                            title: 'Upcoming Event',
                            start: this.now.getFullYear() + '-' + this.getMonth(this.now, 1) +
                                '-15T08:12:14',
                            end: this.now.getFullYear() + '-' + this.getMonth(this.now, 1) +
                                '-18T22:20:20',
                            className: 'primary',
                            description: 'Pellentesque ut convallis velit. Sed purus urna, aliquam et pharetra ut, efficitur id mi. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        },
                    ];
                    var calendarEl = document.getElementById('calendar');
                    this.calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay',
                        },
                        editable: true,
                        dayMaxEvents: true,
                        selectable: true,
                        droppable: true,
                        eventClick: (event) => {
                            this.editEvent(event);
                        },
                        select: (event) => {
                            this.editDate(event)
                        },
                        events: this.events,
                    });
                    this.calendar.render();

                    this.$watch('$store.app.sidebar', () => {
                        setTimeout(() => {
                            this.calendar.render();
                        }, 300);
                    });
                },

                getMonth(dt, add = 0) {
                    let month = dt.getMonth() + 1 + add;
                    return dt.getMonth() < 10 ? '0' + month : month;
                },

                editEvent(data) {
                    this.params = JSON.parse(JSON.stringify(this.defaultParams));
                    if (data) {
                        let obj = JSON.parse(JSON.stringify(data.event));
                        this.params = {
                            id: obj.id ? obj.id : null,
                            title: obj.title ? obj.title : null,
                            start: this.dateFormat(obj.start),
                            end: this.dateFormat(obj.end),
                            type: obj.classNames ? obj.classNames[0] : 'primary',
                            description: obj.extendedProps ? obj.extendedProps.description : '',
                        };
                        this.minStartDate = new Date();
                        this.minEndDate = this.dateFormat(obj.start);
                    } else {
                        this.minStartDate = new Date();
                        this.minEndDate = new Date();
                    }

                    this.isAddEventModal = true;
                },

                editDate(data) {
                    let obj = {
                        event: {
                            start: data.start,
                            end: data.end
                        },
                    };
                    this.editEvent(obj);
                },

                dateFormat(dt) {
                    dt = new Date(dt);
                    const month = dt.getMonth() + 1 < 10 ? '0' + (dt.getMonth() + 1) : dt.getMonth() +
                    1;
                    const date = dt.getDate() < 10 ? '0' + dt.getDate() : dt.getDate();
                    const hours = dt.getHours() < 10 ? '0' + dt.getHours() : dt.getHours();
                    const mins = dt.getMinutes() < 10 ? '0' + dt.getMinutes() : dt.getMinutes();
                    dt = dt.getFullYear() + '-' + month + '-' + date + 'T' + hours + ':' + mins;
                    return dt;
                },

                saveEvent() {
                    if (!this.params.title) {
                        return true;
                    }
                    if (!this.params.start) {
                        return true;
                    }
                    if (!this.params.end) {
                        return true;
                    }

                    if (this.params.id) {
                        //update event
                        let event = this.events.find((d) => d.id == this.params.id);
                        event.title = this.params.title;
                        event.start = this.params.start;
                        event.end = this.params.end;
                        event.description = this.params.description;
                        event.className = this.params.type;
                    } else {
                        //add event
                        let maxEventId = 0;
                        if (this.events) {
                            maxEventId = this.events.reduce(
                                (max, character) => (character.id > max ? character.id : max),
                                this.events[0].id
                            );
                        }

                        let event = {
                            id: maxEventId + 1,
                            title: this.params.title,
                            start: this.params.start,
                            end: this.params.end,
                            description: this.params.description,
                            className: this.params.type,
                        };
                        this.events.push(event);
                    }
                    this.calendar.getEventSources()[0].refetch() //refresh Calendar
                    this.showMessage('Event has been saved successfully.');
                    this.isAddEventModal = false;
                },

                startDateChange(event) {
                    const dateStr = event.target.value;
                    if (dateStr) {
                        this.minEndDate = this.dateFormat(dateStr);
                        this.params.end = '';
                    }
                },

                showMessage(msg = '', type = 'success') {
                    const toast = window.Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                    });
                    toast.fire({
                        icon: type,
                        title: msg,
                        padding: '10px 20px',
                    });
                }
            }));
        });
    </script>

            <script src="{{ asset('assets/js/areacomercial/actualizarcliente.js') }}"></script>


</x-layout.default>
