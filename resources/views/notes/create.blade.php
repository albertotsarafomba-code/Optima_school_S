@extends('layouts.gestion-ecole')

@section('content')

<div class="max-w-6xl mx-auto">


{{-- HEADER --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Ajouter une note
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Enregistrer une évaluation pour un étudiant.
        </p>
    </div>

    <a href="{{ route('notes.index') }}"
       class="inline-flex items-center justify-center gap-2 rounded-lg
              bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-700
              hover:bg-slate-200 transition">

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

        Retour aux notes
    </a>
</div>


{{-- ETABLISSEMENT --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 mb-6">
    <div class="flex items-center gap-3">

        <div class="w-11 h-11 rounded-lg bg-blue-100 text-blue-700
                    flex items-center justify-center">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-6 h-6"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h1m4 0h1M9 11h1m4 0h1M9 15h1m4 0h1"/>
            </svg>

        </div>

        <div>
            <div class="text-xs uppercase tracking-wide text-slate-500">
                Établissement
            </div>

            <div class="font-semibold text-slate-800">
                {{ $establishment->nom }}
            </div>
        </div>

    </div>
</div>


{{-- ERREURS --}}
@if ($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-6 text-red-700">

        <div class="flex items-start gap-3">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-6 h-6 flex-shrink-0"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-2.6L13.73 4.6a2 2 0 00-3.46 0L3.34 16.4A2 2 0 005.07 19z"/>
            </svg>

            <div>
                <div class="font-semibold mb-2">
                    Vérifiez les informations saisies.
                </div>

                <ul class="list-disc ml-5 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
@endif


{{-- FORMULAIRE --}}
<form action="{{ route('notes.store') }}"
      method="POST"
      id="noteForm"
      class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

    @csrf


    {{-- CONTEXTE SCOLAIRE --}}
    <div class="p-6 border-b border-slate-200">

        <div class="flex items-start gap-3 mb-6">

            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700
                        flex items-center justify-center">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>

            </div>

            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    Contexte scolaire
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Sélectionnez l'année scolaire et la classe.
                </p>
            </div>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- ANNEE SCOLAIRE --}}
            <div>
                <label for="school_year_id"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Année scolaire
                    <span class="text-red-500">*</span>
                </label>

                <select
                    name="school_year_id"
                    id="school_year_id"
                    required
                    class="w-full rounded-lg border-slate-300
                           focus:border-blue-600 focus:ring-blue-600">

                    <option value="">
                        Sélectionner une année
                    </option>

                    @foreach ($schoolYears as $year)
                        <option
                            value="{{ $year->id }}"
                            {{ (string) old('school_year_id', $selectedSchoolYearId) === (string) $year->id ? 'selected' : '' }}>

                            {{ $year->name }}

                            @if ($year->is_active)
                                — Active
                            @endif

                        </option>
                    @endforeach

                </select>
            </div>


            {{-- CLASSE --}}
            <div>

                <label for="class_room_id"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Classe
                    <span class="text-red-500">*</span>
                </label>

                <select
                    name="class_room_id"
                    id="class_room_id"
                    required
                    class="w-full rounded-lg border-slate-300
                           focus:border-blue-600 focus:ring-blue-600">

                    <option value="">
                        Sélectionner une classe
                    </option>

                    @foreach ($classes as $class)

                        <option
                            value="{{ $class->id }}"
                            {{ (string) old('class_room_id') === (string) $class->id ? 'selected' : '' }}>

                            {{ $class->nom_classe }}

                            @if ($class->niveau)
                                — {{ $class->niveau }}
                            @endif

                        </option>

                    @endforeach

                </select>

                <p id="classHelp"
                   class="mt-1 text-xs text-slate-500">
                    Choisissez une classe.
                </p>

            </div>

        </div>
    </div>


    {{-- ETUDIANT / MATIERE / ENSEIGNANT --}}
    <div class="p-6 border-b border-slate-200">

        <div class="flex items-start gap-3 mb-6">

            <div class="w-10 h-10 rounded-lg bg-green-100 text-green-700
                        flex items-center justify-center">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>

            </div>

            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    Élève et enseignement
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Les listes sont filtrées automatiquement selon la classe.
                </p>
            </div>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- ETUDIANT --}}
            <div>

                <label for="student_id"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Étudiant
                    <span class="text-red-500">*</span>
                </label>

                <select
                    name="student_id"
                    id="student_id"
                    required
                    disabled
                    class="w-full rounded-lg border-slate-300 bg-slate-50
                           focus:border-blue-600 focus:ring-blue-600">

                    <option value="">
                        Sélectionner un étudiant
                    </option>

                </select>

                <p id="studentHelp"
                   class="mt-1 text-xs text-slate-500">
                    Sélectionnez d'abord une classe.
                </p>

            </div>


            {{-- MATIERE --}}
            <div>

                <label for="subject_id"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Matière
                    <span class="text-red-500">*</span>
                </label>

                <select
                    name="subject_id"
                    id="subject_id"
                    required
                    disabled
                    class="w-full rounded-lg border-slate-300 bg-slate-50
                           focus:border-blue-600 focus:ring-blue-600">

                    <option value="">
                        Sélectionner une matière
                    </option>

                </select>

                <p id="subjectHelp"
                   class="mt-1 text-xs text-slate-500">
                    Sélectionnez d'abord une classe.
                </p>

            </div>


            {{-- ENSEIGNANT --}}
            <div>

                <label for="teacher_id"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Enseignant
                    <span class="text-red-500">*</span>
                </label>

                <select
                    name="teacher_id"
                    id="teacher_id"
                    required
                    disabled
                    class="w-full rounded-lg border-slate-300 bg-slate-50
                           focus:border-blue-600 focus:ring-blue-600">

                    <option value="">
                        Sélectionner un enseignant
                    </option>

                </select>

                <p id="teacherHelp"
                   class="mt-1 text-xs text-slate-500">
                    Sélectionnez d'abord une matière.
                </p>

            </div>

        </div>
    </div>


    {{-- EVALUATION --}}
    <div class="p-6">

        <div class="flex items-start gap-3 mb-6">

            <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-700
                        flex items-center justify-center">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>

            </div>

            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    Résultat de l'évaluation
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Saisissez la note, le coefficient et les informations de l'évaluation.
                </p>
            </div>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- NOTE --}}
            <div>

                <label for="note"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Note sur 20
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="number"
                    name="note"
                    id="note"
                    min="0"
                    max="20"
                    step="0.01"
                    value="{{ old('note') }}"
                    placeholder="Ex : 15"
                    required
                    class="w-full rounded-lg border-slate-300
                           focus:border-blue-600 focus:ring-blue-600">

                <p class="mt-1 text-xs text-slate-500">
                    Valeur autorisée : de 0 à 20.
                </p>

            </div>


            {{-- COEFFICIENT --}}
            <div>

                <label for="coefficient"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Coefficient
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="number"
                    name="coefficient"
                    id="coefficient"
                    min="0.01"
                    max="100"
                    step="0.01"
                    value="{{ old('coefficient', 1) }}"
                    required
                    class="w-full rounded-lg border-slate-300
                           focus:border-blue-600 focus:ring-blue-600">

                <p id="coefficientHelp"
                   class="mt-1 text-xs text-slate-500">
                    Le coefficient peut être récupéré automatiquement selon la matière.
                </p>

            </div>


            {{-- TYPE EVALUATION --}}
            <div>

                <label for="type_evaluation"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Type d'évaluation
                    <span class="text-red-500">*</span>
                </label>

                <select
                    name="type_evaluation"
                    id="type_evaluation"
                    required
                    class="w-full rounded-lg border-slate-300
                           focus:border-blue-600 focus:ring-blue-600">

                    <option value="">
                        Sélectionner un type
                    </option>

                    <option value="Devoir"
                        {{ old('type_evaluation') === 'Devoir' ? 'selected' : '' }}>
                        Devoir
                    </option>

                    <option value="Interrogation"
                        {{ old('type_evaluation') === 'Interrogation' ? 'selected' : '' }}>
                        Interrogation
                    </option>

                    <option value="Contrôle"
                        {{ old('type_evaluation') === 'Contrôle' ? 'selected' : '' }}>
                        Contrôle
                    </option>

                    <option value="Examen"
                        {{ old('type_evaluation') === 'Examen' ? 'selected' : '' }}>
                        Examen
                    </option>

                    <option value="TP"
                        {{ old('type_evaluation') === 'TP' ? 'selected' : '' }}>
                        TP
                    </option>

                    <option value="Évaluation continue"
                        {{ old('type_evaluation') === 'Évaluation continue' ? 'selected' : '' }}>
                        Évaluation continue
                    </option>

                    <option value="Autre"
                        {{ old('type_evaluation') === 'Autre' ? 'selected' : '' }}>
                        Autre
                    </option>

                </select>

            </div>


            {{-- DATE --}}
            <div>

                <label for="date_evaluation"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Date d'évaluation
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="date"
                    name="date_evaluation"
                    id="date_evaluation"
                    value="{{ old('date_evaluation', now()->format('Y-m-d')) }}"
                    required
                    class="w-full rounded-lg border-slate-300
                           focus:border-blue-600 focus:ring-blue-600">

            </div>

        </div>


        {{-- APERCU --}}
        <div id="notePreview"
             class="hidden mt-6 rounded-xl border border-blue-200
                    bg-blue-50 p-4">

            <div class="flex items-center justify-between gap-4">

                <div>

                    <div class="text-xs uppercase tracking-wide
                                text-blue-700 font-semibold">
                        Aperçu
                    </div>

                    <div id="notePreviewText"
                         class="text-sm text-slate-700 mt-1">
                    </div>

                </div>

                <div id="notePreviewValue"
                     class="text-2xl font-bold text-blue-700">
                </div>

            </div>
        </div>

    </div>


    {{-- FOOTER --}}
    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200
                flex flex-col sm:flex-row justify-end gap-3">

        <a href="{{ route('notes.index') }}"
           class="inline-flex items-center justify-center
                  px-5 py-2.5 rounded-lg bg-white border
                  border-slate-300 text-slate-700 font-medium
                  hover:bg-slate-100 transition">
            Annuler
        </a>

        <button
            type="submit"
            id="submitButton"
            class="inline-flex items-center justify-center gap-2
                   px-5 py-2.5 rounded-lg bg-blue-700
                   hover:bg-blue-800 text-white font-semibold transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 4v16m8-8H4"/>
            </svg>

            <span id="submitText">
                Enregistrer la note
            </span>

        </button>

    </div>

</form>


</div>
@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const schoolYearSelect = document.getElementById('school_year_id');
    const classSelect = document.getElementById('class_room_id');
    const studentSelect = document.getElementById('student_id');
    const subjectSelect = document.getElementById('subject_id');
    const teacherSelect = document.getElementById('teacher_id');

    const coefficientInput = document.getElementById('coefficient');
    const coefficientHelp = document.getElementById('coefficientHelp');

    const noteInput = document.getElementById('note');
    const notePreview = document.getElementById('notePreview');
    const notePreviewText = document.getElementById('notePreviewText');
    const notePreviewValue = document.getElementById('notePreviewValue');

    const submitButton = document.getElementById('submitButton');
    const submitText = document.getElementById('submitText');

    const classHelp = document.getElementById('classHelp');
    const studentHelp = document.getElementById('studentHelp');
    const subjectHelp = document.getElementById('subjectHelp');
    const teacherHelp = document.getElementById('teacherHelp');


    /*
    |--------------------------------------------------------------------------
    | ANCIENNES VALEURS
    |--------------------------------------------------------------------------
    */

    const oldClassId = @json(old('class_room_id'));
    const oldStudentId = @json(old('student_id'));
    const oldSubjectId = @json(old('subject_id'));
    const oldTeacherId = @json(old('teacher_id'));


    /*
    |--------------------------------------------------------------------------
    | VERIFICATION DES ELEMENTS
    |--------------------------------------------------------------------------
    */

    if (
        !schoolYearSelect ||
        !classSelect ||
        !studentSelect ||
        !subjectSelect ||
        !teacherSelect
    ) {
        console.error(
            'Erreur : un ou plusieurs éléments du formulaire sont introuvables.'
        );
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | RESET SELECT
    |--------------------------------------------------------------------------
    */

    function resetSelect(select, text)
    {
        select.innerHTML = '';

        const option = document.createElement('option');

        option.value = '';
        option.textContent = text;

        select.appendChild(option);
    }


    /*
    |--------------------------------------------------------------------------
    | ENABLE / DISABLE
    |--------------------------------------------------------------------------
    */

    function setEnabled(select, enabled)
    {
        select.disabled = !enabled;

        if (enabled) {
            select.classList.remove('bg-slate-50');
        } else {
            select.classList.add('bg-slate-50');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | AJOUTER OPTION
    |--------------------------------------------------------------------------
    */

    function addOption(
        select,
        value,
        text,
        selected = false,
        coefficient = null
    ) {
        const option = document.createElement('option');

        option.value = value;
        option.textContent = text;

        if (selected) {
            option.selected = true;
        }

        if (
            coefficient !== null &&
            coefficient !== undefined &&
            coefficient !== ''
        ) {
            option.dataset.coefficient = coefficient;
        }

        select.appendChild(option);
    }


    /*
    |--------------------------------------------------------------------------
    | FETCH JSON
    |--------------------------------------------------------------------------
    */

    async function getJson(url)
    {
        const response = await fetch(
            url,
            {
                method: 'GET',

                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },

                credentials: 'same-origin'
            }
        );

        if (!response.ok) {

            const responseText = await response.text();

            console.error(
                'Erreur HTTP',
                response.status,
                responseText
            );

            throw new Error(
                'HTTP ' + response.status
            );
        }

        return await response.json();
    }


    /*
    |--------------------------------------------------------------------------
    | CHARGER LES ETUDIANTS
    |--------------------------------------------------------------------------
    */

    async function loadStudents()
    {
        const yearId = schoolYearSelect.value;
        const classId = classSelect.value;

        resetSelect(
            studentSelect,
            'Chargement...'
        );

        setEnabled(
            studentSelect,
            false
        );

        if (!yearId || !classId) {

            resetSelect(
                studentSelect,
                'Sélectionner un étudiant'
            );

            studentHelp.textContent =
                'Sélectionnez d’abord une classe.';

            return;
        }

        try {

            const url =
                "{{ route('notes.students-by-class') }}" +
                '?school_year_id=' +
                encodeURIComponent(yearId) +
                '&class_room_id=' +
                encodeURIComponent(classId);

            console.log(
                'Chargement étudiants :',
                url
            );

            const students = await getJson(url);

            console.log(
                'Étudiants reçus :',
                students
            );

            resetSelect(
                studentSelect,
                'Sélectionner un étudiant'
            );

            if (
                Array.isArray(students) &&
                students.length > 0
            ) {

                students.forEach(function (student) {

                    const fullName =
                        (
                            student.nom ?? ''
                        ) +
                        ' ' +
                        (
                            student.prenom ?? ''
                        );

                    const matricule =
                        student.matricule
                            ? ' — ' + student.matricule
                            : '';

                    addOption(
                        studentSelect,
                        student.id,
                        fullName.trim() + matricule,
                        String(student.id) === String(oldStudentId)
                    );
                });

                setEnabled(
                    studentSelect,
                    true
                );

                studentHelp.textContent =
                    students.length +
                    ' étudiant(s) inscrit(s) dans cette classe.';

            } else {

                resetSelect(
                    studentSelect,
                    'Aucun étudiant inscrit'
                );

                studentHelp.textContent =
                    'Aucun étudiant inscrit dans cette classe pour cette année.';
            }

        } catch (error) {

            console.error(
                'Erreur étudiants :',
                error
            );

            resetSelect(
                studentSelect,
                'Erreur de chargement'
            );

            studentHelp.textContent =
                'Erreur lors du chargement des étudiants.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CHARGER LES MATIERES
    |--------------------------------------------------------------------------
    */

    async function loadSubjects()
    {
        const yearId = schoolYearSelect.value;
        const classId = classSelect.value;

        resetSelect(
            subjectSelect,
            'Chargement...'
        );

        resetSelect(
            teacherSelect,
            'Sélectionner un enseignant'
        );

        setEnabled(
            subjectSelect,
            false
        );

        setEnabled(
            teacherSelect,
            false
        );

        if (!yearId || !classId) {

            resetSelect(
                subjectSelect,
                'Sélectionner une matière'
            );

            subjectHelp.textContent =
                'Sélectionnez d’abord une classe.';

            return;
        }

        try {

            const url =
                "{{ route('notes.subjects-by-class') }}" +
                '?school_year_id=' +
                encodeURIComponent(yearId) +
                '&class_room_id=' +
                encodeURIComponent(classId);

            console.log(
                'Chargement matières :',
                url
            );

            const subjects = await getJson(url);

            console.log(
                'Matières reçues :',
                subjects
            );

            resetSelect(
                subjectSelect,
                'Sélectionner une matière'
            );

            if (
                Array.isArray(subjects) &&
                subjects.length > 0
            ) {

                subjects.forEach(function (subject) {

                    const label =
                        subject.code
                            ? subject.code +
                              ' — ' +
                              subject.nom
                            : subject.nom;

                    const selected =
                        String(subject.id) ===
                        String(oldSubjectId);

                    addOption(
                        subjectSelect,
                        subject.id,
                        label,
                        selected,
                        subject.coefficient
                    );

                    if (
                        selected &&
                        subject.coefficient !== undefined &&
                        subject.coefficient !== null
                    ) {
                        coefficientInput.value =
                            subject.coefficient;

                        coefficientHelp.textContent =
                            'Coefficient récupéré automatiquement depuis la matière.';
                    }
                });

                setEnabled(
                    subjectSelect,
                    true
                );

                subjectHelp.textContent =
                    subjects.length +
                    ' matière(s) affectée(s) à cette classe.';

                if (subjectSelect.value) {
                    await loadTeachers();
                }

            } else {

                resetSelect(
                    subjectSelect,
                    'Aucune matière affectée'
                );

                subjectHelp.textContent =
                    'Aucune matière affectée à cette classe.';
            }

        } catch (error) {

            console.error(
                'Erreur matières :',
                error
            );

            resetSelect(
                subjectSelect,
                'Erreur de chargement'
            );

            subjectHelp.textContent =
                'Erreur lors du chargement des matières.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CHARGER LES ENSEIGNANTS
    |--------------------------------------------------------------------------
    */

    async function loadTeachers()
    {
        const yearId = schoolYearSelect.value;
        const classId = classSelect.value;
        const subjectId = subjectSelect.value;

        resetSelect(
            teacherSelect,
            'Chargement...'
        );

        setEnabled(
            teacherSelect,
            false
        );

        if (
            !yearId ||
            !classId ||
            !subjectId
        ) {

            resetSelect(
                teacherSelect,
                'Sélectionner un enseignant'
            );

            teacherHelp.textContent =
                'Sélectionnez d’abord une matière.';

            return;
        }

        try {

            const url =
                "{{ route('notes.teachers-by-subject') }}" +
                '?school_year_id=' +
                encodeURIComponent(yearId) +
                '&class_room_id=' +
                encodeURIComponent(classId) +
                '&subject_id=' +
                encodeURIComponent(subjectId);

            console.log(
                'Chargement enseignants :',
                url
            );

            const teachers = await getJson(url);

            console.log(
                'Enseignants reçus :',
                teachers
            );

            resetSelect(
                teacherSelect,
                'Sélectionner un enseignant'
            );

            if (
                Array.isArray(teachers) &&
                teachers.length > 0
            ) {

                teachers.forEach(function (teacher) {

                    const fullName =
                        (
                            teacher.nom ?? ''
                        ) +
                        ' ' +
                        (
                            teacher.prenom ?? ''
                        );

                    addOption(
                        teacherSelect,
                        teacher.id,
                        fullName.trim(),
                        String(teacher.id) === String(oldTeacherId)
                    );
                });

                setEnabled(
                    teacherSelect,
                    true
                );

                teacherHelp.textContent =
                    teachers.length +
                    ' enseignant(s) affecté(s) à cette matière.';

            } else {

                resetSelect(
                    teacherSelect,
                    'Aucun enseignant affecté'
                );

                teacherHelp.textContent =
                    'Aucun enseignant affecté à cette matière dans cette classe.';
            }

        } catch (error) {

            console.error(
                'Erreur enseignants :',
                error
            );

            resetSelect(
                teacherSelect,
                'Erreur de chargement'
            );

            teacherHelp.textContent =
                'Erreur lors du chargement des enseignants.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CHARGER LES CLASSES
    |--------------------------------------------------------------------------
    */

    async function loadClasses()
    {
        const yearId = schoolYearSelect.value;

        console.log(
            'Année scolaire sélectionnée :',
            yearId
        );

        resetSelect(
            classSelect,
            'Chargement...'
        );

        setEnabled(
            classSelect,
            false
        );

        resetSelect(
            studentSelect,
            'Sélectionner un étudiant'
        );

        resetSelect(
            subjectSelect,
            'Sélectionner une matière'
        );

        resetSelect(
            teacherSelect,
            'Sélectionner un enseignant'
        );

        setEnabled(
            studentSelect,
            false
        );

        setEnabled(
            subjectSelect,
            false
        );

        setEnabled(
            teacherSelect,
            false
        );

        if (!yearId) {

            resetSelect(
                classSelect,
                'Sélectionner une classe'
            );

            classHelp.textContent =
                'Sélectionnez une année scolaire.';

            return;
        }

        try {

            const url =
                "{{ route('notes.classes-by-school-year') }}" +
                '?school_year_id=' +
                encodeURIComponent(yearId);

            console.log(
                'Chargement classes :',
                url
            );

            const classes = await getJson(url);

            console.log(
                'Classes reçues :',
                classes
            );

            resetSelect(
                classSelect,
                'Sélectionner une classe'
            );

            if (
                Array.isArray(classes) &&
                classes.length > 0
            ) {

                classes.forEach(function (classe) {

                    const label =
                        classe.nom_classe +
                        (
                            classe.niveau
                                ? ' — ' + classe.niveau
                                : ''
                        );

                    addOption(
                        classSelect,
                        classe.id,
                        label,
                        String(classe.id) === String(oldClassId)
                    );
                });

                setEnabled(
                    classSelect,
                    true
                );

                classHelp.textContent =
                    classes.length +
                    ' classe(s) disponible(s).';

                if (classSelect.value) {

                    await Promise.all([
                        loadStudents(),
                        loadSubjects()
                    ]);
                }

            } else {

                resetSelect(
                    classSelect,
                    'Aucune classe disponible'
                );

                classHelp.textContent =
                    'Aucune classe pour cette année scolaire.';
            }

        } catch (error) {

            console.error(
                'Erreur classes :',
                error
            );

            resetSelect(
                classSelect,
                'Erreur de chargement'
            );

            classHelp.textContent =
                'Erreur lors du chargement des classes.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | APERCU DE LA NOTE
    |--------------------------------------------------------------------------
    */

    function updateNotePreview()
    {
        const value =
            parseFloat(noteInput.value);

        if (
            Number.isNaN(value) ||
            value < 0 ||
            value > 20
        ) {

            notePreview.classList.add(
                'hidden'
            );

            return;
        }

        notePreview.classList.remove(
            'hidden'
        );

        notePreviewValue.textContent =
            value.toFixed(2) + ' / 20';

        let mention = '';

        if (value >= 16) {
            mention = 'Très bien';
        } else if (value >= 14) {
            mention = 'Bien';
        } else if (value >= 12) {
            mention = 'Assez bien';
        } else if (value >= 10) {
            mention = 'Passable';
        } else {
            mention = 'Insuffisant';
        }

        notePreviewText.textContent =
            'Note enregistrée avec la mention : ' +
            mention;
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGEMENT ANNEE
    |--------------------------------------------------------------------------
    */

    schoolYearSelect.addEventListener(
        'change',
        async function () {

            console.log(
                'Changement année :',
                this.value
            );

            await loadClasses();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | CHANGEMENT CLASSE
    |--------------------------------------------------------------------------
    */

    classSelect.addEventListener(
        'change',
        async function () {

            console.log(
                'Changement classe :',
                this.value
            );

            await Promise.all([
                loadStudents(),
                loadSubjects()
            ]);
        }
    );


    /*
    |--------------------------------------------------------------------------
    | CHANGEMENT MATIERE
    |--------------------------------------------------------------------------
    */

    subjectSelect.addEventListener(
        'change',
        async function () {

            const selectedOption =
                subjectSelect.options[
                    subjectSelect.selectedIndex
                ];

            if (
                selectedOption &&
                selectedOption.dataset &&
                selectedOption.dataset.coefficient
            ) {

                coefficientInput.value =
                    selectedOption.dataset.coefficient;

                coefficientHelp.textContent =
                    'Coefficient récupéré automatiquement depuis la matière.';
            }

            await loadTeachers();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | CHANGEMENT NOTE
    |--------------------------------------------------------------------------
    */

    noteInput.addEventListener(
        'input',
        updateNotePreview
    );


    /*
    |--------------------------------------------------------------------------
    | ENVOI DU FORMULAIRE
    |--------------------------------------------------------------------------
    */

    document.getElementById('noteForm')
        .addEventListener(
            'submit',
            function () {

                submitButton.disabled = true;

                submitButton.classList.add(
                    'opacity-75',
                    'cursor-not-allowed'
                );

                submitText.textContent =
                    'Enregistrement...';
            }
        );


    /*
    |--------------------------------------------------------------------------
    | INITIALISATION
    |--------------------------------------------------------------------------
    */

    console.log(
        'Initialisation du formulaire Ajouter une note'
    );

    console.log(
        'Année initiale :',
        schoolYearSelect.value
    );

    console.log(
        'Ancienne classe :',
        oldClassId
    );

    console.log(
        'Ancien étudiant :',
        oldStudentId
    );

    console.log(
        'Ancienne matière :',
        oldSubjectId
    );

    console.log(
        'Ancien enseignant :',
        oldTeacherId
    );

    updateNotePreview();

    loadClasses();

});
</script>

@endpush
