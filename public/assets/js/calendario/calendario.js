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

        isAddEventModal: false,
        minStartDate: '',
        minEndDate: '',
        calendar: null,
        now: new Date(),
        events: [],
        etiquetas: [],
        usuarios: [],
        tomSelect: null,

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
                    this.editDate(event);
                },
                events: this.events,
            });
            this.calendar.render();

            this.$watch('$store.app.sidebar', () => {A
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

    async fetchEventos() {
    try {
        const response = await axios.get('/actividades');
        this.events = response.data.map(event => {
            // Buscar la etiqueta completa en el array de etiquetas
            const etiquetaCompleta = this.etiquetas.find(e => e.nombre === event.etiqueta);
            
            return {
                id: event.actividad_id,
                title: event.titulo,
                start: event.fechainicio,
                end: event.fechafin,
                className: etiquetaCompleta ? `bg-${etiquetaCompleta.color}` : 'bg-primary',
                description: event.descripcion,
                enlaceevento: event.enlaceevento,
                ubicacion: event.ubicacion,
                invitados: event.invitados?.map(i => i.id_usuarios) || [],
                extendedProps: {
                    etiqueta: event.etiqueta,
                    color: etiquetaCompleta?.color || 'primary'
                }
            };
        });
    } catch (error) {
        console.error('Error fetching eventos:', error);
        this.showMessage('Error al cargar eventos', 'error');
    }
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
            option: function(data, escape) {
                return `
                    <div>
                        <span class="block font-medium">${escape(data.text)}</span>
                        <span class="block text-xs text-gray-500">${escape(data.email)}</span>
                    </div>
                `;
            },
            item: function(data, escape) {
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

    try {
        // Obtener invitados seleccionados (asegurando que sean strings)
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
                // Actualizar todas las propiedades del evento
                eventObj.setProp('title', this.params.title);
                eventObj.setDates(this.params.start, this.params.end);
                eventObj.setExtendedProp('description', this.params.description);
                eventObj.setExtendedProp('etiqueta', this.params.etiqueta);
                eventObj.setExtendedProp('enlaceevento', this.params.enlaceevento);
                eventObj.setExtendedProp('ubicacion', this.params.ubicacion);
                eventObj.setExtendedProp('invitados', this.params.invitados);
                eventObj.setProp('classNames', [this.getColorForEtiqueta(this.params.etiqueta)]);

                // Actualizar el color basado en la etiqueta
                const colorClase = this.getColorForEtiqueta(this.params.etiqueta);
                eventObj.setProp('classNames', [colorClase]);
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

            // Agregar el nuevo evento al calendario
            this.calendar.addEvent({
                id: response.data.actividad_id,
                title: this.params.title,
                start: this.params.start,
                end: this.params.end,
                className: this.getColorForEtiqueta(this.params.etiqueta),
                extendedProps: {
                    description: this.params.description,
                    etiqueta: this.params.etiqueta,
                    enlaceevento: this.params.enlaceevento,
                    ubicacion: this.params.ubicacion,
                    invitados: this.params.invitados,
                    color: this.getColorForEtiqueta(this.params.etiqueta)

                }
            });
        }

        this.showMessage('Evento guardado exitosamente');
        this.isAddEventModal = false;
    } catch (error) {
        console.error('Error saving event:', error);
        this.showMessage('Error al guardar el evento', 'error');
    }
},
       async deleteEvent() {
    if (!this.params.id) return;
    
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