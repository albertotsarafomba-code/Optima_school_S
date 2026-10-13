@extends('layouts.gestion-ecole')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('notes.index') }}"
                   class="hover:text-blue-700 transition">
                    Notes
                </a>

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M9 5l7 7-7 7"/>
                </svg>

                <span>Détail</span>
            </div>

            <h1 class="text-2xl font-bold text-gray-800">
                Détail de la note
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Consultation des informations de l'évaluation.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">

            {{-- Retour --}}
            <a href="{{ route('notes.index') }}"
               class="inline-flex items-center justify-center gap-2
                      px-4 py-2.5 rounded-lg
                      bg-gray-100 hover:bg-gray-200
                      text-gray-700 font-medium transition">

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

                Retour
            </a>

            @if(in_array(auth()->user()->role, ['admin', 'enseignant'], true))

            {{-- Modifier --}}
            <a href="{{ route('notes.edit', $note) }}"
               class="inline-flex items-center justify-center gap-2
                      px-4 py-2.5 rounded-lg
                      bg-blue-700 hover:bg-blue-800
                      text-white font-medium transition shadow-sm">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M16.862 4.487l1.687-1.688a2.121 2.121 0 013 3l-1.688 1.688M16.862 4.487L7.5 13.85a4.5 4.5 0 00-1.06 1.72L5.5 19.5l3.93-.94a4.5 4.5 0 001.72-1.06l9.36-9.36M16.862 4.487l3.688 3.688"/>
                </svg>

                Modifier
            </a>

            @endif

        </div>

    </div>


    {{-- =========================================================
         ETABLISSEMENT
    ========================================================== --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div class="flex items-center gap-3">

                <div class="w-11 h-11 rounded-lg
                            bg-blue-100 text-blue-700
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
                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                        Établissement
                    </p>

                    <p class="font-semibold text-gray-800">
                        {{ $note->establishment->nom ?? 'Non renseigné' }}
                    </p>
                </div>

            </div>

            {{-- Année --}}
            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-lg
                            bg-green-100 text-green-700
                            flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                    </svg>

                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                        Année scolaire
                    </p>

                    <p class="font-semibold text-gray-800">
                        {{ $note->schoolYear->name ?? 'Non renseignée' }}
                    </p>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         NOTE PRINCIPALE
    ========================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        {{-- Note --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">

            <div class="flex items-center justify-between mb-4">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Note obtenue
                    </p>

                    <p class="text-xs text-gray-400 mt-1">
                        Résultat de l'évaluation
                    </p>
                </div>

                <div class="w-11 h-11 rounded-lg
                            bg-green-100 text-green-700
                            flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>

                </div>

            </div>

            <div class="flex items-end gap-2">

                <span class="text-4xl font-bold text-blue-700">
                    {{ number_format((float) $note->note, 2, ',', ' ') }}
                </span>

                <span class="text-lg text-gray-500 mb-1">
                    / 20
                </span>

            </div>

            @php
                $noteValue = (float) $note->note;

                if ($noteValue >= 16) {
                    $mention = 'Très bien';
                } elseif ($noteValue >= 14) {
                    $mention = 'Bien';
                } elseif ($noteValue >= 12) {
                    $mention = 'Assez bien';
                } elseif ($noteValue >= 10) {
                    $mention = 'Passable';
                } else {
                    $mention = 'Insuffisant';
                }
            @endphp

            <div class="mt-4">

                <span class="inline-flex items-center
                             px-3 py-1.5 rounded-full
                             text-sm font-medium
                             bg-blue-50 text-blue-700">

                    {{ $mention }}

                </span>

            </div>

        </div>


        {{-- Coefficient --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">

            <div class="flex items-center justify-between mb-4">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Coefficient
                    </p>

                    <p class="text-xs text-gray-400 mt-1">
                        Pondération de l'évaluation
                    </p>
                </div>

                <div class="w-11 h-11 rounded-lg
                            bg-blue-100 text-blue-700
                            flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M7 20l10-16M6 8h.01M18 16h.01"/>
                    </svg>

                </div>

            </div>

            <div class="text-4xl font-bold text-gray-800">
                {{ number_format((float) ($note->coefficient ?? 1), 2, ',', ' ') }}
            </div>

        </div>


        {{-- Type évaluation --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">

            <div class="flex items-center justify-between mb-4">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Type d'évaluation
                    </p>

                    <p class="text-xs text-gray-400 mt-1">
                        Nature de l'épreuve
                    </p>
                </div>

                <div class="w-11 h-11 rounded-lg
                            bg-green-100 text-green-700
                            flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-5 7h6m-6 4h4"/>
                    </svg>

                </div>

            </div>

            <div class="text-xl font-semibold text-gray-800">
                {{ $note->type_evaluation ?: 'Non renseigné' }}
            </div>

        </div>

    </div>


    {{-- =========================================================
         INFORMATIONS ETUDIANT / CLASSE / MATIERE
    ========================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        {{-- Etudiant --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">

            <div class="px-6 py-4 border-b border-gray-100">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-lg
                                bg-blue-100 text-blue-700
                                flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zm7-1a4 4 0 100-8 4 4 0 000 8zm-3 4h3a4 4 0 014 4v1"/>
                        </svg>

                    </div>

                    <div>
                        <h2 class="font-semibold text-gray-800">
                            Étudiant
                        </h2>

                        <p class="text-xs text-gray-500">
                            Informations de l'étudiant
                        </p>
                    </div>

                </div>

            </div>

            <div class="p-6">

                <div class="mb-4">

                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                        Nom complet
                    </p>

                    <p class="text-lg font-semibold text-gray-800 mt-1">
                        {{ $note->student->nom ?? 'Non renseigné' }}
                        {{ $note->student->prenom ?? '' }}
                    </p>

                </div>

                @if(isset($note->student->matricule))

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">
                            Matricule
                        </p>

                        <p class="font-medium text-gray-700 mt-1">
                            {{ $note->student->matricule }}
                        </p>
                    </div>

                @endif

            </div>

        </div>


        {{-- Classe --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">

            <div class="px-6 py-4 border-b border-gray-100">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-lg
                                bg-green-100 text-green-700
                                flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5"
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
                        <h2 class="font-semibold text-gray-800">
                            Classe
                        </h2>

                        <p class="text-xs text-gray-500">
                            Classe concernée
                        </p>
                    </div>

                </div>

            </div>

            <div class="p-6">

                <p class="text-lg font-semibold text-gray-800">
                    {{ $note->classRoom->nom_classe ?? 'Non renseignée' }}
                </p>

                @if($note->classRoom?->niveau)

                    <p class="text-sm text-gray-500 mt-1">
                        Niveau : {{ $note->classRoom->niveau }}
                    </p>

                @endif

                @if($note->classRoom?->filiere)

                    <p class="text-sm text-gray-500 mt-1">
                        Filière : {{ $note->classRoom->filiere }}
                    </p>

                @endif

            </div>

        </div>


        {{-- Matière --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">

            <div class="px-6 py-4 border-b border-gray-100">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-lg
                                bg-blue-100 text-blue-700
                                flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
                        </svg>

                    </div>

                    <div>
                        <h2 class="font-semibold text-gray-800">
                            Matière
                        </h2>

                        <p class="text-xs text-gray-500">
                            Matière évaluée
                        </p>
                    </div>

                </div>

            </div>

            <div class="p-6">

                <p class="text-lg font-semibold text-gray-800">
                    {{ $note->subject->nom ?? 'Non renseignée' }}
                </p>

                @if($note->subject?->code)

                    <p class="text-sm text-gray-500 mt-1">
                        Code : {{ $note->subject->code }}
                    </p>

                @endif

            </div>

        </div>


        {{-- Enseignant --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">

            <div class="px-6 py-4 border-b border-gray-100">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-lg
                                bg-green-100 text-green-700
                                flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 14a5 5 0 100-10 5 5 0 000 10zm-7 7a7 7 0 0114 0"/>
                        </svg>

                    </div>

                    <div>
                        <h2 class="font-semibold text-gray-800">
                            Enseignant
                        </h2>

                        <p class="text-xs text-gray-500">
                            Enseignant associé
                        </p>
                    </div>

                </div>

            </div>

            <div class="p-6">

                @if($note->teacher)

                    <p class="text-lg font-semibold text-gray-800">
                        {{ $note->teacher->nom }}
                        {{ $note->teacher->prenom }}
                    </p>

                    @if($note->teacher->specialite)

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $note->teacher->specialite }}
                        </p>

                    @endif

                @else

                    <p class="text-gray-400">
                        Aucun enseignant renseigné.
                    </p>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
         DATE EVALUATION
    ========================================================== --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">

        <div class="px-6 py-4 border-b border-gray-100">

            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-lg
                            bg-blue-100 text-blue-700
                            flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                    </svg>

                </div>

                <div>
                    <h2 class="font-semibold text-gray-800">
                        Date de l'évaluation
                    </h2>

                    <p class="text-xs text-gray-500">
                        Date à laquelle la note a été enregistrée
                    </p>
                </div>

            </div>

        </div>

        <div class="p-6">

            <p class="text-gray-700 font-medium">
                @if($note->date_evaluation)
                    {{ $note->date_evaluation->format('d/m/Y') }}
                @else
                    Non renseignée
                @endif
            </p>

        </div>

    </div>


    @if(in_array(auth()->user()->role, ['admin', 'enseignant'], true))

    {{-- =========================================================
         ACTIONS
    ========================================================== --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">

        <div class="flex flex-col sm:flex-row
                    sm:items-center sm:justify-between gap-4">

            <div>

                <p class="font-semibold text-gray-800">
                    Actions
                </p>

                <p class="text-sm text-gray-500 mt-1">
                    Modifier ou supprimer cette note.
                </p>

            </div>

            <div class="flex flex-wrap gap-3">

                <a href="{{ route('notes.edit', $note) }}"
                   class="inline-flex items-center justify-center gap-2
                          px-4 py-2.5 rounded-lg
                          bg-blue-700 hover:bg-blue-800
                          text-white font-medium transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M16.862 4.487l1.687-1.688a2.121 2.121 0 013 3l-1.688 1.688M16.862 4.487L7.5 13.85a4.5 4.5 0 00-1.06 1.72L5.5 19.5l3.93-.94a4.5 4.5 0 001.72-1.06l9.36-9.36"/>
                    </svg>

                    Modifier la note
                </a>

                <form action="{{ route('notes.destroy', $note) }}"
                      method="POST"
                      onsubmit="return confirm('Voulez-vous vraiment supprimer cette note ?');">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2
                                   px-4 py-2.5 rounded-lg
                                   bg-red-50 hover:bg-red-100
                                   text-red-700 font-medium transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h12"/>
                        </svg>

                        Supprimer
                    </button>

                </form>

            </div>

        </div>

    </div>

    @endif

</div>

@endsection
