@extends('layouts.gestion-ecole')

@section('title', 'Tableau de bord Secrétaire')

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
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Tableau de bord Secrétaire
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Gestion administrative et scolaire de l'établissement
                    </p>
                </div>

            </div>
        </div>

        {{-- Établissement --}}
        <div class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm">

            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-700">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 9h.01M12 9h.01M15 9h.01"/>
                </svg>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Établissement
                </p>

                <p class="text-sm font-bold text-gray-900">
                    {{ $establishment->nom ?? 'Établissement' }}
                </p>

                <p class="text-xs text-green-700">
                    {{ $schoolTypeName ?? 'Type non défini' }}
                </p>
            </div>

        </div>

    </div>


    {{-- ============================================================
        ANNÉE SCOLAIRE
    ============================================================ --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

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
                              d="M8 2v4M16 2v4M3 10h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                    </svg>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Année scolaire active
                    </p>

                    <p class="text-base font-bold text-gray-900">
                        @if($schoolYear)
                            {{ $schoolYear->nom
                                ?? $schoolYear->name
                                ?? $schoolYear->annee_scolaire
                                ?? 'Année scolaire' }}
                        @else
                            Aucune année scolaire sélectionnée
                        @endif
                    </p>
                </div>

            </div>

            <a href="{{ route('school_years.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M8 7V3m8 4V3M4 11h16M6 5h12a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                </svg>

                Voir l'année scolaire
            </a>

        </div>

    </div>


    {{-- ============================================================
        STATISTIQUES ADMINISTRATIVES
    ============================================================ --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">

        {{-- Étudiants --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Étudiants
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $stats['students'] ?? 0 }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-800">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>

                </div>

            </div>

            <a href="{{ route('students.index') }}"
               class="mt-4 inline-block text-sm font-semibold text-blue-800 hover:text-blue-600">
                Voir les étudiants →
            </a>

        </div>


        {{-- Inscriptions --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Inscriptions
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $stats['inscriptions'] ?? 0 }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 12h6M9 16h6M8 3h8l2 3v15H6V6l2-3zM9 3v3h6V3"/>
                    </svg>

                </div>

            </div>

            <a href="{{ route('inscriptions.index') }}"
               class="mt-4 inline-block text-sm font-semibold text-green-700 hover:text-green-600">
                Voir les inscriptions →
            </a>

        </div>


        {{-- Classes --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Classes
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $stats['classes'] ?? 0 }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M4 19V5a2 2 0 012-2h12a2 2 0 012 2v14M4 19h16M8 7h8M8 11h8M8 15h5"/>
                    </svg>

                </div>

            </div>

            <a href="{{ route('class_rooms.index') }}"
               class="mt-4 inline-block text-sm font-semibold text-indigo-700 hover:text-indigo-600">
                Voir les classes →
            </a>

        </div>


        {{-- Enseignants --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Enseignants
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $stats['teachers'] ?? 0 }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 14a5 5 0 100-10 5 5 0 000 10zM4 21a8 8 0 0116 0"/>
                    </svg>

                </div>

            </div>

            <a href="{{ route('teachers.index') }}"
               class="mt-4 inline-block text-sm font-semibold text-emerald-700 hover:text-emerald-600">
                Voir les enseignants →
            </a>

        </div>


        {{-- Matières --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Matières
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $stats['subjects'] ?? 0 }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-purple-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 6.5a4.5 4.5 0 00-4.5-4.5H3v16h4.5A4.5 4.5 0 0112 22M12 6.5A4.5 4.5 0 0116.5 2H21v16h-4.5A4.5 4.5 0 0012 22m0-15.5V22"/>
                    </svg>

                </div>

            </div>

            <a href="{{ route('subjects.index') }}"
               class="mt-4 inline-block text-sm font-semibold text-purple-700 hover:text-purple-600">
                Voir les matières →
            </a>

        </div>

    </div>


    {{-- ============================================================
        ACTIONS RAPIDES
    ============================================================ --}}
    <div class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

        <div class="mb-5">
            <h2 class="text-lg font-bold text-gray-900">
                Actions rapides
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Accès rapide aux principales tâches du secrétariat
            </p>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Ajouter étudiant --}}
            <a href="{{ route('students.create') }}"
               class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 transition hover:border-blue-300 hover:bg-blue-50">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-800">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M15 19a4 4 0 00-8 0M11 11a4 4 0 100-8 4 4 0 000 8zM19 8v6M22 11h-6"/>
                    </svg>

                </div>

                <div>
                    <p class="text-sm font-bold text-gray-900">
                        Ajouter étudiant
                    </p>

                    <p class="text-xs text-gray-500">
                        Nouveau dossier
                    </p>
                </div>

            </a>


            {{-- Nouvelle inscription --}}
            <a href="{{ route('inscriptions.create') }}"
               class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 transition hover:border-green-300 hover:bg-green-50">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-green-100 text-green-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 5v14M5 12h14"/>
                    </svg>

                </div>

                <div>
                    <p class="text-sm font-bold text-gray-900">
                        Nouvelle inscription
                    </p>

                    <p class="text-xs text-gray-500">
                        Inscrire un étudiant
                    </p>
                </div>

            </a>


            {{-- Voir classes --}}
            <a href="{{ route('class_rooms.index') }}"
               class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 transition hover:border-indigo-300 hover:bg-indigo-50">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M4 19V5a2 2 0 012-2h12a2 2 0 012 2v14M4 19h16"/>
                    </svg>

                </div>

                <div>
                    <p class="text-sm font-bold text-gray-900">
                        Voir les classes
                    </p>

                    <p class="text-xs text-gray-500">
                        Classes scolaires
                    </p>
                </div>

            </a>


            {{-- Emploi du temps --}}
            <a href="{{ route('emploi-du-temps.index') }}"
               class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 transition hover:border-orange-300 hover:bg-orange-50">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-orange-100 text-orange-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 2v4M16 2v4M3 10h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                    </svg>

                </div>

                <div>
                    <p class="text-sm font-bold text-gray-900">
                        Emploi du temps
                    </p>

                    <p class="text-xs text-gray-500">
                        Planning scolaire
                    </p>
                </div>

            </a>

        </div>

    </div>


    {{-- ============================================================
        GESTION ADMINISTRATIVE
    ============================================================ --}}
    <div class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

        <div class="mb-5">
            <h2 class="text-lg font-bold text-gray-900">
                Gestion administrative
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Accès rapide aux principales fonctions du secrétariat
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Élèves --}}
            <a href="{{ route('students.index') }}"
               class="group rounded-xl border border-gray-200 bg-gray-50 p-4 transition hover:border-blue-300 hover:bg-blue-50">

                <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-lg bg-blue-100 text-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </div>

                <p class="text-sm font-bold text-gray-900">
                    Élèves
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Gérer les dossiers élèves
                </p>
            </a>


            {{-- Inscriptions --}}
            <a href="{{ route('inscriptions.index') }}"
               class="group rounded-xl border border-gray-200 bg-gray-50 p-4 transition hover:border-green-300 hover:bg-green-50">

                <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-lg bg-green-100 text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 12h6M9 16h6M8 3h8l2 3v15H6V6l2-3zM9 3v3h6V3"/>
                    </svg>
                </div>

                <p class="text-sm font-bold text-gray-900">
                    Inscriptions
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Gérer les inscriptions scolaires
                </p>
            </a>


            {{-- Classes --}}
            <a href="{{ route('class_rooms.index') }}"
               class="group rounded-xl border border-gray-200 bg-gray-50 p-4 transition hover:border-indigo-300 hover:bg-indigo-50">

                <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M4 19V5a2 2 0 012-2h12a2 2 0 012 2v14M4 19h16M8 7h8M8 11h8M8 15h5"/>
                    </svg>
                </div>

                <p class="text-sm font-bold text-gray-900">
                    Classes
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Gérer les classes scolaires
                </p>
            </a>


            {{-- Enseignants --}}
            <a href="{{ route('teachers.index') }}"
               class="group rounded-xl border border-gray-200 bg-gray-50 p-4 transition hover:border-emerald-300 hover:bg-emerald-50">

                <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 14a5 5 0 100-10 5 5 0 000 10zM4 21a8 8 0 0116 0"/>
                    </svg>
                </div>

                <p class="text-sm font-bold text-gray-900">
                    Enseignants
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Gérer les enseignants
                </p>
            </a>


            {{-- Matières --}}
            <a href="{{ route('subjects.index') }}"
               class="group rounded-xl border border-gray-200 bg-gray-50 p-4 transition hover:border-purple-300 hover:bg-purple-50">

                <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-lg bg-purple-100 text-purple-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 6.5a4.5 4.5 0 00-4.5-4.5H3v16h4.5A4.5 4.5 0 0112 22M12 6.5A4.5 4.5 0 0116.5 2H21v16h-4.5A4.5 4.5 0 0012 22m0-15.5V22"/>
                    </svg>
                </div>

                <p class="text-sm font-bold text-gray-900">
                    Matières
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Gérer les matières scolaires
                </p>
            </a>


            {{-- Affectations --}}
            <a href="{{ route('teacher_subjects.index') }}"
               class="group rounded-xl border border-gray-200 bg-gray-50 p-4 transition hover:border-cyan-300 hover:bg-cyan-50">

                <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-lg bg-cyan-100 text-cyan-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 7h8M8 12h8M8 17h5M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                    </svg>
                </div>

                <p class="text-sm font-bold text-gray-900">
                    Affectations
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Enseignant / matière
                </p>
            </a>


            {{-- Emploi du temps --}}
            <a href="{{ route('emploi-du-temps.index') }}"
               class="group rounded-xl border border-gray-200 bg-gray-50 p-4 transition hover:border-orange-300 hover:bg-orange-50">

                <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-lg bg-orange-100 text-orange-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 2v4M16 2v4M3 10h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                    </svg>
                </div>

                <p class="text-sm font-bold text-gray-900">
                    Emploi du temps
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Planning scolaire
                </p>
            </a>


            {{-- Année scolaire --}}
            <a href="{{ route('school_years.index') }}"
               class="group rounded-xl border border-gray-200 bg-gray-50 p-4 transition hover:border-slate-400 hover:bg-slate-50">

                <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 2v4M16 2v4M3 10h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                    </svg>
                </div>

                <p class="text-sm font-bold text-gray-900">
                    Année scolaire
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Gérer les années scolaires
                </p>
            </a>

        </div>
    </div>


    {{-- ============================================================
        DERNIÈRES INSCRIPTIONS + DERNIERS ÉTUDIANTS
    ============================================================ --}}
    <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-2">

        {{-- Dernières inscriptions --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">

                <div>
                    <h2 class="font-bold text-gray-900">
                        Dernières inscriptions
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Les inscriptions les plus récentes
                    </p>
                </div>

                <a href="{{ route('inscriptions.index') }}"
                   class="text-sm font-semibold text-blue-800 hover:text-blue-600">
                    Tout voir
                </a>

            </div>

            <div class="divide-y divide-gray-100">

                @forelse($latestInscriptions as $inscription)

                    <div class="flex items-center justify-between gap-4 px-6 py-4">

                        <div class="min-w-0">

                            <p class="truncate text-sm font-semibold text-gray-900">
                                {{ $inscription->student->nom ?? 'Étudiant' }}
                                {{ $inscription->student->prenom ?? '' }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                {{ $inscription->classRoom->nom_classe ?? 'Classe non définie' }}
                            </p>

                        </div>

                        <div class="shrink-0 text-right">

                            <p class="text-xs font-medium text-gray-500">
                                {{ $inscription->date_inscription
                                    ? \Carbon\Carbon::parse($inscription->date_inscription)->format('d/m/Y')
                                    : '—' }}
                            </p>

                            @if($inscription->schoolYear)
                                <p class="mt-1 text-xs text-green-700">
                                    {{ $inscription->schoolYear->nom
                                        ?? $inscription->schoolYear->name
                                        ?? $inscription->schoolYear->annee_scolaire
                                        ?? '' }}
                                </p>
                            @endif

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-8 text-center">

                        <p class="text-sm text-gray-500">
                            Aucune inscription récente.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- Derniers étudiants --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">

                <div>
                    <h2 class="font-bold text-gray-900">
                        Derniers étudiants
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Les étudiants récemment enregistrés
                    </p>
                </div>

                <a href="{{ route('students.index') }}"
                   class="text-sm font-semibold text-blue-800 hover:text-blue-600">
                    Tout voir
                </a>

            </div>

            <div class="divide-y divide-gray-100">

                @forelse($latestStudents as $student)

                    <div class="flex items-center justify-between gap-4 px-6 py-4">

                        <div class="flex min-w-0 items-center gap-3">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-800">
                                {{ strtoupper(substr($student->nom ?? 'E', 0, 1)) }}
                            </div>

                            <div class="min-w-0">

                                <p class="truncate text-sm font-semibold text-gray-900">
                                    {{ $student->nom ?? 'Étudiant' }}
                                    {{ $student->prenom ?? '' }}
                                </p>

                                <p class="mt-1 truncate text-xs text-gray-500">
                                    {{ $student->matricule ?? 'Matricule non défini' }}
                                </p>

                            </div>

                        </div>

                        @if(isset($student->id))
                            <a href="{{ route('students.show', $student) }}"
                               class="shrink-0 text-sm font-semibold text-blue-800 hover:text-blue-600">
                                Voir
                            </a>
                        @endif

                    </div>

                @empty

                    <div class="px-6 py-8 text-center">

                        <p class="text-sm text-gray-500">
                            Aucun étudiant récent.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- ============================================================
        INFORMATIONS ÉTABLISSEMENT
    ============================================================ --}}
    <div class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

        <div class="mb-5">

            <h2 class="text-lg font-bold text-gray-900">
                Informations de l'établissement
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informations de l'établissement actuellement sélectionné
            </p>

        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">

            <div class="rounded-lg bg-gray-50 p-4">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Établissement
                </p>

                <p class="mt-2 text-sm font-bold text-gray-900">
                    {{ $establishment->nom ?? '—' }}
                </p>
            </div>

            <div class="rounded-lg bg-gray-50 p-4">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Type
                </p>

                <p class="mt-2 text-sm font-bold text-gray-900">
                    {{ $schoolTypeName ?? '—' }}
                </p>
            </div>

            <div class="rounded-lg bg-gray-50 p-4">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Téléphone
                </p>

                <p class="mt-2 text-sm font-bold text-gray-900">
                    {{ $establishment->telephone ?? '—' }}
                </p>
            </div>

            <div class="rounded-lg bg-gray-50 p-4">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Email
                </p>

                <p class="mt-2 break-all text-sm font-bold text-gray-900">
                    {{ $establishment->email ?? '—' }}
                </p>
            </div>

        </div>

    </div>


    {{-- ============================================================
        RAPPEL DU PÉRIMÈTRE DU SECRÉTAIRE
    ============================================================ --}}
    <div class="mt-6 rounded-xl border border-blue-200 bg-blue-50 p-5">

        <div class="flex items-start gap-3">

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-800">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z"/>
                </svg>

            </div>

            <div>
                <h3 class="text-sm font-bold text-blue-900">
                    Espace Secrétaire
                </h3>

                <p class="mt-1 text-sm leading-6 text-blue-800">
                    Cet espace est destiné aux tâches administratives et scolaires.
                    La gestion des notes est réservée aux enseignants autorisés.
                </p>
            </div>

        </div>

    </div>

</div>

@endsection
