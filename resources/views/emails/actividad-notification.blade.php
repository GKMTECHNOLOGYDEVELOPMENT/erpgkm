<!DOCTYPE html>
<html>
<head>
    <title>Notificación de Actividad</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background-color: #3b82f6;
            color: white;
            padding: 25px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-body {
            padding: 25px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #444;
        }
        .activity-details {
            background-color: #f8fafc;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        .detail-item {
            margin-bottom: 10px;
            display: flex;
        }
        .detail-label {
            font-weight: 600;
            min-width: 100px;
            color: #64748b;
        }
        .detail-value {
            flex: 1;
        }
        .event-link {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }
        .event-link:hover {
            text-decoration: underline;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .status-created {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-updated {
            background-color: #bfdbfe;
            color: #1e40af;
        }
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <!-- Reemplaza con tu logo -->
           <img src="https://erp.beyritech.com/assets/images/auth/logogkm.png" alt="Logo" class="logo">
            <h1>
                @if($tipo == 'creacion')
                    Nueva Actividad Programada
                @elseif($tipo == 'actualizacion')
                    Actividad Actualizada
                @else
                    Actividad Cancelada
                @endif
            </h1>
        </div>
        
        <div class="email-body">
            <p class="greeting">Hola {{ $usuario ? $usuario->Nombre : 'Usuario' }},</p>
            
            <div class="status-badge 
                @if($tipo == 'creacion') status-created
                @elseif($tipo == 'actualizacion') status-updated
                @else status-cancelled @endif">
                @if($tipo == 'creacion') NUEVO
                @elseif($tipo == 'actualizacion') ACTUALIZADO
                @else CANCELADO @endif
            </div>
            
            @if($tipo != 'eliminacion')
                <div class="activity-details">
                    <div class="detail-item">
                        <span class="detail-label">Título:</span>
                        <span class="detail-value">{{ $actividad->titulo }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Fecha inicio:</span>
                        <span class="detail-value">{{ date('d/m/Y H:i', strtotime($actividad->fechainicio)) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Fecha fin:</span>
                        <span class="detail-value">{{ date('d/m/Y H:i', strtotime($actividad->fechafin)) }}</span>
                    </div>
                    @if($actividad->ubicacion)
                    <div class="detail-item">
                        <span class="detail-label">Ubicación:</span>
                        <span class="detail-value">{{ $actividad->ubicacion }}</span>
                    </div>
                    @endif
                    @if($actividad->descripcion)
                    <div class="detail-item">
                        <span class="detail-label">Descripción:</span>
                        <span class="detail-value">{{ $actividad->descripcion }}</span>
                    </div>
                    @endif
                    @if($actividad->enlaceevento)
                    <div class="detail-item">
                        <span class="detail-label">Enlace:</span>
                        <span class="detail-value">
                            <a href="{{ $actividad->enlaceevento }}" class="event-link">Acceder al evento</a>
                        </span>
                    </div>
                    @endif
                </div>
            @else
                <div class="activity-details">
                    <p>La siguiente actividad ha sido cancelada:</p>
                    <div class="detail-item">
                        <span class="detail-label">Título:</span>
                        <span class="detail-value">{{ $actividad->titulo }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Fecha original:</span>
                        <span class="detail-value">{{ date('d/m/Y H:i', strtotime($actividad->fechainicio)) }}</span>
                    </div>
                </div>
            @endif
            
            @if($tipo != 'eliminacion')
            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ $actividad->enlaceevento ?? '#' }}" 
                   style="background-color: #3b82f6; color: white; padding: 12px 24px; 
                          text-decoration: none; border-radius: 6px; font-weight: 500;
                          display: inline-block;">
                    Ver detalles del evento
                </a>
            </div>
            @endif
            
            <div class="footer">
                <p>Gracias por usar nuestra plataforma</p>
                <p><small>© {{ date('Y') }} Nombre de tu App. Todos los derechos reservados.</small></p>
                <p><small><a href="#" style="color: #64748b;">Configurar notificaciones</a></small></p>
            </div>
        </div>
    </div>
</body>
</html>