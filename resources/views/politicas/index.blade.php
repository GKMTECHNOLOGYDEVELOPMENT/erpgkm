<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Política de Privacidad</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            background: #0d0d20;
            color: #fff;
        }

        .card {
            backdrop-filter: blur(14px) saturate(180%);
            -webkit-backdrop-filter: blur(14px) saturate(180%);
            background-color: rgba(0, 0, 0, 0.6);
            /* un poco más transparente */
            border-radius: 12px;
            border: none;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.4);
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInCard 0.8s ease-out forwards;

        }

        @keyframes fadeInCard {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes hero-gradient-animation {
            0% {
                --x-0: 88%;
                --s-start-0: 8%;
                --s-end-0: 47.27292564072304%;
                --c-0: hsla(217.59493670886076, 100%, 30%, 1);
                --y-0: 57%;
                --y-1: 19%;
                --c-1: hsla(0, 0%, 0%, 1);
                --s-start-1: 1%;
                --s-end-1: 49.166438627289%;
                --x-1: 94%;
                --c-2: hsla(0, 0%, 0%, 1);
                --y-2: 77%;
                --x-2: 24%;
                --s-start-2: 18.50397409325151%;
                --s-end-2: 49.166438627289%;
                --s-start-3: 8.392121895570533%;
                --s-end-3: 48.52567687605722%;
                --y-3: 46%;
                --c-3: hsla(279.7894736842105, 67%, 27%, 1);
                --x-3: 0%;
                --c-4: hsla(279.7894736842105, 67%, 27%, 1);
                --y-4: 52%;
                --x-4: 96%;
                --s-start-4: 8.392121895570533%;
                --s-end-4: 49.58090142552271%;
                --s-start-5: 18%;
                --s-end-5: 49.94408311488525%;
                --y-5: 48%;
                --c-5: hsla(217.68844221105527, 100%, 39%, 1);
                --x-5: 57%;
                --c-6: hsla(217.59493670886076, 100%, 30%, 0);
                --s-start-6: 11.133917156423086%;
                --s-end-6: 41.601566034303026%;
                --y-6: 61%;
                --x-6: 88%;
                --x-7: 11%;
                --y-7: 26%;
                --s-start-7: 9.713086970899472%;
                --s-end-7: 40.915935065940864%;
                --c-7: hsla(217.59336099585062, 100%, 47%, 1);
            }

            50% {
                --x-0: 5%;
                --s-start-0: 9;
                --s-end-0: 61.21878342221337%;
                --c-0: hsla(217.59493670886076, 100%, 30%, 1);
                --y-0: 57%;
                --y-1: 15%;
                --c-1: hsla(0, 0%, 0%, 1);
                --s-start-1: 9;
                --s-end-1: 42.724849131360514%;
                --x-1: 51%;
                --c-2: hsla(0, 0%, 0%, 1);
                --y-2: 96%;
                --x-2: 48%;
                --s-start-2: 9;
                --s-end-2: 42.724849131360514%;
                --s-start-3: 9%;
                --s-end-3: 40.539112902234415%;
                --y-3: 38%;
                --c-3: hsla(279.7894736842105, 67%, 27%, 1);
                --x-3: 61%;
                --c-4: hsla(279.7894736842105, 67%, 27%, 1);
                --y-4: 71%;
                --x-4: 46%;
                --s-start-4: 9%;
                --s-end-4: 49.05074977970796%;
                --s-start-5: 9;
                --s-end-5: 41.70549052437993%;
                --y-5: 42%;
                --c-5: hsla(217.68844221105527, 100%, 39%, 1);
                --x-5: 14%;
                --c-6: hsla(217.59493670886076, 100%, 30%, 0);
                --s-start-6: 11.232588486654471%;
                --s-end-6: 45.2963125079906%;
                --y-6: 102%;
                --x-6: 42%;
                --x-7: 48%;
                --y-7: 60%;
                --s-start-7: 9;
                --s-end-7: 26.022325116539797%;
                --c-7: hsla(217.61194029850745, 100%, 39%, 1);
            }

            100% {
                --y-1: 4%;
                --c-1: hsla(0, 85%, 49%, 1);
                --s-start-1: 9;
                --s-end-1: 22.729655783472005%;
                --x-1: 10%;
                --c-2: hsla(0, 0%, 0%, 1);
                --y-2: 78%;
                --x-2: 82%;
                --s-start-2: 11.236704874019%;
                --s-end-2: 32.969946745736706%;
                --s-start-3: 10.200720718860145%;
                --s-end-3: 50.46456494288142%;
                --y-3: 57%;
                --c-3: hsla(279.7894736842105, 67%, 27%, 1);
                --x-3: 95%;
                --c-4: hsla(279.7894736842105, 67%, 27%, 1);
                --y-4: 42%;
                --x-4: 5%;
                --s-start-4: 10.200720718860145%;
                --s-end-4: 50.46456494288142%;
                --x-7: 81%;
                --y-7: 53%;
                --s-start-7: 6.294132688184733%;
                --s-end-7: 32.60155297274837%;
                --c-7: hsla(217.5, 100%, 31%, 1);
            }
        }

        @property --x-0 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 88%
        }

        @property --s-start-0 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 8%
        }

        @property --s-end-0 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 47.27292564072304%
        }

        @property --c-0 {
            syntax: '<color>';
            inherits: false;
            initial-value: hsla(217.59493670886076, 100%, 30%, 1)
        }

        @property --y-0 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 57%
        }

        @property --y-1 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 19%
        }

        @property --c-1 {
            syntax: '<color>';
            inherits: false;
            initial-value: hsla(0, 0%, 0%, 1)
        }

        @property --s-start-1 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 1%
        }

        @property --s-end-1 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 49.166438627289%
        }

        @property --x-1 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 94%
        }

        @property --c-2 {
            syntax: '<color>';
            inherits: false;
            initial-value: hsla(0, 0%, 0%, 1)
        }

        @property --y-2 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 77%
        }

        @property --x-2 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 24%
        }

        @property --s-start-2 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 18.50397409325151%
        }

        @property --s-end-2 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 49.166438627289%
        }

        @property --s-start-3 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 8.392121895570533%
        }

        @property --s-end-3 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 48.52567687605722%
        }

        @property --y-3 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 46%
        }

        @property --c-3 {
            syntax: '<color>';
            inherits: false;
            initial-value: hsla(279.7894736842105, 67%, 27%, 1)
        }

        @property --x-3 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 0%
        }

        @property --c-4 {
            syntax: '<color>';
            inherits: false;
            initial-value: hsla(279.7894736842105, 67%, 27%, 1)
        }

        @property --y-4 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 52%
        }

        @property --x-4 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 96%
        }

        @property --s-start-4 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 8.392121895570533%
        }

        @property --s-end-4 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 49.58090142552271%
        }

        @property --s-start-5 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 18%
        }

        @property --s-end-5 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 49.94408311488525%
        }

        @property --y-5 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 48%
        }

        @property --c-5 {
            syntax: '<color>';
            inherits: false;
            initial-value: hsla(217.68844221105527, 100%, 39%, 1)
        }

        @property --x-5 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 57%
        }

        @property --c-6 {
            syntax: '<color>';
            inherits: false;
            initial-value: hsla(217.59493670886076, 100%, 30%, 0)
        }

        @property --s-start-6 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 11.133917156423086%
        }

        @property --s-end-6 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 41.601566034303026%
        }

        @property --y-6 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 61%
        }

        @property --x-6 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 88%
        }

        @property --x-7 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 11%
        }

        @property --y-7 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 26%
        }

        @property --s-start-7 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 9.713086970899472%
        }

        @property --s-end-7 {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 40.915935065940864%
        }

        @property --c-7 {
            syntax: '<color>';
            inherits: false;
            initial-value: hsla(217.59336099585062, 100%, 47%, 1)
        }

        .wa {
            --x-0: 88%;
            --c-0: hsla(217.59493670886076, 100%, 30%, 1);
            --y-0: 57%;
            --y-1: 19%;
            --c-1: hsla(0, 0%, 0%, 1);
            --x-1: 94%;
            --c-2: hsla(0, 0%, 0%, 1);
            --y-2: 77%;
            --x-2: 24%;
            --y-3: 46%;
            --c-3: hsla(279.7894736842105, 67%, 27%, 1);
            --x-3: 0%;
            --c-4: hsla(279.7894736842105, 67%, 27%, 1);
            --y-4: 52%;
            --x-4: 96%;
            --y-5: 48%;
            --c-5: hsla(217.68844221105527, 100%, 39%, 1);
            --x-5: 57%;
            --c-6: hsla(217.59493670886076, 100%, 30%, 0);
            --y-6: 61%;
            --x-6: 88%;
            --x-7: 11%;
            --y-7: 26%;
            --c-7: hsla(217.59336099585062, 100%, 47%, 1);
            ;
            background-color: hsla(262, 82%, 3%, 1);
            background-image: radial-gradient(circle at var(--x-0) var(--y-0), var(--c-0) var(--s-start-0), transparent var(--s-end-0)), radial-gradient(circle at var(--x-1) var(--y-1), var(--c-1) var(--s-start-1), transparent var(--s-end-1)), radial-gradient(circle at var(--x-2) var(--y-2), var(--c-2) var(--s-start-2), transparent var(--s-end-2)), radial-gradient(circle at var(--x-3) var(--y-3), var(--c-3) var(--s-start-3), transparent var(--s-end-3)), radial-gradient(circle at var(--x-4) var(--y-4), var(--c-4) var(--s-start-4), transparent var(--s-end-4)), radial-gradient(circle at var(--x-5) var(--y-5), var(--c-5) var(--s-start-5), transparent var(--s-end-5)), radial-gradient(circle at var(--x-6) var(--y-6), var(--c-6) var(--s-start-6), transparent var(--s-end-6)), radial-gradient(circle at var(--x-7) var(--y-7), var(--c-7) var(--s-start-7), transparent var(--s-end-7));
            animation: hero-gradient-animation 10s linear infinite alternate;
            background-blend-mode: normal, normal, normal, normal, normal, normal, normal, normal;
        }
    </style>

</head>

<body class="wa">
    <div class="card max-w-6xl mx-auto px-6 py-10 text-white">

        <div class="text-center mb-6">
            <img src="{{ asset('assets/images/auth/logogkm.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
            <h1 class="text-2xl font-bold">Política de Privacidad</h1>
            <p class="text-sm text-gray-500">Última actualización: 28 de Marzo de 2025</p>
        </div>

        <h2 class="font-semibold mt-6 mb-2">1. Introducción</h2>
        <p class="mb-4">La presente Política de Privacidad describe cómo recopilamos, utilizamos y protegemos los
            datos personales de los usuarios que utilizan nuestra aplicación corporativa denominada “CSTI” (en adelante,
            "la Aplicación"), diseñada para gestionar el control de acceso y la ubicación de empleados que laboran en la
            empresa GKM Technology (en adelante, “la empresa”), así como la gestión de órdenes de servicio, mediante la
            asignación de tickets, de los cuales el colaborador deberá de realizar los pasos de servicios establecidos
            por su empleador, información que se envía a una base de datos centralizada y luego es proporcionada a la
            empresa empleadora del usuario.</p>
        <p class="mb-4">Al utilizar la Aplicación, el usuario acepta los términos de esta Política de Privacidad.</p>

        <h2 class="font-semibold mt-6 mb-2">2. Información Recopilada</h2>
        <ul class="list-disc ml-6 mb-4 space-y-2">
            <li>Acceso a la Cámara del Dispositivo: Para permitir cargar a los informes, observaciones de ausencias las
                evidencias fotográficas relevantes al servicio, estas fotos no se almacenan en el dispositivo y son de
                uso exclusivo de la aplicación.</li>
            <li>Acceso a la galería del dispositivo: Para permitir cargar a los informes, observaciones de ausencias las
                evidencias fotográficas relevantes al servicio, de fotos ya existentes en la galería y no es necesario
                recapturar la imagen.</li>
            <li>Datos de Hora y Ubicación: Se recopilan automáticamente la hora y la ubicación geográfica del
                dispositivo (mediante GPS u otros métodos de geolocalización del dispositivo móvil), la cual es enviada
                a la base de datos del sistema web (beyritech.com) que gestiona su empleador, donde este podrá ver los
                registros de ingreso, así como el flujo de avance al estar en ruta a un servicio asignado. Se utiliza la
                ubicación en segundo plano ya que el empleador en el sistema web (beyritech.com) tendrá acceso a un mapa
                con las ubicaciones en tiempo real, que permiten al empleador ver la ubicación del colaborador en
                relación con la dirección del servicio a realizar.</li>
        </ul>

        <h2 class="font-semibold mt-6 mb-2">3. Uso de la Información</h2>
        <ul class="list-disc ml-6 mb-4 space-y-2">
            <li>Registro y Control de Acceso: La hora y ubicación del usuario al momento de marcar un evento (ingreso,
                salida, cambio de estado de ruta a servicio) se almacenan en una base de datos centralizada para llevar
                un registro y control específicos para la empresa.</li>
            <li>Transmisión al Empleador: La información recopilada (hora y ubicación) se envía a la empresa, quien hace
                uso de esta para su monitoreo y gestiones administrativas a fines.</li>
        </ul>

        <h2 class="font-semibold mt-6 mb-2">4. Divulgación de la Información</h2>
        <ul class="list-disc ml-6 mb-4 space-y-2">
            <li>Colaborador: El colaborador podrá ver el historial de sus eventos dentro del aplicativo relacionados con
                los recopilados. Los mismos no pueden ser descargados desde el aplicativo, y solo se tiene acceso
                visual, a menos que se solicite lo contrario.</li>
            <li>La Empresa: La empresa recibirá los datos de acceso de cada empleado con fines de control y
                verificación.</li>
        </ul>

        <h2 class="font-semibold mt-6 mb-2">5. Seguridad de la Información</h2>
        <p class="mb-4">Implementamos medidas de seguridad técnicas y organizativas para proteger la información
            recopilada contra accesos no autorizados, alteración, divulgación o destrucción. Dichas medidas incluyen
            encriptación de datos y autenticación de usuarios. Sin embargo, ningún método de transmisión de datos a
            través de internet o de almacenamiento electrónico es completamente seguro, por lo que no podemos garantizar
            una seguridad absoluta.</p>

        <h2 class="font-semibold mt-6 mb-2">6. Almacenamiento de Datos</h2>
        <p class="mb-4">Los datos de hora y ubicación se retendrán solo por el tiempo necesario para cumplir con los
            propósitos establecidos en esta Política de Privacidad, o de acuerdo con lo que exija la ley o las políticas
            de retención de la empresa.</p>

        <h2 class="font-semibold mt-6 mb-2">7. Derechos del Usuario</h2>
        <ul class="list-disc ml-6 mb-4 space-y-2">
            <li>Acceder a sus datos personales: Los usuarios pueden solicitar una copia de los datos personales
                recopilados a través de la Aplicación.</li>
            <li>Rectificación y Cancelación: Solicitar la corrección de datos incorrectos o la eliminación de los datos,
                siempre y cuando no se requiera su retención por ley o para fines legítimos de la empresa.</li>
            <li>Limitación de Tratamiento: Solicitar que se limite el uso de sus datos personales en determinados casos,
                previa aceptación del empleador y notificación mediante un representante oficial de la empresa
                contratante.</li>
        </ul>
        <p class="mb-4">Para ejercer estos derechos, el usuario debe contactar al departamento de soporte de la
            empresa en <a href="mailto:contacto@gkmtechnology.com.pe"
                class="text-red-600 underline">contacto@gkmtechnology.com.pe</a>.</p>

        <h2 class="font-semibold mt-6 mb-2">8. Cambios en la Política de Privacidad</h2>
        <p class="mb-4">Nos reservamos el derecho de actualizar esta Política de Privacidad en cualquier momento.
            Notificaremos a los usuarios sobre cualquier cambio importante a través de la Aplicación o por otros medios
            antes de que entren en vigor. La fecha de la última actualización se indica en la parte superior de este
            documento.</p>

        <h2 class="font-semibold mt-6 mb-2">9. Contacto</h2>
        <p>Para cualquier pregunta o solicitud relacionada con esta Política de Privacidad, el usuario puede contactarse
            con nosotros vía correo a través de <a href="mailto:contacto@gkmtechnology.com.pe"
                class="text-red-600 underline">contacto@gkmtechnology.com.pe</a> o vía llamada a nuestro número de
            contacto <strong>0800 80142</strong>.</p>
    </div>
</body>

</html>
