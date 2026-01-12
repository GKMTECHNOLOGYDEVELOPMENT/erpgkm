                document.addEventListener('DOMContentLoaded', function() {
                    // ========== FILTROS CON AJAX (SIN RECARGAR PÁGINA) ==========
                    const form = document.getElementById('filtrosForm');
                    const searchInput = document.getElementById('searchInput');
                    const resultsContainer = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3');
                    let searchTimeout = null;
                    let currentRequest = null;

                    // Función para cargar resultados con AJAX
                    function cargarResultados() {
                        // Cancelar request anterior si existe
                        if (currentRequest) {
                            currentRequest.abort();
                        }

                        const formData = new FormData(form);
                        const params = new URLSearchParams(formData);

                        // Mostrar loading
                        if (resultsContainer) {
                            const loadingHtml = `
                    <div class="col-span-full py-12 text-center">
                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                        <p class="mt-4 text-gray-600">Buscando solicitudes...</p>
                    </div>
                `;
                            resultsContainer.innerHTML = loadingHtml;
                        }

                        // Mostrar loading en búsqueda
                        const searchIcon = searchInput ? searchInput.parentNode.querySelector('svg') : null;
                        if (searchIcon) {
                            searchIcon.classList.add('animate-pulse', 'text-blue-500');
                        }

                        // Hacer request AJAX para obtener toda la página
                        currentRequest = fetch(`${form.action}?${params.toString()}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => {
                                if (!response.ok) throw new Error('Error en la respuesta');
                                return response.text();
                            })
                            .then(html => {
                                // Parsear el HTML completo
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');

                                // Extraer SOLO la parte de resultados
                                const newResults = doc.querySelector(
                                    '.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3');
                                if (newResults && resultsContainer) {
                                    resultsContainer.innerHTML = newResults.innerHTML;
                                }

                                // Extraer paginación
                                const newPagination = doc.querySelector('.mt-8');
                                const paginationContainer = document.querySelector('.mt-8');
                                if (newPagination && paginationContainer) {
                                    paginationContainer.innerHTML = newPagination.innerHTML;
                                } else if (paginationContainer && !newPagination) {
                                    paginationContainer.innerHTML = '';
                                }

                                // Extraer filtros activos
                                const newActiveFilters = doc.querySelector('.mt-4.pt-4');
                                const activeFiltersContainer = document.querySelector('.mt-4.pt-4');
                                if (newActiveFilters && activeFiltersContainer) {
                                    activeFiltersContainer.innerHTML = newActiveFilters.innerHTML;

                                    // Re-asignar eventos a los enlaces de remover filtros
                                    setTimeout(() => {
                                        document.querySelectorAll('.mt-4.pt-4 a[href*="fullUrlWithoutQuery"]')
                                            .forEach(link => {
                                                link.addEventListener('click', function(e) {
                                                    e.preventDefault();
                                                    const url = this.href;
                                                    fetch(url, {
                                                            headers: {
                                                                'X-Requested-With': 'XMLHttpRequest'
                                                            }
                                                        })
                                                        .then(response => response.text())
                                                        .then(html => {
                                                            const parser = new DOMParser();
                                                            const doc = parser.parseFromString(
                                                                html, 'text/html');
                                                            const newResults = doc
                                                                .querySelector(
                                                                    '.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3'
                                                                    );
                                                            if (newResults &&
                                                                resultsContainer) {
                                                                resultsContainer.innerHTML =
                                                                    newResults.innerHTML;
                                                            }
                                                            window.history.pushState({}, '',
                                                                url);
                                                        });
                                                });
                                            });
                                    }, 100);
                                }

                                // Extraer botón limpiar
                                const newClearBtn = doc.querySelector('.flex.justify-end');
                                const clearBtnContainer = document.querySelector('.flex.justify-end');
                                if (newClearBtn && clearBtnContainer) {
                                    clearBtnContainer.innerHTML = newClearBtn.innerHTML;

                                    // Re-asignar evento al botón limpiar
                                    setTimeout(() => {
                                        const clearBtn = clearBtnContainer.querySelector('a');
                                        if (clearBtn) {
                                            clearBtn.addEventListener('click', function(e) {
                                                e.preventDefault();
                                                fetch(this.href, {
                                                        headers: {
                                                            'X-Requested-With': 'XMLHttpRequest'
                                                        }
                                                    })
                                                    .then(response => response.text())
                                                    .then(html => {
                                                        const parser = new DOMParser();
                                                        const doc = parser.parseFromString(html,
                                                            'text/html');
                                                        const newResults = doc.querySelector(
                                                            '.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3'
                                                            );
                                                        if (newResults && resultsContainer) {
                                                            resultsContainer.innerHTML = newResults
                                                                .innerHTML;
                                                        }
                                                        window.history.pushState({}, '', this.href);
                                                    });
                                            });
                                        }
                                    }, 100);
                                }

                                // Actualizar URL sin recargar
                                const newUrl = `${window.location.pathname}?${params.toString()}`;
                                window.history.pushState({}, '', newUrl);

                                // Actualizar estilos de selects
                                updateSelectsStyles();

                                // Re-asignar eventos a los botones de los modales
                                setTimeout(() => {
                                    if (document.getElementById('openModalBtnEmpty')) {
                                        document.getElementById('openModalBtnEmpty').addEventListener('click',
                                            function() {
                                                openModalWithAnimation(firstModal);
                                            });
                                    }
                                }, 100);
                            })
                            .catch(error => {
                                if (error.name !== 'AbortError') {
                                    console.error('Error al cargar resultados:', error);
                                    if (resultsContainer) {
                                        resultsContainer.innerHTML = `
                            <div class="col-span-full py-12 text-center">
                                <div class="text-red-500 mb-4">
                                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">Error al cargar resultados</h3>
                                <p class="mt-1 text-gray-500">Intenta de nuevo en unos momentos</p>
                                <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                    Reintentar
                                </button>
                            </div>
                        `;
                                    }
                                }
                            })
                            .finally(() => {
                                currentRequest = null;
                                if (searchIcon) {
                                    searchIcon.classList.remove('animate-pulse');
                                    searchIcon.classList.remove('text-blue-500');
                                    searchIcon.classList.add('text-gray-400');
                                }
                            });
                    }

                    // Función para debounce
                    function debounceSubmit(delay = 600) {
                        if (searchTimeout) {
                            clearTimeout(searchTimeout);
                        }
                        searchTimeout = setTimeout(cargarResultados, delay);
                    }

                    // Función para actualizar estilos de selects
                    function updateSelectsStyles() {
                        const selects = document.querySelectorAll('.filtro-select');
                        selects.forEach(select => {
                            if (select.value) {
                                select.classList.add('font-medium', 'bg-gray-50');
                            } else {
                                select.classList.remove('font-medium', 'bg-gray-50');
                            }
                        });
                    }

                    // Manejar cambio en selects
                    const selects = document.querySelectorAll('.filtro-select');
                    selects.forEach(select => {
                        select.addEventListener('change', function() {
                            // Animación visual
                            this.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.2)';
                            setTimeout(() => {
                                this.style.boxShadow = '';
                            }, 300);

                            // Actualizar estilos
                            updateSelectsStyles();

                            // Cargar resultados inmediatamente
                            cargarResultados();
                        });
                    });

                    // Manejar búsqueda con debounce
                    if (searchInput) {
                        searchInput.addEventListener('input', function() {
                            // Mostrar indicador de búsqueda
                            const icon = this.parentNode.querySelector('svg');
                            if (icon) {
                                icon.classList.remove('text-gray-400');
                                icon.classList.add('text-blue-500', 'animate-pulse');
                            }

                            // Aplicar debounce
                            debounceSubmit(800);
                        });

                        // También permitir búsqueda con Enter
                        searchInput.addEventListener('keypress', function(e) {
                            if (e.key === 'Enter') {
                                e.preventDefault();
                                if (searchTimeout) {
                                    clearTimeout(searchTimeout);
                                }
                                cargarResultados();
                            }
                        });
                    }

                    // Botón Limpiar - prevenir recarga y usar AJAX
                    const clearBtn = document.querySelector('a[href="{{ route(\'solicitudarticulo.index\') }}"]');
                    if (clearBtn) {
                        clearBtn.addEventListener('click', function(e) {
                            e.preventDefault();

                            // Limpiar todos los filtros
                            selects.forEach(select => {
                                select.value = '';
                            });

                            if (searchInput) {
                                searchInput.value = '';
                            }

                            // Cargar resultados limpios
                            fetch("{{ route('solicitudarticulo.index') }}", {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(response => response.text())
                                .then(html => {
                                    const parser = new DOMParser();
                                    const doc = parser.parseFromString(html, 'text/html');
                                    const newResults = doc.querySelector(
                                        '.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3');
                                    if (newResults && resultsContainer) {
                                        resultsContainer.innerHTML = newResults.innerHTML;
                                    }
                                    window.history.pushState({}, '', "{{ route('solicitudarticulo.index') }}");
                                });
                        });
                    }

                    // Inicializar estilos de selects
                    updateSelectsStyles();

                    // Soporte para navegación con botones atrás/adelante
                    window.addEventListener('popstate', function() {
                        cargarResultados();
                    });

                    // ========== MODALES ==========
                    const firstModal = document.getElementById('solicitudModal');
                    const openModalBtn = document.getElementById('openModalBtn');
                    const openModalBtnEmpty = document.getElementById('openModalBtnEmpty');
                    const closeModalBtn = document.getElementById('closeModalBtn');
                    const btnRepuesto = document.getElementById('btnRepuesto');
                    const provinciaModal = document.getElementById('provinciaModal');
                    const backToFirstModal = document.getElementById('backToFirstModal');
                    const closeProvinciaModal = document.getElementById('closeProvinciaModal');
                    const btnSiProvincia = document.getElementById('btnSiProvincia');
                    const btnNoProvincia = document.getElementById('btnNoProvincia');
                    const rutaParaProvincia = "{{ route('solicitudrepuesto.create.provincia') }}";
                    const rutaNoParaProvincia = "{{ route('solicitudrepuesto.create') }}";

                    // Función para abrir modal con animación
                    function openModalWithAnimation(modal) {
                        if (!modal) return;
                        modal.classList.remove('hidden');
                        setTimeout(() => {
                            const content = modal.querySelector('.bg-white');
                            if (content) {
                                content.classList.remove('scale-95', 'opacity-0');
                                content.classList.add('scale-100', 'opacity-100');
                            }
                        }, 10);
                    }

                    function closeModalWithAnimation(modal) {
                        if (!modal) return;
                        const content = modal.querySelector('.bg-white');
                        if (content) {
                            content.classList.remove('scale-100', 'opacity-100');
                            content.classList.add('scale-95', 'opacity-0');
                        }
                        setTimeout(() => {
                            modal.classList.add('hidden');
                        }, 300);
                    }

                    // 1. Abrir primer modal con animación
                    if (openModalBtn) {
                        openModalBtn.addEventListener('click', function() {
                            openModalWithAnimation(firstModal);
                        });
                    }

                    if (openModalBtnEmpty) {
                        openModalBtnEmpty.addEventListener('click', function() {
                            openModalWithAnimation(firstModal);
                        });
                    }

                    // 2. Cerrar primer modal con animación
                    if (closeModalBtn) {
                        closeModalBtn.addEventListener('click', function() {
                            closeModalWithAnimation(firstModal);
                        });
                    }

                    // 3. Cuando hacen clic en "Solicitud de Repuesto"
                    if (btnRepuesto) {
                        btnRepuesto.addEventListener('click', function() {
                            closeModalWithAnimation(firstModal);
                            setTimeout(() => {
                                openModalWithAnimation(provinciaModal);
                            }, 300);
                        });
                    }

                    // 4. Volver al primer modal desde el segundo con animación
                    if (backToFirstModal) {
                        backToFirstModal.addEventListener('click', function() {
                            closeModalWithAnimation(provinciaModal);
                            setTimeout(() => {
                                openModalWithAnimation(firstModal);
                            }, 300);
                        });
                    }

                    // 5. Cerrar segundo modal con animación
                    if (closeProvinciaModal) {
                        closeProvinciaModal.addEventListener('click', function() {
                            closeModalWithAnimation(provinciaModal);
                        });
                    }

                    // 6. Cuando seleccionan SÍ (para provincia)
                    if (btnSiProvincia) {
                        btnSiProvincia.addEventListener('click', function(e) {
                            e.currentTarget.style.transform = 'scale(0.95)';
                            setTimeout(() => {
                                window.location.href = rutaParaProvincia;
                            }, 150);
                        });
                    }

                    // 7. Cuando seleccionan NO (no para provincia)
                    if (btnNoProvincia) {
                        btnNoProvincia.addEventListener('click', function(e) {
                            e.currentTarget.style.transform = 'scale(0.95)';
                            setTimeout(() => {
                                window.location.href = rutaNoParaProvincia;
                            }, 150);
                        });
                    }

                    // 8. Cerrar modales al hacer clic fuera (con animación)
                    function setupModalClose(modal) {
                        if (modal) {
                            modal.addEventListener('click', function(e) {
                                if (e.target === modal) {
                                    closeModalWithAnimation(modal);
                                }
                            });
                        }
                    }

                    setupModalClose(firstModal);
                    setupModalClose(provinciaModal);

                    // 9. Cerrar modales con tecla ESC (con animación)
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            if (provinciaModal && !provinciaModal.classList.contains('hidden')) {
                                closeModalWithAnimation(provinciaModal);
                            } else if (firstModal && !firstModal.classList.contains('hidden')) {
                                closeModalWithAnimation(firstModal);
                            }
                        }
                    });

                    // 10. Asegurar que los modales tengan animación
                    function ensureModalAnimation(modal) {
                        if (modal) {
                            const content = modal.querySelector('.bg-white');
                            if (content && !content.classList.contains('transform')) {
                                content.classList.add('transform', 'transition-all', 'duration-300', 'scale-95',
                                    'opacity-0');
                            }
                        }
                    }

                    ensureModalAnimation(firstModal);
                    ensureModalAnimation(provinciaModal);
                });
