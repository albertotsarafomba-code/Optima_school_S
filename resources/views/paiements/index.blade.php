@extends('layouts.gestion-ecole')

@section('title', 'Suivi des écolages')

@section('content')

<div class="space-y-6">

    {{-- ============================================================
         EN-TÊTE
    ============================================================ --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Suivi des écolages
            </h1>

            <p class="mt-1 text-sm text-slate-600">
                {{ $establishment->nom }}
                ·
                Année scolaire :
                <span class="font-semibold text-blue-900">
                    {{ $schoolYear->name }}
                </span>
            </p>
        </div>

        <a
            href="{{ route('paiements.create', [
                'school_year_id' => $schoolYear->id,
                'mois' => $selectedMonth,
                'annee' => $selectedYear,
            ]) }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg
                   bg-green-600 px-5 py-3 text-sm font-semibold text-white
                   shadow-sm transition hover:bg-green-700"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path d="M12 5v14"/>
                <path d="M5 12h14"/>
            </svg>

            Enregistrer un paiement
        </a>

        <a
            href="{{ route('paiements.tarifs.index', [
                'school_year_id' => $schoolYear->id,
            ]) }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg
                   border border-blue-200 bg-blue-50 px-4 py-2.5
                   text-sm font-semibold text-blue-800
                   transition hover:bg-blue-100"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path d="M12 3v18"/>
                <path d="M3 12h18"/>
                <path d="M5 7h14"/>
                <path d="M5 17h14"/>
            </svg>

            Tarifs d’écolage
        </a>

    </div>


    <div class="flex flex-wrap gap-3">
        <a href="{{ route('paiements.droits.index', ['school_year_id' => $schoolYear->id]) }}"
           class="inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-900 transition hover:bg-blue-100">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 8v8m-4-4h8M4 5h16v14H4z"/>
            </svg>
            Gérer les droits d'inscription
        </a>
    </div>

    {{-- ============================================================
         MESSAGE DE SUCCÈS
    ============================================================ --}}
    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif


    {{-- ============================================================
         ERREURS
    ============================================================ --}}
    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <div class="font-semibold">
                Impossible d'effectuer l'opération.
            </div>

            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- ============================================================
         FILTRES
    ============================================================ --}}
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="mb-4">
            <h2 class="text-base font-bold text-slate-900">
                Filtres du suivi
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Sélectionnez la période et utilisez les filtres pour suivre les paiements.
            </p>
        </div>

        <form
            method="GET"
            action="{{ route('paiements.index') }}"
            class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6"
        >

            {{-- Année scolaire --}}
            <div>
                <label
                    for="school_year_id"
                    class="mb-1.5 block text-sm font-semibold text-slate-700"
                >
                    Année scolaire
                </label>

                <select
                    id="school_year_id"
                    name="school_year_id"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5
                           text-sm text-slate-800 focus:border-blue-700 focus:outline-none
                           focus:ring-2 focus:ring-blue-100"
                >
                    @foreach($schoolYears as $yearItem)
                        <option
                            value="{{ $yearItem->id }}"
                            @selected($yearItem->id == $schoolYear->id)
                        >
                            {{ $yearItem->name }}
                            @if($yearItem->is_active)
                                — active
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- Mois --}}
            <div>
                <label
                    for="mois"
                    class="mb-1.5 block text-sm font-semibold text-slate-700"
                >
                    Mois
                </label>

                <select
                    id="mois"
                    name="mois"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5
                           text-sm text-slate-800 focus:border-blue-700 focus:outline-none
                           focus:ring-2 focus:ring-blue-100"
                >
                    @foreach($mois as $numero => $nomMois)
                        <option
                            value="{{ $numero }}"
                            @selected($numero == $selectedMonth)
                        >
                            {{ $nomMois }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- Année --}}
            <div>
                <label
                    for="annee"
                    class="mb-1.5 block text-sm font-semibold text-slate-700"
                >
                    Année
                </label>

                <input
                    type="number"
                    id="annee"
                    name="annee"
                    value="{{ $selectedYear }}"
                    min="2000"
                    max="2100"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5
                           text-sm text-slate-800 focus:border-blue-700 focus:outline-none
                           focus:ring-2 focus:ring-blue-100"
                >
            </div>


            {{-- Classe --}}
            <div>
                <label
                    for="class_room_id"
                    class="mb-1.5 block text-sm font-semibold text-slate-700"
                >
                    Classe
                </label>

                <select
                    id="class_room_id"
                    name="class_room_id"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5
                           text-sm text-slate-800 focus:border-blue-700 focus:outline-none
                           focus:ring-2 focus:ring-blue-100"
                >
                    <option value="">
                        Toutes les classes
                    </option>

                    @foreach($classes as $classRoom)
                        <option
                            value="{{ $classRoom->id }}"
                            @selected($classRoom->id == $selectedClassRoom)
                        >
                            {{ $classRoom->nom_classe }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- Statut --}}
            <div>
                <label
                    for="statut"
                    class="mb-1.5 block text-sm font-semibold text-slate-700"
                >
                    Statut
                </label>

                <select
                    id="statut"
                    name="statut"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5
                           text-sm text-slate-800 focus:border-blue-700 focus:outline-none
                           focus:ring-2 focus:ring-blue-100"
                >
                    <option value="">
                        Tous
                    </option>

                    <option
                        value="paye"
                        @selected($selectedStatus === 'paye')
                    >
                        Payé
                    </option>

                    <option
                        value="partiel"
                        @selected($selectedStatus === 'partiel')
                    >
                        Partiel
                    </option>

                    <option
                        value="impaye"
                        @selected($selectedStatus === 'impaye')
                    >
                        Impayé
                    </option>

                    <option
                        value="tarif_non_defini"
                        @selected($selectedStatus === 'tarif_non_defini')
                    >
                        Tarif non défini
                    </option>
                </select>
            </div>


            {{-- Recherche --}}
            <div>
                <label
                    for="search"
                    class="mb-1.5 block text-sm font-semibold text-slate-700"
                >
                    Recherche
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Nom ou matricule..."
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5
                           text-sm text-slate-800 focus:border-blue-700 focus:outline-none
                           focus:ring-2 focus:ring-blue-100"
                >
            </div>


            {{-- Boutons --}}
            <div class="flex items-end gap-2 md:col-span-2 xl:col-span-6">

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-900
                           px-5 py-2.5 text-sm font-semibold text-white
                           transition hover:bg-blue-800"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m20 20-4-4"/>
                    </svg>

                    Appliquer
                </button>

                <a
                    href="{{ route('paiements.index', [
                        'school_year_id' => $schoolYear->id,
                        'mois' => $selectedMonth,
                        'annee' => $selectedYear,
                    ]) }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300
                           bg-white px-5 py-2.5 text-sm font-semibold text-slate-700
                           transition hover:bg-slate-50"
                >
                    Réinitialiser
                </a>

            </div>

        </form>
    </div>


    {{-- ============================================================
         STATISTIQUES
    ============================================================ --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Attendu --}}
        <div class="rounded-xl border border-blue-100 bg-blue-50 p-5">
            <p class="text-sm font-medium text-blue-700">
                Total attendu
            </p>

            <p class="mt-2 text-2xl font-bold text-blue-950">
                {{ number_format($stats['total_expected'], 0, ',', ' ') }} Ar
            </p>

            <p class="mt-1 text-xs text-blue-700">
                Pour les élèves avec tarif défini
            </p>
        </div>


        {{-- Payé --}}
        <div class="rounded-xl border border-green-100 bg-green-50 p-5">
            <p class="text-sm font-medium text-green-700">
                Total encaissé
            </p>

            <p class="mt-2 text-2xl font-bold text-green-900">
                {{ number_format($stats['total_paid'], 0, ',', ' ') }} Ar
            </p>

            <p class="mt-1 text-xs text-green-700">
                Paiements enregistrés
            </p>
        </div>


        {{-- Reste --}}
        <div class="rounded-xl border border-orange-100 bg-orange-50 p-5">
            <p class="text-sm font-medium text-orange-700">
                Reste à encaisser
            </p>

            <p class="mt-2 text-2xl font-bold text-orange-900">
                {{ number_format($stats['total_remaining'], 0, ',', ' ') }} Ar
            </p>

            <p class="mt-1 text-xs text-orange-700">
                Sur les tarifs configurés
            </p>
        </div>


        {{-- Taux --}}
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
            <p class="text-sm font-medium text-slate-600">
                Taux d'encaissement
            </p>

            <p class="mt-2 text-2xl font-bold text-slate-900">
                {{ number_format($stats['collection_rate'], 2, ',', ' ') }} %
            </p>

            <p class="mt-1 text-xs text-slate-600">
                {{ $stats['students'] }} élève(s) affiché(s)
            </p>
        </div>

    </div>


    {{-- ============================================================
         COMPTEURS STATUTS
    ============================================================ --}}
    <div class="grid grid-cols-2 gap-3 md:grid-cols-4">

        <div class="rounded-lg border border-green-200 bg-white px-4 py-3">
            <p class="text-xs font-medium uppercase tracking-wide text-green-700">
                Payés
            </p>

            <p class="mt-1 text-xl font-bold text-green-900">
                {{ $stats['count_paid'] }}
            </p>
        </div>

        <div class="rounded-lg border border-yellow-200 bg-white px-4 py-3">
            <p class="text-xs font-medium uppercase tracking-wide text-yellow-700">
                Partiels
            </p>

            <p class="mt-1 text-xl font-bold text-yellow-900">
                {{ $stats['count_partial'] }}
            </p>
        </div>

        <div class="rounded-lg border border-red-200 bg-white px-4 py-3">
            <p class="text-xs font-medium uppercase tracking-wide text-red-700">
                Impayés
            </p>

            <p class="mt-1 text-xl font-bold text-red-900">
                {{ $stats['count_unpaid'] }}
            </p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white px-4 py-3">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-600">
                Tarif non défini
            </p>

            <p class="mt-1 text-xl font-bold text-slate-900">
                {{ $stats['count_without_tariff'] }}
            </p>
        </div>

    </div>


    {{-- ============================================================
         TABLEAU
    ============================================================ --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-5 py-4">

            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h2 class="text-base font-bold text-slate-900">
                        Situation des élèves
                    </h2>

                    <p class="text-sm text-slate-500">
                        {{ $mois[$selectedMonth] ?? $selectedMonth }}
                        {{ $selectedYear }}
                    </p>
                </div>

                <div class="text-sm text-slate-500">
                    {{ $rows->count() }} résultat(s)
                </div>

            </div>

        </div>


        @if($rows->isEmpty())

            <div class="px-6 py-12 text-center">

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-slate-500"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M9 12h6"/>
                        <path d="M9 16h6"/>
                        <path d="M5 4h14v16H5z"/>
                    </svg>
                </div>

                <h3 class="mt-4 text-base font-semibold text-slate-900">
                    Aucun résultat
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Aucun élève ne correspond aux filtres sélectionnés.
                </p>

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Élève
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Classe
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Tarif
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Payé
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Reste
                            </th>

                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Statut
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100 bg-white">

                        @foreach($rows as $row)

                            <tr class="transition hover:bg-slate-50">

                                {{-- Élève --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    <div class="font-semibold text-slate-900">
                                        {{ $row['student']->nom }}
                                        {{ $row['student']->prenom }}
                                    </div>

                                    <div class="mt-0.5 text-xs text-slate-500">
                                        {{ $row['student']->matricule }}
                                    </div>

                                </td>


                                {{-- Classe --}}
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-700">
                                    {{ $row['class_room']->nom_classe }}
                                </td>


                                {{-- Tarif --}}
                                <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-medium text-slate-800">

                                    @if($row['montant_attendu'] !== null)
                                        {{ number_format($row['montant_attendu'], 0, ',', ' ') }} Ar
                                    @else
                                        <span class="text-slate-400">
                                            Non défini
                                        </span>
                                    @endif

                                </td>


                                {{-- Payé --}}
                                <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-semibold text-green-700">
                                    {{ number_format($row['montant_paye'], 0, ',', ' ') }} Ar
                                </td>


                                {{-- Reste --}}
                                <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-semibold text-orange-700">

                                    @if($row['reste'] !== null)
                                        {{ number_format($row['reste'], 0, ',', ' ') }} Ar
                                    @else
                                        —
                                    @endif

                                </td>


                                {{-- Statut --}}
                                <td class="whitespace-nowrap px-5 py-4 text-center">

                                    @if($row['statut'] === 'paye')

                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                            Payé
                                        </span>

                                    @elseif($row['statut'] === 'partiel')

                                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                                            Partiel
                                        </span>

                                    @elseif($row['statut'] === 'impaye')

                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
                                            Impayé
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                            Tarif non défini
                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="whitespace-nowrap px-5 py-4 text-right">

                                    @if($row['montant_paye'] > 0)

                                        @php
                                            $lastPayment = \App\Models\Paiement::where('establishment_id', $establishment->id)
                                                ->where('school_year_id', $schoolYear->id)
                                                ->where('student_id', $row['student']->id)
                                                ->where('type_paiement', 'Écolage mensuel')
                                                ->where('mois', $selectedMonth)
                                                ->where('annee', $selectedYear)
                                                ->latest('id')
                                                ->first();
                                        @endphp

                                        @if($lastPayment)

                                            <a
                                                href="{{ route('paiements.recu', $lastPayment) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-blue-200
                                                       bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-800
                                                       transition hover:bg-blue-100"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >
                                                    <path d="M6 3h12v18H6z"/>
                                                    <path d="M9 7h6"/>
                                                    <path d="M9 11h6"/>
                                                    <path d="M9 15h4"/>
                                                </svg>

                                                Reçu
                                            </a>

                                        @endif

                                    @else

                                        <a
                                            href="{{ route('paiements.create', [
                                                'school_year_id' => $schoolYear->id,
                                                'student_id' => $row['student']->id,
                                                'mois' => $selectedMonth,
                                                'annee' => $selectedYear,
                                            ]) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-green-600
                                                   px-3 py-2 text-xs font-semibold text-white
                                                   transition hover:bg-green-700"
                                        >
                                            Payer
                                        </a>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</div>

@endsection
