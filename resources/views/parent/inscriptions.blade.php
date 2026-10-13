@extends('layouts.parent')

@section('title', 'Mes enfants - OptimaSchool')

@section('content')
<div class="min-h-full bg-slate-50">

    <div class="mx-auto max-w-7xl px-6 py-8">

        {{-- En-tête --}}
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-[#1e3a8a]">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <circle cx="9" cy="8" r="3"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 20a6 6 0 0112 0M16 11a3 3 0 100-6M16 14a5 5 0 015 5"/>
                    </svg>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-green-600">
                        Espace famille
                    </p>

                    <h1 class="mt-1 text-2xl font-bold text-[#1e3a8a]">
                        Mes enfants
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Consultez la situation scolaire de vos enfants.
                    </p>
                </div>
            </div>

            <a href="{{ route('parent.dashboard') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#1e3a8a] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">

                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 19l-7-7 7-7"/>
                </svg>

                Tableau de bord
            </a>

        </div>


        {{-- Contexte scolaire --}}
        <div class="mb-8 grid gap-5 md:grid-cols-2">

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-[#1e3a8a]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Établissement
                        </p>
                        <p class="mt-1 truncate text-base font-bold text-[#1e3a8a]">
                            {{ $currentEstablishment?->nom ?? '—' }}
                        </p>
                    </div>

                </div>
            </div>


            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-green-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Année scolaire
                        </p>
                        <p class="mt-1 text-base font-bold text-green-700">
                            {{ $currentSchoolYear?->name
                                ?? $currentSchoolYear?->nom
                                ?? $currentSchoolYear?->annee_scolaire
                                ?? '—' }}
                        </p>
                    </div>

                </div>
            </div>

        </div>


        {{-- Liste des enfants --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5">
                <div class="flex items-center justify-between gap-4">

                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Scolarité de mes enfants
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Les informations accessibles depuis votre espace famille.
                        </p>
                    </div>

                    <div class="hidden rounded-lg bg-blue-50 px-3 py-2 text-sm font-bold text-[#1e3a8a] sm:block">
                        {{ $inscriptions->count() }} inscription(s)
                    </div>

                </div>
            </div>


            @if($inscriptions->isEmpty())

                <div class="px-6 py-16 text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100">
                        <svg class="h-7 w-7 text-slate-400" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="9" cy="8" r="3"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 20a6 6 0 0112 0"/>
                        </svg>
                    </div>

                    <h3 class="mt-4 text-base font-bold text-slate-800">
                        Aucune inscription trouvée
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        Aucune inscription de vos enfants n'est disponible pour l'année scolaire sélectionnée.
                    </p>

                </div>

            @else

                <div class="grid gap-5 p-6 md:grid-cols-2">

                    @foreach($inscriptions as $inscription)

                        <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5 transition hover:border-blue-200 hover:bg-blue-50/30">

                            <div class="flex items-start justify-between gap-4">

                                <div class="flex min-w-0 items-center gap-3">

                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-[#1e3a8a]">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="1.8">
                                            <circle cx="9" cy="8" r="3"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M3 20a6 6 0 0112 0M16 11a3 3 0 100-6M16 14a5 5 0 015 5"/>
                                        </svg>
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="truncate text-base font-bold text-slate-900">
                                            {{ trim(($inscription->student?->nom ?? '') . ' ' . ($inscription->student?->prenom ?? '')) ?: 'Élève inconnu' }}
                                        </h3>

                                        @if($inscription->student?->matricule)
                                            <p class="mt-1 text-xs text-slate-500">
                                                Matricule : {{ $inscription->student->matricule }}
                                            </p>
                                        @endif
                                    </div>

                                </div>

                                <span class="shrink-0 rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                    {{ $inscription->statut ?? 'Actif' }}
                                </span>

                            </div>


                            <div class="mt-5 grid grid-cols-2 gap-3">

                                <div class="rounded-xl bg-white p-3">
                                    <p class="text-xs font-medium text-slate-400">
                                        Classe
                                    </p>
                                    <p class="mt-1 text-sm font-bold text-[#1e3a8a]">
                                        {{ $inscription->classRoom?->nom_classe
                                            ?? $inscription->student?->classe
                                            ?? '—' }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-white p-3">
                                    <p class="text-xs font-medium text-slate-400">
                                        Niveau
                                    </p>
                                    <p class="mt-1 text-sm font-bold text-slate-800">
                                        {{ $inscription->niveau_scolaire ?? '—' }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-white p-3">
                                    <p class="text-xs font-medium text-slate-400">
                                        Situation
                                    </p>
                                    <p class="mt-1 text-sm font-semibold text-slate-700">
                                        {{ $inscription->situation_scolaire ?? '—' }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-white p-3">
                                    <p class="text-xs font-medium text-slate-400">
                                        Inscription
                                    </p>
                                    <p class="mt-1 text-sm font-semibold text-slate-700">
                                        {{ $inscription->date_inscription?->format('d/m/Y') ?? '—' }}
                                    </p>
                                </div>

                            </div>

                        </article>

                    @endforeach

                </div>

            @endif

        </section>

    </div>
</div>
@endsection
