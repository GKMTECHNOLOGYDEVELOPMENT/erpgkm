document.addEventListener('alpine:init', () => {
    Alpine.data('compra', () => ({
        // Estado del componente
        codigoBarras: '',
        serie: '',
        nro: '',
        fecha: '',
        fechaVencimiento: '',
        proveedorId: '',
        documentoId: '',
        monedaId: '',
        impuestoId: '',
        sujetoId: '',
        condicionCompraId: '',
        tipoPagoId: '',
        guardandoCompra: false, // Estado de loading para el botón

        // Arrays para datos de la API
        documentos: [],
        proveedores: [],
        monedas: [],
        impuestos: [],
        sujetos: [],
        condicionesCompra: [],
        tiposPago: [],
        productosEncontrados: [],
        selected: {},
        paginaActual: 1,
        resultadosPorPagina: 10,
        totalResultados: 0,

        productos: [],
        modalAbierto: false,
        open: false,
        productoEncontrado: null,
        cantidadProducto: 1,
        precioCompra: 0,
        tipoCambio: 4.05,

        nuevoProducto: {
            codigo_barras: '',
            sku: '',
            nombre: '',
            stock: 0,
            stock_minimo: 0,
            unidad: '',
            modelo: '',
            peso: 0,
            precio_compra: 0,
            precio_venta: 0,
        },

        unidades: [
            { id: 1, nombre: 'Unidad' },
            { id: 2, nombre: 'Kilogramo' },
            { id: 3, nombre: 'Litro' },
        ],
        modelos: [
            { id: 1, nombre: 'Modelo A' },
            { id: 2, nombre: 'Modelo B' },
            { id: 3, nombre: 'Modelo C' },
        ],

        // Computadas
        get subtotal() {
            return this.productos.reduce((sum, p) => sum + p.subtotal, 0);
        },

        get itbis() {
            const impuesto = this.impuestos.find((i) => i.id == this.impuestoId);
            const porcentaje = impuesto ? impuesto.monto / 100 : 0.18;
            return this.subtotal * porcentaje;
        },

        get total() {
            return this.subtotal + this.itbis;
        },

        get puedeGuardar() {
            return (
                this.documentoId &&
                this.proveedorId &&
                this.productos.length > 0 &&
                this.serie &&
                this.nro &&
                this.monedaId &&
                this.impuestoId &&
                !this.guardandoCompra
            );
        },

        get monedaSeleccionada() {
            return this.monedas.find((m) => m.id == this.monedaId);
        },

        get simboloMoneda() {
            const moneda = this.monedaSeleccionada;
            return moneda ? moneda.simbolo : 'S/';
        },

        // Métodos API
        async cargarDocumentos() {
            try {
                const response = await fetch('/api/documentos');
                const data = await response.json();

                if (data.success) {
                    this.documentos = data.data;
                } else {
                    console.error('Error al cargar documentos:', data.message);
                }
            } catch (error) {
                console.error('Error en la petición de documentos:', error);
            }
        },

        async cargarProveedores() {
            try {
                const response = await fetch('/api/getall-proveedores');
                const data = await response.json();

                if (data.success) {
                    this.proveedores = data.data;
                } else {
                    console.error('Error al cargar proveedores:', data.message);
                }
            } catch (error) {
                console.error('Error en la petición de proveedores:', error);
            }
        },

        async cargarMonedas() {
            try {
                const response = await fetch('/api/monedas');
                const data = await response.json();

                if (data.success) {
                    this.monedas = data.data;
                    // Seleccionar Soles por defecto si existe
                    const soles = this.monedas.find((m) => m.nombre.toLowerCase().includes('sol'));
                    if (soles && !this.monedaId) {
                        this.monedaId = soles.id;
                    }
                } else {
                    console.error('Error al cargar monedas:', data.message);
                }
            } catch (error) {
                console.error('Error en la petición de monedas:', error);
            }
        },

        async cargarImpuestos() {
            try {
                const response = await fetch('/api/impuestos');
                const data = await response.json();

                if (data.success) {
                    this.impuestos = data.data;
                    // Seleccionar IGV 18% por defecto si existe
                    const igv = this.impuestos.find((i) => i.monto == 18);
                    if (igv && !this.impuestoId) {
                        this.impuestoId = igv.id;
                    }
                } else {
                    console.error('Error al cargar impuestos:', data.message);
                }
            } catch (error) {
                console.error('Error en la petición de impuestos:', error);
            }
        },

        async cargarSujetos() {
            try {
                const response = await fetch('/api/sujetos');
                const data = await response.json();

                if (data.success) {
                    this.sujetos = data.data;
                    // Seleccionar "Ninguno" por defecto si existe
                    const ninguno = this.sujetos.find((s) => s.nombre.toLowerCase().includes('ninguno'));
                    if (ninguno && !this.sujetoId) {
                        this.sujetoId = ninguno.id;
                    }
                } else {
                    console.error('Error al cargar sujetos:', data.message);
                }
            } catch (error) {
                console.error('Error en la petición de sujetos:', error);
            }
        },

        async cargarCondicionesCompra() {
            try {
                const response = await fetch('/api/condiciones-compra');
                const data = await response.json();

                if (data.success) {
                    this.condicionesCompra = data.data;
                    // Seleccionar "Crédito" por defecto si existe
                    const credito = this.condicionesCompra.find((c) => c.nombre.toLowerCase().includes('crédito'));
                    if (credito && !this.condicionCompraId) {
                        this.condicionCompraId = credito.id;
                    }
                } else {
                    console.error('Error al cargar condiciones de compra:', data.message);
                }
            } catch (error) {
                console.error('Error en la petición de condiciones de compra:', error);
            }
        },

        async cargarTiposPago() {
            try {
                const response = await fetch('/api/tipos-pago');
                const data = await response.json();

                if (data.success) {
                    this.tiposPago = data.data;
                } else {
                    console.error('Error al cargar tipos de pago:', data.message);
                }
            } catch (error) {
                console.error('Error en la petición de tipos de pago:', error);
            }
        },

        async abrirModalVerificacion(pagina = 1) {
            // Si vino un evento (KeyboardEvent, MouseEvent), forzamos página = 1
            if (pagina && typeof pagina === 'object') pagina = 1;
            pagina = Number(pagina) || 1;

            if (!this.codigoBarras) return;

            this.productoEncontrado = null;
            this.productosEncontrados = [];
            this.paginaActual = pagina;
            this.modalAbierto = true;
            this.open = true;

            try {
                const url = `/buscar-articulo?q=${encodeURIComponent(this.codigoBarras)}&tipo=texto&page=${this.paginaActual}&perPage=${this.resultadosPorPagina}`;
                const response = await fetch(url);
                const data = await response.json();

                if (data.existe) {
                    this.productosEncontrados = data.productos;
                    this.totalResultados = Number(data.total) || 0;

                    if (data.productos.length === 1) {
                        this.seleccionarProducto(data.productos[0]);
                    }
                } else {
                    this.productosEncontrados = [];
                    this.totalResultados = 0;
                }
            } catch (e) {
                console.error('Error al buscar producto:', e);
                alert('Error al buscar el producto');
            }
        },
        get seleccionadosCount() {
            return Object.keys(this.selected).length;
        },

        convertirPrecio(precio) {
            const moneda = this.monedaSeleccionada;
            if (!moneda || moneda.nombre.toLowerCase().includes('sol')) {
                return precio;
            } else if (moneda.nombre.toLowerCase().includes('dólar')) {
                return precio / this.tipoCambio;
            } else if (moneda.nombre.toLowerCase().includes('euro')) {
                return precio / (this.tipoCambio * 0.85);
            }
            return precio;
        },

        cambiarMoneda() {
            this.productos.forEach((producto) => {
                producto.precio = this.convertirPrecio(producto.precioBase || producto.precio);
            });

            if (this.productoEncontrado) {
                this.precioCompra = this.convertirPrecio(this.productoEncontrado.precio_compra);
            }
        },

        isSelected(producto) {
            return !!this.selected[producto.idArticulos];
        },

        toggleSeleccion(producto) {
            const id = producto.idArticulos;
            if (this.isSelected(producto)) {
                delete this.selected[id];
            } else {
                // Guarda el producto tal como viene del backend (para agregar luego)
                this.selected[id] = producto;
            }
        },

        agregarSeleccionados() {
            const ids = Object.keys(this.selected);
            if (!ids.length) return;

            ids.forEach((id) => {
                const p = this.selected[id];

                // Evitar duplicados en this.productos
                const idx = this.productos.findIndex((x) => x.id === p.idArticulos);
                const precioConv = this.convertirPrecio(p.precio_compra);

                if (idx >= 0) {
                    this.productos[idx].cantidad += 1;
                    this.actualizarSubtotal(this.productos[idx]);
                } else {
                    this.productos.push({
                        id: p.idArticulos,
                        codigo_barras: p.codigo_barras,
                        nombre: p.nombre,
                        cantidad: 1,
                        precio: precioConv,
                        precioBase: p.precio_compra,
                        subtotal: precioConv,
                        stock: p.stock_total,
                        precio_venta: p.precio_venta,
                    });
                }
            });

            // limpiar selección y cerrar modal
            this.selected = {};
            this.cerrarModal();
            this.codigoBarras = '';
        },
        agregarAlCarrito() {
            if (!this.productoEncontrado) return;

            const nuevoProducto = {
                id: this.productoEncontrado.id,
                codigo_barras: this.productoEncontrado.codigo_barras,
                nombre: this.productoEncontrado.nombre,
                cantidad: this.cantidadProducto,
                precio: this.precioCompra,
                precioBase: this.productoEncontrado.precio_compra,
                subtotal: this.cantidadProducto * this.precioCompra,
                stock: this.productoEncontrado.stock,
                precio_venta: this.productoEncontrado.precio_venta,
            };

            this.productos.push(nuevoProducto);
            this.cerrarModal();
            this.codigoBarras = '';
        },
        seleccionarProducto(producto) {
            this.productoEncontrado = {
                id: producto.idArticulos,
                codigo_barras: producto.codigo_barras,
                nombre: producto.nombre,
                stock: producto.stock_total,
                precio_compra: producto.precio_compra,
                precio_venta: producto.precio_venta,
            };
            this.precioCompra = this.convertirPrecio(producto.precio_compra);
            this.cantidadProducto = 1;
        },

        guardarNuevoProducto() {
            console.log('Guardar nuevo producto:', this.nuevoProducto);

            const nuevoProductoCarrito = {
                id: Date.now(),
                codigo_barras: this.nuevoProducto.codigo_barras,
                nombre: this.nuevoProducto.nombre,
                cantidad: 1,
                precio: this.convertirPrecio(this.nuevoProducto.precio_compra),
                precioBase: this.nuevoProducto.precio_compra,
                subtotal: this.convertirPrecio(this.nuevoProducto.precio_compra),
                stock: this.nuevoProducto.stock,
                precio_venta: this.nuevoProducto.precio_venta,
            };

            this.productos.push(nuevoProductoCarrito);
            this.cerrarModal();
            this.codigoBarras = '';
        },

        cerrarModal() {
            this.modalAbierto = false;
            this.open = false;
            this.productoEncontrado = null;
        },

        actualizarSubtotal(producto) {
            producto.subtotal = producto.cantidad * producto.precio;
        },

        removerProducto(index) {
            this.productos.splice(index, 1);
        },

        formatCurrency(value) {
            return `${this.simboloMoneda}${value.toFixed(2)}`;
        },

        async guardarCompra() {
            if (!this.puedeGuardar) {
                alert('Por favor complete todos los campos obligatorios');
                return;
            }

            // Activar estado de loading
            this.guardandoCompra = true;

            try {
                const compraData = {
                    serie: this.serie,
                    nro: parseInt(this.nro),
                    fecha: this.fecha,
                    fecha_vencimiento: this.fechaVencimiento,
                    documento_id: this.documentoId,
                    proveedor_id: this.proveedorId,
                    moneda_id: this.monedaId,
                    impuesto_id: this.impuestoId,
                    sujeto_id: this.sujetoId,
                    condicion_compra_id: this.condicionCompraId,
                    tipo_pago_id: this.tipoPagoId,
                    productos: this.productos,
                    subtotal: this.subtotal,
                    igv: this.itbis,
                    total: this.total,
                    tipo_cambio: this.tipoCambio,
                };

                const response = await fetch('/api/guardar-compra', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(compraData),
                });

                const result = await response.json();

                if (result.success) {
                    alert('Compra guardada exitosamente');
                    this.resetFormulario();
                } else {
                    alert('Error al guardar la compra: ' + result.message);
                }
            } catch (error) {
                console.error('Error al guardar compra:', error);
                alert('Error al guardar la compra');
            } finally {
                // Desactivar estado de loading siempre
                this.guardandoCompra = false;
            }
        },

        resetFormulario() {
            this.productos = [];
            this.proveedorId = '';
            this.documentoId = '';
            this.serie = '';
            this.nro = '';
            this.fechaVencimiento = '';
            this.tipoPagoId = '';

            // Resetear Select2
            if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
                $('#proveedorSelect').val(null).trigger('change');
            }
        },

        inicializarSelect2() {
            if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
                $('#proveedorSelect').select2({
                    placeholder: 'Seleccione un proveedor',
                    allowClear: true,
                    language: 'es',
                });

                $('#proveedorSelect').on('change', (e) => {
                    this.proveedorId = e.target.value;
                });
            } else {
                console.error('jQuery o Select2 no están cargados correctamente');
            }
        },

        init() {
            // Cargar todos los datos al inicializar
            this.cargarDocumentos();
            this.cargarProveedores();
            this.cargarMonedas();
            this.cargarImpuestos();
            this.cargarSujetos();
            this.cargarCondicionesCompra();
            this.cargarTiposPago();

            // Flatpickr para Fecha Emisión
            flatpickr(this.$refs.fechaInput, {
                defaultDate: new Date(),
                dateFormat: 'Y-m-d',
                onChange: (selectedDates, dateStr) => {
                    this.fecha = dateStr;
                },
            });

            // Flatpickr para Fecha Vencimiento
            flatpickr(this.$refs.fechaVencimientoInput, {
                defaultDate: new Date(),
                dateFormat: 'Y-m-d',
                onChange: (selectedDates, dateStr) => {
                    this.fechaVencimiento = dateStr;
                },
            });

            // Set fechas iniciales
            this.fecha = new Date().toISOString().split('T')[0];
            this.fechaVencimiento = new Date().toISOString().split('T')[0];

            // Inicializar Select2 después de que Alpine haya renderizado
            this.$nextTick(() => {
                this.inicializarSelect2();
            });
        },
    }));
});
