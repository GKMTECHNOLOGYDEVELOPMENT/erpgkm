document.addEventListener("alpine:init", () => {
    Alpine.data("calendar", () => ({
        defaultParams: {
            id: null,
            title: '',
            start: '',
            end: '',
            description: '',
            etiqueta: '',
            enlaceevento: '',
            ubicacion: '',
            invitados: []
        },
        params: {
            id: null,
            title: '',
            start: '',
            end: '',
            description: '',
            etiqueta: '',
            enlaceevento: '',
            ubicacion: '',
            invitados: []
        },


        isSaving: false,
        isDeleting: false,
        isSavingTag: false,
        isDeletingTag: false,
        isAddEventModal: false,
        minStartDate: '',
        minEndDate: '',
        calendar: null,
        now: new Date(),
        events: [],
        etiquetas: [],
        usuarios: [],
        tomSelect: null,
        isEtiquetaModal: false,
        etiquetaParams: {
            id: null,
            nombre: '',
            color: '',
            icono: ''
        },

        async init() {
            // Cargar etiquetas y usuarios
            await this.fetchEtiquetas();
            await this.fetchUsuarios();

            // Inicializar TomSelect
            this.initTomSelect();

            // Cargar eventos del servidor
            await this.fetchEventos();

            var calendarEl = document.getElementById('calendar');
            this.calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es', // ← 👈 AÑADE ESTA LÍNEA
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay',
                },
                editable: true,
                dayMaxEvents: true,
                selectable: true,
                droppable: true,
                // 🔒 Bloquear selección en fechas pasadas (pero se siguen mostrando)
                selectAllow: (selectInfo) => {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0); // 🔒 inicio del día actual
                    return selectInfo.start >= today;
                },
                
                eventClick: (event) => {
                    this.editEvent(event);
                },
                select: (event) => {
                    this.editDate(event);
                },
                events: this.events,
            });
            this.calendar.render();

            this.$watch('$store.app.sidebar', () => {
                A
                setTimeout(() => {
                    this.calendar.render();
                }, 300);
            });
        },

        async fetchEtiquetas() {
            try {
                const response = await axios.get('/etiquetas');
                this.etiquetas = response.data.map(etiqueta => ({
                    id: etiqueta.id,
                    nombre: etiqueta.nombre,
                    color: etiqueta.color,
                    icono: etiqueta.icono
                }));
            } catch (error) {
                console.error('Error fetching etiquetas:', error);
                this.showMessage('Error al cargar etiquetas', 'error');
            }
        },
        getColorForEtiqueta(nombreEtiqueta) {
            if (!nombreEtiqueta) return 'bg-primary';

            // Busca la etiqueta por nombre en el array de etiquetas
            const etiqueta = this.etiquetas.find(e => e.nombre === nombreEtiqueta);
            return etiqueta ? `bg-${etiqueta.color}` : 'bg-primary';
        },

        async fetchUsuarios() {
            try {
                const response = await axios.get('/api/usuarios');

                if (response.data && response.data.success && Array.isArray(response.data.data)) {
                    this.usuarios = response.data.data.map(user => ({
                        value: user.id.toString(), // TomSelect espera strings como valores
                        text: `${user.Nombre} ${user.apellidoPaterno}`,
                        email: user.email,
                        rol: user.idRol
                    }));

                    // Inicializar TomSelect después de cargar usuarios
                    this.initTomSelect();
                } else {
                    console.error('Formato de respuesta inesperado:', response.data);
                    this.usuarios = [];
                }
            } catch (error) {
                console.error('Error fetching usuarios:', error);
                this.usuarios = [];
                this.showMessage('Error al cargar usuarios', 'error');
            }
        },

        // Métodos para gestión de etiquetas
        showEtiquetaModal(etiqueta = null) {
            this.etiquetaParams = etiqueta ?
                JSON.parse(JSON.stringify(etiqueta)) :
                { id: null, nombre: '', color: '', icono: '' };
            this.isEtiquetaModal = true;
        },

        editEtiqueta(etiqueta) {
            this.showEtiquetaModal(etiqueta);
        },

        async saveEtiqueta() {
            if (!this.etiquetaParams.nombre || !this.etiquetaParams.color) {
                this.showMessage('Nombre y color son requeridos', 'error');
                return;
            }

            this.isSavingTag = true; // Activar estado de carga


            try {
                let response;
                if (this.etiquetaParams.id) {
                    // Actualizar etiqueta
                    response = await axios.put(`/etiquetas/${this.etiquetaParams.id}`, this.etiquetaParams);
                    const index = this.etiquetas.findIndex(e => e.id === this.etiquetaParams.id);
                    if (index !== -1) {
                        this.etiquetas[index] = response.data;
                    }
                } else {
                    // Crear nueva etiqueta
                    response = await axios.post('/etiquetas', this.etiquetaParams);
                    this.etiquetas.push(response.data);
                }

                // Actualizar eventos para reflejar cambios en colores
                await this.fetchEventos();
                this.calendar.refetchEvents();

                this.showMessage('Etiqueta guardada exitosamente');
                this.isEtiquetaModal = false;
            } catch (error) {
                console.error('Error saving tag:', error);
                this.showMessage('Error al guardar la etiqueta', 'error');
            } finally {
                this.isSavingTag = false; // Desactivar estado de carga
            }
        },

        async deleteEtiqueta(id) {
            if (!id) return;
            this.isDeletingTag = true; // Activar estado de carga

            try {
                // Verificar si la etiqueta está en uso
                const etiqueta = this.etiquetas.find(t => t.id === id);
                const eventosConEstaEtiqueta = this.events.filter(
                    e => e.extendedProps?.etiqueta === etiqueta?.nombre
                );

                if (eventosConEstaEtiqueta.length > 0) {
                    this.showMessage('No puedes eliminar una etiqueta en uso', 'error');
                    return;
                }

                await axios.delete(`/etiquetas/${id}`);
                this.etiquetas = this.etiquetas.filter(e => e.id !== id);

                // Actualizar eventos
                await this.fetchEventos();
                this.calendar.refetchEvents();

                this.showMessage('Etiqueta eliminada exitosamente');
                this.isEtiquetaModal = false;
            } catch (error) {
                console.error('Error deleting tag:', error);
                this.showMessage('Error al eliminar la etiqueta', 'error');
            } finally {
                this.isDeletingTag = false; // Desactivar estado de carga
            }
        },

        async fetchEventos() {
            try {
                const response = await axios.get('/actividades');
                this.events = response.data.map(event => {
                    // Buscar la etiqueta completa en el array de etiquetas
                    const etiquetaCompleta = this.etiquetas.find(e => e.nombre === event.etiqueta);
                    const colorClase = etiquetaCompleta ? `bg-${etiquetaCompleta.color}` : 'bg-primary';

                    return {
                        id: event.actividad_id,
                        title: event.titulo,
                        start: event.fechainicio,
                        end: event.fechafin,
                        allDay: this.isAllDayEvent(event.fechainicio, event.fechafin),
                        className: colorClase,
                        description: event.descripcion,
                        enlaceevento: event.enlaceevento,
                        ubicacion: event.ubicacion,
                        invitados: event.invitados?.map(i => i.id_usuarios) || [],
                        extendedProps: {
                            etiqueta: event.etiqueta,
                            color: etiquetaCompleta?.color || 'primary'
                        },
                        display: 'block', // Fuerza el mismo estilo para todos los eventos
                        backgroundColor: this.getBackgroundColor(etiquetaCompleta?.color),
                        borderColor: this.getBorderColor(etiquetaCompleta?.color)
                    };
                });
            } catch (error) {
                console.error('Error fetching eventos:', error);
                this.showMessage('Error al cargar eventos', 'error');
            }
        },

        // Verifica si el evento es de todo el día
        isAllDayEvent(start, end) {
            const startDate = new Date(start);
            const endDate = new Date(end);

            // Si no tiene hora (es medianoche) o dura exactamente 24 horas
            return (startDate.getHours() === 0 && startDate.getMinutes() === 0) ||
                (endDate - startDate) === 86400000;
        },

        // Obtiene el color de fondo en formato hexadecimal
        getBackgroundColor(colorName) {
            const colors = {
                'primary': '#3b82f6',
                'success': '#10b981',
                'danger': '#ef4444',
                'warning': '#f59e0b',
                'info': '#06b6d4',
                'secondary': '#64748b',
                'dark': '#1e293b',
                'indigo': '#6366f1',
                'purple': '#8b5cf6',
                'pink': '#ec4899'
            };
            return colors[colorName] || colors['primary'];
        },

        // Obtiene el color del borde (puede ser igual al de fondo o un tono más oscuro)
        getBorderColor(colorName) {
            const colors = {
                'primary': '#2563eb',
                'success': '#059669',
                'danger': '#dc2626',
                'warning': '#d97706',
                'info': '#0891b2',
                'secondary': '#475569',
                'dark': '#0f172a',
                'indigo': '#4f46e5',
                'purple': '#7c3aed',
                'pink': '#db2777'
            };
            return colors[colorName] || colors['primary'];
        },

        initTomSelect() {
            // Destruir instancia previa si existe
            if (this.tomSelect) {
                this.tomSelect.destroy();
            }

            this.tomSelect = new TomSelect('#invitados', {
                plugins: ['remove_button'],
                placeholder: 'Selecciona invitados...',
                options: this.usuarios,
                valueField: 'value',
                labelField: 'text',
                searchField: ['text', 'email'],
                render: {
                    option: function (data, escape) {
                        return `
                    <div>
                        <span class="block font-medium">${escape(data.text)}</span>
                        <span class="block text-xs text-gray-500">${escape(data.email)}</span>
                    </div>
                `;
                    },
                    item: function (data, escape) {
                        return `<div>${escape(data.text)}</div>`;
                    }
                },
                onChange: (value) => {
                    this.params.invitados = value;
                }
            });
        },

        getMonth(dt, add = 0) {
            let month = dt.getMonth() + 1 + add;
            return dt.getMonth() < 10 ? '0' + month : month;
        },
        editEvent(data) {
            // Reiniciar parámetros
            this.params = JSON.parse(JSON.stringify(this.defaultParams));
            this.tomSelect.clear();

            if (data) {
                let eventObj = data.event;

                // Obtener invitados del evento (asegurando formato correcto)
                let invitados = eventObj.extendedProps?.invitados || [];
                invitados = invitados.map(id => id.toString()); // Convertir a strings

                // Configurar parámetros del formulario
                this.params = {
                    id: eventObj.id,
                    title: eventObj.title,
                    start: this.dateFormat(eventObj.start),
                    end: this.dateFormat(eventObj.end),
                    etiqueta: eventObj.extendedProps?.etiqueta || '',
                    description: eventObj.extendedProps?.description || '',
                    enlaceevento: eventObj.extendedProps?.enlaceevento || '',
                    ubicacion: eventObj.extendedProps?.ubicacion || '',
                    invitados: invitados
                };

                // Cargar invitados en TomSelect (con retraso para asegurar renderizado)
                setTimeout(() => {
                    if (this.params.invitados && this.params.invitados.length > 0) {
                        this.tomSelect.setValue(this.params.invitados);
                    }
                }, 100);

                // Configurar fechas mínimas
                this.minStartDate = new Date();
                this.minEndDate = this.dateFormat(eventObj.start);
            } else {
                // Nuevo evento - configurar fechas mínimas
                this.minStartDate = new Date();
                this.minEndDate = new Date();
            }

            // Mostrar modal
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
            const month = dt.getMonth() + 1 < 10 ? '0' + (dt.getMonth() + 1) : dt.getMonth() + 1;
            const date = dt.getDate() < 10 ? '0' + dt.getDate() : dt.getDate();
            const hours = dt.getHours() < 10 ? '0' + dt.getHours() : dt.getHours();
            const mins = dt.getMinutes() < 10 ? '0' + dt.getMinutes() : dt.getMinutes();
            dt = dt.getFullYear() + '-' + month + '-' + date + 'T' + hours + ':' + mins;
            return dt;
        },

        async saveEvent() {
            // Validaciones requeridas
            if (!this.params.title) {
                this.showMessage('El título del evento es requerido', 'error');
                return;
            }
            if (!this.params.start) {
                this.showMessage('La fecha de inicio es requerida', 'error');
                return;
            }
            if (!this.params.end) {
                this.showMessage('La fecha de fin es requerida', 'error');
                return;
            }
            if (!this.params.etiqueta) {
                this.showMessage('La etiqueta es requerida', 'error');
                return;
            }

            this.isSaving = true; // Activar estado de carga


            try {
                // Obtener invitados seleccionados
                this.params.invitados = this.tomSelect.getValue().map(id => id.toString());

                let response;
                if (this.params.id) {
                    // ACTUALIZAR EVENTO EXISTENTE
                    response = await axios.put(`/actividades/${this.params.id}`, {
                        titulo: this.params.title,
                        fechainicio: this.params.start,
                        fechafin: this.params.end,
                        descripcion: this.params.description,
                        etiqueta: this.params.etiqueta,
                        enlaceevento: this.params.enlaceevento,
                        ubicacion: this.params.ubicacion,
                        invitados: this.params.invitados
                    });

                    // Buscar el evento en el calendario
                    const eventObj = this.calendar.getEventById(this.params.id);

                    if (eventObj) {
                        // Obtener la etiqueta completa
                        const etiquetaCompleta = this.etiquetas.find(e => e.nombre === this.params.etiqueta);
                        const colorClase = this.getColorForEtiqueta(this.params.etiqueta);

                        // Actualizar todas las propiedades del evento
                        eventObj.setProp('title', this.params.title);
                        eventObj.setDates(this.params.start, this.params.end);
                        eventObj.setExtendedProp('description', this.params.description);
                        eventObj.setExtendedProp('etiqueta', this.params.etiqueta);
                        eventObj.setExtendedProp('enlaceevento', this.params.enlaceevento);
                        eventObj.setExtendedProp('ubicacion', this.params.ubicacion);
                        eventObj.setExtendedProp('invitados', this.params.invitados);

                        // Actualizar estilos visuales
                        eventObj.setProp('classNames', [colorClase]);
                        eventObj.setProp('backgroundColor', this.getBackgroundColor(etiquetaCompleta?.color));
                        eventObj.setProp('borderColor', this.getBorderColor(etiquetaCompleta?.color));
                        eventObj.setProp('allDay', this.isAllDayEvent(this.params.start, this.params.end));
                    }
                } else {
                    // CREAR NUEVO EVENTO
                    response = await axios.post('/actividades', {
                        titulo: this.params.title,
                        fechainicio: this.params.start,
                        fechafin: this.params.end,
                        descripcion: this.params.description,
                        etiqueta: this.params.etiqueta,
                        enlaceevento: this.params.enlaceevento,
                        ubicacion: this.params.ubicacion,
                        invitados: this.params.invitados
                    });

                    // Obtener la etiqueta completa
                    const etiquetaCompleta = this.etiquetas.find(e => e.nombre === this.params.etiqueta);
                    const colorClase = this.getColorForEtiqueta(this.params.etiqueta);

                    // Agregar el nuevo evento al calendario
                    this.calendar.addEvent({
                        id: response.data.actividad_id,
                        title: this.params.title,
                        start: this.params.start,
                        end: this.params.end,
                        allDay: this.isAllDayEvent(this.params.start, this.params.end),
                        className: colorClase,
                        backgroundColor: this.getBackgroundColor(etiquetaCompleta?.color),
                        borderColor: this.getBorderColor(etiquetaCompleta?.color),
                        display: 'block',
                        extendedProps: {
                            description: this.params.description,
                            etiqueta: this.params.etiqueta,
                            enlaceevento: this.params.enlaceevento,
                            ubicacion: this.params.ubicacion,
                            invitados: this.params.invitados,
                            color: etiquetaCompleta?.color || 'primary'
                        }
                    });
                }

                this.showMessage('Evento guardado exitosamente');
                this.isAddEventModal = false;
            } catch (error) {
                console.error('Error saving event:', error);
                this.showMessage('Error al guardar el evento', 'error');
            } finally {
                this.isSaving = false; // Desactivar estado de carga
            }
        },
        async deleteEvent() {
            if (!this.params.id) return;

            this.isDeleting = true; // Activar estado de carga


            try {
                await axios.delete(`/actividades/${this.params.id}`);


                // Eliminar el evento localmente sin recargar
                this.events = this.events.filter(e => e.id != this.params.id);
                this.calendar.removeAllEvents();
                this.calendar.addEventSource(this.events);

                this.showMessage('Evento eliminado exitosamente');
                this.isAddEventModal = false;
            } catch (error) {
                console.error('Error deleting event:', error);
                this.showMessage('Error al eliminar el evento', 'error');
            } finally {
                this.isDeleting = false; // Desactivar estado de carga
            }
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