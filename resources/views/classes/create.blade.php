@extends('layouts.gestion-ecole')

@section('title', 'Ajouter des classes')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-3">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M4 6.5A2.5 2.5 0 016.5 4h11A2.5 2.5 0 0120 6.5v11a2.5 2.5 0 01-2.5 2.5h-11A2.5 2.5 0 014 17.5v-11z"/>

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M8 8h8M8 12h8M8 16h5"/>

                </svg>

            </div>

            <div>

                <h1 class="text-2xl font-bold text-slate-800">
                    Ajouter des classes
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Créez plusieurs classes en une seule opération.
                </p>

            </div>

        </div>

        <a href="{{ route('class_rooms.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">

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

            Retour aux classes

        </a>

    </div>


    {{-- ERREURS --}}
    @if ($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <div class="flex items-start gap-3">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 9v4m0 4h.01M10.29 3.86l-7.82 14a2 2 0 001.74 3h15.58a2 2 0 001.74-3l-7.82-14a2 2 0 00-3.42 0z"/>

                    </svg>

                </div>

                <div>

                    <h3 class="font-semibold text-red-800">
                        Vérifiez les informations saisies.
                    </h3>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- FORMULAIRE --}}
    <form method="POST"
          action="{{ route('class_rooms.store') }}"
          class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        @csrf


        {{-- TITRE --}}
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 4v16m8-8H4"/>

                    </svg>

                </div>

                <div>

                    <h2 class="font-bold text-slate-800">
                        Création de plusieurs classes
                    </h2>

                    <p class="text-sm text-slate-500">
                        Ajoutez autant de classes que nécessaire.
                    </p>

                </div>

            </div>

        </div>


        <div class="space-y-7 p-6">


            {{-- TYPE --}}
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600 text-white">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 9h.01M12 9h.01M15 9h.01"/>

                        </svg>

                    </div>

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">
                            Type d'établissement
                        </p>

                        <p class="text-lg font-bold capitalize text-blue-900">
                            {{ $typeName }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- ANNEE SCOLAIRE --}}
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div>

                    <label for="school_year_id"
                           class="mb-2 block text-sm font-semibold text-slate-700">

                        Année scolaire
                        <span class="text-red-500">*</span>

                    </label>

                    <select id="school_year_id"
                            name="school_year_id"
                            required
                            class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                        <option value="">
                            Sélectionner une année
                        </option>

                        @foreach ($schoolYears as $schoolYear)

                            @php

                                $schoolYearLabel =
                                    $schoolYear->nom
                                    ?? $schoolYear->name
                                    ?? $schoolYear->annee_scolaire
                                    ?? $schoolYear->libelle
                                    ?? '';

                            @endphp

                            <option value="{{ $schoolYear->id }}"
                                @selected(old('school_year_id') == $schoolYear->id)>

                                {{ $schoolYearLabel }}

                            </option>

                        @endforeach

                    </select>

                    @error('school_year_id')

                        <p class="mt-1.5 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                <div>

                    <label for="annee_scolaire"
                           class="mb-2 block text-sm font-semibold text-slate-700">

                        Libellé de l'année scolaire

                    </label>

                    <input type="text"
                           id="annee_scolaire"
                           name="annee_scolaire"
                           value="{{ old('annee_scolaire') }}"
                           maxlength="100"
                           placeholder="Ex. 2027-2028"
                           class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                    @error('annee_scolaire')

                        <p class="mt-1.5 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>


            {{-- UNIVERSITE : FILIERE GLOBALE NON UTILISEE --}}
            @if ($typeName === 'université')

                <div class="rounded-xl border border-purple-200 bg-purple-50 p-4">

                    <div class="flex items-start gap-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-purple-600 text-white">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 14l9-5-9-5-9 5 9 5z"/>

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M5 12v5c0 1.657 3.134 3 7 3s7-1.343 7-3v-5"/>

                            </svg>

                        </div>

                        <div>

                            <p class="font-semibold text-purple-900">
                                Université
                            </p>

                            <p class="mt-1 text-sm text-purple-700">
                                Pour chaque classe universitaire, vous pourrez
                                définir la filière séparément.
                            </p>

                        </div>

                    </div>

                </div>

            @endif


            {{-- LISTE DES CLASSES --}}
            <div>

                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h3 class="text-base font-bold text-slate-800">
                            Classes à créer
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Exemple : 5ème A, 5ème B, 5ème C.
                        </p>

                    </div>

                    <button type="button"
                            id="add-class"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 4v16m8-8H4"/>

                        </svg>

                        Ajouter une classe

                    </button>

                </div>


                <div id="classes-container"
                     class="space-y-4">

                    {{-- LES LIGNES SONT AJOUTÉES PAR JAVASCRIPT --}}

                </div>

            </div>


            {{-- INFORMATION --}}
            <div class="rounded-xl border border-green-200 bg-green-50 p-4">

                <div class="flex items-start gap-3">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-green-600 text-white">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z"/>

                        </svg>

                    </div>

                    <div>

                        <p class="font-semibold text-green-900">
                            Création multiple
                        </p>

                        <p class="mt-1 text-sm text-green-700">
                            Vous pouvez ajouter plusieurs classes avant
                            d'enregistrer. Toutes les classes seront créées
                            dans l'année scolaire sélectionnée.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end">

            <a href="{{ route('class_rooms.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">

                Annuler

            </a>

            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>

                </svg>

                Enregistrer les classes

            </button>

        </div>

    </form>

</div>


{{-- TEMPLATE D'UNE CLASSE --}}
<template id="class-row-template">

    <div class="class-row rounded-xl border border-slate-200 bg-slate-50 p-5 shadow-sm">

        <div class="mb-4 flex items-center justify-between gap-3">

            <div class="flex items-center gap-3">

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                    <span class="row-number font-bold">
                        1
                    </span>

                </div>

                <div>

                    <h4 class="font-bold text-slate-800">
                        Classe <span class="row-number-text">1</span>
                    </h4>

                    <p class="text-xs text-slate-500">
                        Informations de la classe
                    </p>

                </div>

            </div>

            <button type="button"
                    class="remove-class inline-flex items-center gap-2 rounded-lg border border-red-200 bg-white px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M6 7h12M9 7V4h6v3m-7 0l1 13h6l1-13M10 11v5m4-5v5"/>

                </svg>

                Supprimer

            </button>

        </div>


        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


            {{-- NIVEAU --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">

                    Niveau
                    <span class="text-red-500">*</span>

                </label>

                <select class="niveau-field block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        required>

                    <option value="">
                        Sélectionner un niveau
                    </option>

                    @foreach ($niveaux as $niveau)

                        <option value="{{ $niveau }}">
                            {{ $niveau }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- NOM --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">

                    Nom / Section
                    <span class="text-red-500">*</span>

                </label>

                <input type="text"
                       class="nom-field block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                       required
                       maxlength="150"
                       placeholder="Ex. A">

                <p class="mt-1.5 text-xs text-slate-500">
                    Exemple : A, B, C ou Groupe 1.
                </p>

            </div>


            {{-- FILIERE UNIVERSITE --}}
            @if ($typeName === 'université')

                <div class="md:col-span-2">

                    <label class="mb-2 block text-sm font-semibold text-slate-700">

                        Filière
                        <span class="text-red-500">*</span>

                    </label>

                    <input type="text"
                           class="filiere-field block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                           maxlength="150"
                           placeholder="Ex. Génie Informatique"
                           required>

                </div>

            @endif


            {{-- SALLE --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Salle
                </label>

                @if ($salles->count() > 0)

                    <select class="salle-field block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                        <option value="">
                            Aucune salle
                        </option>

                        @foreach ($salles as $salle)

                            <option value="{{ $salle->id }}">

                                {{ $salle->nom }}

                                @if ($salle->capacite)
                                    — {{ $salle->capacite }} places
                                @endif

                            </option>

                        @endforeach

                    </select>

                @else

                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-3">

                        <p class="text-sm font-semibold text-amber-800">
                            Aucune salle disponible.
                        </p>

                        <a href="{{ route('salles.create') }}"
                           class="mt-2 inline-flex items-center gap-2 text-sm font-semibold text-green-700 hover:text-green-800">

                            Ajouter une salle

                        </a>

                    </div>

                @endif

            </div>


            {{-- ENSEIGNANT --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">

                    Enseignant responsable

                </label>

                <select class="teacher-field block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                    <option value="">
                        Aucun enseignant
                    </option>

                    @foreach ($teachers as $teacher)

                        <option value="{{ $teacher->id }}">

                            {{ $teacher->nom }}
                            {{ $teacher->prenom }}

                        </option>

                    @endforeach

                </select>

            </div>


            {{-- CAPACITE --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Capacité
                </label>

                <input type="number"
                       class="capacite-field block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                       min="1"
                       max="10000"
                       placeholder="Ex. 30">

            </div>


            {{-- MINIMUM --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Effectif minimum
                </label>

                <input type="number"
                       class="minimum-field block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                       min="0"
                       max="10000"
                       placeholder="Ex. 10">

            </div>


            {{-- MAXIMUM --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Effectif maximum
                </label>

                <input type="number"
                       class="maximum-field block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                       min="1"
                       max="10000"
                       placeholder="Ex. 30">

            </div>

        </div>


        {{-- APERCU --}}
        <div class="preview mt-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3">

            <p class="text-xs font-semibold uppercase tracking-wide text-green-700">
                Nom généré
            </p>

            <p class="preview-text mt-1 text-base font-bold text-green-900">
                Sélectionnez les informations
            </p>

        </div>

    </div>

</template>


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const container =
        document.getElementById('classes-container');

    const template =
        document.getElementById('class-row-template');

    const addButton =
        document.getElementById('add-class');

    if (!container || !template || !addButton) {
        return;
    }

    let index = 0;


    function addClassRow(data = {}) {

        const fragment =
            template.content.cloneNode(true);

        const row =
            fragment.querySelector('.class-row');

        const niveauField =
            fragment.querySelector('.niveau-field');

        const nomField =
            fragment.querySelector('.nom-field');

        const filiereField =
            fragment.querySelector('.filiere-field');

        const salleField =
            fragment.querySelector('.salle-field');

        const teacherField =
            fragment.querySelector('.teacher-field');

        const capaciteField =
            fragment.querySelector('.capacite-field');

        const minimumField =
            fragment.querySelector('.minimum-field');

        const maximumField =
            fragment.querySelector('.maximum-field');

        const removeButton =
            fragment.querySelector('.remove-class');

        const previewText =
            fragment.querySelector('.preview-text');

        niveauField.name =
            `classes[${index}][niveau]`;

        nomField.name =
            `classes[${index}][nom]`;

        if (filiereField) {

            filiereField.name =
                `classes[${index}][filiere]`;
        }

        if (salleField) {

            salleField.name =
                `classes[${index}][salle_id]`;
        }

        if (teacherField) {

            teacherField.name =
                `classes[${index}][teacher_id]`;
        }

        capaciteField.name =
            `classes[${index}][capacite]`;

        minimumField.name =
            `classes[${index}][effectif_minimum]`;

        maximumField.name =
            `classes[${index}][effectif_maximum]`;


        /*
        |--------------------------------------------------------------------------
        | Valeurs
        |--------------------------------------------------------------------------
        */

        if (data.niveau) {
            niveauField.value =
                data.niveau;
        }

        if (data.nom) {
            nomField.value =
                data.nom;
        }

        if (filiereField && data.filiere) {
            filiereField.value =
                data.filiere;
        }

        if (salleField && data.salle_id) {
            salleField.value =
                data.salle_id;
        }

        if (teacherField && data.teacher_id) {
            teacherField.value =
                data.teacher_id;
        }

        if (data.capacite) {
            capaciteField.value =
                data.capacite;
        }

        if (data.effectif_minimum !== undefined) {
            minimumField.value =
                data.effectif_minimum;
        }

        if (data.effectif_maximum) {
            maximumField.value =
                data.effectif_maximum;
        }


        /*
        |--------------------------------------------------------------------------
        | Numéro
        |--------------------------------------------------------------------------
        */

        row.querySelectorAll('.row-number')
            .forEach(function (element) {
                element.textContent =
                    index + 1;
            });

        row.querySelectorAll('.row-number-text')
            .forEach(function (element) {
                element.textContent =
                    index + 1;
            });


        /*
        |--------------------------------------------------------------------------
        | Preview
        |--------------------------------------------------------------------------
        */

        function updatePreview() {

            const niveau =
                niveauField.value.trim();

            const nom =
                nomField.value.trim();

            let preview = '';

            @if ($typeName === 'université')

                const filiere =
                    filiereField
                        ? filiereField.value.trim()
                        : '';

                if (niveau && filiere && nom) {

                    preview =
                        niveau
                        + ' - '
                        + filiere
                        + ' - '
                        + nom;

                } else if (niveau && filiere) {

                    preview =
                        niveau
                        + ' - '
                        + filiere;

                } else if (niveau) {

                    preview =
                        niveau
                        + ' - Filière';

                } else {

                    preview =
                        'Sélectionnez les informations';
                }

            @else

                if (niveau && nom) {

                    preview =
                        niveau
                        + ' '
                        + nom;

                } else if (niveau) {

                    preview =
                        niveau
                        + ' ...';

                } else {

                    preview =
                        'Sélectionnez les informations';
                }

            @endif

            previewText.textContent =
                preview;
        }


        niveauField.addEventListener(
            'change',
            updatePreview
        );

        nomField.addEventListener(
            'input',
            updatePreview
        );

        if (filiereField) {

            filiereField.addEventListener(
                'input',
                updatePreview
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Suppression
        |--------------------------------------------------------------------------
        */

        removeButton.addEventListener(
            'click',
            function () {

                row.remove();

                refreshNumbers();
            }
        );


        container.appendChild(fragment);

        index++;

        updatePreview();

        refreshNumbers();
    }


    function refreshNumbers() {

        const rows =
            container.querySelectorAll('.class-row');

        rows.forEach(function (row, position) {

            row.querySelectorAll('.row-number')
                .forEach(function (element) {

                    element.textContent =
                        position + 1;
                });

            row.querySelectorAll('.row-number-text')
                .forEach(function (element) {

                    element.textContent =
                        position + 1;
                });
        });


        /*
        |--------------------------------------------------------------------------
        | Au moins une classe
        |--------------------------------------------------------------------------
        */

        if (rows.length === 1) {

            rows[0]
                .querySelector('.remove-class')
                .classList.add('hidden');

        } else {

            rows.forEach(function (row) {

                row.querySelector('.remove-class')
                    .classList.remove('hidden');
            });
        }
    }


    addButton.addEventListener(
        'click',
        function () {

            addClassRow();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Restauration après erreur Laravel
    |--------------------------------------------------------------------------
    */

    @if (old('classes'))

        const oldClasses =
            @json(old('classes'));

        if (
            Array.isArray(oldClasses)
            &&
            oldClasses.length > 0
        ) {

            oldClasses.forEach(function (item) {

                addClassRow(item);

            });

        } else {

            addClassRow();
        }

    @else

        addClassRow();

    @endif

});
</script>

@endpush

@endsection