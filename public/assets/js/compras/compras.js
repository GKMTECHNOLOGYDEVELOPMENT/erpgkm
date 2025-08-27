document.addEventListener('alpine:init', () => {
    Alpine.data('compra', () => ({
        // Estado del componente
        codigoBarras: '',
        fecha: '',
        proveedorId: '',
        proveedores: [{
            id: 1,
            nombre: 'Proveedor A'
        },
        {
            id: 2,
            nombre: 'Proveedor B'
        },
        {
            id: 3,
            nombre: 'Proveedor C'
        },
        ],
        productos: [],
        modalAbierto: false,
        open: false, // AÑADIDO: propiedad open que falta
        productoEncontrado: null,
        cantidadProducto: 1,
        precioCompra: 0,
        
        // AÑADIDO: Nuevo objeto para producto
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
            precio_venta: 0
        },
        
        // AÑADIDO: Arrays de unidades y modelos
        unidades: [
            {id: 1, nombre: 'Unidad'},
            {id: 2, nombre: 'Kilogramo'},
            {id: 3, nombre: 'Litro'}
        ],
        modelos: [
            {id: 1, nombre: 'Modelo A'},
            {id: 2, nombre: 'Modelo B'},
            {id: 3, nombre: 'Modelo C'}
        ],

        // Computadas
        get subtotal() {
            return this.productos.reduce((sum, p) => sum + p.subtotal, 0);
        },

        get itbis() {
            return this.subtotal * 0.18;
        },

        get total() {
            return this.subtotal + this.itbis;
        },

        get puedeGuardar() {
            return this.proveedorId && this.productos.length > 0;
        },

        // Métodos
        async abrirModalVerificacion() {
            if (!this.codigoBarras) return;

            try {
                const response = await fetch(
                    `/buscar-articulo?codigo=${this.codigoBarras}`);
                const data = await response.json();

                if (data.existe) {
                    this.productoEncontrado = {
                        id: data.articulo.idArticulos,
                        codigo: data.articulo.codigo_barras,
                        codigo_barras: data.articulo.codigo_barras,
                        nombre: data.articulo.nombre,
                        stock: data.articulo.stock_total,
                        precio_compra: data.articulo.precio_compra,
                        precio_venta: data.articulo.precio_venta,
                        imagen: data.articulo.foto ?
                            `data:image/jpeg;base64,${data.articulo.foto}` : null
                    };
                    this.precioCompra = data.articulo.precio_compra;
                } else {
                    this.productoEncontrado = null;
                }

                this.modalAbierto = true;
                this.open = true; // AÑADIDO: establecer open en true
                this.cantidadProducto = 1;
            } catch (error) {
                console.error('Error al buscar producto:', error);
                alert('Error al buscar el producto');
            }
        },

        cerrarModal() {
            this.modalAbierto = false;
            this.open = false; // AÑADIDO: establecer open en false
            this.productoEncontrado = null;
        },

        agregarAlCarrito() {
            if (!this.productoEncontrado) return;

            const nuevoProducto = {
                id: this.productoEncontrado.id,
                codigo_barras: this.productoEncontrado.codigo_barras,
                nombre: this.productoEncontrado.nombre,
                cantidad: this.cantidadProducto,
                precio: this.precioCompra,
                subtotal: this.cantidadProducto * this.precioCompra,
                stock: this.productoEncontrado.stock,
                precio_venta: this.productoEncontrado.precio_venta
            };

            this.productos.push(nuevoProducto);
            this.cerrarModal();
            this.codigoBarras = '';
        },

        // AÑADIDO: Método para guardar nuevo producto
        guardarNuevoProducto() {
            // Aquí iría la lógica para guardar el nuevo producto
            console.log('Guardar nuevo producto:', this.nuevoProducto);
            
            // Luego agregar al carrito
            const nuevoProductoCarrito = {
                id: Date.now(), // ID temporal
                codigo_barras: this.nuevoProducto.codigo_barras,
                nombre: this.nuevoProducto.nombre,
                cantidad: 1,
                precio: this.nuevoProducto.precio_compra,
                subtotal: this.nuevoProducto.precio_compra,
                stock: this.nuevoProducto.stock,
                precio_venta: this.nuevoProducto.precio_venta
            };

            this.productos.push(nuevoProductoCarrito);
            this.cerrarModal();
            this.codigoBarras = '';
        },

        registrarNuevoProducto() {
            window.location.href = `/productos/nuevo?codigo=${this.codigoBarras}`;
        },

        actualizarSubtotal(producto) {
            producto.subtotal = producto.cantidad * producto.precio;
        },

        removerProducto(index) {
            this.productos.splice(index, 1);
        },

        formatCurrency(value) {
            return '$' + value.toFixed(2);
        },

        guardarCompra() {
            if (!this.puedeGuardar) return;

            const compraData = {
                fecha: this.fecha,
                proveedor_id: this.proveedorId,
                productos: this.productos,
                subtotal: this.subtotal,
                itbis: this.itbis,
                total: this.total
            };

            // Aquí iría la llamada AJAX para guardar en backend
            console.log('Datos a enviar:', compraData);
            alert('Compra guardada exitosamente');

            // Reset
            this.productos = [];
            this.proveedorId = '';
        },
        init() {
            flatpickr(this.$refs.fechaInput, {
                defaultDate: new Date(),
                dateFormat: 'Y-m-d',
                onChange: (selectedDates, dateStr) => {
                    this.fecha = dateStr;
                },
            });

            // Set fecha inicial
            this.fecha = new Date().toISOString().split('T')[0];
        }
    }));
});