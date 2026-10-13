@extends('layouts.gestion-ecole')

@section('title', 'Modifier un tarif d’écolage')

@section('content')

<div class="mx-auto max-w-3xl space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Modifier le tarif d’écolage
            </h1>

            <p class="mt-1 text-sm text-slate-600">
                {{ $establishment->nom }}
            </p>
        </div>

        <a
            href="{{ route('paiements.tarifs.index', [
                'school_year_id' => $schoolYear->id,
            ]) }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg
                   border border-slate-300 bg-white px-4 py-2.5
                   text-sm font-semibold text-slate-700
                   transition hover:bg-slate-50"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path d="M19 12H5"/>
                <path d="m12 19-7-7 7-7"/>
            </svg>

            Retour aux tarifs
        </a>

    </div>


    {{-- =========================================================
         ERREURS
    ========================================================== --}}
    @if($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <div class="flex items-start gap-3">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="mt-0.5 h-5 w-5 shrink-0 text-red-700"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M12 9v4"/>
                    <path d="M12 17h.01"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>

                <div>

                    <p class="font-semibold text-red-800">
                        Impossible de modifier le tarif.
                    </p>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         INFORMATIONS ACTUELLES
    ========================================================== --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">

            <h2 class="text-lg font-bold text-slate-900">
                Tarif actuel
            </h2>

            <div class="mt-4 grid gap-4 sm:grid-cols-3">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Année scolaire
                    </p>

                    <p class="mt-1 text-base font-bold text-slate-900">
                        {{ $ecolageTarif->schoolYear->name ?? $schoolYear->name }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Classe
                    </p>

                    <p class="mt-1 text-base font-bold text-slate-900">
                        {{ $ecolageTarif->classRoom->nom_classe ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Tarif mensuel actuel
                    </p>

                    <p class="mt-1 text-xl font-bold text-blue-800">
                        {{ number_format((float) $ecolageTarif->montant_mensuel, 0, ',', ' ') }}
                        Ar
                    </p>
                </div>

            </div>

        </div>


        {{-- =====================================================
             FORMULAIRE
        ====================================================== --}}
        <form
            method="POST"
            action="{{ route('paiements.tarifs.update', $ecolageTarif) }}"
            class="space-y-6 p-6"
        >

            @csrf
            @method('PUT')


            {{-- =================================================
                 CLASSE
            ================================================== --}}
            <div>

                <label
                    for="class_room_id"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Classe
                </label>

                <select
                    id="class_room_id"
                    name="class_room_id"
                    required
                    class="w-full rounded-lg border border-slate-300 bg-white
                           px-3 py-3 text-sm text-slate-800
                           focus:border-blue-700 focus:outline-none
                           focus:ring-2 focus:ring-blue-100"
                >

                    @foreach($classes as $classRoom)

                        <option
                            value="{{ $classRoom->id }}"
                            @selected(
                                old(
                                    'class_room_id',
                                    $ecolageTarif->class_room_id
                                ) == $classRoom->id
                            )
                        >
                            {{ $classRoom->nom_classe }}

                            @if($classRoom->niveau)
                                — {{ $classRoom->niveau }}
                            @endif
                        </option>

                    @endforeach

                </select>

                <p class="mt-2 text-xs text-slate-500">
                    La classe doit appartenir à l'établissement
                    et à l'année scolaire du tarif.
                </p>

            </div>


            {{-- =================================================
                 MONTANT
            ================================================== --}}
            <div>

                <label
                    for="montant_mensuel"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Nouveau montant mensuel
                </label>

                <div class="relative">

                    <input
                        type="number"
                        id="montant_mensuel"
                        name="montant_mensuel"
                        value="{{ old(
                            'montant_mensuel',
                            $ecolageTarif->montant_mensuel
                        ) }}"
                        min="0.01"
                        max="9999999999.99"
                        step="0.01"
                        required
                        class="w-full rounded-lg border border-slate-300
                               bg-white px-4 py-3 pr-16 text-base font-semibold
                               text-slate-900
                               focus:border-blue-700 focus:outline-none
                               focus:ring-2 focus:ring-blue-100"
                    >

                    <span
                        class="pointer-events-none absolute inset-y-0 right-0
                               flex items-center px-4 text-sm font-semibold
                               text-slate-500"
                    >
                        Ar
                    </span>

                </div>

                <p class="mt-2 text-xs text-slate-500">
                    Le nouveau montant remplacera le tarif mensuel actuel
                    pour cette classe et cette année scolaire.
                </p>

            </div>


            {{-- =================================================
                 AVERTISSEMENT
            ================================================== --}}
            <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">

                <div class="flex items-start gap-3">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="mt-0.5 h-5 w-5 shrink-0 text-amber-700"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M12 9v4"/>
                        <path d="M12 17h.01"/>
                        <path d="M10.3 3.9 2.9 17a2 2 0 0 0 1.7 3h14.8a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                    </svg>

                    <div class="text-sm text-amber-900">

                        <p class="font-semibold">
                            Attention
                        </p>

                        <p class="mt-1 leading-6">
                            La modification du tarif change la base de calcul
                            utilisée par le suivi des écolages.
                            Les paiements déjà enregistrés ne seront pas supprimés.
                        </p>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 BOUTONS
            ================================================== --}}
            <div class="flex flex-col-reverse gap-3 border-t border-slate-200
                        pt-6 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('paiements.tarifs.index', [
                        'school_year_id' => $schoolYear->id,
                    ]) }}"
                    class="inline-flex items-center justify-center rounded-lg
                           border border-slate-300 bg-white px-5 py-3
                           text-sm font-semibold text-slate-700
                           transition hover:bg-slate-50"
                >
                    Annuler
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg
                           bg-blue-700 px-5 py-3 text-sm font-semibold text-white
                           shadow-sm transition hover:bg-blue-800"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>

                    Enregistrer les modifications
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
