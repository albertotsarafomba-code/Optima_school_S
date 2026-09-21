<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Gestion d\'école' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100">

    <div class="flex min-h-screen flex-col items-center justify-center">

        <!-- Logo / Lien vers le dashboard -->
        <a href="{{ route('dashboard') }}"
           class="mb-6 flex flex-col items-center gap-2 font-medium"
           wire:navigate>

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-xl font-bold text-white">
                GE
            </div>

            <span class="text-xl font-semibold text-gray-800">
                Gestion d'école
            </span>
        </a>

        <!-- Contenu de la page -->
        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-md">
            {{ $slot }}
        </div>

    </div>

</body>
</html> 
