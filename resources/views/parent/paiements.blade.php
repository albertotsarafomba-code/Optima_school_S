@extends('layouts.parent')

@section('title', 'Paiements - OptimaSchool')

@section('content')
<div class="min-h-full bg-slate-50">

    <div class="mx-auto max-w-7xl px-6 py-8">

        {{-- En-tête --}}
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div class="flex items-center gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100 text-green-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 8c-1.657 0-3 1.12-3 2.5S10.343 13 12 13s3 1.12 3 2.5S13.657 18 12 18m0-10V6m0 12v-2m8-4a8 8 0 11-16 0 8 8 0 0116 0z"/>
                    </svg>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-green-600">
                        Espace famille
                    </p>

                    <h1 class="mt-1 text-2xl font-bold text-[#1e3a8a]">
                        Paiements & écolage
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Consultez les paiements enregistrés pour vos enfants.
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


        {{-- Contexte --}}
        <div class="mb-8 grid gap-5 md:grid-cols-2">

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Établissement
                </p>

                <p class="mt-2 text-base font-bold text-[#1e3a8a]">
                    {{ $currentEstablishment?->nom ?? '—' }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Année scolaire
                </p>

                <p class="mt-2 text-base font-bold text-green-700">
                    {{ $currentSchoolYear?->name
                        ?? $currentSchoolYear?->nom
                        ?? $currentSchoolYear?->annee_scolaire
                        ?? '—' }}
                </p>
            </div>

        </div>


        {{-- Paiements --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Historique des paiements
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Paiements enregistrés pour vos enfants.
                        </p>
                    </div>

                    <div class="rounded-lg bg-green-50 px-3 py-2 text-sm font-bold text-green-700">
                        {{ $paiements->count() }} paiement(s)
                    </div>

                </div>

            </div>


            @if($paiements->isEmpty())

                <div class="px-6 py-16 text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100">
                        <svg class="h-7 w-7 text-slate-400" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8c-1.657 0-3 1.12-3 2.5S10.343 13 12 13s3 1.12 3 2.5S13.657 18 12 18"/>
                        </svg>
                    </div>

                    <h3 class="mt-4 text-base font-bold text-slate-800">
                        Aucun paiement enregistré
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        Aucun paiement de vos enfants n'est disponible pour l'année scolaire sélectionnée.
                    </p>

                </div>

            @else

                {{-- Version cartes mobile --}}
                <div class="space-y-4 p-5 md:hidden">

                    @foreach($paiements as $paiement)

                        <article class="rounded-2xl border border-slate-200 bg-slate-50 p-4">

                            <div class="flex items-start justify-between gap-3">

                                <div>
                                    <p class="font-bold text-slate-900">
                                        {{ trim(($paiement->student?->nom ?? '') . ' ' . ($paiement->student?->prenom ?? '')) ?: 'Élève inconnu' }}
                                    </p>

                                    @if($paiement->student?->matricule)
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $paiement->student->matricule }}
                                        </p>
                                    @endif
                                </div>

                                <p class="text-sm font-bold text-[#1e3a8a]">
                                    {{ number_format((float) $paiement->montant, 0, ',', ' ') }} Ar
                                </p>

                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-3 text-sm">

                                <div class="rounded-xl bg-white p-3">
                                    <p class="text-xs text-slate-400">Type</p>
                                    <p class="mt-1 font-semibold text-slate-700">
                                        {{ $paiement->type_paiement ?? '—' }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-white p-3">
                                    <p class="text-xs text-slate-400">Mois</p>
                                    <p class="mt-1 font-semibold text-slate-700">
                                        {{ $paiement->nom_mois ?? '—' }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-white p-3">
                                    <p class="text-xs text-slate-400">Mode</p>
                                    <p class="mt-1 font-semibold text-slate-700">
                                        {{ $paiement->mode_paiement ?? '—' }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-white p-3">
                                    <p class="text-xs text-slate-400">Date</p>
                                    <p class="mt-1 font-semibold text-slate-700">
                                        {{ $paiement->date_paiement?->format('d/m/Y') ?? '—' }}
                                    </p>
                                </div>

                            </div>

                            <div class="mt-4">
                                @if($paiement->statut_verification === 'verifie')
                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                        Vérifié
                                    </span>
                                @elseif($paiement->statut_verification)
                                    <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                                        {{ ucfirst($paiement->statut_verification) }}
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                                        Enregistré
                                    </span>
                                @endif
                            </div>

                        </article>

                    @endforeach

                </div>


                {{-- Version bureau --}}
                <div class="hidden overflow-x-auto md:block">

                    <table class="min-w-full">

                        <thead class="border-b border-slate-200 bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Élève
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Type
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Mois
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Montant
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Mode
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Date
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Statut
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            @foreach($paiements as $paiement)

                                <tr class="transition hover:bg-slate-50">

                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-800">
                                            {{ trim(($paiement->student?->nom ?? '') . ' ' . ($paiement->student?->prenom ?? '')) ?: 'Élève inconnu' }}
                                        </p>

                                        @if($paiement->student?->matricule)
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $paiement->student->matricule }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-700">
                                        {{ $paiement->type_paiement ?? '—' }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-700">
                                        {{ $paiement->nom_mois ?? '—' }}
                                    </td>

                                    <td class="px-6 py-4 text-right text-sm font-bold text-[#1e3a8a]">
                                        {{ number_format((float) $paiement->montant, 0, ',', ' ') }} Ar
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-700">
                                        {{ $paiement->mode_paiement ?? 'Espèces' }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-700">
                                        {{ $paiement->date_paiement?->format('d/m/Y') ?? '—' }}
                                    </td>

                                    <td class="px-6 py-4">

                                        @if($paiement->statut_verification === 'verifie')
                                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                                Vérifié
                                            </span>
                                        @elseif($paiement->statut_verification)
                                            <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                                                {{ ucfirst($paiement->statut_verification) }}
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                                                Enregistré
                                            </span>
                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </section>

    </div>
</div>
@endsection
