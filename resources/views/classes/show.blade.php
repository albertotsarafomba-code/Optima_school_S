@extends('layouts.gestion-ecole')

@section('title', 'Classe - ' . $classRoom->nom_classe)

@section('page-title', $classRoom->nom_classe)

@section('page-description', 'Détails et étudiants inscrits dans cette classe')

@section('content')

<div class="mx-auto max-w-7xl">

    {{-- RETOUR --}}
    <div class="mb-5">
        <a
            href="{{ route('class_rooms.index') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 transition hover:text-blue-700"
        >
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Retour aux classes
        </a>
    </div>


    {{-- INFORMATIONS CLASSE --}}
    <div class="mb-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

        {{-- HEADER --}}
        <div class="bg-blue-950 px-6 py-6 text-white">

            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                <div class="flex items-center gap-4">

                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-600">
                        <i data-lucide="graduation-cap" class="h-7 w-7"></i>
                    </div>

                    <div>

                        <h2 class="text-2xl font-bold">
                            {{ $classRoom->nom_classe }}
                        </h2>

                        <p class="mt-1 text-sm text-blue-100">
                            {{ $classRoom->establishment?->nom ?? 'Établissement non défini' }}
                        </p>

                    </div>

                </div>


                <a
                    href="{{ route('class_rooms.edit', $classRoom) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-blue-950 transition hover:bg-blue-50"
                >
                    <i data-lucide="pencil" class="h-4 w-4"></i>
                    Modifier
                </a>

            </div>

        </div>


        {{-- INFORMATIONS --}}
        <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 lg:grid-cols-4">

            {{-- NIVEAU --}}
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">

                <div class="flex items-center gap-2 text-gray-500">

                    <i data-lucide="layers" class="h-4 w-4"></i>

                    <span class="text-sm">
                        Niveau
                    </span>

                </div>

                <p class="mt-2 font-bold text-blue-950">
                    {{ $classRoom->niveau ?: '—' }}
                </p>

            </div>


            {{-- FILIERE --}}
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">

                <div class="flex items-center gap-2 text-gray-500">

                    <i data-lucide="book-open" class="h-4 w-4"></i>

                    <span class="text-sm">
                        Filière
                    </span>

                </div>

                <p class="mt-2 font-bold text-blue-950">
                    {{ $classRoom->filiere ?: '—' }}
                </p>

            </div>


            {{-- ANNEE SCOLAIRE --}}
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">

                <div class="flex items-center gap-2 text-gray-500">

                    <i data-lucide="calendar-days" class="h-4 w-4"></i>

                    <span class="text-sm">
                        Année scolaire
                    </span>

                </div>

                <p class="mt-2 font-bold text-blue-950">
                    {{ $classRoom->schoolYear?->name ?? $classRoom->annee_scolaire ?? '—' }}
                </p>

            </div>


            {{-- SALLE --}}
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">

                <div class="flex items-center gap-2 text-gray-500">

                    <i data-lucide="door-open" class="h-4 w-4"></i>

                    <span class="text-sm">
                        Salle
                    </span>

                </div>

                <p class="mt-2 font-bold text-blue-950">
                    {{ $classRoom->room?->nom ?? $classRoom->salle ?? '—' }}
                </p>

            </div>

        </div>

    </div>


    {{-- STATISTIQUES --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">

        {{-- ETUDIANTS --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Étudiants actifs
                    </p>

                    <p class="mt-2 text-3xl font-bold text-blue-950">
                        {{ $effectifActuel }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-700">

                    <i data-lucide="users" class="h-6 w-6"></i>

                </div>

            </div>

        </div>


        {{-- CAPACITE --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Capacité
                    </p>

                    <p class="mt-2 text-3xl font-bold text-blue-950">
                        {{ $classRoom->capacite ?? $classRoom->effectif_maximum ?? 0 }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

                    <i data-lucide="users-round" class="h-6 w-6"></i>

                </div>

            </div>

        </div>


        {{-- MATIERES --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Matières
                    </p>

                    <p class="mt-2 text-3xl font-bold text-blue-950">
                        {{ $classRoom->subjects->count() }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-purple-700">

                    <i data-lucide="book-open" class="h-6 w-6"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- ENSEIGNANT --}}
    <div class="mb-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

        <div class="border-b border-gray-100 px-6 py-5">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

                    <i data-lucide="user-round" class="h-5 w-5"></i>

                </div>

                <div>

                    <h3 class="font-bold text-blue-950">
                        Enseignant responsable
                    </h3>

                    <p class="text-sm text-gray-500">
                        Enseignant affecté à cette classe
                    </p>

                </div>

            </div>

        </div>


        <div class="px-6 py-5">

            @if($classRoom->teacher)

                <div class="flex items-center gap-4">

                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">

                        @php
                            $teacherFirstLetter =
                                strtoupper(
                                    substr(
                                        $classRoom->teacher->nom
                                            ?? $classRoom->teacher->name
                                            ?? 'E',
                                        0,
                                        1
                                    )
                                );
                        @endphp

                        {{ $teacherFirstLetter }}

                    </div>


                    <div>

                        <p class="font-semibold text-gray-900">

                            {{ $classRoom->teacher->nom ?? $classRoom->teacher->name ?? '—' }}

                            @if(!empty($classRoom->teacher->prenom))
                                {{ $classRoom->teacher->prenom }}
                            @endif

                        </p>


                        @if(!empty($classRoom->teacher->email))

                            <p class="text-sm text-gray-500">
                                {{ $classRoom->teacher->email }}
                            </p>

                        @endif

                    </div>

                </div>

            @else

                <div class="flex items-center gap-3 text-gray-500">

                    <i data-lucide="user-x" class="h-5 w-5"></i>

                    <span class="text-sm">
                        Aucun enseignant n'est encore affecté à cette classe.
                    </span>

                </div>

            @endif

        </div>

    </div>


    {{-- ETUDIANTS --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

        {{-- HEADER --}}
        <div class="border-b border-gray-100 px-6 py-5">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h3 class="font-bold text-blue-950">
                        Étudiants inscrits
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Liste des étudiants actifs dans cette classe
                    </p>

                </div>


                <a
                    href="{{ route('inscriptions.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700"
                >

                    <i data-lucide="user-plus" class="h-4 w-4"></i>

                    Inscrire un étudiant

                </a>

            </div>

        </div>


        {{-- LISTE DES ETUDIANTS --}}
        @if($students->isNotEmpty())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-100">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Matricule
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Nom
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Prénom
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Sexe
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Statut
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @foreach($students as $student)

                            @if($student)

                                <tr class="transition hover:bg-blue-50/40">

                                    {{-- MATRICULE --}}
                                    <td class="px-6 py-4">

                                        <span class="font-semibold text-blue-700">
                                            {{ $student->matricule ?? '—' }}
                                        </span>

                                    </td>


                                    {{-- NOM --}}
                                    <td class="px-6 py-4 font-medium text-gray-800">
                                        {{ $student->nom ?? '—' }}
                                    </td>


                                    {{-- PRENOM --}}
                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $student->prenom ?? '—' }}
                                    </td>


                                    {{-- SEXE --}}
                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $student->sexe ?? '—' }}
                                    </td>


                                    {{-- STATUT --}}
                                    <td class="px-6 py-4">

                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-700">

                                            <i data-lucide="circle-check" class="h-3.5 w-3.5"></i>

                                            Actif

                                        </span>

                                    </td>

                                </tr>

                            @endif

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            {{-- AUCUN ETUDIANT --}}
            <div class="px-6 py-14 text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 text-blue-600">

                    <i data-lucide="users" class="h-7 w-7"></i>

                </div>


                <h3 class="mt-4 font-bold text-blue-950">
                    Aucun étudiant inscrit
                </h3>


                <p class="mt-2 text-sm text-gray-500">
                    Cette classe ne contient actuellement aucun étudiant actif.
                </p>


                <a
                    href="{{ route('inscriptions.create') }}"
                    class="mt-5 inline-flex items-center gap-2 rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-green-700"
                >

                    <i data-lucide="user-plus" class="h-4 w-4"></i>

                    Inscrire un étudiant

                </a>

            </div>

        @endif

    </div>

</div>


{{-- LUCIDE ICONS --}}
<script src="https://unpkg.com/lucide@latest"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

    });
</script>

@endsection