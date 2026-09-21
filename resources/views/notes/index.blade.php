@extends('layouts.gestion-ecole')

@section('title', 'Notes')

@section('page-title', 'Gestion des notes')

@section('page-description', 'Gérez les notes et les évaluations des étudiants')

@section('content')

    {{-- Message de succès --}}
    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-green-500 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <div>
                    <p class="font-semibold">Opération réussie</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif


    {{-- Message d'erreur --}}
    @if(session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-red-500 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>

                <div>
                    <p class="font-semibold">Erreur</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif


    {{-- Erreurs de validation --}}
    @if($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-800 shadow-sm">
            <p class="mb-2 font-semibold">
                Veuillez corriger les erreurs suivantes :
            </p>

            <ul class="list-disc space-y-1 pl-5 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- En-tête --}}
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h2 class="text-lg font-bold text-blue-950">
                Liste des notes
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Consultez et gérez les notes attribuées aux étudiants.
            </p>
        </div>

        <a href="{{ route('notes.create') }}"
           class="inline-flex items-center justify-center rounded-xl bg-green-500 px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-green-600 hover:shadow-lg">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="mr-2 h-5 w-5"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 4v16m8-8H4"/>
            </svg>

            Ajouter une note
        </a>
    </div>


    {{-- Statistiques --}}
    <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Total notes --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Total notes
                    </p>

                    <p class="mt-2 text-3xl font-bold text-blue-950">
                        {{ $notes->count() }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

            </div>
        </div>


        {{-- Moyenne --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Moyenne générale
                    </p>

                    <p class="mt-2 text-3xl font-bold text-blue-950">
                        {{ number_format($notes->avg('note') ?? 0, 2) }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 17v-2m3 2v-4m3 4v-6m3 8H6a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2z"/>
                    </svg>
                </div>

            </div>
        </div>


        {{-- Étudiants évalués --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Étudiants évalués
                    </p>

                    <p class="mt-2 text-3xl font-bold text-blue-950">
                        {{ $notes->pluck('student_id')->filter()->unique()->count() }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-5a4 4 0 100-8 4 4 0 000 8zm6 2a3 3 0 10-6 0"/>
                    </svg>
                </div>

            </div>
        </div>


        {{-- Matières --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Matières évaluées
                    </p>

                    <p class="mt-2 text-3xl font-bold text-blue-950">
                        {{ $notes->pluck('subject_id')->filter()->unique()->count() }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
                    </svg>
                </div>

            </div>
        </div>

    </div>


    {{-- Tableau --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

        <div class="flex flex-col justify-between gap-3 border-b border-gray-100 px-6 py-5 sm:flex-row sm:items-center">

            <div>
                <h3 class="font-bold text-blue-950">
                    Notes enregistrées
                </h3>

                <p class="mt-1 text-xs text-gray-500">
                    {{ $notes->count() }} note(s) enregistrée(s)
                </p>
            </div>

            <div class="rounded-lg bg-blue-50 px-4 py-2 text-sm font-medium text-blue-800">
                Établissement #{{ session('establishment_id') }}
            </div>

        </div>


        @if($notes->count())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-100">

                    <thead class="bg-blue-950">
                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">
                                Étudiant
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">
                                Matière
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">
                                Note
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">
                                Coefficient
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">
                                Évaluation
                            </th>

                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-white">
                                Actions
                            </th>

                        </tr>
                    </thead>


                    <tbody class="divide-y divide-gray-100 bg-white">

                        @foreach($notes as $note)

                            <tr class="transition hover:bg-blue-50/50">

                                {{-- Étudiant --}}
                                <td class="whitespace-nowrap px-6 py-5">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-5 w-5"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M15 19a3 3 0 00-6 0m3-7a4 4 0 100-8 4 4 0 000 8z"/>
                                            </svg>

                                        </div>

                                        <div>

                                            @if($note->student)

                                                <p class="font-semibold text-blue-950">
                                                    {{ $note->student->nom }}
                                                    {{ $note->student->prenom }}
                                                </p>

                                                @if($note->student->matricule)
                                                    <p class="text-xs text-gray-500">
                                                        {{ $note->student->matricule }}
                                                    </p>
                                                @endif

                                            @else

                                                <p class="text-sm text-gray-400">
                                                    Étudiant introuvable
                                                </p>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- Matière --}}
                                <td class="whitespace-nowrap px-6 py-5">

                                    @if($note->subject)

                                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                                            {{ $note->subject->nom ?? $note->subject->name ?? 'Matière' }}
                                        </span>

                                    @else

                                        <span class="text-sm text-gray-400">
                                            Non définie
                                        </span>

                                    @endif

                                </td>


                                {{-- Note --}}
                                <td class="whitespace-nowrap px-6 py-5">

                                    @php
                                        $valeurNote = (float) $note->note;
                                    @endphp

                                    @if($valeurNote >= 10)

                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-bold text-green-800">
                                            {{ number_format($valeurNote, 2) }}/20
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-bold text-red-800">
                                            {{ number_format($valeurNote, 2) }}/20
                                        </span>

                                    @endif

                                </td>


                                {{-- Coefficient --}}
                                <td class="whitespace-nowrap px-6 py-5">

                                    <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                        {{ $note->coefficient ?? 1 }}
                                    </span>

                                </td>


                                {{-- Type évaluation --}}
                                <td class="whitespace-nowrap px-6 py-5">

                                    @if($note->type_evaluation)

                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                            {{ $note->type_evaluation }}
                                        </span>

                                    @else

                                        <span class="text-sm text-gray-400">
                                            Non défini
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="whitespace-nowrap px-6 py-5">

                                    <div class="flex items-center justify-center gap-2">

                                        <a href="{{ route('notes.show', $note) }}"
                                           class="rounded-lg bg-blue-100 px-3 py-2 text-sm font-medium text-blue-700 transition hover:bg-blue-200">
                                            Voir
                                        </a>

                                        <a href="{{ route('notes.edit', $note) }}"
                                           class="rounded-lg bg-yellow-100 px-3 py-2 text-sm font-medium text-yellow-700 transition hover:bg-yellow-200">
                                            Modifier
                                        </a>

                                        <form action="{{ route('notes.destroy', $note) }}"
                                              method="POST"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer cette note ?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="rounded-lg bg-red-100 px-3 py-2 text-sm font-medium text-red-700 transition hover:bg-red-200">
                                                Supprimer
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            {{-- Aucun résultat --}}
            <div class="px-6 py-16 text-center">

                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-blue-100 text-blue-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-10 w-10"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>

                </div>

                <h3 class="mt-5 text-lg font-bold text-blue-950">
                    Aucune note
                </h3>

                <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
                    Aucune note n'est encore enregistrée dans cet établissement.
                </p>

                <a href="{{ route('notes.create') }}"
                   class="mt-6 inline-flex items-center rounded-xl bg-green-500 px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-green-600">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="mr-2 h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 4v16m8-8H4"/>
                    </svg>

                    Ajouter la première note
                </a>

            </div>

        @endif

    </div>

@endsection
