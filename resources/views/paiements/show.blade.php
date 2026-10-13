@extends('layouts.gestion-ecole')

@section('title', 'Détail du paiement')

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- En-tête --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Détail du paiement
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Consultation des informations du paiement
            </p>
        </div>

        <div class="flex gap-3">

            <a href="{{ route('paiements.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg
                      bg-gray-100 text-gray-700 font-semibold
                      hover:bg-gray-200 transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>

                Retour
            </a>

            <a href="{{ route('paiements.edit', $paiement) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg
                      bg-blue-700 text-white font-semibold
                      hover:bg-blue-800 transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M19.5 7.125L16.875 4.5"/>
                </svg>

                Modifier
            </a>

        </div>

    </div>


    {{-- Message succès --}}
    @if(session('success'))

        <div class="mb-6 rounded-lg border border-green-200 bg-green-50
                    px-4 py-3 text-green-800">

            {{ session('success') }}

        </div>

    @endif


    {{-- Carte principale --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Bandeau reçu --}}
        <div class="px-6 py-5 bg-blue-900 text-white">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>

                    <p class="text-sm text-blue-100">
                        Numéro de reçu
                    </p>

                    <p class="text-xl font-bold mt-1">
                        {{ $paiement->numero_recu ?? 'Non attribué' }}
                    </p>

                </div>

                <div class="text-left md:text-right">

                    <p class="text-sm text-blue-100">
                        Montant payé
                    </p>

                    <p class="text-2xl font-bold mt-1">
                        {{ number_format((float) $paiement->montant, 2, ',', ' ') }} Ar
                    </p>

                </div>

            </div>

        </div>


        {{-- Informations générales --}}
        <div class="p-6">

            <h2 class="text-lg font-bold text-gray-800 mb-4">
                Informations générales
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Établissement --}}
                <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Établissement
                    </p>

                    <p class="mt-1 text-gray-800 font-semibold">
                        {{ $paiement->establishment?->nom ?? 'Non renseigné' }}
                    </p>

                </div>


                {{-- Année scolaire --}}
                <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Année scolaire
                    </p>

                    <p class="mt-1 text-gray-800 font-semibold">
                        {{ $paiement->schoolYear?->name ?? 'Non renseignée' }}
                    </p>

                </div>


                {{-- Type --}}
                <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Type de paiement
                    </p>

                    <p class="mt-1 text-gray-800 font-semibold">
                        {{ $paiement->type_paiement }}
                    </p>

                </div>


                {{-- Mode --}}
                <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Mode de paiement
                    </p>

                    <p class="mt-1 text-gray-800 font-semibold">
                        {{ $paiement->mode_paiement }}
                    </p>

                </div>


                {{-- Date --}}
                <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Date du paiement
                    </p>

                    <p class="mt-1 text-gray-800 font-semibold">
                        {{ $paiement->date_paiement?->format('d/m/Y') ?? '-' }}
                    </p>

                </div>


                {{-- Référence --}}
                <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Référence
                    </p>

                    <p class="mt-1 text-gray-800 font-semibold">
                        {{ $paiement->reference ?? '-' }}
                    </p>

                </div>

            </div>


            {{-- Bénéficiaire --}}
            <div class="mt-8">

                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    Bénéficiaire
                </h2>

                @if($paiement->student)

                    <div class="rounded-xl border border-blue-100 bg-blue-50 p-5">

                        <div class="flex items-start gap-4">

                            <div class="w-11 h-11 rounded-lg bg-blue-700
                                        text-white flex items-center justify-center
                                        shrink-0">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-6 h-6"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="2">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M4.5 20.25a8.25 8.25 0 0115 0"/>
                                </svg>

                            </div>

                            <div>

                                <p class="text-xs font-semibold uppercase
                                          tracking-wide text-blue-700">
                                    Élève
                                </p>

                                <p class="text-lg font-bold text-gray-800 mt-1">
                                    {{ $paiement->student->nom }}
                                    {{ $paiement->student->prenom }}
                                </p>

                                <p class="text-sm text-gray-600 mt-1">
                                    Matricule :
                                    <span class="font-semibold">
                                        {{ $paiement->student->matricule ?? '-' }}
                                    </span>
                                </p>

                            </div>

                        </div>

                    </div>

                @elseif($paiement->teacher)

                    <div class="rounded-xl border border-green-100 bg-green-50 p-5">

                        <div class="flex items-start gap-4">

                            <div class="w-11 h-11 rounded-lg bg-green-700
                                        text-white flex items-center justify-center
                                        shrink-0">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-6 h-6"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="2">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M4.5 20.25a8.25 8.25 0 0115 0"/>
                                </svg>

                            </div>

                            <div>

                                <p class="text-xs font-semibold uppercase
                                          tracking-wide text-green-700">
                                    Enseignant
                                </p>

                                <p class="text-lg font-bold text-gray-800 mt-1">
                                    {{ $paiement->teacher->nom }}
                                    {{ $paiement->teacher->prenom }}
                                </p>

                                @if($paiement->teacher->specialite)

                                    <p class="text-sm text-gray-600 mt-1">
                                        Spécialité :
                                        <span class="font-semibold">
                                            {{ $paiement->teacher->specialite }}
                                        </span>
                                    </p>

                                @endif

                            </div>

                        </div>

                    </div>

                @else

                    <div class="rounded-lg border border-yellow-200 bg-yellow-50
                                p-4 text-yellow-800">

                        Bénéficiaire non renseigné.

                    </div>

                @endif

            </div>


            {{-- Informations écolage --}}
            @if($paiement->isEcolageMensuel())

                <div class="mt-8">

                    <h2 class="text-lg font-bold text-gray-800 mb-4">
                        Période de l'écolage
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div class="rounded-lg border border-indigo-100
                                    bg-indigo-50 p-4">

                            <p class="text-xs font-semibold uppercase
                                      tracking-wide text-indigo-600">
                                Mois concerné
                            </p>

                            <p class="mt-1 text-lg font-bold text-gray-800">
                                {{ $paiement->nom_mois ?? '-' }}
                            </p>

                        </div>

                        <div class="rounded-lg border border-indigo-100
                                    bg-indigo-50 p-4">

                            <p class="text-xs font-semibold uppercase
                                      tracking-wide text-indigo-600">
                                Année du mois payé
                            </p>

                            <p class="mt-1 text-lg font-bold text-gray-800">
                                {{ $paiement->annee ?? '-' }}
                            </p>

                        </div>

                    </div>

                </div>

            @endif


            {{-- Description --}}
            <div class="mt-8">

                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    Description
                </h2>

                <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">

                    <p class="text-gray-700 whitespace-pre-line">
                        {{ $paiement->description ?? 'Aucune description.' }}
                    </p>

                </div>

            </div>


            {{-- Reçu disponible immédiatement --}}
            <div class="mt-8 rounded-xl border border-green-200 bg-green-50 p-5">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div>
                        <h2 class="text-lg font-bold text-gray-800">
                            Paiement enregistré
                        </h2>

                        <p class="text-sm text-gray-600 mt-1">
                            Ce paiement est enregistré en espèces.
                            Le reçu peut être consulté ou imprimé immédiatement.
                        </p>
                    </div>

                    <a href="{{ route('paiements.recu', $paiement) }}"
                       target="_blank"
                       class="inline-flex justify-center items-center gap-2
                              px-5 py-3 rounded-lg
                              bg-green-700 text-white font-semibold
                              hover:bg-green-800 transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M6 9V4.5A1.5 1.5 0 017.5 3h9A1.5 1.5 0 0118 4.5V9"/>

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M6 18h12"/>

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M6 13.5h12A1.5 1.5 0 0119.5 15v4.5A1.5 1.5 0 0118 21H6a1.5 1.5 0 01-1.5-1.5V15A1.5 1.5 0 016 13.5z"/>
                        </svg>

                        Voir / imprimer le reçu
                    </a>

                </div>

            </div>


            {{-- Actions --}}
            <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-3">

                <a href="{{ route('paiements.edit', $paiement) }}"
                   class="inline-flex justify-center items-center gap-2
                          px-5 py-3 rounded-lg
                          bg-blue-700 text-white font-semibold
                          hover:bg-blue-800 transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M19.5 7.125L16.875 4.5"/>
                    </svg>

                    Modifier le paiement
                </a>


                <a href="{{ route('paiements.index') }}"
                   class="inline-flex justify-center items-center gap-2
                          px-5 py-3 rounded-lg
                          bg-gray-100 text-gray-700 font-semibold
                          hover:bg-gray-200 transition">

                    Retour à la liste

                </a>

            </div>

        </div>

    </div>

</div>

@endsection
