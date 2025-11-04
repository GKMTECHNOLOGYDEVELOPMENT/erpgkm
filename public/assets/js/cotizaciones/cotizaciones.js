document.addEventListener('alpine:init', () => {
    console.log('✅ Alpine:init ejecutándose');

    Alpine.data('cotizacionAdd', () => ({
        mostrarNGR: false,
        items: [],
        monedas: [],
        articulos: [],
        simbolosMonedas: {},
        selectCounter: 0,
        articulosCargados: false,
        incluirIGV: true,
        params: {
            cotizacionNo: 'COT-' + new Date().getFullYear() + '-' + Math.random().toString().substr(2, 4),
            fechaEmision: new Date().toISOString().split('T')[0],
            validaHasta: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
            ticket: {
                id: '',
                numero_ticket: '',
                tecnico_nombre: '',
                tienda_nombre: '',
                fecha_llegada: '',
                descripcion: '',
                visitas: [],
                serie_equipo: '',
                mostrar_serie: false
            },
            visita_seleccionada: '',
            serie: '',
            ot: '',
            cliente: {
                id: '',
                nombre: '',
                email: '',
                telefono: '',
                empresa: '',
                direccion: '',
            },
            moneda: '',
            terminosPago: '',
            diasValidez: 30,
            notas: 'Esta cotización incluye todos los impuestos aplicables. Precios válidos por 30 días. Términos de pago según lo acordado.',
        },

        async init() {
            console.log('✅ Alpine init() ejecutado');

            // Primero cargar los artículos
            await this.cargarArticulos();

            // Luego agregar el primer item
            this.addItem();

            // Inicializar el resto
            this.initSelect2();
            this.initFlatpickr();
            this.cargarConfiguracion();

            // Configurar IGV por defecto como true
            this.incluirIGV = true;
            console.log('🎯 IGV inicializado en:', this.incluirIGV);

            this.$watch('params.moneda', (nuevaMoneda, monedaAnterior) => {
                console.log('🎯 Moneda cambiada:', monedaAnterior, '→', nuevaMoneda);
                this.actualizarMonedaEnUI();
            });

            this.$watch('params.visita_seleccionada', (value) => {
                if (value) {
                    this.onVisitaSeleccionada(value);
                    // 🔥 CARGAR SUMINISTROS CUANDO CAMBIA LA VISITA
                    setTimeout(() => {
                        this.cargarSuministrosAutomaticamente();
                    }, 300);
                }
            });

            this.$watch('mostrarNGR', (value) => {
                if (value) {
                    console.log('🎯 NGR activado - Cargando tickets disponibles');
                    this.cargarTodosLosTickets();
                } else {
                    console.log('🎯 NGR desactivado - Limpiando tickets');
                    this.limpiarTickets();
                }
            });

            // 🔥 OBSERVAR CAMBIOS EN EL TICKET PARA CARGAR SUMINISTROS
            this.$watch('params.ticket.id', (value) => {
                if (value && this.mostrarNGR) {
                    console.log('🎯 Ticket seleccionado, cargando suministros...');
                    // Esperar un poco para que se carguen las visitas primero
                    setTimeout(() => {
                        this.cargarSuministrosAutomaticamente();
                    }, 500);
                }
            });
        },

        // 🔥 MÉTODO MEJORADO: Para cargar suministros automáticamente
        async cargarSuministrosAutomaticamente() {
            if (!this.params.ticket.id) return;

            try {
                console.log('🔄 Cargando suministros automáticamente...', {
                    ticketId: this.params.ticket.id,
                    visitaId: this.params.visita_seleccionada
                });

                const url = this.params.visita_seleccionada
                    ? `/api/tickets/${this.params.ticket.id}/suministros/${this.params.visita_seleccionada}`
                    : `/api/tickets/${this.params.ticket.id}/suministros`;

                const response = await fetch(url);
                const data = await response.json();

                console.log('📋 Respuesta de suministros:', data);

                if (data.success && data.suministros && data.suministros.length > 0) {
                    // Limpiar items existentes pero mantener al menos uno si estaba vacío
                    if (this.items.length === 1 && !this.items[0].articulo_data) {
                        this.items = [];
                    } else {
                        this.items = [];
                    }

                    // Agregar cada suministro como un item
                    data.suministros.forEach(suministro => {
                        this.agregarSuministroComoItem(suministro);
                    });

                    console.log('✅ Suministros cargados automáticamente:', data.suministros.length);
                    toastr.success(`${data.suministros.length} suministros cargados automáticamente`);

                    // 🔥 INICIALIZAR SELECT2 DESPUÉS DE AGREGAR TODOS LOS ITEMS
                    setTimeout(() => {
                        this.inicializarSelect2ParaTodosLosItems();
                    }, 500);

                } else {
                    console.log('ℹ️ No hay suministros para esta combinación');
                    if (this.items.length === 0) {
                        this.addItem();
                    }
                    toastr.info('No se encontraron suministros. Puede agregar items manualmente.');
                }

            } catch (error) {
                console.error('❌ Error al cargar suministros:', error);
                toastr.error('Error al cargar suministros automáticamente');
            }
        },

        // 🔥 MÉTODO CORREGIDO: Para agregar suministro como item y actualizar Select2
        agregarSuministroComoItem(suministro) {
            const newId = this.items.length ? Math.max(...this.items.map(i => i.id)) + 1 : 1;

            // Buscar el artículo completo en la lista de artículos
            const articuloCompleto = this.articulos.find(a => a.idArticulos == suministro.idArticulos);

            let descripcion = '';
            if (articuloCompleto) {
                const tipo = parseInt(articuloCompleto.idTipoArticulo);
                switch (tipo) {
                    case 2: descripcion = articuloCompleto.codigo_repuesto || `Repuesto ${articuloCompleto.idArticulos}`; break;
                    default: descripcion = articuloCompleto.nombre || `Artículo ${articuloCompleto.idArticulos}`;
                }
            } else {
                descripcion = suministro.nombre || `Artículo ${suministro.idArticulos}`;
            }

            const nuevoItem = {
                id: newId,
                articulo_id: suministro.idArticulos,
                descripcion: descripcion,
                cantidad: suministro.cantidad_suministro || 1,
                precio: 0,
                codigo_repuesto: suministro.codigo_repuesto || '',
                articulo_data: articuloCompleto || {
                    idArticulos: suministro.idArticulos,
                    nombre: suministro.nombre,
                    codigo_repuesto: suministro.codigo_repuesto,
                    precio_venta: suministro.precio_venta,
                    idTipoArticulo: suministro.idTipoArticulo
                }
            };

            // Calcular precio según configuración de IGV
            if (suministro.precio_venta) {
                nuevoItem.precio = this.incluirIGV ? parseFloat(suministro.precio_venta) : (parseFloat(suministro.precio_venta) / 1.18);
            }

            this.items.push(nuevoItem);
            console.log('➕ Suministro agregado como item:', nuevoItem);

            // 🔥 ACTUALIZAR SELECT2 VISUALMENTE DESPUÉS DE AGREGAR EL ITEM
            this.$nextTick(() => {
                setTimeout(() => {
                    this.actualizarSelect2ParaItem(nuevoItem.id, suministro.idArticulos);
                }, 100);
            });
        },

        // 🔥 NUEVO MÉTODO: Actualizar Select2 visualmente para un item específico
        actualizarSelect2ParaItem(itemId, articuloId) {
            const itemIndex = this.items.findIndex(item => item.id === itemId);
            if (itemIndex === -1) return;

            const selectElement = document.querySelector(`#articulo-select-${itemId}`);
            if (selectElement && $(selectElement).hasClass('select2-hidden-accessible')) {
                // Establecer el valor en el Select2
                $(selectElement).val(articuloId).trigger('change');
                console.log('✅ Select2 actualizado visualmente para item:', itemId, 'con artículo:', articuloId);
            } else {
                console.log('⚠️ Select2 no encontrado para item:', itemId);
                // Reintentar si no está listo
                setTimeout(() => {
                    this.actualizarSelect2ParaItem(itemId, articuloId);
                }, 200);
            }
        },

        // 🔥 BOTÓN PARA CARGAR SUMINISTROS MANUALMENTE
        cargarSuministrosManual() {
            this.cargarSuministrosAutomaticamente();
        },

        // Método para toggle de IGV
        toggleIGV() {
            this.incluirIGV = !this.incluirIGV;
            console.log('🎯 IGV cambiado a:', this.incluirIGV ? 'CON IGV' : 'SIN IGV');
            this.actualizarPreciosPorIGV();
        },

        // Método mejorado para actualizar precios
        actualizarPreciosPorIGV() {
            console.log('🔄 Actualizando precios - IGV:', this.incluirIGV ? 'INCLUIDO' : 'NO INCLUIDO');

            this.items.forEach((item, index) => {
                if (item.articulo_data && item.articulo_data.precio_venta) {
                    const precioBase = parseFloat(item.articulo_data.precio_venta);

                    // CALCULO CORREGIDO: Si incluirIGV es true, mostramos precio con IGV
                    // Si es false, mostramos precio sin IGV
                    item.precio = this.incluirIGV ? precioBase : (precioBase / 1.18);

                    console.log(`📊 Item ${index + 1} - Base: ${precioBase}, Mostrado: ${item.precio}, Con IGV: ${this.incluirIGV}`);
                }
            });

            // Forzar actualización de la UI
            this.items = [...this.items];

            const mensaje = this.incluirIGV
                ? '✅ IGV 18% INCLUIDO en los precios'
                : 'ℹ️ IGV 18% NO INCLUIDO en los precios';

            toastr.info(mensaje);
        },

        // Método para actualizar totales cuando cambian cantidades o precios
        actualizarTotales() {
            // Forzar recálculo
            this.items = [...this.items];
            console.log('🔄 Totales actualizados');
        },

        // MÉTODOS DE MONEDA
        obtenerSimboloMoneda() {
            if (!this.params.moneda) return '$';
            const monedaSeleccionada = this.monedas.find(m => m.idMonedas == this.params.moneda);
            return monedaSeleccionada ? monedaSeleccionada.simbolo : 'S/';
        },

        actualizarMonedaEnUI() {
            console.log('🔄 Actualizando símbolos de moneda en UI');
            const simbolo = this.obtenerSimboloMoneda();
            this.items = [...this.items];
            const monedaNombre = this.monedas.find(m => m.idMonedas == this.params.moneda)?.nombre || 'Moneda';
            toastr.info(`Moneda cambiada a: ${monedaNombre} (${simbolo})`);
        },

        // MÉTODOS DE ARTÍCULOS
        async cargarArticulos() {
            try {
                console.log('🔄 Cargando artículos...');
                const response = await fetch('/api/articulos/cotizaciones');
                const data = await response.json();

                console.log('📋 Respuesta de API:', data);

                if (data.success && data.articulos && data.articulos.length > 0) {
                    this.articulos = data.articulos;
                    this.articulosCargados = true;
                    console.log('✅ Artículos cargados:', this.articulos.length);
                    console.log('📋 Primer artículo de muestra:', this.articulos[0]);

                } else {
                    console.error('❌ Error al cargar artículos:', data.message);
                    this.articulos = [];
                    toastr.error('Error al cargar artículos: ' + (data.message || 'Datos vacíos'));
                }
            } catch (error) {
                console.error('❌ Error al cargar artículos:', error);
                this.articulos = [];
                toastr.error('Error de conexión al cargar artículos');
            }
        },

        // 🔥 MÉTODO MEJORADO: Inicializar Select2 para todos los items
        inicializarSelect2ParaTodosLosItems() {
            if (!this.articulosCargados || this.articulos.length === 0) {
                console.log('⚠️ No se pueden inicializar Select2: artículos no cargados');
                return;
            }

            this.$nextTick(() => {
                setTimeout(() => {
                    const selects = document.querySelectorAll('.articulo-select');
                    console.log('🎯 Encontrados', selects.length, 'selects para inicializar');

                    selects.forEach((select, index) => {
                        if (!$(select).hasClass('select2-hidden-accessible')) {
                            console.log('🔄 Inicializando Select2 para item', index);
                            this.inicializarSelect2Individual(select, index);
                        } else {
                            console.log('✅ Select2 ya inicializado para item', index);
                            // 🔥 ACTUALIZAR VALOR SI EL ITEM YA TIENE UN ARTÍCULO SELECCIONADO
                            const item = this.items[index];
                            if (item && item.articulo_id) {
                                $(select).val(item.articulo_id).trigger('change');
                                console.log('✅ Valor restaurado en Select2 para item:', index, 'Artículo:', item.articulo_id);
                            }
                        }
                    });
                }, 150);
            });
        },

        // 🔥 MÉTODO CORREGIDO: Usar nombre en lugar de descripcion
        obtenerTextoArticulo(articulo) {
            let texto = '';

            if (!articulo) {
                console.warn('⚠️ Artículo undefined en obtenerTextoArticulo');
                return 'Artículo no disponible';
            }

            switch (parseInt(articulo.idTipoArticulo)) {
                case 1: texto = articulo.nombre || `Producto ${articulo.idArticulos}`; break;
                case 2: texto = articulo.codigo_repuesto || `Repuesto ${articulo.idArticulos}`; break;
                case 3: texto = articulo.nombre || `Herramienta ${articulo.idArticulos}`; break;
                case 4: texto = articulo.nombre || `Suministro ${articulo.idArticulos}`; break;
                default: texto = articulo.nombre || `Artículo ${articulo.idArticulos}`; // 🔥 CAMBIADO
            }

            if (articulo.precio_venta) {
                texto += ` - ${this.obtenerSimboloMoneda()}${articulo.precio_venta}`;
            }

            return texto;
        },

        obtenerBadgeTipo(articulo) {
            if (!articulo) return { texto: 'Error', clase: 'badge-danger' };

            const tipo = parseInt(articulo.idTipoArticulo);
            switch (tipo) {
                case 1: return { texto: 'Producto', clase: 'badge-primary' };
                case 2: return { texto: 'Repuesto', clase: 'badge-warning' };
                case 3: return { texto: 'Herramienta', clase: 'badge-success' };
                case 4: return { texto: 'Suministro', clase: 'badge-info' };
                default: return { texto: 'Artículo', clase: 'badge-secondary' };
            }
        },

        inicializarSelect2Individual(selectElement, index) {
            if (!this.articulosCargados || this.articulos.length === 0) {
                console.log('❌ No se puede inicializar Select2: sin artículos');
                return;
            }

            if (!selectElement.id) {
                this.selectCounter++;
                selectElement.id = `articulo-select-${this.selectCounter}`;
            }

            // Destruir Select2 si ya existe
            if ($(selectElement).hasClass('select2-hidden-accessible')) {
                $(selectElement).select2('destroy');
            }

            console.log('🔄 Inicializando Select2 con', this.articulos.length, 'artículos');

            try {
                $(selectElement).select2({
                    placeholder: 'Buscar artículo...',
                    allowClear: true,
                    width: '100%',
                    data: this.articulos.map(articulo => {
                        return {
                            id: articulo.idArticulos,
                            text: this.obtenerTextoArticulo(articulo),
                            precio: articulo.precio_venta || 0,
                            codigo_repuesto: articulo.codigo_repuesto || '',
                            nombre: articulo.nombre || '', // 🔥 CAMBIADO
                            tipo_articulo: articulo.idTipoArticulo || '',
                            stock: articulo.stock_total || 0
                        };
                    }),
                    templateResult: (articulo) => {
                        if (!articulo.id) return articulo.text;

                        const badge = this.obtenerBadgeTipo(articulo);
                        const $container = $(`
                            <div class="flex justify-between items-center w-full py-1">
                                <span class="flex-1 text-sm">${articulo.text}</span>
                                <span class="badge badge-sm ${badge.clase} ml-2">${badge.texto}</span>
                            </div>
                        `);

                        return $container;
                    },
                    templateSelection: (articulo) => {
                        if (!articulo.id) return articulo.text;

                        let texto = '';
                        const tipo = parseInt(articulo.tipo_articulo);

                        switch (tipo) {
                            case 2: texto = articulo.codigo_repuesto || `Repuesto ${articulo.id}`; break;
                            default: texto = articulo.nombre || `Artículo ${articulo.id}`; // 🔥 CAMBIADO
                        }

                        return texto;
                    }
                }).on('select2:select', (e) => {
                    const articuloId = e.params.data.id;
                    const itemIndex = this.obtenerIndicePorSelectElement(selectElement);
                    console.log('🎯 Artículo seleccionado:', articuloId, 'en índice:', itemIndex);
                    this.cargarArticulo(articuloId, itemIndex);
                }).on('select2:clear', (e) => {
                    const itemIndex = this.obtenerIndicePorSelectElement(selectElement);
                    console.log('🗑️ Artículo limpiado en índice:', itemIndex);
                    this.limpiarArticulo(itemIndex);
                });

                console.log('✅ Select2 inicializado correctamente para item:', index);
            } catch (error) {
                console.error('❌ Error al inicializar Select2:', error);
            }
        },

        obtenerIndicePorSelectElement(selectElement) {
            const selects = document.querySelectorAll('.articulo-select');
            return Array.from(selects).indexOf(selectElement);
        },

        // 🔥 MÉTODO CORREGIDO: Usar nombre en lugar de descripcion
        cargarArticulo(articuloId, index) {
            console.log('🎯 Cargando artículo:', articuloId, 'en índice:', index);

            if (!articuloId || index === -1) {
                console.log('❌ Índice inválido o ID vacío');
                return;
            }

            const articulo = this.articulos.find(a => a.idArticulos == articuloId);
            if (articulo) {
                let descripcion = '';
                const tipo = parseInt(articulo.idTipoArticulo);

                switch (tipo) {
                    case 2: descripcion = articulo.codigo_repuesto || `Repuesto ${articulo.idArticulos}`; break;
                    default: descripcion = articulo.nombre || `Artículo ${articulo.idArticulos}`; // 🔥 CAMBIADO
                }

                this.items[index].descripcion = descripcion;

                // CALCULAR PRECIO SEGÚN IGV
                let precioBase = articulo.precio_venta || 0;
                this.items[index].precio = this.incluirIGV ? precioBase : (precioBase / 1.18);

                this.items[index].codigo_repuesto = articulo.codigo_repuesto || '';
                this.items[index].articulo_data = articulo;

                console.log('✅ Artículo cargado:', {
                    descripcion: this.items[index].descripcion,
                    precioBase: precioBase,
                    precioCalculado: this.items[index].precio,
                    tipo: articulo.idTipoArticulo,
                    conIGV: this.incluirIGV
                });

                const tipoNombres = { 1: 'Producto', 2: 'Repuesto', 3: 'Herramienta', 4: 'Suministro' };
                const tipoNombre = tipoNombres[tipo] || 'Artículo';
                toastr.success(`${tipoNombre} agregado correctamente`);
            } else {
                console.log('❌ Artículo no encontrado con ID:', articuloId);
                toastr.error('Artículo no encontrado');
            }
        },

        limpiarArticulo(index) {
            if (index === -1) return;
            this.items[index].descripcion = '';
            this.items[index].precio = 0;
            this.items[index].codigo_repuesto = '';
            this.items[index].articulo_data = null;
        },

        // MÉTODOS DE ITEMS
        addItem() {
            const newId = this.items.length ? Math.max(...this.items.map((i) => i.id)) + 1 : 1;
            this.items.push({
                id: newId,
                articulo_id: '',
                descripcion: '',
                cantidad: 1,
                precio: 0,
                codigo_repuesto: '',
                articulo_data: null
            });

            console.log('➕ Item agregado. Total items:', this.items.length);
            console.log('💰 Configuración IGV:', this.incluirIGV ? 'CON IGV' : 'SIN IGV');

            this.$nextTick(() => {
                setTimeout(() => {
                    const selects = document.querySelectorAll('.articulo-select');
                    const lastSelect = selects[selects.length - 1];
                    if (lastSelect) {
                        if (this.articulosCargados && this.articulos.length > 0) {
                            this.inicializarSelect2Individual(lastSelect, this.items.length - 1);
                            console.log('✅ Select2 inicializado para nuevo item');
                        } else {
                            console.log('⚠️ Select2 no inicializado: artículos no cargados');
                            setTimeout(() => {
                                if (this.articulosCargados && this.articulos.length > 0) {
                                    this.inicializarSelect2Individual(lastSelect, this.items.length - 1);
                                    console.log('✅ Select2 inicializado en reintento');
                                }
                            }, 500);
                        }
                    } else {
                        console.log('❌ No se encontró el select del nuevo item');
                    }
                }, 300);
            });
        },

        removeItem(item) {
            if (this.items.length > 1) {
                const index = this.items.findIndex(i => i.id === item.id);
                console.log('🗑️ Eliminando item en índice:', index);

                const selects = document.querySelectorAll('.articulo-select');
                if (selects[index]) {
                    $(selects[index]).select2('destroy');
                    console.log('✅ Select2 destruido para item eliminado');
                }

                this.items = this.items.filter((i) => i.id !== item.id);
                console.log('✅ Item eliminado. Items restantes:', this.items.length);

                setTimeout(() => {
                    this.inicializarSelect2ParaTodosLosItems();
                }, 200);

                toastr.success('Item eliminado correctamente');
            } else {
                toastr.warning('Debe haber al menos un item en la cotización');
            }
        },

        // MÉTODOS DE CONFIGURACIÓN
        async cargarConfiguracion() {
            try {
                console.log('🔄 Cargando configuración...');
                const response = await fetch('/api/configuracion');
                const data = await response.json();

                if (data.success) {
                    this.cargarMonedas(data.monedas);
                    this.cargarTerminosPago(data.terminosPago);
                    console.log('✅ Configuración cargada correctamente');
                } else {
                    console.error('❌ Error al cargar configuración');
                    this.cargarConfiguracionPorDefecto();
                }
            } catch (error) {
                console.error('❌ Error al cargar configuración:', error);
                this.cargarConfiguracionPorDefecto();
            }
        },

        cargarMonedas(monedas) {
            this.monedas = monedas;
            const monedaSelect = document.getElementById('monedaSelect');
            monedaSelect.innerHTML = '';

            if (monedas && monedas.length > 0) {
                monedas.forEach(moneda => {
                    const option = document.createElement('option');
                    option.value = moneda.idMonedas;
                    option.textContent = `${moneda.nombre} (${moneda.simbolo})`;
                    monedaSelect.appendChild(option);
                });

                if (monedas.length > 0) {
                    this.params.moneda = monedas[0].idMonedas;
                }
            } else {
                this.cargarMonedasPorDefecto();
            }
        },

        cargarMonedasPorDefecto() {
            const monedasPorDefecto = [
                { idMonedas: 'USD', nombre: 'Dólares Americanos', simbolo: '$' },
                { idMonedas: 'PEN', nombre: 'Soles Peruanos', simbolo: 'S/' },
                { idMonedas: 'EUR', nombre: 'Euros', simbolo: '€' }
            ];
            this.cargarMonedas(monedasPorDefecto);
        },

        cargarTerminosPago(terminosPago) {
            const terminosSelect = document.getElementById('terminosPagoSelect');
            terminosSelect.innerHTML = '';

            if (terminosPago && terminosPago.length > 0) {
                const contadoOption = document.createElement('option');
                contadoOption.value = 'contado';
                contadoOption.textContent = 'Al contado';
                terminosSelect.appendChild(contadoOption);

                terminosPago.forEach(termino => {
                    const option = document.createElement('option');
                    option.value = termino.idCredito;
                    let texto = `${termino.credito_descripcion}`;

                    if (termino.credito_dias) {
                        texto += ` - ${termino.credito_dias} días`;
                    }

                    if (termino.credito_porcentaje) {
                        texto += ` - ${termino.credito_porcentaje}%`;
                    }

                    option.textContent = texto;
                    terminosSelect.appendChild(option);
                });

                this.params.terminosPago = 'contado';
            } else {
                this.cargarTerminosPagoPorDefecto();
            }
        },

        cargarTerminosPagoPorDefecto() {
            const terminosPorDefecto = [
                { value: 'contado', text: 'Al contado' },
                { value: '30dias', text: '30 días neto' },
                { value: '60dias', text: '60 días neto' },
                { value: '90dias', text: '90 días neto' }
            ];

            const terminosSelect = document.getElementById('terminosPagoSelect');
            terminosSelect.innerHTML = '';

            terminosPorDefecto.forEach(termino => {
                const option = document.createElement('option');
                option.value = termino.value;
                option.textContent = termino.text;
                terminosSelect.appendChild(option);
            });

            this.params.terminosPago = 'contado';
        },

        cargarConfiguracionPorDefecto() {
            console.log('⚠️ Usando configuración por defecto');
            this.cargarMonedasPorDefecto();
            this.cargarTerminosPagoPorDefecto();
        },

        // SELECT2 PARA CLIENTE Y TICKET
        initSelect2() {
            console.log('🔄 Inicializando Select2...');

            // Select2 para Cliente
            $('#clienteSelect').select2({
                placeholder: 'Buscar o seleccionar cliente...',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '/api/clientescotizaciones',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.data.map(cliente => ({
                                id: cliente.idCliente,
                                text: cliente.nombre,
                                nombre: cliente.nombre,
                                email: cliente.email,
                                telefono: cliente.telefono,
                                empresa: cliente.nombre,
                                direccion: cliente.direccion,
                                documento: cliente.documento
                            }))
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            }).on('select2:select', (e) => {
                const selectedData = $('#clienteSelect').select2('data')[0];
                if (selectedData) {
                    this.cargarDatosCliente(selectedData);
                }
            }).on('select2:clear', () => {
                this.limpiarDatosCliente();
            });

            // Select2 para Ticket
            $('#ticketSelect').select2({
                placeholder: 'Seleccionar ticket...',
                allowClear: true,
                width: '100%'
            }).on('change', (e) => {
                this.cargarDatosTicket(e.target.value);
            });

            console.log('✅ Select2 inicializado correctamente');
        },

        // MÉTODOS DE TICKETS
        async cargarTodosLosTickets() {
            console.log('🔄 Cargando TODOS los tickets disponibles...');

            try {
                const response = await fetch('/api/tickets/disponibles');
                const data = await response.json();

                console.log('📋 Tickets disponibles recibidos:', data);

                if (data.success && data.tickets && data.tickets.length > 0) {
                    $('#ticketSelect').empty();
                    $('#ticketSelect').append(new Option('Seleccionar ticket...', '', true, true));

                    data.tickets.forEach(ticket => {
                        const texto = `${ticket.numero_ticket} - ${ticket.fallaReportada?.substring(0, 50) || 'Sin descripción'}`;
                        $('#ticketSelect').append(new Option(texto, ticket.idTickets, false, false));
                    });

                    $('#ticketSelect').trigger('change');
                    console.log('✅ Tickets cargados:', data.tickets.length);
                    toastr.success(`${data.tickets.length} tickets disponibles`);

                } else {
                    console.log('❌ No hay tickets disponibles, respuesta:', data);
                    $('#ticketSelect').empty();
                    $('#ticketSelect').append(new Option('No hay tickets disponibles', '', true, true));
                    toastr.info('No hay tickets disponibles para NGR');
                }
            } catch (error) {
                console.error('❌ Error al cargar tickets:', error);
                $('#ticketSelect').empty();
                $('#ticketSelect').append(new Option('Error al cargar tickets', '', true, true));
                toastr.error('Error al cargar tickets: ' + error.message);
            }
        },

        async cargarDatosTicket(ticketId) {
            console.log('🔄 Cargando datos del ticket:', ticketId);

            if (!ticketId) {
                this.limpiarDatosTicket();
                return;
            }

            try {
                const ticketResponse = await fetch(`/api/tickets/${ticketId}/detalle`);
                const ticketData = await ticketResponse.json();

                console.log('📋 Datos completos del ticket:', ticketData);

                if (ticketData.success && ticketData.ticket) {
                    const ticketCompleto = ticketData.ticket;

                    const mostrarSerie = ticketCompleto.idTipotickets == 2 && ticketCompleto.tipoServicio == 6;
                    console.log('🎯 Condiciones para serie:', {
                        idTipotickets: ticketCompleto.idTipotickets,
                        tipoServicio: ticketCompleto.tipoServicio,
                        mostrarSerie: mostrarSerie
                    });

                    const visitasResponse = await fetch(`/api/tickets/${ticketId}/visitas`);
                    const visitasData = await visitasResponse.json();

                    console.log('📋 Visitas del ticket:', visitasData);

                    let visitas = [];
                    let tecnicoNombre = 'No asignado';
                    let fechaLlegada = 'No definida';
                    let serieEquipo = '';

                    if (visitasData.success && visitasData.visitas && visitasData.visitas.length > 0) {
                        visitas = visitasData.visitas;

                        if (visitas.length === 1) {
                            const visita = visitas[0];
                            tecnicoNombre = visita.tecnico?.Nombre || 'Técnico no asignado';
                            fechaLlegada = visita.fecha_llegada ? new Date(visita.fecha_llegada).toLocaleDateString() : 'Sin fecha';

                            if (mostrarSerie) {
                                const equipoResponse = await fetch(`/api/tickets/${ticketId}/equipo/${visita.idVisitas}`);
                                const equipoData = await equipoResponse.json();

                                if (equipoData.success && equipoData.equipo) {
                                    serieEquipo = equipoData.equipo.nserie || '';
                                }
                            }
                        }
                    }

                    this.params.ticket = {
                        id: ticketId,
                        numero_ticket: ticketCompleto.numero_ticket,
                        tienda_nombre: ticketCompleto.tienda?.nombre || 'Tienda no asignada',
                        tecnico_nombre: tecnicoNombre,
                        fecha_llegada: fechaLlegada,
                        descripcion: ticketCompleto.fallaReportada || 'Sin descripción',
                        visitas: visitas,
                        serie_equipo: serieEquipo,
                        mostrar_serie: mostrarSerie
                    };

                    if (serieEquipo) {
                        this.params.serie = serieEquipo;
                        console.log('✅ Serie asignada automáticamente:', serieEquipo);
                    }

                    this.params.ot = ticketId;
                    console.log('✅ OT asignado:', ticketId);

                    if (visitas.length > 1) {
                        this.params.visita_seleccionada = '';
                        toastr.info('Seleccione una visita específica');
                    }

                    console.log('✅ Datos del ticket cargados:', this.params.ticket);

                    // 🔥 CARGAR SUMINISTROS AUTOMÁTICAMENTE AL SELECCIONAR TICKET
                    setTimeout(() => {
                        this.cargarSuministrosAutomaticamente();
                    }, 500);

                    if (this.items.length > 0) {
                        this.items[0].descripcion = `Servicios para ticket: ${ticketCompleto.fallaReportada?.substring(0, 100) || 'Sin descripción'}`;
                    }

                    if (mostrarSerie) {
                        if (serieEquipo) {
                            toastr.success(`Serie del equipo cargada automáticamente: ${serieEquipo}`);
                        } else {
                            toastr.info('Ticket cumple condiciones para serie, pero no se encontró equipo asociado');
                        }
                    } else {
                        toastr.info('Este ticket no cumple las condiciones para mostrar serie automática');
                    }

                    toastr.success(`Ticket ${ticketCompleto.numero_ticket} cargado correctamente - OT: ${ticketId}`);
                } else {
                    console.log('❌ No se encontró el ticket completo');
                    toastr.error('No se pudieron cargar los datos del ticket');
                }
            } catch (error) {
                console.error('❌ Error al cargar datos del ticket:', error);
                toastr.error('Error al cargar datos del ticket');
            }
        },

        async onVisitaSeleccionada(visitaId) {
            console.log('🎯 Visita seleccionada:', visitaId);

            if (!visitaId || !this.params.ticket.id) return;

            try {
                const visita = this.params.ticket.visitas.find(v => v.idVisitas == visitaId);
                if (visita) {
                    this.params.ticket.tecnico_nombre = visita.tecnico?.Nombre || 'Técnico no asignado';
                    this.params.ticket.fecha_llegada = visita.fecha_llegada ? new Date(visita.fecha_llegada).toLocaleDateString() : 'Sin fecha';

                    if (this.params.ticket.mostrar_serie) {
                        const equipoResponse = await fetch(`/api/tickets/${this.params.ticket.id}/equipo/${visitaId}`);
                        const equipoData = await equipoResponse.json();

                        if (equipoData.success && equipoData.equipo) {
                            this.params.ticket.serie_equipo = equipoData.equipo.nserie || '';
                            if (equipoData.equipo.nserie) {
                                this.params.serie = equipoData.equipo.nserie;
                                console.log('✅ Serie asignada desde visita:', equipoData.equipo.nserie);
                                toastr.success(`Serie del equipo cargada: ${equipoData.equipo.nserie}`);
                            }
                        }
                    }

                    toastr.success('Visita seleccionada correctamente');

                    // 🔥 CARGAR SUMINISTROS AUTOMÁTICAMENTE AL SELECCIONAR VISITA
                    this.cargarSuministrosAutomaticamente();
                }
            } catch (error) {
                console.error('❌ Error al cargar datos de la visita:', error);
                toastr.error('Error al cargar datos de la visita');
            }
        },

        cargarDatosCliente(clienteData) {
            if (clienteData) {
                this.params.cliente = {
                    id: clienteData.id,
                    nombre: clienteData.nombre,
                    email: clienteData.email,
                    telefono: clienteData.telefono,
                    empresa: clienteData.nombre,
                    direccion: clienteData.direccion,
                    documento: clienteData.documento
                };
                console.log('✅ Datos del cliente cargados:', this.params.cliente);
                toastr.success('Cliente seleccionado correctamente');
            }
        },

        limpiarDatosTicket() {
            this.params.ticket = {
                id: '',
                numero_ticket: '',
                tecnico_nombre: '',
                tienda_nombre: '',
                fecha_llegada: '',
                descripcion: '',
                visitas: [],
                serie_equipo: '',
                mostrar_serie: false
            };
            this.params.visita_seleccionada = '';
            this.params.serie = '';
            this.params.ot = '';
        },

        limpiarDatosCliente() {
            this.params.cliente = { id: '', nombre: '', email: '', telefono: '', empresa: '', direccion: '' };
        },

        limpiarTickets() {
            $('#ticketSelect').empty();
            $('#ticketSelect').append(new Option('Seleccionar ticket...', '', true, true)).trigger('change');
            this.limpiarDatosTicket();
        },

        initFlatpickr() {
            const config = {
                locale: 'es',
                dateFormat: 'd/m/Y',
                allowInput: true,
                clickOpens: true,
                theme: 'airbnb'
            };

            flatpickr('#fechaEmision', {
                ...config,
                defaultDate: this.params.fechaEmision,
                onChange: (dates) => {
                    this.params.fechaEmision = dates[0] ? dates[0].toISOString().split('T')[0] : '';
                },
            });

            flatpickr('#validaHasta', {
                ...config,
                defaultDate: this.params.validaHasta,
                minDate: this.params.fechaEmision,
                onChange: (dates) => {
                    this.params.validaHasta = dates[0] ? dates[0].toISOString().split('T')[0] : '';
                },
            });
        },

        // MÉTODOS DE CÁLCULO CON IGV
        get subtotal() {
            return this.items.reduce((sum, i) => sum + (parseFloat(i.precio) || 0) * (parseInt(i.cantidad) || 0), 0);
        },

        get igv() {
            return this.incluirIGV ? this.subtotal * 0.18 : 0;
        },

        get total() {
            return this.subtotal + this.igv;
        },

        // MÉTODOS PARA IGV
        get textoIGV() {
            return this.incluirIGV ? 'CON IGV' : 'SIN IGV';
        },

        get claseBadgeIGV() {
            return this.incluirIGV ? 'badge-success' : 'badge-warning';
        },

        async guardarCotizacion() {
            try {
                console.log('💾 Iniciando guardado de cotización...');

                // Validaciones básicas
                if (!this.params.cliente.id) {
                    toastr.error('Debe seleccionar un cliente');
                    return;
                }

                if (this.items.length === 0) {
                    toastr.error('Debe agregar al menos un item a la cotización');
                    return;
                }

                // Validar que todos los items tengan artículo seleccionado
                const itemsInvalidos = this.items.filter(item => !item.articulo_data);
                if (itemsInvalidos.length > 0) {
                    toastr.error('Todos los items deben tener un artículo seleccionado');
                    return;
                }

                // Validar NGR si está activado
                if (this.mostrarNGR && !this.params.ticket.id) {
                    toastr.error('Debe seleccionar un ticket cuando NGR está activado');
                    return;
                }

                // Preparar datos para enviar
                const datosCotizacion = {
                    numero_cotizacion: this.params.cotizacionNo,
                    fecha_emision: this.params.fechaEmision,
                    valida_hasta: this.params.validaHasta,
                    subtotal: this.subtotal,
                    igv: this.igv,
                    total: this.total,
                    incluir_igv: this.incluirIGV,
                    terminos_condiciones: this.params.notas,
                    dias_validez: this.params.diasValidez,
                    terminos_pago: this.params.terminosPago,

                    // Campos NGR
                    ot: this.params.ot,
                    serie: this.params.serie,
                    visita_id: this.params.visita_seleccionada,

                    // Relaciones
                    idCliente: this.params.cliente.id,
                    idMonedas: this.params.moneda,
                    idTickets: this.params.ticket.id,
                    idTienda: this.params.ticket.tienda_id || null,

                    // Items de la cotización
                    items: this.items.map(item => ({
                        articulo_id: item.articulo_data.idArticulos,
                        descripcion: item.descripcion,
                        codigo_repuesto: item.codigo_repuesto || '',
                        precio_unitario: parseFloat(item.precio),
                        cantidad: parseInt(item.cantidad),
                        subtotal: parseFloat(item.precio) * parseInt(item.cantidad)
                    }))
                };

                console.log('📦 Datos a enviar:', datosCotizacion);

                // Mostrar loading
                toastr.info('Guardando cotización...', '', { timeOut: 0 });

                // Enviar al servidor
                const response = await fetch('/cotizaciones/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(datosCotizacion)
                });

                const result = await response.json();

                // Cerrar toast de loading
                toastr.clear();

                if (result.success) {
                    toastr.success('✅ Cotización guardada correctamente');
                    console.log('✅ Cotización guardada con ID:', result.cotizacion_id);

                    // Opcional: Redirigir a la vista de la cotización
                    setTimeout(() => {
                        window.location.href = `/cotizaciones/${result.cotizacion_id}`;
                    }, 2000);

                } else {
                    console.error('❌ Error al guardar:', result.message);
                    toastr.error('Error al guardar: ' + result.message);
                }

            } catch (error) {
                console.error('❌ Error al guardar cotización:', error);
                toastr.error('Error de conexión al guardar cotización');
            }
        },

       async vistaPrevia() {
    try {
        console.log('👁️ Generando vista previa...');
        
        // Validaciones básicas
        if (!this.params.cliente.id) {
            toastr.error('Debe seleccionar un cliente para la vista previa');
            return;
        }

        if (this.items.length === 0) {
            toastr.error('Debe agregar al menos un item para la vista previa');
            return;
        }

        // Preparar datos para vista previa
        const datosVistaPrevia = this.prepararDatosCotizacion();
        
        // Abrir en nueva pestaña con los datos
        const ventana = window.open('', '_blank');
        
        if (!ventana) {
            toastr.error('Por favor permite ventanas emergentes para la vista previa');
            return;
        }

        // Mostrar loading
        toastr.info('Generando vista previa...', '', { timeOut: 0 });

        // Enviar datos al servidor para generar HTML
        const response = await fetch('/cotizaciones-tickets/vista-previa-temporal', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(datosVistaPrevia)
        });

        const html = await response.text();
        
        ventana.document.write(html);
        ventana.document.close();
        
        toastr.clear();
        toastr.success('Vista previa generada correctamente');

    } catch (error) {
        console.error('❌ Error en vista previa:', error);
        toastr.error('Error al generar vista previa');
    }
},

async generarPDF() {
    try {
        console.log('📄 Generando PDF...');

        // Validaciones básicas
        if (!this.params.cliente.id) {
            toastr.error('Debe seleccionar un cliente para generar PDF');
            return;
        }

        if (this.items.length === 0) {
            toastr.error('Debe agregar al menos un item para generar PDF');
            return;
        }

        // Preparar datos
        const datosPDF = this.prepararDatosCotizacion();
        
        // Mostrar loading
        toastr.info('Generando PDF...', '', { timeOut: 0 });

        // Generar PDF
        const response = await fetch('/cotizaciones-tickets/generar-pdf-temporal', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(datosPDF)
        });

        if (!response.ok) {
            throw new Error('Error en la generación del PDF');
        }

        // Crear blob y descargar
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = `cotizacion-${this.params.cotizacionNo}.pdf`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);

        toastr.clear();
        toastr.success('PDF generado y descargado correctamente');

    } catch (error) {
        console.error('❌ Error al generar PDF:', error);
        toastr.clear();
        toastr.error('Error al generar PDF: ' + error.message);
    }
},

async enviarEmail() {
    try {
        console.log('📧 Preparando envío por email...');

        // Validaciones básicas
        if (!this.params.cliente.id) {
            toastr.error('Debe seleccionar un cliente para enviar por email');
            return;
        }

        if (this.items.length === 0) {
            toastr.error('Debe agregar al menos un item para enviar por email');
            return;
        }

        if (!this.params.cliente.email) {
            toastr.error('El cliente seleccionado no tiene email registrado');
            return;
        }

        // Confirmar envío
        const confirmar = await this.mostrarConfirmacionEmail();
        if (!confirmar) return;

        // Preparar datos
        const datosEmail = this.prepararDatosCotizacion();
        datosEmail.email = this.params.cliente.email;

        // Mostrar loading
        toastr.info('Enviando email...', '', { timeOut: 0 });

        // Enviar email
        const response = await fetch('/cotizaciones-tickets/enviar-email-temporal', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(datosEmail)
        });

        const result = await response.json();

        toastr.clear();

        if (result.success) {
            toastr.success('✅ Email enviado correctamente');
            console.log('✅ Email enviado a:', this.params.cliente.email);
        } else {
            toastr.error('Error al enviar email: ' + result.message);
        }

    } catch (error) {
        console.error('❌ Error al enviar email:', error);
        toastr.clear();
        toastr.error('Error al enviar email: ' + error.message);
    }
},

// 🔥 MÉTODO AUXILIAR: Preparar datos para las acciones
prepararDatosCotizacion() {
    return {
        numero_cotizacion: this.params.cotizacionNo,
        fecha_emision: this.params.fechaEmision,
        valida_hasta: this.params.validaHasta,
        subtotal: this.subtotal,
        igv: this.igv,
        total: this.total,
        incluir_igv: this.incluirIGV,
        terminos_condiciones: this.params.notas,
        dias_validez: this.params.diasValidez,
        terminos_pago: this.params.terminosPago,
        
        // Campos NGR
        ot: this.params.ot,
        serie: this.params.serie,
        visita_id: this.params.visita_seleccionada,
        
        // Información del cliente
        cliente: this.params.cliente,
        
        // Información de moneda
        moneda: this.monedas.find(m => m.idMonedas == this.params.moneda) || {},
        
        // Información del ticket (si existe)
        ticket: this.params.ticket.id ? this.params.ticket : null,
        
        // Items de la cotización
        items: this.items.map(item => ({
            articulo_id: item.articulo_data.idArticulos,
            descripcion: item.descripcion,
            codigo_repuesto: item.codigo_repuesto || '',
            precio_unitario: parseFloat(item.precio),
            cantidad: parseInt(item.cantidad),
            subtotal: parseFloat(item.precio) * parseInt(item.cantidad),
            articulo_data: item.articulo_data
        }))
    };
},

// 🔥 MÉTODO AUXILIAR: Mostrar confirmación para enviar email
mostrarConfirmacionEmail() {
    return new Promise((resolve) => {
        // Crear modal de confirmación
        const modalHTML = `
            <div id="emailConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-paper-plane text-blue-500 text-xl mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-900">Confirmar envío de email</h3>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-gray-600 mb-2">¿Está seguro de enviar esta cotización por email?</p>
                        <div class="bg-gray-50 p-3 rounded border">
                            <p class="text-sm"><strong>Destinatario:</strong> ${this.params.cliente.email}</p>
                            <p class="text-sm"><strong>Cliente:</strong> ${this.params.cliente.nombre}</p>
                            <p class="text-sm"><strong>Cotización:</strong> ${this.params.cotizacionNo}</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelEmail" class="btn btn-secondary btn-sm">
                            Cancelar
                        </button>
                        <button type="button" id="confirmEmail" class="btn btn-primary btn-sm">
                            <i class="fas fa-paper-plane mr-2"></i> Enviar
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Agregar modal al DOM
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        const modal = document.getElementById('emailConfirmModal');
        const confirmBtn = document.getElementById('confirmEmail');
        const cancelBtn = document.getElementById('cancelEmail');

        // Configurar eventos
        confirmBtn.onclick = () => {
            modal.remove();
            resolve(true);
        };

        cancelBtn.onclick = () => {
            modal.remove();
            resolve(false);
        };

        // Cerrar modal al hacer clic fuera
        modal.onclick = (e) => {
            if (e.target === modal) {
                modal.remove();
                resolve(false);
            }
        };
    });
},
    }));
});

// Configuración Toastr
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right",
    timeOut: 3000
};