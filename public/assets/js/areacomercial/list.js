$(document).ready(function () {
    const table = $('#tablaClientes').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/api/seguimientos',
columns: [
    { data: 'tipo_prospecto', className: 'text-center' },
    { data: 'nombre_prospecto', className: 'text-center' },
    { data: 'documento', className: 'text-center' },
    { data: 'usuario', className: 'text-center' },
    { data: 'fechaIngreso', className: 'text-center' },
    { data: 'acciones', orderable: false, searchable: false, className: 'text-center' }
],

        pageLength: 10,
        order: [[2, 'desc']],
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
                previous: 'Anterior'
            }
        },
        dom: 'rt<"flex flex-wrap justify-between items-center mt-4"ilp>',
        initComplete: function () {
            const wrapper = document.querySelector('.dataTables_wrapper');
            const tableEl = wrapper.querySelector('#tablaClientes');

            const scrollContainer = document.createElement('div');
            scrollContainer.className = 'dataTables_scrollable overflow-x-auto border border-gray-200 rounded-md mb-3';
            tableEl.parentNode.insertBefore(scrollContainer, tableEl);
            scrollContainer.appendChild(tableEl);

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

    const $input = $('#searchInput');
    const $clearBtn = $('#clearInput');

    $('#btnSearch').on('click', () => {
        const value = $input.val();
        table.search(value).draw();
    });

    $input.on('keypress', function (e) {
        if (e.which === 13) $('#btnSearch').click();
    });

    $input.on('input', function () {
        $clearBtn.toggleClass('hidden', $input.val().trim() === '');
    });

    $clearBtn.on('click', function () {
        $input.val('');
        $clearBtn.addClass('hidden');
        table.search('').draw();
    });
});
