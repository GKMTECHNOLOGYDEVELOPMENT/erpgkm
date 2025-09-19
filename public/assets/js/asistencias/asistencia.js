document.addEventListener('alpine:init', () => {
    Alpine.data('usuariosTable', () => ({
        datatable: null,

        init() {
            flatpickr('#startDate', {
                dateFormat: 'Y-m-d',
                defaultDate: new Date(),
                onChange: () => this.reloadTable(),
            });

            flatpickr('#endDate', {
                dateFormat: 'Y-m-d',
                defaultDate: new Date(),
                onChange: () => this.reloadTable(),
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
                    },
                },
                columns: [
                    {
                        title: 'DETALLE',
                        data: null, // Usamos toda la fila
                        className: 'text-center align-middle',
                        render: function (data, type, row) {
                            const tieneDelDia = !!row.observacion;
                            const tieneHistorial = row.tiene_historial; // este campo lo debes enviar desde backend (bool)

                            if (!tieneDelDia && !tieneHistorial) return '-';

                            let html = '<div class="flex justify-center gap-1">';

                            if (tieneDelDia) {
                                html += `<button class="btn btn-sm btn-info ver-observacion" title="Ver observación del día"
                                            data-observacion="${encodeURIComponent(
                                                JSON.stringify({
                                                    ...row.observacion,
                                                    idUsuario: row.idUsuario,
                                                }),
                                            )}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
    </svg>
                                        </button>`;
                            }

                            if (tieneHistorial) {
                                html += `<a href="/asistencias/historial/${row.idUsuario}" target="_blank" class="btn btn-sm btn-primary" title="Ver todas las observaciones">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v12a2 2 0 01-2 2z" />
    </svg>
                                </a>`;
                            }

                            html += '</div>';
                            return html;
                        },
                    },
                    {
                        data: 'empleado',
                        className: 'text-center align-middle w-[140px]',
                        render: (data, type) =>
                            type === 'display' ? `<strong class="block break-words whitespace-normal text-sm uppercase font-semibold">${data}</strong>` : data,
                    },
                    {
                        data: 'asistencia',
                        className: 'text-center align-middle',
                        render: function (d, type) {
                            if (type !== 'display') return d;

                            const clase = d === 'ASISTIÓ' ? 'bg-success' : 'bg-danger';
                            return `<span class="badge ${clase}">${d}</span>`;
                        },
                    },

                    {
                        data: 'fecha',
                        className: 'text-center align-middle',
                        render: (data, type) => (type === 'display' ? `<span class="badge badge-outline-primary">${data}</span>` : data),
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
                        },
                    },

                    {
                        data: 'ubicacion_entrada',
                        className: 'text-center align-middle col-ubicacion',
                        render: (d) => (d ? `<div class="whitespace-normal break-words leading-snug text-sm">${d}</div>` : '-'),
                    },

                    {
                        data: 'inicio_break',
                        className: 'text-center align-middle',
                        render: (d) => (d ? `<span class="badge badge-outline-warning">${d}</span>` : '-'),
                    },
                    {
                        data: 'fin_break',
                        className: 'text-center align-middle',
                        render: (d) => (d ? `<span class="badge badge-outline-info">${d}</span>` : '-'),
                    },
                    {
                        data: 'salida',
                        className: 'text-center align-middle',
                        render: (d) => (d ? `<span class="badge badge-outline-danger">${d}</span>` : '-'),
                    },
                    {
                        data: 'ubicacion_salida',
                        className: 'text-center align-middle col-ubicacion',
                        render: (d) => (d ? `<div class="whitespace-normal break-words leading-snug text-sm">${d}</div>` : '-'),
                    },
                ],
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                order: [],
                language: {
                    search: 'Buscar...',
                    zeroRecords: 'No se encontraron registros',
                    lengthMenu: 'Mostrar _MENU_ registros por página',
                    loadingRecords: 'Cargando...',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: 'Siguiente',
                        previous: 'Anterior',
                    },
                },
                dom: '<"flex flex-wrap justify-end mb-4"f>rt<"flex flex-wrap justify-between items-center mt-4"ilp>',
                initComplete: function () {
                    const wrapper = document.querySelector('.dataTables_wrapper');
                    const table = wrapper?.querySelector('#tablaAsistencias');

                    if (!table || !wrapper) return; // 💥 Si no existe, detiene ejecución

                    // Crear contenedor scroll horizontal
                    const scrollContainer = document.createElement('div');
                    scrollContainer.className = 'dataTables_scrollable overflow-x-auto border border-gray-200 rounded-md mb-3';
                    table.parentNode.insertBefore(scrollContainer, table);
                    scrollContainer.appendChild(table);

                    // Crear scroll superior
                    const scrollTop = document.createElement('div');
                    scrollTop.className = 'dataTables_scrollTop overflow-x-auto mb-2';
                    scrollTop.style.height = '14px';

                    const topInner = document.createElement('div');
                    topInner.style.width = scrollContainer.scrollWidth + 'px';
                    topInner.style.height = '1px';
                    scrollTop.appendChild(topInner);

                    scrollTop.addEventListener('scroll', () => {
                        scrollContainer.scrollLeft = scrollTop.scrollLeft;
                    });
                    scrollContainer.addEventListener('scroll', () => {
                        scrollTop.scrollLeft = scrollContainer.scrollLeft;
                    });

                    wrapper.insertBefore(scrollTop, scrollContainer);

                    // Controles flotantes
                    const floatingControls = document.createElement('div');
                    floatingControls.className = 'floating-controls flex justify-between items-center border-t p-2 shadow-md bg-white dark:bg-[#121c2c]';
                    Object.assign(floatingControls.style, {
                        position: 'sticky',
                        bottom: '0',
                        left: '0',
                        width: '100%',
                        zIndex: '10',
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
                },
            });
            $('#tablaAsistencias tbody')
                .off('click', '.ver-observacion')
                .on('click', '.ver-observacion', function () {
                    try {
                        const json = $(this).data('observacion');
                        const observacion = typeof json === 'string' ? JSON.parse(decodeURIComponent(json)) : json;

                        window.verObservacion(observacion);
                    } catch (e) {
                        console.error('Error al parsear observación', e);
                    }
                });
        },
    }));
    let observacionActual = null;
    let observacionesDelDia = [];
    let indiceActualObs = 0;
    function nombreTipoAsunto(id) {
        switch (id) {
            case 1:
                return 'TARDANZA';
            case 2:
                return 'FALTA MEDICA';
            case 3:
                return 'FALTA';
            default:
                return 'OBSERVACIÓN';
        }
    }

    window.verObservacion = function (observacion) {
        const fecha = observacion?.fechaHora?.substring(0, 10);
        const idUsuario = observacion?.idUsuario;

        if (!fecha || !idUsuario) return;

        fetch(`/asistencias/observaciones-dia/${idUsuario}/${fecha}`)
            .then((res) => res.json())
            .then((data) => {
                observacionesDelDia = data || [];
                indiceActualObs = observacionesDelDia.findIndex((o) => o.idObservaciones === observacion.idObservaciones);
                mostrarObservacion(indiceActualObs);
            });
    };

    function mostrarObservacion(index) {
        const obs = observacionesDelDia[index];
        if (!obs) return;

        observacionActual = obs;

        const tipoTexto = nombreTipoAsunto(obs?.idTipoAsunto);
        const total = observacionesDelDia.length;
        const indexActual = index + 1;

        document.querySelector('#modalObservacion h5').textContent = `${tipoTexto} (${indexActual} de ${total})`;
        document.getElementById('observacionFechaHora').textContent = obs?.fechaHora ?? 'Fecha no registrada';
        document.getElementById('observacionUbicacion').textContent = obs?.ubicacion ?? 'Ubicación no registrada';
        document.getElementById('observacionMensaje').textContent = obs?.mensaje || 'Sin mensaje';
        document.getElementById('paginadorObs').textContent = `Observación ${indexActual} de ${total}`;

        // Botones
        const respuestaTextarea = document.getElementById('respuestaTexto');
        const btnAprobar = document.querySelector('#observacionAcciones button.btn-primary');
        const btnDenegar = document.querySelector('#observacionAcciones button.btn-outline-danger');

        respuestaTextarea.value = obs?.respuesta || '';
        if (obs?.estado === 1 || obs?.estado === 2) {
            respuestaTextarea.setAttribute('readonly', true);
            btnAprobar.disabled = true;
            btnDenegar.disabled = true;
        } else {
            respuestaTextarea.removeAttribute('readonly');
            btnAprobar.disabled = false;
            btnDenegar.disabled = false;
        }

        // Cargar imágenes
        const contenedor = document.getElementById('observacionImagenes');
        contenedor.innerHTML = 'Cargando...';

        fetch(`/asistencias/observacion/${obs.idObservaciones}`)
            .then((res) => res.json())
            .then((data) => {
                contenedor.innerHTML = '';
                if (Array.isArray(data.imagenes)) {
                    data.imagenes.forEach((src) => {
                        const img = document.createElement('img');
                        img.src = 'data:image/jpeg;base64,' + src;
                        img.className = 'w-24 h-24 object-contain rounded border cursor-zoom-in';
                        contenedor.appendChild(img);
                    });

                    if (window.viewerInstance) window.viewerInstance.destroy();
                    window.viewerInstance = new Viewer(contenedor, {
                        toolbar: true,
                        title: false,
                        navbar: false,
                    });
                } else {
                    contenedor.innerHTML = 'Sin imágenes';
                }
            });

        document.getElementById('modalObservacion').classList.remove('hidden');

        // Deshabilitar botones si estás en los extremos
        document.getElementById('btnAnteriorObs').disabled = indexActual === 1;
        document.getElementById('btnSiguienteObs').disabled = indexActual === total;

        // Estado final (mostrar solo si ya fue aprobado/denegado)
        const estadoFinal = document.getElementById('observacionEstadoFinal');

        if (obs?.estado === 1) {
            estadoFinal.innerHTML = '<span class="badge badge-outline-success">Observación: Aprobada</span>';
            estadoFinal.className = 'mt-2 text-center'; // centrado
            estadoFinal.classList.remove('hidden');
        } else if (obs?.estado === 2) {
            estadoFinal.innerHTML = '<span class="badge badge-outline-danger">Observación: Denegada</span>';
            estadoFinal.className = 'mt-2 text-center'; // centrado
            estadoFinal.classList.remove('hidden');
        } else {
            estadoFinal.innerHTML = '';
            estadoFinal.classList.add('hidden');
        }
    }
    window.navegarObservacion = function (delta) {
        const nuevoIndex = indiceActualObs + delta;
        if (nuevoIndex >= 0 && nuevoIndex < observacionesDelDia.length) {
            indiceActualObs = nuevoIndex;
            mostrarObservacion(nuevoIndex);
        }
    };

    window.aprobarObservacion = function () {
        const id = observacionActual?.idObservaciones;
        const respuesta = document.getElementById('respuestaTexto').value.trim();

        if (!id) return console.error('ID no definido');

        fetch('/asistencias/actualizar-observacion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ id, estado: 1, respuesta }), // puede ser ''
        })
            .then((res) => res.json())
            .then(() => {
                Toastify({
                    text: '✅ Observación aprobada.',
                    duration: 2500,
                    style: { background: '#22c55e' },
                }).showToast();

                cerrarModalObservacion();
            })
            .catch(() => {
                Toastify({
                    text: '❌ Error al aprobar la observación.',
                    duration: 2500,
                    style: { background: '#ef4444' },
                }).showToast();
            });
    };

    window.denegarObservacion = function () {
        const id = observacionActual?.idObservaciones;
        const respuesta = document.getElementById('respuestaTexto').value.trim();

        if (!id) return console.error('ID no definido');

        fetch('/asistencias/actualizar-observacion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ id, estado: 2, respuesta }),
        })
            .then((res) => res.json())
            .then(() => {
                Toastify({
                    text: '❌ Observación denegada.',
                    duration: 2500,
                    style: { background: '#f87171' },
                }).showToast();

                cerrarModalObservacion();
            })
            .catch(() => {
                Toastify({
                    text: '❌ Error al denegar la observación.',
                    duration: 2500,
                    style: { background: '#dc2626' },
                }).showToast();
            });
    };

    window.cerrarModalObservacion = function () {
        document.getElementById('modalObservacion').classList.add('hidden');
    };
});
