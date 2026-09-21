@extends('layouts.gestion-ecole')

@section('title', 'Salle - ' . $salle->nom)

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div class="flex items-center gap-4">

            <a href="{{ route('salles.index') }}"
               class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15 18l-6-6 6-6"/>
                </svg>

            </a>

            <div class="flex items-center gap-3">

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 21h18"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 7h2M9 11h2M9 15h2M14 7h2M14 11h2M14 15h2"/>
                    </svg>

                </div>

                <div>

                    <div class="flex items-center gap-2">

                        <h1 class="text-2xl font-bold text-slate-800">
                            {{ $salle->nom }}
                        </h1>

                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                            Salle
                        </span>

                    </div>

                    <p class="text-sm text-slate-500">
                        Informations et personnes affectées à cette salle
                    </p>

                </div>

            </div>

        </div>

        <a href="{{ route('salles.edit', $salle) }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 20h9"/>
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M16.5 3.5a2.12 2.12 0 013 3L8 18l-4 1 1-4L16.5 3.5z"/>
            </svg>

            Modifier
        </a>

    </div>


    {{-- STATISTIQUES --}}
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">

        {{-- CAPACITE --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Capacité
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-800">
                        {{ $salle->capacite ?? '—' }}
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M17 20H7"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 4v12"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 8l4-4 4 4"/>
                    </svg>

                </div>

            </div>

            <p class="mt-2 text-xs text-slate-400">
                Nombre de places
            </p>

        </div>


        {{-- CLASSES --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Classes
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-800">
                        {{ $classRooms->count() }}
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-green-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 21h18"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/>
                    </svg>

                </div>

            </div>

            <p class="mt-2 text-xs text-slate-400">
                Classes dans cette salle
            </p>

        </div>


        {{-- ETUDIANTS --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Étudiants
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-800">
                        {{ $students->count() }}
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-100 text-purple-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <circle cx="9"
                                cy="7"
                                r="4"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 21v-2a6 6 0 0112 0v2"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M16 3.13a4 4 0 010 7.75"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M21 21v-2a6 6 0 00-3-5.19"/>
                    </svg>

                </div>

            </div>

            <p class="mt-2 text-xs text-slate-400">
                Étudiants présents
            </p>

        </div>


        {{-- ENSEIGNANTS --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Enseignants
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-800">
                        {{ $teachers->count() }}
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <circle cx="12"
                                cy="7"
                                r="4"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 21v-2a7 7 0 0114 0v2"/>
                    </svg>

                </div>

            </div>

            <p class="mt-2 text-xs text-slate-400">
                Enseignants affectés
            </p>

        </div>

    </div>


    {{-- CLASSES --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 px-6 py-5">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Classes dans cette salle
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Liste des classes actuellement affectées.
                    </p>

                </div>

                <span class="rounded-full bg-blue-50 px-3 py-1.5 text-sm font-semibold text-blue-700">
                    {{ $classRooms->count() }}
                </span>

            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-slate-50">

                    <tr class="text-left text-xs font-bold uppercase tracking-wide text-slate-500">

                        <th class="px-6 py-4">
                            Classe
                        </th>

                        <th class="px-6 py-4">
                            Niveau
                        </th>

                        <th class="px-6 py-4">
                            Filière
                        </th>

                        <th class="px-6 py-4">
                            Enseignant
                        </th>

                        <th class="px-6 py-4">
                            Étudiants
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($classRooms as $classRoom)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">

                                <div class="font-semibold text-slate-800">
                                    {{ $classRoom->nom_classe }}
                                </div>

                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $classRoom->niveau ?: '—' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $classRoom->filiere ?: '—' }}
                            </td>

                            <td class="px-6 py-4">

                                @if($classRoom->teacher)

                                    <span class="font-medium text-slate-700">
                                        {{ $classRoom->teacher->prenom }}
                                        {{ $classRoom->teacher->nom }}
                                    </span>

                                @else

                                    <span class="text-sm text-slate-400">
                                        Non affecté
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4">

                                <span class="rounded-lg bg-purple-50 px-3 py-1.5 text-sm font-semibold text-purple-700">
                                    {{ $classRoom->inscriptions->whereNotNull('student_id')->count() }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="px-6 py-12 text-center">

                                <p class="font-semibold text-slate-600">
                                    Aucune classe dans cette salle
                                </p>

                                <p class="mt-1 text-sm text-slate-400">
                                    Affectez une classe à cette salle pour voir son contenu.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- ETUDIANTS + ENSEIGNANTS --}}
    <div class="grid gap-6 xl:grid-cols-2">

        {{-- ETUDIANTS --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Étudiants
                    </h2>

                    <p class="text-sm text-slate-500">
                        Étudiants présents dans les classes de cette salle.
                    </p>

                </div>

                <span class="rounded-full bg-purple-50 px-3 py-1.5 text-sm font-semibold text-purple-700">
                    {{ $students->count() }}
                </span>

            </div>

            <div class="divide-y divide-slate-100">

                @forelse($students as $item)

                    @php
                        $student = $item['student'];
                        $classRoom = $item['class_room'];
                    @endphp

                    <div class="flex items-center gap-4 px-6 py-4">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-purple-100 text-sm font-bold text-purple-700">

                            {{ strtoupper(substr($student->prenom ?? $student->nom ?? '?', 0, 1)) }}

                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="truncate font-semibold text-slate-800">

                                {{ $student->prenom }}
                                {{ $student->nom }}

                            </p>

                            <p class="mt-0.5 text-xs text-slate-500">

                                {{ $classRoom->nom_classe }}

                                @if($student->email)
                                    • {{ $student->email }}
                                @endif

                            </p>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-12 text-center">

                        <p class="font-semibold text-slate-600">
                            Aucun étudiant
                        </p>

                        <p class="mt-1 text-sm text-slate-400">
                            Les étudiants apparaîtront ici automatiquement.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- ENSEIGNANTS --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Enseignants
                    </h2>

                    <p class="text-sm text-slate-500">
                        Enseignants affectés aux classes de cette salle.
                    </p>

                </div>

                <span class="rounded-full bg-amber-50 px-3 py-1.5 text-sm font-semibold text-amber-700">
                    {{ $teachers->count() }}
                </span>

            </div>

            <div class="divide-y divide-slate-100">

                @forelse($teachers as $item)

                    @php
                        $teacher = $item['teacher'];
                        $classRoom = $item['class_room'];
                    @endphp

                    <div class="flex items-center gap-4 px-6 py-4">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 text-sm font-bold text-amber-700">

                            {{ strtoupper(substr($teacher->prenom ?? $teacher->nom ?? '?', 0, 1)) }}

                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="truncate font-semibold text-slate-800">

                                {{ $teacher->prenom }}
                                {{ $teacher->nom }}

                            </p>

                            <p class="mt-0.5 text-xs text-slate-500">

                                Classe :
                                {{ $classRoom->nom_classe }}

                                @if($teacher->matiere)
                                    • {{ $teacher->matiere }}
                                @endif

                            </p>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-12 text-center">

                        <p class="font-semibold text-slate-600">
                            Aucun enseignant
                        </p>

                        <p class="mt-1 text-sm text-slate-400">
                            Les enseignants apparaîtront ici automatiquement.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

@endsection
