@extends('layouts.gestion-ecole')

@section('title', 'Ajouter un étudiant')

@section('page-title', 'Ajouter un étudiant')

@section(
    'page-description',
    'Enregistrer un nouvel étudiant dans votre établissement'
)

@section('content')

<div class="mx-auto max-w-7xl space-y-5">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-3">

            {{-- SVG étudiant --}}
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    class="h-5 w-5 text-blue-600"
                    aria-hidden="true"
                >
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 19a6 6 0 0 0-12 0"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 8v6m3-3h-6"/>
                </svg>
            </div>

            <div>
                <h1 class="text-xl font-bold text-gray-900">
                    Ajouter un étudiant
                </h1>

                <p class="text-sm text-gray-500">
                    Enregistrer les informations du nouvel étudiant.
                </p>
            </div>

        </div>

        <a
            href="{{ route('students.index') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
        >
            {{-- SVG retour --}}
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                class="h-4 w-4"
                aria-hidden="true"
            >
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19 12H5"/>
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="m12 19-7-7 7-7"/>
            </svg>

            Retour
        </a>

    </div>


    {{-- =========================================================
        ERREURS
    ========================================================== --}}
    @if($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <div class="flex gap-3">

                {{-- SVG alerte --}}
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    class="h-5 w-5 shrink-0 text-red-600"
                    aria-hidden="true"
                >
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v4"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 17h.01"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m10.3 3.7-8 14A2 2 0 0 0 4 20.7h16a2 2 0 0 0 1.7-3l-8-14a2 2 0 0 0-3.4 0Z"/>
                </svg>

                <div class="min-w-0">

                    <h3 class="font-semibold text-red-800">
                        Veuillez corriger les erreurs
                    </h3>

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
    <form
        action="{{ route('students.store') }}"
        method="POST"
        class="space-y-5"
    >

        @csrf


        {{-- =====================================================
            INFORMATIONS PERSONNELLES
        ====================================================== --}}
        <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">

            <div class="border-b border-gray-100 bg-gray-50 px-5 py-4">

                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100">

                        {{-- SVG utilisateur --}}
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            class="h-4 w-4 text-blue-600"
                            aria-hidden="true"
                        >
                            <circle cx="12" cy="8" r="4"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 21a8 8 0 0 1 16 0"/>
                        </svg>

                    </div>

                    <div>
                        <h2 class="font-bold text-gray-900">
                            Informations personnelles
                        </h2>

                        <p class="text-xs text-gray-500">
                            Informations générales de l'étudiant.
                        </p>
                    </div>

                </div>

            </div>


            <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2 lg:grid-cols-3">

                

                {{-- NOM --}}
                <div>

                    <label
                        for="nom"
                        class="mb-1.5 block text-sm font-semibold text-gray-700"
                    >
                        Nom <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="nom"
                        name="nom"
                        value="{{ old('nom') }}"
                        required
                        maxlength="255"
                        placeholder="Ex : RAKOTO"
                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >

                </div>


                {{-- PRENOM --}}
                <div>

                    <label
                        for="prenom"
                        class="mb-1.5 block text-sm font-semibold text-gray-700"
                    >
                        Prénom <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="prenom"
                        name="prenom"
                        value="{{ old('prenom') }}"
                        required
                        maxlength="255"
                        placeholder="Ex : Jean"
                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >

                </div>


                {{-- DATE DE NAISSANCE --}}
                <div>

                    <label
                        for="date_naissance"
                        class="mb-1.5 block text-sm font-semibold text-gray-700"
                    >
                        Date de naissance <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        id="date_naissance"
                        name="date_naissance"
                        value="{{ old('date_naissance') }}"
                        required
                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >

                </div>


                {{-- LIEU DE NAISSANCE --}}
                <div>

                    <label
                        for="lieu_naissance"
                        class="mb-1.5 block text-sm font-semibold text-gray-700"
                    >
                        Lieu de naissance
                    </label>

                    <input
                        type="text"
                        id="lieu_naissance"
                        name="lieu_naissance"
                        value="{{ old('lieu_naissance') }}"
                        maxlength="255"
                        placeholder="Ex : Mahajanga"
                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >

                </div>


                {{-- SEXE --}}
                <div>

                    <label
                        for="sexe"
                        class="mb-1.5 block text-sm font-semibold text-gray-700"
                    >
                        Sexe <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="sexe"
                        name="sexe"
                        required
                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >

                        <option value="">
                            Sélectionner
                        </option>

                        <option
                            value="Masculin"
                            @selected(old('sexe') === 'Masculin')
                        >
                            Masculin
                        </option>

                        <option
                            value="Féminin"
                            @selected(old('sexe') === 'Féminin')
                        >
                            Féminin
                        </option>

                    </select>

                </div>

            </div>

        </div>


        {{-- =====================================================
            COORDONNEES
        ====================================================== --}}
        <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">

            <div class="border-b border-gray-100 bg-gray-50 px-5 py-4">

                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-100">

                        {{-- SVG contact --}}
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            class="h-4 w-4 text-green-600"
                            aria-hidden="true"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 10.5a8.4 8.4 0 0 1-1 4l2 3.5-4-.8a8.5 8.5 0 1 1 3-6.7Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 10h.01M12 10h.01M16 10h.01"/>
                        </svg>

                    </div>

                    <div>
                        <h2 class="font-bold text-gray-900">
                            Coordonnées
                        </h2>

                        <p class="text-xs text-gray-500">
                            Informations permettant de contacter l'étudiant.
                        </p>
                    </div>

                </div>

            </div>


            <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">

                {{-- TELEPHONE --}}
                <div>

                    <label
                        for="telephone"
                        class="mb-1.5 block text-sm font-semibold text-gray-700"
                    >
                        Téléphone
                    </label>

                    <input
                        type="tel"
                        id="telephone"
                        name="telephone"
                        value="{{ old('telephone') }}"
                        maxlength="30"
                        placeholder="034 00 000 00"
                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >

                </div>


                {{-- EMAIL --}}
                <div>

                    <label
                        for="email"
                        class="mb-1.5 block text-sm font-semibold text-gray-700"
                    >
                        Adresse e-mail
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        maxlength="255"
                        placeholder="etudiant@example.com"
                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >

                </div>


                {{-- ADRESSE --}}
                <div class="md:col-span-2">

                    <label
                        for="adresse"
                        class="mb-1.5 block text-sm font-semibold text-gray-700"
                    >
                        Adresse
                    </label>

                    <textarea
                        id="adresse"
                        name="adresse"
                        rows="2"
                        maxlength="255"
                        placeholder="Adresse complète"
                        class="w-full resize-none rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >{{ old('adresse') }}</textarea>

                </div>

            </div>

        </div>


        {{-- =====================================================
            SCOLARITE
        ====================================================== --}}
        <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">

            <div class="border-b border-gray-100 bg-gray-50 px-5 py-4">

                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100">

                        {{-- SVG école --}}
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            class="h-4 w-4 text-blue-600"
                            aria-hidden="true"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m3 10 9-6 9 6"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 9v10h14V9"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 19v-6h6v6"/>
                        </svg>

                    </div>

                    <div>
                        <h2 class="font-bold text-gray-900">
                            Scolarité
                        </h2>

                        <p class="text-xs text-gray-500">
                            Année scolaire et classe de l'étudiant.
                        </p>
                    </div>

                </div>

            </div>


            <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">

                {{-- ANNEE SCOLAIRE --}}
                <div>

                    <label
                        for="school_year_id"
                        class="mb-1.5 block text-sm font-semibold text-gray-700"
                    >
                        Année scolaire <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="school_year_id"
                        name="school_year_id"
                        required
                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >

                        <option value="">
                            -- Sélectionner une année --
                        </option>

                        @foreach($schoolYears as $year)

                            <option
                                value="{{ $year->id }}"
                                @selected(
                                    old(
                                        'school_year_id',
                                        $activeSchoolYear?->id
                                    ) == $year->id
                                )
                            >
                                {{ $year->name }}

                                @if($year->is_active)
                                    — Active
                                @endif
                            </option>

                        @endforeach

                    </select>

                    @if($activeSchoolYear)

                        <p class="mt-1.5 text-xs text-gray-500">
                            Année active :
                            <span class="font-semibold text-green-600">
                                {{ $activeSchoolYear->name }}
                            </span>
                        </p>

                    @endif

                </div>


                {{-- CLASSE --}}
                <div>

                    <label
                        for="classe"
                        class="mb-1.5 block text-sm font-semibold text-gray-700"
                    >
                        Classe
                    </label>

                    @if($classes->count() > 0)

                        <select
                            id="classe"
                            name="classe"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >

                            <option value="">
                                -- Sélectionner une classe --
                            </option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->nom_classe }}"
                                    @selected(old('classe') === $class->nom_classe)
                                >
                                    {{ $class->nom_classe }}

                                    @if($class->niveau)
                                        — {{ $class->niveau }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    @else

                        <div class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2.5">

                            <div class="flex items-center gap-2">

                                {{-- SVG warning --}}
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    class="h-4 w-4 shrink-0 text-amber-600"
                                    aria-hidden="true"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 9v4"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 17h.01"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m10.3 3.7-8 14A2 2 0 0 0 4 20.7h16a2 2 0 0 0 1.7-3l-8-14a2 2 0 0 0-3.4 0Z"/>
                                </svg>

                                <span class="text-xs font-medium text-amber-700">
                                    Aucune classe disponible pour cette année.
                                </span>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- =====================================================
            MATIERES DISPONIBLES — INFORMATION
        ====================================================== --}}
        @if(isset($subjects))

            <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">

                <div class="border-b border-gray-100 bg-gray-50 px-5 py-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-100">

                            {{-- SVG livre --}}
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                class="h-4 w-4 text-green-600"
                                aria-hidden="true"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M4 19a2.5 2.5 0 0 1 2.5-2.5H20"/>
                            </svg>

                        </div>

                        <div>
                            <h2 class="font-bold text-gray-900">
                                Matières disponibles
                            </h2>

                            <p class="text-xs text-gray-500">
                                Matières configurées pour l'année scolaire sélectionnée.
                            </p>
                        </div>

                    </div>

                </div>


                <div class="p-5">

                    @if($subjects->count() > 0)

                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">

                            @foreach($subjects as $subject)

                                <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-green-100">

                                            {{-- SVG livre --}}
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                class="h-4 w-4 text-green-600"
                                                aria-hidden="true"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M4 19a2.5 2.5 0 0 1 2.5-2.5H20"/>
                                            </svg>

                                        </div>

                                        <div class="min-w-0">

                                            @if($subject->code)

                                                <p class="text-[11px] font-bold uppercase tracking-wide text-blue-600">
                                                    {{ $subject->code }}
                                                </p>

                                            @endif

                                            <p class="truncate text-sm font-semibold text-gray-900">
                                                {{ $subject->nom }}
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3">

                            <div class="flex items-center gap-2">

                                {{-- SVG warning --}}
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    class="h-5 w-5 shrink-0 text-amber-600"
                                    aria-hidden="true"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 9v4"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 17h.01"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m10.3 3.7-8 14A2 2 0 0 0 4 20.7h16a2 2 0 0 0 1.7-3l-8-14a2 2 0 0 0-3.4 0Z"/>
                                </svg>

                                <p class="text-sm font-medium text-amber-700">
                                    Aucune matière n'est encore configurée pour cette année scolaire.
                                </p>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        @endif


        {{-- =====================================================
            ACTIONS
        ====================================================== --}}
        <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:justify-end">

            <a
                href="{{ route('students.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
            >
                {{-- SVG fermer --}}
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    class="h-4 w-4"
                    aria-hidden="true"
                >
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6 6l12 12M18 6 6 18"/>
                </svg>

                Annuler
            </a>


            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100"
            >
                {{-- SVG enregistrer --}}
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    class="h-4 w-4"
                    aria-hidden="true"
                >
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 4h11l3 3v13H5V4Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8 4v6h8V4"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8 20v-6h8v6"/>
                </svg>

                Enregistrer l'étudiant
            </button>

        </div>

    </form>

</div>

@endsection
