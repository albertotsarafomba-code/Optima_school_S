@extends('layouts.gestion-ecole')

@section('title', 'Années scolaires')

@section('page-title', 'Gestion des années scolaires')

@section('page-description', 'Gérez les années scolaires et consultez les données de chaque période scolaire')

@section('content')

    {{-- ============================================================
        MESSAGES
    ============================================================ --}}

    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-800 shadow-sm">
            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-500 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <div>
                    <p class="font-semibold">
                        Opération réussie
                    </p>

                    <p class="text-sm">
                        {{ session('success') }}
                    </p>
                </div>

            </div>
        </div>
    @endif


    @if(session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800 shadow-sm">
            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-500 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>

                <div>
                    <p class="font-semibold">
                        Erreur
                    </p>

                    <p class="text-sm">
                        {{ session('error') }}
                    </p>
                </div>

            </div>
        </div>
    @endif


    @if($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800 shadow-sm">

            <div class="flex items-start gap-3">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 9v4m0 4h.01M10.29 3.86l-8.82 15a2 2 0 001.73 3h17.6a2 2 0 001.73-3l-8.82-15a2 2 0 00-3.46 0z"/>
                    </svg>
                </div>

                <div>
                    <p class="font-semibold">
                        Veuillez corriger les erreurs suivantes :
                    </p>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>

        </div>
    @endif


    {{-- ============================================================
        EN-TÊTE
    ============================================================ --}}

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="flex items-center gap-3">

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002 2z"/>

                    </svg>

                </div>

                <div>

                    <h2 class="text-xl font-bold text-blue-950">
                        Liste des années scolaires
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Consultez et gérez les périodes scolaires de votre établissement.
                    </p>

                </div>

            </div>

        </div>


        {{-- AJOUTER : ADMIN UNIQUEMENT --}}
        @if(auth()->user()->role === 'admin')

            <a href="{{ route('school_years.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-500 px-5 py-3 text-sm font-semibold text-white shadow-md transition duration-200 hover:bg-green-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 4v16m8-8H4"/>

                </svg>

                Ajouter une année scolaire

            </a>

        @endif

    </div>


    {{-- ============================================================
        ANNÉE ACTUELLEMENT CONSULTÉE
    ============================================================ --}}

    @if($selectedSchoolYear)

        <div class="mb-8 overflow-hidden rounded-2xl border border-blue-100 bg-white shadow-sm">

            <div class="border-l-4 border-blue-600 bg-blue-50 p-5">

                <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

                    <div class="flex items-center gap-4">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-950 text-white shadow-sm">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-6 w-6"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002 2z"/>

                            </svg>

                        </div>

                        <div>

                            <p class="text-xs font-bold uppercase tracking-wider text-blue-600">
                                Année actuellement consultée
                            </p>

                            <p class="mt-1 text-xl font-bold text-blue-950">
                                {{ $selectedSchoolYear->name }}
                            </p>

                        </div>

                    </div>


                    @if($activeSchoolYear)

                        <div class="flex items-center gap-3 rounded-xl border border-green-100 bg-green-50 px-4 py-3">

                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-100 text-green-600">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 9.29 9 11.622C17.176 18.29 21 14.591 21 9c0-1.042-.133-2.053-.382-3.016z"/>

                                </svg>

                            </div>

                            <div>

                                <p class="text-xs text-gray-500">
                                    Année active du système
                                </p>

                                <p class="font-bold text-green-700">
                                    {{ $activeSchoolYear->name }}
                                </p>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    @endif


    {{-- ============================================================
        FILTRE ANNÉE SCOLAIRE
    ============================================================ --}}

    <div class="mb-8 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

        <div class="mb-5 flex items-start gap-3">

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 12.414V19a1 1 0 01-.553.894l-4 2A1 1 0 019 21v-8.586L3.293 6.707A1 1 0 013 6V4z"/>

                </svg>

            </div>

            <div>

                <h3 class="font-bold text-blue-950">
                    Filtrer par année scolaire
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Sélectionnez une année pour consulter ses données.
                </p>

            </div>

        </div>


        <form method="POST"
              id="schoolYearFilterForm">

            @csrf

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">

                <select name="school_year_id"
                        id="school_year_id"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 shadow-sm transition focus:border-blue-500 focus:ring-blue-500 sm:max-w-md"
                        onchange="changeSchoolYear(this)">

                    @foreach($schoolYears as $schoolYear)

                        <option value="{{ $schoolYear->id }}"
                                data-url="{{ route('school_years.select', $schoolYear) }}"
                                @selected(
                                    $selectedSchoolYear &&
                                    $selectedSchoolYear->id == $schoolYear->id
                                )>

                            {{ $schoolYear->name }}

                            @if($schoolYear->is_active)
                                — Active
                            @else
                                — Archivée
                            @endif

                        </option>

                    @endforeach

                </select>


                <span class="text-xs text-gray-500">
                    L'année sélectionnée sera utilisée pour la consultation.
                </span>

            </div>

        </form>

    </div>


    {{-- ============================================================
        STATISTIQUES
    ============================================================ --}}

    <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

        {{-- TOTAL --}}
        <div class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition duration-200 hover:-translate-y-0.5 hover:shadow-md">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Total années
                    </p>

                    <p class="mt-2 text-3xl font-bold text-blue-950">
                        {{ $schoolYears->count() }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-700 transition group-hover:bg-blue-950 group-hover:text-white">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002 2z"/>

                    </svg>

                </div>

            </div>

        </div>


        {{-- ANNÉE ACTIVE --}}
        <div class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition duration-200 hover:-translate-y-0.5 hover:shadow-md">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Année active
                    </p>

                    <p class="mt-2 text-xl font-bold text-green-600">
                        {{ $activeSchoolYear?->name ?? 'Aucune' }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-700 transition group-hover:bg-green-500 group-hover:text-white">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 9.29 9 11.622C17.176 18.29 21 14.591 21 9c0-1.042-.133-2.053-.382-3.016z"/>

                    </svg>

                </div>

            </div>

        </div>


        {{-- ARCHIVÉES --}}
        <div class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition duration-200 hover:-translate-y-0.5 hover:shadow-md">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Années archivées
                    </p>

                    <p class="mt-2 text-3xl font-bold text-blue-950">
                        {{ $schoolYears->where('is_active', false)->count() }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-700 transition group-hover:bg-blue-950 group-hover:text-white">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 8h14M5 12h14M5 16h14"/>

                    </svg>

                </div>

            </div>

        </div>


        {{-- CONSULTATION --}}
        <div class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition duration-200 hover:-translate-y-0.5 hover:shadow-md">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Année consultée
                    </p>

                    <p class="mt-2 text-xl font-bold text-blue-600">
                        {{ $selectedSchoolYear?->name ?? 'Aucune' }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-700 transition group-hover:bg-blue-950 group-hover:text-white">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">

                        <circle cx="12"
                                cy="12"
                                r="9"/>

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 7v5l3 2"/>

                    </svg>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        TABLEAU
    ============================================================ --}}

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

        {{-- HEADER TABLEAU --}}
        <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002 2z"/>

                    </svg>

                </div>

                <div>

                    <h3 class="font-bold text-blue-950">
                        Années scolaires enregistrées
                    </h3>

                    <p class="mt-1 text-xs text-gray-500">
                        {{ $schoolYears->count() }} année(s) scolaire(s) enregistrée(s)
                    </p>

                </div>

            </div>


            <div class="inline-flex w-fit items-center gap-2 rounded-xl bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-800">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 8h1m4 0h1M9 12h1m4 0h1M9 16h1m4 0h1"/>

                </svg>

                {{ auth()->user()->establishments()->find(session('establishment_id'))?->nom ?? 'Établissement' }}

            </div>

        </div>


        @if($schoolYears->count())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-100">

                    <thead class="bg-blue-950">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">
                                Année scolaire
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">
                                Date de début
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">
                                Date de fin
                            </th>

                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-white">
                                Statut
                            </th>

                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-white">
                                Consultation
                            </th>

                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-white">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100 bg-white">

                        @foreach($schoolYears as $schoolYear)

                            @php

                                $isSelected =
                                    $selectedSchoolYear &&
                                    $selectedSchoolYear->id == $schoolYear->id;

                            @endphp


                            <tr class="transition duration-150 hover:bg-blue-50/50
                                {{ $isSelected ? 'bg-blue-50/70' : '' }}">


                                {{-- ANNÉE --}}
                                <td class="whitespace-nowrap px-6 py-5">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl
                                            {{ $schoolYear->is_active
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-blue-100 text-blue-700' }}">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-5 w-5"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002 2z"/>

                                            </svg>

                                        </div>


                                        <div>

                                            <p class="font-bold text-blue-950">
                                                {{ $schoolYear->name }}
                                            </p>

                                            @if($isSelected)

                                                <div class="mt-1 flex items-center gap-1.5 text-xs font-medium text-blue-600">

                                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>

                                                    Année consultée

                                                </div>

                                            @else

                                                <p class="mt-1 text-xs text-gray-500">
                                                    Période scolaire
                                                </p>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- DATE DÉBUT --}}
                                <td class="whitespace-nowrap px-6 py-5 text-sm text-gray-700">

                                    <div class="flex items-center gap-2">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4 text-gray-400"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002-2z"/>

                                        </svg>

                                        {{ $schoolYear->start_date
                                            ? $schoolYear->start_date->format('d/m/Y')
                                            : '—' }}

                                    </div>

                                </td>


                                {{-- DATE FIN --}}
                                <td class="whitespace-nowrap px-6 py-5 text-sm text-gray-700">

                                    <div class="flex items-center gap-2">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4 text-gray-400"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002-2z"/>

                                        </svg>

                                        {{ $schoolYear->end_date
                                            ? $schoolYear->end_date->format('d/m/Y')
                                            : '—' }}

                                    </div>

                                </td>


                                {{-- STATUT --}}
                                <td class="whitespace-nowrap px-6 py-5 text-center">

                                    @if($schoolYear->is_active)

                                        <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1.5 text-xs font-bold text-green-800">

                                            <span class="h-2 w-2 rounded-full bg-green-500"></span>

                                            Active

                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-600">

                                            <span class="h-2 w-2 rounded-full bg-gray-400"></span>

                                            Archivée

                                        </span>

                                    @endif

                                </td>


                                {{-- CONSULTATION --}}
                                <td class="whitespace-nowrap px-6 py-5 text-center">

                                    @if($isSelected)

                                        <span class="inline-flex items-center gap-2 rounded-xl bg-blue-100 px-3 py-2 text-xs font-bold text-blue-700">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M5 13l4 4L19 7"/>

                                            </svg>

                                            Sélectionnée

                                        </span>

                                    @else

                                        <form action="{{ route('school_years.select', $schoolYear) }}"
                                              method="POST">

                                            @csrf

                                            <button type="submit"
                                                    class="inline-flex items-center gap-2 rounded-xl bg-blue-100 px-3.5 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-200 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4"
                                                     viewBox="0 0 24 24"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     stroke-width="2">

                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542-7z"/>

                                                </svg>

                                                Consulter

                                            </button>

                                        </form>

                                    @endif

                                </td>


                                {{-- ========================================================
                                    ACTIONS
                                ========================================================= --}}

                                <td class="px-6 py-5">

                                    <div class="flex flex-wrap items-center justify-center gap-2">

                                        {{-- VOIR --}}
                                        <a href="{{ route('school_years.show', $schoolYear) }}"
                                           title="Voir les détails"
                                           class="inline-flex items-center gap-2 rounded-xl bg-blue-100 px-3.5 py-2 text-sm font-semibold text-blue-700 transition duration-200 hover:bg-blue-600 hover:text-white hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7z"/>

                                            </svg>

                                            Voir

                                        </a>


                                        {{-- ACTIONS ADMIN UNIQUEMENT --}}
                                        @if(auth()->user()->role === 'admin')

                                            {{-- MODIFIER --}}
                                            <a href="{{ route('school_years.edit', $schoolYear) }}"
                                               title="Modifier cette année scolaire"
                                               class="inline-flex items-center gap-2 rounded-xl bg-green-100 px-3.5 py-2 text-sm font-semibold text-green-700 transition duration-200 hover:bg-green-500 hover:text-white hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4"
                                                     viewBox="0 0 24 24"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     stroke-width="2">

                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>

                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>

                                                </svg>

                                                Modifier

                                            </a>


                                            {{-- ACTIVER --}}
                                            @if(!$schoolYear->is_active)

                                                <form action="{{ route('school_years.activate', $schoolYear) }}"
                                                      method="POST">

                                                    @csrf

                                                    <button type="submit"
                                                            title="Activer cette année"
                                                            onclick="return confirm('Voulez-vous rendre cette année scolaire active ?');"
                                                            class="inline-flex items-center gap-2 rounded-xl bg-green-100 px-3.5 py-2 text-sm font-semibold text-green-700 transition duration-200 hover:bg-green-600 hover:text-white hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">

                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             class="h-4 w-4"
                                                             viewBox="0 0 24 24"
                                                             fill="none"
                                                             stroke="currentColor"
                                                             stroke-width="2">

                                                            <path stroke-linecap="round"
                                                                  stroke-linejoin="round"
                                                                  d="M5 13l4 4L19 7"/>

                                                        </svg>

                                                        Activer

                                                    </button>

                                                </form>

                                            @endif


                                            {{-- SUPPRIMER --}}
                                            <form action="{{ route('school_years.destroy', $schoolYear) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cette année scolaire ?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        title="Supprimer cette année"
                                                        class="inline-flex items-center gap-2 rounded-xl bg-red-100 px-3.5 py-2 text-sm font-semibold text-red-700 transition duration-200 hover:bg-red-600 hover:text-white hover:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="h-4 w-4"
                                                         viewBox="0 0 24 24"
                                                         fill="none"
                                                         stroke="currentColor"
                                                         stroke-width="2">

                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              d="M3 6h18"/>

                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              d="M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2"/>

                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              d="M19 6l-1 14H6L5 6"/>

                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              d="M10 11v5M14 11v5"/>

                                                    </svg>

                                                    Supprimer

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

            {{-- ========================================================
                AUCUNE ANNÉE
            ========================================================= --}}

            <div class="px-6 py-16 text-center">

                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-10 w-10"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002 2z"/>

                    </svg>

                </div>


                <h3 class="mt-5 text-lg font-bold text-blue-950">
                    Aucune année scolaire
                </h3>


                <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
                    Aucune année scolaire n'est encore enregistrée dans cet établissement.
                </p>


                @if(auth()->user()->role === 'admin')

                    <a href="{{ route('school_years.create') }}"
                       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-green-500 px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-green-600 hover:shadow-lg">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 4v16m8-8H4"/>

                        </svg>

                        Ajouter la première année

                    </a>

                @endif

            </div>

        @endif

    </div>


    {{-- ============================================================
        JAVASCRIPT DU FILTRE
    ============================================================ --}}

    <script>

        function changeSchoolYear(select) {

            const option = select.options[select.selectedIndex];

            const url = option.dataset.url;

            if (!url) {
                return;
            }

            const form = document.getElementById('schoolYearFilterForm');

            form.action = url;

            form.submit();
        }

    </script>

@endsection