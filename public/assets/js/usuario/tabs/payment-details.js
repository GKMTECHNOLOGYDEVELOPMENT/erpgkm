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
    // 2. FIRMA DIGITAL (SignaturePad)
    // ============================================
    const SignatureModule = (function() {
        let signaturePad = null;
        let canvas = null;

        function init(userId) {
            if (userId) config.userId = userId;
            
            canvas = document.getElementById('signature-pad');
            if (!canvas) return;

            // Evitar múltiples inicializaciones
            if (window.signaturePadInstance) {
                signaturePad = window.signaturePadInstance;
                return;
            }

            // Verificar si SignaturePad está disponible
            if (typeof SignaturePad === 'undefined') {
                console.error('SignaturePad library no está cargada');
                return;
            }

            // Crear instancia de SignaturePad
            signaturePad = new SignaturePad(canvas, {
                minWidth: 0.5,
                maxWidth: 1.2,
                penColor: 'black',
                backgroundColor: 'white'
            });

            window.signaturePadInstance = signaturePad;
            
            setupEventListeners();
            
            if (config.userId) {
                cargarFirmaExistente();
            }
        }

        function setupEventListeners() {
            const clearBtn = document.getElementById('clear-btn');
            const saveBtn = document.getElementById('save-btn');
            const refreshBtn = document.getElementById('refresh-btn');

            if (clearBtn) {
                clearBtn.addEventListener('click', () => signaturePad?.clear());
            }

            if (saveBtn) {
                saveBtn.addEventListener('click', guardarFirma);
            }

            if (refreshBtn) {
                refreshBtn.addEventListener('click', () => cargarFirmaExistente());
            }
        }

        function guardarFirma() {
            if (!signaturePad || !config.userId) {
                toastr?.error('Error: No se pudo guardar la firma');
                return;
            }

            if (signaturePad.isEmpty()) {
                toastr?.error('Por favor, proporciona tu firma primero.');
                return;
            }

            const dataURL = signaturePad.toDataURL();
            const binaryData = dataURL.split(',')[1];

            fetch(`/usuario/firma/${config.userId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': config.csrfToken
                },
                body: JSON.stringify({ firma: binaryData })
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                toastr?.success('Firma guardada correctamente.');
                console.log('Firma guardada:', data);
            })
            .catch(error => {
                console.error('Error al guardar la firma:', error);
                toastr?.error('Error al guardar la firma');
            });
        }

        function cargarFirmaExistente() {
            if (!canvas || !config.userId) return;

            fetch(`/usuario/firma/${config.userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.firma) {
                        signaturePad?.fromDataURL(data.firma);
                    }
                })
                .catch(error => console.error('Error al cargar la firma:', error));
        }

        return { init };
    })();

    // ============================================
    // 3. CUENTAS BANCARIAS
    // ============================================
    const CuentasBancariasModule = (function() {
        function init(userId) {
            if (userId) config.userId = userId;
            setupFormListeners();
            if (config.userId) {
                cargarCuentasBancarias(config.userId);
            }
        }

        function setupFormListeners() {
            const payBrand = document.getElementById('payBrand');
            const saveBtn = document.getElementById('saveBtn');

            if (payBrand) {
                payBrand.addEventListener('change', function() {
                    const tipoCuenta = this.value;
                    const numeroCuentaInput = document.getElementById('payNumber');
                    
                    if (!numeroCuentaInput) return;

                    numeroCuentaInput.value = '';
                    numeroCuentaInput.removeAttribute('maxlength');
                    numeroCuentaInput.placeholder = 'Número de cuenta';

                    if (tipoCuenta === '1') {
                        numeroCuentaInput.maxLength = 20;
                        numeroCuentaInput.placeholder = 'Número interbancario (20 dígitos)';
                    } else if (tipoCuenta === '2') {
                        numeroCuentaInput.maxLength = 24;
                        numeroCuentaInput.placeholder = 'Número de cuenta (13-24 dígitos)';
                    }
                });
            }

            if (saveBtn) {
                saveBtn.addEventListener('click', guardarCuentaBancaria);
            }
        }

        function guardarCuentaBancaria() {
            const tipoCuenta = document.getElementById('payBrand')?.value;
            const numeroCuenta = document.getElementById('payNumber')?.value;
            const banco = document.getElementById('banco')?.value;

            if (!tipoCuenta || !numeroCuenta || !banco || tipoCuenta === 'Seleccione una Opción') {
                toastr?.error('Por favor, complete todos los campos');
                return;
            }

            if (tipoCuenta === '1' && numeroCuenta.length !== 20) {
                toastr?.error('El número interbancario debe tener exactamente 20 dígitos.');
                return;
            }

            if (tipoCuenta === '2' && (numeroCuenta.length < 13 || numeroCuenta.length > 24)) {
                toastr?.error('El número de cuenta debe tener entre 13 y 24 dígitos.');
                return;
            }

            fetch('/api/guardar-cuenta', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': config.csrfToken
                },
                body: JSON.stringify({
                    tipoCuenta: tipoCuenta,
                    numeroCuenta: numeroCuenta,
                    banco: banco,
                    usuarioId: config.userId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr?.success('Cuenta bancaria guardada con éxito');
                    cargarCuentasBancarias(config.userId);
                    
                    document.getElementById('payBrand').value = '';
                    document.getElementById('payNumber').value = '';
                    document.getElementById('banco').value = '';
                } else {
                    toastr?.error(data.message || 'Hubo un error al guardar la cuenta bancaria');
                }
            })
            .catch(error => {
                console.error('Error al guardar la cuenta:', error);
                toastr?.error('Error al guardar la cuenta bancaria');
            });
        }

        function cargarCuentasBancarias(userId) {
            if (!userId) {
                console.warn('No se puede cargar cuentas bancarias: userId no disponible');
                return;
            }

            const container = document.getElementById('cuentas-bancarias');
            if (!container) return;

            fetch(`/api/cuentas-bancarias/${userId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Error en la solicitud');
                    return response.json();
                })
                .then(cuentas => {
                    container.innerHTML = '';
                    
                    if (cuentas.length === 0) {
                        container.innerHTML = '<p class="text-gray-500 text-center py-4">No hay cuentas bancarias registradas.</p>';
                        return;
                    }

                    cuentas.forEach(cuenta => {
                        const cuentaElement = document.createElement('div');
                        cuentaElement.className = 'border-b border-[#ebedf2] dark:border-[#1b2e4b]';
                        cuentaElement.innerHTML = `
                            <div class="flex items-start justify-between py-3">
                                <div class="flex-none ltr:mr-4 rtl:ml-4">
                                    <img src="/assets/images/card-${cuenta.tipodecuenta === 1 ? 'visa' : 'mastercard'}.svg" alt="card" />
                                </div>
                                <h6 class="text-[#515365] font-bold dark:text-white-dark text-[15px]">
                                    ${cuenta.banco_nombre || 'Banco'}
                                    <span class="block text-white-dark dark:text-white-light font-normal text-xs mt-1">
                                        **** **** **** ${cuenta.numerocuenta.slice(-4)}
                                    </span>
                                </h6>
                                <div class="flex items-start justify-between ltr:ml-auto rtl:mr-auto gap-2">
                                    <button class="btn btn-primary btn-sm" onclick="CuentasBancariasModule.verCuenta('${cuenta.id}')">Ver</button>
                                    <button class="btn btn-dark btn-sm" onclick="CuentasBancariasModule.editarCuenta('${cuenta.id}')">Editar</button>
                                    <button class="btn btn-danger btn-sm" onclick="CuentasBancariasModule.eliminarCuenta('${cuenta.id}')">Eliminar</button>
                                </div>
                            </div>
                        `;
                        container.appendChild(cuentaElement);
                    });
                })
                .catch(error => {
                    console.error('Error al cargar cuentas bancarias:', error);
                    container.innerHTML = '<p class="text-red-500 text-center py-4">Error al cargar las cuentas bancarias.</p>';
                });
        }

        function verCuenta(id) {
            console.log('Ver cuenta:', id);
        }

        function editarCuenta(id) {
            console.log('Editar cuenta:', id);
        }

        function eliminarCuenta(id) {
            if (confirm('¿Estás seguro de que deseas eliminar esta cuenta bancaria?')) {
                fetch(`/api/cuentas-bancarias/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': config.csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr?.success('Cuenta eliminada correctamente');
                        cargarCuentasBancarias(config.userId);
                    }
                })
                .catch(error => console.error('Error al eliminar cuenta:', error));
            }
        }

        return {
            init,
            cargarCuentasBancarias,
            verCuenta,
            editarCuenta,
            eliminarCuenta
        };
    })();

    // ============================================
    // 4. UTILIDADES GENERALES
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
        }
    };

    // ============================================
    // 5. INICIALIZACIÓN DESDE ALPINE.JS
    // ============================================
    window.initPaymentDetails = function(userId) {
        config.userId = userId;
        SignatureModule.init(userId);
        CuentasBancariasModule.init(userId);
        Utils.initSelect2();
        console.log('✅ Payment Details inicializado con userId:', userId);
    };

    // Exponer funciones globales
    window.previewImage = Utils.previewImage;
    window.CuentasBancariasModule = CuentasBancariasModule;
    window.cargarCuentasBancarias = CuentasBancariasModule.cargarCuentasBancarias;

})();