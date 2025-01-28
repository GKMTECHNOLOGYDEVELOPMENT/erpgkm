<x-layout.default>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Estilos de la tarjeta */
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 1200px;
        }

        .card-header {
            background-color: #f7f7f7;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            font-size: 18px;
            font-weight: bold;
        }

        .card-body {
            padding: 20px;
        }

        .tabs-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .tabs {
            display: flex;
            justify-content: space-evenly;
            border-bottom: 2px solid #ccc;
            padding: 10px 0;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            flex: 1;
            text-align: center;
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            border-radius: 5px 5px 0 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s;
        }

        .tab:hover {
            background-color: #e0e0e0;
        }

        .tab.active {
            background-color: #ffffff;
            border-color: #007bff;
            font-weight: bold;
        }

        .tab i {
            font-size: 18px;
        }

        .tab-content {
            display: none;
            padding: 20px;
            border-top: 1px solid #ddd;
            margin-top: 10px;
        }

        .tab-content.active {
            display: block;
        }

        .tab-content h3 {
            margin-bottom: 20px;
        }
    </style>

    <!-- Card Contenedor -->
    <div class="card">
        <div class="card-header">
            <h4>Editar Orden N° {{ $orden->idTickets }}</h4> <!-- Aquí se muestra el ID de la orden -->
        </div>
        <div class="card-body">
            <div class="tabs-container">
                <!-- Las pestañas -->
                <div class="tabs">
                    <div class="tab active" data-tab="1">
                        <i class="fas fa-info-circle"></i> Detalle TK
                    </div>
                    <div class="tab" data-tab="2">
                        <i class="fas fa-cogs"></i> Proceso de Atención
                    </div>
                    <div class="tab" data-tab="3">
                        <i class="fas fa-signature"></i> Firmas
                    </div>
                    <div class="tab" data-tab="4">
                        <i class="fas fa-file-alt"></i> Hoja Entrega
                    </div>
                    <div class="tab" data-tab="5">
                        <i class="fas fa-clipboard-list"></i> Informe
                    </div>
                </div>

                <!-- Contenido de las pestañas -->
                <div class="tab-content active" id="tab-content-1">
                    @include('tickets.ordenes-trabajo.tabs.detalle-tk', ['orden' => $orden]) <!-- Se pasa el objeto orden a la vista -->
                </div>
                <div class="tab-content" id="tab-content-2">
                    @include('tickets.ordenes-trabajo.tabs.proceso-atencion', ['orden' => $orden])
                </div>
                <div class="tab-content" id="tab-content-3">
                    @include('tickets.ordenes-trabajo.tabs.firmas', ['orden' => $orden])
                </div>
                <div class="tab-content" id="tab-content-4">
                    @include('tickets.ordenes-trabajo.tabs.hoja-entrega', ['orden' => $orden])
                </div>
                <div class="tab-content" id="tab-content-5">
                    @include('tickets.ordenes-trabajo.tabs.informe', ['orden' => $orden])
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Cambiar entre pestañas
            $('.tab').click(function() {
                var tabId = $(this).data('tab');

                // Cambiar las clases activas
                $('.tab').removeClass('active');
                $(this).addClass('active');

                $('.tab-content').removeClass('active');
                $('#tab-content-' + tabId).addClass('active');
            });
        });
    </script>

    <script src="{{ asset('assets/js/ordenes/ordenes.js') }}"></script>
    <script src="{{ asset('assets/js/ordenes/ordenesStore.js') }}"></script>
    <script src="{{ asset('assets/js/ordenes/ordenesValidaciones.js') }}"></script>
    <script src="/assets/js/simple-datatables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>
