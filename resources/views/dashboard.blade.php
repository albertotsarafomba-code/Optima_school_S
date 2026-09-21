@extends('layouts.gestion-ecole')

@section('title', 'Tableau de bord')

@section('content')

<div class="min-h-screen bg-gray-50">

    {{-- ============================================================
        EN-TÊTE DU DASHBOARD
    ============================================================ --}}
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-900 text-white shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 13h8V3H3v10zm10 8h8V3h-8v18zM3 21h8v-6H3v6z"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Tableau de bord
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Vue générale de votre établissement scolaire
                    </p>
                </div>
            </div>
        </div>

        {{-- Établissement sélectionné --}}
        <div class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm">

            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-700">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 9h.01M12 9h.01M15 9h.01"/>
                </svg>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Établissement
                </p>

                <p class="text-sm font-bold text-gray-900">
                    {{ $establishment->nom ?? 'Établissement' }}
                </p>

                <p class="text-xs text-green-700">
                    {{ $schoolTypeName ?? 'Type non défini' }}
                </p>
            </div>
        </div>

    </div>


    {{-- ============================================================
        ANNÉE SCOLAIRE
    ============================================================ --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 2v4M16 2v4M3 10h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                    </svg>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Année scolaire active
                    </p>

                    <p class="text-base font-bold text-gray-900">
                        @if($schoolYear)
                            {{ $schoolYear->nom
                                ?? $schoolYear->name
                                ?? $schoolYear->annee_scolaire
                                ?? 'Année scolaire' }}
                        @else
                            Aucune année scolaire sélectionnée
                        @endif
                    </p>
                </div>

            </div>

            <a href="{{ route('school_years.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M8 7V3m8 4V3M4 11h16M6 5h12a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                </svg>

                Gérer les années
            </a>

        </div>
    </div>


    {{-- ============================================================
        CARTES STATISTIQUES
    ============================================================ --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Étudiants --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Étudiants
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $stats['students'] ?? 0 }}
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                        Étudiants de l'établissement
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-800">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>

                </div>

            </div>

            <div class="mt-4">
                <a href="{{ route('students.index') }}"
                   class="text-sm font-semibold text-blue-800 hover:text-blue-600">
                    Voir les étudiants →
                </a>
            </div>

        </div>


        {{-- Enseignants --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Enseignants
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $stats['teachers'] ?? 0 }}
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                        Enseignants de l'établissement
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 14a5 5 0 100-10 5 5 0 000 10zM4 21a8 8 0 0116 0"/>
                    </svg>

                </div>

            </div>

            <div class="mt-4">
                <a href="{{ route('teachers.index') }}"
                   class="text-sm font-semibold text-green-700 hover:text-green-600">
                    Voir les enseignants →
                </a>
            </div>

        </div>


        {{-- Classes --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Classes
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $stats['classes'] ?? 0 }}
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                        Classes de l'année scolaire
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M4 19V5a2 2 0 012-2h12a2 2 0 012 2v14M4 19h16M8 7h8M8 11h8M8 15h5"/>
                    </svg>

                </div>

            </div>

            <div class="mt-4">
                <a href="{{ route('class_rooms.index') }}"
                   class="text-sm font-semibold text-indigo-700 hover:text-indigo-600">
                    Voir les classes →
                </a>
            </div>

        </div>


        {{-- Matières --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Matières
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $stats['subjects'] ?? 0 }}
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                        Matières disponibles
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 6.5a4.5 4.5 0 00-4.5-4.5H3v16h4.5A4.5 4.5 0 0112 22M12 6.5A4.5 4.5 0 0116.5 2H21v16h-4.5A4.5 4.5 0 0012 22m0-15.5V22"/>
                    </svg>

                </div>

            </div>

            <div class="mt-4">
                <a href="{{ route('subjects.index') }}"
                   class="text-sm font-semibold text-emerald-700 hover:text-emerald-600">
                    Voir les matières →
                </a>
            </div>

        </div>

    </div>


    {{-- ============================================================
        DEUXIÈME LIGNE DE STATISTIQUES
    ============================================================ --}}
    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Inscriptions --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center gap-4">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-800">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 5h6M9 3h6a2 2 0 012 2v1h1a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h1V5a2 2 0 012-2zM8 11h8M8 15h6"/>
                    </svg>

                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Inscriptions
                    </p>

                    <p class="text-xl font-bold text-gray-900">
                        {{ $stats['inscriptions'] ?? 0 }}
                    </p>
                </div>

            </div>

            <a href="{{ route('inscriptions.index') }}"
               class="mt-4 inline-block text-sm font-semibold text-blue-800 hover:text-blue-600">
                Gérer les inscriptions →
            </a>

        </div>


        {{-- Notes --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center gap-4">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-purple-100 text-purple-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h5"/>
                    </svg>

                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Notes
                    </p>

                    <p class="text-xl font-bold text-gray-900">
                        {{ $stats['notes'] ?? 0 }}
                    </p>
                </div>

            </div>

            <a href="{{ route('notes.index') }}"
               class="mt-4 inline-block text-sm font-semibold text-purple-700 hover:text-purple-600">
                Gérer les notes →
            </a>

        </div>


        {{-- Paiements --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center gap-4">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-green-100 text-green-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 7h18M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2zm10 8h3"/>
                    </svg>

                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Paiements
                    </p>

                    <p class="text-xl font-bold text-gray-900">
                        {{ $stats['paiements'] ?? 0 }}
                    </p>
                </div>

            </div>

            <p class="mt-3 text-sm font-semibold text-green-700">
                {{ number_format((float) ($totalPayments ?? 0), 0, ',', ' ') }}
            </p>

            <p class="text-xs text-gray-400">
                Montant total enregistré
            </p>

        </div>


        {{-- Établissements --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center gap-4">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-orange-100 text-orange-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 9h.01M12 9h.01M15 9h.01"/>
                    </svg>

                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Mes établissements
                    </p>

                    <p class="text-xl font-bold text-gray-900">
                        {{ $establishments->count() }}
                    </p>
                </div>

            </div>

            <a href="{{ route('establishments.choose') }}"
               class="mt-4 inline-block text-sm font-semibold text-orange-700 hover:text-orange-600">
                Gérer les établissements →
            </a>

        </div>

    </div>


    {{-- ============================================================
        CONTENU PRINCIPAL
    ============================================================ --}}
    <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-2">

        {{-- ========================================================
            DERNIERS ÉTUDIANTS
        ======================================================== --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">

                <div>
                    <h2 class="text-base font-bold text-gray-900">
                        Derniers étudiants
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Les derniers étudiants enregistrés
                    </p>
                </div>

                <a href="{{ route('students.index') }}"
                   class="text-sm font-semibold text-blue-800 hover:text-blue-600">
                    Voir tout
                </a>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-gray-50">

                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Matricule
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Étudiant
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Classe
                            </th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($latestStudents ?? [] as $student)

                            <tr class="transition hover:bg-gray-50">

                                <td class="whitespace-nowrap px-5 py-3 text-sm font-medium text-blue-800">
                                    {{ $student->matricule ?? '—' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-3">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-800">
                                            {{ strtoupper(substr($student->prenom ?? $student->nom ?? 'E', 0, 1)) }}
                                        </div>

                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $student->nom ?? '' }}
                                                {{ $student->prenom ?? '' }}
                                            </p>

                                            @if($student->email)
                                                <p class="text-xs text-gray-400">
                                                    {{ $student->email }}
                                                </p>
                                            @endif
                                        </div>

                                    </div>

                                </td>

                                <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-600">
                                    {{ $student->classe ?? '—' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="px-5 py-10 text-center">

                                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8z"/>
                                        </svg>
                                    </div>

                                    <p class="mt-3 text-sm font-medium text-gray-500">
                                        Aucun étudiant enregistré
                                    </p>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ========================================================
            DERNIÈRES INSCRIPTIONS
        ======================================================== --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">

                <div>
                    <h2 class="text-base font-bold text-gray-900">
                        Dernières inscriptions
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Les dernières inscriptions effectuées
                    </p>
                </div>

                <a href="{{ route('inscriptions.index') }}"
                   class="text-sm font-semibold text-green-700 hover:text-green-600">
                    Voir tout
                </a>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-gray-50">

                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Étudiant
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Classe
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Statut
                            </th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($latestInscriptions ?? [] as $inscription)

                            <tr class="transition hover:bg-gray-50">

                                <td class="whitespace-nowrap px-5 py-3">

                                    <p class="text-sm font-semibold text-gray-900">

                                        @if($inscription->student)
                                            {{ $inscription->student->nom ?? '' }}
                                            {{ $inscription->student->prenom ?? '' }}
                                        @else
                                            Étudiant supprimé
                                        @endif

                                    </p>

                                    @if($inscription->student?->matricule)

                                        <p class="text-xs text-gray-400">
                                            {{ $inscription->student->matricule }}
                                        </p>

                                    @endif

                                </td>

                                <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-600">

                                    @if($inscription->classRoom)
                                        {{ $inscription->classRoom->nom_classe }}
                                    @else
                                        —
                                    @endif

                                </td>

                                <td class="whitespace-nowrap px-5 py-3">

                                    @php
                                        $statut = strtolower(
                                            (string) ($inscription->statut ?? 'active')
                                        );
                                    @endphp

                                    @if(in_array($statut, ['active', 'actif', 'validée', 'validee', 'valide']))

                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            {{ $inscription->statut ?? 'Active' }}
                                        </span>

                                    @elseif(in_array($statut, ['annulée', 'annulee', 'annulé', 'annule']))

                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            {{ $inscription->statut }}
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                            {{ $inscription->statut ?? 'En attente' }}
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="px-5 py-10 text-center">

                                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-400">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M9 5h6M9 3h6a2 2 0 012 2v1h1a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h1V5a2 2 0 012-2z"/>
                                        </svg>

                                    </div>

                                    <p class="mt-3 text-sm font-medium text-gray-500">
                                        Aucune inscription enregistrée
                                    </p>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ============================================================
        INFORMATIONS DE L'ÉTABLISSEMENT
    ============================================================ --}}
    <div class="mt-6 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-5 py-4">

            <h2 class="text-base font-bold text-gray-900">
                Informations de l'établissement
            </h2>

            <p class="mt-1 text-xs text-gray-500">
                Informations de l'établissement actuellement sélectionné
            </p>

        </div>

        <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2 xl:grid-cols-4">

            {{-- Nom --}}
            <div class="rounded-lg bg-gray-50 p-4">

                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Nom
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-900">
                    {{ $establishment->nom ?? '—' }}
                </p>

            </div>

            {{-- Type --}}
            <div class="rounded-lg bg-gray-50 p-4">

                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Type
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-900">
                    {{ $schoolTypeName ?? '—' }}
                </p>

            </div>

            {{-- Adresse --}}
            <div class="rounded-lg bg-gray-50 p-4">

                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Adresse
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-900">
                    {{ $establishment->adresse ?? '—' }}
                </p>

            </div>

            {{-- Téléphone --}}
            <div class="rounded-lg bg-gray-50 p-4">

                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Téléphone
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-900">
                    {{ $establishment->telephone ?? '—' }}
                </p>

            </div>

        </div>

    </div>


    {{-- ============================================================
        RÉPARTITION DES ÉTUDIANTS
    ============================================================ --}}
    <div class="mt-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

        <div class="mb-5">

            <h2 class="text-base font-bold text-gray-900">
                Répartition des étudiants
            </h2>

            <p class="mt-1 text-xs text-gray-500">
                Répartition par sexe dans l'établissement
            </p>

        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            {{-- Hommes --}}
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">

                <div class="flex items-center justify-between">

                    <span class="text-sm font-medium text-gray-600">
                        Hommes
                    </span>

                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-800">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M15 3h6v6M21 3l-8 8M18 13v5a3 3 0 01-3 3H6a3 3 0 01-3-3v-9a3 3 0 013-3h5"/>
                        </svg>

                    </span>

                </div>

                <p class="mt-3 text-2xl font-bold text-gray-900">
                    {{ $studentsBySexe['M'] ?? $studentsBySexe['H'] ?? 0 }}
                </p>

            </div>


            {{-- Femmes --}}
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">

                <div class="flex items-center justify-between">

                    <span class="text-sm font-medium text-gray-600">
                        Femmes
                    </span>

                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-100 text-green-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 21a7 7 0 100-14 7 7 0 000 14zM12 4V2M9 2h6M12 11v4M10 13h4"/>
                        </svg>

                    </span>

                </div>

                <p class="mt-3 text-2xl font-bold text-gray-900">
                    {{ $studentsBySexe['F'] ?? 0 }}
                </p>

            </div>


            {{-- Total --}}
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">

                <div class="flex items-center justify-between">

                    <span class="text-sm font-medium text-gray-600">
                        Total
                    </span>

                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                        </svg>

                    </span>

                </div>

                <p class="mt-3 text-2xl font-bold text-gray-900">
                    {{ $stats['students'] ?? 0 }}
                </p>

            </div>

        </div>

    </div>


    {{-- ============================================================
        ACTIONS RAPIDES
    ============================================================ --}}
    <div class="mt-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

        <div class="mb-5">

            <h2 class="text-base font-bold text-gray-900">
                Actions rapides
            </h2>

            <p class="mt-1 text-xs text-gray-500">
                Accès rapide aux principales fonctions
            </p>

        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">

            <a href="{{ route('students.create') }}"
               class="flex items-center gap-3 rounded-lg border border-gray-200 p-4 transition hover:border-blue-300 hover:bg-blue-50">

                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-800">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 5v14M5 12h14"/>
                    </svg>

                </span>

                <span>
                    <span class="block text-sm font-semibold text-gray-900">
                        Ajouter étudiant
                    </span>

                    <span class="block text-xs text-gray-500">
                        Nouveau dossier
                    </span>
                </span>

            </a>


            <a href="{{ route('teachers.create') }}"
               class="flex items-center gap-3 rounded-lg border border-gray-200 p-4 transition hover:border-green-300 hover:bg-green-50">

                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 12a4 4 0 100-8 4 4 0 000 8zM4 21a8 8 0 0116 0"/>
                    </svg>

                </span>

                <span>
                    <span class="block text-sm font-semibold text-gray-900">
                        Ajouter enseignant
                    </span>

                    <span class="block text-xs text-gray-500">
                        Nouveau personnel
                    </span>
                </span>

            </a>


            <a href="{{ route('class_rooms.create') }}"
               class="flex items-center gap-3 rounded-lg border border-gray-200 p-4 transition hover:border-indigo-300 hover:bg-indigo-50">

                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M4 19V5a2 2 0 012-2h12a2 2 0 012 2v14M4 19h16M8 7h8M8 11h8"/>
                    </svg>

                </span>

                <span>
                    <span class="block text-sm font-semibold text-gray-900">
                        Ajouter une classe
                    </span>

                    <span class="block text-xs text-gray-500">
                        Nouvelle classe
                    </span>
                </span>

            </a>


            <a href="{{ route('inscriptions.create') }}"
               class="flex items-center gap-3 rounded-lg border border-gray-200 p-4 transition hover:border-emerald-300 hover:bg-emerald-50">

                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 5h6M9 3h6a2 2 0 012 2v1h1a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h1V5a2 2 0 012-2zM8 11h8M8 15h5"/>
                    </svg>

                </span>

                <span>
                    <span class="block text-sm font-semibold text-gray-900">
                        Nouvelle inscription
                    </span>

                    <span class="block text-xs text-gray-500">
                        Inscrire un étudiant
                    </span>
                </span>

            </a>

        </div>

    </div>

</div>

@endsection