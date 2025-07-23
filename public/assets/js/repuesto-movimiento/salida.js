document.addEventListener('alpine:init', () => {
    Alpine.data('repuestos', () => ({
        codigoRepuesto: '',
        clienteNombre: 'Público General',
        fecha: '',
        proveedorId: '',
        areaId: '',
        proveedores: [
            { id: 1, nombre: 'Proveedor A' },
            { id: 2, nombre: 'Proveedor B' },
            { id: 3, nombre: 'Proveedor C' },
        ],
        areas: [
            { id: 1, nombre: 'Taller' },
            { id: 2, nombre: 'Almacén' },
            { id: 3, nombre: 'Soporte Técnico' },
        ],
        modelos: window.modelos || [],
        subcategorias: window.subcategorias || [],
        repuestos: [],
        modalAbierto: false,
        repuestoEncontrado: null,
        cantidadRepuesto: 1,
        precioCompra: 0,
        nuevoRepuesto: {
            codigo_repuesto: '',
            codigo_barras: '',
            nombre: '',
            stock_total: 0,
            stock_minimo: 0,
            precio_compra: 0,
            precio_venta: 0,
            idModelo: [],
            idsubcategoria: '',
            sku: '',
        },

        get subtotal() {
            return this.repuestos.reduce((sum, p) => sum + p.subtotal, 0);
        },

        get itbis() {
            return this.subtotal * 0.18;
        },

        get total() {
            return this.subtotal + this.itbis;
        },

        get puedeGuardar() {
            return this.proveedorId && this.areaId && this.repuestos.length > 0;
        },

        async abrirModalVerificacion() {
            if (!this.codigoRepuesto) return;

            try {
                const response = await fetch(`/buscar-repuesto?codigo=${this.codigoRepuesto}`);
                const data = await response.json();

                if (data.existe) {
                    this.repuestoEncontrado = {
                        id: data.articulo.idArticulos,
                        codigo_repuesto: data.articulo.codigo_repuesto,
                        codigo_barras: data.articulo.codigo_barras,
                        nombre: data.subcategoria,
                        stock_total: data.articulo.stock_total,
                        stock_minimo: data.articulo.stock_minimo,
                        precio_compra: data.articulo.precio_compra,
                        precio_venta: data.articulo.precio_venta,
                        modelo: data.modelos.length ? data.modelos.join(' / ') : '',
                        pulgadas: data.articulo.pulgadas,
                        idModelo: data.articulo.idModelo,
                        subcategoria: data.subcategoria
                    };
                    this.precioCompra = data.articulo.precio_compra;
                } else {
                    this.repuestoEncontrado = null;
                }

                this.modalAbierto = true;
                this.cantidadRepuesto = 1;
            } catch (error) {
                console.error('Error al buscar repuesto:', error);
                alert('Error al buscar el repuesto');
            }
        },

        cerrarModal() {
            this.modalAbierto = false;
            this.repuestoEncontrado = null;
        },

        agregarAlRegistro() {
            if (!this.repuestoEncontrado) return;

            if (this.repuestoEncontrado.stock_total <= 0) {
                Swal.fire({ icon: 'error', title: 'Cantidad inválida', text: 'La cantidad debe ser mayor que cero' });
                return;
            }

            if (parseFloat(this.precioCompra) > parseFloat(this.repuestoEncontrado.precio_venta)) {
                Swal.fire({ icon: 'error', title: 'Precio inválido', text: 'El precio de compra no puede ser mayor al precio de venta' });
                return;
            }

            if (this.precioCompra < 0) {
                Swal.fire({ icon: 'error', title: 'Precio inválido', text: 'El precio de compra no puede ser negativo' });
                return;
            }

            const nuevoRepuesto = {
                id: this.repuestoEncontrado.id,
                codigo_repuesto: this.repuestoEncontrado.codigo_repuesto,
                codigo_barras: this.repuestoEncontrado.codigo_barras,
                nombre: this.repuestoEncontrado.nombre,
                cantidad: this.repuestoEncontrado.stock_total,
                precio: this.precioCompra,
                subtotal: this.repuestoEncontrado.stock_total * this.precioCompra,
                stock_total: this.repuestoEncontrado.stock_total,
                precio_venta: this.repuestoEncontrado.precio_venta,
                modelo: this.repuestoEncontrado.modelo,
                pulgadas: this.repuestoEncontrado.pulgadas
            };

            this.repuestos.push(nuevoRepuesto);

            Swal.fire({
                icon: 'success',
                title: 'Repuesto agregado',
                text: 'El repuesto fue agregado al registro correctamente',
                timer: 1500,
                showConfirmButton: false
            });

            this.cerrarModal();
            this.codigoRepuesto = '';
        },

        actualizarPrecioCompra() {
            if (this.repuestoEncontrado) {
                this.repuestoEncontrado.precio_compra = this.precioCompra;
            }
        },

        actualizarSubtotal(repuesto) {
            repuesto.subtotal = repuesto.cantidad * repuesto.precio;
        },

        removerRepuesto(index) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Este repuesto será eliminado del registro.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.repuestos.splice(index, 1);
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        text: 'El repuesto fue eliminado correctamente',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        },

        formatCurrency(value) {
            return 'S/ ' + value.toFixed(2);
        },

        guardarRegistro() {
            if (!this.puedeGuardar) return;

            const registroData = {
                fecha: this.fecha,
                proveedor_id: this.proveedorId,
                repuestos: this.repuestos,
                subtotal: this.subtotal,
                itbis: this.itbis,
                total: this.total
            };

            console.log('Datos a enviar:', registroData);
            alert('Registro de repuestos guardado exitosamente');

            this.repuestos = [];
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
            this.fecha = new Date().toISOString().split('T')[0];
        }
    }));
});

$(document).ready(function () {
    $('#modelosSelect').select2({
        placeholder: "Seleccione modelos compatibles",
        allowClear: true,
        templateResult: function (modelo) {
            if (!modelo.id) return modelo.text;
            const parts = modelo.text.split(' - ');
            return $('<span>').text(parts.join(' - '));
        },
        templateSelection: function (modelo) {
            if (!modelo.id) return modelo.text;
            return modelo.text.split(' - ')[0];
        },
        escapeMarkup: function (m) {
            return m;
        }
    });
});
