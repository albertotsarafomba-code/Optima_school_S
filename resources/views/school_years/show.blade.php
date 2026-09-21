@extends('layouts.gestion-ecole')

@section('title', 'Détails année scolaire')

@section('page-title', 'Année scolaire')

@section('page-description', 'Détails et gestion de l’année scolaire sélectionnée')

@section('content')

{{-- ========================================================= --}}
{{-- EN-TÊTE --}}
{{-- ========================================================= --}}

<div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

    <div>

        <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">

            <a href="{{ route('school_years.index') }}"
               class="transition hover:text-blue-600">
                Années scolaires
            </a>

            <svg class="h-4 w-4"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5l7 7-7 7"/>

            </svg>

            <span class="font-medium text-gray-700">
                {{ $schoolYear->name }}
            </span>

        </div>


        <h2 class="text-2xl font-bold tracking-tight text-gray-900">
            {{ $schoolYear->name }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Informations détaillées de cette année scolaire.
        </p>

    </div>


    <div class="flex flex-wrap items-center gap-3">

        {{-- RETOUR --}}
        <a href="{{ route('school_years.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-300">

            <svg class="h-5 w-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 19l-7-7 7-7"/>

            </svg>

            Retour

        </a>


        {{-- MODIFIER : ADMIN UNIQUEMENT --}}
        @if(auth()->user()->role === 'admin')

            <a href="{{ route('school_years.edit', $schoolYear) }}"
               class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">

                <svg class="h-5 w-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>

                </svg>

                Modifier

            </a>

        @endif

    </div>

</div>


{{-- ========================================================= --}}
{{-- CARTE PRINCIPALE --}}
{{-- ========================================================= --}}

<div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

    {{-- HEADER DE LA CARTE --}}
    <div class="bg-blue-950 px-6 py-6 sm:px-8">

        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex items-center gap-4">

                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/20">

                    <svg class="h-7 w-7 text-white"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2v12a2 2 0 002 2z"/>

                    </svg>

                </div>


                <div>

                    <p class="text-sm font-medium text-blue-200">
                        Année scolaire
                    </p>

                    <h3 class="mt-1 text-2xl font-bold text-white">
                        {{ $schoolYear->name }}
                    </h3>

                </div>

            </div>


            {{-- STATUT --}}
            @if($schoolYear->is_active)

                <div class="inline-flex w-fit items-center gap-2 rounded-full bg-green-500/20 px-4 py-2 text-sm font-semibold text-green-100 ring-1 ring-green-400/30">

                    <span class="h-2.5 w-2.5 rounded-full bg-green-400"></span>

                    Année active

                </div>

            @else

                <div class="inline-flex w-fit items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-blue-100 ring-1 ring-white/20">

                    <span class="h-2.5 w-2.5 rounded-full bg-gray-300"></span>

                    Année inactive

                </div>

            @endif

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- INFORMATIONS --}}
    {{-- ===================================================== --}}

    <div class="p-6 sm:p-8">

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- DATE DÉBUT --}}
            <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-5">

                <div class="flex items-start gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white">

                        <svg class="h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002 2z"/>

                        </svg>

                    </div>


                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">
                            Date de début
                        </p>

                        <p class="mt-1 text-lg font-bold text-gray-900">
                            {{ $schoolYear->start_date ? $schoolYear->start_date->format('d/m/Y') : '—' }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- DATE FIN --}}
            <div class="rounded-2xl border border-green-100 bg-green-50/60 p-5">

                <div class="flex items-start gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-600 text-white">

                        <svg class="h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002-2z"/>

                        </svg>

                    </div>


                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-green-600">
                            Date de fin
                        </p>

                        <p class="mt-1 text-lg font-bold text-gray-900">
                            {{ $schoolYear->end_date ? $schoolYear->end_date->format('d/m/Y') : '—' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- STATUT --}}
        {{-- ================================================= --}}

        <div class="mt-6 rounded-2xl border border-gray-200 bg-gray-50 p-5">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl
                        {{ $schoolYear->is_active
                            ? 'bg-green-100 text-green-600'
                            : 'bg-gray-200 text-gray-500' }}">

                        @if($schoolYear->is_active)

                            <svg class="h-6 w-6"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M5 13l4 4L19 7"/>

                            </svg>

                        @else

                            <svg class="h-6 w-6"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>

                            </svg>

                        @endif

                    </div>


                    <div>

                        <p class="font-semibold text-gray-900">
                            Statut de l'année
                        </p>

                        @if($schoolYear->is_active)

                            <p class="text-sm text-green-600">
                                Cette année scolaire est actuellement active.
                            </p>

                        @else

                            <p class="text-sm text-gray-500">
                                Cette année scolaire n'est pas active.
                            </p>

                        @endif

                    </div>

                </div>


                {{-- ACTIVER : ADMIN UNIQUEMENT --}}
                @if(auth()->user()->role === 'admin' && !$schoolYear->is_active)

                    <form action="{{ route('school_years.activate', $schoolYear) }}"
                          method="POST">

                        @csrf

                        <button type="submit"
                                onclick="return confirm('Voulez-vous rendre cette année scolaire active ?');"
                                class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">

                            <svg class="h-5 w-5"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M5 13l4 4L19 7"/>

                            </svg>

                            Activer cette année

                        </button>

                    </form>

                @endif

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- INFORMATIONS TECHNIQUES --}}
        {{-- ================================================= --}}

        <div class="mt-8">

            <div class="mb-4 flex items-center gap-3">

                <div class="h-8 w-1 rounded-full bg-blue-600"></div>

                <h3 class="text-lg font-bold text-gray-900">
                    Informations supplémentaires
                </h3>

            </div>


            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                {{-- ID --}}
                <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm">

                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Identifiant
                    </p>

                    <p class="mt-2 text-lg font-bold text-gray-900">
                        #{{ $schoolYear->id }}
                    </p>

                </div>


                {{-- CRÉATION --}}
                <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm">

                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Créée le
                    </p>

                    <p class="mt-2 text-lg font-bold text-gray-900">
                        {{ $schoolYear->created_at
                            ? $schoolYear->created_at->format('d/m/Y')
                            : '—' }}
                    </p>

                </div>


                {{-- MODIFICATION --}}
                <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm">

                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Dernière modification
                    </p>

                    <p class="mt-2 text-lg font-bold text-gray-900">
                        {{ $schoolYear->updated_at
                            ? $schoolYear->updated_at->format('d/m/Y')
                            : '—' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- ACTIONS BAS DE PAGE --}}
{{-- ========================================================= --}}

<div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-between">

    {{-- RETOUR --}}
    <a href="{{ route('school_years.index') }}"
       class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50 hover:shadow-md">

        <svg class="h-5 w-5"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 19l-7-7 7-7"/>

        </svg>

        Retour aux années scolaires

    </a>


    {{-- MODIFIER : ADMIN UNIQUEMENT --}}
    @if(auth()->user()->role === 'admin')

        <a href="{{ route('school_years.edit', $schoolYear) }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-md">

            <svg class="h-5 w-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>

            </svg>

            Modifier l'année

        </a>

    @endif

</div>

@endsection