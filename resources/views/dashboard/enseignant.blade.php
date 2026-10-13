@extends('layouts.gestion-ecole')

@section('content')

<div class="space-y-6">

    {{-- ============================================================
         EN-TÊTE ENSEIGNANT
    ============================================================ --}}
    <div class="rounded-2xl border border-blue-200 bg-white p-6 shadow-sm">

        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

            <div class="flex items-start gap-4">

                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-800 text-white">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 14.5a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4.5 20a7.5 7.5 0 0 1 15 0"/>
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">
                        Espace enseignant
                    </p>

                    <h1 class="mt-1 text-2xl font-bold text-slate-900">
                        Dashboard Enseignant
                    </h1>

                    @if($teacher)
                        <p class="mt-2 text-base font-semibold text-slate-700">
                            Bonjour
                            {{ trim(($teacher->prenom ?? '') . ' ' . ($teacher->nom ?? '')) }}
                        </p>
                    @endif

                    <p class="mt-1 text-sm text-slate-500">
                        Gestion pédagogique, classes, élèves et notes.
                    </p>
                </div>

            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                @if($currentEstablishment)
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Établissement
                        </p>

                        <p class="mt-1 font-bold text-slate-900">
                            {{ $currentEstablishment->nom }}
                        </p>
                    </div>
                @endif

                @if($currentSchoolYear)
                    <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-green-700">
                            Année scolaire
                        </p>

                        <p class="mt-1 font-bold text-green-900">
                            {{ $currentSchoolYear->name }}
                        </p>
                    </div>
                @endif

            </div>

        </div>
    </div>


    {{-- ============================================================
         PROFIL MANQUANT
    ============================================================ --}}
    @if($profileMissing)

        <div class="rounded-2xl border border-red-200 bg-red-50 p-6">

            <div class="flex items-start gap-4">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v4"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 17h.01"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M10.3 4.2 2.8 17a2 2 0 0 0 1.7 3h15a2 2 0 0 0 1.7-3L13.7 4.2a2 2 0 0 0-3.4 0Z"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-red-900">
                        Profil enseignant non configuré
                    </h2>

                    <p class="mt-1 text-sm leading-6 text-red-800">
                        Votre compte possède le rôle Enseignant, mais aucun profil
                        enseignant n'est encore associé à votre compte.
                        Contactez l'administration pour effectuer cette association.
                    </p>
                </div>

            </div>
        </div>

    @else

        {{-- ========================================================
             STATISTIQUES
        ========================================================= --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Classes --}}
            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">

                <div class="flex items-center justify-between gap-4">

                    <div>
                        <p class="text-sm font-semibold text-blue-700">
                            Mes classes
                        </p>

                        <p class="mt-2 text-3xl font-bold text-blue-950">
                            {{ $stats['classes'] }}
                        </p>

                        <p class="mt-1 text-xs text-blue-700">
                            Classes enseignées
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-800 text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 6.5h16M4 10.5h16M4 14.5h10M4 18.5h7"/>
                        </svg>
                    </div>

                </div>
            </div>


            {{-- Matières --}}
            <div class="rounded-2xl border border-green-200 bg-green-50 p-5">

                <div class="flex items-center justify-between gap-4">

                    <div>
                        <p class="text-sm font-semibold text-green-700">
                            Mes matières
                        </p>

                        <p class="mt-2 text-3xl font-bold text-green-950">
                            {{ $stats['subjects'] }}
                        </p>

                        <p class="mt-1 text-xs text-green-700">
                            Matières enseignées
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-700 text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 4h11a3 3 0 0 1 3 3v13H8a3 3 0 0 1-3-3V4Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 20V7a3 3 0 0 0-3-3"/>
                        </svg>
                    </div>

                </div>
            </div>


            {{-- Élèves --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between gap-4">

                    <div>
                        <p class="text-sm font-semibold text-slate-600">
                            Mes élèves
                        </p>

                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            {{ $stats['students'] }}
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Élèves de mes classes
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-800 text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <circle cx="9" cy="8" r="3"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3.5 20a5.5 5.5 0 0 1 11 0"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 5.5a3 3 0 0 1 0 5.8M17 20a5 5 0 0 0-3-4.6"/>
                        </svg>
                    </div>

                </div>
            </div>


            {{-- Notes --}}
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">

                <div class="flex items-center justify-between gap-4">

                    <div>
                        <p class="text-sm font-semibold text-amber-700">
                            Mes notes
                        </p>

                        <p class="mt-2 text-3xl font-bold text-amber-950">
                            {{ $stats['notes'] }}
                        </p>

                        <p class="mt-1 text-xs text-amber-700">
                            Notes saisies
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-600 text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6 3h9l3 3v15H6V3Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 8h3M9 12h6M9 16h6"/>
                        </svg>
                    </div>

                </div>
            </div>

        </div>


        {{-- ========================================================
             ACTIONS RAPIDES
        ========================================================= --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="mb-5">
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">
                    Accès rapide
                </p>

                <h2 class="mt-1 text-xl font-bold text-slate-900">
                    Mes outils pédagogiques
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Accédez rapidement aux fonctions disponibles pour votre rôle.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

                {{-- Ajouter note --}}
                <a href="{{ route('notes.create') }}"
                   class="rounded-xl border border-blue-200 bg-blue-50 p-5 transition hover:border-blue-400 hover:bg-blue-100">

                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-blue-800 text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 5v14M5 12h14"/>
                        </svg>
                    </div>

                    <h3 class="font-bold text-blue-950">
                        Ajouter une note
                    </h3>

                    <p class="mt-1 text-sm text-blue-800">
                        Saisir une nouvelle note pour un élève.
                    </p>
                </a>


                {{-- Mes notes --}}
                <a href="{{ route('notes.index') }}"
                   class="rounded-xl border border-green-200 bg-green-50 p-5 transition hover:border-green-400 hover:bg-green-100">

                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-green-700 text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 5h14v14H5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m8 12 2.5 2.5L16 9"/>
                        </svg>
                    </div>

                    <h3 class="font-bold text-green-950">
                        Gérer mes notes
                    </h3>

                    <p class="mt-1 text-sm text-green-800">
                        Consulter, modifier et supprimer vos notes.
                    </p>
                </a>


                {{-- Résultats --}}
                <a href="{{ route('resultats.index') }}"
                   class="rounded-xl border border-slate-200 bg-slate-50 p-5 transition hover:border-slate-400 hover:bg-slate-100">

                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-slate-800 text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 19V5M5 19h14"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m8 15 3-4 3 2 4-6"/>
                        </svg>
                    </div>

                    <h3 class="font-bold text-slate-900">
                        Résultats scolaires
                    </h3>

                    <p class="mt-1 text-sm text-slate-600">
                        Consulter les résultats disponibles.
                    </p>
                </a>


                {{-- Emploi du temps --}}
                <a href="{{ route('emploi-du-temps.index') }}"
                   class="rounded-xl border border-indigo-200 bg-indigo-50 p-5 transition hover:border-indigo-400 hover:bg-indigo-100">

                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-700 text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <rect x="4" y="5" width="16" height="15" rx="2"/>
                            <path stroke-linecap="round" d="M8 3v4M16 3v4M4 10h16"/>
                        </svg>
                    </div>

                    <h3 class="font-bold text-indigo-950">
                        Emploi du temps
                    </h3>

                    <p class="mt-1 text-sm text-indigo-800">
                        Consulter votre planning.
                    </p>
                </a>

            </div>
        </div>


        {{-- ========================================================
             MES CLASSES + MES MATIÈRES
        ========================================================= --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

            {{-- Classes --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 p-6">
                    <div class="flex items-center justify-between gap-4">

                        <div>
                            <h2 class="text-xl font-bold text-slate-900">
                                Mes classes
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Classes qui vous sont affectées cette année.
                            </p>
                        </div>

                        <span class="rounded-full bg-blue-100 px-3 py-1.5 text-sm font-bold text-blue-800">
                            {{ $classes->count() }}
                        </span>

                    </div>
                </div>

                <div class="divide-y divide-slate-100">

                    @forelse($classes as $class)

                        <div class="flex items-center justify-between gap-4 p-5">

                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-800">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M4 5h16v14H4z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M8 9h8M8 13h5"/>
                                    </svg>
                                </div>

                                <div>
                                    <p class="font-bold text-slate-900">
                                        {{ $class->nom_classe }}
                                    </p>

                                    @if($class->niveau)
                                        <p class="mt-1 text-sm text-slate-500">
                                            Niveau : {{ $class->niveau }}
                                        </p>
                                    @endif
                                </div>

                            </div>

                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                                Classe
                            </span>

                        </div>

                    @empty

                        <div class="p-6 text-sm text-slate-500">
                            Aucune classe ne vous est actuellement affectée.
                        </div>

                    @endforelse

                </div>
            </div>


            {{-- Matières --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 p-6">
                    <div class="flex items-center justify-between gap-4">

                        <div>
                            <h2 class="text-xl font-bold text-slate-900">
                                Mes matières
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Matières que vous enseignez cette année.
                            </p>
                        </div>

                        <span class="rounded-full bg-green-100 px-3 py-1.5 text-sm font-bold text-green-800">
                            {{ $subjects->count() }}
                        </span>

                    </div>
                </div>

                <div class="divide-y divide-slate-100">

                    @forelse($subjects as $subject)

                        <div class="flex items-center justify-between gap-4 p-5">

                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-green-100 text-green-800">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M5 4h11a3 3 0 0 1 3 3v13H8a3 3 0 0 1-3-3V4Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M8 20V7a3 3 0 0 0-3-3"/>
                                    </svg>
                                </div>

                                <p class="font-bold text-slate-900">
                                    {{ $subject->nom ?? $subject->name ?? 'Matière' }}
                                </p>

                            </div>

                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                Matière
                            </span>

                        </div>

                    @empty

                        <div class="p-6 text-sm text-slate-500">
                            Aucune matière ne vous est actuellement affectée.
                        </div>

                    @endforelse

                </div>
            </div>

        </div>


        {{-- ========================================================
             MES ENSEIGNEMENTS
        ========================================================= --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 p-6">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h2 class="text-xl font-bold text-slate-900">
                            Mes enseignements
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Détail de vos affectations pédagogiques.
                        </p>
                    </div>

                    <span class="w-fit rounded-full bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-700">
                        {{ $teacherSubjects->count() }} affectation(s)
                    </span>

                </div>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">
                                Classe
                            </th>

                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">
                                Matière
                            </th>

                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">
                                Année scolaire
                            </th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">

                        @forelse($teacherSubjects as $assignment)

                            <tr class="transition hover:bg-slate-50">

                                <td class="px-6 py-4 text-sm font-bold text-slate-900">
                                    {{ $assignment->classRoom->nom_classe ?? 'Classe' }}
                                </td>

                                <td class="px-6 py-4 text-sm font-semibold text-blue-800">
                                    {{ $assignment->subject->nom ?? $assignment->subject->name ?? 'Matière' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $assignment->schoolYear->name ?? 'Année scolaire' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-sm text-slate-500">
                                    Aucune affectation pédagogique pour cette année scolaire.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>


        {{-- ========================================================
             DERNIÈRES NOTES
        ========================================================= --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="flex flex-col gap-4 border-b border-slate-200 p-6 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">
                        Suivi pédagogique
                    </p>

                    <h2 class="mt-1 text-xl font-bold text-slate-900">
                        Dernières notes
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Les dernières notes que vous avez enregistrées.
                    </p>
                </div>

                <a href="{{ route('notes.index') }}"
                   class="inline-flex items-center justify-center rounded-lg bg-blue-800 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-900">

                    <svg class="mr-2 h-5 w-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M5 5h14v14H5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 12h8"/>
                    </svg>

                    Voir toutes les notes
                </a>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>
                            <th class="px-5 py-4 text-left text-sm font-semibold text-slate-700">
                                Élève
                            </th>

                            <th class="px-5 py-4 text-left text-sm font-semibold text-slate-700">
                                Classe
                            </th>

                            <th class="px-5 py-4 text-left text-sm font-semibold text-slate-700">
                                Matière
                            </th>

                            <th class="px-5 py-4 text-left text-sm font-semibold text-slate-700">
                                Note
                            </th>

                            <th class="px-5 py-4 text-left text-sm font-semibold text-slate-700">
                                Évaluation
                            </th>

                            <th class="px-5 py-4 text-left text-sm font-semibold text-slate-700">
                                Date
                            </th>

                            <th class="px-5 py-4 text-right text-sm font-semibold text-slate-700">
                                Action
                            </th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse($recentNotes as $note)

                            <tr class="transition hover:bg-slate-50">

                                <td class="px-5 py-4 text-sm font-bold text-slate-900">
                                    {{ trim(($note->student->prenom ?? '') . ' ' . ($note->student->nom ?? '')) ?: 'Élève' }}
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-600">
                                    {{ $note->classRoom->nom_classe ?? '—' }}
                                </td>

                                <td class="px-5 py-4 text-sm font-semibold text-slate-700">
                                    {{ $note->subject->nom ?? $note->subject->name ?? '—' }}
                                </td>

                                <td class="px-5 py-4">

                                    <span class="inline-flex rounded-lg bg-blue-100 px-3 py-1.5 text-sm font-bold text-blue-900">
                                        {{ $note->note }}/20
                                    </span>

                                </td>

                                <td class="px-5 py-4 text-sm text-slate-600">
                                    {{ $note->type_evaluation ?? '—' }}
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-600">
                                    {{ $note->date_evaluation?->format('d/m/Y') ?? '—' }}
                                </td>

                                <td class="px-5 py-4">

                                    <div class="flex items-center justify-end gap-2">

                                        @if(Route::has('notes.show'))
                                            <a href="{{ route('notes.show', $note) }}"
                                               class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                Voir
                                            </a>
                                        @endif

                                        @if(Route::has('notes.edit'))
                                            <a href="{{ route('notes.edit', $note) }}"
                                               class="inline-flex items-center rounded-lg bg-green-700 px-3 py-2 text-xs font-semibold text-white hover:bg-green-800">
                                                Modifier
                                            </a>
                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="px-6 py-10 text-center">

                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M6 3h9l3 3v15H6V3Z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M9 12h6M9 16h4"/>
                                        </svg>
                                    </div>

                                    <p class="mt-3 font-semibold text-slate-700">
                                        Aucune note saisie
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Les notes que vous enregistrerez apparaîtront ici.
                                    </p>

                                    <a href="{{ route('notes.create') }}"
                                       class="mt-4 inline-flex rounded-lg bg-blue-800 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-900">
                                        Ajouter la première note
                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>


        {{-- ========================================================
             RÉPARTITION DES ÉVALUATIONS
        ========================================================= --}}
        @if($notesByType->count())

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 p-6">

                    <h2 class="text-xl font-bold text-slate-900">
                        Répartition des évaluations
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Nombre de notes et moyenne par type d'évaluation.
                    </p>

                </div>

                <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2 lg:grid-cols-3">

                    @foreach($notesByType as $type => $data)

                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">

                            <div class="flex items-center justify-between gap-3">

                                <p class="font-bold text-slate-900">
                                    {{ $type ?: 'Autre' }}
                                </p>

                                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-bold text-blue-800">
                                    {{ $data['count'] }}
                                </span>

                            </div>

                            <div class="mt-4 flex items-end justify-between gap-4">

                                <div>
                                    <p class="text-2xl font-bold text-blue-900">
                                        {{ $data['count'] }}
                                    </p>

                                    <p class="text-xs text-slate-500">
                                        note(s)
                                    </p>
                                </div>

                                <div class="text-right">

                                    <p class="text-xl font-bold text-green-700">
                                        {{ number_format($data['average'], 2, ',', ' ') }}/20
                                    </p>

                                    <p class="text-xs text-slate-500">
                                        moyenne
                                    </p>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>
            </div>

        @endif


        {{-- ========================================================
             RAPPEL DU RÔLE
        ========================================================= --}}
        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">

            <div class="flex items-start gap-4">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-800 text-white">

                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 16v-4"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 8h.01"/>
                        <circle cx="12" cy="12" r="9"/>
                    </svg>

                </div>

                <div>

                    <h3 class="font-bold text-blue-950">
                        Votre espace enseignant
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-blue-800">
                        Vous pouvez gérer les notes de vos élèves,
                        consulter vos classes, vos matières, vos résultats
                        et votre emploi du temps.
                    </p>

                </div>

            </div>

        </div>

    @endif

</div>

@endsection
