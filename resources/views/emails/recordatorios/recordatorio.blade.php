@php
$r = $recordatorio;
@endphp

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Recordatorio</title>
</head>
<body>
    <h2>{{ $stage === 'created' ? 'Recordatorio creado' : 'Recordatorio' }}</h2>

    <p>Hola {{ optional($notifiable)->nombres ?? 'usuario' }},</p>

    <p>{{ $r->mensaje ?? 'Tienes un recordatorio programado.' }}</p>

    <ul>
        <li><strong>Título:</strong> {{ $r->titulo }}</li>
        <li><strong>Tipo:</strong> {{ $r->tipo }}</li>
        <li><strong>Fecha:</strong> {{ $r->fecha_recordatorio ? $r->fecha_recordatorio->format('Y-m-d') : 'N/A' }}</li>
        @if($r->hora_recordatorio)
            <li><strong>Hora:</strong> {{ $r->hora_recordatorio }}</li>
        @endif
        <li><strong>Paciente:</strong> {{ $r->dependiente ? $r->dependiente->nombre_completo : 'Usted' }}</li>
    </ul>

    <p>
        <a href="{{ $url }}">Ver recordatorio</a>
    </p>

    <p>Si ya realizaste esta acción, marca el recordatorio como completado en la app.</p>

    <hr>
    <small>Inmuno</small>
</body>
</html>
