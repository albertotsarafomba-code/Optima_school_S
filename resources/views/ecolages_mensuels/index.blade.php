@extends('layouts.gestion-ecole')

@section('title', 'Suivi des écolages mensuels')

@section('page-title', 'Suivi des écolages')

@section('page-description', 'Suivez les écolages mensuels des élèves de l’établissement')

@section('content')

{{-- =========================================================
EN-TÊTE
========================================================= --}}

<div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

    <div>
        <h2 class="text-xl font-bold text-blue-950">
            Suivi des écolages mensuels
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Consultez le montant prévu, les paiements effectués et les restes à payer.
        </p>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row">

        {{-- Retour paiements --}}
        <a href="{{ route('paiements.index') }}"
           class="inline-flex items-center justify-center rounded-xl
                  border border-blue-700 bg-white px-5 py-3
                  text-sm font-semibold text-blue-700
                  transition hover:bg-blue-50">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="mr-2 h-5 w-5"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>

            </svg>

            Paiements
        </a>

        {{-- Nouveau paiement --}}
        <a href="{{ route('paiements.create') }}"
           class="inline-flex items-center justify-center rounded-xl
                  bg-green-600 px-5 py-3 text-sm font-semibold text-white
                  shadow-md transition hover:bg-green-700 hover:shadow-lg">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="mr-2 h-5 w-5"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 4v16m8-8H4"/>

            </svg>

            Enregistrer un paiement
        </a>

    </div>

</div>


{{-- =========================================================
FILTRES
========================================================= --}}

<div class="mb-8 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

    <div class="mb-5 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">

        <div>
            <h3 class="font-bold text-blue-950">
                Période de suivi
            </h3>

            <p class="mt-1 text-xs text-gray-500">
                Sélectionnez le mois et l’année à consulter.
            </p>
        </div>

        <div class="rounded-lg bg-blue-50 px-4 py-2 text-sm font-medium text-blue-800">
            {{ session('establishment_name', 'Établissement sélectionné') }}
        </div>

    </div>


    <form method="GET"
          action="{{ route('ecolages-mensuels.index') }}"
          class="grid grid-cols-1 gap-4 md:grid-cols-3">

        {{-- Mois --}}
        <div>

            <label for="mois"
                   class="mb-1.5 block text-sm font-semibold text-gray-700">
                Mois
            </label>

            <select id="mois"
                    name="mois"
                    class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm
                           focus:border-blue-700 focus:ring-blue-700">

                @php
                    $moisListe = [
                        1 => 'Janvier',
                        2 => 'Février',
                        3 => 'Mars',
                        4 => 'Avril',
                        5 => 'Mai',
                        6 => 'Juin',
                        7 => 'Juillet',
                        8 => 'Août',
                        9 => 'Septembre',
                        10 => 'Octobre',
                        11 => 'Novembre',
                        12 => 'Décembre',
                    ];
                @endphp

                @foreach($moisListe as $numero => $nom)

                    <option value="{{ $numero }}"
                        @selected((int) $mois === $numero)>
                        {{ $nom }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- Année --}}
        <div>

            <label for="annee"
                   class="mb-1.5 block text-sm font-semibold text-gray-700">
                Année
            </label>

            <input type="number"
                   id="annee"
                   name="annee"
                   value="{{ $annee }}"
                   min="2000"
                   max="2100"
                   class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm
                          focus:border-blue-700 focus:ring-blue-700">

        </div>


        {{-- Bouton --}}
        <div class="flex items-end">

            <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded-lg
                           bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white
                           transition hover:bg-blue-800">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="mr-2 h-5 w-5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M21 21l-4.35-4.35m2.1-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"/>

                </svg>

                Afficher le suivi

            </button>

        </div>

    </form>

</div>


{{-- =========================================================
RÉSUMÉ
========================================================= --}}

@php
    $totalPrevu = $suivi->sum('prevu');
    $totalPaye = $suivi->sum('paye');
    $totalReste = $suivi->sum('reste');

    $nombrePaye = $suivi->where('statut', 'Payé')->count();
    $nombrePartiel = $suivi->where('statut', 'Partiel')->count();
    $nombreImpaye = $suivi->where('statut', 'Impayé')->count();
@endphp


<div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

    {{-- Total prévu --}}
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Total prévu
                </p>

                <p class="mt-2 text-2xl font-bold text-blue-950">
                    {{ number_format($totalPrevu, 0, ',', ' ') }}
                    <span class="text-sm font-semibold">Ar</span>
                </p>

            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-xl
                        bg-blue-100 text-blue-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 6v12m-4-2h8M5 4h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1z"/>

                </svg>

            </div>

        </div>

    </div>


    {{-- Total payé --}}
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Total payé
                </p>

                <p class="mt-2 text-2xl font-bold text-green-700">
                    {{ number_format($totalPaye, 0, ',', ' ') }}
                    <span class="text-sm font-semibold">Ar</span>
                </p>

            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-xl
                        bg-green-100 text-green-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>

                </svg>

            </div>

        </div>

    </div>


    {{-- Total restant --}}
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Total restant
                </p>

                <p class="mt-2 text-2xl font-bold text-red-600">
                    {{ number_format($totalReste, 0, ',', ' ') }}
                    <span class="text-sm font-semibold">Ar</span>
                </p>

            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-xl
                        bg-red-100 text-red-600">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 9v4m0 4h.01M10.29 3.86l-7.82 13a2 2 0 001.71 3h15.64a2 2 0 001.71-3l-7.82-13a2 2 0 00-3.42 0z"/>

                </svg>

            </div>

        </div>

    </div>


    {{-- Situation --}}
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

        <div>

            <p class="text-sm font-medium text-gray-500">
                Situation
            </p>

            <div class="mt-3 flex flex-wrap gap-2">

                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                    {{ $nombrePaye }} payé(s)
                </span>

                <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-700">
                    {{ $nombrePartiel }} partiel(s)
                </span>

                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                    {{ $nombreImpaye }} impayé(s)
                </span>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
TABLEAU
========================================================= --}}

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

    <div class="flex flex-col justify-between gap-3 border-b border-gray-100
                px-6 py-5 sm:flex-row sm:items-center">

        <div>

            <h3 class="font-bold text-blue-950">
                Écolages de {{ $moisListe[$mois] ?? $mois }} {{ $annee }}
            </h3>

            <p class="mt-1 text-xs text-gray-500">
                {{ $suivi->count() }} élève(s) suivi(s)
            </p>

        </div>

        <div class="rounded-lg bg-blue-50 px-4 py-2 text-sm font-medium text-blue-800">
            {{ session('school_year_name', 'Année scolaire sélectionnée') }}
        </div>

    </div>


    @if($suivi->isEmpty())

        <div class="px-6 py-16 text-center">

            <div class="mx-auto flex h-20 w-20 items-center justify-center
                        rounded-full bg-blue-100 text-blue-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-10 w-10"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.8">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M9 14h6m-6 4h6M8 3h8l4 4v14a1 1 0 01-1 1H5a1 1 0 01-1-1V4a1 1 0 011-1h3z"/>

                </svg>

            </div>

            <h3 class="mt-5 text-lg font-bold text-blue-950">
                Aucun écolage configuré
            </h3>

            <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
                Aucun élève ne possède actuellement de configuration d’écolage
                pour l’année scolaire sélectionnée.
            </p>

        </div>

    @else

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-100">

                <thead class="bg-blue-950">

                    <tr>

                        <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">
                            Élève
                        </th>

                        <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">
                            Matricule
                        </th>

                        <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wider text-white">
                            Prévu
                        </th>

                        <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wider text-white">
                            Payé
                        </th>

                        <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wider text-white">
                            Reste
                        </th>

                        <th class="px-5 py-4 text-center text-xs font-semibold uppercase tracking-wider text-white">
                            Statut
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100 bg-white">

                    @foreach($suivi as $ligne)

                        <tr class="transition hover:bg-blue-50/40">

                            {{-- Élève --}}
                            <td class="px-5 py-5">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center
                                                rounded-lg bg-blue-100 text-blue-700">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M15 19a3 3 0 00-6 0m3-7a4 4 0 100-8 4 4 0 000 8z"/>

                                        </svg>

                                    </div>

                                    <div>

                                        <p class="font-semibold text-blue-950">
                                            {{ $ligne['student']->nom }}
                                            {{ $ligne['student']->prenom }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            Élève
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- Matricule --}}
                            <td class="whitespace-nowrap px-5 py-5 text-sm text-gray-600">
                                {{ $ligne['student']->matricule ?? '—' }}
                            </td>


                            {{-- Prévu --}}
                            <td class="whitespace-nowrap px-5 py-5 text-right">

                                <span class="font-semibold text-blue-950">
                                    {{ number_format($ligne['prevu'], 0, ',', ' ') }} Ar
                                </span>

                            </td>


                            {{-- Payé --}}
                            <td class="whitespace-nowrap px-5 py-5 text-right">

                                <span class="font-semibold text-green-700">
                                    {{ number_format($ligne['paye'], 0, ',', ' ') }} Ar
                                </span>

                            </td>


                            {{-- Reste --}}
                            <td class="whitespace-nowrap px-5 py-5 text-right">

                                <span class="font-semibold
                                    {{ $ligne['reste'] > 0
                                        ? 'text-red-600'
                                        : 'text-green-700' }}">

                                    {{ number_format($ligne['reste'], 0, ',', ' ') }} Ar

                                </span>

                            </td>


                            {{-- Statut --}}
                            <td class="whitespace-nowrap px-5 py-5 text-center">

                                @if($ligne['statut'] === 'Payé')

                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1
                                                 text-xs font-semibold text-green-700">
                                        Payé
                                    </span>

                                @elseif($ligne['statut'] === 'Partiel')

                                    <span class="inline-flex rounded-full bg-orange-100 px-3 py-1
                                                 text-xs font-semibold text-orange-700">
                                        Partiel
                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1
                                                 text-xs font-semibold text-red-700">
                                        Impayé
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

</div>

@endsection
