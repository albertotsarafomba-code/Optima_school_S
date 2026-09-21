<x-layouts::app.sidebar :title="'Nouvelle affectation'">

    <flux:main>

        <div class="container-fluid py-4">

            {{-- HEADER --}}
            <div class="mb-4">

                <a href="{{ route('teacher_subjects.index') }}"
                   class="btn btn-sm btn-outline-secondary mb-3">

                    <i data-lucide="arrow-left" class="me-1"></i>

                    Retour

                </a>


                <h2 class="fw-bold mb-1">

                    <i data-lucide="user-round-plus" class="me-2"></i>

                    Nouvelle affectation

                </h2>


                <p class="text-muted mb-0">

                    Affecter un enseignant à une matière,
                    une classe et une année scolaire.

                </p>

            </div>


            {{-- ERREURS --}}
            @if($errors->any())

                <div class="alert alert-danger">

                    <div class="fw-semibold mb-2">

                        <i data-lucide="circle-alert" class="me-2"></i>

                        Impossible d'enregistrer l'affectation

                    </div>


                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- FORMULAIRE --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0 fw-semibold">

                        <i data-lucide="clipboard-pen" class="me-2"></i>

                        Informations de l'affectation

                    </h5>

                </div>


                <div class="card-body">

                    <form action="{{ route('teacher_subjects.store') }}"
                          method="POST">

                        @csrf


                        <div class="row g-4">


                            {{-- ENSEIGNANT --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    <i data-lucide="user"
                                       class="me-1"
                                       style="width:16px;height:16px;">
                                    </i>

                                    Enseignant

                                    <span class="text-danger">*</span>

                                </label>


                                <select name="teacher_id"
                                        class="form-select"
                                        required>

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
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    <i data-lucide="book-open"
                                       class="me-1"
                                       style="width:16px;height:16px;">
                                    </i>

                                    Matière

                                    <span class="text-danger">*</span>

                                </label>


                                <select name="subject_id"
                                        class="form-select"
                                        required>

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
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    <i data-lucide="school"
                                       class="me-1"
                                       style="width:16px;height:16px;">
                                    </i>

                                    Classe

                                    <span class="text-danger">*</span>

                                </label>


                                <select name="class_room_id"
                                        class="form-select"
                                        required>

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


                                <div class="form-text">

                                    La classe doit déjà avoir cette matière affectée.

                                </div>

                            </div>


                            {{-- ANNEE --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    <i data-lucide="calendar-days"
                                       class="me-1"
                                       style="width:16px;height:16px;">
                                    </i>

                                    Année scolaire

                                    <span class="text-danger">*</span>

                                </label>


                                <select name="school_year_id"
                                        class="form-select"
                                        required>

                                    <option value="">

                                        -- Sélectionner une année scolaire --

                                    </option>


                                    @foreach($schoolYears as $schoolYear)

                                        <option value="{{ $schoolYear->id }}"
                                            {{ old('school_year_id') == $schoolYear->id ? 'selected' : '' }}>

                                            {{ $schoolYear->name }}

                                            @if($schoolYear->is_active)

                                                — Active

                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                        </div>


                        {{-- INFO --}}
                        <div class="alert alert-info mt-4">

                            <div class="d-flex gap-2">

                                <i data-lucide="info"></i>

                                <div>

                                    <strong>
                                        Règle d'affectation
                                    </strong>

                                    <div class="small mt-1">

                                        L'enseignant pourra ensuite saisir
                                        les notes uniquement pour les matières,
                                        classes et années scolaires qui lui sont
                                        affectées.

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- BUTTONS --}}
                        <div class="d-flex justify-content-end gap-2 mt-4">

                            <a href="{{ route('teacher_subjects.index') }}"
                               class="btn btn-outline-secondary">

                                <i data-lucide="x" class="me-1"></i>

                                Annuler

                            </a>


                            <button type="submit"
                                    class="btn btn-primary">

                                <i data-lucide="save" class="me-1"></i>

                                Enregistrer l'affectation

                            </button>

                        </div>


                    </form>

                </div>

            </div>

        </div>


        <script>

            document.addEventListener('DOMContentLoaded', function () {

                if (typeof lucide !== 'undefined') {

                    lucide.createIcons();

                }

            });

        </script>

    </flux:main>

</x-layouts::app.sidebar>
