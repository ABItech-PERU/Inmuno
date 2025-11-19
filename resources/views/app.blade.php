<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}" type="image/x-icon">

        <title inertia>{{ config('app.name', 'Inmuno Alerta') }}</title>
        <!-- Theme Color -->
        <meta name="theme-color" content="#009688">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Keywords -->
        <meta name="keywords"
        content="Inmuno Alerta, Sistema de vacunación infantil, Recordatorio de vacunas, Esquema de inmunización MINSA, Control de vacunas niños, Alertas de vacunación, Registro de vacunas, Vacunación Perú, Seguimiento de vacunas, Notificaciones de inmunización">


        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:site_name" content="{{ config('app.name', 'Inmuno Alerta') }}">
        <meta property="og:title" content="{{ config('app.name', 'Inmuno Alerta') }}">
        <meta property="og:description" content="Sistema de alertas y seguimiento de vacunación infantil.">
        <meta property="og:image" content="{{ asset('img/compartir.jpg') }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:type" content="website">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead

        <!-- reCAPTCHA Script -->
        <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
