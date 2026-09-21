<x-layouts::app.sidebar :title="'Affectations des enseignants'">

    <flux:main>

        <div class="container-fluid py-4">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>
                    <h2 class="fw-bold mb-1">
                        <i data-lucide="users-round" class="me-2"></i>
                        Affectations des enseignants
                    </h2>

                    <p class="text-muted mb-0">
                        Gestion des enseignants, matières, classes et années scolaires
                    </p>
                </div>

                <a href="{{ route('teacher_subjects.create') }}"
                   class="btn btn-primary d-flex align-items-center gap-2">

                    <i data-lucide="plus"></i>
                    Nouvelle affectation

                </a>

            </div>


            {{-- SUCCESS --}}
            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    <i data-lucide="circle-check" class="me-2"></i>

                    {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            {{-- ERRORS --}}
            @if($errors->any())

                <div class="alert alert-danger">

                    <div class="fw-semibold mb-2">

                        <i data-lucide="circle-alert" class="me-2"></i>

                        Une erreur est survenue

                    </div>

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- CARD --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0 fw-semibold">

                        <i data-lucide="list" class="me-2"></i>

                        Liste des affectations

                    </h5>

                </div>


                <div class="card-body p-0">

                    @if($assignments->count())

                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th>#</th>
                                        <th>Enseignant</th>
                                        <th>Matière</th>
                                        <th>Classe</th>
                                        <th>Année scolaire</th>
                                        <th class="text-end">Action</th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($assignments as $assignment)

                                        <tr>

                                            <td>
                                                {{ $loop->iteration }}
                                            </td>


                                            {{-- ENSEIGNANT --}}
                                            <td>

                                                <div class="d-flex align-items-center gap-2">

                                                    <div class="avatar-teacher">

                                                        <i data-lucide="user"></i>

                                                    </div>

                                                    <div>

                                                        <div class="fw-semibold">

                                                            {{ $assignment->teacher->nom }}
                                                            {{ $assignment->teacher->prenom }}

                                                        </div>

                                                        @if($assignment->teacher->specialite)

                                                            <small class="text-muted">

                                                                {{ $assignment->teacher->specialite }}

                                                            </small>

                                                        @endif

                                                    </div>

                                                </div>

                                            </td>


                                            {{-- MATIERE --}}
                                            <td>

                                                <span class="badge bg-primary-subtle text-primary">

                                                    {{ $assignment->subject->nom }}

                                                </span>

                                                @if($assignment->subject->code)

                                                    <small class="text-muted d-block mt-1">

                                                        {{ $assignment->subject->code }}

                                                    </small>

                                                @endif

                                            </td>


                                            {{-- CLASSE --}}
                                            <td>

                                                <div class="fw-semibold">

                                                    {{ $assignment->classRoom->nom_classe }}

                                                </div>

                                                @if($assignment->classRoom->niveau)

                                                    <small class="text-muted">

                                                        {{ $assignment->classRoom->niveau }}

                                                    </small>

                                                @endif

                                            </td>


                                            {{-- ANNEE --}}
                                            <td>

                                                <span class="badge bg-success-subtle text-success">

                                                    <i data-lucide="calendar-days"
                                                       style="width:14px;height:14px;">
                                                    </i>

                                                    {{ $assignment->schoolYear->name }}

                                                </span>

                                            </td>


                                            {{-- ACTION --}}
                                            <td class="text-end">

                                                <form action="{{ route(
                                                    'teacher_subjects.destroy',
                                                    $assignment
                                                ) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm(
                                                    'Voulez-vous vraiment supprimer cette affectation ?'
                                                )">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Supprimer">

                                                        <i data-lucide="trash-2"></i>

                                                    </button>

                                                </form>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center py-5">

                            <div class="empty-icon mb-3">

                                <i data-lucide="clipboard-list"></i>

                            </div>

                            <h5 class="fw-semibold">

                                Aucune affectation

                            </h5>

                            <p class="text-muted">

                                Aucun enseignant n'est encore affecté
                                à une matière et une classe.

                            </p>

                            <a href="{{ route('teacher_subjects.create') }}"
                               class="btn btn-primary">

                                <i data-lucide="plus" class="me-1"></i>

                                Créer une affectation

                            </a>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        <style>

            .avatar-teacher {

                width: 42px;
                height: 42px;
                border-radius: 50%;

                display: flex;
                align-items: center;
                justify-content: center;

                background: #e8f1ff;
                color: #0d6efd;

            }

            .avatar-teacher svg {

                width: 20px;
                height: 20px;

            }

            .empty-icon {

                width: 70px;
                height: 70px;

                margin: auto;

                border-radius: 50%;

                display: flex;
                align-items: center;
                justify-content: center;

                background: #e9f8ef;
                color: #198754;

            }

            .empty-icon svg {

                width: 32px;
                height: 32px;

            }

        </style>


        <script>

            document.addEventListener('DOMContentLoaded', function () {

                if (typeof lucide !== 'undefined') {

                    lucide.createIcons();

                }

            });

        </script>

    </flux:main>

</x-layouts::app.sidebar>
