@extends('layouts.gestion-ecole')

@section('title', 'Affectations des enseignants')

@section('content')

<div class="min-h-screen bg-gray-50">

    {{-- ============================================================
        EN-TÊTE
    ============================================================ --}}
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-900 text-white shadow-sm">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2
                                 M9 11a4 4 0 100-8 4 4 0 000 8
                                 M22 21v-2a4 4 0 00-3-3.87
                                 M16 3.13a4 4 0 010 7.75"/>

                    </svg>

                </div>

                <div>

                    <h1 class="text-2xl font-bold text-gray-900">
                        Affectations des enseignants
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Gestion des enseignants, matières, classes et années scolaires
                    </p>

                </div>

            </div>

        </div>


        {{-- ========================================================
             NOUVELLE AFFECTATION
        ========================================================= --}}
        <a href="{{ route('teacher_subjects.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-4 w-4"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="1.8">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 5v14M5 12h14"/>

            </svg>

            Nouvelle affectation

        </a>

    </div>


    {{-- ============================================================
        MESSAGE SUCCÈS
    ============================================================ --}}
    @if(session('success'))

        <div class="mb-5 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 shadow-sm">

            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-green-100 text-green-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>

                </svg>

            </div>

            <div class="text-sm font-medium text-green-800">
                {{ session('success') }}
            </div>

        </div>

    @endif


    {{-- ============================================================
        ERREURS
    ============================================================ --}}
    @if($errors->any())

        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm">

            <div class="flex items-center gap-2 text-sm font-semibold text-red-800">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 9v4m0 4h.01
                             M10.29 3.86l-7.82 14a2 2 0 001.75 3h15.56a2 2 0 001.75-3l-7.82-14a2 2 0 00-3.42 0z"/>

                </svg>

                Une erreur est survenue

            </div>

            <ul class="mt-2 list-disc space-y-1 pl-7 text-sm text-red-700">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ============================================================
        CARTE PRINCIPALE
    ============================================================ --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">


        {{-- ========================================================
             HEADER CARTE
        ========================================================= --}}
        <div class="flex flex-col gap-4 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-800">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M4 6h16M4 12h16M4 18h16"/>

                    </svg>

                </div>

                <div>

                    <h2 class="text-base font-bold text-gray-900">
                        Liste des affectations
                    </h2>

                    <p class="mt-0.5 text-xs text-gray-500">
                        Enseignants affectés aux matières et aux classes
                    </p>

                </div>

            </div>


            @if($assignments->count())

                <div class="inline-flex items-center rounded-lg bg-blue-50 px-3 py-2">

                    <span class="text-sm font-bold text-blue-800">
                        {{ $assignments->count() }}
                    </span>

                    <span class="ml-1.5 text-sm text-blue-700">
                        {{ $assignments->count() > 1 ? 'affectations' : 'affectation' }}
                    </span>

                </div>

            @endif

        </div>


        {{-- ========================================================
             LISTE
        ========================================================= --}}
        @if($assignments->count())

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    {{-- TABLE HEADER --}}
                    <thead>

                        <tr class="border-b border-gray-200 bg-gray-50">

                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">
                                #
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">
                                Enseignant
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">
                                Matière
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">
                                Classe
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">
                                Année scolaire
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wide text-gray-500">
                                Action
                            </th>

                        </tr>

                    </thead>


                    {{-- TABLE BODY --}}
                    <tbody class="divide-y divide-gray-100">

                        @foreach($assignments as $assignment)

                            <tr class="transition hover:bg-gray-50">


                                {{-- NUMÉRO --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    <span class="text-sm font-semibold text-gray-500">
                                        {{ $loop->iteration }}
                                    </span>

                                </td>


                                {{-- ENSEIGNANT --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-800">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-5 w-5"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.8">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M20 21a8 8 0 00-16 0
                                                         M12 13a4 4 0 100-8 4 4 0 000 8z"/>

                                            </svg>

                                        </div>

                                        <div>

                                            <div class="text-sm font-bold text-gray-900">

                                                {{ $assignment->teacher->nom }}
                                                {{ $assignment->teacher->prenom }}

                                            </div>

                                            @if($assignment->teacher->specialite)

                                                <div class="mt-0.5 text-xs text-gray-500">

                                                    {{ $assignment->teacher->specialite }}

                                                </div>

                                            @else

                                                <div class="mt-0.5 text-xs text-gray-400">
                                                    Enseignant
                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- MATIÈRE --}}
                                <td class="px-5 py-4">

                                    <div class="flex flex-col items-start gap-1">

                                        <span class="inline-flex items-center rounded-lg bg-blue-100 px-2.5 py-1 text-xs font-bold text-blue-800">

                                            {{ $assignment->subject->nom }}

                                        </span>

                                        @if($assignment->subject->code)

                                            <span class="text-xs font-medium text-gray-400">

                                                {{ $assignment->subject->code }}

                                            </span>

                                        @endif

                                    </div>

                                </td>


                                {{-- CLASSE --}}
                                <td class="px-5 py-4">

                                    <div class="text-sm font-bold text-gray-900">

                                        {{ $assignment->classRoom->nom_classe }}

                                    </div>

                                    @if($assignment->classRoom->niveau)

                                        <div class="mt-0.5 text-xs text-gray-500">

                                            {{ $assignment->classRoom->niveau }}

                                        </div>

                                    @endif

                                </td>


                                {{-- ANNÉE SCOLAIRE --}}
                                <td class="px-5 py-4">

                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-green-100 px-2.5 py-1 text-xs font-bold text-green-700">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-3.5 w-3.5"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M8 2v4M16 2v4M3 10h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>

                                        </svg>

                                        {{ $assignment->schoolYear->name }}

                                    </span>

                                </td>


                                {{-- ACTION --}}
                                <td class="px-5 py-4 text-right">

                                    <form action="{{ route('teacher_subjects.destroy', $assignment) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Voulez-vous vraiment supprimer cette affectation ?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                title="Supprimer"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-200 bg-white text-red-600 transition hover:bg-red-50 hover:text-red-700">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.8">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M3 6h18
                                                         M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2
                                                         M19 6l-1 15H6L5 6
                                                         M10 11v6
                                                         M14 11v6"/>

                                            </svg>

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


        @else

            {{-- ====================================================
                 AUCUNE AFFECTATION
            ===================================================== --}}
            <div class="px-6 py-14 text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-8 w-8"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                                 M9 5a3 3 0 016 0
                                 M9 5h6
                                 M9 13h6
                                 M9 17h4"/>

                    </svg>

                </div>

                <h3 class="mt-4 text-base font-bold text-gray-900">
                    Aucune affectation
                </h3>

                <p class="mx-auto mt-1 max-w-md text-sm text-gray-500">
                    Aucun enseignant n'est encore affecté à une matière et une classe.
                </p>

                <a href="{{ route('teacher_subjects.create') }}"
                   class="mt-5 inline-flex items-center justify-center gap-2 rounded-lg bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 5v14M5 12h14"/>

                    </svg>

                    Créer une affectation

                </a>

            </div>

        @endif

    </div>

</div>

@endsection
