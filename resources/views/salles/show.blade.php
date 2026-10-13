@extends('layouts.gestion-ecole')

@section('content')
<div id="salle-detail" class="space-y-6 pb-10">

    {{-- EN-TÊTE --}}
    <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:flex-row md:items-center md:justify-between">
        <div class="flex items-start gap-4">
            <a href="{{ route('salles.index') }}"
               class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50"
               aria-label="Retour aux salles">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m15 18-6-6 6-6"/>
                    <path d="M9 12h12"/>
                </svg>
            </a>

            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">
                    Gestion des salles
                </p>
                <h1 class="mt-1 text-2xl font-extrabold text-slate-900">
                    {{ $salle->nom }}
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $schoolYear?->name ?? 'Année scolaire non définie' }}
                    @if($salle->description)
                        <span class="mx-1">•</span>{{ $salle->description }}
                    @endif
                </p>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 no-print">
            <a href="{{ route('salles.edit', $salle) }}"
               class="inline-flex items-center gap-2 rounded-xl bg-blue-800 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-900">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 20h9"/>
                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                </svg>
                Modifier
            </a>

            <button type="button" onclick="printSalle()"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 9V2h12v7"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <path d="M6 14h12v8H6z"/>
                </svg>
                Imprimer / PDF
            </button>

            <button type="button" onclick="toggleSalleFullscreen()"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M8 3H5a2 2 0 0 0-2 2v3m13-5h3a2 2 0 0 1 2 2v3M3 16v3a2 2 0 0 0 2 2h3m13-5v3a2 2 0 0 1-2 2h-3"/>
                </svg>
                Plein écran
            </button>

            <button type="button" onclick="shareSalle()"
                    class="inline-flex items-center gap-2 rounded-xl bg-green-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-green-800">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="18" cy="5" r="3"/>
                    <circle cx="6" cy="12" r="3"/>
                    <circle cx="18" cy="19" r="3"/>
                    <path d="m8.7 10.7 6.6-4.4m-6.6 9 6.6 4.4"/>
                </svg>
                Partager le lien
            </button>
        </div>
    </div>

    {{-- INDICATEURS --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Capacité de la salle</p>
            <div class="mt-3 flex items-end justify-between">
                <p class="text-3xl font-extrabold text-blue-800">
                    {{ $salle->capacite ?? '—' }}
                </p>
                <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-800">Places</span>
            </div>
        </div>

        <div class="rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Classes associées</p>
            <div class="mt-3 flex items-end justify-between">
                <p class="text-3xl font-extrabold text-green-700">{{ $totalClasses }}</p>
                <span class="rounded-lg bg-green-50 px-3 py-1.5 text-xs font-bold text-green-800">Classes</span>
            </div>
        </div>

        <div class="rounded-2xl border border-purple-100 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Élèves inscrits</p>
            <div class="mt-3 flex items-end justify-between">
                <p class="text-3xl font-extrabold text-purple-700">{{ $totalStudents }}</p>
                <span class="rounded-lg bg-purple-50 px-3 py-1.5 text-xs font-bold text-purple-800">Élèves</span>
            </div>
        </div>

        <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Enseignants</p>
            <div class="mt-3 flex items-end justify-between">
                <p class="text-3xl font-extrabold text-amber-700">{{ $totalTeachers }}</p>
                <span class="rounded-lg bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-800">Affectés</span>
            </div>
        </div>
    </div>

    @if(!$schoolYear)
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
            Aucune année scolaire n'est configurée pour cet établissement. Les informations de planning ne peuvent pas être séparées par année.
        </div>
    @endif

    {{-- CLASSES --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900">Classes dans cette salle</h2>
                <p class="mt-1 text-sm text-slate-500">Classes associées à la salle pour l'année scolaire sélectionnée.</p>
            </div>
            <span class="w-fit rounded-full bg-blue-50 px-3 py-1.5 text-sm font-bold text-blue-800">{{ $classRooms->count() }} classe(s)</span>
        </div>


        {{-- Filtres indépendants des classes --}}
        <div class="mb-4 grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-2"
             data-salle-filter-panel="classes">
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">
                    Rechercher une classe
                </label>
                <input type="search"
                       data-filter-input="classes"
                       placeholder="Nom de la classe..."
                       class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-100">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">
                    Filtrer par niveau
                </label>
                <select data-filter-level="classes"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">Tous les niveaux</option>
                    @foreach($classRooms->pluck('niveau')->filter()->unique()->sort() as $niveau)
                        <option value="{{ $niveau }}">{{ $niveau }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 flex justify-end">
                <button type="button"
                        onclick="resetSalleFilter('classes')"
                        class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    Réinitialiser
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                        <th class="px-5 py-4">Classe</th>
                        <th class="px-5 py-4">Niveau</th>
                        <th class="px-5 py-4">Filière</th>
                        <th class="px-5 py-4">Enseignant principal</th>
                        <th class="px-5 py-4">Effectif</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($classRooms as $classRoom)
                        <tr class="hover:bg-slate-50" data-filter-row="classes" data-class-name="{{ $classRoom->nom_classe }}" data-class-level="{{ $classRoom->niveau }}">
                            <td class="px-5 py-4 font-bold text-slate-800">{{ $classRoom->nom_classe }}</td>
                            <td class="px-5 py-4 text-sm text-slate-600">{{ $classRoom->niveau ?: '—' }}</td>
                            <td class="px-5 py-4 text-sm text-slate-600">{{ $classRoom->filiere ?: '—' }}</td>
                            <td class="px-5 py-4 text-sm text-slate-700">
                                @if($classRoom->teacher)
                                    {{ $classRoom->teacher->prenom }} {{ $classRoom->teacher->nom }}
                                @else
                                    <span class="text-slate-400">Non affecté</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-lg bg-purple-50 px-3 py-1.5 text-sm font-bold text-purple-800">
                                    {{ $classRoom->inscriptions->filter(fn($i) => $i->student !== null)->count() }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <p class="font-bold text-slate-700">Aucune classe trouvée</p>
                                <p class="mt-1 text-sm text-slate-500">Vérifiez l'affectation de la salle et l'année scolaire.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- PLANNING --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900">Emploi du temps de la salle</h2>
                <p class="mt-1 text-sm text-slate-500">Créneaux réellement enregistrés pour cette salle.</p>
            </div>
            <span class="w-fit rounded-full bg-green-50 px-3 py-1.5 text-sm font-bold text-green-800">{{ $emplois->count() }} créneau(x)</span>
        </div>


        {{-- Filtres indépendants de l'emploi du temps --}}
        <div class="mb-4 grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-2 xl:grid-cols-4"
             data-salle-filter-panel="planning">
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Classe</label>
                <select data-planning-class
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                    <option value="">Toutes les classes</option>
                    @foreach($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}">{{ $classRoom->nom_classe }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Jour</label>
                <select data-planning-day
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                    <option value="">Tous les jours</option>
                    @foreach($emplois->pluck('jour')->filter()->unique() as $jour)
                        <option value="{{ $jour }}">{{ $jour }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Matière</label>
                <input type="search" data-planning-subject
                       placeholder="Rechercher une matière..."
                       class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Enseignant</label>
                <input type="search" data-planning-teacher
                       placeholder="Rechercher un enseignant..."
                       class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
            </div>
            <div class="md:col-span-2 xl:col-span-4 flex justify-end">
                <button type="button"
                        onclick="resetSalleFilter('planning')"
                        class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    Réinitialiser
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                        <th class="px-5 py-4">Jour</th>
                        <th class="px-5 py-4">Horaire</th>
                        <th class="px-5 py-4">Classe</th>
                        <th class="px-5 py-4">Matière</th>
                        <th class="px-5 py-4">Enseignant</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($emplois as $emploi)
                        <tr class="hover:bg-slate-50" data-filter-row="planning" data-planning-class="{{ $emploi->class_room_id }}" data-planning-day="{{ $emploi->jour }}" data-planning-subject="{{ $emploi->subject?->nom }}" data-planning-teacher="{{ $emploi->teacher ? $emploi->teacher->prenom.' '.$emploi->teacher->nom : '' }}">
                            <td class="px-5 py-4 font-bold text-slate-800">{{ $emploi->jour }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-700">
                                {{ $emploi->heure_debut ? $emploi->heure_debut->format('H:i') : '—' }}
                                –
                                {{ $emploi->heure_fin ? $emploi->heure_fin->format('H:i') : '—' }}
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-700">{{ $emploi->classRoom?->nom_classe ?? '—' }}</td>
                            <td class="px-5 py-4 text-sm font-semibold text-blue-800">{{ $emploi->subject?->nom ?? '—' }}</td>
                            <td class="px-5 py-4 text-sm text-slate-700">
                                @if($emploi->teacher)
                                    {{ $emploi->teacher->prenom }} {{ $emploi->teacher->nom }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <p class="font-bold text-slate-700">Aucun créneau enregistré</p>
                                <p class="mt-1 text-sm text-slate-500">Le planning apparaîtra ici dès qu'un créneau sera affecté à cette salle.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- ÉLÈVES ET ENSEIGNANTS --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-5">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">Liste des élèves</h2>
                    <p class="mt-1 text-sm text-slate-500">Élèves inscrits dans les classes de cette salle.</p>
                </div>
                <span class="rounded-full bg-purple-50 px-3 py-1.5 text-sm font-bold text-purple-800">{{ $students->count() }}</span>
            </div>


                {{-- Filtres indépendants des élèves --}}
                <div class="mb-4 grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-2"
                     data-salle-filter-panel="students">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Classe</label>
                        <select data-students-class
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                            <option value="">Toutes les classes</option>
                            @foreach($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}">{{ $classRoom->nom_classe }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Rechercher un élève</label>
                        <input type="search" data-students-search
                               placeholder="Nom, prénom ou matricule..."
                               class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                    </div>
                    <div class="md:col-span-2 flex justify-end">
                        <button type="button"
                                onclick="resetSalleFilter('students')"
                                class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                            Réinitialiser
                        </button>
                    </div>
                </div>

            <div class="max-h-[560px] divide-y divide-slate-100 overflow-y-auto">
                @forelse($students as $item)
                    @php
                        $student = $item['student'];
                        $classRoom = $item['class_room'];
                    @endphp
                    <div class="flex items-center gap-3 px-5 py-4" data-filter-row="students" data-student-class="{{ $item['class_room']?->id }}" data-student-name="{{ ($item['student']->prenom ?? '').' '.($item['student']->nom ?? '') }}" data-student-matricule="{{ $item['student']->matricule ?? '' }}">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-purple-100 font-bold text-purple-800">
                            {{ strtoupper(substr($student->prenom ?? $student->nom ?? '?', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-slate-800">{{ $student->prenom }} {{ $student->nom }}</p>
                            <p class="mt-1 text-xs text-slate-500">
                                Classe : {{ $classRoom->nom_classe }}
                                @if($student->matricule)
                                    <span class="mx-1">•</span>Matricule : {{ $student->matricule }}
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center text-sm text-slate-500">
                        Aucun élève inscrit pour cette année scolaire.
                    </div>
                @endforelse
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-5">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">Enseignants et matières</h2>
                    <p class="mt-1 text-sm text-slate-500">Enseignants principaux et intervenants du planning.</p>
                </div>
                <span class="rounded-full bg-amber-50 px-3 py-1.5 text-sm font-bold text-amber-800">{{ $totalTeachers }}</span>
            </div>


                {{-- Filtres indépendants des enseignants --}}
                <div class="mb-4 grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-3"
                     data-salle-filter-panel="teachers">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Classe</label>
                        <select data-teachers-class
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                            <option value="">Toutes les classes</option>
                            @foreach($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}">{{ $classRoom->nom_classe }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Enseignant</label>
                        <input type="search" data-teachers-search
                               placeholder="Nom ou prénom..."
                               class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Matière</label>
                        <input type="search" data-teachers-subject
                               placeholder="Rechercher une matière..."
                               class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                    </div>
                    <div class="md:col-span-3 flex justify-end">
                        <button type="button"
                                onclick="resetSalleFilter('teachers')"
                                class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                            Réinitialiser
                        </button>
                    </div>
                </div>

            <div class="max-h-[560px] divide-y divide-slate-100 overflow-y-auto">
                @forelse($teachers as $item)
                    @php $teacher = $item['teacher']; @endphp
                    <div class="flex items-start gap-3 px-5 py-4" data-filter-row="teachers" data-teacher-class="{{ $item['class_room']?->id }}" data-teacher-name="{{ ($item['teacher']->prenom ?? '').' '.($item['teacher']->nom ?? '') }}" data-teacher-subject="{{ $item['subject']?->nom ?? '' }}">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 font-bold text-amber-800">
                            {{ strtoupper(substr($teacher->prenom ?? $teacher->nom ?? '?', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-slate-800">{{ $teacher->prenom }} {{ $teacher->nom }}</p>
                            <p class="mt-1 text-sm text-slate-600">
                                Classe : {{ $item['class_room']?->nom_classe ?? '—' }}
                            </p>
                            @if($item['subject'])
                                <p class="mt-1 text-sm font-semibold text-blue-800">
                                    Matière : {{ $item['subject']->nom }}
                                </p>
                            @elseif($teacher->specialite)
                                <p class="mt-1 text-sm text-slate-500">
                                    Spécialité : {{ $teacher->specialite }}
                                </p>
                            @endif
                            @if($item['jour'])
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $item['jour'] }}
                                    • {{ $item['heure_debut'] ? $item['heure_debut']->format('H:i') : '—' }}
                                    – {{ $item['heure_fin'] ? $item['heure_fin']->format('H:i') : '—' }}
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center text-sm text-slate-500">
                        Aucun enseignant affecté à cette salle.
                    </div>
                @endforelse
            </div>
        </section>

    </div>

    {{-- MATIÈRES --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-lg font-extrabold text-slate-900">Matières programmées dans cette salle</h2>
        <p class="mt-1 text-sm text-slate-500">Liste dédupliquée à partir de l'emploi du temps.</p>


        {{-- Filtres indépendants des matières programmées --}}
        <div class="mb-4 grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-2"
             data-salle-filter-panel="subjects">
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Classe</label>
                <select data-subjects-class
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                    <option value="">Toutes les classes</option>
                    @foreach($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}">{{ $classRoom->nom_classe }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Rechercher une matière</label>
                <input type="search" data-subjects-search
                       placeholder="Nom de la matière..."
                       class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
            </div>
            <div class="md:col-span-2 flex justify-end">
                <button type="button"
                        onclick="resetSalleFilter('subjects')"
                        class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    Réinitialiser
                </button>
            </div>
        </div>

        <div class="mt-4 flex flex-wrap gap-2">
            @forelse($subjects as $emploi)
                <span class="rounded-xl border border-blue-100 bg-blue-50 px-3 py-2 text-sm font-bold text-blue-800" data-filter-row="subjects" data-subject-class="{{ $emploi->class_room_id }}" data-subject-name="{{ $emploi->subject?->nom }}">
                    {{ $emploi->subject->nom }}
                </span>
            @empty
                <p class="text-sm text-slate-500">Aucune matière n'est encore programmée.</p>
            @endforelse
        </div>
    </section>

    <p id="share-status" class="hidden rounded-xl border border-green-200 bg-green-50 p-3 text-sm font-semibold text-green-800 no-print" role="status"></p>
</div>

<style>
@media print {
    @page { size: A4 landscape; margin: 12mm; }
    body { background: white !important; }
    body * { visibility: hidden !important; }
    #salle-detail, #salle-detail * { visibility: visible !important; }
    #salle-detail {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        background: white !important;
    }
    #salle-detail .no-print { display: none !important; }
    #salle-detail section,
    #salle-detail table,
    #salle-detail tr {
        break-inside: avoid;
    }
    #salle-detail .max-h-\[560px\] {
        max-height: none !important;
        overflow: visible !important;
    }
    #salle-detail .shadow-sm { box-shadow: none !important; }
    #salle-detail .rounded-2xl { border-radius: 6px !important; }
}
</style>

<script>


(function () {
    function normalizeSalleText(value) {
        return (value || '')
            .toString()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLocaleLowerCase()
            .trim();
    }

    function getSalleValue(selector) {
        const element = document.querySelector(selector);
        return element ? normalizeSalleText(element.value) : '';
    }

    function filterSalleSection(section, predicate) {
        document.querySelectorAll('[data-filter-row="' + section + '"]').forEach(function (row) {
            row.hidden = !predicate(row);
        });
    }

    function applySalleFilter(section) {
        if (section === 'classes') {
            const search = getSalleValue('[data-filter-input="classes"]');
            const level = getSalleValue('[data-filter-level="classes"]');

            filterSalleSection('classes', function (row) {
                return normalizeSalleText(row.dataset.className).includes(search)
                    && (!level || normalizeSalleText(row.dataset.classLevel) === level);
            });
        }

        if (section === 'planning') {
            const classId = getSalleValue('[data-planning-class]');
            const day = getSalleValue('[data-planning-day]');
            const subject = getSalleValue('[data-planning-subject]');
            const teacher = getSalleValue('[data-planning-teacher]');

            filterSalleSection('planning', function (row) {
                return (!classId || row.dataset.planningClass === classId)
                    && (!day || normalizeSalleText(row.dataset.planningDay) === day)
                    && normalizeSalleText(row.dataset.planningSubject).includes(subject)
                    && normalizeSalleText(row.dataset.planningTeacher).includes(teacher);
            });
        }

        if (section === 'students') {
            const classId = getSalleValue('[data-students-class]');
            const search = getSalleValue('[data-students-search]');

            filterSalleSection('students', function (row) {
                const identity = normalizeSalleText(
                    row.dataset.studentName + ' ' + row.dataset.studentMatricule
                );

                return (!classId || row.dataset.studentClass === classId)
                    && identity.includes(search);
            });
        }

        if (section === 'teachers') {
            const classId = getSalleValue('[data-teachers-class]');
            const search = getSalleValue('[data-teachers-search]');
            const subject = getSalleValue('[data-teachers-subject]');

            filterSalleSection('teachers', function (row) {
                return (!classId || row.dataset.teacherClass === classId)
                    && normalizeSalleText(row.dataset.teacherName).includes(search)
                    && normalizeSalleText(row.dataset.teacherSubject).includes(subject);
            });
        }

        if (section === 'subjects') {
            const classId = getSalleValue('[data-subjects-class]');
            const search = getSalleValue('[data-subjects-search]');

            filterSalleSection('subjects', function (row) {
                return (!classId || row.dataset.subjectClass === classId)
                    && normalizeSalleText(row.dataset.subjectName).includes(search);
            });
        }
    }

    window.resetSalleFilter = function (section) {
        const selectors = {
            classes: '[data-filter-input="classes"], [data-filter-level="classes"]',
            planning: '[data-planning-class], [data-planning-day], [data-planning-subject], [data-planning-teacher]',
            students: '[data-students-class], [data-students-search]',
            teachers: '[data-teachers-class], [data-teachers-search], [data-teachers-subject]',
            subjects: '[data-subjects-class], [data-subjects-search]'
        };

        if (!selectors[section]) return;

        document.querySelectorAll(selectors[section]).forEach(function (element) {
            element.value = '';
        });

        applySalleFilter(section);
    };

    document.addEventListener('DOMContentLoaded', function () {
        const bindings = [
            ['classes', '[data-filter-input="classes"], [data-filter-level="classes"]'],
            ['planning', '[data-planning-class], [data-planning-day], [data-planning-subject], [data-planning-teacher]'],
            ['students', '[data-students-class], [data-students-search]'],
            ['teachers', '[data-teachers-class], [data-teachers-search], [data-teachers-subject]'],
            ['subjects', '[data-subjects-class], [data-subjects-search]']
        ];

        bindings.forEach(function (binding) {
            document.querySelectorAll(binding[1]).forEach(function (element) {
                element.addEventListener('input', function () {
                    applySalleFilter(binding[0]);
                });

                element.addEventListener('change', function () {
                    applySalleFilter(binding[0]);
                });
            });
        });
    });
})();

function printSalle() {
    window.print();
}

async function toggleSalleFullscreen() {
    const element = document.getElementById('salle-detail');

    try {
        if (!document.fullscreenElement) {
            await element.requestFullscreen();
        } else {
            await document.exitFullscreen();
        }
    } catch (error) {
        alert("Le mode plein écran n'est pas disponible dans ce navigateur.");
    }
}

async function shareSalle() {
    const status = document.getElementById('share-status');

    /*
     * Le lien partagé n'est pas un lien public :
     * l'accès reste protégé par l'authentification et les permissions
     * normales de l'application.
     */
    const shareData = {
        title: 'Salle - {{ addslashes($salle->nom) }}',
        text: 'Consulter les informations de cette salle dans OPTIMASCHOOL.',
        url: window.location.href
    };

    try {
        if (navigator.share) {
            await navigator.share(shareData);
            return;
        }

        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(shareData.url);
            status.textContent = "Lien copié. La personne destinataire devra disposer des autorisations nécessaires pour accéder à la salle.";
            status.classList.remove('hidden');
            return;
        }

        window.prompt('Copiez ce lien. L’accès reste protégé par OPTIMASCHOOL :', shareData.url);
    } catch (error) {
        if (error.name !== 'AbortError') {
            status.textContent = "Le partage n'a pas pu être effectué. Vous pouvez copier l'adresse de la page depuis le navigateur.";
            status.classList.remove('hidden');
        }
    }
}
</script>
@endsection
