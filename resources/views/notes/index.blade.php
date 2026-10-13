@extends(auth()->user()->role === 'parent'
    ? 'layouts.parent'
    : 'layouts.gestion-ecole')

@section('title', 'Notes')

@section('page-title', 'Gestion des notes')

@section('page-description', 'Consultez, recherchez et gérez les évaluations des étudiants.')

@section('content')

<div class="space-y-6">

    {{-- ================================================================
        MESSAGES
    ================================================================= --}}

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800">
            <div class="flex items-start gap-3">
                <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>

                <div>
                    <p class="font-semibold">Opération réussie</p>
                    <p class="mt-1">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
            <div class="flex items-start gap-3">
                <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>

                <div>
                    <p class="font-semibold">Attention</p>
                    <p class="mt-1">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif


    {{-- ================================================================
        EN-TÊTE
    ================================================================= --}}

    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6.586a1 1 0 01.707.293l3.414 3.414A1 1 0 0118 7.414V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-gray-900">
                        Liste des notes
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Suivi des évaluations et des résultats scolaires.
                    </p>
                </div>
            </div>
        </div>

        @if(in_array(auth()->user()->role, ['admin', 'enseignant'], true))

        <a href="{{ route('notes.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">

            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>

            Ajouter une note
        </a>

        @endif

    </div>


    {{-- ================================================================
        ÉTABLISSEMENT
    ================================================================= --}}

    <div class="rounded-xl border border-blue-100 bg-blue-50 px-5 py-4">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-700 text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 4h1m4 0h1"/>
                    </svg>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-blue-600">
                        Établissement
                    </p>

                    <p class="font-semibold text-gray-900">
                        {{ $establishment->nom }}
                    </p>
                </div>

            </div>

            <div class="rounded-lg bg-white px-3 py-2 text-xs font-medium text-gray-600 shadow-sm">
                ID : {{ $establishment->id }}
            </div>

        </div>

    </div>


    {{-- ================================================================
        STATISTIQUES
    ================================================================= --}}

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Total notes --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Notes
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalNotes }}
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6.586a1 1 0 01.707.293l3.414 3.414A1 1 0 0118 7.414V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

            </div>

            <p class="mt-3 text-xs text-gray-500">
                Selon les filtres sélectionnés
            </p>

        </div>


        {{-- Moyenne --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Moyenne
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ number_format($moyenne, 2, ',', ' ') }}
                        <span class="text-sm font-medium text-gray-400">/20</span>
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-green-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>

            </div>

            <p class="mt-3 text-xs text-gray-500">
                Moyenne des notes affichées
            </p>

        </div>


        {{-- Étudiants --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Étudiants évalués
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalEtudiants }}
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>

            </div>

            <p class="mt-3 text-xs text-gray-500">
                Étudiants concernés
            </p>

        </div>


        {{-- Matières --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Matières évaluées
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalMatieres }}
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-100 text-purple-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>

            </div>

            <p class="mt-3 text-xs text-gray-500">
                Matières concernées
            </p>

        </div>

    </div>


    {{-- ================================================================
        FILTRES
    ================================================================= --}}

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-5 py-4">

            <div class="flex items-center gap-3">

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 12.414V19a1 1 0 01-.553.894l-4 2A1 1 0 019 21v-8.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900">
                        Recherche et filtres
                    </h3>

                    <p class="text-xs text-gray-500">
                        Affinez la liste des évaluations.
                    </p>
                </div>

            </div>

        </div>


        <form method="GET"
              action="{{ route('notes.index') }}"
              class="p-5">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">

                {{-- Recherche --}}
                <div class="xl:col-span-3">

                    <label for="search"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Rechercher un étudiant
                    </label>

                    <div class="relative">

                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>
                            </svg>

                        </div>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Nom, prénom ou matricule..."
                            class="w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >

                    </div>

                </div>


                {{-- Année --}}
                <div>

                    <label for="school_year_id"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Année scolaire
                    </label>

                    <select
                        id="school_year_id"
                        name="school_year_id"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                        <option value="">Toutes les années</option>

                        @foreach($schoolYears as $schoolYear)
                            <option
                                value="{{ $schoolYear->id }}"
                                @selected((string) request('school_year_id') === (string) $schoolYear->id)
                            >
                                {{ $schoolYear->name }}
                                @if($schoolYear->is_active)
                                    — Active
                                @endif
                            </option>
                        @endforeach
                    </select>

                </div>


                {{-- Classe --}}
                <div>

                    <label for="class_room_id"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Classe
                    </label>

                    <select
                        id="class_room_id"
                        name="class_room_id"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                        <option value="">Toutes les classes</option>

                        @foreach($classes as $class)
                            <option
                                value="{{ $class->id }}"
                                @selected((string) request('class_room_id') === (string) $class->id)
                            >
                                {{ $class->nom_classe }}
                                @if($class->niveau)
                                    — {{ $class->niveau }}
                                @endif
                            </option>
                        @endforeach
                    </select>

                </div>


                {{-- Matière --}}
                <div>

                    <label for="subject_id"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Matière
                    </label>

                    <select
                        id="subject_id"
                        name="subject_id"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                        <option value="">Toutes les matières</option>

                        @foreach($subjects as $subject)
                            <option
                                value="{{ $subject->id }}"
                                @selected((string) request('subject_id') === (string) $subject->id)
                            >
                                {{ $subject->nom }}
                            </option>
                        @endforeach
                    </select>

                </div>


                {{-- Type --}}
                <div>

                    <label for="type_evaluation"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Type d'évaluation
                    </label>

                    <select
                        id="type_evaluation"
                        name="type_evaluation"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                        <option value="">Tous les types</option>

                        @foreach($typesEvaluation as $type)
                            <option
                                value="{{ $type }}"
                                @selected(request('type_evaluation') === $type)
                            >
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>

                </div>

            </div>


            <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:justify-end">

                <a href="{{ route('notes.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>

                    Réinitialiser
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>
                    </svg>

                    Rechercher
                </button>

            </div>

        </form>

    </div>


    {{-- ================================================================
        LISTE DES NOTES
    ================================================================= --}}

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="flex flex-col gap-3 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h3 class="font-semibold text-gray-900">
                    Évaluations enregistrées
                </h3>

                <p class="mt-1 text-xs text-gray-500">
                    {{ $notes->total() }} résultat(s) correspondant aux critères.
                </p>
            </div>

            @if(request()->hasAny([
                'search',
                'school_year_id',
                'class_room_id',
                'subject_id',
                'type_evaluation'
            ]))
                <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700">

                    <span class="h-2 w-2 rounded-full bg-blue-600"></span>

                    Filtres actifs
                </span>
            @endif

        </div>


        @if($notes->count())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Étudiant
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Classe
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Matière
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Enseignant
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Note
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Coef.
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Évaluation
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Date
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100 bg-white">

                        @foreach($notes as $note)

                            @php
                                $score = (float) $note->note;

                                if ($score >= 16) {
                                    $scoreClass = 'bg-green-100 text-green-700 border-green-200';
                                } elseif ($score >= 10) {
                                    $scoreClass = 'bg-blue-100 text-blue-700 border-blue-200';
                                } elseif ($score >= 8) {
                                    $scoreClass = 'bg-orange-100 text-orange-700 border-orange-200';
                                } else {
                                    $scoreClass = 'bg-red-100 text-red-700 border-red-200';
                                }
                            @endphp

                            <tr class="transition hover:bg-gray-50">

                                {{-- Étudiant --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-sm font-bold text-blue-700">
                                            {{ strtoupper(substr($note->student?->nom ?? '?', 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">

                                            <p class="truncate font-semibold text-gray-900">
                                                {{ $note->student?->nom ?? '—' }}
                                                {{ $note->student?->prenom ?? '' }}
                                            </p>

                                            @if($note->student?->matricule)
                                                <p class="mt-0.5 text-xs text-gray-500">
                                                    {{ $note->student->matricule }}
                                                </p>
                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- Classe --}}
                                <td class="px-5 py-4">

                                    <span class="font-medium text-gray-800">
                                        {{ $note->classRoom?->nom_classe ?? '—' }}
                                    </span>

                                    @if($note->classRoom?->niveau)
                                        <p class="mt-0.5 text-xs text-gray-500">
                                            {{ $note->classRoom->niveau }}
                                        </p>
                                    @endif

                                </td>


                                {{-- Matière --}}
                                <td class="px-5 py-4">

                                    <p class="font-medium text-gray-800">
                                        {{ $note->subject?->nom ?? '—' }}
                                    </p>

                                    @if($note->subject?->code)
                                        <p class="mt-0.5 text-xs text-gray-500">
                                            {{ $note->subject->code }}
                                        </p>
                                    @endif

                                </td>


                                {{-- Enseignant --}}
                                <td class="px-5 py-4">

                                    @if($note->teacher)

                                        <p class="font-medium text-gray-800">
                                            {{ $note->teacher->nom }}
                                            {{ $note->teacher->prenom }}
                                        </p>

                                    @else

                                        <span class="text-sm text-gray-400">
                                            Non renseigné
                                        </span>

                                    @endif

                                </td>


                                {{-- Note --}}
                                <td class="px-5 py-4 text-center">

                                    <span class="inline-flex items-center rounded-lg border px-3 py-1.5 text-sm font-bold {{ $scoreClass }}">
                                        {{ number_format($score, 2, ',', ' ') }}/20
                                    </span>

                                </td>


                                {{-- Coefficient --}}
                                <td class="px-5 py-4 text-center">

                                    <span class="font-semibold text-gray-700">
                                        {{ number_format((float) $note->coefficient, 2, ',', ' ') }}
                                    </span>

                                </td>


                                {{-- Type --}}
                                <td class="px-5 py-4">

                                    <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                                        {{ $note->type_evaluation ?: 'Non renseigné' }}
                                    </span>

                                </td>


                                {{-- Date --}}
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">

                                    @if($note->date_evaluation)
                                        {{ $note->date_evaluation->format('d/m/Y') }}
                                    @else
                                        —
                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center justify-end gap-2">

                                        {{-- Voir : accessible à tous --}}
                                        <a
                                            href="{{ route('notes.show', $note) }}"
                                            title="Voir"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        {{-- Modifier + Supprimer : interdits au parent --}}
                                        @if(in_array(auth()->user()->role, ['admin', 'enseignant'], true))

                                            {{-- Modifier --}}
                                            <a
                                                href="{{ route('notes.edit', $note) }}"
                                                title="Modifier"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:border-green-200 hover:bg-green-50 hover:text-green-700"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </a>

                                            {{-- Supprimer --}}
                                            <form
                                                action="{{ route('notes.destroy', $note) }}"
                                                method="POST"
                                                onsubmit="return confirm('Voulez-vous vraiment supprimer cette note ?');"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    title="Supprimer"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-700"
                                                >
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-8 0h10"/>
                                                    </svg>
                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- ========================================================
                PAGINATION
            ========================================================= --}}

            @if($notes->hasPages())

                <div class="border-t border-gray-200 px-5 py-4">

                    {{ $notes->links() }}

                </div>

            @endif

        @else

            {{-- ========================================================
                EMPTY STATE
            ========================================================= --}}

            <div class="px-6 py-14 text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 text-gray-400">

                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6.586a1 1 0 01.707.293l3.414 3.414A1 1 0 0118 7.414V19a2 2 0 01-2 2z"/>
                    </svg>

                </div>

                <h3 class="mt-4 text-base font-semibold text-gray-900">
                    Aucune note trouvée
                </h3>

                <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
                    Aucune note ne correspond aux critères sélectionnés.
                    Vous pouvez modifier les filtres ou enregistrer une nouvelle note.
                </p>

                <div class="mt-5 flex flex-col justify-center gap-3 sm:flex-row">

                    <a
                        href="{{ route('notes.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Réinitialiser les filtres
                    </a>

                    @if(in_array(auth()->user()->role, ['admin', 'enseignant'], true))

                    <a
                        href="{{ route('notes.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4"/>
                        </svg>

                        Ajouter une note
                    </a>

                    @endif

                </div>

            </div>

        @endif

    </div>

</div>

@endsection