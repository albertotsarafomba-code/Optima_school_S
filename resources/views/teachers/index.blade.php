@extends('layouts.gestion-ecole')

@section('title', 'Enseignants')

@section('content')
@php
$teachersWithAssignments = $teachers->filter(function ($teacher) {
return $teacher->teacherSubjects
->filter(function ($assignment) {
return $assignment->subject && $assignment->classRoom;
})
->isNotEmpty();
})->count();


$totalAssignments = $teachers->sum(function ($teacher) {
    return $teacher->teacherSubjects
        ->filter(function ($assignment) {
            return $assignment->subject && $assignment->classRoom;
        })
        ->count();
});


@endphp

<div class="min-h-[calc(100vh-80px)] bg-slate-50 px-4 py-5 sm:px-6 lg:px-8">


<div class="mx-auto max-w-7xl">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="mb-5 flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:flex-row md:items-center md:justify-between">

        <div class="flex items-center gap-3">

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     class="h-6 w-6">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>

            <div>
                <h1 class="text-xl font-bold text-slate-800">
                    Enseignants
                </h1>

                <p class="text-sm text-slate-500">
                    Gestion des enseignants et de leurs affectations
                </p>
            </div>

        </div>

        <a href="{{ route('teachers.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">

            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 class="h-5 w-5">
                <circle cx="9" cy="7" r="4"/>
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <line x1="19" x2="19" y1="8" y2="14"/>
                <line x1="16" x2="22" y1="11" y2="11"/>
            </svg>

            Ajouter un enseignant
        </a>

    </div>


    {{-- =====================================================
         STATISTIQUES
    ====================================================== --}}
    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

        {{-- Total --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Total enseignants
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-800">
                        {{ $teachers->count() }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         class="h-5 w-5">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>

                </div>

            </div>

        </div>


        {{-- Affectés --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Enseignants affectés
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-800">
                        {{ $teachersWithAssignments }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100 text-green-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         class="h-5 w-5">
                        <path d="M9 11l3 3L22 4"/>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>

                </div>

            </div>

        </div>


        {{-- Affectations --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Affectations
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-800">
                        {{ $totalAssignments }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         class="h-5 w-5">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         MESSAGE SUCCESS
    ====================================================== --}}
    @if(session('success'))

        <div class="mb-5 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">

            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 class="h-5 w-5 shrink-0">
                <circle cx="12" cy="12" r="10"/>
                <path d="m9 12 2 2 4-4"/>
            </svg>

            <span>{{ session('success') }}</span>

        </div>

    @endif


    {{-- =====================================================
         CONTENU
    ====================================================== --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Titre tableau --}}
        <div class="flex items-center gap-3 border-b border-slate-200 bg-slate-50 px-5 py-4">

            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     class="h-5 w-5">
                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                    <path d="M3 9h18"/>
                    <path d="M9 21V9"/>
                </svg>

            </div>

            <div>
                <h2 class="text-sm font-bold text-slate-800">
                    Liste des enseignants
                </h2>

                <p class="text-xs text-slate-500">
                    Classes et matières réellement sélectionnées
                </p>
            </div>

        </div>


        @if($teachers->isNotEmpty())

            {{-- =================================================
                 VERSION DESKTOP
            ================================================== --}}
            <div class="hidden md:block">

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="border-b border-slate-200 bg-white">

                            <tr>

                                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Enseignant
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Contact
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Classes et matières
                                </th>

                                <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            @foreach($teachers as $teacher)

                                @php
                                    $assignments = $teacher->teacherSubjects
                                        ->filter(function ($assignment) {
                                            return $assignment->subject && $assignment->classRoom;
                                        });

                                    $groupedAssignments = $assignments->groupBy('class_room_id');
                                @endphp

                                <tr class="transition hover:bg-slate-50">

                                    {{-- =========================
                                         ENSEIGNANT
                                    ========================== --}}
                                    <td class="px-5 py-4 align-top">

                                        <div class="flex items-center gap-3">

                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">

                                                {{ strtoupper(substr($teacher->prenom ?: $teacher->nom ?: 'E', 0, 1)) }}

                                            </div>

                                            <div class="min-w-0">

                                                <div class="font-semibold text-slate-800">
                                                    {{ $teacher->prenom }} {{ $teacher->nom }}
                                                </div>

                                                @if($teacher->specialite)
                                                    <div class="mt-0.5 text-xs text-slate-500">
                                                        {{ $teacher->specialite }}
                                                    </div>
                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- =========================
                                         CONTACT
                                    ========================== --}}
                                    <td class="px-5 py-4 align-top">

                                        <div class="space-y-2">

                                            @if($teacher->telephone)

                                                <div class="flex items-center gap-2 text-xs text-slate-600">

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         viewBox="0 0 24 24"
                                                         fill="none"
                                                         stroke="currentColor"
                                                         stroke-width="2"
                                                         stroke-linecap="round"
                                                         stroke-linejoin="round"
                                                         class="h-4 w-4 shrink-0 text-blue-600">
                                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2
                                                        19.79 19.79 0 0 1-8.63-3.07
                                                        19.5 19.5 0 0 1-6-6
                                                        19.79 19.79 0 0 1-3.07-8.67
                                                        A2 2 0 0 1 4.11 2h3
                                                        a2 2 0 0 1 2 1.72
                                                        12.84 12.84 0 0 0 .7 2.81
                                                        2 2 0 0 1-.45 2.11L8.09 9.91
                                                        a16 16 0 0 0 6 6l1.27-1.27
                                                        a2 2 0 0 1 2.11-.45
                                                        12.84 12.84 0 0 0 2.81.7
                                                        A2 2 0 0 1 22 16.92z"/>
                                                    </svg>

                                                    <span>{{ $teacher->telephone }}</span>

                                                </div>

                                            @endif


                                            @if($teacher->email)

                                                <div class="flex items-center gap-2 text-xs text-slate-600">

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         viewBox="0 0 24 24"
                                                         fill="none"
                                                         stroke="currentColor"
                                                         stroke-width="2"
                                                         stroke-linecap="round"
                                                         stroke-linejoin="round"
                                                         class="h-4 w-4 shrink-0 text-green-600">
                                                        <rect width="20" height="16" x="2" y="4" rx="2"/>
                                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                                    </svg>

                                                    <span class="max-w-[180px] truncate">
                                                        {{ $teacher->email }}
                                                    </span>

                                                </div>

                                            @endif

                                        </div>

                                    </td>


                                    {{-- =========================
                                         CLASSES / MATIERES
                                    ========================== --}}
                                    <td class="px-5 py-4 align-top">

                                        @if($groupedAssignments->isNotEmpty())

                                            <div class="space-y-2">

                                                @foreach($groupedAssignments as $classAssignments)

                                                    @php
    $class = $classAssignments->first()->classRoom;

    $subjectsForClass = $classAssignments
        ->map(function ($assignment) {
            return $assignment->subject?->nom;
        })
        ->filter(function ($subjectName) {
            return filled($subjectName);
        })
        ->unique()
        ->values();
@endphp

                                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">

                                                        {{-- Classe --}}
                                                        <div class="flex items-center gap-2">

                                                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                     viewBox="0 0 24 24"
                                                                     fill="none"
                                                                     stroke="currentColor"
                                                                     stroke-width="2"
                                                                     stroke-linecap="round"
                                                                     stroke-linejoin="round"
                                                                     class="h-4 w-4">
                                                                    <path d="M3 21h18"/>
                                                                    <path d="M6 21V3h12v18"/>
                                                                    <path d="M9 7h6"/>
                                                                    <path d="M9 11h6"/>
                                                                    <path d="M9 15h6"/>
                                                                </svg>

                                                            </div>

                                                            <span class="text-sm font-bold text-slate-800">
                                                                {{ $class->nom_classe }}
                                                            </span>

                                                        </div>


                                                        {{-- Matières --}}
                                                        <div class="mt-2 flex flex-wrap gap-1.5">

                                                            @foreach($subjectsForClass as $subjectName)

                                                                <span class="inline-flex items-center gap-1.5 rounded-lg border border-green-200 bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">

                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                         viewBox="0 0 24 24"
                                                                         fill="none"
                                                                         stroke="currentColor"
                                                                         stroke-width="2"
                                                                         stroke-linecap="round"
                                                                         stroke-linejoin="round"
                                                                         class="h-3.5 w-3.5">
                                                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                                                                    </svg>

                                                                    {{ $subjectName }}

                                                                </span>

                                                            @endforeach

                                                        </div>

                                                    </div>

                                                @endforeach

                                            </div>

                                        @else

                                            <span class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-3 py-2 text-xs font-medium text-slate-500">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 24 24"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     stroke-width="2"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round"
                                                     class="h-4 w-4">
                                                    <circle cx="12" cy="12" r="10"/>
                                                    <line x1="8" x2="16" y1="12" y2="12"/>
                                                </svg>

                                                Aucune affectation

                                            </span>

                                        @endif

                                    </td>


                                    {{-- =========================
                                         ACTIONS
                                    ========================== --}}
                                    <td class="px-5 py-4 align-top">

                                        <div class="flex items-center justify-end gap-1.5">

                                            {{-- Voir --}}
                                            <a href="{{ route('teachers.show', $teacher) }}"
                                               title="Voir"
                                               class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 24 24"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     stroke-width="2"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round"
                                                     class="h-4.5 w-4.5">
                                                    <path d="M2.06 12.35a1 1 0 0 1 0-.7
                                                    10.75 10.75 0 0 1 19.88 0
                                                    1 1 0 0 1 0 .7
                                                    10.75 10.75 0 0 1-19.88 0z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>

                                            </a>


                                            {{-- Modifier --}}
                                            <a href="{{ route('teachers.edit', $teacher) }}"
                                               title="Modifier"
                                               class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-green-200 hover:bg-green-50 hover:text-green-700">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 24 24"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     stroke-width="2"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round"
                                                     class="h-4.5 w-4.5">
                                                    <path d="M12 20h9"/>
                                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1
                                                    1-4Z"/>
                                                </svg>

                                            </a>


                                            {{-- Supprimer --}}
                                            <form action="{{ route('teachers.destroy', $teacher) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cet enseignant ?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        title="Supprimer"
                                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600">

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         viewBox="0 0 24 24"
                                                         fill="none"
                                                         stroke="currentColor"
                                                         stroke-width="2"
                                                         stroke-linecap="round"
                                                         stroke-linejoin="round"
                                                         class="h-4.5 w-4.5">
                                                        <polyline points="3 6 5 6 21 6"/>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7
                                                        a2 2 0 0 1-2-2V6"/>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4
                                                        a2 2 0 0 1 2 2v2"/>
                                                        <line x1="10" x2="10" y1="11" y2="17"/>
                                                        <line x1="14" x2="14" y1="11" y2="17"/>
                                                    </svg>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- =================================================
                 VERSION MOBILE
            ================================================== --}}
            <div class="divide-y divide-slate-100 md:hidden">

                @foreach($teachers as $teacher)

                    @php
                        $assignments = $teacher->teacherSubjects
                            ->filter(function ($assignment) {
                                return $assignment->subject && $assignment->classRoom;
                            });

                        $groupedAssignments = $assignments->groupBy('class_room_id');
                    @endphp

                    <div class="p-4">

                        {{-- Enseignant --}}
                        <div class="flex items-center gap-3">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">

                                {{ strtoupper(substr($teacher->prenom ?: $teacher->nom ?: 'E', 0, 1)) }}

                            </div>

                            <div class="min-w-0 flex-1">

                                <h3 class="font-semibold text-slate-800">
                                    {{ $teacher->prenom }} {{ $teacher->nom }}
                                </h3>

                                @if($teacher->specialite)
                                    <p class="text-xs text-slate-500">
                                        {{ $teacher->specialite }}
                                    </p>
                                @endif

                            </div>

                        </div>


                        {{-- Contact --}}
                        <div class="mt-3 space-y-1.5">

                            @if($teacher->telephone)

                                <div class="flex items-center gap-2 text-xs text-slate-600">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="h-4 w-4 text-blue-600">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2
                                        19.79 19.79 0 0 1-8.63-3.07
                                        19.5 19.5 0 0 1-6-6
                                        19.79 19.79 0 0 1-3.07-8.67
                                        A2 2 0 0 1 4.11 2h3
                                        a2 2 0 0 1 2 1.72
                                        12.84 12.84 0 0 0 .7 2.81
                                        2 2 0 0 1-.45 2.11L8.09 9.91
                                        a16 16 0 0 0 6 6l1.27-1.27
                                        a2 2 0 0 1 2.11-.45
                                        12.84 12.84 0 0 0 2.81.7
                                        A2 2 0 0 1 22 16.92z"/>
                                    </svg>

                                    {{ $teacher->telephone }}

                                </div>

                            @endif


                            @if($teacher->email)

                                <div class="flex items-center gap-2 text-xs text-slate-600">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="h-4 w-4 text-green-600">
                                        <rect width="20" height="16" x="2" y="4" rx="2"/>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                    </svg>

                                    <span class="truncate">
                                        {{ $teacher->email }}
                                    </span>

                                </div>

                            @endif

                        </div>


                        {{-- Affectations --}}
                        <div class="mt-4">

                            <div class="mb-2 flex items-center gap-2 text-xs font-bold uppercase tracking-wide text-slate-500">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="h-4 w-4">
                                    <path d="M3 21h18"/>
                                    <path d="M6 21V3h12v18"/>
                                    <path d="M9 7h6"/>
                                    <path d="M9 11h6"/>
                                    <path d="M9 15h6"/>
                                </svg>

                                Classes et matières

                            </div>


                            @if($groupedAssignments->isNotEmpty())

                                <div class="space-y-2">

                                    @foreach($groupedAssignments as $classAssignments)

                                        @php
                                            $class = $classAssignments->first()->classRoom;

                                            $subjectsForClass = $classAssignments
                                                ->filter(function ($assignment) {
                                                    return $assignment->subject;
                                                })
                                                ->pluck('subject.nom')
                                                ->filter()
                                                ->unique()
                                                ->values();
                                        @endphp

                                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">

                                            <div class="flex items-center gap-2 text-sm font-bold text-slate-800">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 24 24"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     stroke-width="2"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round"
                                                     class="h-4 w-4 text-blue-700">
                                                    <path d="M3 21h18"/>
                                                    <path d="M6 21V3h12v18"/>
                                                    <path d="M9 7h6"/>
                                                    <path d="M9 11h6"/>
                                                    <path d="M9 15h6"/>
                                                </svg>

                                                {{ $class->nom_classe }}

                                            </div>

                                            <div class="mt-2 flex flex-wrap gap-1.5">

                                                @foreach($subjectsForClass as $subjectName)

                                                    <span class="inline-flex items-center gap-1 rounded-lg bg-green-50 px-2 py-1 text-xs font-medium text-green-700">

                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 24 24"
                                                             fill="none"
                                                             stroke="currentColor"
                                                             stroke-width="2"
                                                             stroke-linecap="round"
                                                             stroke-linejoin="round"
                                                             class="h-3.5 w-3.5">
                                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                                                        </svg>

                                                        {{ $subjectName }}

                                                    </span>

                                                @endforeach

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            @else

                                <div class="flex items-center gap-2 rounded-lg bg-slate-100 px-3 py-2 text-xs text-slate-500">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="h-4 w-4">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="8" x2="16" y1="12" y2="12"/>
                                    </svg>

                                    Aucune affectation

                                </div>

                            @endif

                        </div>


                        {{-- Actions --}}
                        <div class="mt-4 flex justify-end gap-2 border-t border-slate-100 pt-3">

                            <a href="{{ route('teachers.show', $teacher) }}"
                               title="Voir"
                               class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-blue-50 hover:text-blue-700">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="h-4.5 w-4.5">
                                    <path d="M2.06 12.35a1 1 0 0 1 0-.7
                                    10.75 10.75 0 0 1 19.88 0
                                    1 1 0 0 1 0 .7
                                    10.75 10.75 0 0 1-19.88 0z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>

                            </a>


                            <a href="{{ route('teachers.edit', $teacher) }}"
                               title="Modifier"
                               class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-green-50 hover:text-green-700">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="h-4.5 w-4.5">
                                    <path d="M12 20h9"/>
                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1
                                    1-4Z"/>
                                </svg>

                            </a>


                            <form action="{{ route('teachers.destroy', $teacher) }}"
                                  method="POST"
                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cet enseignant ?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        title="Supprimer"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-red-50 hover:text-red-600">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="h-4.5 w-4.5">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7
                                        a2 2 0 0 1-2-2V6"/>
                                        <path d="M8 6V4a2 2 0 0 1 2-2h4
                                        a2 2 0 0 1 2 2v2"/>
                                        <line x1="10" x2="10" y1="11" y2="17"/>
                                        <line x1="14" x2="14" y1="11" y2="17"/>
                                    </svg>

                                </button>

                            </form>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            {{-- =================================================
                 EMPTY
            ================================================== --}}
            <div class="px-6 py-14 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         class="h-7 w-7">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>

                </div>

                <h3 class="mt-4 text-base font-bold text-slate-800">
                    Aucun enseignant
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Aucun enseignant n'est encore enregistré dans cet établissement.
                </p>

                <a href="{{ route('teachers.create') }}"
                   class="mt-5 inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         class="h-5 w-5">
                        <line x1="12" x2="12" y1="5" y2="19"/>
                        <line x1="5" x2="19" y1="12" y2="12"/>
                    </svg>

                    Ajouter un enseignant

                </a>

            </div>

        @endif

    </div>

</div>


</div>
@endsection
