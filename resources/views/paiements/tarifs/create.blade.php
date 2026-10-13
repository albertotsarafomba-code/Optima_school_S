@extends('layouts.gestion-ecole')

@section('title', 'Ajouter un tarif d’écolage')

@section('content')

<div class="mx-auto max-w-3xl space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Ajouter un tarif d’écolage
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
                        Vérifiez les informations saisies.
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
         FORMULAIRE
    ========================================================== --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-bold text-slate-900">
                Configuration du tarif mensuel
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Le tarif sera utilisé pour calculer automatiquement
                les statuts Payé, Partiel et Impayé.
            </p>

        </div>


        <form
            method="POST"
            action="{{ route('paiements.tarifs.store') }}"
            class="space-y-6 p-6"
        >

            @csrf


            {{-- =================================================
                 ANNÉE SCOLAIRE
            ================================================== --}}
            <div>

                <label
                    for="school_year_id"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Année scolaire
                </label>

                <select
                    id="school_year_id"
                    name="school_year_id"
                    required
                    class="w-full rounded-lg border border-slate-300 bg-white
                           px-3 py-3 text-sm text-slate-800
                           focus:border-blue-700 focus:outline-none
                           focus:ring-2 focus:ring-blue-100"
                >

                    @foreach($schoolYears as $yearItem)

                        <option
                            value="{{ $yearItem->id }}"
                            @selected(
                                old('school_year_id', $schoolYear->id)
                                == $yearItem->id
                            )
                        >
                            {{ $yearItem->name }}

                            @if($yearItem->is_active)
                                — active
                            @endif
                        </option>

                    @endforeach

                </select>

                <p class="mt-2 text-xs text-slate-500">
                    Le tarif sera associé uniquement à cette année scolaire.
                </p>

            </div>


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

                    <option value="">
                        Sélectionner une classe
                    </option>

                    @foreach($classes as $classRoom)

                        <option
                            value="{{ $classRoom->id }}"
                            @selected(
                                old('class_room_id', request('class_room_id'))
                                == $classRoom->id
                            )
                        >
                            {{ $classRoom->nom_classe }}

                            @if($classRoom->niveau)
                                — {{ $classRoom->niveau }}
                            @endif
                        </option>

                    @endforeach

                </select>

                @if($classes->isEmpty())

                    <p class="mt-2 text-sm text-orange-700">
                        Aucune classe n'est disponible pour cette année scolaire.
                    </p>

                @else

                    <p class="mt-2 text-xs text-slate-500">
                        Une seule configuration de tarif est autorisée
                        par classe et par année scolaire.
                    </p>

                @endif

            </div>


            {{-- =================================================
                 MONTANT
            ================================================== --}}
            <div>

                <label
                    for="montant_mensuel"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Montant mensuel
                </label>

                <div class="relative">

                    <input
                        type="number"
                        id="montant_mensuel"
                        name="montant_mensuel"
                        value="{{ old('montant_mensuel') }}"
                        min="0.01"
                        max="9999999999.99"
                        step="0.01"
                        required
                        placeholder="Ex. 150000"
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
                    Saisissez le montant officiel de l'écolage mensuel
                    pour cette classe.
                </p>

            </div>


            {{-- =================================================
                 RÉSUMÉ
            ================================================== --}}
            <div class="rounded-lg border border-blue-100 bg-blue-50 p-4">

                <div class="flex items-start gap-3">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="mt-0.5 h-5 w-5 shrink-0 text-blue-800"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 8v4"/>
                        <path d="M12 16h.01"/>
                    </svg>

                    <div class="text-sm text-blue-900">

                        <p class="font-semibold">
                            Utilisation du tarif
                        </p>

                        <p class="mt-1 leading-6">
                            Ce montant servira de base au suivi mensuel
                            de l'écolage. Le système comparera ce tarif
                            avec les paiements réellement enregistrés
                            pour déterminer automatiquement le statut
                            de chaque élève.
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

                    Enregistrer le tarif
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
