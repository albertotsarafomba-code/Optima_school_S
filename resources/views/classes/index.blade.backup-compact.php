@extends('layouts.gestion-ecole')

@section('title', 'Classes')

@section('page-title', 'Gestion des classes')

@section('page-description', 'Gérez les classes, les salles et les étudiants de votre établissement')

@section('content')

{{-- ========================================================= --}}
{{-- ALERTES --}}
{{-- ========================================================= --}}

@if(session('success'))
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-800">
        <i data-lucide="circle-check" class="mt-0.5 h-5 w-5 shrink-0"></i>

        <div class="text-sm font-medium">
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">
        <i data-lucide="circle-alert" class="mt-0.5 h-5 w-5 shrink-0"></i>

        <div class="text-sm font-medium">
            {{ session('error') }}
        </div>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">

        <div class="flex items-start gap-3">

            <i data-lucide="circle-alert" class="mt-0.5 h-5 w-5 shrink-0"></i>

            <div>
                <p class="text-sm font-semibold">
                    Une erreur est survenue
                </p>

                <ul class="mt-2 space-y-1 text-sm">
                    @foreach($errors->all() as $error)
                        <li>
                            • {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>

    </div>
@endif


{{-- ========================================================= --}}
{{-- EN-TÊTE --}}
{{-- ========================================================= --}}

<div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

    <div>

        <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">

            <i data-lucide="building-2" class="h-4 w-4"></i>

            <span>
                {{ $establishment->nom ?? 'Établissement' }}
            </span>

        </div>

        <h2 class="text-2xl font-bold text-gray-900">
            Classes & Salles
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Organisez les classes par niveau et consultez les étudiants de chaque salle.
        </p>

    </div>


    {{-- Ajouter --}}
    <a
        href="{{ route('class_rooms.create') }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
    >

        <i data-lucide="plus" class="h-5 w-5"></i>

        Ajouter une classe

    </a>

</div>


{{-- ========================================================= --}}
{{-- STATISTIQUES --}}
{{-- ========================================================= --}}

<div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

    {{-- Classes / salles --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Classes / Salles
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $classes->count() }}
                </p>

            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-600">

                <i data-lucide="school" class="h-6 w-6"></i>

            </div>

        </div>

    </div>


    {{-- Étudiants --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Étudiants inscrits
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $classes->sum('effectif_actuel') }}
                </p>

            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-600">

                <i data-lucide="users" class="h-6 w-6"></i>

            </div>

        </div>

    </div>


    {{-- Capacité --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Capacité totale
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $classes->sum('capacite_reelle') }}
                </p>

            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">

                <i data-lucide="layers" class="h-6 w-6"></i>

            </div>

        </div>

    </div>


    {{-- Niveaux --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Niveaux
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $classes->pluck('niveau')->filter()->unique()->count() }}
                </p>

            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">

                <i data-lucide="list-tree" class="h-6 w-6"></i>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- FILTRES --}}
{{-- ========================================================= --}}

<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

    <div class="mb-4 flex items-center gap-2">

        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-600">

            <i data-lucide="filter" class="h-5 w-5"></i>

        </div>

        <div>

            <h3 class="font-semibold text-gray-900">
                Rechercher une classe
            </h3>

            <p class="text-xs text-gray-500">
                Filtrez les classes par niveau, salle, filière ou nom.
            </p>

        </div>

    </div>


    <form
        method="GET"
        action="{{ route('class_rooms.index') }}"
    >

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            {{-- Recherche --}}
            <div class="md:col-span-2">

                <label
                    for="search"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Recherche
                </label>

                <div class="relative">

                    <i
                        data-lucide="search"
                        class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                    ></i>

                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ $search ?? request('search') }}"
                        placeholder="Niveau, salle, filière ou nom..."
                        class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-4 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>

            </div>


            {{-- Année scolaire --}}
            <div>

                <label
                    for="school_year_id"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Année scolaire
                </label>

                <select
                    name="school_year_id"
                    id="school_year_id"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

                    <option value="">
                        Toutes les années
                    </option>

                    @foreach($schoolYears as $schoolYear)

                        <option
                            value="{{ $schoolYear->id }}"
                            @selected(($schoolYearId ?? request('school_year_id')) == $schoolYear->id)
                        >
                            {{ $schoolYear->name }}

                            @if($schoolYear->is_active)
                                — Active
                            @endif

                        </option>

                    @endforeach

                </select>

            </div>

        </div>


        {{-- Boutons --}}
        <div class="mt-4 flex flex-wrap gap-3">

            <button
                type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
            >

                <i data-lucide="search" class="h-4 w-4"></i>

                Rechercher

            </button>


            <a
                href="{{ route('class_rooms.index') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
            >

                <i data-lucide="rotate-ccw" class="h-4 w-4"></i>

                Réinitialiser

            </a>

        </div>

    </form>

</div>


{{-- ========================================================= --}}
{{-- ORGANISATION PAR NIVEAU --}}
{{-- ========================================================= --}}

@php

    $classesParNiveau = $classes->groupBy(function ($classe) {

        return $classe->niveau ?: 'Niveau non défini';

    });

@endphp


@if($classesParNiveau->count() > 0)

    <div class="space-y-8">

        @foreach($classesParNiveau as $niveau => $classesDuNiveau)

            {{-- ================================================= --}}
            {{-- NIVEAU --}}
            {{-- ================================================= --}}

            <section
                class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm"
            >

                {{-- En-tête niveau --}}
                <div class="border-b border-gray-200 bg-gradient-to-r from-blue-50 to-green-50 px-6 py-5">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        <div class="flex items-center gap-4">

                            {{-- Icône niveau --}}
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm">

                                <i data-lucide="layers-3" class="h-6 w-6"></i>

                            </div>


                            <div>

                                <div class="flex items-center gap-3">

                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ $niveau }}
                                    </h3>

                                    <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">

                                        {{ $classesDuNiveau->count() }}

                                        {{ $classesDuNiveau->count() > 1 ? 'salles' : 'salle' }}

                                    </span>

                                </div>

                                <p class="mt-1 text-sm text-gray-500">

                                    Classes et salles du niveau
                                    {{ $niveau }}

                                </p>

                            </div>

                        </div>


                        {{-- Total étudiants du niveau --}}
                        <div class="flex items-center gap-2 text-sm text-gray-600">

                            <i data-lucide="users" class="h-4 w-4"></i>

                            <span>
                                {{ $classesDuNiveau->sum('effectif_actuel') }}
                                étudiants
                            </span>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- SALLES --}}
                {{-- ================================================= --}}

                <div class="grid grid-cols-1 gap-5 p-6 xl:grid-cols-2">

                    @foreach($classesDuNiveau as $classe)

                        @php

                            $effectif = (int) ($classe->effectif_actuel ?? 0);

                            $capacite = (int) (
                                $classe->capacite_reelle
                                ?? $classe->capacite
                                ?? $classe->effectif_maximum
                                ?? 0
                            );

                            $placesRestantes = max(
                                0,
                                $capacite - $effectif
                            );

                            $pourcentage = $capacite > 0
                                ? min(
                                    100,
                                    round(
                                        ($effectif / $capacite) * 100
                                    )
                                )
                                : 0;

                        @endphp


                        {{-- ================================================= --}}
                        {{-- CARTE SALLE --}}
                        {{-- ================================================= --}}

                        <div
                            class="rounded-2xl border border-gray-200 bg-white p-5 transition duration-200 hover:border-blue-200 hover:shadow-md"
                        >

                            {{-- ================================================= --}}
                            {{-- HEADER SALLE --}}
                            {{-- ================================================= --}}

                            <div class="flex items-start justify-between gap-4">

                                <div class="flex min-w-0 items-start gap-4">

                                    {{-- Icône salle --}}
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100 text-green-600">

                                        <i
                                            data-lucide="door-open"
                                            class="h-6 w-6"
                                        ></i>

                                    </div>


                                    <div class="min-w-0">

                                        {{-- Nom classe généré --}}
                                        <h4 class="truncate text-lg font-bold text-gray-900">

                                            {{ $classe->nom_classe }}

                                        </h4>


                                        {{-- Informations salle --}}
                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-sm text-gray-500">

                                            <span class="inline-flex items-center gap-1">

                                                <i
                                                    data-lucide="door-closed"
                                                    class="h-4 w-4"
                                                ></i>

                                                {{ $classe->salle ?: 'Salle non définie' }}

                                            </span>


                                            @if($classe->filiere)

                                                <span class="text-gray-300">
                                                    •
                                                </span>

                                                <span>
                                                    {{ $classe->filiere }}
                                                </span>

                                            @endif

                                        </div>

                                    </div>

                                </div>


                                {{-- ================================================= --}}
                                {{-- ACTIONS --}}
                                {{-- ================================================= --}}

                                <div class="flex shrink-0 items-center gap-1">

                                    {{-- VOIR --}}
                                    <a
                                        href="{{ route('class_rooms.show', $classe) }}"
                                        title="Voir la classe et les étudiants"
                                        aria-label="Voir la classe et les étudiants"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-green-50 hover:text-green-600"
                                    >

                                        <i
                                            data-lucide="eye"
                                            class="h-4 w-4"
                                        ></i>

                                    </a>


                                    {{-- MODIFIER --}}
                                    <a
                                        href="{{ route('class_rooms.edit', $classe) }}"
                                        title="Modifier la classe"
                                        aria-label="Modifier la classe"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-blue-50 hover:text-blue-600"
                                    >

                                        <i
                                            data-lucide="pencil"
                                            class="h-4 w-4"
                                        ></i>

                                    </a>


                                    {{-- SUPPRIMER --}}
                                    <form
                                        method="POST"
                                        action="{{ route('class_rooms.destroy', $classe) }}"
                                        onsubmit="return confirm('Voulez-vous vraiment supprimer la classe {{ $classe->nom_classe }} ?');"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Supprimer la classe"
                                            aria-label="Supprimer la classe"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-red-50 hover:text-red-600"
                                        >

                                            <i
                                                data-lucide="trash-2"
                                                class="h-4 w-4"
                                            ></i>

                                        </button>

                                    </form>

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- INFORMATIONS --}}
                            {{-- ================================================= --}}

                            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">

                                {{-- Enseignant --}}
                                <div class="rounded-xl bg-gray-50 p-3">

                                    <div class="flex items-center gap-2 text-xs font-medium text-gray-500">

                                        <i
                                            data-lucide="user-round"
                                            class="h-4 w-4"
                                        ></i>

                                        Enseignant

                                    </div>


                                    <p class="mt-1 truncate text-sm font-semibold text-gray-900">

                                        @if($classe->teacher)

                                            {{ $classe->teacher->prenom }}
                                            {{ $classe->teacher->nom }}

                                        @else

                                            <span class="font-normal text-gray-400">
                                                Non affecté
                                            </span>

                                        @endif

                                    </p>

                                </div>


                                {{-- Année --}}
                                <div class="rounded-xl bg-gray-50 p-3">

                                    <div class="flex items-center gap-2 text-xs font-medium text-gray-500">

                                        <i
                                            data-lucide="calendar-days"
                                            class="h-4 w-4"
                                        ></i>

                                        Année scolaire

                                    </div>


                                    <p class="mt-1 text-sm font-semibold text-gray-900">

                                        {{ $classe->schoolYear->name ?? $classe->annee_scolaire ?? '—' }}

                                    </p>

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- EFFECTIF --}}
                            {{-- ================================================= --}}

                            <div class="mt-5">

                                <div class="mb-2 flex items-center justify-between">

                                    <div class="flex items-center gap-2">

                                        <i
                                            data-lucide="users-round"
                                            class="h-4 w-4 text-gray-500"
                                        ></i>

                                        <span class="text-sm font-medium text-gray-700">
                                            Effectif
                                        </span>

                                    </div>


                                    <span class="text-sm font-bold text-gray-900">

                                        {{ $effectif }}

                                        /

                                        {{ $capacite }}

                                    </span>

                                </div>


                                {{-- Progression --}}
                                <div class="h-2.5 overflow-hidden rounded-full bg-gray-200">

                                    <div
                                        class="h-full rounded-full transition-all duration-500

                                            @if($pourcentage >= 100)
                                                bg-red-500
                                            @elseif($pourcentage >= 80)
                                                bg-orange-500
                                            @else
                                                bg-green-500
                                            @endif
                                        "
                                        style="width: {{ $pourcentage }}%"
                                    ></div>

                                </div>


                                <div class="mt-2 flex items-center justify-between text-xs">

                                    <span class="text-gray-500">

                                        {{ $pourcentage }}% occupé

                                    </span>


                                    @if($placesRestantes > 0)

                                        <span class="font-semibold text-green-600">

                                            {{ $placesRestantes }}

                                            {{ $placesRestantes > 1
                                                ? 'places restantes'
                                                : 'place restante'
                                            }}

                                        </span>

                                    @else

                                        <span class="font-semibold text-red-600">

                                            Classe complète

                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- MATIÈRES --}}
                            {{-- ================================================= --}}

                            <div class="mt-5 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">

                                <div class="flex items-center gap-2 text-sm text-gray-600">

                                    <i
                                        data-lucide="book-open"
                                        class="h-4 w-4"
                                    ></i>

                                    @php
                                        $nombreMatieres = $classe->subjects_count ?? 0;
                                    @endphp

                                    <span>

                                        {{ $nombreMatieres }}

                                        {{ $nombreMatieres > 1
                                            ? 'matières'
                                            : 'matière'
                                        }}

                                    </span>

                                </div>


                                <a
                                    href="{{ route('class_rooms.subjects', $classe) }}"
                                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 transition hover:text-blue-800"
                                >

                                    Gérer les matières

                                    <i
                                        data-lucide="arrow-right"
                                        class="h-4 w-4"
                                    ></i>

                                </a>

                            </div>


                            {{-- ================================================= --}}
                            {{-- ACTION PRINCIPALE : VOIR --}}
                            {{-- ================================================= --}}

                            <div class="mt-4">

                                <a
                                    href="{{ route('class_rooms.show', $classe) }}"
                                    class="group flex w-full items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700 transition hover:border-blue-600 hover:bg-blue-600 hover:text-white"
                                >

                                    <i
                                        data-lucide="eye"
                                        class="h-5 w-5"
                                    ></i>

                                    <span>
                                        Voir les étudiants
                                    </span>

                                    <i
                                        data-lucide="arrow-right"
                                        class="h-4 w-4 transition-transform group-hover:translate-x-1"
                                    ></i>

                                </a>

                            </div>

                        </div>

                    @endforeach

                </div>

            </section>

        @endforeach

    </div>


@else

    {{-- ========================================================= --}}
    {{-- AUCUNE CLASSE --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center shadow-sm">

        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">

            <i
                data-lucide="school"
                class="h-8 w-8"
            ></i>

        </div>


        <h3 class="mt-5 text-lg font-bold text-gray-900">

            Aucune classe trouvée

        </h3>


        <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">

            Aucune classe ou salle ne correspond aux critères sélectionnés.
            Commencez par créer une nouvelle classe.

        </p>


        <a
            href="{{ route('class_rooms.create') }}"
            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
        >

            <i
                data-lucide="plus"
                class="h-5 w-5"
            ></i>

            Ajouter une classe

        </a>

    </div>

@endif


{{-- ========================================================= --}}
{{-- LUCIDE --}}
{{-- ========================================================= --}}

<script src="https://unpkg.com/lucide@latest"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>

@endsection
