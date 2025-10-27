document.addEventListener('alpine:init', () => {
    Alpine.data('cotizacionAdd', () => ({
        mostrarNGR: false,
        items: [],
        params: {
            cotizacionNo: 'COT-' + new Date().getFullYear() + '-' + Math.random().toString().substr(2, 4),
            fechaEmision: new Date().toISOString().split('T')[0],
            validaHasta: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
            tecnico: '',
            tienda: '',
            serie: '',
            cliente: {
                id: '',
                nombre: '',
                email: '',
                telefono: '',
                empresa: '',
                direccion: '',
            },
            moneda: 'USD',
            terminosPago: 'contado',
            diasValidez: 30,
            notas: 'Esta cotización incluye todos los impuestos aplicables. Precios válidos por 30 días. Términos de pago según lo acordado.',
        },

        init() {
            this.addItem();
            this.initSelect2();
            this.initFlatpickr();
        },

        // --- SELECT2 CLIENTE, TÉCNICO Y TIENDA ---
        initSelect2() {
            // Select2 para Cliente
            $('#clienteSelect').select2({
                placeholder: 'Buscar o seleccionar cliente...',
                allowClear: true,
                width: '100%',
                language: {
                    noResults: () => 'No se encontraron clientes',
                    searching: () => 'Buscando...',
                    loadingMore: () => 'Cargando más resultados...',
                    errorLoading: () => 'Error al cargar los resultados',
                },
                data: [
                    { id: 1, text: 'Tech Solutions S.A.', nombre: 'Tech Solutions S.A.', email: 'contacto@techsolutions.com', telefono: '+51 987 654 321', empresa: 'Tech Solutions S.A.', direccion: 'Av. Javier Prado 1234, San Isidro, Lima' },
                    { id: 2, text: 'Global Import Export', nombre: 'Global Import Export', email: 'ventas@globalimport.com', telefono: '+51 987 123 456', empresa: 'Global Import Export', direccion: 'Calle Los Olivos 567, Miraflores, Lima' },
                    { id: 3, text: 'Innovate Corp Perú', nombre: 'Innovate Corp Perú', email: 'info@innovatecorp.pe', telefono: '+51 987 789 123', empresa: 'Innovate Corp Perú', direccion: 'Av. La Marina 890, San Miguel, Lima' },
                    { id: 4, text: 'Servicios Integrales SAC', nombre: 'Servicios Integrales SAC', email: 'administracion@serviciosintegrales.com', telefono: '+51 987 456 789', empresa: 'Servicios Integrales SAC', direccion: 'Jr. Unión 234, Lima Centro' },
                    { id: 5, text: 'Constructora Andina S.A.', nombre: 'Constructora Andina S.A.', email: 'proyectos@constructoraandina.com', telefono: '+51 987 321 654', empresa: 'Constructora Andina S.A.', direccion: 'Av. Arequipa 1235, Lince, Lima' },
                ],
            });

            $('#clienteSelect').on('select2:select', (e) => {
                const selectedData = $('#clienteSelect').select2('data')[0];
                this.cargarDatosCliente(selectedData);
            });

            $('#clienteSelect').on('select2:clear', () => this.limpiarDatosCliente());

            // Select2 para Técnico
            $('#tecnicoSelect').select2({
                placeholder: 'Seleccionar técnico...',
                allowClear: true,
                width: '100%'
            }).on('change', (e) => {
                this.params.tecnico = e.target.value;
            });

            // Select2 para Tienda
            $('#tiendaSelect').select2({
                placeholder: 'Seleccionar tienda...',
                allowClear: true,
                width: '100%'
            }).on('change', (e) => {
                this.params.tienda = e.target.value;
            });
        },

        cargarDatosCliente(clienteData) {
            if (clienteData && clienteData.id) {
                const cliente = this.obtenerClientePorId(clienteData.id);
                if (cliente) {
                    this.params.cliente = {
                        id: cliente.id,
                        nombre: cliente.nombre,
                        email: cliente.email,
                        telefono: cliente.telefono,
                        empresa: cliente.empresa,
                        direccion: cliente.direccion,
                    };
                    toastr.success('Cliente seleccionado correctamente');
                }
            }
        },

        obtenerClientePorId(id) {
            const data = $('#clienteSelect').select2('data');
            return data.find((item) => item.id == id);
        },

        limpiarDatosCliente() {
            this.params.cliente = { id: '', nombre: '', email: '', telefono: '', empresa: '', direccion: '' };
            toastr.info('Cliente deseleccionado');
        },

        // --- FECHAS ---
        initFlatpickr() {
            const config = {
                locale: 'es',
                dateFormat: 'd/m/Y',
                allowInput: true,
                clickOpens: true,
                theme: 'airbnb',
                static: true,
                monthSelectorType: 'static',
                prevArrow: '<i class="fas fa-chevron-left"></i>',
                nextArrow: '<i class="fas fa-chevron-right"></i>',
                onReady(_, __, instance) {
                    instance.calendarContainer.style.zIndex = '9999';
                },
            };

            const fechaEmisionPicker = flatpickr('#fechaEmision', {
                ...config,
                defaultDate: this.params.fechaEmision,
                onChange: (dates) => {
                    this.params.fechaEmision = this.formatDateForAlpine(dates[0]);
                },
            });

            const validaHastaPicker = flatpickr('#validaHasta', {
                ...config,
                defaultDate: this.params.validaHasta,
                minDate: this.params.fechaEmision,
                onChange: (dates) => {
                    this.params.validaHasta = this.formatDateForAlpine(dates[0]);
                },
            });

            this.$watch('params.fechaEmision', (value) => {
                if (value) validaHastaPicker.set('minDate', new Date(value));
            });
        },

        formatDateForAlpine(date) {
            return date.toISOString().split('T')[0];
        },

        // --- ITEMS ---
        addItem() {
            const newId = this.items.length ? Math.max(...this.items.map((i) => i.id)) + 1 : 1;
            this.items.push({ id: newId, descripcion: '', cantidad: 1, precio: 0 });
        },

        removeItem(item) {
            if (this.items.length > 1) this.items = this.items.filter((i) => i.id !== item.id);
        },

        // --- TOTALES ---
        get subtotal() {
            return this.items.reduce((sum, i) => sum + (parseFloat(i.precio) || 0) * (parseInt(i.cantidad) || 0), 0);
        },
        get igv() {
            return this.subtotal * 0.18;
        },
        get total() {
            return this.subtotal + this.igv;
        },

        // --- ACCIONES ---
        guardarCotizacion() {
            toastr.success('Cotización guardada correctamente');
        },

        vistaPrevia() {
            toastr.info('Vista previa generada');
        },

        generarPDF() {
            toastr.success('PDF generado correctamente');
        },

        enviarEmail() {
            toastr.success('Email enviado correctamente');
        }
    }));
});

// --- CONFIGURACIÓN GLOBAL TOASTR ---
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right",
    timeOut: 3000,
    preventDuplicates: true
};