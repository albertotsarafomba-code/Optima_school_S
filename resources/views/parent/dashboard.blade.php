@extends('layouts.parent')

@section('title', 'Espace famille - OptimaSchool')

@section('content')
<div class="min-h-full bg-slate-50">

    <div class="mx-auto max-w-7xl px-6 py-8">

        {{-- EN-TÊTE SIMPLE --}}
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-wide text-green-600">
                Espace famille
            </p>

            <div class="mt-2 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-[#1e3a8a] sm:text-3xl">
                        Tableau de bord
                    </h1>

                    <p class="mt-2 text-sm text-slate-600 sm:text-base">
                        Consultez simplement les informations scolaires de vos enfants.
                    </p>
                </div>

                @if($currentSchoolYear)
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Année scolaire
                        </p>
                        <p class="mt-1 font-bold text-[#1e3a8a]">
                            {{ $currentSchoolYear->nom ?? $currentSchoolYear->name ?? 'Année scolaire' }}
                        </p>
                    </div>
                @endif
            </div>
        </div>


        {{-- PAS D'ÉTABLISSEMENT --}}
        @if($noEstablishment)

            <section class="rounded-2xl border border-amber-200 bg-amber-50 p-6">
                <div class="flex items-start gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75-3L13.7 3.8a2 2 0 00-3.4 0z"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="font-bold text-amber-900">
                            Établissement non disponible
                        </h2>

                        <p class="mt-1 text-sm text-amber-800">
                            Votre compte parent n'est actuellement associé à aucun établissement.
                        </p>
                    </div>

                </div>
            </section>

        @else

            {{-- ÉTABLISSEMENT --}}
            @if($currentEstablishment)
                <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Établissement
                            </p>

                            <h2 class="mt-1 text-lg font-bold text-[#1e3a8a]">
                                {{ $currentEstablishment->nom ?? $currentEstablishment->name ?? 'Établissement' }}
                            </h2>

                            @if(!empty($currentEstablishment->adresse))
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $currentEstablishment->adresse }}
                                </p>
                            @endif
                        </div>

                        @if($currentSchoolYear)
                            <div class="rounded-xl bg-blue-50 px-4 py-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-[#1e3a8a]">
                                    Année scolaire
                                </p>

                                <p class="mt-1 font-bold text-[#1e3a8a]">
                                    {{ $currentSchoolYear->nom ?? $currentSchoolYear->name ?? 'Année scolaire' }}
                                </p>
                            </div>
                        @endif

                    </div>
                </div>
            @endif


            {{-- RÉSUMÉ --}}
            <section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Mes enfants
                            </p>

                            <p class="mt-2 text-3xl font-bold text-[#1e3a8a]">
                                {{ $students->count() }}
                            </p>
                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-[#1e3a8a]">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <circle cx="9" cy="8" r="3"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 20a6 6 0 0112 0M16 11a3 3 0 10.01 0"/>
                            </svg>
                        </div>
                    </div>
                </div>


                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Notes & résultats
                            </p>

                            <p class="mt-2 text-sm font-semibold text-slate-700">
                                Consultation
                            </p>
                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-green-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M5 19V9m7 10V5m7 14v-7"/>
                            </svg>
                        </div>
                    </div>
                </div>


                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Paiements
                            </p>

                            <p class="mt-2 text-sm font-semibold text-slate-700">
                                Historique disponible
                            </p>
                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-green-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 3v18m4-14.5c-.7-.9-2-1.5-4-1.5-2.2 0-4 1.1-4 3s1.8 3 4 3 4 1.1 4 3-1.8 3-4 3c-2 0-3.3-.6-4-1.5"/>
                            </svg>
                        </div>
                    </div>
                </div>

            </section>


            {{-- MES ENFANTS --}}
            <section class="mb-8">

                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-green-600">
                            Mes enfants
                        </p>

                        <h2 class="mt-1 text-xl font-bold text-[#1e3a8a]">
                            Situation scolaire
                        </h2>
                    </div>

                    <a href="{{ route('parent.inscriptions') }}"
                       class="hidden rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-[#1e3a8a] shadow-sm transition hover:bg-blue-50 sm:inline-flex">
                        Voir tous
                    </a>
                </div>


                @if($students->isEmpty())

                    <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="8" r="3"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M5 21a7 7 0 0114 0"/>
                            </svg>
                        </div>

                        <h3 class="mt-4 font-bold text-slate-900">
                            Aucun enfant associé
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Aucun élève n'est actuellement associé à votre compte parent.
                        </p>
                    </div>

                @else

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

                        @foreach($students as $student)

                            @php
                                $statutNormalise = strtolower(trim((string) ($student->statut ?? $student->status ?? '')));
                            @endphp

                            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                                <div class="flex items-start justify-between gap-4">

                                    <div class="min-w-0">
                                        <h3 class="truncate text-lg font-bold text-slate-900">
                                            {{ $student->nom_complet
                                                ?? trim(($student->nom ?? '') . ' ' . ($student->prenom ?? ''))
                                                ?: 'Élève' }}
                                        </h3>

                                        @if(!empty($student->matricule))
                                            <p class="mt-1 text-sm text-slate-500">
                                                Matricule :
                                                <span class="font-semibold text-slate-700">
                                                    {{ $student->matricule }}
                                                </span>
                                            </p>
                                        @endif
                                    </div>

                                    @if(in_array($statutNormalise, ['inscrit', 'active', 'actif', 'validé', 'valide']))
                                        <span class="shrink-0 rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                            Actif
                                        </span>
                                    @elseif($statutNormalise !== '')
                                        <span class="shrink-0 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                                            {{ $student->statut ?? $student->status }}
                                        </span>
                                    @endif

                                </div>


                                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">

                                    <div class="rounded-xl bg-slate-50 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                            Classe
                                        </p>

                                        <p class="mt-1 font-semibold text-slate-800">
                                            {{ $student->classe
                                                ?? $student->classRoom?->nom_classe
                                                ?? 'Non renseignée' }}
                                        </p>
                                    </div>


                                    <div class="rounded-xl bg-slate-50 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                            Niveau
                                        </p>

                                        <p class="mt-1 font-semibold text-slate-800">
                                            {{ $student->niveau_scolaire
                                                ?? $student->niveau
                                                ?? 'Non renseigné' }}
                                        </p>
                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @endif

            </section>


            {{-- ACCÈS RAPIDES --}}
            <section>

                <div class="mb-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-green-600">
                        Consultation
                    </p>

                    <h2 class="mt-1 text-xl font-bold text-[#1e3a8a]">
                        Informations scolaires
                    </h2>
                </div>


                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                    <a href="{{ route('parent.inscriptions') }}"
                       class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-[#1e3a8a]">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M5 4h14v16H5z"/>
                                <path stroke-linecap="round" d="M8 8h8M8 12h8M8 16h5"/>
                            </svg>
                        </div>

                        <h3 class="mt-4 font-bold text-slate-900">
                            Mes enfants
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Consulter les informations scolaires de vos enfants.
                        </p>

                    </a>


                    <a href="{{ route('notes.index') }}"
                       class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-green-200 hover:shadow-md">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-green-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M5 19V9m7 10V5m7 14v-7"/>
                            </svg>
                        </div>

                        <h3 class="mt-4 font-bold text-slate-900">
                            Notes & résultats
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Consulter les résultats scolaires de vos enfants.
                        </p>

                    </a>


                    <a href="{{ route('bulletins.index') }}"
                       class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-[#1e3a8a]">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6 4h12v16H6z"/>
                                <path stroke-linecap="round" d="M9 8h6M9 12h6M9 16h4"/>
                            </svg>
                        </div>

                        <h3 class="mt-4 font-bold text-slate-900">
                            Bulletins
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Consulter les bulletins scolaires disponibles.
                        </p>

                    </a>


                    <a href="{{ route('emploi-du-temps.index') }}"
                       class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-green-200 hover:shadow-md">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-green-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <rect x="4" y="5" width="16" height="15" rx="2"/>
                                <path stroke-linecap="round" d="M8 3v4M16 3v4M4 9h16"/>
                            </svg>
                        </div>

                        <h3 class="mt-4 font-bold text-slate-900">
                            Emploi du temps
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Consulter l'emploi du temps de la classe.
                        </p>

                    </a>


                    <a href="{{ route('parent.paiements') }}"
                       class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-green-200 hover:shadow-md">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-green-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 3v18m4-14.5c-.7-.9-2-1.5-4-1.5-2.2 0-4 1.1-4 3s1.8 3 4 3 4 1.1 4 3-1.8 3-4 3c-2 0-3.3-.6-4-1.5"/>
                            </svg>
                        </div>

                        <h3 class="mt-4 font-bold text-slate-900">
                            Paiements
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Consulter l'historique des paiements scolaires.
                        </p>

                    </a>


                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M4 5h16v14H4z"/>
                                <path stroke-linecap="round" d="M8 9h8M8 13h5"/>
                            </svg>
                        </div>

                        <h3 class="mt-4 font-bold text-slate-900">
                            Informations scolaires
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Retrouvez les informations générales de l'établissement.
                        </p>

                    </div>

                </div>

            </section>

        @endif

    </div>
</div>
@endsection
