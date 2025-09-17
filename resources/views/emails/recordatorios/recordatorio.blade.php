@php
$r = $recordatorio;
$isCreated = $stage === 'created';
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isCreated ? 'Recordatorio Creado' : 'Recordatorio de Vacunación' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 20px;
        }
        .message {
            font-size: 18px;
            margin-bottom: 20px;
            color: #1e293b;
        }
        .details {
            background-color: #f1f5f9;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #06b6d4;
        }
        .details h3 {
            margin-top: 0;
            color: #06b6d4;
            font-size: 16px;
            font-weight: 600;
        }
        .details ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .details li {
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .details li:last-child {
            border-bottom: none;
        }
        .details strong {
            color: #475569;
            display: inline-block;
            width: 100px;
        }
        .cta-button {
            display: inline-block;
            background-color: #06b6d4;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
            transition: background-color 0.3s;
        }
        .cta-button:hover {
            background-color: #0891b2;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
        .emoji {
            font-size: 20px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
            }
            .header, .content {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $isCreated ? '✅ Recordatorio Creado' : '💉 Recordatorio de Vacunación' }}</h1>
            <p>{{ $isCreated ? 'Tu cita está programada' : '¡Es momento de cuidar tu salud!' }}</p>
        </div>

        <div class="content">
            <div class="message">
                @if($isCreated)
                    ¡Hola {{ optional($notifiable)->name ?? 'Paciente' }}! <span class="emoji">🎉</span><br>
                    Hemos creado exitosamente tu recordatorio de vacunación. Te enviaremos un aviso cuando llegue el momento.
                @else
                    ¡Hola {{ optional($notifiable)->name ?? 'Paciente' }}! <span class="emoji">🔔</span><br>
                    Es hora de tu {{ $r->vacuna ? 'vacuna de ' . $r->vacuna->nombre : 'cita programada' }}. ¡No lo olvides!
                @endif
            </div>

            <div class="details">
                <h3><span class="emoji">📋</span> Detalles del recordatorio</h3>
                <ul>
                    <li><strong>Título:</strong> {{ $r->titulo }}</li>
                    <li><strong>Tipo:</strong> {{ $r->tipo === 'vacuna_proxima' ? 'Vacuna Próxima' : ($r->tipo === 'cita_programada' ? 'Cita Programada' : ($r->tipo === 'refuerzo_pendiente' ? 'Refuerzo Pendiente' : 'Recordatorio Personal')) }}</li>
                    <li><strong>Fecha:</strong> {{ $r->fecha_recordatorio ? $r->fecha_recordatorio->format('d/m/Y') : 'N/A' }}</li>
                    @if($r->hora_recordatorio)
                        <li><strong>Hora:</strong> {{ $r->hora_recordatorio }}</li>
                    @endif
                    <li><strong>Paciente:</strong> {{ $r->dependiente ? $r->dependiente->nombre_completo : 'Tú mismo' }}</li>
                    @if($r->mensaje)
                        <li><strong>Notas:</strong> {{ $r->mensaje }}</li>
                    @endif
                </ul>
            </div>

            <p style="text-align: center; color: #ffffff; font-weight: 600;">
                <a href="{{ $url }}" class="cta-button">
                    {{ $isCreated ? 'VER RECORDATORIO' : 'MARCAR COMO COMPLETADO' }} <span class="emoji">👆</span>
                </a>
            </p>

            @if(!$isCreated)
                <p style="text-align: center; color: #06b6d4; font-weight: 600;">
                    ¡Tu salud es lo más importante! No pospongas tu vacunación. <span class="emoji">❤️</span>
                </p>
            @endif
        </div>

        <div class="footer">
            <p><strong>Inmuno Alerta</strong> - Cuidando tu salud</p>
            <p>Si ya completaste esta acción, marca el recordatorio en la app.</p>
            <p style="font-size: 12px; color: #94a3b8;">
                Este es un email automático. No respondas a este mensaje.
            </p>
        </div>
    </div>
</body>
</html>
