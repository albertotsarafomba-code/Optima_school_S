<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Gestion d\'école' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100">

    @php
        $isForgotPasswordPage = request()->routeIs('password.request');
    @endphp

    @if ($isForgotPasswordPage)
        <!-- Fond image uniquement pour la page Mot de passe oublié -->
        <div
            class="fixed inset-0 z-0 overflow-hidden"
            aria-hidden="true"
        >
            <div
                class="absolute -inset-3 bg-cover bg-center bg-no-repeat"
                style="background-image: url('{{ asset('images/motdepa.jpg') }}'); filter: blur(2px);"
            ></div>

            <div class="absolute inset-0 bg-blue-950/55"></div>
            <div class="absolute inset-0 bg-green-950/10"></div>
        </div>
    @endif

    <div class="relative z-10 flex min-h-screen flex-col items-center justify-center px-4 py-8">

        <!-- Contenu de la page -->
        <div
            class="w-full max-w-md rounded-2xl p-6 shadow-xl sm:p-8
            {{ $isForgotPasswordPage
                ? 'border border-white/70 bg-white/[0.94] backdrop-blur-md shadow-2xl ring-1 ring-blue-900/10'
                : 'bg-white' }}"
        >
            {{ $slot }}
        </div>

    </div>

</body>
</html>
