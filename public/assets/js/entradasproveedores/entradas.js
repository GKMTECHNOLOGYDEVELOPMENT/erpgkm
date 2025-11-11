document.addEventListener('alpine:init', () => {
    Alpine.data('entrada', () => ({
        // Estado del componente para entradas
        codigoBarras: '',
        productos: [],
        productosSeleccionados: [], // ← AÑADE ESTA LÍNEA
        tipoEntrada: '',
        fechaIngreso: new Date().toISOString().split('T')[0],
        clienteGeneral: '',
        observaciones: '',
        archivoAdjunto: null,
        guardandoEntrada: false,
        
        // Modal
        modalAbierto: false,
        modalCargando: false,
        productosEncontrados: [],
        productoSeleccionado: null,
        cantidadProducto: 1,
        
        // Inicialización
        init() {
            console.log('Sistema de entradas de proveedores inicializado');
            
            // Configurar el manejo de archivos
            this.$watch('archivoAdjunto', (value) => {
                console.log('Archivo adjunto actualizado:', value);
            });
        },
        
      // En tu método abrirModalVerificacion, agrega más logs:
async abrirModalVerificacion() {
    const busqueda = this.codigoBarras.trim();
    console.log('Búsqueda iniciada:', busqueda);
    
    if (!busqueda) {
        toastr.warning('Ingrese un nombre, modelo o marca para buscar');
        return;
    }
    
    this.modalAbierto = true;
    this.modalCargando = true;
    this.productosEncontrados = [];
    this.productoSeleccionado = null;
    
    try {
        console.log('Realizando fetch a:', `/buscar-producto-entrada?q=${encodeURIComponent(busqueda)}`);
        
        const response = await fetch(`/buscar-producto-entrada?q=${encodeURIComponent(busqueda)}`);
        const data = await response.json();
        
        console.log('Respuesta COMPLETA del servidor:', data);
        
        if (data.success && data.productos.length > 0) {
            this.productosEncontrados = data.productos;
            console.log('Productos encontrados - DETALLES:', data.productos);
            
            // Log detallado de cada producto encontrado
            data.productos.forEach((producto, index) => {
                console.log(`📦 Producto ${index + 1}:`, {
                    id: producto.id,
                    nombre: producto.nombre,
                    codigo_repuesto: producto.codigo_repuesto,
                    modelo: producto.modelo,
                    marca: producto.marca,
                    subcategoria: producto.subcategoria,
                    codigo_barras: producto.codigo_barras,
                    sku: producto.sku,
                    stock_total: producto.stock_total
                });
            });
            
            toastr.success(`Se encontraron ${data.productos.length} producto(s)`);
        } else {
            console.log('❌ No se encontraron productos. Respuesta completa:', data);
            console.log('🔍 Query utilizada:', busqueda);
            
            if (data.debug_verificacion) {
                console.log('🐛 Datos de verificación:', data.debug_verificacion);
            }
            
            toastr.info('No se encontraron productos con ese criterio');
            this.productosEncontrados = [];
        }
    } catch (error) {
        console.error('💥 Error al buscar productos:', error);
        toastr.error('Error al buscar productos');
        this.productosEncontrados = [];
    } finally {
        this.modalCargando = false;
    }
},
        
        // Seleccionar producto del modal
        seleccionarProducto(producto) {
    const index = this.productosSeleccionados.findIndex(p => p.id === producto.id);
    
    if (index === -1) {
        // Agregar a selección
        this.productosSeleccionados.push({
            ...producto,
            cantidadModal: 1 // Cantidad temporal para el modal
        });
    } else {
        // Remover de selección
        this.productosSeleccionados.splice(index, 1);
    }
},
// Verificar si un producto está seleccionado
estaSeleccionado(producto) {
    return this.productosSeleccionados.some(p => p.id === producto.id);
},

// Limpiar todas las selecciones
limpiarSelecciones() {
    this.productosSeleccionados = [];
},

// Agregar productos seleccionados a la lista principal
agregarProductosSeleccionados() {
    if (this.productosSeleccionados.length === 0) {
        toastr.warning('Seleccione al menos un producto');
        return;
    }

    this.productosSeleccionados.forEach(productoSeleccionado => {
        // Verificar si el producto ya está en la lista
        const existe = this.productos.find(p => p.id === productoSeleccionado.id);
        const cantidad = productoSeleccionado.cantidadModal || 1;
        
        if (existe) {
            existe.cantidad += parseInt(cantidad);
            toastr.info(`Cantidad actualizada para ${productoSeleccionado.nombre}`);
        } else {
            this.productos.push({
                id: productoSeleccionado.id,
                codigo_barras: productoSeleccionado.codigo_barras,
                nombre: productoSeleccionado.nombre,
                codigo_repuesto: productoSeleccionado.codigo_repuesto, // ← Añade esta línea
                marca: productoSeleccionado.marca,
                modelo: productoSeleccionado.modelo,
                stock_actual: productoSeleccionado.stock_total,
                cantidad: parseInt(cantidad)
            });
            toastr.success(`Producto agregado: ${productoSeleccionado.nombre}`);
        }
    });

    this.cerrarModal();
},
// Actualizar cantidad en el modal
actualizarCantidadModal(producto, event) {
    const nuevaCantidad = parseInt(event.target.value);
    const productoSeleccionado = this.productosSeleccionados.find(p => p.id === producto.id);
    
    if (productoSeleccionado) {
        if (nuevaCantidad && nuevaCantidad > 0) {
            productoSeleccionado.cantidadModal = nuevaCantidad;
        } else {
            event.target.value = productoSeleccionado.cantidadModal || 1;
            toastr.warning('Ingrese una cantidad válida');
        }
    }
},
        
        // Agregar producto a la lista
        agregarProducto() {
            if (!this.productoSeleccionado) {
                toastr.warning('Seleccione un producto primero');
                return;
            }
            
            if (!this.cantidadProducto || this.cantidadProducto < 1) {
                toastr.warning('Ingrese una cantidad válida');
                return;
            }
            
            // Verificar si el producto ya está en la lista
            const existe = this.productos.find(p => p.id === this.productoSeleccionado.id);
            if (existe) {
                existe.cantidad += parseInt(this.cantidadProducto);
                toastr.info(`Cantidad actualizada para ${this.productoSeleccionado.nombre}`);
            } else {
                this.productos.push({
                    id: this.productoSeleccionado.id,
                    codigo_barras: this.productoSeleccionado.codigo_barras,
                    nombre: this.productoSeleccionado.nombre,
                    marca: this.productoSeleccionado.marca,
                    modelo: this.productoSeleccionado.modelo,
                    stock_actual: this.productoSeleccionado.stock_total,
                    cantidad: parseInt(this.cantidadProducto)
                });
                toastr.success(`Producto agregado: ${this.productoSeleccionado.nombre}`);
            }
            
            this.cerrarModal();
        },
        
        // Remover producto de la lista
        removerProducto(index) {
            const producto = this.productos[index];
            this.productos.splice(index, 1);
            toastr.info(`Producto removido: ${producto.nombre}`);
        },
        
        // Actualizar cantidad de un producto
        actualizarCantidad(producto, event) {
            const nuevaCantidad = parseInt(event.target.value);
            if (nuevaCantidad && nuevaCantidad > 0) {
                producto.cantidad = nuevaCantidad;
            } else {
                event.target.value = producto.cantidad;
                toastr.warning('Ingrese una cantidad válida');
            }
        },
        
        // Cerrar modal
        cerrarModal() {
            this.modalAbierto = false;
            this.productosEncontrados = [];
            this.productosSeleccionados = [];
            this.productoSeleccionado = null;
            this.codigoBarras = '';
            this.cantidadProducto = 1;
            this.modalCargando = false;
        },
        
        // Establecer archivo adjunto
        establecerArchivo(archivo) {
            this.archivoAdjunto = archivo;
        },
        
        // Limpiar archivo adjunto
        limpiarArchivo() {
            this.archivoAdjunto = null;
        },
        
        // Guardar entrada completa
        async guardarEntrada() {
            // Validaciones básicas
            if (!this.tipoEntrada) {
                toastr.error('Seleccione el tipo de entrada');
                return;
            }
            
            if (!this.fechaIngreso) {
                toastr.error('Ingrese la fecha de ingreso');
                return;
            }
            
            if (this.productos.length === 0) {
                toastr.error('Agregue al menos un producto');
                return;
            }
            
            // Validar que todas las cantidades sean válidas
            const cantidadesValidas = this.productos.every(p => p.cantidad && p.cantidad > 0);
            if (!cantidadesValidas) {
                toastr.error('Todas las cantidades deben ser mayores a 0');
                return;
            }
            
            this.guardandoEntrada = true;
            
            try {
                // Preparar datos del formulario
                const formData = new FormData();
                formData.append('tipo_entrada', this.tipoEntrada);
                formData.append('fecha_ingreso', this.fechaIngreso);
                formData.append('cliente_general_id', this.clienteGeneral || '');
                formData.append('observaciones', this.observaciones || '');
                formData.append('productos', JSON.stringify(this.productos));
                
                // Agregar archivo si existe
                if (this.archivoAdjunto) {
                    formData.append('archivo', this.archivoAdjunto);
                }
                
                // Obtener token CSRF
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    throw new Error('Token CSRF no encontrado');
                }
                
                // Enviar datos al servidor
                const response = await fetch('/guardar-entrada-proveedor', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    toastr.success('Entrada guardada exitosamente');
                    this.limpiarFormulario();
                    
                    // Opcional: redirigir o actualizar la vista
                    setTimeout(() => {
                        // window.location.reload();
                    }, 1500);
                } else {
                    toastr.error(result.message || 'Error al guardar la entrada');
                }
            } catch (error) {
                console.error('Error al guardar entrada:', error);
                toastr.error('Error de conexión al guardar la entrada');
            } finally {
                this.guardandoEntrada = false;
            }
        },
        
        // Limpiar formulario después de guardar
        limpiarFormulario() {
            this.tipoEntrada = '';
            this.fechaIngreso = new Date().toISOString().split('T')[0];
            this.clienteGeneral = '';
            this.observaciones = '';
            this.productos = [];
            this.archivoAdjunto = null;
            
            // Limpiar el input de archivo si existe
            const fileInput = document.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.value = '';
            }
        }
    }));
});

// Inicializar componentes después de que se cargue la página
document.addEventListener('DOMContentLoaded', function() {
    // Configurar toastr
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: 3000,
        extendedTimeOut: 1000,
        showMethod: 'slideDown',
        hideMethod: 'slideUp'
    };
    
    console.log('Sistema de entradas de proveedores cargado correctamente');
});