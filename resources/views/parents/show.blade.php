@extends('layouts.gestion-ecole')

@section('title', 'Parent - OPTIMA SCHOOL')
@section('page-title', 'Fiche parent')

@section('content')

    <div class="mx-auto max-w-6xl space-y-6">

        {{-- =========================================================
             EN-TÊTE
        ========================================================== --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <div class="flex items-center gap-3">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"
                            />
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold text-blue-950">
                            Fiche parent
                        </h1>

                        <p class="mt-1 text-sm text-slate-500">
                            Informations du parent et élèves rattachés
                        </p>
                    </div>

                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">

                <a
                    href="{{ route('parents.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl
                           border border-slate-300 bg-white px-5 py-3
                           text-sm font-bold text-slate-700 shadow-sm
                           transition hover:border-blue-400 hover:bg-slate-50"
                >
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>

                    Retour
                </a>

                <a
                    href="{{ route('parents.edit', $parent) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl
                           bg-blue-700 px-5 py-3 text-sm font-bold text-white
                           shadow-md transition hover:bg-blue-800 hover:shadow-lg"
                >
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5Z"
                        />
                    </svg>

                    Modifier
                </a>

            </div>

        </div>

        {{-- =========================================================
             ÉTABLISSEMENT
        ========================================================== --}}
        <div class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 shadow-sm">

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-700 text-white">
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 21h18M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16M9 7h2M9 11h2M13 7h2M13 11h2M9 15h2M13 15h2"
                            />
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-blue-600">
                            Établissement
                        </p>

                        <p class="text-base font-bold text-blue-950">
                            {{ $establishment->nom }}
                        </p>
                    </div>

                </div>

                <span class="inline-flex w-fit items-center rounded-full bg-green-100 px-3 py-1.5 text-xs font-bold text-green-700">
                    Parent enregistré
                </span>

            </div>

        </div>

        {{-- =========================================================
             INFORMATIONS DU PARENT
        ========================================================== --}}
        <div class="grid gap-6 lg:grid-cols-2">

            {{-- Identité --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 19a6 6 0 0 0-12 0M9 13a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM19 8v6M22 11h-6"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-blue-950">
                                Identité
                            </h2>

                            <p class="text-sm text-slate-500">
                                Informations personnelles
                            </p>
                        </div>

                    </div>

                </div>

                <div class="divide-y divide-slate-100">

                    <div class="px-6 py-4">
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                            Nom complet
                        </p>

                        <p class="mt-1 text-base font-bold text-slate-900">
                            {{ trim(($parent->prenom ?? '') . ' ' . $parent->nom) }}
                        </p>
                    </div>

                    <div class="grid gap-4 px-6 py-4 sm:grid-cols-2">

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                                Nom
                            </p>

                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ $parent->nom }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                                Prénom
                            </p>

                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ $parent->prenom ?: '—' }}
                            </p>
                        </div>

                    </div>

                    <div class="px-6 py-4">

                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                            Téléphone
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-800">
                            {{ $parent->telephone ?: 'Non renseigné' }}
                        </p>

                    </div>

                    <div class="px-6 py-4">

                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                            Adresse
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-800">
                            {{ $parent->adresse ?: 'Non renseignée' }}
                        </p>

                    </div>

                </div>

            </div>

            {{-- Compte --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-700">
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM5 21a7 7 0 0 1 14 0M19 11h2a2 2 0 0 1 2 2v7H17"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-blue-950">
                                Compte utilisateur
                            </h2>

                            <p class="text-sm text-slate-500">
                                Accès à OPTIMASCHOOL
                            </p>
                        </div>

                    </div>

                </div>

                <div class="divide-y divide-slate-100">

                    <div class="px-6 py-4">

                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                            Adresse e-mail
                        </p>

                        <p class="mt-1 break-all text-sm font-semibold text-slate-800">
                            {{ $parent->email ?: ($parent->user?->email ?? 'Non renseignée') }}
                        </p>

                    </div>

                    <div class="px-6 py-4">

                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                            Rôle
                        </p>

                        <div class="mt-2">
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1.5 text-xs font-bold text-blue-700">
                                Parent
                            </span>
                        </div>

                    </div>

                    <div class="px-6 py-4">

                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                            État du compte
                        </p>

                        <div class="mt-2">

                            @if($parent->user)

                                <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1.5 text-xs font-bold text-green-700">

                                    <span class="h-2 w-2 rounded-full bg-green-600"></span>

                                    Compte actif
                                </span>

                            @else

                                <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1.5 text-xs font-bold text-red-700">

                                    <span class="h-2 w-2 rounded-full bg-red-600"></span>

                                    Compte utilisateur absent
                                </span>

                            @endif

                        </div>

                    </div>

                    <div class="px-6 py-4">

                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                            Date de création
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-800">
                            {{ $parent->created_at?->format('d/m/Y à H:i') ?? '—' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

        {{-- =========================================================
             ÉLÈVES RATTACHÉS
        ========================================================== --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-700">
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M17 20h5v-2a4 4 0 0 0-4-4h-1M9 20H4v-2a4 4 0 0 1 4-4h1M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM16 11a4 4 0 1 0 0-7.75M20 20v-2a4 4 0 0 0-3-3.87M4 20v-2a4 4 0 0 1 3-3.87"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-blue-950">
                                Élèves rattachés
                            </h2>

                            <p class="text-sm text-slate-500">
                                Élèves associés à ce compte parent
                            </p>
                        </div>

                    </div>

                    <span class="inline-flex w-fit items-center rounded-full bg-green-100 px-3 py-1.5 text-sm font-bold text-green-700">
                        {{ $parent->students->count() }}
                        {{ $parent->students->count() > 1 ? 'élèves' : 'élève' }}
                    </span>

                </div>

            </div>

            @if($parent->students->isNotEmpty())

                <div class="divide-y divide-slate-100">

                    @foreach($parent->students as $student)

                        @php
                            $inscription = $student->inscriptions
                                ->sortByDesc(function ($item) {
                                    return $item->school_year_id ?? 0;
                                })
                                ->first();
                        @endphp

                        <div class="px-6 py-5">

                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                                <div class="flex items-start gap-4">

                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

                                        <svg
                                            class="h-5 w-5"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15 19a6 6 0 0 0-12 0M9 13a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM19 8v6M22 11h-6"
                                            />
                                        </svg>

                                    </div>

                                    <div>

                                        <p class="text-base font-bold text-slate-900">
                                            {{ trim($student->prenom . ' ' . $student->nom) }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            Matricule :
                                            <span class="font-semibold text-slate-700">
                                                {{ $student->matricule ?: 'Non renseigné' }}
                                            </span>
                                        </p>

                                    </div>

                                </div>

                                <div class="grid gap-3 sm:grid-cols-3 lg:min-w-[520px]">

                                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">

                                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                                            Classe
                                        </p>

                                        <p class="mt-1 text-sm font-bold text-blue-900">
                                            {{ $inscription?->classe ?? $student->classe ?? 'Non renseignée' }}
                                        </p>

                                    </div>

                                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">

                                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                                            Année scolaire
                                        </p>

                                        <p class="mt-1 text-sm font-bold text-blue-900">
                                            {{ $inscription?->annee_scolaire ?? 'Non renseignée' }}
                                        </p>

                                    </div>

                                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">

                                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                                            Statut
                                        </p>

                                        <p class="mt-1 text-sm font-bold text-green-700">
                                            {{ $inscription?->statut ?? 'Non renseigné' }}
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="px-6 py-12 text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-500">

                        <svg
                            class="h-7 w-7"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M17 20h5v-2a4 4 0 0 0-4-4h-1M9 20H4v-2a4 4 0 0 1 4-4h1M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8"
                            />
                        </svg>

                    </div>

                    <h3 class="mt-4 text-base font-bold text-slate-900">
                        Aucun élève rattaché
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                        Aucun élève n'est actuellement associé à ce compte parent.
                    </p>

                </div>

            @endif

        </div>

        {{-- =========================================================
             ACTIONS
        ========================================================== --}}
        <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-between">

            <a
                href="{{ route('parents.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl
                       border border-slate-300 bg-white px-5 py-3
                       text-sm font-bold text-slate-700 shadow-sm
                       transition hover:border-blue-400 hover:bg-slate-50"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>

                Retour à la liste
            </a>

            <a
                href="{{ route('parents.edit', $parent) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl
                       bg-green-600 px-5 py-3 text-sm font-bold text-white
                       shadow-md transition hover:bg-green-700 hover:shadow-lg"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5Z"
                    />
                </svg>

                Modifier le parent
            </a>

        </div>

    </div>

@endsection
