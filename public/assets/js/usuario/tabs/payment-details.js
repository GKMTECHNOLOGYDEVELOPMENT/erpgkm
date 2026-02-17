// ============================================
// ARCHIVO: payment-details.js
// ============================================
(function() {
    'use strict';

    // ============================================
    // 1. CONFIGURACIÓN GLOBAL
    // ============================================
    const config = {
        userId: null, // Se establecerá desde Alpine.js o variable global
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    };

    // ============================================
    // 2. CUENTAS BANCARIAS (desde usuarios_ficha_general)
    // ============================================
    const CuentasBancariasModule = (function() {
        let datosBancarios = null; // Almacenar los datos cargados

        function init(userId) {
            if (userId) config.userId = userId;
            cargarDatosBancarios();
            setupFormListeners();
        }

        function cargarDatosBancarios() {
            if (!config.userId) {
                console.warn('No se puede cargar datos bancarios: userId no disponible');
                return;
            }

            // Los datos ya están en la vista a través de $fichaGeneral
            // Esta función es para recargar después de guardar
            const container = document.getElementById('cuentas-bancarias');
            if (!container) return;

            // Mostrar los datos actuales (ya vienen del servidor)
            mostrarCuentaEnUI();
        }

        function mostrarCuentaEnUI() {
            const container = document.getElementById('cuentas-bancarias');
            if (!container) return;

            // Obtener datos del formulario (ya están cargados del servidor)
            const bancoSelect = document.getElementById('banco');
            const monedaSelect = document.getElementById('moneda');
            const tipoCuentaSelect = document.getElementById('tipoCuenta');
            const numeroCuentaInput = document.getElementById('numeroCuenta');
            const numeroCCIInput = document.getElementById('numeroCCI');

            const bancoNombre = bancoSelect?.options[bancoSelect.selectedIndex]?.text || 'No especificado';
            const monedaNombre = monedaSelect?.options[monedaSelect.selectedIndex]?.text || 'No especificada';
            const tipoCuentaNombre = tipoCuentaSelect?.options[tipoCuentaSelect.selectedIndex]?.text || 'No especificado';
            const numeroCuenta = numeroCuentaInput?.value || '';
            const numeroCCI = numeroCCIInput?.value || '';

            if (!numeroCuenta) {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-university text-4xl text-green-300 mb-3"></i>
                        <p>No hay cuentas bancarias registradas</p>
                        <p class="text-xs mt-1">Complete el formulario para agregar su primera cuenta</p>
                    </div>
                `;
                return;
            }

            // Crear la tarjeta de cuenta
            const cuentaHTML = `
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-5 border border-green-200 dark:border-green-800/30">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-university"></i>
                            </div>
                            <div>
                                <h6 class="font-semibold text-gray-800 dark:text-white">${bancoNombre}</h6>
                                <p class="text-xs text-gray-500 dark:text-gray-400">${monedaNombre} - ${tipoCuentaNombre}</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-medium rounded-full">
                            Principal
                        </span>
                    </div>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Número de Cuenta</p>
                            <p class="font-mono text-sm font-medium text-gray-800 dark:text-white">${numeroCuenta}</p>
                        </div>
                        ${numeroCCI ? `
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Número de CCI</p>
                            <p class="font-mono text-sm font-medium text-gray-800 dark:text-white">${numeroCCI}</p>
                        </div>
                        ` : ''}
                    </div>
                    
                    <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-green-200 dark:border-green-800/30">
                        <button type="button" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1 edit-cuenta-btn">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                    </div>
                </div>
            `;

            container.innerHTML = cuentaHTML;
        }

        function setupFormListeners() {
            const saveBtn = document.getElementById('saveBtn');

            if (saveBtn) {
                saveBtn.removeEventListener('click', guardarCuentaBancaria);
                saveBtn.addEventListener('click', guardarCuentaBancaria);
            }
        }

        function guardarCuentaBancaria(e) {
            e.preventDefault();
            
            const banco = document.getElementById('banco')?.value;
            const moneda = document.getElementById('moneda')?.value;
            const tipoCuenta = document.getElementById('tipoCuenta')?.value;
            const numeroCuenta = document.getElementById('numeroCuenta')?.value;
            const numeroCCI = document.getElementById('numeroCCI')?.value;

            // Validaciones
            if (!banco || banco === '') {
                toastr?.error('Por favor, seleccione un banco');
                return;
            }

            if (!moneda || moneda === '') {
                toastr?.error('Por favor, seleccione una moneda');
                return;
            }

            if (!tipoCuenta || tipoCuenta === '') {
                toastr?.error('Por favor, seleccione un tipo de cuenta');
                return;
            }

            if (!numeroCuenta || numeroCuenta.trim() === '') {
                toastr?.error('Por favor, ingrese el número de cuenta');
                return;
            }

            if (!numeroCCI || numeroCCI.trim() === '') {
                toastr?.error('Por favor, ingrese el número de CCI');
                return;
            }

            // Validar formato (opcional)
            if (numeroCuenta.length < 5) {
                toastr?.error('El número de cuenta debe tener al menos 5 dígitos');
                return;
            }

            if (numeroCCI.length < 10) {
                toastr?.error('El CCI debe tener al menos 10 dígitos');
                return;
            }

            // Mostrar indicador de carga
            const $btn = $(saveBtn);
            const originalText = $btn.html();
            $btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...').prop('disabled', true);

            const data = {
                entidadBancaria: banco,
                moneda: moneda,
                tipoCuenta: tipoCuenta,
                numeroCuenta: numeroCuenta,
                numeroCCI: numeroCCI
            };

            fetch(`/usuario/${config.userId}/cuenta-bancaria/guardar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': config.csrfToken
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    toastr?.success(data.message);
                    // Actualizar la UI sin recargar
                    mostrarCuentaEnUI();
                    
                    // Cambiar título del formulario
                    const formTitle = document.getElementById('form-title');
                    if (formTitle) {
                        formTitle.textContent = 'Editar Cuenta Bancaria';
                    }
                    
                    // Cambiar texto del botón
                    $btn.html('<i class="fas fa-save"></i> Actualizar Cuenta Bancaria');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.errors) {
                    // Errores de validación
                    Object.values(error.errors).forEach(err => {
                        toastr?.error(err[0]);
                    });
                } else {
                    toastr?.error(error.message || 'Error al guardar la cuenta bancaria');
                }
            })
            .finally(() => {
                $btn.prop('disabled', false);
                if ($btn.html().includes('spinner')) {
                    $btn.html(originalText);
                }
            });
        }

        function editarCuenta() {
            // Scroll al formulario
            const form = document.getElementById('cuenta-bancaria-form');
            if (form) {
                form.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Resaltar el formulario
                form.classList.add('ring-2', 'ring-purple-500', 'ring-opacity-50');
                setTimeout(() => {
                    form.classList.remove('ring-2', 'ring-purple-500', 'ring-opacity-50');
                }, 2000);
            }
        }

        // Exponer funciones públicas
        return {
            init,
            cargarDatosBancarios: mostrarCuentaEnUI,
            editarCuenta
        };
    })();

    // ============================================
    // 3. UTILIDADES GENERALES
    // ============================================
    const Utils = {
        initSelect2() {
            if (typeof NiceSelect !== 'undefined') {
                document.querySelectorAll('.select2').forEach(select => {
                    NiceSelect.bind(select, { searchable: true });
                });
            }
        },

        previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('profile-img');
                if (output) output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        },

        // Validar números
        soloNumeros(input) {
            input.value = input.value.replace(/[^0-9-]/g, '');
        },

        soloNumerosSinGuion(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }
    };

    // ============================================
    // 4. INICIALIZACIÓN
    // ============================================
    function init(userId) {
        if (userId) config.userId = userId;
        
        console.log('✅ Payment Details inicializado con userId:', config.userId);
        
        CuentasBancariasModule.init(config.userId);
        Utils.initSelect2();
        setupEventListeners();
    }

    function setupEventListeners() {
        // Botón Editar cuenta
        $(document).on('click', '.edit-cuenta-btn', function() {
            CuentasBancariasModule.editarCuenta();
        });

        // Validaciones en tiempo real
        const numeroCuenta = document.getElementById('numeroCuenta');
        if (numeroCuenta) {
            numeroCuenta.addEventListener('input', function() {
                Utils.soloNumeros(this);
            });
        }

        const numeroCCI = document.getElementById('numeroCCI');
        if (numeroCCI) {
            numeroCCI.addEventListener('input', function() {
                Utils.soloNumerosSinGuion(this);
            });
        }
    }

    // ============================================
    // 5. EXPORTAR FUNCIONES GLOBALES
    // ============================================
    window.initPaymentDetails = init;
    window.previewImage = Utils.previewImage;
    window.CuentasBancariasModule = CuentasBancariasModule;

    // Auto-inicializar si hay userId en el DOM
    document.addEventListener('DOMContentLoaded', function() {
        const userIdElement = document.getElementById('user-id-data');
        if (userIdElement) {
            init(userIdElement.value);
        }
    });

})();