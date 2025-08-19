document.addEventListener('alpine:init', () => {
    Alpine.data('scrumboard', () => ({
                idSeguimiento: document.getElementById('idSeguimientoHidden')?.value || '',
                idPersona: document.getElementById('idPersonaHidden')?.value || '',


        // Estado inicial
        params: {
            id: null,
            title: '',
        },
        paramsTask: {
            projectId: null,
            id: null,
            title: '',
            description: '',
            tags: '',
            image: null,
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
    fetch(`/scrumboard/projects?seguimiento=${this.idSeguimiento}&idpersona=${this.idPersona}`)
        .then((response) => response.json())
        .then((data) => {
            this.projectList = data.map((project) => ({
                ...project,
                tasks: project.tasks || [],
            }));
            this.initializeSortable();
        });
},


        // Inicializar SortableJS para drag and drop
        initializeSortable() {
            setTimeout(() => {
                const sortableLists = document.querySelectorAll('.sortable-list');

                sortableLists.forEach((list) => {
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
                        },
                    });
                });
            });
        },

        moveTask(taskId, newProjectId) {
            // Encontrar el proyecto actual de la tarea
            const currentProject = this.projectList.find((project) => project.tasks.some((task) => task.id === taskId));

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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    task_id: taskId,
                    new_project_id: newProjectId,
                }),
            })
                .then((response) => {
                    if (!response.ok) {
                        return response.json().then((err) => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        this.showMessage('Tarea movida exitosamente!');
                        this.loadProjects(); // Recargar los proyectos
                    } else {
                        this.showMessage(data.message || 'Error al mover la tarea', 'error');
                        this.loadProjects(); // Recargar para sincronizar estado
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    this.showMessage('Error al mover la tarea: ' + (error.message || 'Error desconocido'), 'error');
                    this.loadProjects(); // Recargar para sincronizar estado
                });
        },

        // Abrir modal para agregar/editar proyecto
        addEditProject(project = null) {
            this.params = {
                id: null,
                title: '',
            };

            if (project) {
                this.params = {
                    id: project.id,
                    title: project.title,
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

            const url = this.params.id ? `/scrumboard/projects/${this.params.id}` : '/scrumboard/projects';
            const method = this.params.id ? 'PUT' : 'POST';

            // Agregar idSeguimiento al objeto de datos
            const data = {
                ...this.params,
                idseguimiento: this.idSeguimiento
            };

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify(data),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        this.showMessage('Project has been saved successfully.');
                        this.isAddProjectModal = false;
                        this.loadProjects(); // Recargar la lista de proyectos
                    } else {
                        this.showMessage(data.message || 'Error saving project', 'error');
                    }
                })
                .catch((error) => {
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        this.showMessage('Project has been deleted successfully.');
                        this.isDeleteModal = false;
                        this.loadProjects(); // Recargar la lista de proyectos
                    } else {
                        this.showMessage(data.message || 'Error deleting project', 'error');
                    }
                })
                .catch((error) => {
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        this.showMessage('All tasks have been cleared from the project.');
                        this.loadProjects(); // Recargar la lista de proyectos
                    } else {
                        this.showMessage(data.message || 'Error clearing tasks', 'error');
                    }
                })
                .catch((error) => {
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
                image: null,
            };

            if (task) {
                this.paramsTask = {
                    projectId: projectId,
                    id: task.id,
                    title: task.title,
                    description: task.description,
                    tags: task.tags ? task.tags.join(', ') : '',
                    image: task.image || null,
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

            const url = this.paramsTask.id ? `/scrumboard/tasks/${this.paramsTask.id}` : '/scrumboard/tasks';
            const method = this.paramsTask.id ? 'PUT' : 'POST';

            const data = {
                title: this.paramsTask.title,
                description: this.paramsTask.description || '',
                tags: this.paramsTask.tags || '',
                idseguimiento: this.idSeguimiento // Agregar idSeguimiento
            };

            if (!this.paramsTask.id) {
                data.project_id = this.paramsTask.projectId;
            }
            // 3. Configurar headers
            const headers = {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                Accept: 'application/json',
            };

            // 4. Opciones para fetch
            const options = {
                method: method,
                headers: headers,
                body: JSON.stringify(data),
            };

            // 5. Realizar la petición
            fetch(url, options)
                .then((response) => {
                    if (!response.ok) {
                        return response.json().then((err) => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then((data) => {
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
                            image: null,
                        };
                    }
                })
                .catch((error) => {
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        this.showMessage('Task has been deleted successfully.');
                        this.isDeleteModal = false;
                        this.loadProjects(); // Recargar la lista de proyectos
                    } else {
                        this.showMessage(data.message || 'Error deleting task', 'error');
                    }
                })
                .catch((error) => {
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
        },
    }));
});
