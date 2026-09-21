<!DOCTYPE html>

<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Classes - Gestion d'école</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="min-h-screen bg-gray-100">

<!-- ========================================================= -->
<!-- SIDEBAR -->
<!-- ========================================================= -->

<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-blue-900 text-white shadow-xl">

    <!-- Logo -->
    <div class="flex h-20 items-center border-b border-blue-800 px-5">

        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-500 text-lg font-bold">
            GE
        </div>

        <div class="ml-3">
            <h1 class="text-lg font-bold">
                Gestion d'école
            </h1>

            <p class="text-xs text-blue-200">
                Administration
            </p>
        </div>

    </div>


    <!-- Navigation -->
    <nav class="mt-6 px-3">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="mb-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium text-blue-100 transition hover:bg-blue-800">

            <span class="mr-3 text-lg">⌂</span>

            Dashboard

        </a>


        <!-- Étudiants -->
        <a href="{{ route('students.index') }}"
           class="mb-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium text-blue-100 transition hover:bg-blue-800">

            <span class="mr-3 text-lg"></span>

            Étudiants

        </a>


        <!-- Enseignants -->
        <a href="{{ route('teachers.index') }}"
           class="mb-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium text-blue-100 transition hover:bg-blue-800">

            <span class="mr-3 text-lg"></span>

            Enseignants

        </a>


        <!-- Classes ACTIVE -->
        <a href="{{ route('class_rooms.index') }}"
           class="mb-1 flex items-center rounded-lg bg-green-500 px-4 py-3 text-sm font-bold text-white shadow-md">

            <span class="mr-3 text-lg"></span>

            Classes

        </a>


        <!-- Matières -->
        <a href="{{ route('subjects.index') }}"
           class="mb-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium text-blue-100 transition hover:bg-blue-800">

            <span class="mr-3 text-lg"></span>

            Matières

        </a>


        <!-- Inscriptions -->
        <a href="{{ route('inscriptions.index') }}"
           class="mb-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium text-blue-100 transition hover:bg-blue-800">

            <span class="mr-3 text-lg"></span>

            Inscriptions

        </a>


        <!-- Notes -->
        <a href="{{ route('notes.index') }}"
           class="mb-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium text-blue-100 transition hover:bg-blue-800">

            <span class="mr-3 text-lg"></span>

            Notes

        </a>


        <!-- Paiements -->
        <a href="{{ route('paiements.index') }}"
           class="mb-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium text-blue-100 transition hover:bg-blue-800">

            <span class="mr-3 text-lg"></span>

            Paiements

        </a>


        <!-- Années scolaires -->
        <a href="{{ route('school_years.index') }}"
           class="mb-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium text-blue-100 transition hover:bg-blue-800">

            <span class="mr-3 text-lg"></span>

            Années scolaires

        </a>

    </nav>


    <!-- Établissement -->
    <div class="absolute bottom-0 left-0 w-full border-t border-blue-800 p-4">

        <div class="rounded-lg bg-blue-800 p-3">

            <p class="text-xs text-blue-200">
                Établissement sélectionné
            </p>

            <p class="mt-1 font-semibold text-white">
                #{{ session('establishment_id') }}
            </p>

        </div>

    </div>

</aside>


<!-- ========================================================= -->
<!-- CONTENU PRINCIPAL -->
<!-- ========================================================= -->

<main class="ml-64 min-h-screen">


    <!-- HEADER -->
    <header class="flex min-h-20 items-center justify-between border-b border-gray-200 bg-white px-8 shadow-sm">

        <div>

            <h1 class="text-2xl font-bold text-blue-900">
                Gestion des classes
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Liste des classes de l'établissement sélectionné
            </p>

        </div>


        <a href="{{ route('class_rooms.create') }}"
           class="inline-flex items-center rounded-lg bg-green-500 px-5 py-3 text-sm font-bold text-white shadow-md transition hover:bg-green-600">

            <span class="mr-2 text-xl">
                +
            </span>

            Ajouter une classe

        </a>

    </header>


    <!-- CONTENU -->
    <div class="p-8">


        <!-- ================================================= -->
        <!-- MESSAGES -->
        <!-- ================================================= -->

        @if(session('success'))

            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-700">

                ✓ {{ session('success') }}

            </div>

        @endif


        @if(session('error'))

            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">

                ⚠ {{ session('error') }}

            </div>

        @endif


        <!-- ================================================= -->
        <!-- CARTES STATISTIQUES -->
        <!-- ================================================= -->

        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">


            <!-- Classes -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Classes
                        </p>

                        <p class="mt-2 text-3xl font-bold text-blue-900">
                            {{ $classes->count() }}
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            Classes enregistrées
                        </p>

                    </div>

                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-100 text-2xl">
                        
                    </div>

                </div>

            </div>


            <!-- Capacité -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Capacité
                        </p>

                        <p class="mt-2 text-3xl font-bold text-green-600">
                            {{ $classes->sum('capacite') }}
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            Places disponibles
                        </p>

                    </div>

                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-green-100 text-2xl">
                        
                    </div>

                </div>

            </div>


            <!-- Niveaux -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Niveaux
                        </p>

                        <p class="mt-2 text-3xl font-bold text-blue-900">
                            {{ $classes->pluck('niveau')->filter()->unique()->count() }}
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            Niveaux différents
                        </p>

                    </div>

                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-100 text-2xl">
                        
                    </div>

                </div>

            </div>


            <!-- Filières -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Filières
                        </p>

                        <p class="mt-2 text-3xl font-bold text-green-600">
                            {{ $classes->pluck('filiere')->filter()->unique()->count() }}
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            Filières différentes
                        </p>

                    </div>

                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-green-100 text-2xl">
                        
                    </div>

                </div>

            </div>

        </div>


        <!-- ================================================= -->
        <!-- TABLEAU DES CLASSES -->
        <!-- ================================================= -->

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">


            <!-- Titre -->
            <div class="flex flex-col gap-3 border-b border-gray-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h2 class="text-lg font-bold text-blue-900">
                        Classes enregistrées
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Toutes les classes de votre établissement
                    </p>

                </div>


                <div class="rounded-full bg-green-100 px-4 py-2 text-sm font-bold text-green-700">

                    {{ $classes->count() }}
                    classe(s)

                </div>

            </div>


            @if($classes->count() > 0)


                <!-- TABLE -->
                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">


                        <thead class="bg-blue-50">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-blue-900">
                                    Classe
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-blue-900">
                                    Niveau
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-blue-900">
                                    Filière
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-blue-900">
                                    Année scolaire
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-blue-900">
                                    Salle
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-blue-900">
                                    Capacité
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-blue-900">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-200 bg-white">


                            @foreach($classes as $class)

                                <tr class="transition hover:bg-blue-50">


                                    <!-- Classe -->
                                    <td class="whitespace-nowrap px-6 py-5">

                                        <div class="flex items-center">

                                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 font-bold text-blue-700">

                                                C

                                            </div>

                                            <div class="ml-3">

                                                <p class="font-bold text-gray-800">
                                                    {{ $class->nom_classe }}
                                                </p>

                                                <p class="text-xs text-gray-400">
                                                    ID #{{ $class->id }}
                                                </p>

                                            </div>

                                        </div>

                                    </td>


                                    <!-- Niveau -->
                                    <td class="whitespace-nowrap px-6 py-5">

                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700">

                                            {{ $class->niveau }}

                                        </span>

                                    </td>


                                    <!-- Filière -->
                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-gray-600">

                                        {{ $class->filiere ?: '—' }}

                                    </td>


                                    <!-- Année -->
                                    <td class="whitespace-nowrap px-6 py-5">

                                        @if($class->school_year_id)

                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">

                                                {{ $class->schoolYear?->name ?? $class->annee_scolaire }}

                                            </span>

                                        @else

                                            <span class="text-sm text-gray-600">

                                                {{ $class->annee_scolaire ?: '—' }}

                                            </span>

                                        @endif

                                    </td>


                                    <!-- Salle -->
                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-gray-600">

                                        {{ $class->salle ?: '—' }}

                                    </td>


                                    <!-- Capacité -->
                                    <td class="whitespace-nowrap px-6 py-5">

                                        @if($class->capacite)

                                            <span class="font-bold text-gray-700">
                                                {{ $class->capacite }}
                                            </span>

                                            <span class="text-xs text-gray-400">
                                                places
                                            </span>

                                        @else

                                            <span class="text-gray-400">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <!-- Actions -->
                                    <td class="whitespace-nowrap px-6 py-5">

                                        <div class="flex justify-end gap-2">


                                            <!-- Voir -->
                                            <a href="{{ route('class_rooms.show', $class) }}"
                                               class="rounded-lg bg-blue-100 px-3 py-2 text-xs font-bold text-blue-700 transition hover:bg-blue-200">

                                                Voir

                                            </a>


                                            <!-- Matières -->
                                            <a href="{{ route('class_rooms.subjects', $class) }}"
                                               class="rounded-lg bg-green-100 px-3 py-2 text-xs font-bold text-green-700 transition hover:bg-green-200">

                                                Matières

                                            </a>


                                            <!-- Modifier -->
                                            <a href="{{ route('class_rooms.edit', $class) }}"
                                               class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-bold text-white transition hover:bg-blue-700">

                                                Modifier

                                            </a>


                                            <!-- Supprimer -->
                                            <form action="{{ route('class_rooms.destroy', $class) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cette classe ?');">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit"
                                                        class="rounded-lg bg-red-100 px-3 py-2 text-xs font-bold text-red-700 transition hover:bg-red-200">

                                                    Supprimer

                                                </button>

                                            </form>


                                        </div>

                                    </td>

                                </tr>

                            @endforeach


                        </tbody>

                    </table>

                </div>


            @else


                <!-- AUCUNE CLASSE -->
                <div class="px-6 py-20 text-center">

                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-blue-100 text-3xl">

                        

                    </div>


                    <h3 class="mt-5 text-xl font-bold text-blue-900">

                        Aucune classe enregistrée

                    </h3>


                    <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">

                        Commencez par ajouter une nouvelle classe
                        à votre établissement.

                    </p>


                    <a href="{{ route('class_rooms.create') }}"
                       class="mt-6 inline-flex items-center rounded-lg bg-green-500 px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-green-600">

                        <span class="mr-2 text-lg">
                            +
                        </span>

                        Ajouter une classe

                    </a>

                </div>

            @endif


        </div>

    </div>

</main>

</body>

</html>
