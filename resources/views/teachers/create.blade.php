@extends('layouts.gestion-ecole')

@section('title', 'Ajouter un enseignant')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- ============================================================
         HEADER
    ============================================================= --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>
            <div class="flex items-center gap-3">

                <div class="w-11 h-11 rounded-xl bg-blue-100
                            flex items-center justify-center">

                    <svg class="w-6 h-6 text-blue-700"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M18 9a6 6 0 11-12 0
                                 6 6 0 0112 0z
                                 M3 21a9 9 0 0118 0
                                 M15 9h6
                                 M18 6v6"/>
                    </svg>

                </div>

                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Ajouter un enseignant
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Enregistrez les informations et les affectations de l'enseignant.
                    </p>
                </div>

            </div>
        </div>


        {{-- Retour --}}
        <a href="{{ route('teachers.index') }}"
           class="inline-flex items-center justify-center gap-2
                  px-4 py-2.5 rounded-lg
                  border border-gray-300 bg-white
                  text-gray-700 font-medium text-sm
                  hover:bg-gray-50 transition">

            <svg class="w-5 h-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 19l-7-7 7-7"/>
            </svg>

            Retour
        </a>

    </div>


    {{-- ============================================================
         ERREURS DE VALIDATION
    ============================================================= --}}
    @if($errors->any())

        <div class="mb-6 rounded-lg border border-red-200
                    bg-red-50 px-4 py-4 text-red-800">

            <div class="flex items-start gap-3">

                <svg class="w-5 h-5 mt-0.5 flex-shrink-0"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 8v4m0 4h.01
                             M21 12a9 9 0 11-18 0
                             9 9 0 0118 0z"/>
                </svg>

                <div>

                    <p class="font-semibold mb-1">
                        Veuillez corriger les erreurs suivantes :
                    </p>

                    <ul class="list-disc ml-5 text-sm space-y-1">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            </div>
        </div>

    @endif


    {{-- ============================================================
         FORMULAIRE
    ============================================================= --}}
    <form action="{{ route('teachers.store') }}"
          method="POST"
          id="teacher-form">

        @csrf


        {{-- ========================================================
             INFORMATIONS PERSONNELLES
        ========================================================= --}}
        <div class="bg-white rounded-xl border border-gray-200
                    shadow-sm mb-6">

            <div class="px-6 py-4 border-b border-gray-200">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-lg bg-blue-100
                                flex items-center justify-center">

                        <svg class="w-5 h-5 text-blue-700"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0
                                     4 4 0 018 0z
                                     M12 14a7 7 0 00-7 7h14
                                     a7 7 0 00-7-7z"/>
                        </svg>

                    </div>

                    <div>

                        <h2 class="text-lg font-semibold text-gray-800">
                            Informations personnelles
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Informations générales de l'enseignant.
                        </p>

                    </div>

                </div>

            </div>


            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Nom --}}
                    <div>

                        <label for="nom"
                               class="block text-sm font-medium
                                      text-gray-700 mb-1.5">

                            Nom <span class="text-red-500">*</span>

                        </label>

                        <input type="text"
                               name="nom"
                               id="nom"
                               value="{{ old('nom') }}"
                               required
                               autocomplete="family-name"
                               class="w-full rounded-lg border-gray-300
                                      focus:border-blue-500
                                      focus:ring-blue-500 text-sm"
                               placeholder="Nom de famille">

                        @error('nom')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Prénom --}}
                    <div>

                        <label for="prenom"
                               class="block text-sm font-medium
                                      text-gray-700 mb-1.5">

                            Prénom <span class="text-red-500">*</span>

                        </label>

                        <input type="text"
                               name="prenom"
                               id="prenom"
                               value="{{ old('prenom') }}"
                               required
                               autocomplete="given-name"
                               class="w-full rounded-lg border-gray-300
                                      focus:border-blue-500
                                      focus:ring-blue-500 text-sm"
                               placeholder="Prénom">

                        @error('prenom')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Date naissance --}}
                    <div>

                        <label for="date_naissance"
                               class="block text-sm font-medium
                                      text-gray-700 mb-1.5">

                            Date de naissance
                            <span class="text-red-500">*</span>

                        </label>

                        <input type="date"
                               name="date_naissance"
                               id="date_naissance"
                               value="{{ old('date_naissance') }}"
                               required
                               class="w-full rounded-lg border-gray-300
                                      focus:border-blue-500
                                      focus:ring-blue-500 text-sm">

                        @error('date_naissance')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Lieu naissance --}}
                    <div>

                        <label for="lieu_naissance"
                               class="block text-sm font-medium
                                      text-gray-700 mb-1.5">

                            Lieu de naissance

                        </label>

                        <input type="text"
                               name="lieu_naissance"
                               id="lieu_naissance"
                               value="{{ old('lieu_naissance') }}"
                               class="w-full rounded-lg border-gray-300
                                      focus:border-blue-500
                                      focus:ring-blue-500 text-sm"
                               placeholder="Ex. Mahajanga">

                        @error('lieu_naissance')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Sexe --}}
                    <div>

                        <label for="sexe"
                               class="block text-sm font-medium
                                      text-gray-700 mb-1.5">

                            Sexe <span class="text-red-500">*</span>

                        </label>

                        <select name="sexe"
                                id="sexe"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500 text-sm">

                            <option value="">
                                Sélectionner
                            </option>

                            <option value="M"
                                {{ old('sexe') === 'M' ? 'selected' : '' }}>
                                Masculin
                            </option>

                            <option value="F"
                                {{ old('sexe') === 'F' ? 'selected' : '' }}>
                                Féminin
                            </option>

                        </select>

                        @error('sexe')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Spécialité --}}
                    <div>

                        <label for="specialite"
                               class="block text-sm font-medium
                                      text-gray-700 mb-1.5">

                            Spécialité

                        </label>

                        <input type="text"
                               name="specialite"
                               id="specialite"
                               value="{{ old('specialite') }}"
                               class="w-full rounded-lg border-gray-300
                                      focus:border-blue-500
                                      focus:ring-blue-500 text-sm"
                               placeholder="Ex. Mathématiques, Informatique...">

                        @error('specialite')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Téléphone --}}
                    <div>

                        <label for="telephone"
                               class="block text-sm font-medium
                                      text-gray-700 mb-1.5">

                            Téléphone

                        </label>

                        <input type="text"
                               name="telephone"
                               id="telephone"
                               value="{{ old('telephone') }}"
                               autocomplete="tel"
                               class="w-full rounded-lg border-gray-300
                                      focus:border-blue-500
                                      focus:ring-blue-500 text-sm"
                               placeholder="0341234567">

                        <p class="mt-1 text-xs text-gray-500">
                            Exemple : 0341234567
                        </p>

                        @error('telephone')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Email --}}
                    <div>

                        <label for="email"
                               class="block text-sm font-medium
                                      text-gray-700 mb-1.5">

                            Email

                        </label>

                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email') }}"
                               autocomplete="email"
                               class="w-full rounded-lg border-gray-300
                                      focus:border-blue-500
                                      focus:ring-blue-500 text-sm"
                               placeholder="enseignant@example.com">

                        @error('email')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>


                {{-- Adresse --}}
                <div class="mt-5">

                    <label for="adresse"
                           class="block text-sm font-medium
                                  text-gray-700 mb-1.5">

                        Adresse

                    </label>

                    <textarea name="adresse"
                              id="adresse"
                              rows="3"
                              class="w-full rounded-lg border-gray-300
                                     focus:border-blue-500
                                     focus:ring-blue-500 text-sm"
                              placeholder="Adresse complète">{{ old('adresse') }}</textarea>

                    @error('adresse')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>
        </div>


        {{-- ========================================================
             ANNÉE SCOLAIRE
        ========================================================= --}}
        <div class="bg-white rounded-xl border border-gray-200
                    shadow-sm mb-6">

            <div class="px-6 py-4 border-b border-gray-200">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-lg bg-green-100
                                flex items-center justify-center">

                        <svg class="w-5 h-5 text-green-700"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M8 7V3m8 4V3
                                     M4 11h16
                                     M5 5h14a1 1 0 011 1v14
                                     a1 1 0 01-1 1H5a1 1 0 01-1-1V6
                                     a1 1 0 011-1z"/>
                        </svg>

                    </div>

                    <div>

                        <h2 class="text-lg font-semibold text-gray-800">
                            Année scolaire
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Choisissez l'année scolaire des affectations.
                        </p>

                    </div>

                </div>

            </div>


            <div class="p-6">

                <label for="school_year_id"
                       class="block text-sm font-medium
                              text-gray-700 mb-1.5">

                    Année scolaire
                    <span class="text-red-500">*</span>

                </label>


                <select name="school_year_id"
                        id="school_year_id"
                        required
                        class="w-full md:w-1/2 rounded-lg border-gray-300
                               focus:border-blue-500
                               focus:ring-blue-500 text-sm">

                    @forelse($schoolYears as $schoolYear)

                        <option value="{{ $schoolYear->id }}"
                            {{ (string) old(
                                'school_year_id',
                                $selectedSchoolYearId
                            ) === (string) $schoolYear->id
                                ? 'selected'
                                : '' }}>

                            {{ $schoolYear->name
                                ?? $schoolYear->nom
                                ?? $schoolYear->libelle
                                ?? (
                                    $schoolYear->start_date
                                        ? \Carbon\Carbon::parse(
                                            $schoolYear->start_date
                                        )->format('Y')
                                        : $schoolYear->id
                                )
                            }}

                            @if($schoolYear->is_active)
                                — Active
                            @endif

                        </option>

                    @empty

                        <option value="">
                            Aucune année scolaire disponible
                        </option>

                    @endforelse

                </select>


                @error('school_year_id')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror


                @if($selectedSchoolYear)

                    <div class="mt-3 flex items-start gap-2
                                px-3 py-2 rounded-lg
                                bg-blue-50 text-blue-800 text-sm">

                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01
                                     M12 20a8 8 0 100-16
                                     8 8 0 000 16z"/>
                        </svg>

                        <span>
                            Les classes affichées correspondent à l'année scolaire sélectionnée.
                        </span>

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================
             CLASSES ET MATIÈRES
        ========================================================= --}}
        <div class="bg-white rounded-xl border border-gray-200
                    shadow-sm mb-6">

            <div class="px-6 py-4 border-b border-gray-200">

                <div class="flex flex-col lg:flex-row
                            lg:items-center lg:justify-between
                            gap-4">

                    <div>

                        <div class="flex items-center gap-3">

                            <div class="w-10 h-10 rounded-lg bg-blue-100
                                        flex items-center justify-center">

                                <svg class="w-5 h-5 text-blue-700"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 4v16
                                             M4 12h16"/>
                                </svg>

                            </div>

                            <div>

                                <h2 class="text-lg font-semibold text-gray-800">
                                    Classes et matières
                                </h2>

                                <p class="text-sm text-gray-500 mt-1">
                                    Affectez l'enseignant aux matières qu'il enseignera.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Actions globales --}}
                    @if($classes->count() && $subjects->count())

                        <div class="flex flex-wrap gap-2">

                            <button type="button"
                                    id="select-all"
                                    class="inline-flex items-center
                                           gap-2 px-3 py-2
                                           rounded-lg bg-blue-600
                                           text-white text-sm
                                           font-medium
                                           hover:bg-blue-700
                                           transition">

                                <svg class="w-4 h-4"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M5 13l4 4L19 7"/>
                                </svg>

                                Tout sélectionner
                            </button>


                            <button type="button"
                                    id="clear-all"
                                    class="inline-flex items-center
                                           gap-2 px-3 py-2
                                           rounded-lg bg-gray-100
                                           text-gray-700 text-sm
                                           font-medium
                                           hover:bg-gray-200
                                           transition">

                                <svg class="w-4 h-4"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"/>
                                </svg>

                                Tout effacer
                            </button>

                        </div>

                    @endif

                </div>

            </div>


            <div class="p-6">

                @if($classes->count())

                    <div class="space-y-5">

                        @foreach($classes as $classRoom)

                            @php
                                $classId = $classRoom->id;

                                $oldForClass =
                                    old(
                                        "affectations.$classId",
                                        []
                                    );

                                $oldForClass =
                                    is_array($oldForClass)
                                        ? array_map(
                                            'strval',
                                            $oldForClass
                                        )
                                        : [];
                            @endphp


                            {{-- =================================================
                                 CLASSE
                            ================================================== --}}
                            <div class="class-card rounded-xl
                                        border border-gray-200
                                        overflow-hidden bg-gray-50"
                                 data-class-id="{{ $classId }}">

                                {{-- Header classe --}}
                                <div class="px-5 py-4 bg-white
                                            border-b border-gray-200">

                                    <div class="flex flex-col sm:flex-row
                                                sm:items-center
                                                sm:justify-between
                                                gap-3">

                                        <div>

                                            <h3 class="font-semibold
                                                       text-gray-800">

                                                {{ $classRoom->nom_classe }}

                                            </h3>


                                            <div class="flex flex-wrap
                                                        items-center
                                                        gap-2 mt-1">

                                                @if($classRoom->niveau)

                                                    <span class="inline-flex
                                                                 items-center
                                                                 px-2.5 py-1
                                                                 rounded-full
                                                                 bg-blue-100
                                                                 text-blue-700
                                                                 text-xs
                                                                 font-medium">

                                                        {{ $classRoom->niveau }}

                                                    </span>

                                                @endif


                                                @if($classRoom->filiere)

                                                    <span class="inline-flex
                                                                 items-center
                                                                 px-2.5 py-1
                                                                 rounded-full
                                                                 bg-green-100
                                                                 text-green-700
                                                                 text-xs
                                                                 font-medium">

                                                        {{ $classRoom->filiere }}

                                                    </span>

                                                @endif


                                                @if($classRoom->salle)

                                                    <span class="text-xs
                                                                 text-gray-500">

                                                        Salle :
                                                        {{ $classRoom->salle }}

                                                    </span>

                                                @endif

                                            </div>

                                        </div>


                                        <div class="flex items-center
                                                    gap-3">

                                            <span class="class-count
                                                         text-xs
                                                         font-medium
                                                         text-gray-500">

                                                {{ count($oldForClass) }}
                                                matière(s)

                                            </span>


                                            <button type="button"
                                                    class="class-select-all
                                                           inline-flex
                                                           items-center
                                                           gap-1.5 px-3 py-1.5
                                                           rounded-lg
                                                           border
                                                           border-blue-200
                                                           bg-blue-50
                                                           text-blue-700
                                                           text-xs
                                                           font-medium
                                                           hover:bg-blue-100
                                                           transition"
                                                    data-class-id="{{ $classId }}">

                                                <svg class="w-4 h-4"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     viewBox="0 0 24 24">

                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M5 13l4 4L19 7"/>
                                                </svg>

                                                Tout sélectionner

                                            </button>

                                        </div>

                                    </div>

                                </div>


                                {{-- =================================================
                                     MATIÈRES
                                ================================================== --}}
                                <div class="p-5">

                                    @if($subjects->count())

                                        <div class="grid grid-cols-1
                                                    sm:grid-cols-2
                                                    lg:grid-cols-3
                                                    gap-3">

                                            @foreach($subjects as $subject)

                                                @php
                                                    $subjectName =
                                                        $subject->nom
                                                        ?? $subject->name
                                                        ?? 'Matière';

                                                    $subjectId =
                                                        (string) $subject->id;
                                                @endphp


                                                <label class="subject-item
                                                              flex items-start
                                                              gap-3 p-3
                                                              rounded-lg
                                                              border
                                                              border-gray-200
                                                              bg-white
                                                              cursor-pointer
                                                              hover:border-blue-300
                                                              hover:bg-blue-50
                                                              transition">

                                                    <input type="checkbox"
                                                           name="affectations[{{ $classId }}][]"
                                                           value="{{ $subject->id }}"
                                                           class="subject-checkbox
                                                                  mt-0.5
                                                                  rounded
                                                                  border-gray-300
                                                                  text-blue-600
                                                                  focus:ring-blue-500"
                                                           data-class-id="{{ $classId }}"
                                                           {{ in_array(
                                                               $subjectId,
                                                               $oldForClass,
                                                               true
                                                           )
                                                               ? 'checked'
                                                               : '' }}>


                                                    <span class="flex-1 min-w-0">

                                                        <span class="block
                                                                     text-sm
                                                                     font-medium
                                                                     text-gray-800">

                                                            {{ $subjectName }}

                                                        </span>


                                                        @if(isset($subject->code))

                                                            <span class="block
                                                                         text-xs
                                                                         text-gray-500
                                                                         mt-0.5">

                                                                Code :
                                                                {{ $subject->code }}

                                                            </span>

                                                        @endif

                                                    </span>

                                                </label>

                                            @endforeach

                                        </div>

                                    @else

                                        <div class="rounded-lg
                                                    border border-yellow-200
                                                    bg-yellow-50 p-4">

                                            <div class="flex items-start
                                                        gap-3">

                                                <svg class="w-5 h-5
                                                            text-yellow-600
                                                            flex-shrink-0"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     viewBox="0 0 24 24">

                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M12 8v4m0 4h.01
                                                             M21 12a9 9 0 11-18 0
                                                             9 9 0 0118 0z"/>
                                                </svg>


                                                <div>

                                                    <p class="font-medium
                                                              text-yellow-800">

                                                        Aucune matière disponible.

                                                    </p>


                                                    <p class="text-sm
                                                              text-yellow-700
                                                              mt-1">

                                                        Ajoutez d'abord une matière
                                                        pour cet établissement.

                                                    </p>


                                                    @if(Route::has('subjects.create'))

                                                        <a href="{{ route('subjects.create') }}"
                                                           class="inline-flex
                                                                  items-center
                                                                  gap-2 mt-3
                                                                  px-3 py-2
                                                                  rounded-lg
                                                                  bg-green-600
                                                                  text-white
                                                                  text-sm
                                                                  font-medium
                                                                  hover:bg-green-700
                                                                  transition">

                                                            <svg class="w-4 h-4"
                                                                 fill="none"
                                                                 stroke="currentColor"
                                                                 viewBox="0 0 24 24">

                                                                <path stroke-linecap="round"
                                                                      stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M12 4v16
                                                                         M4 12h16"/>
                                                            </svg>

                                                            Ajouter une matière

                                                        </a>

                                                    @endif

                                                </div>

                                            </div>

                                        </div>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    {{-- =================================================
                         AUCUNE CLASSE
                    ================================================== --}}
                    <div class="rounded-xl border
                                border-yellow-200
                                bg-yellow-50 p-6">

                        <div class="flex items-start gap-4">

                            <div class="w-10 h-10 rounded-lg
                                        bg-yellow-100
                                        flex items-center
                                        justify-center
                                        flex-shrink-0">

                                <svg class="w-5 h-5 text-yellow-700"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 8v4m0 4h.01
                                             M21 12a9 9 0 11-18 0
                                             9 9 0 0118 0z"/>
                                </svg>

                            </div>


                            <div>

                                <h3 class="font-semibold
                                           text-yellow-800">

                                    Aucune classe disponible

                                </h3>


                                <p class="text-sm text-yellow-700 mt-1">

                                    Aucune classe n'est disponible pour
                                    l'année scolaire sélectionnée.

                                </p>


                                @if(Route::has('class_rooms.create'))

                                    <a href="{{ route('class_rooms.create') }}"
                                       class="inline-flex
                                              items-center gap-2
                                              mt-4 px-4 py-2.5
                                              rounded-lg
                                              bg-blue-600
                                              text-white
                                              text-sm
                                              font-medium
                                              hover:bg-blue-700
                                              transition">

                                        <svg class="w-5 h-5"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M12 4v16
                                                     M4 12h16"/>
                                        </svg>

                                        Ajouter une classe

                                    </a>

                                @endif

                            </div>

                        </div>

                    </div>

                @endif

            </div>


            {{-- Résumé --}}
            @if($classes->count() && $subjects->count())

                <div class="px-6 py-4
                            border-t border-gray-200
                            bg-gray-50">

                    <div class="flex flex-col sm:flex-row
                                sm:items-center
                                sm:justify-between gap-3">

                        <div class="text-sm text-gray-600">

                            <span class="font-semibold
                                         text-gray-800"
                                  id="total-selected">

                                0

                            </span>

                            matière(s) sélectionnée(s) au total.

                        </div>


                        <div class="text-xs text-gray-500">

                            Les affectations seront enregistrées
                            pour l'année scolaire sélectionnée.

                        </div>

                    </div>

                </div>

            @endif

        </div>


        {{-- ========================================================
             ACTIONS FINALES
        ========================================================= --}}
        <div class="flex flex-col sm:flex-row
                    sm:items-center
                    sm:justify-between gap-3">

            <a href="{{ route('teachers.index') }}"
               class="inline-flex items-center
                      justify-center gap-2
                      px-5 py-2.5 rounded-lg
                      border border-gray-300
                      bg-white text-gray-700
                      font-medium text-sm
                      hover:bg-gray-50 transition">

                <svg class="w-5 h-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 19l-7-7 7-7"/>
                </svg>

                Annuler

            </a>


            <button type="submit"
                    id="submit-button"
                    class="inline-flex items-center
                           justify-center gap-2
                           px-6 py-2.5 rounded-lg
                           bg-blue-600
                           text-white
                           font-semibold text-sm
                           hover:bg-blue-700
                           focus:outline-none
                           focus:ring-2
                           focus:ring-blue-500
                           focus:ring-offset-2
                           transition">

                <svg class="w-5 h-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>

                Enregistrer l'enseignant

            </button>

        </div>

    </form>

</div>


{{-- ================================================================
     JAVASCRIPT
================================================================ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form =
        document.getElementById('teacher-form');

    const selectAllButton =
        document.getElementById('select-all');

    const clearAllButton =
        document.getElementById('clear-all');

    const totalSelected =
        document.getElementById('total-selected');

    const schoolYearSelect =
        document.getElementById('school_year_id');


    /*
    |--------------------------------------------------------------------------
    | Toutes les cases à cocher
    |--------------------------------------------------------------------------
    */
    function getAllCheckboxes() {

        return Array.from(
            document.querySelectorAll(
                '.subject-checkbox'
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Compteur global
    |--------------------------------------------------------------------------
    */
    function updateTotal() {

        if (!totalSelected) {
            return;
        }

        const checked =
            document.querySelectorAll(
                '.subject-checkbox:checked'
            ).length;

        totalSelected.textContent =
            checked;

    }


    /*
    |--------------------------------------------------------------------------
    | Compteur d'une classe
    |--------------------------------------------------------------------------
    */
    function updateClassCount(classId) {

        const card =
            document.querySelector(
                '.class-card[data-class-id="' +
                classId +
                '"]'
            );

        if (!card) {
            return;
        }

        const count =
            card.querySelectorAll(
                '.subject-checkbox:checked'
            ).length;

        const counter =
            card.querySelector(
                '.class-count'
            );

        if (counter) {

            counter.textContent =
                count +
                ' matière(s)';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Tous les compteurs
    |--------------------------------------------------------------------------
    */
    function updateAllCounts() {

        document
            .querySelectorAll('.class-card')
            .forEach(function (card) {

                const classId =
                    card.dataset.classId;

                updateClassCount(
                    classId
                );

            });

        updateTotal();

    }


    /*
    |--------------------------------------------------------------------------
    | Sélectionner toutes les matières
    |--------------------------------------------------------------------------
    */
    if (selectAllButton) {

        selectAllButton.addEventListener(
            'click',
            function () {

                getAllCheckboxes()
                    .forEach(function (checkbox) {

                        checkbox.checked = true;

                    });

                updateAllCounts();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Effacer toutes les matières
    |--------------------------------------------------------------------------
    */
    if (clearAllButton) {

        clearAllButton.addEventListener(
            'click',
            function () {

                getAllCheckboxes()
                    .forEach(function (checkbox) {

                        checkbox.checked = false;

                    });

                updateAllCounts();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Sélectionner toutes les matières d'une classe
    |--------------------------------------------------------------------------
    */
    document
        .querySelectorAll('.class-select-all')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const classId =
                        button.dataset.classId;

                    const card =
                        document.querySelector(
                            '.class-card[data-class-id="' +
                            classId +
                            '"]'
                        );

                    if (!card) {
                        return;
                    }

                    card
                        .querySelectorAll(
                            '.subject-checkbox'
                        )
                        .forEach(function (checkbox) {

                            checkbox.checked = true;

                        });

                    updateClassCount(
                        classId
                    );

                    updateTotal();

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | Changement d'une matière
    |--------------------------------------------------------------------------
    */
    document
        .querySelectorAll('.subject-checkbox')
        .forEach(function (checkbox) {

            checkbox.addEventListener(
                'change',
                function () {

                    updateClassCount(
                        checkbox.dataset.classId
                    );

                    updateTotal();

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | Changement d'année scolaire
    |--------------------------------------------------------------------------
    |
    | Le contrôleur attend :
    |
    | ?school_year_id=ID
    |
    | On recharge donc la page afin de récupérer
    | les classes et matières de cette année.
    |--------------------------------------------------------------------------
    */
    if (schoolYearSelect) {

        schoolYearSelect.addEventListener(
            'change',
            function () {

                const schoolYearId =
                    this.value;

                if (!schoolYearId) {
                    return;
                }

                const url =
                    new URL(
                        window.location.href
                    );

                url.searchParams.set(
                    'school_year_id',
                    schoolYearId
                );

                /*
                | Supprimer éventuellement les anciennes
                | données de validation dans l'URL.
                */
                window.location.href =
                    url.toString();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Empêcher double soumission
    |--------------------------------------------------------------------------
    */
    if (form) {

        form.addEventListener(
            'submit',
            function () {

                const submitButton =
                    document.getElementById(
                        'submit-button'
                    );

                if (!submitButton) {
                    return;
                }

                submitButton.disabled =
                    true;

                submitButton.classList.add(
                    'opacity-75',
                    'cursor-not-allowed'
                );

                submitButton.innerHTML = `
                    <svg class="w-5 h-5 animate-spin"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <circle
                            cx="12"
                            cy="12"
                            r="9"
                            stroke="currentColor"
                            stroke-width="3"
                            class="opacity-25">
                        </circle>

                        <path
                            d="M21 12a9 9 0 01-9 9"
                            stroke="currentColor"
                            stroke-width="3">
                        </path>

                    </svg>

                    Enregistrement...
                `;

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Initialisation
    |--------------------------------------------------------------------------
    */
    updateAllCounts();

});
</script>
@endsection
