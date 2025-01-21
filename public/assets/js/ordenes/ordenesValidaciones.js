document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('ordenTrabajoForm');
    const numeroTicketInput = document.getElementById('nroTicket');

    // Validaciones
    const validateNumeroTicketUnico = async (numero_ticket) => {
        try {
            const response = await fetch('/api/ordenes/check-ticket', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify({
                    numero_ticket,
                }),
            });
            const data = await response.json();
            return data.unique; // true si es único, false si ya existe
        } catch (error) {
            console.error('Error al validar el número de ticket:', error);
            return false;
        }
    };

    const validateNumeroTicket = (value) => {
        const regex = /^[a-zA-Z0-9-]+$/; // Permite letras, números y guiones
        return value.trim() !== '' && regex.test(value);
    };

    // Escucha de eventos para validaciones en tiempo real
    numeroTicketInput.addEventListener('input', async () => {
        const numero_ticket = numeroTicketInput.value;
        if (!validateNumeroTicket(numero_ticket)) {
            numeroTicketInput.setCustomValidity(
                'El número de ticket no debe estar vacío ni contener caracteres no permitidos.',
            );
        } else if (!(await validateNumeroTicketUnico(numero_ticket))) {
            numeroTicketInput.setCustomValidity('El número de ticket ya está en uso.');
        } else {
            numeroTicketInput.setCustomValidity('');
        }
        numeroTicketInput.reportValidity();
    });

    // Validaciones al enviar el formulario
    form.addEventListener('submit', async (event) => {
        const numero_ticket = numeroTicketInput.value;

        // Validar el número de ticket
        if (!validateNumeroTicket(numero_ticket)) {
            event.preventDefault();
            return;
        }

        if (!(await validateNumeroTicketUnico(numero_ticket))) {
            event.preventDefault();
            return;
        }

        // Si todo es válido, el formulario se enviará
    });
});
