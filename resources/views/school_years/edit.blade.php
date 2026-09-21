@extends('layouts.gestion-ecole')

@section('title', 'Modifier année scolaire')

@section('page-title', 'Modifier l’année scolaire')

@section('page-description', 'Mettre à jour les informations de l’année scolaire')

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

            <a href="{{ route('school_years.show', $schoolYear) }}"
               class="transition hover:text-blue-600">
                {{ $schoolYear->name }}
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
                Modifier
            </span>

        </div>

        <h2 class="text-2xl font-bold tracking-tight text-gray-900">
            Modifier l’année scolaire
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Modifiez les informations de l’année scolaire
            <span class="font-semibold text-gray-700">
                {{ $schoolYear->name }}
            </span>.
        </p>

    </div>


    {{-- Retour --}}
    <a href="{{ route('school_years.show', $schoolYear) }}"
       class="inline-flex w-fit items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-300">

        <svg class="h-5 w-5"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 19l-7-7 7-7"/>

        </svg>

        Retour aux détails

    </a>

</div>


{{-- ========================================================= --}}
{{-- FORMULAIRE --}}
{{-- ========================================================= --}}

<div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-950 via-blue-900 to-blue-800 px-6 py-6 sm:px-8">

        <div class="flex items-center gap-4">

            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/20">

                <svg class="h-7 w-7 text-white"
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

            </div>

            <div>

                <p class="text-sm font-medium text-blue-200">
                    Gestion de l’année scolaire
                </p>

                <h3 class="mt-1 text-2xl font-bold text-white">
                    Modifier {{ $schoolYear->name }}
                </h3>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- BODY --}}
    {{-- ===================================================== --}}

    <div class="p-6 sm:p-8">

        {{-- Erreurs --}}
        @if($errors->any())

            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5">

                <div class="flex gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">

                        <svg class="h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 9v2m0 4h.01M10.29 3.86l-8.82 15A2 2 0 003.2 22h17.6a2 2 0 001.73-3.14l-8.82-15a2 2 0 00-3.42 0z"/>

                        </svg>

                    </div>

                    <div>

                        <h4 class="font-semibold text-red-800">
                            Vérifiez les informations saisies
                        </h4>

                        <ul class="mt-2 space-y-1 text-sm text-red-700">

                            @foreach($errors->all() as $error)

                                <li class="flex items-start gap-2">
                                    <span>•</span>
                                    <span>{{ $error }}</span>
                                </li>

                            @endforeach

                        </ul>

                    </div>

                </div>

            </div>

        @endif


        {{-- Formulaire --}}
        <form action="{{ route('school_years.update', $schoolYear) }}"
              method="POST"
              class="space-y-8">

            @csrf
            @method('PUT')


            {{-- ================================================= --}}
            {{-- INFORMATIONS PRINCIPALES --}}
            {{-- ================================================= --}}

            <div>

                <div class="mb-5 flex items-center gap-3">

                    <div class="h-8 w-1 rounded-full bg-blue-600"></div>

                    <div>
                        <h4 class="text-lg font-bold text-gray-900">
                            Informations principales
                        </h4>

                        <p class="text-sm text-gray-500">
                            Définissez les dates de l’année scolaire.
                        </p>
                    </div>

                </div>


                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- Nom --}}
                    <div class="md:col-span-2">

                        <label for="name"
                               class="mb-2 block text-sm font-semibold text-gray-700">

                            Nom de l’année scolaire

                            <span class="text-red-500">*</span>

                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">

                                <svg class="h-5 w-5 text-gray-400"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>

                                </svg>

                            </div>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $schoolYear->name) }}"
                                required
                                maxlength="255"
                                placeholder="Exemple : 2026-2027"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3.5 pl-12 pr-4 text-sm font-medium text-gray-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 @error('name') border-red-400 @enderror"
                            >

                        </div>

                        @error('name')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Date début --}}
                    <div>

                        <label for="start_date"
                               class="mb-2 block text-sm font-semibold text-gray-700">

                            Date de début

                            <span class="text-red-500">*</span>

                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">

                                <svg class="h-5 w-5 text-blue-500"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>

                                </svg>

                            </div>

                            <input
                                type="date"
                                id="start_date"
                                name="start_date"
                                value="{{ old('start_date', $schoolYear->start_date ? $schoolYear->start_date->format('Y-m-d') : '') }}"
                                required
                                class="w-full rounded-xl border border-gray-200 bg-blue-50/40 py-3.5 pl-12 pr-4 text-sm font-medium text-gray-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 @error('start_date') border-red-400 @enderror"
                            >

                        </div>

                        @error('start_date')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Date fin --}}
                    <div>

                        <label for="end_date"
                               class="mb-2 block text-sm font-semibold text-gray-700">

                            Date de fin

                            <span class="text-red-500">*</span>

                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">

                                <svg class="h-5 w-5 text-green-500"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2v-5a2 2 0 002-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>

                                </svg>

                            </div>

                            <input
                                type="date"
                                id="end_date"
                                name="end_date"
                                value="{{ old('end_date', $schoolYear->end_date ? $schoolYear->end_date->format('Y-m-d') : '') }}"
                                required
                                class="w-full rounded-xl border border-gray-200 bg-green-50/40 py-3.5 pl-12 pr-4 text-sm font-medium text-gray-900 outline-none transition focus:border-green-500 focus:bg-white focus:ring-4 focus:ring-green-500/10 @error('end_date') border-red-400 @enderror"
                            >

                        </div>

                        @error('end_date')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- STATUT --}}
            {{-- ================================================= --}}

            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">

                <div class="flex items-start gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl
                        {{ $schoolYear->is_active ? 'bg-green-100 text-green-600' : 'bg-gray-200 text-gray-500' }}">

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
                            Statut actuel
                        </p>

                        @if($schoolYear->is_active)

                            <p class="mt-1 text-sm text-green-600">
                                Cette année scolaire est actuellement active.
                            </p>

                        @else

                            <p class="mt-1 text-sm text-gray-500">
                                Cette année scolaire est actuellement inactive.
                            </p>

                        @endif

                        <p class="mt-2 text-xs text-gray-400">
                            Le statut peut être géré depuis la page de détails.
                        </p>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- ACTIONS --}}
            {{-- ================================================= --}}

            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:items-center sm:justify-between">

                {{-- Annuler --}}
                <a href="{{ route('school_years.show', $schoolYear) }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-300">

                    <svg class="h-5 w-5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>

                    </svg>

                    Annuler

                </a>


                <div class="flex flex-col gap-3 sm:flex-row">

                    {{-- Retour liste --}}
                    <a href="{{ route('school_years.index') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-5 py-3 text-sm font-semibold text-blue-700 transition-all duration-200 hover:-translate-y-0.5 hover:bg-blue-100 hover:shadow-sm">

                        <svg class="h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 10h18M3 10l6-6m-6 6l6 6"/>

                        </svg>

                        Liste

                    </a>


                    {{-- Enregistrer --}}
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">

                        <svg class="h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M5 13l4 4L19 7"/>

                        </svg>

                        Enregistrer les modifications

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- ========================================================= --}}
{{-- INFORMATIONS --}}
{{-- ========================================================= --}}

<div class="mt-6 rounded-2xl border border-blue-100 bg-blue-50/50 p-5">

    <div class="flex items-start gap-3">

        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">

            <svg class="h-5 w-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>

            </svg>

        </div>

        <div>

            <h4 class="font-semibold text-blue-900">
                À propos de cette modification
            </h4>

            <p class="mt-1 text-sm leading-6 text-blue-700">
                Les modifications seront enregistrées immédiatement.
                Vérifiez bien les dates avant de valider.
            </p>

        </div>

    </div>

</div>

@endsection