@extends('layouts.gestion-ecole')

@section('title', 'Classes')

@section('page-title', 'Gestion des classes')

@section('page-description', 'Gérez les classes, les salles et les étudiants de votre établissement')

@section('content')

@php
    $totalClasses = $classes->count();
    $totalStudents = $classes->sum('effectif_actuel');
    $totalCapacity = $classes->sum('capacite_reelle');
    $totalLevels = $classes->pluck('niveau')->filter()->unique()->count();

    $classesParNiveau = $classes->groupBy(function ($classe) {
        return $classe->niveau ?: 'Niveau non défini';
    });
@endphp


{{-- ========================================================= --}}
{{-- ALERTES --}}
{{-- ========================================================= --}}

@if(session('success'))
    <div class="mb-5 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">

        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-100">
            <i data-lucide="circle-check" class="h-5 w-5"></i>
        </div>

        <div>
            <p class="text-sm font-semibold">Succès</p>
            <p class="text-sm">{{ session('success') }}</p>
        </div>

    </div>
@endif


@if(session('error'))
    <div class="mb-5 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">

        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100">
            <i data-lucide="circle-alert" class="h-5 w-5"></i>
        </div>

        <div>
            <p class="text-sm font-semibold">Erreur</p>
            <p class="text-sm">{{ session('error') }}</p>
        </div>

    </div>
@endif


@if($errors->any())
    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">

        <div class="flex items-start gap-3">

            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100">
                <i data-lucide="triangle-alert" class="h-5 w-5"></i>
            </div>

            <div>
                <p class="text-sm font-semibold">
                    Une erreur est survenue
                </p>

                <ul class="mt-1 list-disc pl-5 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        </div>

    </div>
@endif


{{-- ========================================================= --}}
{{-- EN-TÊTE --}}
{{-- ========================================================= --}}

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div class="flex min-w-0 items-center gap-4">

        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

            <i data-lucide="school" class="h-6 w-6"></i>

        </div>

        <div class="min-w-0">

            <div class="flex flex-wrap items-center gap-2">

                <h2 class="text-xl font-bold leading-tight text-gray-900">
                    Classes & Salles
                </h2>

                @if(isset($establishment) && $establishment)

                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                        {{ $establishment->nom }}
                    </span>

                @endif

            </div>

            <p class="mt-1 text-sm text-gray-500">
                Organisez les classes, salles et étudiants
            </p>

        </div>

    </div>


    <a
        href="{{ route('class_rooms.create') }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
    >

        <i data-lucide="plus" class="h-5 w-5"></i>

        <span>
            Ajouter une classe
        </span>

    </a>

</div>


{{-- ========================================================= --}}
{{-- STATISTIQUES --}}
{{-- ========================================================= --}}

<div class="mb-6 grid grid-cols-2 gap-4 xl:grid-cols-4">


    {{-- Classes --}}
    <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white px-5 py-4 shadow-sm">

        <div>

            <p class="text-sm font-medium text-gray-500">
                Classes / Salles
            </p>

            <p class="mt-1 text-2xl font-bold text-gray-900">
                {{ $totalClasses }}
            </p>

        </div>

        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

            <i data-lucide="school" class="h-6 w-6"></i>

        </div>

    </div>


    {{-- Étudiants --}}
    <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white px-5 py-4 shadow-sm">

        <div>

            <p class="text-sm font-medium text-gray-500">
                Étudiants inscrits
            </p>

            <p class="mt-1 text-2xl font-bold text-gray-900">
                {{ $totalStudents }}
            </p>

        </div>

        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-green-700">

            <i data-lucide="users" class="h-6 w-6"></i>

        </div>

    </div>


    {{-- Capacité --}}
    <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white px-5 py-4 shadow-sm">

        <div>

            <p class="text-sm font-medium text-gray-500">
                Capacité totale
            </p>

            <p class="mt-1 text-2xl font-bold text-gray-900">
                {{ $totalCapacity }}
            </p>

        </div>

        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">

            <i data-lucide="layers" class="h-6 w-6"></i>

        </div>

    </div>


    {{-- Niveaux --}}
    <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white px-5 py-4 shadow-sm">

        <div>

            <p class="text-sm font-medium text-gray-500">
                Niveaux
            </p>

            <p class="mt-1 text-2xl font-bold text-gray-900">
                {{ $totalLevels }}
            </p>

        </div>

        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">

            <i data-lucide="list-tree" class="h-6 w-6"></i>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- RECHERCHE + ANNÉE --}}
{{-- ========================================================= --}}

<div class="mb-6 rounded-xl border border-gray-100 bg-white p-5 shadow-sm">

    <form
        method="GET"
        action="{{ route('class_rooms.index') }}"
        class="flex flex-col gap-4 xl:flex-row xl:items-end"
    >

        {{-- Recherche --}}
        <div class="min-w-0 flex-1">

            <label
                for="search"
                class="mb-2 block text-sm font-semibold text-gray-700"
            >
                Rechercher une classe
            </label>

            <div class="relative">

                <i
                    data-lucide="search"
                    class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                ></i>

                <input
                    type="text"
                    name="search"
                    id="search"
                    value="{{ $search ?? request('search') }}"
                    placeholder="Niveau, salle, filière ou nom..."
                    class="h-11 w-full rounded-lg border border-gray-200 py-2 pl-11 pr-4 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

            </div>

        </div>


        {{-- Année scolaire --}}
        <div class="xl:w-64">

            <label
                for="school_year_id"
                class="mb-2 block text-sm font-semibold text-gray-700"
            >
                Année scolaire
            </label>

            <select
                name="school_year_id"
                id="school_year_id"
                class="h-11 w-full rounded-lg border border-gray-200 bg-white px-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
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


        {{-- Boutons --}}
        <div class="flex gap-2">

            <button
                type="submit"
                class="inline-flex h-11 items-center gap-2 rounded-lg bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700"
            >

                <i data-lucide="search" class="h-5 w-5"></i>

                Rechercher

            </button>


            <a
                href="{{ route('class_rooms.index') }}"
                title="Réinitialiser"
                class="inline-flex h-11 w-11 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-50 hover:text-gray-900"
            >

                <i data-lucide="rotate-ccw" class="h-5 w-5"></i>

            </a>

        </div>

    </form>

</div>


{{-- ========================================================= --}}
{{-- CLASSES PAR NIVEAU --}}
{{-- ========================================================= --}}

@if($classesParNiveau->count() > 0)

    <div class="space-y-5">

        @foreach($classesParNiveau as $niveau => $classesDuNiveau)

            {{-- ================================================= --}}
            {{-- BLOC NIVEAU --}}
            {{-- ================================================= --}}

            <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">


                {{-- Header niveau --}}
                <div class="flex flex-col gap-3 border-b border-gray-100 bg-blue-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex min-w-0 items-center gap-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white">

                            <i data-lucide="layers-3" class="h-5 w-5"></i>

                        </div>

                        <div class="min-w-0">

                            <div class="flex flex-wrap items-center gap-2">

                                <h3 class="text-lg font-bold text-gray-900">
                                    {{ $niveau }}
                                </h3>

                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">

                                    {{ $classesDuNiveau->count() }}

                                    {{ $classesDuNiveau->count() > 1
                                        ? 'classes'
                                        : 'classe'
                                    }}

                                </span>

                            </div>

                            <p class="mt-1 text-sm text-gray-500">

                                {{ $classesDuNiveau->sum('effectif_actuel') }}

                                étudiants inscrits

                            </p>

                        </div>

                    </div>


                    <div class="flex items-center gap-2 text-sm font-medium text-gray-500">

                        <i data-lucide="users" class="h-5 w-5"></i>

                        Total niveau

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- CARTES DES CLASSES --}}
                {{-- ================================================= --}}

                <div class="grid grid-cols-1 gap-4 p-4 2xl:grid-cols-2">

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
                                    round(($effectif / $capacite) * 100)
                                )
                                : 0;

                            $nombreMatieres = $classe->subjects_count ?? 0;

                        @endphp


                        {{-- ================================================= --}}
                        {{-- CARTE CLASSE --}}
                        {{-- ================================================= --}}

                        <div class="rounded-xl border border-gray-200 bg-white p-5 transition hover:border-blue-300 hover:shadow-md">


                            {{-- Header classe --}}
                            <div class="flex items-start justify-between gap-4">

                                <div class="flex min-w-0 items-center gap-3">

                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-100 text-green-700">

                                        <i data-lucide="door-open" class="h-6 w-6"></i>

                                    </div>

                                    <div class="min-w-0">

                                        <h4 class="truncate text-base font-bold text-gray-900">
                                            {{ $classe->nom_classe }}
                                        </h4>

                                        <div class="mt-1.5 flex flex-wrap items-center gap-2 text-sm text-gray-500">

                                            <span class="inline-flex items-center gap-1.5">

                                                <i data-lucide="door-closed" class="h-4 w-4"></i>

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


                                {{-- Actions --}}
                                <div class="flex shrink-0 items-center gap-1.5">

                                    <a
                                        href="{{ route('class_rooms.show', $classe) }}"
                                        title="Voir"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-green-50 hover:text-green-600"
                                    >

                                        <i data-lucide="eye" class="h-5 w-5"></i>

                                    </a>


                                    <a
                                        href="{{ route('class_rooms.edit', $classe) }}"
                                        title="Modifier"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-blue-50 hover:text-blue-600"
                                    >

                                        <i data-lucide="pencil" class="h-5 w-5"></i>

                                    </a>


                                    <form
                                        method="POST"
                                        action="{{ route('class_rooms.destroy', $classe) }}"
                                        onsubmit="return confirm('Voulez-vous vraiment supprimer la classe {{ $classe->nom_classe }} ?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Supprimer"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-red-50 hover:text-red-600"
                                        >

                                            <i data-lucide="trash-2" class="h-5 w-5"></i>

                                        </button>

                                    </form>

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- INFORMATIONS --}}
                            {{-- ================================================= --}}

                            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">


                                {{-- Enseignant --}}
                                <div class="rounded-xl bg-gray-50 px-4 py-3">

                                    <div class="flex items-center gap-2 text-sm font-medium text-gray-500">

                                        <i data-lucide="user-round" class="h-5 w-5"></i>

                                        Enseignant

                                    </div>

                                    <p class="mt-1.5 truncate text-sm font-semibold text-gray-800">

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


                                {{-- Année scolaire --}}
                                <div class="rounded-xl bg-gray-50 px-4 py-3">

                                    <div class="flex items-center gap-2 text-sm font-medium text-gray-500">

                                        <i data-lucide="calendar-days" class="h-5 w-5"></i>

                                        Année scolaire

                                    </div>

                                    <p class="mt-1.5 truncate text-sm font-semibold text-gray-800">

                                        {{ $classe->schoolYear->name ?? $classe->annee_scolaire ?? '—' }}

                                    </p>

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- EFFECTIF --}}
                            {{-- ================================================= --}}

                            <div class="mt-5">

                                <div class="mb-2 flex items-center justify-between gap-3">

                                    <div class="flex items-center gap-2 text-sm font-medium text-gray-600">

                                        <i data-lucide="users-round" class="h-5 w-5"></i>

                                        Effectif

                                    </div>

                                    <span class="text-sm font-bold text-gray-900">
                                        {{ $effectif }} / {{ $capacite }}
                                    </span>

                                </div>


                                <div class="h-2.5 overflow-hidden rounded-full bg-gray-200">

                                    <div
                                        class="h-full rounded-full transition-all
                                            @if($pourcentage >= 100)
                                                bg-red-500
                                            @elseif($pourcentage >= 80)
                                                bg-orange-500
                                            @else
                                                bg-green-500
                                            @endif"
                                        style="width: {{ $pourcentage }}%"
                                    ></div>

                                </div>


                                <div class="mt-2 flex items-center justify-between gap-3 text-sm">

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
                            {{-- MATIÈRES + ÉTUDIANTS --}}
                            {{-- ================================================= --}}

                            <div class="mt-5 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">


                                {{-- Matières --}}
                                <a
                                    href="{{ route('class_rooms.subjects', $classe) }}"
                                    class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 transition hover:text-blue-800"
                                >

                                    <i data-lucide="book-open" class="h-5 w-5"></i>

                                    <span>

                                        {{ $nombreMatieres }}

                                        {{ $nombreMatieres > 1
                                            ? 'matières'
                                            : 'matière'
                                        }}

                                    </span>

                                </a>


                                {{-- Voir étudiants --}}
                                <a
                                    href="{{ route('class_rooms.show', $classe) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700 transition hover:bg-blue-600 hover:text-white"
                                >

                                    <i data-lucide="users" class="h-5 w-5"></i>

                                    Voir étudiants

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

    <div class="rounded-2xl border border-dashed border-gray-200 bg-white px-5 py-14 text-center shadow-sm">

        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">

            <i data-lucide="school" class="h-10 w-10"></i>

        </div>

        <h3 class="mt-5 text-lg font-bold text-gray-900">
            Aucune classe trouvée
        </h3>

        <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
            Aucune classe ou salle ne correspond aux critères sélectionnés.
        </p>

        <a
            href="{{ route('class_rooms.create') }}"
            class="mt-5 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
        >

            <i data-lucide="plus" class="h-5 w-5"></i>

            Ajouter une classe

        </a>

    </div>

@endif


{{-- ========================================================= --}}
{{-- LUCIDE --}}
{{-- ========================================================= --}}

@push('scripts')

    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

@endpush

@endsection