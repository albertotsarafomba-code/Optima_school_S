@extends('layouts.gestion-ecole')

@section('title', 'Nouvelle affectation')

@section('content')

<div class="min-h-screen bg-gray-50">

    {{-- HEADER --}}
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-900 text-white shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <line x1="19" x2="19" y1="8" y2="14"/>
                        <line x1="22" x2="16" y1="11" y2="11"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Nouvelle affectation
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Affecter un enseignant à une matière, une classe et une année scolaire.
                    </p>
                </div>

            </div>
        </div>


        {{-- RETOUR --}}
        <a href="{{ route('teacher_subjects.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-4 w-4"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <path d="m15 18-6-6 6-6"/>
            </svg>

            Retour
        </a>

    </div>


    {{-- ERREURS --}}
    @if($errors->any())

        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm">

            <div class="flex items-start gap-3">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" x2="12" y1="8" y2="12"/>
                        <line x1="12" x2="12.01" y1="16" y2="16"/>
                    </svg>

                </div>

                <div class="min-w-0">

                    <h3 class="font-semibold text-red-800">
                        Impossible d'enregistrer l'affectation
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


    {{-- FORMULAIRE --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        {{-- CARD HEADER --}}
        <div class="border-b border-gray-200 bg-white px-6 py-5">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-900">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                        <path d="M15 5l3 3"/>
                    </svg>

                </div>

                <div>

                    <h2 class="text-lg font-bold text-gray-900">
                        Informations de l'affectation
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Sélectionnez les éléments nécessaires pour créer l'affectation.
                    </p>

                </div>

            </div>

        </div>


        {{-- CARD BODY --}}
        <div class="p-6">

            <form action="{{ route('teacher_subjects.store') }}"
                  method="POST">

                @csrf


                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">


                    {{-- ENSEIGNANT --}}
                    <div>

                        <label for="teacher_id"
                               class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4 text-blue-900"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path d="M20 21a8 8 0 0 0-16 0"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>

                            Enseignant

                            <span class="text-red-500">*</span>

                        </label>


                        <select id="teacher_id"
                                name="teacher_id"
                                required
                                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                            <option value="">
                                -- Sélectionner un enseignant --
                            </option>

                            @foreach($teachers as $teacher)

                                <option value="{{ $teacher->id }}"
                                    {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>

                                    {{ $teacher->nom }}
                                    {{ $teacher->prenom }}

                                    @if($teacher->specialite)
                                        — {{ $teacher->specialite }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- MATIERE --}}
                    <div>

                        <label for="subject_id"
                               class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4 text-blue-900"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                            </svg>

                            Matière

                            <span class="text-red-500">*</span>

                        </label>


                        <select id="subject_id"
                                name="subject_id"
                                required
                                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                            <option value="">
                                -- Sélectionner une matière --
                            </option>

                            @foreach($subjects as $subject)

                                <option value="{{ $subject->id }}"
                                    {{ old('subject_id') == $subject->id ? 'selected' : '' }}>

                                    {{ $subject->nom }}

                                    @if($subject->code)
                                        ({{ $subject->code }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- CLASSE --}}
                    <div>

                        <label for="class_room_id"
                               class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4 text-blue-900"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path d="M3 21h18"/>
                                <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"/>
                                <path d="M9 7h1"/>
                                <path d="M14 7h1"/>
                                <path d="M9 11h1"/>
                                <path d="M14 11h1"/>
                                <path d="M9 15h1"/>
                                <path d="M14 15h1"/>
                            </svg>

                            Classe

                            <span class="text-red-500">*</span>

                        </label>


                        <select id="class_room_id"
                                name="class_room_id"
                                required
                                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                            <option value="">
                                -- Sélectionner une classe --
                            </option>

                            @foreach($classRooms as $classRoom)

                                <option value="{{ $classRoom->id }}"
                                    {{ old('class_room_id') == $classRoom->id ? 'selected' : '' }}>

                                    {{ $classRoom->nom_classe }}

                                    @if($classRoom->niveau)
                                        — {{ $classRoom->niveau }}
                                    @endif

                                </option>

                            @endforeach

                        </select>


                        <p class="mt-2 text-xs text-gray-500">

                            La classe doit déjà avoir cette matière affectée.

                        </p>

                    </div>


                    {{-- ANNEE SCOLAIRE --}}
                    <div>

                        <label class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4 text-blue-900"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <rect width="18" height="18" x="3" y="4" rx="2"/>
                                <line x1="16" x2="16" y1="2" y2="6"/>
                                <line x1="8" x2="8" y1="2" y2="6"/>
                                <line x1="3" x2="21" y1="10" y2="10"/>
                            </svg>

                            Année scolaire

                            <span class="text-red-500">*</span>

                        </label>


                        <input type="hidden"
                               name="school_year_id"
                               value="{{ $activeSchoolYear?->id }}">


                        <div class="flex min-h-[44px] items-center rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700">

                            <span class="font-medium">
                                {{ $activeSchoolYear?->name ?? 'Aucune année scolaire sélectionnée' }}
                            </span>

                            @if($activeSchoolYear?->is_active)

                                <span class="ml-2 inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                    Active
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- INFO --}}
                <div class="mt-6 rounded-xl border border-blue-200 bg-blue-50 p-4">

                    <div class="flex items-start gap-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-900">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" x2="12" y1="16" y2="16"/>
                                <line x1="12" x2="12.01" y1="12" y2="12"/>
                            </svg>

                        </div>

                        <div>

                            <h3 class="text-sm font-bold text-blue-900">
                                Règle d'affectation
                            </h3>

                            <p class="mt-1 text-sm leading-6 text-blue-800">

                                L'enseignant pourra ensuite saisir les notes uniquement
                                pour les matières, classes et années scolaires qui lui
                                sont affectées.

                            </p>

                        </div>

                    </div>

                </div>


                {{-- BUTTONS --}}
                <div class="mt-6 flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">

                    <a href="{{ route('teacher_subjects.index') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <path d="M18 6 6 18"/>
                            <path d="m6 6 12 12"/>
                        </svg>

                        Annuler

                    </a>


                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <path d="M15.2 3.8a2.1 2.1 0 0 1 3 3L7 18l-4 1 1-4Z"/>
                            <path d="m14 5 3 3"/>
                        </svg>

                        Enregistrer l'affectation

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const classSelect = document.getElementById('class_room_id');
    const subjectSelect = document.getElementById('subject_id');

    if (!classSelect || !subjectSelect) {
        return;
    }

    const subjectsByClass = {};

    @foreach($classRooms as $classRoom)
        subjectsByClass[{{ $classRoom->id }}] = [
            @foreach($classRoom->subjects as $subject)
                {
                    id: {{ $subject->id }},
                    nom: @json($subject->nom),
                    code: @json($subject->code)
                },
            @endforeach
        ];
    @endforeach

    const oldSubjectId = @json(old('subject_id'));

    function updateSubjects() {

        const classId = classSelect.value;

        subjectSelect.innerHTML = '';

        const defaultOption = document.createElement('option');

        defaultOption.value = '';

        defaultOption.textContent = classId
            ? '-- Sélectionner une matière --'
            : '-- Sélectionner d’abord une classe --';

        subjectSelect.appendChild(defaultOption);

        if (!classId) {
            subjectSelect.disabled = true;
            return;
        }

        const subjects = subjectsByClass[classId] || [];

        if (subjects.length === 0) {

            const emptyOption = document.createElement('option');

            emptyOption.value = '';
            emptyOption.textContent =
                'Aucune matière affectée à cette classe';

            subjectSelect.appendChild(emptyOption);

            subjectSelect.disabled = true;

            return;
        }

        subjectSelect.disabled = false;

        subjects.forEach(function (subject) {

            const option = document.createElement('option');

            option.value = subject.id;

            option.textContent =
                subject.nom +
                (subject.code ? ' (' + subject.code + ')' : '');

            if (
                String(oldSubjectId) ===
                String(subject.id)
            ) {
                option.selected = true;
            }

            subjectSelect.appendChild(option);
        });
    }

    classSelect.addEventListener('change', function () {
        updateSubjects();
    });

    updateSubjects();

});
</script>

@endsection
