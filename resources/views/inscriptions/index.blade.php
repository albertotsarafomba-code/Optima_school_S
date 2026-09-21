@extends('layouts.gestion-ecole')

@section('title', 'Inscriptions')

@section('page-title', 'Inscriptions')

@section('page-description', 'Gestion des inscriptions')

@section('content')

{{-- =========================================================
     ALERTES
========================================================= --}}

@if(session('success'))
    <div class="mb-3 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-green-800">
        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-green-100">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <span class="text-sm font-medium">
            {{ session('success') }}
        </span>
    </div>
@endif

@if(session('error'))
    <div class="mb-3 flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-red-800">
        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-red-100">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>

        <span class="text-sm font-medium">
            {{ session('error') }}
        </span>
    </div>
@endif

@if($errors->any())
    <div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
        <p class="text-sm font-semibold">Erreurs :</p>

        <ul class="mt-1 list-disc pl-5 text-xs">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- =========================================================
     HEADER
========================================================= --}}

<div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

    <div class="flex items-center gap-3">

        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>

        <div>
            <h2 class="text-base font-bold leading-tight text-blue-950 sm:text-lg">
                Liste des inscriptions
            </h2>

            <p class="mt-0.5 text-xs text-gray-500">
                Gestion des étudiants inscrits
            </p>
        </div>

    </div>


    <a href="{{ route('inscriptions.create') }}"
       class="inline-flex w-fit items-center gap-2 rounded-lg bg-green-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700">

        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-4 w-4"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 4v16m8-8H4"/>
        </svg>

        <span class="hidden sm:inline">
            Nouvelle inscription
        </span>

        <span class="sm:hidden">
            Ajouter
        </span>

    </a>

</div>


{{-- =========================================================
     STATISTIQUES
========================================================= --}}

<div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-4">

    {{-- TOTAL --}}
    <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-white p-3 shadow-sm">

        <div class="min-w-0">
            <p class="text-xs font-medium text-gray-500">
                Total
            </p>

            <p class="mt-1 text-xl font-bold leading-none text-blue-950">
                {{ $inscriptions->count() }}
            </p>

            <p class="mt-1 text-[11px] text-gray-400">
                inscriptions
            </p>
        </div>

        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>

    </div>


    {{-- CETTE ANNÉE --}}
    <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-white p-3 shadow-sm">

        <div class="min-w-0">
            <p class="text-xs font-medium text-gray-500">
                Cette année
            </p>

            <p class="mt-1 text-xl font-bold leading-none text-blue-950">
                {{ $inscriptions->count() }}
            </p>

            <p class="mt-1 text-[11px] text-gray-400">
                année scolaire
            </p>
        </div>

        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-green-50 text-green-600">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>

    </div>


    {{-- ÉTUDIANTS --}}
    <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-white p-3 shadow-sm">

        <div class="min-w-0">
            <p class="text-xs font-medium text-gray-500">
                Étudiants
            </p>

            <p class="mt-1 text-xl font-bold leading-none text-blue-950">
                {{ $inscriptions->pluck('student_id')->filter()->unique()->count() }}
            </p>

            <p class="mt-1 text-[11px] text-gray-400">
                étudiants inscrits
            </p>
        </div>

        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-5a4 4 0 100-8 4 4 0 000 8zm6 2a3 3 0 10-6 0"/>
            </svg>
        </div>

    </div>


    {{-- CLASSES --}}
    <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-white p-3 shadow-sm">

        <div class="min-w-0">
            <p class="text-xs font-medium text-gray-500">
                Classes
            </p>

            <p class="mt-1 text-xl font-bold leading-none text-blue-950">
                {{ $inscriptions->pluck('class_room_id')->filter()->unique()->count() }}
            </p>

            <p class="mt-1 text-[11px] text-gray-400">
                classes concernées
            </p>
        </div>

        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-green-50 text-green-600">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M3 10l9-6 9 6M5 10v9h14v-9M9 19v-5h6v5"/>
            </svg>
        </div>

    </div>

</div>


{{-- =========================================================
     TABLEAU
========================================================= --}}

<div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">

    {{-- HEADER TABLEAU --}}
    <div class="flex flex-col gap-2 border-b border-gray-100 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h3 class="text-sm font-bold text-blue-950">
                Inscriptions enregistrées
            </h3>

            <p class="mt-0.5 text-xs text-gray-500">
                {{ $inscriptions->count() }} inscription(s) enregistrée(s)
            </p>
        </div>

        <span class="inline-flex w-fit items-center rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
            Établissement #{{ session('establishment_id') }}
        </span>

    </div>


    @if($inscriptions->count())

        {{-- =================================================
             DESKTOP / TABLET
        ================================================== --}}

        <div class="hidden overflow-x-auto md:block">

            <table class="min-w-full divide-y divide-gray-100">

                <thead class="bg-blue-950">

                    <tr>

                        <th class="whitespace-nowrap px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-white">
                            Étudiant
                        </th>

                        <th class="whitespace-nowrap px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-white">
                            Classe
                        </th>

                        <th class="whitespace-nowrap px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-white">
                            Année scolaire
                        </th>

                        <th class="whitespace-nowrap px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-white">
                            Date
                        </th>

                        <th class="whitespace-nowrap px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wide text-white">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @foreach($inscriptions as $inscription)

                        <tr class="transition hover:bg-blue-50/40">

                            {{-- ÉTUDIANT --}}
                            <td class="px-4 py-2.5">

                                <div class="flex items-center gap-3">

                                    {{-- AVATAR --}}
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-700">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M15 19a3 3 0 00-6 0m3-7a4 4 0 100-8 4 4 0 000 8z"/>
                                        </svg>

                                    </div>


                                    <div class="min-w-0">

                                        @if($inscription->student)

                                            <p class="truncate text-sm font-semibold text-blue-950">
                                                {{ $inscription->student->nom }}
                                                {{ $inscription->student->prenom }}
                                            </p>

                                            @if($inscription->student->matricule)
                                                <p class="mt-0.5 text-xs text-gray-400">
                                                    {{ $inscription->student->matricule }}
                                                </p>
                                            @endif

                                        @else

                                            <p class="text-sm text-gray-400">
                                                Étudiant introuvable
                                            </p>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- CLASSE --}}
                            <td class="px-4 py-2.5">

                                @if($inscription->classRoom)

                                    <span class="inline-flex max-w-[220px] truncate rounded-md bg-blue-50 px-2.5 py-1.5 text-xs font-semibold text-blue-800">
                                        {{ $inscription->classRoom->nom_classe ?? $inscription->classRoom->name ?? 'Classe' }}
                                    </span>

                                @else

                                    <span class="text-xs text-gray-400">
                                        Non définie
                                    </span>

                                @endif

                            </td>


                            {{-- ANNÉE --}}
                            <td class="px-4 py-2.5">

                                @if($inscription->schoolYear)

                                    <span class="inline-flex rounded-md bg-green-50 px-2.5 py-1.5 text-xs font-semibold text-green-700">
                                        {{ $inscription->schoolYear->name }}
                                    </span>

                                @else

                                    <span class="text-xs text-gray-400">
                                        Non définie
                                    </span>

                                @endif

                            </td>


                            {{-- DATE --}}
                            <td class="whitespace-nowrap px-4 py-2.5">

                                <span class="text-sm text-gray-600">
                                    {{ $inscription->date_inscription ?? $inscription->created_at?->format('d/m/Y') ?? '—' }}
                                </span>

                            </td>


                            {{-- ACTIONS --}}
                            <td class="px-4 py-2.5">

                                <div class="flex items-center justify-center gap-1.5">

                                    {{-- VOIR --}}
                                    <a href="{{ route('inscriptions.show', $inscription) }}"
                                       title="Voir l'inscription"
                                       aria-label="Voir"
                                       class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-700 transition hover:bg-blue-100 hover:text-blue-800">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>

                                    </a>


                                    {{-- MODIFIER --}}
                                    <a href="{{ route('inscriptions.edit', $inscription) }}"
                                       title="Modifier l'inscription"
                                       aria-label="Modifier"
                                       class="flex h-8 w-8 items-center justify-center rounded-lg bg-yellow-50 text-yellow-700 transition hover:bg-yellow-100 hover:text-yellow-800">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>

                                    </a>


                                    {{-- SUPPRIMER --}}
                                    <form action="{{ route('inscriptions.destroy', $inscription) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Voulez-vous vraiment supprimer cette inscription ?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                title="Supprimer l'inscription"
                                                aria-label="Supprimer"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-700 transition hover:bg-red-100 hover:text-red-800">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h10"/>
                                            </svg>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- =================================================
             MOBILE
        ================================================== --}}

        <div class="divide-y divide-gray-100 md:hidden">

            @foreach($inscriptions as $inscription)

                <div class="p-4">

                    {{-- ÉTUDIANT --}}
                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-700">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M15 19a3 3 0 00-6 0m3-7a4 4 0 100-8 4 4 0 000 8z"/>
                            </svg>

                        </div>

                        <div class="min-w-0 flex-1">

                            @if($inscription->student)

                                <p class="truncate text-sm font-bold text-blue-950">
                                    {{ $inscription->student->nom }}
                                    {{ $inscription->student->prenom }}
                                </p>

                            @else

                                <p class="text-sm text-gray-400">
                                    Étudiant introuvable
                                </p>

                            @endif

                        </div>

                    </div>


                    {{-- INFOS --}}
                    <div class="mt-3 grid grid-cols-2 gap-2">

                        <div class="rounded-lg bg-blue-50 px-3 py-2">

                            <p class="text-[11px] font-medium text-blue-500">
                                Classe
                            </p>

                            <p class="mt-0.5 truncate text-xs font-semibold text-blue-900">

                                @if($inscription->classRoom)
                                    {{ $inscription->classRoom->nom_classe ?? $inscription->classRoom->name ?? 'Classe' }}
                                @else
                                    Non définie
                                @endif

                            </p>

                        </div>


                        <div class="rounded-lg bg-green-50 px-3 py-2">

                            <p class="text-[11px] font-medium text-green-600">
                                Année
                            </p>

                            <p class="mt-0.5 text-xs font-semibold text-green-800">

                                @if($inscription->schoolYear)
                                    {{ $inscription->schoolYear->name }}
                                @else
                                    Non définie
                                @endif

                            </p>

                        </div>

                    </div>


                    {{-- DATE --}}
                    <div class="mt-2 flex items-center justify-between">

                        <span class="text-xs text-gray-500">
                            {{ $inscription->date_inscription ?? $inscription->created_at?->format('d/m/Y') ?? '—' }}
                        </span>


                        {{-- ACTION ICONS --}}
                        <div class="flex items-center gap-1.5">

                            <a href="{{ route('inscriptions.show', $inscription) }}"
                               title="Voir"
                               aria-label="Voir"
                               class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-700">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-4 w-4"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>

                            </a>


                            <a href="{{ route('inscriptions.edit', $inscription) }}"
                               title="Modifier"
                               aria-label="Modifier"
                               class="flex h-8 w-8 items-center justify-center rounded-lg bg-yellow-50 text-yellow-700">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-4 w-4"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>

                            </a>


                            <form action="{{ route('inscriptions.destroy', $inscription) }}"
                                  method="POST"
                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cette inscription ?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        title="Supprimer"
                                        aria-label="Supprimer"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-700">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-4 w-4"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h10"/>
                                    </svg>

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        {{-- =================================================
             AUCUNE INSCRIPTION
        ================================================== --}}

        <div class="px-4 py-10 text-center">

            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>

            </div>

            <h3 class="mt-3 text-sm font-bold text-blue-950">
                Aucune inscription
            </h3>

            <p class="mt-1 text-xs text-gray-500">
                Aucune inscription n'est encore enregistrée.
            </p>

            <a href="{{ route('inscriptions.create') }}"
               class="mt-4 inline-flex items-center gap-2 rounded-lg bg-green-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>

                Nouvelle inscription

            </a>

        </div>

    @endif

</div>

@endsection