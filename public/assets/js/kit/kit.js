document.addEventListener('alpine:init', () => {
    Alpine.data('kitManager', () => ({
        // Datos del Kit
        kit: {
            codigo: '',
            nombre: '',
            descripcion: '',
            fecha: '',
            moneda_compra: 'S/',
            precio_compra: 0,
            moneda_venta: 'S/',
            precio_venta: 0,
            symbol_compra: 'S/',
            symbol_venta: 'S/',
        },
        kits: [], // Lista de kits
        kitArticulos: [], // Artículos asignados al kit
        availableArticulos: [
            // Artículos disponibles
            {
                id: 1,
                codigo: 'A001',
                nombre: 'Artículo 1',
                cantidad: 0,
                showInput: false,
            },
            {
                id: 2,
                codigo: 'A002',
                nombre: 'Artículo 2',
                cantidad: 0,
                showInput: false,
            },
            {
                id: 3,
                codigo: 'A003',
                nombre: 'Artículo 3',
                cantidad: 0,
                showInput: false,
            },
            {
                id: 4,
                codigo: 'A004',
                nombre: 'Artículo 4',
                cantidad: 0,
                showInput: false,
            },
            {
                id: 5,
                codigo: 'A005',
                nombre: 'Artículo 5',
                cantidad: 0,
                showInput: false,
            },
        ],
        searchQuery: '', // Query del buscador
        showArticlesSection: false, // Control para mostrar la sección de artículos
        currentKitName: '', // Nombre actual del kit
        showModal: false, // Control para mostrar el modal
        selectedArticle: {}, // Artículo seleccionado para el modal

        // Función para agregar un nuevo kit
        addKit() {
            // Crear el objeto del kit con un ID único
            this.kits.push({
                ...this.kit,
                id: Date.now(),
            });

            // Actualizar el nombre del kit actual
            this.currentKitName = this.kit.nombre;

            // Vaciar los artículos del kit
            this.kitArticulos = [];

            // Mostrar la sección de artículos
            this.showArticlesSection = true;

            // Configurar Sortable.js
            this.initializeSortable();

            // Obtener el formulario por su ID
            const formulario = document.getElementById('kitForm'); // Asegúrate de tener un formulario con este ID

            // Crear un FormData a partir del formulario
            let formData = new FormData(formulario);

            // Agregar datos adicionales si es necesario
            formData.append('nombre', this.kit.nombre);
            formData.append('descripcion', this.kit.descripcion);
            formData.append('precio_compra', this.kit.precio_compra);
            formData.append('precio', this.kit.precio);

            // Realizar el envío del formulario
            fetch('/kits/store', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Accept: 'application/json',
                },
                body: formData, // Enviar los datos del kit
            })
                .then((response) => response.json()) // Espera una respuesta JSON
                .then((data) => {
                    if (data.success) {
                        // Mostrar mensaje de éxito
                        showMessage('Kit agregado correctamente.', 'top-end');

                        // Limpiar el formulario después del envío
                        formulario.reset();
                    } else {
                        // Mostrar alerta de error
                        showMessage('Hubo un error al guardar el kit.', 'top-end', true, '', 3000, 'error');
                    }
                })
                .catch((error) => {
                    console.error('Error en la solicitud:', error);
                    // Mostrar mensaje de error
                    showMessage('Ocurrió un error, por favor intenta de nuevo.', 'top-end', true, '', 3000, 'error');
                });
        },

        // Función para guardar los artículos en el kit
        saveArticles() {
            alert(`Artículos guardados en el kit "${this.currentKitName}"!`);
        },

        // Función para ver los detalles de un artículo en el modal
        viewArticle(article) {
            this.selectedArticle = article; // Asignar el artículo seleccionado
            this.showModal = true; // Mostrar el modal
        },

        // Actualizar la cantidad de un artículo
        updateQuantity(article) {
            const index = this.kitArticulos.findIndex((a) => a.id === article.id);
            if (index !== -1) {
                this.kitArticulos[index].cantidad = article.cantidad;
            }
        },

        // Función para actualizar el símbolo de la moneda de compra
        updateMonedaCompra() {
            this.kit.symbol_compra = this.kit.moneda_compra;
        },

        // Función para actualizar el símbolo de la moneda de venta
        updateMonedaVenta() {
            this.kit.symbol_venta = this.kit.moneda_venta;
        },

        // Filtrar artículos disponibles en base al buscador
        get filteredAvailableArticulos() {
            const query = this.searchQuery.toLowerCase();
            return this.availableArticulos.filter((art) => art.nombre.toLowerCase().includes(query) || art.codigo.toLowerCase().includes(query));
        },

        // Configurar Sortable.js
        initializeSortable() {
            // Lista izquierda (kit)
            Sortable.create(document.getElementById('kitItemsList'), {
                animation: 150,
                group: 'shared',
                onAdd: (evt) => {
                    const itemId = parseInt(evt.item.dataset.id, 10);
                    const item = this.availableArticulos.find((art) => art.id === itemId);
                    if (item) {
                        this.kitArticulos.push({
                            ...item,
                            cantidad: 0,
                            showInput: false,
                        });
                        this.availableArticulos = this.availableArticulos.filter((art) => art.id !== itemId);
                    }
                },
                onRemove: (evt) => {
                    const itemId = parseInt(evt.item.dataset.id, 10);
                    const item = this.kitArticulos.find((art) => art.id === itemId);
                    if (item) {
                        this.availableArticulos.push(item);
                        this.kitArticulos = this.kitArticulos.filter((art) => art.id !== itemId);
                    }
                },
            });

            // Lista derecha (disponibles)
            Sortable.create(document.getElementById('availableItemsList'), {
                animation: 150,
                group: 'shared',
            });
        },
    }));
});
// Función para mostrar la alerta con SweetAlert
function showMessage(
    msg = 'Example notification text.',
    position = 'top-end',
    showCloseButton = true,
    closeButtonHtml = '',
    duration = 3000,
    type = 'success',
) {
    const toast = window.Swal.mixin({
        toast: true,
        position: position || 'top-end',
        showConfirmButton: false,
        timer: duration,
        showCloseButton: showCloseButton,
        icon: type === 'success' ? 'success' : 'error', // Cambia el icono según el tipo
        background: type === 'success' ? '#28a745' : '#dc3545', // Verde para éxito, Rojo para error
        iconColor: 'white', // Color del icono
        customClass: {
            title: 'text-white', // Asegura que el texto sea blanco
        },
    });

    toast.fire({
        title: msg,
    });
}

document.addEventListener('DOMContentLoaded', function () {
    // Inicializa flatpickr en el campo de fecha
    flatpickr('#fecha', {
        dateFormat: 'Y-m-d', // Formato de fecha
        defaultDate: new Date(), // Fecha predeterminada: hoy
        altInput: true, // Mostrar campo alternativo amigable
        altFormat: 'F j, Y', // Formato amigable para el usuario
        locale: 'es', // Localización en español
    });
});
