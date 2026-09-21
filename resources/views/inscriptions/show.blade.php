@extends('layouts.gestion-ecole')

@section('title', 'Détails de l’inscription')

@section('content')

<div class="space-y-6">

    {{-- EN-TÊTE --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M14 3v5h5"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Détails de l'inscription
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Informations complètes concernant cette inscription scolaire
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">

            {{-- Retour --}}
            <a href="{{ route('inscriptions.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>

                Retour
            </a>

            {{-- Modifier --}}
            <a href="{{ route('inscriptions.edit', $inscription) }}"
               class="inline-flex items-center gap-2 rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M16.862 3.487a2.25 2.25 0 113.182 3.182L8.25 18.464 4 19.5l1.036-4.25L16.862 3.487z"/>
                </svg>

                Modifier
            </a>

        </div>
    </div>


    {{-- CARTE PRINCIPALE --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        {{-- Bandeau --}}
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-5">

            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <div class="flex items-center gap-4">

                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-700 text-white shadow-sm">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-7 w-7"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M4.5 20.118a7.5 7.5 0 0115 0A17.933 17.933 0 0112 21.75a17.933 17.933 0 01-7.5-1.632z"/>
                        </svg>

                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Étudiant
                        </p>

                        <h2 class="text-xl font-bold text-gray-800">
                            {{ $inscription->student->nom ?? '' }}
                            {{ $inscription->student->prenom ?? '' }}
                        </h2>

                        @if($inscription->student?->matricule)
                            <p class="mt-1 text-sm text-gray-500">
                                Matricule :
                                <span class="font-semibold text-blue-700">
                                    {{ $inscription->student->matricule }}
                                </span>
                            </p>
                        @endif
                    </div>

                </div>


                {{-- Statut --}}
                @php
                    $statut = strtolower(trim($inscription->statut ?? ''));

                    if (in_array($statut, ['actif', 'active', 'validé', 'valide'])) {
                        $statutClass = 'bg-green-100 text-green-700 border-green-200';
                    } elseif (in_array($statut, ['en attente', 'attente'])) {
                        $statutClass = 'bg-yellow-100 text-yellow-700 border-yellow-200';
                    } elseif (in_array($statut, ['annulé', 'annule', 'inactif'])) {
                        $statutClass = 'bg-red-100 text-red-700 border-red-200';
                    } else {
                        $statutClass = 'bg-gray-100 text-gray-700 border-gray-200';
                    }
                @endphp

                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold {{ $statutClass }}">

                        <span class="h-2 w-2 rounded-full bg-current"></span>

                        {{ $inscription->statut ?: 'Non défini' }}

                    </span>
                </div>

            </div>
        </div>


        {{-- CONTENU --}}
        <div class="p-6">

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                {{-- INFORMATIONS ÉTUDIANT --}}
                <div class="rounded-xl border border-gray-200 bg-white">

                    <div class="flex items-center gap-3 border-b border-gray-200 px-5 py-4">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M4.5 20.118a7.5 7.5 0 0115 0A17.933 17.933 0 0112 21.75a17.933 17.933 0 01-7.5-1.632z"/>
                            </svg>

                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-800">
                                Informations étudiant
                            </h3>

                            <p class="text-xs text-gray-500">
                                Identité de l'étudiant inscrit
                            </p>
                        </div>

                    </div>

                    <div class="divide-y divide-gray-100">

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-gray-500">
                                Nom complet
                            </span>

                            <span class="text-right text-sm font-semibold text-gray-800">
                                {{ $inscription->student->nom ?? '' }}
                                {{ $inscription->student->prenom ?? '' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-gray-500">
                                Matricule
                            </span>

                            <span class="text-right text-sm font-semibold text-blue-700">
                                {{ $inscription->student->matricule ?: 'Non renseigné' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-gray-500">
                                Date de naissance
                            </span>

                            <span class="text-right text-sm font-semibold text-gray-800">
                                @if($inscription->student?->date_naissance)
                                    {{ \Carbon\Carbon::parse($inscription->student->date_naissance)->format('d/m/Y') }}
                                @else
                                    Non renseignée
                                @endif
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-gray-500">
                                Sexe
                            </span>

                            <span class="text-right text-sm font-semibold text-gray-800">
                                {{ $inscription->student->sexe ?: 'Non renseigné' }}
                            </span>
                        </div>

                    </div>
                </div>


                {{-- INFORMATIONS SCOLAIRES --}}
                <div class="rounded-xl border border-gray-200 bg-white">

                    <div class="flex items-center gap-3 border-b border-gray-200 px-5 py-4">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-700">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M4.5 3.75h15A1.5 1.5 0 0121 5.25v13.5a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 013 18.75V5.25a1.5 1.5 0 011.5-1.5z"/>
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M7.5 7.5h9M7.5 11.25h9M7.5 15h5"/>
                            </svg>

                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-800">
                                Informations scolaires
                            </h3>

                            <p class="text-xs text-gray-500">
                                Classe et année scolaire
                            </p>
                        </div>

                    </div>

                    <div class="divide-y divide-gray-100">

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-gray-500">
                                Classe
                            </span>

                            <span class="text-right text-sm font-semibold text-gray-800">
                                {{ $inscription->classRoom->nom_classe ?? 'Non renseignée' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-gray-500">
                                Niveau
                            </span>

                            <span class="text-right text-sm font-semibold text-gray-800">
                                {{ $inscription->classRoom->niveau ?? 'Non renseigné' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-gray-500">
                                Filière
                            </span>

                            <span class="text-right text-sm font-semibold text-gray-800">
                                {{ $inscription->classRoom->filiere ?? 'Non renseignée' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-gray-500">
                                Année scolaire
                            </span>

                            <span class="text-right text-sm font-semibold text-blue-700">
                                {{ $inscription->annee_scolaire ?: ($inscription->schoolYear->name ?? 'Non renseignée') }}
                            </span>
                        </div>

                    </div>
                </div>


                {{-- DÉTAILS DE L'INSCRIPTION --}}
                <div class="rounded-xl border border-gray-200 bg-white">

                    <div class="flex items-center gap-3 border-b border-gray-200 px-5 py-4">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/>
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M14 3v5h5"/>
                            </svg>

                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-800">
                                Détails de l'inscription
                            </h3>

                            <p class="text-xs text-gray-500">
                                Informations administratives
                            </p>
                        </div>

                    </div>

                    <div class="divide-y divide-gray-100">

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-gray-500">
                                Date d'inscription
                            </span>

                            <span class="text-right text-sm font-semibold text-gray-800">
                                @if($inscription->date_inscription)
                                    {{ \Carbon\Carbon::parse($inscription->date_inscription)->format('d/m/Y') }}
                                @else
                                    Non renseignée
                                @endif
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-gray-500">
                                Situation scolaire
                            </span>

                            <span class="text-right text-sm font-semibold text-gray-800">
                                {{ $inscription->situation_scolaire ?: 'Non renseignée' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-gray-500">
                                Statut
                            </span>

                            <span class="text-right text-sm font-semibold text-gray-800">
                                {{ $inscription->statut ?: 'Non renseigné' }}
                            </span>
                        </div>

                    </div>
                </div>


                {{-- ÉTABLISSEMENT --}}
                <div class="rounded-xl border border-gray-200 bg-white">

                    <div class="flex items-center gap-3 border-b border-gray-200 px-5 py-4">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-700">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M3.75 21h16.5M5.25 21V6.75A1.5 1.5 0 016.75 5.25h10.5a1.5 1.5 0 011.5 1.5V21M9 9h1.5M9 12h1.5M9 15h1.5M13.5 9H15M13.5 12H15M13.5 15H15M8.25 21v-3.75h7.5V21"/>
                            </svg>

                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-800">
                                Établissement
                            </h3>

                            <p class="text-xs text-gray-500">
                                Établissement concerné par l'inscription
                            </p>
                        </div>

                    </div>

                    <div class="divide-y divide-gray-100">

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-gray-500">
                                Nom
                            </span>

                            <span class="text-right text-sm font-semibold text-gray-800">
                                {{ $establishment->nom ?? 'Non renseigné' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-gray-500">
                                Adresse
                            </span>

                            <span class="text-right text-sm font-semibold text-gray-800">
                                {{ $establishment->adresse ?? 'Non renseignée' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-gray-500">
                                Téléphone
                            </span>

                            <span class="text-right text-sm font-semibold text-gray-800">
                                {{ $establishment->telephone ?? 'Non renseigné' }}
                            </span>
                        </div>

                    </div>
                </div>

            </div>


            {{-- ACTIONS --}}
            <div class="mt-6 flex flex-col gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-between">

                <a href="{{ route('inscriptions.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>

                    Retour aux inscriptions
                </a>


                <div class="flex flex-col gap-3 sm:flex-row">

                    <a href="{{ route('inscriptions.edit', $inscription) }}"
                       class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M16.862 3.487a2.25 2.25 0 113.182 3.182L8.25 18.464 4 19.5l1.036-4.25L16.862 3.487z"/>
                        </svg>

                        Modifier l'inscription
                    </a>


                    <form action="{{ route('inscriptions.destroy', $inscription) }}"
                          method="POST"
                          onsubmit="return confirm('Voulez-vous vraiment supprimer cette inscription ?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M6 7.5h12M9.75 7.5V5.25A1.25 1.25 0 0111 4h2a1.25 1.25 0 011.25 1.25V7.5m2.25 0v11.25A1.25 1.25 0 0115.25 20h-6.5a1.25 1.25 0 01-1.25-1.25V7.5m2.25 3v6m3-6v6"/>
                            </svg>

                            Supprimer
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection