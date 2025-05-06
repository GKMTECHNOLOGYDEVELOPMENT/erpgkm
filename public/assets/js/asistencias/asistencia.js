document.addEventListener('alpine:init', () => {
    Alpine.data('usuariosTable', () => ({
        datatable: null,

        init() {
            flatpickr("#startDate", {
                dateFormat: "Y-m-d",
                maxDate: "today",
                defaultDate: new Date(),
                onChange: () => this.reloadTable()
            });

            flatpickr("#endDate", {
                dateFormat: "Y-m-d",
                maxDate: "today",
                defaultDate: new Date(),
                onChange: () => this.reloadTable()
            });

            this.initTable();
        },

        reloadTable() {
            if (this.datatable) {
                this.datatable.ajax.reload();
            }
        },

        initTable() {
            this.datatable = $('#tablaAsistencias').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: '/asistencias/listado',
                    dataSrc: 'data',
                    data: function (d) {
                        d.startDate = document.getElementById('startDate').value;
                        d.endDate = document.getElementById('endDate').value;
                    }
                },
                columns: [
                    {
                        data: 'empleado',
                        className: 'text-center align-middle w-[140px]',
                        render: (data, type) => type === 'display'
                            ? `<strong class="block break-words whitespace-normal text-sm uppercase font-semibold">${data}</strong>`
                            : data
                    },
                    {
                        data: 'fecha',
                        className: 'text-center align-middle',
                        render: (data, type) => type === 'display'
                            ? `<span class="badge badge-outline-primary">${data}</span>` : data
                    },
                    {
                        data: 'entrada',
                        className: 'text-center align-middle',
                        render: function (data, type, row) {
                            if (!data) return '-';
                            let color = 'badge-outline-secondary';

                            if (row.observacion?.estado === 1) {
                                color = 'badge-outline-primary'; // aprobado = azul
                            } else {
                                if (row.estado_entrada === 'azul') color = 'badge-outline-primary';
                                else if (row.estado_entrada === 'amarillo') color = 'badge-outline-warning';
                                else if (row.estado_entrada === 'rojo') color = 'badge-outline-danger';
                            }


                            return `<div class="inline-block px-1 text-xs whitespace-normal">
                                        <span class="badge ${color} block w-full">${data}</span>
                                    </div>`;
                        }
                    },

                    {
                        data: 'ubicacion_entrada',
                        className: 'text-center align-middle col-ubicacion',
                        render: d => d
                            ? `<div class="whitespace-normal break-words leading-snug text-sm">${d}</div>`
                            : '-'
                    },

                    {
                        data: 'inicio_break',
                        className: 'text-center align-middle',
                        render: d => d ? `<span class="badge badge-outline-warning">${d}</span>` : '-'
                    },
                    {
                        data: 'fin_break',
                        className: 'text-center align-middle',
                        render: d => d ? `<span class="badge badge-outline-info">${d}</span>` : '-'
                    },
                    {
                        data: 'salida',
                        className: 'text-center align-middle',
                        render: d => d ? `<span class="badge badge-outline-danger">${d}</span>` : '-'
                    },
                    {
                        data: 'ubicacion_salida',
                        className: 'text-center align-middle col-ubicacion',
                        render: d => d
                            ? `<div class="whitespace-normal break-words leading-snug text-sm">${d}</div>`
                            : '-'
                    },
                    {
                        data: 'asistencia',
                        className: 'text-center align-middle',
                        render: function (d, type, row) {
                            const observacion = row.observacion;
                            if (observacion) {
                                const estado = observacion.estado;
                                let clase = 'bg-warning';
                                let texto = 'OBSERVACIÓN';

                                if (estado === 1) {
                                    clase = 'bg-primary';
                                    texto = 'APROBADO';
                                } else if (estado === 2) {
                                    clase = 'bg-danger';
                                    texto = 'DENEGADO';
                                }

                                return `
                                <span class="badge ${clase} cursor-pointer ver-observacion"
                                    data-observacion="${encodeURIComponent(JSON.stringify(observacion))}">
                                    ${texto}
                                </span>`;

                            }

                            const clase = d === 'ASISTIÓ' ? 'bg-success' : 'bg-danger';
                            return `<span class="badge ${clase}">${d}</span>`;
                        }
                    }

                ],
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                order: [[1, 'desc']],
                language: {
                    search: "Buscar...",
                    zeroRecords: "No se encontraron registros",
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    loadingRecords: "Cargando...",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                dom: '<"flex flex-wrap justify-end mb-4"f>rt<"flex flex-wrap justify-between items-center mt-4"ilp>',
                initComplete: function () {
                    const wrapper = document.querySelector('.dataTables_wrapper');
                    const table = wrapper.querySelector('#tablaAsistencias');

                    // Envolver la tabla en un contenedor scrollable
                    const scrollContainer = document.createElement('div');
                    scrollContainer.className = 'dataTables_scrollable overflow-x-auto border border-gray-200 rounded-md mb-3';
                    table.parentNode.insertBefore(scrollContainer, table);
                    scrollContainer.appendChild(table);

                    // Scroll superior sincronizado (usando div vacío con mismo ancho del scrollContainer)
                    const scrollTop = document.createElement('div');
                    scrollTop.className = 'dataTables_scrollTop overflow-x-auto mb-2';
                    scrollTop.style.height = '14px';

                    // Usamos un div interno para que tome el mismo ancho que el scroll real
                    const topInner = document.createElement('div');
                    topInner.style.width = scrollContainer.scrollWidth + 'px'; // ancho real del scroll horizontal
                    topInner.style.height = '1px'; // invisible
                    scrollTop.appendChild(topInner);

                    // Sincronizar scroll
                    scrollTop.addEventListener('scroll', () => {
                        scrollContainer.scrollLeft = scrollTop.scrollLeft;
                    });
                    scrollContainer.addEventListener('scroll', () => {
                        scrollTop.scrollLeft = scrollContainer.scrollLeft;
                    });

                    // Insertar arriba de la tabla
                    wrapper.insertBefore(scrollTop, scrollContainer);


                    // Controles flotantes
                    const floatingControls = document.createElement('div');
                    floatingControls.className = 'floating-controls flex justify-between items-center border-t p-2 shadow-md bg-white dark:bg-[#121c2c]';
                    Object.assign(floatingControls.style, {
                        position: 'sticky',
                        bottom: '0',
                        left: '0',
                        width: '100%',
                        zIndex: '10'
                    });

                    const info = wrapper.querySelector('.dataTables_info');
                    const length = wrapper.querySelector('.dataTables_length');
                    const paginate = wrapper.querySelector('.dataTables_paginate');

                    if (info && length && paginate) {
                        floatingControls.appendChild(info);
                        floatingControls.appendChild(length);
                        floatingControls.appendChild(paginate);
                        wrapper.appendChild(floatingControls);
                    }
                }

            });
            $('#tablaAsistencias tbody').off('click', '.ver-observacion').on('click', '.ver-observacion', function () {
                try {
                    const json = $(this).data('observacion');
                    const observacion = typeof json === 'string'
                        ? JSON.parse(decodeURIComponent(json))
                        : json;


                    window.verObservacion(observacion);
                } catch (e) {
                    console.error('Error al parsear observación', e);
                }
            });

        }
    }));
    let observacionActual = null;
    function nombreTipoAsunto(id) {
        switch (id) {
            case 1: return 'TARDANZA';
            case 2: return 'FALTA MEDICA';
            case 3: return 'FALTA';
            default: return 'OBSERVACIÓN';
        }
    }
    window.verObservacion = function (observacion) {
        observacionActual = observacion;

        // 👉 Cambiar título del modal según el tipo de asunto
        document.querySelector('#modalObservacion h5').textContent = nombreTipoAsunto(observacion?.idTipoAsunto);

        // 👉 Mostrar mensaje
        document.getElementById('observacionMensaje').textContent = observacion?.mensaje || 'Sin mensaje';

        // 👉 Mostrar u ocultar botones
        const acciones = document.getElementById('observacionAcciones');
        if (observacion?.estado === 0) {
            acciones.classList.remove('hidden');
        } else {
            acciones.classList.add('hidden');
        }

        const contenedor = document.getElementById('observacionImagenes');
        contenedor.innerHTML = 'Cargando...';

        fetch(`/asistencias/observacion/${observacion.idObservaciones}`)
            .then(res => res.json())
            .then(data => {
                contenedor.innerHTML = '';
                if (Array.isArray(data.imagenes)) {
                    data.imagenes.forEach(src => {
                        const img = document.createElement('img');
                        img.src = 'data:image/jpeg;base64,' + src;
                        img.className = 'w-24 h-24 object-contain rounded border cursor-zoom-in';
                        contenedor.appendChild(img);
                    });

                    if (window.viewerInstance) window.viewerInstance.destroy();
                    window.viewerInstance = new Viewer(contenedor, {
                        toolbar: true,
                        title: false,
                        navbar: false
                    });
                } else {
                    contenedor.innerHTML = 'Sin imágenes';
                }
            });

        document.getElementById('modalObservacion').classList.remove('hidden');
    };

    window.cerrarModalObservacion = function () {
        document.getElementById('modalObservacion').classList.add('hidden');
    };

    window.aprobarObservacion = function () {
        const id = observacionActual?.idObservaciones ?? observacionActual?.id;

        if (id) {
            fetch('/asistencias/actualizar-observacion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    id: id,
                    estado: 1
                })
            })
                .then(res => res.json())
                .then(() => {
                    cerrarModalObservacion();
                    // ✅ Recargar tabla directamente
                    if (window.Alpine?.store('usuariosTable')?.datatable) {
                        window.Alpine.store('usuariosTable').datatable.ajax.reload();
                    }
                })
                .catch(console.error);
        } else {
            console.error('❌ ID de observación no definido (aprobación)');
        }
    };

    window.denegarObservacion = function () {
        const id = observacionActual?.idObservaciones ?? observacionActual?.id;

        if (id) {
            fetch('/asistencias/actualizar-observacion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    id: id,
                    estado: 2
                })
            })
                .then(res => res.json())
                .then(() => {
                    cerrarModalObservacion();
                    // ✅ Recargar tabla directamente
                    if (window.Alpine?.store('usuariosTable')?.datatable) {
                        window.Alpine.store('usuariosTable').datatable.ajax.reload();
                    }
                })
                .catch(console.error);
        } else {
            console.error('❌ ID de observación no definido (denegación)');
        }
    };
});
