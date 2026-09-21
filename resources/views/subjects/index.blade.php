@extends('layouts.gestion-ecole')

@section('title', 'Matières')

@section('page-title', 'Gestion des matières')

@section('page-description', 'Gérez les matières de votre établissement et de l’année scolaire active')

@section('content')

@php
$totalSubjects = $subjects->count();


$subjectsWithDescription = $subjects
    ->whereNotNull('description')
    ->where('description', '!=', '')
    ->count();


@endphp

{{-- =========================================================
ALERTES
========================================================= --}}

@if(session('success')) <div class="mb-4 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">


    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-green-100 text-green-700">
        <svg xmlns="http://www.w3.org/2000/svg"
             width="18" height="18"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round">
            <path d="M20 6 9 17l-5-5"/>
        </svg>
    </div>

    <div>
        <p class="text-sm font-semibold">Succès</p>
        <p class="text-xs">{{ session('success') }}</p>
    </div>

</div>


@endif

@if(session('error')) <div class="mb-4 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">


    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600">
        <svg xmlns="http://www.w3.org/2000/svg"
             width="18" height="18"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
    </div>

    <div>
        <p class="text-sm font-semibold">Erreur</p>
        <p class="text-xs">{{ session('error') }}</p>
    </div>

</div>


@endif

@if($errors->any()) <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">


    <div class="flex items-start gap-3">

        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600">
            <svg xmlns="http://www.w3.org/2000/svg"
                 width="18" height="18"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">
                <path d="M10.3 2.9 1.8 17a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 2.9a2 2 0 0 0-3.4 0Z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>

        <div>
            <p class="text-sm font-semibold">
                Veuillez corriger les erreurs
            </p>

            <ul class="mt-1 list-disc space-y-1 pl-5 text-xs">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

    </div>

</div>


@endif

{{-- =========================================================
EN-TÊTE
========================================================= --}}

<div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">


<div class="flex min-w-0 items-center gap-3">

    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg"
             width="22" height="22"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round">
            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
            <path d="M8 7h8"/>
            <path d="M8 11h6"/>
        </svg>
    </div>

    <div class="min-w-0">
        <h2 class="text-lg font-bold leading-tight text-gray-900">
            Matières
        </h2>

        <p class="mt-0.5 text-xs text-gray-500">
            Gestion des matières de l'établissement
        </p>
    </div>

</div>

<a href="{{ route('subjects.create') }}"
   class="inline-flex w-fit items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700">

    <svg xmlns="http://www.w3.org/2000/svg"
         width="17" height="17"
         viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="2"
         stroke-linecap="round"
         stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/>
        <line x1="12" y1="8" x2="12" y2="16"/>
        <line x1="8" y1="12" x2="16" y2="12"/>
    </svg>

    Ajouter une matière
</a>


</div>

{{-- =========================================================
CONTEXTE
========================================================= --}}

<div class="mb-4 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3">


<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

    <div class="flex min-w-0 items-center gap-3">

        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white text-blue-700 shadow-sm">

            <svg xmlns="http://www.w3.org/2000/svg"
                 width="19" height="19"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">
                <path d="M3 21h18"/>
                <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/>
                <path d="M9 7h1"/>
                <path d="M14 7h1"/>
                <path d="M9 11h1"/>
                <path d="M14 11h1"/>
                <path d="M9 15h1"/>
                <path d="M14 15h1"/>
            </svg>

        </div>

        <div class="min-w-0">

            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600">
                Établissement actif
            </p>

            <p class="truncate text-sm font-bold text-gray-900">
                {{ $establishment->nom }}
            </p>

        </div>

    </div>

    <div class="flex items-center gap-2 rounded-lg bg-white px-3 py-2 shadow-sm">

        <svg xmlns="http://www.w3.org/2000/svg"
             width="18" height="18"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round"
             class="text-green-600">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>

        <div>
            <p class="text-[10px] font-medium uppercase tracking-wide text-gray-400">
                Année scolaire
            </p>

            <p class="text-xs font-bold text-gray-800">
                {{ $schoolYear?->name ?? 'Aucune année active' }}
            </p>
        </div>

    </div>

</div>


</div>

{{-- =========================================================
STATISTIQUES
========================================================= --}}

<div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-3">


{{-- TOTAL --}}
<div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white p-4 shadow-sm">

    <div>
        <p class="text-xs font-medium text-gray-500">
            Total matières
        </p>

        <p class="mt-1 text-2xl font-bold text-gray-900">
            {{ $totalSubjects }}
        </p>

        <p class="mt-1 text-[11px] text-gray-400">
            Pour cette année
        </p>
    </div>

    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

        <svg xmlns="http://www.w3.org/2000/svg"
             width="20" height="20"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round">
            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
        </svg>

    </div>

</div>


{{-- DESCRIPTION --}}
<div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white p-4 shadow-sm">

    <div>
        <p class="text-xs font-medium text-gray-500">
            Avec description
        </p>

        <p class="mt-1 text-2xl font-bold text-gray-900">
            {{ $subjectsWithDescription }}
        </p>

        <p class="mt-1 text-[11px] text-gray-400">
            Matières documentées
        </p>
    </div>

    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-700">

        <svg xmlns="http://www.w3.org/2000/svg"
             width="20" height="20"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/>
            <path d="M14 2v6h6"/>
            <line x1="8" y1="13" x2="16" y2="13"/>
            <line x1="8" y1="17" x2="14" y2="17"/>
        </svg>

    </div>

</div>


{{-- ANNÉE --}}
<div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white p-4 shadow-sm">

    <div class="min-w-0">

        <p class="text-xs font-medium text-gray-500">
            Année scolaire
        </p>

        <p class="mt-1 truncate text-sm font-bold text-gray-900">
            {{ $schoolYear?->name ?? 'Non définie' }}
        </p>

        <p class="mt-1 text-[11px] text-gray-400">
            Année actuellement sélectionnée
        </p>

    </div>

    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-green-100 text-green-700">

        <svg xmlns="http://www.w3.org/2000/svg"
             width="20" height="20"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>

    </div>

</div>


</div>

{{-- =========================================================
LISTE
========================================================= --}}

<div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">


<div class="flex flex-col gap-2 border-b border-gray-100 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">

    <div>
        <h3 class="text-sm font-bold text-gray-900">
            Liste des matières
        </h3>

        <p class="mt-0.5 text-xs text-gray-500">
            Matières saisies par l’administrateur
        </p>
    </div>

    <div class="flex items-center gap-2 text-xs font-medium text-gray-500">

        <svg xmlns="http://www.w3.org/2000/svg"
             width="16" height="16"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round">
            <line x1="8" y1="6" x2="21" y2="6"/>
            <line x1="8" y1="12" x2="21" y2="12"/>
            <line x1="8" y1="18" x2="21" y2="18"/>
            <line x1="3" y1="6" x2="3.01" y2="6"/>
            <line x1="3" y1="12" x2="3.01" y2="12"/>
            <line x1="3" y1="18" x2="3.01" y2="18"/>
        </svg>

        {{ $totalSubjects }} matière(s)

    </div>

</div>


@if($subjects->count())

    {{-- =================================================
         DESKTOP
    ================================================== --}}

    <div class="hidden overflow-x-auto md:block">

        <table class="min-w-full divide-y divide-gray-100">

            <thead class="bg-blue-950">

                <tr>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-white">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="15" height="15"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path d="M4 4h16v16H4z"/>
                                <path d="M8 8h8"/>
                                <path d="M8 12h8"/>
                                <path d="M8 16h5"/>
                            </svg>
                            Code
                        </div>
                    </th>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-white">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="15" height="15"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                            </svg>
                            Matière
                        </div>
                    </th>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-white">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="15" height="15"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/>
                                <path d="M14 2v6h6"/>
                            </svg>
                            Description
                        </div>
                    </th>

                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-white">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-gray-100 bg-white">

                @foreach($subjects as $subject)

                    <tr class="transition hover:bg-blue-50/40">

                        {{-- CODE --}}
                        <td class="whitespace-nowrap px-4 py-3">

                            <span class="inline-flex items-center gap-1.5 rounded-md bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-700">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="13" height="13"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="M4 4h16v16H4z"/>
                                </svg>

                                {{ $subject->code ?: '—' }}

                            </span>

                        </td>


                        {{-- MATIÈRE --}}
                        <td class="px-4 py-3">

                            <div class="flex items-center gap-3">

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="19" height="19"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $subject->nom }}
                                    </p>

                                    <p class="mt-0.5 text-[11px] text-gray-400">
                                        {{ $subject->schoolYear?->name ?? 'Année non définie' }}
                                    </p>

                                </div>

                            </div>

                        </td>


                        {{-- DESCRIPTION --}}
                        <td class="max-w-lg px-4 py-3">

                            @if($subject->description)

                                <p class="line-clamp-2 text-xs leading-5 text-gray-600">
                                    {{ $subject->description }}
                                </p>

                            @else

                                <span class="inline-flex items-center gap-1.5 text-xs italic text-gray-400">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="14" height="14"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="8" y1="12" x2="16" y2="12"/>
                                    </svg>

                                    Aucune description

                                </span>

                            @endif

                        </td>


                        {{-- ACTIONS --}}
                        <td class="whitespace-nowrap px-4 py-3">

                            <div class="flex items-center justify-center gap-2">

                                {{-- VOIR --}}
                                <a href="{{ route('subjects.show', $subject) }}"
                                   title="Voir"
                                   aria-label="Voir"
                                   class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-700 transition hover:bg-blue-100">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="17" height="17"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>

                                </a>


                                {{-- MODIFIER --}}
                                <a href="{{ route('subjects.edit', $subject) }}"
                                   title="Modifier"
                                   aria-label="Modifier"
                                   class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-green-50 text-green-700 transition hover:bg-green-100">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="17" height="17"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path d="M12 20h9"/>
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                    </svg>

                                </a>


                                {{-- SUPPRIMER --}}
                                <form action="{{ route('subjects.destroy', $subject) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Voulez-vous vraiment supprimer cette matière ?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            title="Supprimer"
                                            aria-label="Supprimer"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 transition hover:bg-red-100">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             width="17" height="17"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2"
                                             stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="M3 6h18"/>
                                            <path d="M8 6V4h8v2"/>
                                            <path d="M19 6l-1 15H6L5 6"/>
                                            <path d="M10 11v6"/>
                                            <path d="M14 11v6"/>
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

        @foreach($subjects as $subject)

            <div class="p-4">

                <div class="flex items-start gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="19" height="19"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                        </svg>

                    </div>

                    <div class="min-w-0 flex-1">

                        <div class="flex items-start justify-between gap-2">

                            <div class="min-w-0">

                                <h4 class="truncate text-sm font-bold text-gray-900">
                                    {{ $subject->nom }}
                                </h4>

                                <div class="mt-1 flex items-center gap-1.5 text-xs text-gray-500">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="13" height="13"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path d="M4 4h16v16H4z"/>
                                    </svg>

                                    {{ $subject->code ?: 'Sans code' }}

                                </div>

                            </div>

                            <span class="shrink-0 rounded-md bg-green-50 px-2 py-1 text-[10px] font-semibold text-green-700">
                                {{ $subject->schoolYear?->name ?? '—' }}
                            </span>

                        </div>


                        @if($subject->description)

                            <div class="mt-3 flex gap-2 rounded-lg bg-gray-50 px-3 py-2">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="15" height="15"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="mt-0.5 shrink-0 text-gray-400">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/>
                                    <path d="M14 2v6h6"/>
                                </svg>

                                <p class="text-xs leading-5 text-gray-600">
                                    {{ $subject->description }}
                                </p>

                            </div>

                        @endif


                        <div class="mt-3 flex items-center justify-end gap-2 border-t border-gray-100 pt-3">

                            <a href="{{ route('subjects.show', $subject) }}"
                               title="Voir"
                               aria-label="Voir"
                               class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-700">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="17" height="17"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>

                            </a>

                            <a href="{{ route('subjects.edit', $subject) }}"
                               title="Modifier"
                               aria-label="Modifier"
                               class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-green-50 text-green-700">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="17" height="17"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="M12 20h9"/>
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                </svg>

                            </a>

                            <form action="{{ route('subjects.destroy', $subject) }}"
                                  method="POST"
                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cette matière ?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        title="Supprimer"
                                        aria-label="Supprimer"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="17" height="17"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path d="M3 6h18"/>
                                        <path d="M8 6V4h8v2"/>
                                        <path d="M19 6l-1 15H6L5 6"/>
                                        <path d="M10 11v6"/>
                                        <path d="M14 11v6"/>
                                    </svg>

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

@else

    {{-- =================================================
         AUCUNE MATIÈRE
    ================================================== --}}

    <div class="px-4 py-12 text-center">

        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">

            <svg xmlns="http://www.w3.org/2000/svg"
                 width="30" height="30"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="1.8"
                 stroke-linecap="round"
                 stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                <path d="M8 7h8"/>
                <path d="M8 11h6"/>
            </svg>

        </div>

        <h3 class="mt-4 text-sm font-bold text-gray-900">
            Aucune matière enregistrée
        </h3>

        <p class="mx-auto mt-1 max-w-md text-xs leading-5 text-gray-500">
            L’administrateur n’a encore enregistré aucune matière
            pour l’année scolaire
            <span class="font-semibold">
                {{ $schoolYear?->name ?? 'courante' }}
            </span>.
        </p>

        <a href="{{ route('subjects.create') }}"
           class="mt-4 inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                 width="17" height="17"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="16"/>
                <line x1="8" y1="12" x2="16" y2="12"/>
            </svg>

            Ajouter une matière

        </a>

    </div>

@endif


</div>

{{-- =========================================================
INFORMATION
========================================================= --}}

<div class="mt-3 flex items-start gap-3 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3">


<div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white text-blue-600 shadow-sm">

    <svg xmlns="http://www.w3.org/2000/svg"
         width="17" height="17"
         viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="2"
         stroke-linecap="round"
         stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/>
        <line x1="12" y1="16" x2="12" y2="12"/>
        <line x1="12" y1="8" x2="12.01" y2="8"/>
    </svg>

</div>

<p class="text-xs leading-5 text-blue-700">

    Les matières affichées correspondent uniquement à
    <span class="font-semibold">
        l’établissement sélectionné
    </span>
    et à
    <span class="font-semibold">
        l’année scolaire sélectionnée
    </span>.
    Elles sont saisies et gérées par l’administrateur.

</p>


</div>

@endsection
