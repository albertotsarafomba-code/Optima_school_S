@extends('layouts.gestion-ecole')

@section('title', 'Modifier une classe')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-3">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-white">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4 6.5A2.5 2.5 0 016.5 4h11A2.5 2.5 0 0120 6.5v11a2.5 2.5 0 01-2.5 2.5h-11A2.5 2.5 0 014 17.5v-11z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 8h8M8 12h8M8 16h5"
                    />
                </svg>
            </div>

            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Modifier la classe
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Modifiez les informations de la classe.
                </p>
            </div>

        </div>

        <a
            href="{{ route('class_rooms.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
        >
            Retour aux classes
        </a>

    </div>


    {{-- ERREURS --}}
    @if ($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <h3 class="font-semibold text-red-800">
                Vérifiez les informations saisies.
            </h3>

            <ul class="mt-2 list-disc pl-5 text-sm text-red-700">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- FORMULAIRE --}}
    <form
        method="POST"
        action="{{ route('class_rooms.update', $classRoom) }}"
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
    >

        @csrf
        @method('PUT')


        {{-- TITRE --}}
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">

            <h2 class="font-bold text-slate-800">
                Structure de la classe
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Modifiez les informations de la classe selon le type de votre établissement.
            </p>

        </div>


        <div class="space-y-7 p-6">


            {{-- TYPE D'ÉTABLISSEMENT --}}
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">

                <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">
                    Type d'établissement
                </p>

                <p class="mt-1 text-lg font-bold capitalize text-blue-900">
                    {{ $typeName }}
                </p>

            </div>


            {{-- NOM CLASSE --}}
            <div>

                <label
                    for="nom_classe"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Nom de la classe
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="nom_classe"
                    name="nom_classe"
                    value="{{ old('nom_classe', $classRoom->nom_classe) }}"
                    required
                    maxlength="255"
                    placeholder="Ex. 6ème A"
                    class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

                @error('nom_classe')
                    <p class="mt-1.5 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- NIVEAU --}}
            <div>

                <label
                    for="niveau"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Niveau
                    <span class="text-red-500">*</span>
                </label>

                <select
                    id="niveau"
                    name="niveau"
                    required
                    class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

                    <option value="">
                        Sélectionner un niveau
                    </option>

                    @foreach ($niveaux as $niveau)

                        <option
                            value="{{ $niveau }}"
                            @selected(old('niveau', $classRoom->niveau) === $niveau)
                        >
                            {{ $niveau }}
                        </option>

                    @endforeach

                </select>

                @error('niveau')
                    <p class="mt-1.5 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- FILIÈRE UNIVERSITÉ --}}
            @if (
                $typeName === 'université' ||
                $typeName === 'universite'
            )

                <div>

                    <label
                        for="filiere"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Filière
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="filiere"
                        name="filiere"
                        value="{{ old('filiere', $classRoom->filiere) }}"
                        required
                        maxlength="255"
                        placeholder="Ex. Génie Informatique"
                        class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                    @error('filiere')
                        <p class="mt-1.5 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            @endif


            {{-- ANNÉE + SALLE --}}
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                {{-- ANNÉE SCOLAIRE --}}
                <div>

                    <label
                        for="school_year_id"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Année scolaire
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="school_year_id"
                        name="school_year_id"
                        required
                        class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                        <option value="">
                            Sélectionner une année
                        </option>

                        @foreach ($schoolYears as $schoolYear)

                            @php
                                $label =
                                    $schoolYear->nom
                                    ?? $schoolYear->name
                                    ?? $schoolYear->annee_scolaire
                                    ?? $schoolYear->libelle
                                    ?? '';
                            @endphp

                            <option
                                value="{{ $schoolYear->id }}"
                                @selected(
                                    old(
                                        'school_year_id',
                                        $classRoom->school_year_id
                                    ) == $schoolYear->id
                                )
                            >
                                {{ $label }}
                            </option>

                        @endforeach

                    </select>

                    @error('school_year_id')
                        <p class="mt-1.5 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- SALLE --}}
                <div>

                    <label
                        for="salle_id"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Salle
                    </label>

                    <select
                        id="salle_id"
                        name="salle_id"
                        class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                        <option value="">
                            Aucune salle
                        </option>

                        @foreach ($salles as $salle)

                            <option
                                value="{{ $salle->id }}"
                                @selected(
                                    old(
                                        'salle_id',
                                        $classRoom->salle_id
                                    ) == $salle->id
                                )
                            >
                                {{ $salle->nom }}

                                @if ($salle->capacite)
                                    — {{ $salle->capacite }} places
                                @endif
                            </option>

                        @endforeach

                    </select>

                    @error('salle_id')
                        <p class="mt-1.5 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>


            {{-- ENSEIGNANT --}}
            <div>

                <label
                    for="teacher_id"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Enseignant responsable
                </label>

                <select
                    id="teacher_id"
                    name="teacher_id"
                    class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

                    <option value="">
                        Aucun enseignant
                    </option>

                    @foreach ($teachers as $teacher)

                        <option
                            value="{{ $teacher->id }}"
                            @selected(
                                old(
                                    'teacher_id',
                                    $classRoom->teacher_id
                                ) == $teacher->id
                            )
                        >
                            {{ $teacher->nom }}
                            {{ $teacher->prenom }}
                        </option>

                    @endforeach

                </select>

                @error('teacher_id')
                    <p class="mt-1.5 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- ANNÉE SCOLAIRE TEXTE --}}
            <div>

                <label
                    for="annee_scolaire"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Libellé de l'année scolaire
                </label>

                <input
                    type="text"
                    id="annee_scolaire"
                    name="annee_scolaire"
                    value="{{ old('annee_scolaire', $classRoom->annee_scolaire) }}"
                    maxlength="255"
                    placeholder="Ex. 2026-2027"
                    class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

                @error('annee_scolaire')
                    <p class="mt-1.5 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- EFFECTIFS --}}
            <div>

                <h3 class="mb-4 text-base font-bold text-slate-800">
                    Capacité et effectifs
                </h3>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">


                    {{-- CAPACITÉ --}}
                    <div>

                        <label
                            for="capacite"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Capacité
                        </label>

                        <input
                            type="number"
                            id="capacite"
                            name="capacite"
                            value="{{ old('capacite', $classRoom->capacite) }}"
                            min="1"
                            max="10000"
                            class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >

                        @error('capacite')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- MINIMUM --}}
                    <div>

                        <label
                            for="effectif_minimum"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Effectif minimum
                        </label>

                        <input
                            type="number"
                            id="effectif_minimum"
                            name="effectif_minimum"
                            value="{{ old('effectif_minimum', $classRoom->effectif_minimum) }}"
                            min="0"
                            max="10000"
                            class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >

                        @error('effectif_minimum')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- MAXIMUM --}}
                    <div>

                        <label
                            for="effectif_maximum"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Effectif maximum
                        </label>

                        <input
                            type="number"
                            id="effectif_maximum"
                            name="effectif_maximum"
                            value="{{ old('effectif_maximum', $classRoom->effectif_maximum) }}"
                            min="1"
                            max="10000"
                            class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >

                        @error('effectif_maximum')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end">

            <a
                href="{{ route('class_rooms.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100"
            >
                Annuler
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
            >
                Enregistrer les modifications
            </button>

        </div>

    </form>

</div>

@endsection