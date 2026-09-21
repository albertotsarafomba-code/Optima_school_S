@extends('layouts.gestion-ecole')

@section('title', 'Étudiants')

@section('page-title', 'Gestion des étudiants')

@section(
    'page-description',
    'Gérez les étudiants de votre établissement'
)

@section('content')

{{-- ============================================================
    MESSAGES
============================================================ --}}

@if(session('success'))
    <div class="mb-4 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 shadow-sm">

        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-500 text-white">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="2"
                 stroke="currentColor"
                 class="h-4 w-4">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M5 13l4 4L19 7"/>

            </svg>

        </div>

        <div class="min-w-0">

            <p class="text-sm font-semibold">
                Opération réussie
            </p>

            <p class="text-xs text-green-700">
                {{ session('success') }}
            </p>

        </div>

    </div>
@endif


@if(session('error'))
    <div class="mb-4 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800 shadow-sm">

        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-500 text-white">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="2"
                 stroke="currentColor"
                 class="h-4 w-4">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 9v3.5M12 16h.01"/>

                <circle cx="12"
                        cy="12"
                        r="9"/>

            </svg>

        </div>

        <div class="min-w-0">

            <p class="text-sm font-semibold">
                Erreur
            </p>

            <p class="text-xs text-red-700">
                {{ session('error') }}
            </p>

        </div>

    </div>
@endif


@if($errors->any())

    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800 shadow-sm">

        <p class="mb-1 text-sm font-semibold">
            Veuillez corriger les erreurs :
        </p>

        <ul class="list-disc pl-5 text-xs">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


{{-- ============================================================
    HEADER
============================================================ --}}

<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

    <div class="flex items-center gap-3">

        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="h-5 w-5">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M15 19a6 6 0 00-12 0"/>

                <circle cx="9"
                        cy="8"
                        r="3"/>

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M19 19a5 5 0 00-4-4.9"/>

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M16 5.1a3 3 0 010 5.8"/>

            </svg>

        </div>

        <div>

            <h2 class="text-lg font-bold leading-tight text-blue-950">
                Liste des étudiants
            </h2>

            <p class="mt-0.5 text-xs text-gray-500">
                Étudiants de l'établissement sélectionné
            </p>

        </div>

    </div>


    <a href="{{ route('inscriptions.create') }}"
       class="inline-flex items-center justify-center rounded-lg bg-green-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-600">

        <svg xmlns="http://www.w3.org/2000/svg"
             fill="none"
             viewBox="0 0 24 24"
             stroke-width="2"
             stroke="currentColor"
             class="mr-2 h-4 w-4">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 5v14"/>

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M5 12h14"/>

        </svg>

        Nouvelle inscription

    </a>

</div>


{{-- ============================================================
    ETABLISSEMENT + RECHERCHE
============================================================ --}}

<div class="mb-5 rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">

    <form method="GET"
          action="{{ route('students.index') }}">

        <div class="flex flex-col gap-3 lg:flex-row lg:items-center">

            {{-- ETABLISSEMENT --}}
            <div class="flex shrink-0 items-center gap-3 rounded-lg border border-blue-100 bg-blue-50 px-3 py-2.5">

                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="2"
                         stroke="currentColor"
                         class="h-4 w-4">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-8h6v8"/>

                    </svg>

                </div>

                <div class="leading-tight">

                    <p class="text-xs font-medium uppercase tracking-wide text-blue-600">
                        Établissement
                    </p>

                    <p class="text-sm font-bold text-blue-950">
                        {{ $establishment->nom ?? session('establishment_name') ?? '#' . session('establishment_id') }}
                    </p>

                </div>

            </div>


            {{-- RECHERCHE --}}
            <div class="relative flex-1">

                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="2"
                         stroke="currentColor"
                         class="h-4 w-4 text-gray-400">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>

                    </svg>

                </div>

                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Rechercher un matricule, nom, prénom, classe..."
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-3 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >

            </div>


            {{-- RECHERCHE --}}
            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-blue-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-900">

                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="2"
                     stroke="currentColor"
                     class="mr-2 h-4 w-4">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>

                </svg>

                Rechercher

            </button>


            @if(!empty($search))

                <a
                    href="{{ route('students.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">

                    Réinitialiser

                </a>

            @endif

        </div>

    </form>

</div>


{{-- ============================================================
    STATISTIQUES
============================================================ --}}

<div class="mb-5 grid grid-cols-2 gap-3 lg:grid-cols-4">


    {{-- TOTAL --}}
    <div class="flex items-center justify-between rounded-xl bg-white px-4 py-3.5 shadow-sm ring-1 ring-gray-100">

        <div>

            <p class="text-xs font-medium text-gray-500">
                Total étudiants
            </p>

            <p class="mt-1 text-xl font-bold text-blue-950">
                {{ $statistics['total'] ?? 0 }}
            </p>

        </div>

        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="h-5 w-5">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M15 19a6 6 0 00-12 0"/>

                <circle cx="9"
                        cy="8"
                        r="3"/>

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M19 19a5 5 0 00-4-4.9"/>

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M16 5.1a3 3 0 010 5.8"/>

            </svg>

        </div>

    </div>


    {{-- MASCULINS --}}
    <div class="flex items-center justify-between rounded-xl bg-white px-4 py-3.5 shadow-sm ring-1 ring-gray-100">

        <div>

            <p class="text-xs font-medium text-gray-500">
                Masculins
            </p>

            <p class="mt-1 text-xl font-bold text-blue-950">
                {{ $statistics['masculins'] ?? 0 }}
            </p>

        </div>

        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="h-5 w-5">

                <circle cx="10"
                        cy="8"
                        r="3"/>

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M4 21a6 6 0 0112 0"/>

            </svg>

        </div>

    </div>


    {{-- FEMININS --}}
    <div class="flex items-center justify-between rounded-xl bg-white px-4 py-3.5 shadow-sm ring-1 ring-gray-100">

        <div>

            <p class="text-xs font-medium text-gray-500">
                Féminins
            </p>

            <p class="mt-1 text-xl font-bold text-blue-950">
                {{ $statistics['feminins'] ?? 0 }}
            </p>

        </div>

        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-100 text-green-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="h-5 w-5">

                <circle cx="12"
                        cy="8"
                        r="3"/>

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M6 21a6 6 0 0112 0"/>

            </svg>

        </div>

    </div>


    {{-- AVEC CLASSE --}}
    <div class="flex items-center justify-between rounded-xl bg-white px-4 py-3.5 shadow-sm ring-1 ring-gray-100">

        <div>

            <p class="text-xs font-medium text-gray-500">
                Avec classe
            </p>

            <p class="mt-1 text-xl font-bold text-blue-950">
                {{ $statistics['avec_classe'] ?? 0 }}
            </p>

        </div>

        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-100 text-green-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="h-5 w-5">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M4 19.5A2.5 2.5 0 016.5 17H20"/>

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>

            </svg>

        </div>

    </div>

</div>


{{-- ============================================================
    INFO RECHERCHE
============================================================ --}}

@if(!empty($search))

    <div class="mb-4 flex items-center justify-between rounded-lg border border-blue-100 bg-blue-50 px-4 py-3">

        <div>

            <p class="text-sm font-semibold text-blue-900">
                Résultats de recherche
            </p>

            <p class="text-xs text-blue-700">
                {{ $statistics['resultats'] ?? $students->count() }}
                résultat(s) trouvé(s) pour
                « {{ $search }} »
            </p>

        </div>

        <a
            href="{{ route('students.index') }}"
            class="text-xs font-semibold text-blue-700 hover:text-blue-900">

            Effacer

        </a>

    </div>

@endif


{{-- ============================================================
    TABLEAU
============================================================ --}}

<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">

    <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">

        <div>

            <h3 class="text-sm font-bold text-blue-950">
                Liste des étudiants
            </h3>

            <p class="mt-0.5 text-xs text-gray-500">
                {{ $students->count() }} étudiant(s) affiché(s)
            </p>

        </div>

    </div>


    @if($students->count())

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-100">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Matricule
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Étudiant
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Sexe
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Classe
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Téléphone
                        </th>

                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100 bg-white">

                    @foreach($students as $student)

                        <tr class="transition hover:bg-gray-50">

                            {{-- MATRICULE --}}
                            <td class="whitespace-nowrap px-4 py-3">

                                <span class="rounded-md bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">

                                    {{ $student->matricule ?? '—' }}

                                </span>

                            </td>


                            {{-- ETUDIANT --}}
                            <td class="px-4 py-3">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">

                                        {{ strtoupper(substr($student->prenom ?? $student->nom ?? 'E', 0, 1)) }}

                                    </div>

                                    <div>

                                        <p class="text-sm font-semibold text-gray-800">

                                            {{ $student->nom ?? '' }}
                                            {{ $student->prenom ?? '' }}

                                        </p>

                                        @if(!empty($student->email))

                                            <p class="text-xs text-gray-500">
                                                {{ $student->email }}
                                            </p>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- SEXE --}}
                            <td class="whitespace-nowrap px-4 py-3">

                                @if(in_array($student->sexe, ['M', 'Masculin']))

                                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                        Masculin
                                    </span>

                                @elseif(in_array($student->sexe, ['F', 'Féminin']))

                                    <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                        Féminin
                                    </span>

                                @else

                                    <span class="text-xs text-gray-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- CLASSE --}}
                            <td class="whitespace-nowrap px-4 py-3">

                                @if(!empty($student->classe))

                                    <span class="text-sm font-medium text-gray-700">
                                        {{ $student->classe }}
                                    </span>

                                @else

                                    <span class="text-xs text-gray-400">
                                        Non affecté
                                    </span>

                                @endif

                            </td>


                            {{-- TELEPHONE --}}
                            <td class="whitespace-nowrap px-4 py-3">

                                <span class="text-sm text-gray-600">

                                    {{ $student->telephone ?? $student->phone ?? '—' }}

                                </span>

                            </td>


                            {{-- ACTIONS --}}
                            <td class="whitespace-nowrap px-4 py-3">

                                <div class="flex items-center justify-end gap-2">

                                    @if(Route::has('students.show'))

                                        <a
                                            href="{{ route('students.show', $student) }}"
                                            title="Voir"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke-width="2"
                                                 stroke="currentColor"
                                                 class="h-4 w-4">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"/>

                                                <circle cx="12"
                                                        cy="12"
                                                        r="2.5"/>

                                            </svg>

                                        </a>

                                    @endif


                                    @if(Route::has('students.edit'))

                                        <a
                                            href="{{ route('students.edit', $student) }}"
                                            title="Modifier"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:border-green-200 hover:bg-green-50 hover:text-green-700">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke-width="2"
                                                 stroke="currentColor"
                                                 class="h-4 w-4">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M12 20h9"/>

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/>

                                            </svg>

                                        </a>

                                    @endif


                                    @if(Route::has('students.destroy'))

                                        <form
                                            method="POST"
                                            action="{{ route('students.destroy', $student) }}"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer cet étudiant ?');">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                title="Supprimer"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-700">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke-width="2"
                                                     stroke="currentColor"
                                                     class="h-4 w-4">

                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M3 6h18"/>

                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M8 6V4h8v2"/>

                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M19 6l-1 14H6L5 6"/>

                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M10 11v5M14 11v5"/>

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

    @else

        {{-- EMPTY --}}
        <div class="px-6 py-12 text-center">

            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-blue-50 text-blue-600">

                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.7"
                     stroke="currentColor"
                     class="h-7 w-7">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15 19a6 6 0 00-12 0"/>

                    <circle cx="9"
                            cy="8"
                            r="3"/>

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M19 19a5 5 0 00-4-4.9"/>

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M16 5.1a3 3 0 010 5.8"/>

                </svg>

            </div>

            @if(!empty($search))

                <h3 class="mt-4 text-sm font-bold text-gray-800">
                    Aucun résultat
                </h3>

                <p class="mt-1 text-xs text-gray-500">
                    Aucun étudiant ne correspond à votre recherche.
                </p>

                <a
                    href="{{ route('students.index') }}"
                    class="mt-4 inline-flex items-center rounded-lg bg-blue-950 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-900">

                    Effacer la recherche

                </a>

            @else

                <h3 class="mt-4 text-sm font-bold text-gray-800">
                    Aucun étudiant
                </h3>

                <p class="mt-1 text-xs text-gray-500">
                    Aucun étudiant n'est encore enregistré dans cet établissement.
                </p>

                <a
                    href="{{ route('inscriptions.create') }}"
                    class="mt-4 inline-flex items-center rounded-lg bg-green-500 px-4 py-2 text-sm font-semibold text-white hover:bg-green-600">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="2"
                         stroke="currentColor"
                         class="mr-2 h-4 w-4">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 5v14"/>

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 12h14"/>

                    </svg>

                    Ajouter un étudiant

                </a>

            @endif

        </div>

    @endif

</div>

@endsection