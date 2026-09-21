@extends('layouts.gestion-ecole')

@section('title', 'Modifier une note')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-700"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Modifier une note
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Modifier les informations de la note sélectionnée.
                    </p>
                </div>
            </div>
        </div>

        <a href="{{ route('notes.index') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5
                  bg-gray-100 hover:bg-gray-200 text-gray-700
                  rounded-lg font-medium transition">

            <svg class="w-5 h-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>

            Retour aux notes
        </a>

    </div>


    {{-- =========================================================
         MESSAGES
    ========================================================== --}}

    @if(session('success'))
        <div class="mb-5 flex items-start gap-3 rounded-lg
                    bg-green-50 border border-green-200
                    px-4 py-3 text-green-800">

            <svg class="w-5 h-5 mt-0.5 flex-shrink-0"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"/>
            </svg>

            <div>
                <p class="font-semibold">
                    Succès
                </p>

                <p class="text-sm mt-0.5">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    @endif


    @if($errors->any())
        <div class="mb-5 rounded-lg
                    bg-red-50 border border-red-200
                    px-4 py-3 text-red-800">

            <div class="flex items-center gap-2 mb-2">

                <svg class="w-5 h-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 8v4m0 4h.01M10.29 3.86l-7.1 12.28A2 2 0 004.92 19h14.16a2 2 0 001.73-2.86L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>

                <p class="font-semibold">
                    Vérifiez les informations saisies.
                </p>
            </div>

            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- =========================================================
         CARTE PRINCIPALE
    ========================================================== --}}

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Header carte --}}
        <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">

            <div class="flex items-center gap-3">

                <div class="w-9 h-9 rounded-lg bg-blue-700
                            flex items-center justify-center">

                    <svg class="w-5 h-5 text-white"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 5h6"/>
                    </svg>
                </div>

                <div>
                    <h2 class="font-semibold text-gray-800">
                        Informations de la note
                    </h2>

                    <p class="text-xs text-gray-500">
                        Établissement :
                        <span class="font-medium">
                            {{ $establishment->nom }}
                        </span>
                    </p>
                </div>

            </div>
        </div>


        {{-- =====================================================
             FORMULAIRE
        ====================================================== --}}

        <form action="{{ route('notes.update', $note) }}"
              method="POST"
              class="p-6">

            @csrf
            @method('PUT')


            {{-- =================================================
                 ANNÉE + CLASSE
            ================================================== --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Année scolaire --}}
                <div>

                    <label for="school_year_id"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Année scolaire
                        <span class="text-red-500">*</span>
                    </label>

                    <select id="school_year_id"
                            name="school_year_id"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-blue-500 focus:ring-blue-500
                                   text-sm">

                        @forelse($schoolYears as $schoolYear)

                            <option value="{{ $schoolYear->id }}"
                                {{ (int) old('school_year_id', $selectedSchoolYearId) === (int) $schoolYear->id ? 'selected' : '' }}>

                                {{ $schoolYear->name }}

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

                </div>


                {{-- Classe --}}
                <div>

                    <label for="class_room_id"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Classe
                        <span class="text-red-500">*</span>
                    </label>

                    <select id="class_room_id"
                            name="class_room_id"
                            required
                            class="w-full rounded-lg border-gray-300
                                   focus:border-blue-500 focus:ring-blue-500
                                   text-sm">

                        <option value="">
                            Sélectionner une classe
                        </option>

                        @forelse($classes as $class)

                            <option value="{{ $class->id }}"
                                {{ (int) old('class_room_id', $note->class_room_id) === (int) $class->id ? 'selected' : '' }}>

                                {{ $class->nom_classe }}

                                @if($class->niveau)
                                    — {{ $class->niveau }}
                                @endif

                            </option>

                        @empty

                            <option value="">
                                Aucune classe disponible
                            </option>

                        @endforelse

                    </select>

                    @error('class_room_id')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>


            {{-- =================================================
                 ÉTUDIANT + MATIÈRE
            ================================================== --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">

                {{-- Étudiant --}}
                <div>

                    <label for="student_id"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Étudiant
                        <span class="text-red-500">*</span>
                    </label>

                    <select id="student_id"
                            name="student_id"
                            required
                            class="w-full rounded-lg border-gray-300
                                   focus:border-blue-500 focus:ring-blue-500
                                   text-sm">

                        <option value="">
                            Sélectionner un étudiant
                        </option>

                        @forelse($students as $student)

                            <option value="{{ $student->id }}"
                                {{ (int) old('student_id', $note->student_id) === (int) $student->id ? 'selected' : '' }}>

                                @if($student->matricule)
                                    {{ $student->matricule }} —
                                @endif

                                {{ $student->nom }}
                                {{ $student->prenom }}

                            </option>

                        @empty

                            <option value="">
                                Aucun étudiant inscrit dans cette classe
                            </option>

                        @endforelse

                    </select>

                    @error('student_id')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Matière --}}
                <div>

                    <label for="subject_id"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Matière
                        <span class="text-red-500">*</span>
                    </label>

                    <select id="subject_id"
                            name="subject_id"
                            required
                            class="w-full rounded-lg border-gray-300
                                   focus:border-blue-500 focus:ring-blue-500
                                   text-sm">

                        <option value="">
                            Sélectionner une matière
                        </option>

                        @forelse($subjects as $subject)

                            <option value="{{ $subject->id }}"
                                {{ (int) old('subject_id', $note->subject_id) === (int) $subject->id ? 'selected' : '' }}>

                                @if($subject->code)
                                    {{ $subject->code }} —
                                @endif

                                {{ $subject->nom }}

                            </option>

                        @empty

                            <option value="">
                                Aucune matière affectée à cette classe
                            </option>

                        @endforelse

                    </select>

                    @error('subject_id')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>


            {{-- =================================================
                 ENSEIGNANT + NOTE
            ================================================== --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">

                {{-- Enseignant --}}
                <div>

                    <label for="teacher_id"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Enseignant
                    </label>

                    <select id="teacher_id"
                            name="teacher_id"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-blue-500 focus:ring-blue-500
                                   text-sm">

                        <option value="">
                            Aucun enseignant
                        </option>

                        @forelse($teachers as $teacher)

                            <option value="{{ $teacher->id }}"
                                {{ (int) old('teacher_id', $note->teacher_id) === (int) $teacher->id ? 'selected' : '' }}>

                                {{ $teacher->nom }}
                                {{ $teacher->prenom }}

                            </option>

                        @empty

                            <option value="">
                                Aucun enseignant affecté
                            </option>

                        @endforelse

                    </select>

                    @error('teacher_id')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Note --}}
                <div>

                    <label for="note"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Note
                        <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">

                        <input type="number"
                               id="note"
                               name="note"
                               value="{{ old('note', $note->note) }}"
                               min="0"
                               max="20"
                               step="0.01"
                               required
                               class="w-full rounded-lg border-gray-300
                                      focus:border-blue-500 focus:ring-blue-500
                                      text-sm pr-16">

                        <span class="absolute right-3 top-1/2
                                     -translate-y-1/2
                                     text-sm text-gray-400">
                            / 20
                        </span>

                    </div>

                    @error('note')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>


            {{-- =================================================
                 COEFFICIENT + TYPE
            ================================================== --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">

                {{-- Coefficient --}}
                <div>

                    <label for="coefficient"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Coefficient
                        <span class="text-red-500">*</span>
                    </label>

                    <input type="number"
                           id="coefficient"
                           name="coefficient"
                           value="{{ old('coefficient', $note->coefficient ?? 1) }}"
                           min="0.01"
                           max="100"
                           step="0.01"
                           required
                           class="w-full rounded-lg border-gray-300
                                  focus:border-blue-500 focus:ring-blue-500
                                  text-sm">

                    @error('coefficient')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Type évaluation --}}
                <div>

                    <label for="type_evaluation"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Type d'évaluation
                    </label>

                    <select id="type_evaluation"
                            name="type_evaluation"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-blue-500 focus:ring-blue-500
                                   text-sm">

                        <option value="">
                            Sélectionner
                        </option>

                        @foreach([
                            'Devoir',
                            'Interrogation',
                            'Examen',
                            'Contrôle',
                            'TP',
                            'Autre'
                        ] as $type)

                            <option value="{{ $type }}"
                                {{ old('type_evaluation', $note->type_evaluation) === $type ? 'selected' : '' }}>

                                {{ $type }}

                            </option>

                        @endforeach

                    </select>

                    @error('type_evaluation')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>


            {{-- =================================================
                 DATE
            ================================================== --}}

            <div class="mt-5 max-w-md">

                <label for="date_evaluation"
                       class="block text-sm font-semibold text-gray-700 mb-2">
                    Date d'évaluation
                </label>

                <input type="date"
                       id="date_evaluation"
                       name="date_evaluation"
                       value="{{ old(
                           'date_evaluation',
                           $note->date_evaluation
                               ? $note->date_evaluation->format('Y-m-d')
                               : ''
                       ) }}"
                       class="w-full rounded-lg border-gray-300
                              focus:border-blue-500 focus:ring-blue-500
                              text-sm">

                @error('date_evaluation')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- =================================================
                 RÉSUMÉ
            ================================================== --}}

            <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4">

                <div class="flex items-start gap-3">

                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/>

                    </svg>

                    <div>

                        <p class="text-sm font-semibold text-gray-700">
                            Note actuelle
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $note->student?->nom }}
                            {{ $note->student?->prenom }}

                            @if($note->subject)
                                — {{ $note->subject->nom }}
                            @endif

                            @if($note->classRoom)
                                — {{ $note->classRoom->nom_classe }}
                            @endif
                        </p>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 BOUTONS
            ================================================== --}}

            <div class="mt-7 pt-5 border-t border-gray-200
                        flex flex-col sm:flex-row
                        sm:justify-end gap-3">

                <a href="{{ route('notes.index') }}"
                   class="inline-flex items-center justify-center gap-2
                          px-5 py-2.5 rounded-lg
                          border border-gray-300
                          bg-white hover:bg-gray-50
                          text-gray-700 font-medium
                          transition">

                    <svg class="w-5 h-5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>

                    </svg>

                    Annuler
                </a>


                <button type="submit"
                        class="inline-flex items-center justify-center gap-2
                               px-5 py-2.5 rounded-lg
                               bg-blue-700 hover:bg-blue-800
                               text-white font-semibold
                               shadow-sm transition">

                    <svg class="w-5 h-5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7"/>

                    </svg>

                    Enregistrer les modifications

                </button>

            </div>

        </form>

    </div>

</div>

@endsection