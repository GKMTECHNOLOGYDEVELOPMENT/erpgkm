document.addEventListener('alpine:init', () => {
    Alpine.data('scrumboard', () => ({
        idSeguimiento: document.getElementById('idSeguimientoHidden')?.value || '',
        idPersona: document.getElementById('idPersonaHidden')?.value || '',
        activeTab: 'general', // Agregar esta variable
        currentProjectName: '', // Agregar esta variable para almacenar el nombre del proyecto actual
        filtroUbicacion: '',
        filtroFechalevantamiento: '',
        filtroEstadoObservado: '',
        filtroFechaObservado: '',
        filtroCodigoGanado: '',
        filtroTipoServicio: '',
        filtroFechaGanado: '',
        filtroCodigo: '',
        filtroResponsable: '',
        filtroMotivoRechazo: '',
        filtroFechaRechazo: '',
        filtroFecha: '',
        filteredCotizaciones: '',
        filteredReuniones: '',
        nivelPorcentajeCotizacion: '', // 0, 50, 100 (Inicial, En proceso, Finalizado)
        nivelPorcentajeReunion: '', // 0, 50, 100 (Inicial, En proceso, Finalizado)
        nivelPorcentajeLevantamiento: '', // 0, 50, 100 (Inicial, En proceso, Finalizado)
        nivelPorcentajeGanado: '', // 0, 50, 100 (Inicial, En proceso, Finalizado)
        nivelPorcentajeObservado: '', // 0, 50, 100 (Inicial, En proceso, Finalizado)
        nivelPorcentajeRechazado: '', // 0, 50, 100 (Inicial, En proceso, Finalizado)
        cotizacionEditId: null, // Para controlar edición
        filtroTipoReunion: '',
        filtroResponsableReunion: '',
        filtroFechaReunion: '',
        reunionEditId: null,
        levantamientoEditId: null,
        ganadoEditId: null,
        observadoEditId: null,
        rechazadoEditId: null,

        tarea: null,

        mostrarModalDetalles: false,
        detalleSeleccionado: null,

        // Arrays para almacenar los datos
        levantamientos: [],
        ganados: [],
        observados: [],
        rechazados: [],
        cotizaciones: [], // ← ESTA ES LA QUE FALTA
        reuniones: [],

        estadoOpciones: ['Lista de proyectos', 'cotizacion', 'reunion', 'levantamiento', 'observado', 'ganado', 'rechazado'],

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
            //CAMPOS COTIZACION
            fechaCotizacion: '', // Nuevo campo para fecha de cotización
            codigoCotizacion: '', // Nuevo campo para cotización
            detalleproducto: '', // Nuevo campo para detalle de producto
            condicionescomerciales: '', // Nuevo campo para condiciones comerciales
            totalcotizacion: '', // Nuevo campo para total de cotización
            validezcotizacion: '', // Nuevo campo para validez de cotización
            responsablecotizacion: '', // Nuevo campo para responsable de cotización
            observacionescotizacion: '', // Nuevo campo para observaciones de cotización
            nivelPorcentajeCotizacion: '', // 0, 50, 100 (Inicial, En proceso, Finalizado)
            fechareunion: '', // Nuevo campo para fecha de reunión
            tiporeunion: '', // Nuevo campo para tipo de reunión
            motivoreunion: '', // Nuevo campo para motivo de reunión
            participantesreunion: [], // Nuevo campo para participantes de reunión
            responsablereunion: '', // Nuevo campo para responsable de reunión
            linkreunion: '', // Nuevo campo para link de reunión
            direccionfisica: '', // Nuevo campo para dirección de reunión
            minutareunion: '', // Nuevo campo para minuta de reunión
            nivelPorcentajeReunion: '', //CAMPOS REUNION
            actividadesReunion: '', // Nuevo campo para reunión
            //CAMPOS LEVANTAMIENTO DE INFORMACION
            fecharequerimiento: '', // Nuevo campo para fecha de requerimiento
            descripcionrequerimiento: '', // Nuevo campo para requerimiento
            participanteslevantamiento: '', // Nuevo campo para participantes de levantamiento
            ubicacionlevantamiento: '', // Nuevo campo para ubicación de levantamiento
            observacioneslevantamiento: '', // Nuevo campo para observaciones de levantamiento
            //CAMPOS GANADO
            fechaganado: '', // Nuevo campo para fecha ganado
            codigoCotizacion: '', // Nuevo campo para código de cotización
            tiporelacion: '', // Nuevo campo para tipo de relación
            tiposervicio: '', // Nuevo campo para tipo de servicio
            valorganado: '', // Nuevo campo para valor ganado
            formacierre: '', // Nuevo campo para forma de cierre
            duraciondelacuerdo: '', // Nuevo campo para duración del acuerdo
            observacionesganado: '', // Nuevo campo para observaciones de ganado
            //CAMPOS OBSERVADO
            fechaobservado: '', // Nuevo campo para fecha observado
            estadoactual: '', // Nuevo campo para estado actual
            detallesobservado: '', // Nuevo campo para detalles observado
            comentariosobservado: '', // Nuevo campo para comentarios observado
            accionespendientes: '', // Nuevo campo para acciones pendientes
            detalleobservado: '', // Nuevo campo para detalle observado
            //CAMPOS RECHAZO
            fecharechazo: '', // Nuevo campo para fecha de rechazo
            motivorechazo: '', // Nuevo campo para motivo de rechazo
            comentarioscliente: '', // Nuevo campo para comentarios del cliente
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
        // Computed property para filtrar las cotizaciones
        get filteredCotizaciones() {
            return this.cotizaciones.filter((cotizacion) => {
                const matchesCodigo = !this.filtroCodigo || cotizacion.codigo_cotizacion.toLowerCase().includes(this.filtroCodigo.toLowerCase());
                const matchesResponsable =
                    !this.filtroResponsable || cotizacion.responsable_cotizacion.toLowerCase().includes(this.filtroResponsable.toLowerCase());
                const matchesFecha = !this.filtroFecha || cotizacion.fecha_cotizacion === this.filtroFecha;

                return matchesCodigo && matchesResponsable && matchesFecha;
            });
        },

        get estadosDisponibles() {
            const usados = this.projectList.map((p) => p.title);
            return this.estadoOpciones.filter((e) => !usados.includes(e));
        },

        // Funciones de utilidad
        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES');
        },

        formatCurrency(amount) {
            if (!amount) return '-';
            return new Intl.NumberFormat('es-PE', {
                style: 'currency',
                currency: 'PEN',
            }).format(amount);
        },

        // Método para agregar cotización
        // Método para agregar o actualizar cotización
        agregarCotizacion() {
            // Validar que haya al menos un campo lleno
            const hasData =
                this.paramsTask.codigoCotizacion || this.paramsTask.fechaCotizacion || this.paramsTask.detalleproducto || this.paramsTask.totalcotizacion;

            if (!hasData) {
                this.showMessage('Debe ingresar al menos un campo para la cotización', 'error');
                return;
            }

            const url = '/scrumboard/cotizaciones/handle';
            const method = 'POST';

            const data = {
                task_id: this.paramsTask.id,
                codigo_cotizacion: this.paramsTask.codigoCotizacion,
                fecha_cotizacion: this.paramsTask.fechaCotizacion,
                detalle_producto: this.paramsTask.detalleproducto,
                condiciones_comerciales: this.paramsTask.condicionescomerciales,
                total_cotizacion: this.paramsTask.totalcotizacion,
                validez_cotizacion: this.paramsTask.validezcotizacion,
                responsable_cotizacion: this.paramsTask.responsablecotizacion,
                observaciones: this.paramsTask.observacionescotizacion,
                nivelPorcentajeCotizacion: this.paramsTask.nivelPorcentajeCotizacion,
                cotizacion_id: this.cotizacionEditId || null, // Incluir ID si estamos editando
            };

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(data),
            })
                .then((response) => {
                    // Verificar si la respuesta es JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('El servidor devolvió una respuesta no válida');
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        if (this.cotizacionEditId) {
                            // Actualizar en la lista
                            const index = this.cotizaciones.findIndex((c) => c.id === this.cotizacionEditId);
                            if (index !== -1) {
                                this.cotizaciones[index] = data.cotizacion;
                            }
                            this.showMessage(data.message || 'Cotización actualizada correctamente');
                        } else {
                            // Agregar a la lista
                            this.cotizaciones.push(data.cotizacion);
                            this.showMessage(data.message || 'Cotización agregada correctamente');
                        }

                        // Limpiar formulario
                        this.limpiarFormularioCotizacion();
                    } else {
                        throw new Error(data.message || 'Error desconocido');
                    }
                })
                .catch((error) => {
                    console.error('Error saving cotizacion:', error);
                    this.showMessage(error.message || 'Error al guardar la cotización', 'error');
                });
        },
        // Limpiar formulario de cotización
        limpiarFormularioCotizacion() {
            this.paramsTask.codigoCotizacion = '';
            this.paramsTask.fechaCotizacion = '';
            this.paramsTask.detalleproducto = '';
            this.paramsTask.condicionescomerciales = '';
            this.paramsTask.totalcotizacion = '';
            this.paramsTask.validezcotizacion = '';
            this.paramsTask.responsablecotizacion = '';
            this.paramsTask.observacionescotizacion = '';
            this.cotizacionEditId = null;
            this.paramsTask.nivelPorcentajeCotizacion = '';
        },

        // Editar cotización
        editarCotizacion(cotizacion) {
            this.paramsTask.codigoCotizacion = cotizacion.codigo_cotizacion || '';
            this.paramsTask.fechaCotizacion = cotizacion.fecha_cotizacion || '';
            this.paramsTask.detalleproducto = cotizacion.detalle_producto || '';
            this.paramsTask.condicionescomerciales = cotizacion.condiciones_comerciales || '';
            this.paramsTask.totalcotizacion = cotizacion.total_cotizacion || '';
            this.paramsTask.validezcotizacion = cotizacion.validez_cotizacion || '';
            this.paramsTask.responsablecotizacion = cotizacion.responsable_cotizacion || '';
            this.paramsTask.observacionescotizacion = cotizacion.observaciones || '';
            this.paramsTask.nivelPorcentajeCotizacion = cotizacion.nivel_porcentaje || '';

            this.cotizacionEditId = cotizacion.id;
        },

        // Eliminar cotización
        eliminarCotizacion(id) {
            if (confirm('¿Estás seguro de eliminar esta cotización?')) {
                fetch(`/scrumboard/cotizaciones/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            this.cotizaciones = this.cotizaciones.filter((c) => c.id !== id);
                            this.showMessage('Cotización eliminada correctamente');
                        }
                    })
                    .catch((error) => {
                        console.error('Error deleting cotizacion:', error);
                        this.showMessage('Error al eliminar la cotización', 'error');
                    });
            }
        },
        // Cargar cotizaciones de la tarea
        loadCotizaciones(taskId) {
            if (!taskId) return;

            fetch(`/scrumboard/tasks/${taskId}/cotizaciones`)
                .then((response) => response.json())
                .then((data) => {
                    this.cotizaciones = data.cotizaciones || [];
                })
                .catch((error) => {
                    console.error('Error loading cotizaciones:', error);
                    this.cotizaciones = [];
                });
        },

        // Método para agregar o actualizar reunión
        agregarReunion() {
            // Validar que haya al menos un campo lleno
            const hasData = this.paramsTask.fechareunion || this.paramsTask.tiporeunion || this.paramsTask.motivoreunion;

            if (!hasData) {
                this.showMessage('Debe ingresar al menos un campo para la reunión', 'error');
                return;
            }

            const url = '/scrumboard/reuniones/handle';
            const method = 'POST';

            const data = {
                task_id: this.paramsTask.id,
                fecha_reunion: this.paramsTask.fechareunion,
                tipo_reunion: this.paramsTask.tiporeunion,
                motivo_reunion: this.paramsTask.motivoreunion,
                participantes: this.paramsTask.participantesreunion,
                responsable_reunion: this.paramsTask.responsablereunion,
                link_reunion: this.paramsTask.linkreunion,
                direccion_fisica: this.paramsTask.direccionfisica,
                minuta: this.paramsTask.minutareunion,
                actividades: this.paramsTask.actividadesReunion,
                nivelPorcentajeReunion: this.paramsTask.nivelPorcentajeReunion,
                reunion_id: this.reunionEditId || null,
            };

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(data),
            })
                .then((response) => {
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('El servidor devolvió una respuesta no válida');
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        if (this.reunionEditId) {
                            // Actualizar en la lista
                            const index = this.reuniones.findIndex((r) => r.id === this.reunionEditId);
                            if (index !== -1) {
                                this.reuniones[index] = data.reunion;
                            }
                            this.showMessage(data.message || 'Reunión actualizada correctamente');
                        } else {
                            // Agregar a la lista
                            this.reuniones.push(data.reunion);
                            this.showMessage(data.message || 'Reunión agregada correctamente');
                        }

                        // Limpiar formulario
                        this.limpiarFormularioReunion();
                    } else {
                        throw new Error(data.message || 'Error desconocido');
                    }
                })
                .catch((error) => {
                    console.error('Error saving reunion:', error);
                    this.showMessage(error.message || 'Error al guardar la reunión', 'error');
                });
        },

        // Limpiar formulario de reunión
        limpiarFormularioReunion() {
            this.paramsTask.fechareunion = '';
            this.paramsTask.tiporeunion = '';
            this.paramsTask.motivoreunion = '';
            this.paramsTask.participantesreunion = '';
            this.paramsTask.responsablereunion = '';
            this.paramsTask.linkreunion = '';
            this.paramsTask.direccionfisica = '';
            this.paramsTask.minutareunion = '';
            this.paramsTask.actividadesReunion = '';
            this.paramsTask.nivelPorcentajeReunion = '';
            this.reunionEditId = null;
        },

        // Editar reunión
        editarReunion(reunion) {
            this.paramsTask.fechareunion = reunion.fecha_reunion || '';
            this.paramsTask.tiporeunion = reunion.tipo_reunion || '';
            this.paramsTask.motivoreunion = reunion.motivo_reunion || '';
            this.paramsTask.participantesreunion = reunion.participantes || '';
            this.paramsTask.responsablereunion = reunion.responsable_reunion || '';
            this.paramsTask.linkreunion = reunion.link_reunion || '';
            this.paramsTask.direccionfisica = reunion.direccion_fisica || '';
            this.paramsTask.minutareunion = reunion.minuta || '';
            this.paramsTask.actividadesReunion = reunion.actividades || '';
            this.paramsTask.nivelPorcentajeReunion = reunion.nivel_porcentaje || '';

            this.reunionEditId = reunion.id;
        },

        // Eliminar reunión
        eliminarReunion(id) {
            if (confirm('¿Estás seguro de eliminar esta reunión?')) {
                fetch(`/scrumboard/reuniones/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
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
                            this.reuniones = this.reuniones.filter((r) => r.id !== id);
                            this.showMessage('Reunión eliminada correctamente');
                        } else {
                            throw new Error(data.message || 'Error al eliminar');
                        }
                    })
                    .catch((error) => {
                        console.error('Error deleting reunion:', error);
                        this.showMessage(error.message || 'Error al eliminar la reunión', 'error');
                    });
            }
        },

        loadTarea(taskId) {
            if (!taskId) {
                console.error('❌ No se proporcionó taskId');
                this.tarea = null;
                return;
            }

            console.log('🔄 Cargando tarea con ID:', taskId);
            console.log('📋 URL de fetch:', `/tarea/${taskId}`);

            fetch(`/tarea/${taskId}`)
                .then((response) => {
                    console.log('📡 Respuesta del servidor:', response.status, response.statusText);
                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log('✅ Tarea cargada exitosamente:', data);
                    this.tarea = data;
                })
                .catch((error) => {
                    console.error('❌ Error al cargar la tarea:', error);
                    this.tarea = null;
                });
        },

        // En tu Alpine.js data
        verDetalles(relacion) {
            console.log('📋 Detalles de:', relacion.tipo, relacion);

            // Asigna los datos y muestra el modal
            this.detalleSeleccionado = relacion;
            this.mostrarModalDetalles = true;
        },
        // Cargar reuniones de la tarea
        loadReuniones(taskId) {
            if (!taskId) {
                this.reuniones = [];
                return;
            }

            fetch(`/scrumboard/tasks/${taskId}/reuniones`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Error al cargar reuniones');
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        this.reuniones = data.reuniones || [];
                    } else {
                        throw new Error(data.message || 'Error al cargar reuniones');
                    }
                })
                .catch((error) => {
                    console.error('Error loading reuniones:', error);
                    this.reuniones = [];
                    this.showMessage('Error al cargar las reuniones', 'error');
                });
        },

        // Método para agregar o actualizar levantamiento
        agregarLevantamiento() {
            // Validar que haya al menos un campo lleno
            const hasData = this.paramsTask.fecharequerimiento || this.paramsTask.participanteslevantamiento || this.paramsTask.ubicacionlevantamiento;

            if (!hasData) {
                this.showMessage('Debe ingresar al menos un campo para el levantamiento', 'error');
                return;
            }

            const url = '/scrumboard/levantamientos/handle';
            const method = 'POST';

            const data = {
                task_id: this.paramsTask.id,
                fecha_requerimiento: this.paramsTask.fecharequerimiento,
                participantes: this.paramsTask.participanteslevantamiento,
                ubicacion: this.paramsTask.ubicacionlevantamiento,
                descripcion_requerimiento: this.paramsTask.descripcionrequerimiento,
                observaciones: this.paramsTask.observacioneslevantamiento,
                levantamiento_id: this.levantamientoEditId || null,
                nivelPorcentajeLevantamiento: this.paramsTask.nivelPorcentajeLevantamiento,
            };

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(data),
            })
                .then((response) => {
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('El servidor devolvió una respuesta no válida');
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        if (this.levantamientoEditId) {
                            // Actualizar en la lista
                            const index = this.levantamientos.findIndex((l) => l.id === this.levantamientoEditId);
                            if (index !== -1) {
                                this.levantamientos[index] = data.levantamiento;
                            }
                            this.showMessage(data.message || 'Levantamiento actualizado correctamente');
                        } else {
                            // Agregar a la lista
                            this.levantamientos.push(data.levantamiento);
                            this.showMessage(data.message || 'Levantamiento agregado correctamente');
                        }

                        // Limpiar formulario
                        this.limpiarFormularioLevantamiento();
                    } else {
                        throw new Error(data.message || 'Error desconocido');
                    }
                })
                .catch((error) => {
                    console.error('Error saving levantamiento:', error);
                    this.showMessage(error.message || 'Error al guardar el levantamiento', 'error');
                });
        },

        // Limpiar formulario de levantamiento
        limpiarFormularioLevantamiento() {
            this.paramsTask.fecharequerimiento = '';
            this.paramsTask.participanteslevantamiento = '';
            this.paramsTask.ubicacionlevantamiento = '';
            this.paramsTask.descripcionrequerimiento = '';
            this.paramsTask.observacioneslevantamiento = '';
            this.levantamientoEditId = null;
            this.paramsTask.nivelPorcentajeLevantamiento = '';
        },

        // Editar levantamiento
        editarLevantamiento(levantamiento) {
            this.paramsTask.fecharequerimiento = levantamiento.fecha_requerimiento || '';
            this.paramsTask.participanteslevantamiento = levantamiento.participantes || '';
            this.paramsTask.ubicacionlevantamiento = levantamiento.ubicacion || '';
            this.paramsTask.descripcionrequerimiento = levantamiento.descripcion_requerimiento || '';
            this.paramsTask.observacioneslevantamiento = levantamiento.observaciones || '';
            this.paramsTask.nivelPorcentajeLevantamiento = levantamiento.nivel_porcentaje || '';
            this.levantamientoEditId = levantamiento.id;
        },

        // Eliminar levantamiento
        eliminarLevantamiento(id) {
            if (confirm('¿Estás seguro de eliminar este levantamiento?')) {
                fetch(`/scrumboard/levantamientos/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
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
                            this.levantamientos = this.levantamientos.filter((l) => l.id !== id);
                            this.showMessage('Levantamiento eliminado correctamente');
                        } else {
                            throw new Error(data.message || 'Error al eliminar');
                        }
                    })
                    .catch((error) => {
                        console.error('Error deleting levantamiento:', error);
                        this.showMessage(error.message || 'Error al eliminar el levantamiento', 'error');
                    });
            }
        },
        initParticipantesSelect() {
            const select = this.$refs.participantesSelect;

            console.log('[LOG] Iniciando Select2');

            $(select).select2({
                placeholder: 'Seleccionar participantes',
                width: '100%',
            });

            this.$nextTick(() => {
                $(select).val(this.paramsTask.participantesreunion).trigger('change');
            });

            $(select).on('change', () => {
                this.paramsTask.participantesreunion = $(select).val();
                console.log('[LOG] Participantes actualizados:', this.paramsTask.participantesreunion);
            });
        },

        // Cargar levantamientos de la tarea
        loadLevantamientos(taskId) {
            if (!taskId) {
                this.levantamientos = [];
                return;
            }

            fetch(`/scrumboard/tasks/${taskId}/levantamientos`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Error al cargar levantamientos');
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        this.levantamientos = data.levantamientos || [];
                    } else {
                        throw new Error(data.message || 'Error al cargar levantamientos');
                    }
                })
                .catch((error) => {
                    console.error('Error loading levantamientos:', error);
                    this.levantamientos = [];
                    this.showMessage('Error al cargar los levantamientos', 'error');
                });
        },

        // Método para agregar o actualizar proyecto ganado
        agregarGanado() {
            // Validar que haya al menos un campo lleno
            const hasData = this.paramsTask.fechaganado || this.paramsTask.codigoCotizacionGanado || this.paramsTask.tiposervicio;

            if (!hasData) {
                this.showMessage('Debe ingresar al menos un campo para el proyecto ganado', 'error');
                return;
            }

            const url = '/scrumboard/ganados/handle';
            const method = 'POST';

            const data = {
                task_id: this.paramsTask.id,
                fecha_ganado: this.paramsTask.fechaganado,
                codigo_cotizacion: this.paramsTask.codigoCotizacionGanado,
                tipo_relacion: this.paramsTask.tiporelacion,
                tipo_servicio: this.paramsTask.tiposervicio,
                valor_ganado: this.paramsTask.valorganado,
                forma_cierre: this.paramsTask.formacierre,
                duracion_acuerdo: this.paramsTask.duraciondelacuerdo,
                observaciones: this.paramsTask.observacionesganado,
                nivelPorcentajeGanado: this.paramsTask.nivelPorcentajeGanado,
                ganado_id: this.ganadoEditId || null,
            };

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(data),
            })
                .then((response) => {
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('El servidor devolvió una respuesta no válida');
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        if (this.ganadoEditId) {
                            // Actualizar en la lista
                            const index = this.ganados.findIndex((g) => g.id === this.ganadoEditId);
                            if (index !== -1) {
                                this.ganados[index] = data.ganado;
                            }
                            this.showMessage(data.message || 'Proyecto ganado actualizado correctamente');
                        } else {
                            // Agregar a la lista
                            this.ganados.push(data.ganado);
                            this.showMessage(data.message || 'Proyecto ganado agregado correctamente');
                        }

                        // Limpiar formulario
                        this.limpiarFormularioGanado();
                    } else {
                        throw new Error(data.message || 'Error desconocido');
                    }
                })
                .catch((error) => {
                    console.error('Error saving ganado:', error);
                    this.showMessage(error.message || 'Error al guardar el proyecto ganado', 'error');
                });
        },

        // Limpiar formulario de proyecto ganado
        limpiarFormularioGanado() {
            this.paramsTask.fechaganado = '';
            this.paramsTask.codigoCotizacionGanado = '';
            this.paramsTask.tiporelacion = '';
            this.paramsTask.tiposervicio = '';
            this.paramsTask.valorganado = '';
            this.paramsTask.formacierre = '';
            this.paramsTask.duraciondelacuerdo = '';
            this.paramsTask.observacionesganado = '';
            this.paramsTask.nivelPorcentajeGanado = '';
            this.ganadoEditId = null;
        },

        // Editar proyecto ganado
        editarGanado(ganado) {
            this.paramsTask.fechaganado = ganado.fecha_ganado || '';
            this.paramsTask.codigoCotizacionGanado = ganado.codigo_cotizacion || '';
            this.paramsTask.tiporelacion = ganado.tipo_relacion || '';
            this.paramsTask.tiposervicio = ganado.tipo_servicio || '';
            this.paramsTask.valorganado = ganado.valor_ganado || '';
            this.paramsTask.formacierre = ganado.forma_cierre || '';
            this.paramsTask.duraciondelacuerdo = ganado.duracion_acuerdo || '';
            this.paramsTask.observacionesganado = ganado.observaciones || '';
            this.paramsTask.nivelPorcentajeGanado = ganado.nivel_porcentaje || '';

            this.ganadoEditId = ganado.id;
        },

        // Eliminar proyecto ganado
        eliminarGanado(id) {
            if (confirm('¿Estás seguro de eliminar este proyecto ganado?')) {
                fetch(`/scrumboard/ganados/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
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
                            this.ganados = this.ganados.filter((g) => g.id !== id);
                            this.showMessage('Proyecto ganado eliminado correctamente');
                        } else {
                            throw new Error(data.message || 'Error al eliminar');
                        }
                    })
                    .catch((error) => {
                        console.error('Error deleting ganado:', error);
                        this.showMessage(error.message || 'Error al eliminar el proyecto ganado', 'error');
                    });
            }
        },

        // Cargar proyectos ganados de la tarea
        loadGanados(taskId) {
            if (!taskId) {
                this.ganados = [];
                return;
            }

            fetch(`/scrumboard/tasks/${taskId}/ganados`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Error al cargar proyectos ganados');
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        this.ganados = data.ganados || [];
                    } else {
                        throw new Error(data.message || 'Error al cargar proyectos ganados');
                    }
                })
                .catch((error) => {
                    console.error('Error loading ganados:', error);
                    this.ganados = [];
                    this.showMessage('Error al cargar los proyectos ganados', 'error');
                });
        },

        // Método para agregar o actualizar proyecto observado
        agregarObservado() {
            // Validar que haya al menos un campo lleno
            const hasData = this.paramsTask.fechaobservado || this.paramsTask.estadoactual || this.paramsTask.detallesobservado;

            if (!hasData) {
                this.showMessage('Debe ingresar al menos un campo para el proyecto observado', 'error');
                return;
            }

            const url = '/scrumboard/observados/handle';
            const method = 'POST';

            const data = {
                task_id: this.paramsTask.id,
                fecha_observado: this.paramsTask.fechaobservado,
                estado_actual: this.paramsTask.estadoactual,
                detalles: this.paramsTask.detallesobservado,
                comentarios: this.paramsTask.comentariosobservado,
                acciones_pendientes: this.paramsTask.accionespendientes,
                detalle_observado: this.paramsTask.detalleobservado,
                observado_id: this.observadoEditId || null,
                nivelPorcentajeObservado: this.paramsTask.nivelPorcentajeObservado,
            };

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(data),
            })
                .then((response) => {
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('El servidor devolvió una respuesta no válida');
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        if (this.observadoEditId) {
                            // Actualizar en la lista
                            const index = this.observados.findIndex((o) => o.id === this.observadoEditId);
                            if (index !== -1) {
                                this.observados[index] = data.observado;
                            }
                            this.showMessage(data.message || 'Proyecto observado actualizado correctamente');
                        } else {
                            // Agregar a la lista
                            this.observados.push(data.observado);
                            this.showMessage(data.message || 'Proyecto observado agregado correctamente');
                        }

                        // Limpiar formulario
                        this.limpiarFormularioObservado();
                    } else {
                        throw new Error(data.message || 'Error desconocido');
                    }
                })
                .catch((error) => {
                    console.error('Error saving observado:', error);
                    this.showMessage(error.message || 'Error al guardar el proyecto observado', 'error');
                });
        },

        // Limpiar formulario de proyecto observado
        limpiarFormularioObservado() {
            this.paramsTask.fechaobservado = '';
            this.paramsTask.estadoactual = '';
            this.paramsTask.detallesobservado = '';
            this.paramsTask.comentariosobservado = '';
            this.paramsTask.accionespendientes = '';
            this.paramsTask.detalleobservado = '';
            this.paramsTask.nivelPorcentajeObservado = '';
            this.observadoEditId = null;
        },

        // Editar proyecto observado
        editarObservado(observado) {
            this.paramsTask.fechaobservado = observado.fecha_observado || '';
            this.paramsTask.estadoactual = observado.estado_actual || '';
            this.paramsTask.detallesobservado = observado.detalles || '';
            this.paramsTask.comentariosobservado = observado.comentarios || '';
            this.paramsTask.accionespendientes = observado.acciones_pendientes || '';
            this.paramsTask.detalleobservado = observado.detalle_observado || '';
            this.paramsTask.nivelPorcentajeObservado = observado.nivel_porcentaje || '';

            this.observadoEditId = observado.id;
        },

        // Eliminar proyecto observado
        eliminarObservado(id) {
            if (confirm('¿Estás seguro de eliminar este proyecto observado?')) {
                fetch(`/scrumboard/observados/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
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
                            this.observados = this.observados.filter((o) => o.id !== id);
                            this.showMessage('Proyecto observado eliminado correctamente');
                        } else {
                            throw new Error(data.message || 'Error al eliminar');
                        }
                    })
                    .catch((error) => {
                        console.error('Error deleting observado:', error);
                        this.showMessage(error.message || 'Error al eliminar el proyecto observado', 'error');
                    });
            }
        },

        // Cargar proyectos observados de la tarea
        loadObservados(taskId) {
            if (!taskId) {
                this.observados = [];
                return;
            }

            fetch(`/scrumboard/tasks/${taskId}/observados`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Error al cargar proyectos observados');
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        this.observados = data.observados || [];
                    } else {
                        throw new Error(data.message || 'Error al cargar proyectos observados');
                    }
                })
                .catch((error) => {
                    console.error('Error loading observados:', error);
                    this.observados = [];
                    this.showMessage('Error al cargar los proyectos observados', 'error');
                });
        },

        // Método para agregar o actualizar proyecto rechazado
        agregarRechazado() {
            // Validar que haya al menos un campo lleno
            const hasData = this.paramsTask.fecharechazo || this.paramsTask.motivorechazo;

            if (!hasData) {
                this.showMessage('Debe ingresar al menos un campo para el proyecto rechazado', 'error');
                return;
            }

            const url = '/scrumboard/rechazados/handle';
            const method = 'POST';

            const data = {
                task_id: this.paramsTask.id,
                fecha_rechazo: this.paramsTask.fecharechazo,
                motivo_rechazo: this.paramsTask.motivorechazo,
                comentarios_cliente: this.paramsTask.comentarioscliente,
                rechazado_id: this.rechazadoEditId || null,
                nivelPorcentajeRechazado: this.paramsTask.nivelPorcentajeRechazado,
            };

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(data),
            })
                .then((response) => {
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('El servidor devolvió una respuesta no válida');
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        if (this.rechazadoEditId) {
                            // Actualizar en la lista
                            const index = this.rechazados.findIndex((r) => r.id === this.rechazadoEditId);
                            if (index !== -1) {
                                this.rechazados[index] = data.rechazado;
                            }
                            this.showMessage(data.message || 'Proyecto rechazado actualizado correctamente');
                        } else {
                            // Agregar a la lista
                            this.rechazados.push(data.rechazado);
                            this.showMessage(data.message || 'Proyecto rechazado agregado correctamente');
                        }

                        // Limpiar formulario
                        this.limpiarFormularioRechazado();
                    } else {
                        throw new Error(data.message || 'Error desconocido');
                    }
                })
                .catch((error) => {
                    console.error('Error saving rechazado:', error);
                    this.showMessage(error.message || 'Error al guardar el proyecto rechazado', 'error');
                });
        },

        // Limpiar formulario de proyecto rechazado
        limpiarFormularioRechazado() {
            this.paramsTask.fecharechazo = '';
            this.paramsTask.motivorechazo = '';
            this.paramsTask.comentarioscliente = '';
            this.rechazadoEditId = null;
            this.paramsTask.nivelPorcentajeRechazado = '';
        },

        // Editar proyecto rechazado
        editarRechazado(rechazado) {
            this.paramsTask.fecharechazo = rechazado.fecha_rechazo || '';
            this.paramsTask.motivorechazo = rechazado.motivo_rechazo || '';
            this.paramsTask.comentarioscliente = rechazado.comentarios_cliente || '';
            this.paramsTask.nivelPorcentajeRechazado = rechazado.nivel_porcentaje || '';

            this.rechazadoEditId = rechazado.id;
        },

        // Eliminar proyecto rechazado
        eliminarRechazado(id) {
            if (confirm('¿Estás seguro de eliminar este proyecto rechazado?')) {
                fetch(`/scrumboard/rechazados/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
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
                            this.rechazados = this.rechazados.filter((r) => r.id !== id);
                            this.showMessage('Proyecto rechazado eliminado correctamente');
                        } else {
                            throw new Error(data.message || 'Error al eliminar');
                        }
                    })
                    .catch((error) => {
                        console.error('Error deleting rechazado:', error);
                        this.showMessage(error.message || 'Error al eliminar el proyecto rechazado', 'error');
                    });
            }
        },

        // Cargar proyectos rechazados de la tarea
        loadRechazados(taskId) {
            if (!taskId) {
                this.rechazados = [];
                return;
            }

            fetch(`/scrumboard/tasks/${taskId}/rechazados`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Error al cargar proyectos rechazados');
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        this.rechazados = data.rechazados || [];
                    } else {
                        throw new Error(data.message || 'Error al cargar proyectos rechazados');
                    }
                })
                .catch((error) => {
                    console.error('Error loading rechazados:', error);
                    this.rechazados = [];
                    this.showMessage('Error al cargar los proyectos rechazados', 'error');
                });
        },
        // Agrega estas computed properties
        get filteredLevantamientos() {
            return this.levantamientos.filter((levantamiento) => {
                const matchesUbicacion =
                    !this.filtroUbicacion || (levantamiento.ubicacion && levantamiento.ubicacion.toLowerCase().includes(this.filtroUbicacion.toLowerCase()));
                const matchesFecha = !this.filtroFechalevantamiento || levantamiento.fecha_requerimiento === this.filtroFechalevantamiento;

                return matchesUbicacion && matchesFecha;
            });
        },

        get filteredGanados() {
            return this.ganados.filter((ganado) => {
                const matchesCodigo =
                    !this.filtroCodigoGanado ||
                    (ganado.codigo_cotizacion && ganado.codigo_cotizacion.toLowerCase().includes(this.filtroCodigoGanado.toLowerCase()));
                const matchesServicio =
                    !this.filtroTipoServicio || (ganado.tipo_servicio && ganado.tipo_servicio.toLowerCase().includes(this.filtroTipoServicio.toLowerCase()));
                const matchesFecha = !this.filtroFechaGanado || ganado.fecha_ganado === this.filtroFechaGanado;

                return matchesCodigo && matchesServicio && matchesFecha;
            });
        },

        get filteredObservados() {
            return this.observados.filter((observado) => {
                const matchesEstado =
                    !this.filtroEstadoObservado ||
                    (observado.estado_actual && observado.estado_actual.toLowerCase().includes(this.filtroEstadoObservado.toLowerCase()));
                const matchesFecha = !this.filtroFechaObservado || observado.fecha_observado === this.filtroFechaObservado;

                return matchesEstado && matchesFecha;
            });
        },

        get filteredRechazados() {
            return this.rechazados.filter((rechazado) => {
                const matchesMotivo =
                    !this.filtroMotivoRechazo ||
                    (rechazado.motivo_rechazo && rechazado.motivo_rechazo.toLowerCase().includes(this.filtroMotivoRechazo.toLowerCase()));
                const matchesFecha = !this.filtroFechaRechazo || rechazado.fecha_rechazo === this.filtroFechaRechazo;

                return matchesMotivo && matchesFecha;
            });
        },
        get filteredReuniones() {
            return this.reuniones.filter((reunion) => {
                const matchesTipo =
                    !this.filtroTipoReunion || (reunion.tipo_reunion && reunion.tipo_reunion.toLowerCase().includes(this.filtroTipoReunion.toLowerCase()));
                const matchesResponsable =
                    !this.filtroResponsableReunion ||
                    (reunion.responsable_reunion && reunion.responsable_reunion.toLowerCase().includes(this.filtroResponsableReunion.toLowerCase()));
                const matchesFecha = !this.filtroFechaReunion || reunion.fecha_reunion === this.filtroFechaReunion;

                return matchesTipo && matchesResponsable && matchesFecha;
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
                idseguimiento: this.idSeguimiento,
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
            // Encontrar el proyecto para obtener su nombre
            const project = this.projectList.find((p) => p.id === projectId);
            this.currentProjectName = project ? project.title : '';

            this.paramsTask = {
                projectId: projectId,
                id: null,
                title: '',
                description: '',
                tags: '',
                image: null,
                //CAMPOS COTIZACION
                fechaCotizacion: '', // Nuevo campo para fecha de cotización
                codigoCotizacion: '', // Nuevo campo para cotización
                detalleproducto: '', // Nuevo campo para detalle de producto
                condicionescomerciales: '', // Nuevo campo para condiciones comerciales
                totalcotizacion: '', // Nuevo campo para total de cotización
                validezcotizacion: '', // Nuevo campo para validez de cotización
                responsablecotizacion: '', // Nuevo campo para responsable de cotización
                observacionescotizacion: '', // Nuevo campo para observaciones de cotización
                //CAMPOS REUNION
                fechareunion: '', // Nuevo campo para fecha de reunión
                tiporeunion: '', // Nuevo campo para tipo de reunión
                motivoreunion: '', // Nuevo campo para motivo de reunión
                participantesreunion: '', // Nuevo campo para participantes de reunión
                responsablereunion: '', // Nuevo campo para responsable de reunión
                linkreunion: '', // Nuevo campo para link de reunión
                direccionfisica: '', // Nuevo campo para dirección de reunión
                minutareunion: '', // Nuevo campo para minuta de reunión
                actividadesReunion: '', // Nuevo campo para reunión
                //CAMPOS LEVANTAMIENTO DE INFORMACION
                fecharequerimiento: '', // Nuevo campo para fecha de requerimiento
                descripcionrequerimiento: '', // Nuevo campo para requerimiento
                participanteslevantamiento: '', // Nuevo campo para participantes de levantamiento
                ubicacionlevantamiento: '', // Nuevo campo para ubicación de levantamiento
                observacioneslevantamiento: '', // Nuevo campo para observaciones de levantamiento
                //CAMPOS GANADO
                fechaganado: '', // Nuevo campo para fecha ganado
                codigoCotizacion: '', // Nuevo campo para código de cotización
                tiporelacion: '', // Nuevo campo para tipo de relación
                tiposervicio: '', // Nuevo campo para tipo de servicio
                valorganado: '', // Nuevo campo para valor ganado
                formacierre: '', // Nuevo campo para forma de cierre
                duraciondelacuerdo: '', // Nuevo campo para duración del acuerdo
                observacionesganado: '', // Nuevo campo para observaciones de ganado
                //CAMPOS OBSERVADO
                fechaobservado: '', // Nuevo campo para fecha observado
                estadoactual: '', // Nuevo campo para estado actual
                detallesobservado: '', // Nuevo campo para detalles observado
                comentariosobservado: '', // Nuevo campo para comentarios observado
                accionespendientes: '', // Nuevo campo para acciones pendientes
                detalleobservado: '', // Nuevo campo para detalle observado
                //CAMPOS RECHAZO
                fecharechazo: '', // Nuevo campo para fecha de rechazo
                motivorechazo: '', // Nuevo campo para motivo de rechazo
                comentarioscliente: '', // Nuevo campo para comentarios del cliente
            };

            if (task) {
                this.paramsTask = {
                    projectId: projectId,
                    id: task.id,
                    title: task.title,
                    description: task.description,
                    tags: task.tags ? task.tags.join(', ') : '',
                    image: task.image || null,
                    //COTIZACION
                    fechaCotizacion: task.fechaCotizacion || '', // Cargar valor existente si edita
                    codigoCotizacion: task.codigoCotizacion || '', // Cargar valor existente si edita
                    detalleproducto: task.detalleproducto || '', // Cargar valor existente si edita
                    condicionescomerciales: task.condicionescomerciales || '', // Cargar valor existente si edita
                    totalcotizacion: task.totalcotizacion || '', // Cargar valor existente si edita
                    validezcotizacion: task.validezcotizacion || '', // Cargar valor existente si edita
                    responsablecotizacion: task.responsablecotizacion || '', // Cargar valor existente si edita
                    observacionescotizacion: task.observacionescotizacion || '', // Cargar valor existente si edita
                    //REUNION
                    fechareunion: task.fechareunion || '', // Cargar valor existente si edita
                    tiporeunion: task.tiporeunion || '', // Cargar valor existente si edita
                    motivoreunion: task.motivoreunion || '', // Cargar valor existente si edita
                    participantesreunion: task.participantesreunion || '', // Cargar valor existente si edita
                    responsablereunion: task.responsablereunion || '', // Cargar valor existente si edita
                    linkreunion: task.linkreunion || '', // Cargar valor existente si edita
                    direccionfisica: task.direccionfisica || '', // Cargar valor existente si edita
                    minutareunion: task.minutareunion || '', // Cargar valor existente si edita
                    actividadesReunion: task.actividadesReunion || '', // Cargar valor existente si edita
                    //LEVANTAMIENTO DE INFORMACION
                    fecharequerimiento: task.fecharequerimiento || '', // Cargar valor existente si edita
                    descripcionrequerimiento: task.descripcionrequerimiento || '', // Cargar valor existente si edita
                    participanteslevantamiento: task.participanteslevantamiento || '', // Cargar valor existente si edita
                    ubicacionlevantamiento: task.ubicacionlevantamiento || '', // Cargar valor existente si edita
                    observacioneslevantamiento: task.observacioneslevantamiento || '', // Cargar valor existente si edita
                    //GANADO
                    fechaganado: task.fechaganado || '', // Cargar valor existente si edita
                    codigoCotizacion: task.codigoCotizacion || '', // Cargar valor existente si edita
                    tiporelacion: task.tiporelacion || '', // Cargar valor existente si edita
                    tiposervicio: task.tiposervicio || '', // Cargar valor existente si edita
                    valorganado: task.valorganado || '', // Cargar valor existente si edita
                    formacierre: task.formacierre || '', // Cargar valor existente si edita
                    duraciondelacuerdo: task.duraciondelacuerdo || '', // Cargar valor existente si edita
                    observacionesganado: task.observacionesganado || '', // Cargar valor existente si edita
                    //OBSERVADO
                    fechaobservado: task.fechaobservado || '', // Cargar valor existente si edita
                    estadoactual: task.estadoactual || '', // Cargar valor existente si edita
                    detallesobservado: task.detallesobservado || '', // Cargar valor existente si edita
                    comentariosobservado: task.comentariosobservado || '', // Cargar valor existente si edita
                    accionespendientes: task.accionespendientes || '', // Cargar valor existente si edita
                    detalleobservado: task.detalleobservado || '', // Cargar valor existente si edita
                    //RECHAZO
                    fecharechazo: task.fecharechazo || '', // Cargar valor existente si edita
                    comentarioscliente: task.comentarioscliente || '', // Cargar valor existente si edita
                    motivorechazo: task.motivorechazo || '', // Nuevo campo para motivo de rechazo
                    comentarioscliente: task.comentarioscliente || '',
                    motivorechazo: task.motivorechazo || '',

                    // Cargar valor existente si edita
                };
            }

            this.isAddTaskModal = true;

            // Si es una tarea existente, cargar sus cotizaciones
            // Si es una tarea existente, cargar sus datos
            // Si es una tarea existente, cargar sus datos
            // Si es una tarea existente, cargar sus datos
            if (task && task.id) {
                this.loadCotizaciones(task.id);
                this.loadReuniones(task.id);
                this.loadLevantamientos(task.id);
                this.loadGanados(task.id);
                this.loadObservados(task.id);
                this.loadRechazados(task.id); // ← Agregar esta línea (ÚLTIMA!)
                this.loadTarea(task.id); // Cargar datos de la tarea
            } else {
                this.cotizaciones = [];
                this.reuniones = [];
                this.levantamientos = [];
                this.ganados = [];
                this.observados = [];
                this.rechazados = []; // ← Limpiar para nueva tarea
                this.tarea = null;
            }
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
                idseguimiento: this.idSeguimiento,
                // Agregar los campos adicionales según el tipo de proyecto
                ...(this.currentProjectName.toLowerCase().includes('cotizacion') && {
                    fechaCotizacion: this.paramsTask.fechaCotizacion || '',
                    detalleproducto: this.paramsTask.detalleproducto || '',
                    condicionescomerciales: this.paramsTask.condicionescomerciales || '',
                    codigoCotizacion: this.paramsTask.codigoCotizacion || '',
                    totalcotizacion: this.paramsTask.totalcotizacion || '',
                    validezcotizacion: this.paramsTask.validezcotizacion || '',
                    responsablecotizacion: this.paramsTask.responsablecotizacion || '',
                    observacionescotizacion: this.paramsTask.observacionescotizacion || '',
                }),
                ...(this.currentProjectName.toLowerCase().includes('reunion') && {
                    actividadesReunion: this.paramsTask.actividadesReunion || '',
                    fechareunion: this.paramsTask.fechareunion || '',
                    tiporeunion: this.paramsTask.tiporeunion || '',
                    motivoreunion: this.paramsTask.motivoreunion || '',
                    participantesreunion: this.paramsTask.participantesreunion || '',
                    responsablereunion: this.paramsTask.responsablereunion || '',
                    linkreunion: this.paramsTask.linkreunion || '',
                    direccionfisica: this.paramsTask.direccionfisica || '',
                    minutareunion: this.paramsTask.minutareunion || '',
                }),
                ...(this.currentProjectName.toLowerCase().includes('levantamiento') && {
                    fecharequerimiento: this.paramsTask.fecharequerimiento || '',
                    participanteslevantamiento: this.paramsTask.participanteslevantamiento || '',
                    ubicacionlevantamiento: this.paramsTask.ubicacionlevantamiento || '',
                    observacioneslevantamiento: this.paramsTask.observacioneslevantamiento || '',
                    descripcionrequerimiento: this.paramsTask.descripcionrequerimiento || '',
                }),
                ...(this.currentProjectName.toLowerCase().includes('ganado') && {
                    fechaganado: this.paramsTask.fechaganado || '',
                    codigoCotizacion: this.paramsTask.codigoCotizacion || '',
                    tiporelacion: this.paramsTask.tiporelacion || '',
                    tiposervicio: this.paramsTask.tiposervicio || '',
                    valorganado: this.paramsTask.valorganado || '',
                    formacierre: this.paramsTask.formacierre || '',
                    duraciondelacuerdo: this.paramsTask.duraciondelacuerdo || '',
                    observacionesganado: this.paramsTask.observacionesganado || '',
                }),
                ...(this.currentProjectName.toLowerCase().includes('observado') && {
                    detalleobservado: this.paramsTask.detalleobservado || '',
                    fechaobservado: this.paramsTask.fechaobservado || '',
                    estadoactual: this.paramsTask.estadoactual || '',
                    detallesobservado: this.paramsTask.detallesobservado || '',
                    comentariosobservado: this.paramsTask.comentariosobservado || '',
                    accionespendientes: this.paramsTask.accionespendientes || '',
                }),
                ...(this.currentProjectName.toLowerCase().includes('rechazo') && {
                    motivorechazo: this.paramsTask.motivorechazo || '',
                    fecharechazo: this.paramsTask.fecharechazo || '',
                    comentarioscliente: this.paramsTask.comentarioscliente || '',
                }),
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
function formatDate(iso) {
    if (!iso) return '';
    const d = new Date(iso); // convierte de UTC a hora local
    return d.toLocaleDateString('es-PE', { year: 'numeric', month: '2-digit', day: '2-digit' });
    // si
    // quieres fecha y hora: d.toLocaleString('es-PE')
}
