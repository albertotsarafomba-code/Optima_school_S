@extends('layouts.gestion-ecole')

@section('title', 'Modifier un horaire')

@section('content')

<div class="mx-auto max-w-4xl space-y-6">

    {{-- EN-TÊTE --}}
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-blue-100">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-2xl font-bold text-blue-900">
                    Modifier un horaire
                </h1>

                <p class="mt-1 text-sm text-gray-600">
                    Modifiez les informations de ce cours.
                </p>
            </div>

            <a
                href="{{ route('emploi-du-temps.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-blue-200 bg-white px-5 py-3 text-sm font-bold text-blue-900 transition hover:bg-blue-50"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M10 19l-7-7m0 0l7-7m-7 7h20"
                    />
                </svg>

                Retour
            </a>

        </div>

    </div>


    {{-- ERREURS --}}
    @if ($errors->any())

        <div class="rounded-2xl border border-red-200 bg-red-50 p-5">

            <div class="flex gap-3">

                <svg
                    class="h-6 w-6 shrink-0 text-red-600"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v4m0 4h.01M10.29 3.86l-7.1 12.28A2 2 0 004.93 19h14.14a2 2 0 001.74-2.86l-7.1-12.28a2 2 0 00-3.42 0z"
                    />
                </svg>

                <div>

                    <p class="font-bold text-red-800">
                        Vérifiez les informations saisies.
                    </p>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- FORMULAIRE --}}
    <form
        method="POST"
        action="{{ route('emploi-du-temps.update', $emploi) }}"
        class="space-y-6"
    >

        @csrf
        @method('PUT')


        {{-- INFORMATIONS DU COURS --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-blue-100">

            <h2 class="mb-5 text-lg font-bold text-blue-900">
                Informations du cours
            </h2>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                {{-- ANNÉE SCOLAIRE --}}
                <div>

                    <label
                        for="school_year_id"
                        class="mb-2 block text-sm font-semibold text-blue-900"
                    >
                        Année scolaire
                    </label>

                    <select
                        id="school_year_id"
                        name="school_year_id"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        required
                    >

                        @foreach($schoolYears as $schoolYear)

                            <option
                                value="{{ $schoolYear->id }}"
                                @selected((string) old('school_year_id', $emploi->school_year_id) === (string) $schoolYear->id)
                            >
                                {{ $schoolYear->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- CLASSE --}}
                <div>

                    <label
                        for="class_room_id"
                        class="mb-2 block text-sm font-semibold text-blue-900"
                    >
                        Classe
                    </label>

                    <select
                        id="class_room_id"
                        name="class_room_id"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        required
                    >

                        <option value="">
                            Sélectionner une classe
                        </option>

                        @foreach($classRooms as $classRoom)

                            <option
                                value="{{ $classRoom->id }}"
                                @selected((string) old('class_room_id', $emploi->class_room_id) === (string) $classRoom->id)
                            >
                                {{ $classRoom->nom_classe }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- MATIÈRE --}}
                <div>

                    <label
                        for="subject_id"
                        class="mb-2 block text-sm font-semibold text-blue-900"
                    >
                        Matière
                    </label>

                    <select
                        id="subject_id"
                        name="subject_id"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        required
                    >

                        <option value="">
                            Sélectionner une matière
                        </option>

                        @foreach($subjects as $subject)

                            <option
                                value="{{ $subject->id }}"
                                @selected((string) old('subject_id', $emploi->subject_id) === (string) $subject->id)
                            >
                                {{ $subject->nom }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ENSEIGNANT --}}
                <div>

                    <label
                        for="teacher_id"
                        class="mb-2 block text-sm font-semibold text-blue-900"
                    >
                        Enseignant
                    </label>

                    <select
                        id="teacher_id"
                        name="teacher_id"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        required
                    >

                        <option value="">
                            Sélectionner un enseignant
                        </option>

                        @foreach($teachers as $teacher)

                            <option
                                value="{{ $teacher->id }}"
                                @selected((string) old('teacher_id', $emploi->teacher_id) === (string) $teacher->id)
                            >
                                {{ trim($teacher->prenom . ' ' . $teacher->nom) }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- SALLE --}}
                <div>

                    <label
                        for="salle_id"
                        class="mb-2 block text-sm font-semibold text-blue-900"
                    >
                        Salle
                    </label>

                    <select
                        id="salle_id"
                        name="salle_id"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        required
                    >

                        <option value="">
                            Sélectionner une salle
                        </option>

                        @foreach($salles as $salle)

                            <option
                                value="{{ $salle->id }}"
                                @selected((string) old('salle_id', $emploi->salle_id) === (string) $salle->id)
                            >
                                {{ $salle->nom }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- JOUR --}}
                <div>

                    <label
                        for="jour"
                        class="mb-2 block text-sm font-semibold text-blue-900"
                    >
                        Jour
                    </label>

                    <select
                        id="jour"
                        name="jour"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        required
                    >

                        <option value="">
                            Sélectionner un jour
                        </option>

                        @foreach($jours as $jour)

                            <option
                                value="{{ $jour }}"
                                @selected(old('jour', $emploi->jour) === $jour)
                            >
                                {{ $jour }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- HEURE DÉBUT --}}
                <div>

                    <label
                        for="heure_debut"
                        class="mb-2 block text-sm font-semibold text-blue-900"
                    >
                        Heure de début
                    </label>

                    <input
                        type="time"
                        id="heure_debut"
                        name="heure_debut"
                        value="{{ old('heure_debut', $emploi->heure_debut?->format('H:i')) }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        required
                    >

                </div>


                {{-- HEURE FIN --}}
                <div>

                    <label
                        for="heure_fin"
                        class="mb-2 block text-sm font-semibold text-blue-900"
                    >
                        Heure de fin
                    </label>

                    <input
                        type="time"
                        id="heure_fin"
                        name="heure_fin"
                        value="{{ old('heure_fin', $emploi->heure_fin?->format('H:i')) }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        required
                    >

                </div>

            </div>

        </div>


        {{-- ACTIONS --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

            <a
                href="{{ route('emploi-du-temps.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-6 py-3 text-sm font-bold text-gray-700 transition hover:bg-gray-50"
            >
                Annuler
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-800"
            >

                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 13l4 4L19 7"
                    />
                </svg>

                Enregistrer les modifications

            </button>

        </div>

    </form>

</div>

@endsection
