document.addEventListener('alpine:init', () => {

    Alpine.data('dangerZone', (userId, estado) => ({

        passwordData: {
            current: '',
            new: '',
            confirm: ''
        },

        showCurrent: false,
        showNew: false,
        showConfirm: false,

        loading: false,
        loadingPDF: false,
        loadingZIP: false,
        loadingEnlace: false,
        loadingAccount: false,

        deactivateAccount: estado == 0,
        userId: userId,

        get passwordsMatch() {
            return this.passwordData.new === this.passwordData.confirm &&
                   this.passwordData.new.length >= 6;
        },

        get accountStatus() {
            return this.deactivateAccount ? 'Inactivo' : 'Activo';
        },

        get accountStatusClass() {
            return this.deactivateAccount
                ? 'text-red-600 font-bold'
                : 'text-green-600 font-bold';
        },

        async changePassword() {

            if (!this.passwordsMatch) {
                toastr.error('Las contraseñas no coinciden o son muy cortas');
                return;
            }

            this.loading = true;

            try {
                const response = await fetch(`/usuario/${this.userId}/cambiar-password`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        current_password: this.passwordData.current,
                        new_password: this.passwordData.new,
                        new_password_confirmation: this.passwordData.confirm
                    })
                });

                const data = await response.json();

                if (data.success) {
                    toastr.success('Contraseña cambiada exitosamente');
                    this.passwordData = { current: '', new: '', confirm: '' };
                } else {
                    toastr.error(data.message || 'Error al cambiar contraseña');
                }

            } catch (e) {
                toastr.error('Error al cambiar contraseña');
            } finally {
                this.loading = false;
            }
        },

        async desactivarCuenta() {

            if (!confirm('¿Seguro que quieres desactivar tu cuenta?')) return;

            this.loadingAccount = true;

            try {
                const response = await fetch(`/usuario/${this.userId}/desactivar-cuenta`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.deactivateAccount = true;
                    toastr.success('Cuenta desactivada');
                }

            } finally {
                this.loadingAccount = false;
            }
        },

        async activarCuenta() {

            if (!confirm('¿Seguro que quieres activar tu cuenta?')) return;

            this.loadingAccount = true;

            try {
                const response = await fetch(`/usuario/${this.userId}/activar-cuenta`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.deactivateAccount = false;
                    toastr.success('Cuenta activada');
                }

            } finally {
                this.loadingAccount = false;
            }
        },

        descargarPDF() {
            window.open(`/usuario/${this.userId}/generar-pdf`, '_blank');
        },

        descargarDocumentosZIP() {
            window.open(`/usuario/${this.userId}/descargar-documentos`, '_blank');
        },

        async enviarEnlaceRecuperacion() {

            if (!confirm('¿Deseas enviar enlace de recuperación?')) return;

            this.loadingEnlace = true;

            try {
                const response = await fetch(`/usuario/${this.userId}/enviar-recuperacion`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    toastr.success('Enlace enviado');
                }

            } finally {
                this.loadingEnlace = false;
            }
        }

    }));

});
