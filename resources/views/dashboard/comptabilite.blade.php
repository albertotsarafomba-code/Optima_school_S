@extends('layouts.gestion-ecole')

@section('content')

<div class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">

    {{-- ===================================================== --}}
    {{-- EN-TÊTE --}}
    {{-- ===================================================== --}}

    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-900 text-white shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 8c-2.21 0-4 1.12-4 2.5S9.79 13 12 13s4 1.12 4 2.5S14.21 18 12 18m0-10V6m0 12v-2m0-10.5A4 4 0 0116 9m-8 6a4 4 0 014-1.5M12 21a9 9 0 100-18 9 9 0 000 18z" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-slate-900">
                        Dashboard Comptabilité
                    </h1>

                    <p class="mt-1 text-sm text-slate-600">
                        Gestion des encaissements et des paiements
                    </p>
                </div>
            </div>

            <div class="mt-3 flex flex-wrap gap-2">

                @if($establishment)
                    <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-sm font-medium text-blue-900">
                        {{ $establishment->nom }}
                    </span>
                @endif

                @if($schoolYear)
                    <span class="rounded-lg bg-green-50 px-3 py-1.5 text-sm font-medium text-green-800">
                        Année scolaire :
                        {{ $schoolYear->nom
                            ?? $schoolYear->name
                            ?? $schoolYear->annee_scolaire
                            ?? '—' }}
                    </span>
                @endif

                <span class="rounded-lg bg-slate-100 px-3 py-1.5 text-sm font-medium text-slate-700">
                    Paiement : Espèces
                </span>

            </div>
        </div>

        {{-- ACTIONS RAPIDES --}}
        <div class="flex flex-wrap gap-3">

            <a href="{{ route('paiements.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 4v16m8-8H4" />
                </svg>

                Nouveau paiement
            </a>

            <a href="{{ route('paiements.index') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-4 0a2 2 0 114 0m-4 0h4" />
                </svg>

                Tous les paiements
            </a>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- FILTRES COMPTABLES --}}
    {{-- ===================================================== --}}

    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="mb-4">
            <h2 class="text-lg font-bold text-slate-900">
                Filtrer les paiements
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Les résultats restent limités à l'établissement et à l'année scolaire sélectionnés.
            </p>
        </div>

        <form method="GET" action="{{ route('dashboard') }}"
              class="grid grid-cols-1 items-end gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <div>
                <label for="periode" class="mb-2 block text-sm font-semibold text-slate-700">
                    Période
                </label>
                <select id="periode" name="periode"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-sm text-slate-800 focus:border-blue-700 focus:ring-blue-700">
                    <option value="tous" {{ $periodeFiltre === 'tous' ? 'selected' : '' }}>
                        Tous les paiements
                    </option>
                    <option value="mois" {{ $periodeFiltre === 'mois' ? 'selected' : '' }}>
                        Par mois
                    </option>
                    <option value="annee" {{ $periodeFiltre === 'annee' ? 'selected' : '' }}>
                        Par année civile
                    </option>
                </select>
            </div>

            <div>
                <label for="mois" class="mb-2 block text-sm font-semibold text-slate-700">
                    Mois
                </label>
                <select id="mois" name="mois"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-sm text-slate-800 focus:border-blue-700 focus:ring-blue-700">
                    @foreach([
                        1 => 'Janvier', 2 => 'Février', 3 => 'Mars',
                        4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
                        7 => 'Juillet', 8 => 'Août', 9 => 'Septembre',
                        10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                    ] as $numeroMois => $nomMois)
                        <option value="{{ $numeroMois }}"
                            {{ (int) $moisFiltre === $numeroMois ? 'selected' : '' }}>
                            {{ $nomMois }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="annee" class="mb-2 block text-sm font-semibold text-slate-700">
                    Année civile
                </label>
                <select id="annee" name="annee"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-sm text-slate-800 focus:border-blue-700 focus:ring-blue-700">
                    @for($annee = now()->year + 1; $annee >= 2015; $annee--)
                        <option value="{{ $annee }}"
                            {{ (int) $anneeFiltre === $annee ? 'selected' : '' }}>
                            {{ $annee }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="flex flex-wrap gap-2">
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800">
                    Appliquer
                </button>

                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Réinitialiser
                </a>
            </div>

        </form>

        <p class="mt-3 text-xs text-slate-500">
            Le filtre mensuel utilise le mois et l'année de la date du paiement.
            Le filtre annuel utilise l'année civile, indépendamment du nom de l'année scolaire.
        </p>

    </div>


    {{-- ===================================================== --}}
    {{-- CARTES STATISTIQUES --}}
    {{-- ===================================================== --}}

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-5">

        {{-- TOTAL ENCAISSÉ --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Total encaissé
                    </p>

                    <p class="mt-2 text-2xl font-bold text-slate-900">
                        {{ number_format($stats['total_encaisse'], 0, ',', ' ') }}
                        Ar
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Année scolaire courante
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 8c-2.21 0-4 1.12-4 2.5S9.79 13 12 13s4 1.12 4 2.5S14.21 18 12 18m0-10V6m0 12v-2m0-10.5A4 4 0 0116 9m-8 6a4 4 0 014-1.5" />
                    </svg>
                </div>

            </div>

        </div>


        {{-- TOTAL DES ECOLAGES --}}
        <div class="rounded-2xl border border-green-200 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between gap-3">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Total des écolages
                    </p>

                    <p class="mt-2 text-2xl font-bold text-green-700">
                        {{ number_format($stats['total_ecolages'], 0, ',', ' ') }}
                        Ar
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Année scolaire sélectionnée
                    </p>
                </div>

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 8c-2.21 0-4 1.12-4 2.5S9.79 13 12 13s4 1.12 4 2.5S14.21 18 12 18m0-10V6m0 12v-2m-4-5h8" />
                    </svg>
                </div>

            </div>

        </div>


        {{-- CE MOIS --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Encaissé ce mois
                    </p>

                    <p class="mt-2 text-2xl font-bold text-green-700">
                        {{ number_format($stats['total_encaisse_mois'], 0, ',', ' ') }}
                        Ar
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        {{ now()->translatedFormat('F Y') }}
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 12h18M12 3v18" />
                    </svg>
                </div>

            </div>

        </div>


        {{-- NOMBRE PAIEMENTS --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Nombre de paiements
                    </p>

                    <p class="mt-2 text-2xl font-bold text-slate-900">
                        {{ number_format($stats['nombre_paiements'], 0, ',', ' ') }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        {{ $stats['nombre_paiements_mois'] }} ce mois
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 5h6m-8 4h10M7 13h10M7 17h6" />
                    </svg>
                </div>

            </div>

        </div>


        {{-- ÉLÈVES AYANT PAYÉ --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Élèves ayant payé
                    </p>

                    <p class="mt-2 text-2xl font-bold text-blue-900">
                        {{ number_format($stats['eleves_ayant_paye'], 0, ',', ' ') }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Élèves uniques
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M16 19h6M19 16v6M9 13a4 4 0 100-8 4 4 0 000 8zm-7 8a7 7 0 0114 0" />
                    </svg>
                </div>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- CONTENU PRINCIPAL --}}
    {{-- ===================================================== --}}

    <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- DERNIERS PAIEMENTS --}}
        <div class="xl:col-span-2 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">

                <div>
                    <h2 class="text-lg font-bold text-slate-900">
                        Derniers paiements
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Les dernières opérations enregistrées
                    </p>
                </div>

                <a href="{{ route('paiements.index') }}"
                   class="text-sm font-semibold text-blue-900 hover:text-blue-700">
                    Voir tout
                </a>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">
                        <tr>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Bénéficiaire
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Type
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Date
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Montant
                            </th>

                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Reçu
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">

                        @forelse($derniersPaiements as $paiement)

                            <tr class="hover:bg-slate-50">

                                <td class="px-5 py-4">

                                    @if($paiement->student)
                                        <div class="font-semibold text-slate-900">
                                            {{ $paiement->student->nom }}
                                            {{ $paiement->student->prenom }}
                                        </div>

                                        @if($paiement->student->matricule)
                                            <div class="mt-1 text-xs text-slate-500">
                                                {{ $paiement->student->matricule }}
                                            </div>
                                        @endif

                                    @elseif($paiement->teacher)

                                        <div class="font-semibold text-slate-900">
                                            {{ $paiement->teacher->nom }}
                                            {{ $paiement->teacher->prenom }}
                                        </div>

                                        <div class="mt-1 text-xs text-slate-500">
                                            Enseignant
                                        </div>

                                    @else

                                        <span class="text-slate-500">
                                            — 
                                        </span>

                                    @endif

                                </td>

                                <td class="px-5 py-4 text-sm text-slate-700">
                                    {{ $paiement->type_paiement ?? '—' }}
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-600">
                                    @if($paiement->date_paiement)
                                        {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-right">

                                    <span class="font-bold text-green-700">
                                        {{ number_format((float) $paiement->montant, 0, ',', ' ') }}
                                        Ar
                                    </span>

                                    <div class="mt-1 text-xs text-slate-400">
                                        Espèces
                                    </div>

                                </td>

                                <td class="px-5 py-4 text-center">

                                    <a href="{{ route('paiements.recu', $paiement) }}"
                                       class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-900 hover:bg-blue-100">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                                        </svg>

                                        Reçu
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5"
                                    class="px-5 py-12 text-center">

                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-6 w-6"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>

                                    <p class="mt-3 text-sm font-semibold text-slate-700">
                                        Aucun paiement enregistré
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Les paiements enregistrés apparaîtront ici.
                                    </p>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- RÉPARTITION --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="text-lg font-bold text-slate-900">
                    Répartition des paiements
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Par type de paiement
                </p>

            </div>

            <div class="space-y-4 p-5">

                @forelse($paiementsParType as $type => $data)

                    <div class="rounded-xl border border-slate-200 p-4">

                        <div class="flex items-start justify-between gap-3">

                            <div>
                                <p class="font-semibold text-slate-800">
                                    {{ $type ?: 'Autre' }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $data['nombre'] }}
                                    paiement{{ $data['nombre'] > 1 ? 's' : '' }}
                                </p>
                            </div>

                            <p class="text-right font-bold text-green-700">
                                {{ number_format($data['montant'], 0, ',', ' ') }}
                                Ar
                            </p>

                        </div>

                    </div>

                @empty

                    <div class="rounded-xl bg-slate-50 p-6 text-center">

                        <p class="text-sm font-medium text-slate-600">
                            Aucune donnée financière disponible.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- ACTIONS COMPTABILITÉ --}}
    {{-- ===================================================== --}}

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="mb-4">
            <h2 class="text-lg font-bold text-slate-900">
                Gestion comptable
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Accès rapide aux opérations financières.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <a href="{{ route('paiements.create') }}"
               class="group rounded-xl border border-green-200 bg-green-50 p-4 transition hover:border-green-300 hover:bg-green-100">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-600 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 4v16m8-8H4" />
                    </svg>
                </div>

                <p class="mt-3 font-semibold text-slate-900">
                    Enregistrer un paiement
                </p>

                <p class="mt-1 text-sm text-slate-600">
                    Enregistrer un paiement en espèces.
                </p>

            </a>


            <a href="{{ route('paiements.index') }}"
               class="group rounded-xl border border-blue-200 bg-blue-50 p-4 transition hover:border-blue-300 hover:bg-blue-100">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-900 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                    </svg>
                </div>

                <p class="mt-3 font-semibold text-slate-900">
                    Tous les paiements
                </p>

                <p class="mt-1 text-sm text-slate-600">
                    Consulter et rechercher les paiements.
                </p>

            </a>


            <a href="{{ route('ecolages-mensuels.index') }}"
               class="group rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-slate-300 hover:bg-slate-100">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-700 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 7h6m-6 4h6m-6 4h4m4 5H7a2 2 0 01-2-2V6a2 2 0 012-2h7l5 5v9a2 2 0 01-2 2z" />
                    </svg>
                </div>

                <p class="mt-3 font-semibold text-slate-900">
                    Suivi des écolages
                </p>

                <p class="mt-1 text-sm text-slate-600">
                    Consulter le suivi mensuel.
                </p>

            </a>


            <a href="{{ route('paiements.index') }}"
               class="group rounded-xl border border-slate-200 bg-white p-4 transition hover:border-slate-300 hover:bg-slate-50">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                    </svg>
                </div>

                <p class="mt-3 font-semibold text-slate-900">
                    Reçus
                </p>

                <p class="mt-1 text-sm text-slate-600">
                    Accéder aux paiements et générer les reçus.
                </p>

            </a>

        </div>

    </div>

</div>

@endsection
